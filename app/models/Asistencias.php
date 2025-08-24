<?php
require_once dirname(__DIR__, 2) . '/config/Database.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Asistencias
{
    public static function generarReporte($userInfo, $info_adicional, $fecha_inicio, $fecha_fin)
    {
        $empleado = $userInfo;

        $db = Database::getConnection();
        $stmt = $db->prepare("
    SELECT fecha, hora
    FROM asistencias
    WHERE id_empleado = :id_empleado
      AND fecha BETWEEN :fecha_inicio AND :fecha_fin
    ORDER BY fecha ASC, hora ASC
");

        $stmt->execute([
            'id_empleado' => $empleado['id_empleado'],
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reorganizar: fecha => [horas...]
        $asistenciasPorDia = [];
        foreach ($asistencias as $row) {
            $fecha = $row['fecha'];
            if (!isset($asistenciasPorDia[$fecha])) {
                $asistenciasPorDia[$fecha] = [];
            }
            $asistenciasPorDia[$fecha][] = $row['hora'];
        }
        $vacaciones = self::calcularVacaciones($empleado['fecha_ingreso']);

        ob_start();
        require __DIR__ . '/../views/reportes/empleado.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'Portrait');
        $dompdf->render();

        $dompdf->stream('empleado_' . $empleado['id_empleado'] . '.pdf', ['Attachment' => false]);
    }

    public static function calcularVacaciones($fecha_ingreso)
    {
        try {
            // Establecer la zona horaria para evitar advertencias
            date_default_timezone_set('America/Mexico_City');

            // Crear objetos DateTime para la fecha de ingreso y la fecha actual
            $start = new DateTime($fecha_ingreso);
            $end = new DateTime();

            // Calcular la diferencia entre las dos fechas
            $interval = $start->diff($end);

            // Obtener los años de servicio
            $yearsOfService = $interval->y;

            if ($yearsOfService < 1) {
                $vacationDays = "Aún no tienes derecho a vacaciones. Debes completar 1 año";
                return $vacationDays;
            } elseif ($yearsOfService >= 1 && $yearsOfService <= 5) {
                // 12 días base + 2 días por cada año adicional hasta el 5to año
                $vacationDays = 12 + ($yearsOfService - 1) * 2;
                return $vacationDays;
            } else {
                $vacationDays = 20;
                $additionalYears = $yearsOfService - 5;
                $additionalBlocks = floor($additionalYears / 5);
                $vacationDays += $additionalBlocks * 2;
                return $vacationDays;
            }
        } catch (Exception $e) {
            return "Error: Formato de fecha de ingreso inválido. Por favor, usa el formato YYYY-MM-DD.";
        }

    }

    public static function procesarAsistencias($ruta)
    {
        // Verificamos que el archivo exista
        if (!file_exists($ruta)) {
            return false;
        }

        // Leemos el archivo .dat
        $handle = fopen($ruta, "r");
        if (!$handle) {
            return false;
        }

        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        while (($linea = fgets($handle)) !== false) {
            $linea = trim($linea);
            if ($linea === '') {
                continue;
            }

            // separar por espacios o tabs
            $cols = preg_split('/\s+/', $linea);

            $id_empleado = $cols[0] ?? '';
            $fecha = $cols[1] ?? '';
            $hora = $cols[2] ?? '';

            if (!$id_empleado || !$fecha || !$hora) {
                continue;
            }

            // Evitar duplicados exactos (id_empleado + fecha + hora)
            $stmt = $db->prepare("
            SELECT COUNT(*) FROM asistencias
            WHERE id_empleado = :id_empleado 
              AND fecha = :fecha 
              AND hora = :hora
        ");
            $stmt->execute([
                ':id_empleado' => $id_empleado,
                ':fecha' => $fecha,
                ':hora' => $hora
            ]);

            if ($stmt->fetchColumn() > 0) {
                continue; // ya existe, saltamos
            }

            // Insertar registro
            $stmt = $db->prepare("
            INSERT INTO asistencias (id_empleado, fecha, hora)
            VALUES (:id_empleado, :fecha, :hora)
        ");
            $stmt->execute([
                ':id_empleado' => $id_empleado,
                ':fecha' => $fecha,
                ':hora' => $hora
            ]);
        }

        fclose($handle);
        return true;
    }


    public static function generarExcel($dateInit, $datefin)
    {
        $db = Database::getConnection();

        // --- FECHAS DE REFERENCIA --- 
        $fechaInicio = $dateInit;
        $fechaFin = $datefin;

        $spreadsheet = new Spreadsheet();

        // =======================
        // HOJA 1: RESUMEN GENERAL
        // =======================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle("Resumen General");

        $columnasResumen = ['EMPLEADO', 'DÍAS TRABAJADOS', 'RETARDOS', 'FALTAS', 'ALERTAS'];
        $colIndex = 'A';
        foreach ($columnasResumen as $titulo) {
            $sheet1->setCellValue($colIndex . '1', $titulo);
            $colIndex++;
        }
        $sheet1->getStyle('A1:' . chr(ord('A') + count($columnasResumen) - 1) . '1')->getFont()->setBold(true);

        // --- Consulta de empleados ---
        $empleadosStmt = $db->query("SELECT id_empleado, nombre FROM empleados");
        $fila = 2;
        while ($empleado = $empleadosStmt->fetch(PDO::FETCH_ASSOC)) {
            $idEmpleado = $empleado['id_empleado'];
            $nombre = $empleado['nombre'];

            // Consulta de asistencias en los últimos 15 días
            $asistenciasStmt = $db->prepare("
            SELECT fecha, entrada, alerta 
            FROM asistencias 
            WHERE id_empleado = ? 
              AND fecha BETWEEN ? AND ?
        ");
            $asistenciasStmt->execute([$idEmpleado, $fechaInicio, $fechaFin]);
            $asistencias = $asistenciasStmt->fetchAll(PDO::FETCH_ASSOC);

            $diasTrabajados = count($asistencias);

            // Contar retardos (llegada después de 08:30:00)
            $retardos = 0;
            $alertas = 0;
            foreach ($asistencias as $a) {
                if ($a['entrada'] > '08:30:00')
                    $retardos++;
                if ($a['alerta'])
                    $alertas++;
            }

            // Faltas = días totales (11) - días trabajados
            $faltas = 11 - $diasTrabajados;

            // Llenar hoja
            $sheet1->setCellValue('A' . $fila, $nombre);
            $sheet1->setCellValue('B' . $fila, $diasTrabajados);
            $sheet1->setCellValue('C' . $fila, $retardos);
            $sheet1->setCellValue('D' . $fila, $faltas);
            $sheet1->setCellValue('E' . $fila, $alertas > 0 ? 'Sí' : 'No');

            $fila++;
        }

        // =========================
        // HOJA 2: DETALLE ASISTENCIAS
        // =========================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle("Detalle Asistencias");

        $columnasDetalle = ['EMPLEADO', 'FECHA', 'ENTRADA', 'SALIDA_COMIDA', 'ENTRADA_COMIDA', 'SALIDA', 'ALERTA'];
        $colIndex = 'A';
        foreach ($columnasDetalle as $titulo) {
            $sheet2->setCellValue($colIndex . '1', $titulo);
            $colIndex++;
        }
        $sheet2->getStyle('A1:' . chr(ord('A') + count($columnasDetalle) - 1) . '1')->getFont()->setBold(true);

        // Consulta todas las asistencias con nombre del empleado
        $detalleStmt = $db->query("
        SELECT e.nombre AS empleado, a.fecha, a.entrada, a.salida_comida, a.entrada_comida, a.salida, a.alerta
        FROM asistencias a
        INNER JOIN empleados e ON a.id_empleado = e.id_empleado
        WHERE a.fecha BETWEEN '$fechaInicio' AND '$fechaFin'
        ORDER BY a.fecha, e.nombre
    ");

        $fila = 2;
        while ($registro = $detalleStmt->fetch(PDO::FETCH_ASSOC)) {
            $colIndex = 'A';
            foreach ($columnasDetalle as $campo) {
                if ($campo == 'ALERTA') {
                    $sheet2->setCellValue($colIndex . $fila, $registro['alerta'] ? 'Sí' : 'No');
                } else {
                    $sheet2->setCellValue($colIndex . $fila, $registro[strtolower($campo)]);
                }
                $colIndex++;
            }
            $fila++;
        }

        // =======================
        // EXPORTAR EXCEL
        // =======================
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Reporte_Asistencias.xls"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;

    }
}
?>