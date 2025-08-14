<?php
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Usuario
{
    public static function verificarCredenciales($username, $password)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public static function listaempleados()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados");
        $stmt->execute();
        $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario;
        }
        return false;
    }

    public static function registrar($id, $nombre, $edificio, $area, $vacaciones, $usuario, $password, $creador)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO empleados (id_empleado, nombre, edificio, area, vacaciones, usuario, password, creador) 
            VALUES (:id_empleado, :nombre, :edificio, :area, :vacaciones, :usuario, :password, :creador)
        ");
        return $stmt->execute([
            'id_empleado' => $id,
            'nombre' => $nombre,
            'edificio' => $edificio,
            'area' => $area,
            'vacaciones' => $vacaciones,
            'usuario' => $usuario,
            'password' => $password,
            'creador' => $creador
        ]);
    }

    public static function obtenerUsuarioPorNombre($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :Usuario LIMIT 1");
        $stmt->bindParam(':Usuario', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function pushUserID($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE id_empleado = :id_empleado LIMIT 1");
        $stmt->bindParam(':id_empleado', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generarReporte($userInfo)
    {
        ob_start();
        $empleado = $userInfo;
        require __DIR__ . '/../views/reportes/empleado.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'Portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('empleado_' . $empleado['id_empleado'] . '.pdf', ['Attachment' => false]);
    }

    public static function procesarAsistencias($ruta)
    {
        // Verificamos que el archivo exista
        if (!file_exists($ruta)) {
            return false;
        }

        $asistencias = [];

        // Leemos el archivo .dat
        $handle = fopen($ruta, "r");
        if (!$handle)
            return false;

        while (($linea = fgets($handle)) !== false) {
            $linea = trim($linea);
            if ($linea === '')
                continue;

            $cols = preg_split('/\s+/', $linea);

            $id_empleado = $cols[0] ?? '';
            $fecha = $cols[1] ?? '';
            $hora = $cols[2] ?? '';

            if (!$id_empleado || !$fecha || !$hora)
                continue;

            // Agrupar por empleado y fecha
            $asistencias[$id_empleado][$fecha][] = $hora;
        }
        fclose($handle);

        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Procesar registros
        foreach ($asistencias as $id_empleado => $dias) {
            foreach ($dias as $fecha => $horas) {
                sort($horas);

                // Filtrar duplicados (< 1 min de diferencia)
                $filtradas = [];
                $ultima_hora = null;
                foreach ($horas as $hora) {
                    if ($ultima_hora) {
                        $diff = abs(strtotime("$fecha $hora") - strtotime("$fecha $ultima_hora"));
                        if ($diff < 60)
                            continue;
                    }
                    $filtradas[] = $hora;
                    $ultima_hora = $hora;
                }

                // Asignar tiempos
                $entrada = $filtradas[0] ?? null;
                $salida_comida = $filtradas[1] ?? null;
                $entrada_comida = $filtradas[2] ?? null;
                $salida = $filtradas[3] ?? null;

                // Calcular alerta
                $alerta = 0;
                $dia_semana = date('N', strtotime($fecha)); // 1 = Lunes, 5 = Viernes

                if ($dia_semana == 5) {
                    // Viernes: solo se esperan 2 registros (entrada y salida)
                    if (count($filtradas) < 2) {
                        $alerta = 1;
                    }
                } else {
                    // Otros días: se esperan 4 registros (entrada, salida comida, entrada comida, salida)
                    if (count($filtradas) < 4) {
                        $alerta = 1;
                    } elseif ($salida_comida && $entrada_comida) {
                        $diff_comida = abs(strtotime("$fecha $entrada_comida") - strtotime("$fecha $salida_comida"));
                        if ($diff_comida > 3600) { // más de 1 hora
                            $alerta = 1;
                        }
                    }
                }

                // Insertar en la BD evitando duplicados
                $stmt = $db->prepare("
                SELECT COUNT(*) FROM asistencias
                WHERE id_empleado = :id_empleado AND fecha = :fecha
            ");
                $stmt->execute([
                    ':id_empleado' => $id_empleado,
                    ':fecha' => $fecha
                ]);
                if ($stmt->fetchColumn() > 0) {
                    // Ya existe, por lo tanto: saltar
                    continue;
                }

                $stmt = $db->prepare("
                INSERT INTO asistencias (id_empleado, fecha, entrada, salida_comida, entrada_comida, salida, alerta)
                VALUES (:id_empleado, :fecha, :entrada, :salida_comida, :entrada_comida, :salida, :alerta)
            ");
                $stmt->execute([
                    ':id_empleado' => $id_empleado,
                    ':fecha' => $fecha,
                    ':entrada' => $entrada,
                    ':salida_comida' => $salida_comida,
                    ':entrada_comida' => $entrada_comida,
                    ':salida' => $salida,
                    ':alerta' => $alerta
                ]);
            }
        }

        return true;
    }

    public static function generarExcel()
    {
        $db = Database::getConnection();

        // --- FECHAS DE REFERENCIA ---
        $hoy = new DateTime();
        $fechaInicio = $hoy->sub(new DateInterval('P15D'))->format('Y-m-d'); // 15 días atrás
        $fechaFin = (new DateTime())->format('Y-m-d');

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