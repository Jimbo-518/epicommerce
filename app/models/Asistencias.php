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
        // ========= Helpers de tiempo =========
        $mkTime = function ($hms) {
            // Retorna DateTime en fecha base 1970-01-01
            return DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hms) ?: new DateTime('1970-01-01 00:00:00');
        };
        $sec = function ($hms) use ($mkTime) {
            return $mkTime($hms)->getTimestamp();
        };
        $toHMS = function ($seconds) {
            if ($seconds < 0)
                $seconds = 0;
            return gmdate('H:i:s', (int) $seconds);
        };
        $addSeconds = function ($hms, $add) use ($mkTime) {
            $dt = $mkTime($hms);
            $dt->modify(($add >= 0 ? '+' : '') . $add . ' seconds');
            return $dt->format('H:i:s');
        };
        $diffSec = function ($hmsA, $hmsB) use ($mkTime) {
            return $mkTime($hmsA)->getTimestamp() - $mkTime($hmsB)->getTimestamp();
        };
        $parseMinutesOrHMS = function ($value) {
            // Si viene numérico => minutos. Si viene HH:MM(:SS) => lo paso a minutos.
            if ($value === null || $value === '')
                return 0;
            if (is_numeric($value))
                return (int) $value;
            // HH:MM o HH:MM:SS
            $parts = explode(':', $value);
            if (count($parts) >= 2) {
                $h = (int) $parts[0];
                $m = (int) $parts[1];
                $s = isset($parts[2]) ? (int) $parts[2] : 0;
                return (int) round(($h * 3600 + $m * 60 + $s) / 60);
            }
            return 0;
        };
        $reduceNearDuplicates = function (array $times, int $thresholdSeconds = 60) use ($sec) {
            // Ordena y elimina "toques" con diferencia <= threshold, quedándose con el primero.
            sort($times);
            $res = [];
            $lastKept = null;
            foreach ($times as $t) {
                if ($lastKept === null) {
                    $res[] = $t;
                    $lastKept = $t;
                } else {
                    if (abs($sec($t) - $sec($lastKept)) > $thresholdSeconds) {
                        $res[] = $t;
                        $lastKept = $t;
                    }
                }
            }
            return $res;
        };
        $closestPairAround = function (array $times, string $centerHMS, int $windowMinutes = 60) use ($sec) {
            // Busca dos registros (salida/entrada) alrededor de una hora objetivo dentro de una ventana.
            // Devuelve [outTime, inTime] o [null, null] si no es posible.
            if (empty($times))
                return [null, null];
            $centerS = $sec($centerHMS);
            $windowS = $windowMinutes * 60;

            // candidatos dentro de ventana
            $cand = array_values(array_filter($times, function ($t) use ($sec, $centerS, $windowS) {
                return abs($sec($t) - $centerS) <= $windowS;
            }));
            if (count($cand) < 2) {
                // Con 1 o 0 no podemos medir duración; lo trataremos como inconsistencia
                return [null, null];
            }
            // Heurística: el primero como "salida" al evento, el siguiente como "entrada" al evento
            sort($cand);
            // Buscar el punto de ruptura más cercano al centro
            $bestIdx = null;
            $bestDelta = PHP_INT_MAX;
            for ($i = 0; $i < count($cand) - 1; $i++) {
                $mid = (int) (($sec($cand[$i]) + $sec($cand[$i + 1])) / 2);
                $delta = abs($mid - $centerS);
                if ($delta < $bestDelta) {
                    $bestDelta = $delta;
                    $bestIdx = $i;
                }
            }
            if ($bestIdx === null)
                return [null, null];
            return [$cand[$bestIdx], $cand[$bestIdx + 1]];
        };

        $db = Database::getConnection();

        // ========== 1) CARGA BASE ==========
        // departamento_edificio + nombres
        $deptosEdificiosStmt = $db->query("
        SELECT de.id_depto_edificio, d.departamento, e.edificio, e.id_edificio
        FROM departamento_edificio de
        JOIN departamentos d ON de.id_depto = d.id_depto
        JOIN edificios e ON de.id_edificio = e.id_edificio
        ORDER BY d.departamento, e.edificio
    ");
        $deptosEdificios = $deptosEdificiosStmt->fetchAll(PDO::FETCH_ASSOC);

        // Edificios (para hojas de Desglose)
        $edificiosStmt = $db->query("SELECT id_edificio, edificio FROM edificios ORDER BY edificio");
        $edificios = $edificiosStmt->fetchAll(PDO::FETCH_ASSOC);

        // Horarios indexados por [id_depto_edificio][id_dia]
        $horariosStmt = $db->query("SELECT * FROM horarios");
        $horarios = [];
        while ($row = $horariosStmt->fetch(PDO::FETCH_ASSOC)) {
            $horarios[$row['id_depto_edificio']][$row['id_dia']] = $row;
        }

        // Justificantes en rango
        $justificantesStmt = $db->prepare("SELECT id_empleado, fecha FROM justificantes WHERE fecha BETWEEN ? AND ?");
        $justificantesStmt->execute([$dateInit, $datefin]);
        $justificantes = [];
        while ($row = $justificantesStmt->fetch(PDO::FETCH_ASSOC)) {
            $justificantes[$row['id_empleado']][$row['fecha']] = true;
        }

        // ========== 2) CREAR DOCUMENTO ==========
        $spreadsheet = new Spreadsheet();

        // Para controlar el nombre de hoja (límite 31 chars)
        $safeSheetTitle = function ($name) {
            $name = preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '-', $name);
            return mb_substr($name, 0, 31);
        };

        // ========== 3) HOJAS RESUMEN (una por cada departamento_edificio) ==========
        $firstSheet = true;
        foreach ($deptosEdificios as $de) {
            $idDE = (int) $de['id_depto_edificio'];
            $nombreResumen = $de['departamento'] . ' - ' . $de['edificio'];

            $sheet = $firstSheet ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $firstSheet = false;
            $sheet->setTitle($safeSheetTitle($nombreResumen));

            // Encabezados
            $headers = [
                'ID EMPLEADO',
                'NOMBRE',
                'DÍAS TRABAJADOS',
                'RETARDOS',
                'TIEMPO RETARDOS',
                'FALTAS',
                'JUSTIFICANTES',
                'DÍAS TIEMPO EXTRA',
                'TIEMPO EXTRA',
                'INCONSISTENCIAS',
                'TIEMPO INCONSISTENCIAS'
            ];
            $col = 'A';
            foreach ($headers as $h) {
                $sheet->setCellValue($col . '1', $h);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }

            // Empleados del depto_edificio
            $empleadosStmt = $db->prepare("SELECT id_empleado, nombre FROM empleados WHERE id_depto_edificio = ?");
            $empleadosStmt->execute([$idDE]);

            $rowIdx = 2;
            while ($emp = $empleadosStmt->fetch(PDO::FETCH_ASSOC)) {
                $idEmpleado = (int) $emp['id_empleado'];

                $resumen = [
                    'diasTrabajados' => 0,
                    'retardos' => 0,
                    'tiempo_retardos' => 0,
                    'diasExtra' => 0,
                    'tiempo_extra' => 0,
                    'inconsistencias' => 0,
                    'tiempo_inconsistencias' => 0,
                ];
                $faltas = 0;
                $justificantesCount = 0;

                // Pre-cargar asistencias del empleado en rango
                $asisStmt = $db->prepare("
                SELECT fecha, hora
                FROM asistencias
                WHERE id_empleado = ? AND fecha BETWEEN ? AND ?
                ORDER BY fecha, hora
            ");
                $asisStmt->execute([$idEmpleado, $dateInit, $datefin]);
                $asisAll = $asisStmt->fetchAll(PDO::FETCH_ASSOC);

                // Agrupar por fecha y reducir duplicados
                $asisPorDia = [];
                foreach ($asisAll as $a) {
                    $f = $a['fecha'];
                    $asisPorDia[$f][] = $a['hora'];
                }
                foreach ($asisPorDia as $f => $arr) {
                    $asisPorDia[$f] = $reduceNearDuplicates($arr, 60);
                }

                $dIni = new DateTime($dateInit);
                $dFin = new DateTime($datefin);
                while ($dIni <= $dFin) {
                    $fechaStr = $dIni->format('Y-m-d');
                    $diaN = (int) $dIni->format('N'); // 1=Mon..7=Sun

                    // horario del depto_edificio para ese día
                    $hor = $horarios[$idDE][$diaN] ?? null;
                    if ($hor) {
                        $registros = $asisPorDia[$fechaStr] ?? [];

                        if (!empty($registros)) {
                            // Hay trabajo ese día
                            $resumen['diasTrabajados']++;

                            // ---- Entrada con tolerancia ----
                            $tolSeconds = $diffTol = 0;
                            // tolerancia como HH:MM:SS -> segundos
                            $tolSeconds = $diffTol = ($hor['tolerancia'] ?? '00:00:00') ? $sec($hor['tolerancia']) - $sec('00:00:00') : 0;
                            $horaEntradaTol = $addSeconds($hor['entrada'], $tolSeconds);

                            $primera = $registros[0];
                            if ($diffSec($primera, $horaEntradaTol) > 0) {
                                // llegó después de entrada+tolerancia
                                $resumen['retardos']++;
                                $resumen['tiempo_retardos'] += $diffSec($primera, $horaEntradaTol);
                            }

                            // ---- Descanso (opcional) ----
                            if (!empty($hor['descanso'])) {
                                $rangoDescMin = $parseMinutesOrHMS($hor['rango_descanso'] ?? 15);
                                [$outDesc, $inDesc] = $closestPairAround($registros, $hor['descanso'], max(30, $rangoDescMin)); // ventana generosa
                                if ($outDesc && $inDesc) {
                                    $durDesc = $diffSec($inDesc, $outDesc); // cuánto duró el descanso
                                    $permitido = $rangoDescMin * 60;
                                    if ($durDesc > $permitido) {
                                        $exceso = $durDesc - $permitido;
                                        $resumen['inconsistencias']++;
                                        $resumen['tiempo_inconsistencias'] += $exceso; // retardoBreak
                                    }
                                } else {
                                    // Falta algún toque de descanso => inconsistencia
                                    $resumen['inconsistencias']++;
                                    // No sumamos tiempo porque no hay pares claros
                                }
                            }

                            // ---- Comida (1 hora) ----
                            if (!empty($hor['comida'])) {
                                [$outCom, $inCom] = $closestPairAround($registros, $hor['comida'], 90); // ventana 90 min
                                if ($outCom && $inCom) {
                                    $durCom = $diffSec($inCom, $outCom);
                                    $permitido = 60 * 60; // 1 hora
                                    if ($durCom > $permitido) {
                                        $exceso = $durCom - $permitido;
                                        $resumen['inconsistencias']++;
                                        $resumen['tiempo_inconsistencias'] += $exceso; // retardoComida
                                    }
                                } else {
                                    $resumen['inconsistencias']++;
                                }
                            }

                            // ---- Salida (extra) RevisarYo----
                            $ultima = end($registros);
                            if ($diffSec($ultima, $hor['salida']) > 0) {
                                // se quedó más allá de salida
                                $resumen['diasExtra']++;
                                $resumen['tiempo_extra'] += $diffSec($ultima, $hor['salida']);
                            }

                        } else {
                            // Día laborable sin registros => falta (ver justificante)
                            $faltas++;
                            if (isset($justificantes[$idEmpleado][$fechaStr])) {
                                $justificantesCount++;
                            }
                        }
                    }
                    $dIni->modify('+1 day');
                }

                // Escribir fila
                $sheet->setCellValue('A' . $rowIdx, $idEmpleado);
                $sheet->setCellValue('B' . $rowIdx, $emp['nombre']);
                $sheet->setCellValue('C' . $rowIdx, $resumen['diasTrabajados']);
                $sheet->setCellValue('D' . $rowIdx, $resumen['retardos']);
                $sheet->setCellValue('E' . $rowIdx, $toHMS($resumen['tiempo_retardos']));
                $sheet->setCellValue('F' . $rowIdx, $faltas);
                $sheet->setCellValue('G' . $rowIdx, $justificantesCount);
                $sheet->setCellValue('H' . $rowIdx, $resumen['diasExtra']);
                $sheet->setCellValue('I' . $rowIdx, $toHMS($resumen['tiempo_extra']));
                $sheet->setCellValue('J' . $rowIdx, $resumen['inconsistencias']);
                $sheet->setCellValue('K' . $rowIdx, $toHMS($resumen['tiempo_inconsistencias']));
                $rowIdx++;
            }

            // Auto-size
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // ========== 4) HOJAS DESGLOSE (una por cada edificio) ==========
        foreach ($edificios as $ed) {
            $idEdificio = (int) $ed['id_edificio'];
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($safeSheetTitle('Desglose - ' . $ed['edificio']));

            // Encabezados
            $headers = ['ID', 'NOMBRE', 'FECHA', 'HORA', 'DESCRIPCIÓN', 'TIEMPO EXTRA', 'TIEMPO FALTANTE'];
            $col = 'A';
            foreach ($headers as $h) {
                $sheet->setCellValue($col . '1', $h);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }

            // Traer todos los registros de asistencias de empleados cuyo depto_edificio pertenezca a este edificio
            // y en el rango de fechas
            $asDesStmt = $db->prepare("
            SELECT a.id_empleado, e.nombre, a.fecha, a.hora, de.id_depto_edificio, d.departamento, edf.edificio
            FROM asistencias a
            JOIN empleados e ON a.id_empleado = e.id_empleado
            JOIN departamento_edificio de ON e.id_depto_edificio = de.id_depto_edificio
            JOIN edificios edf ON de.id_edificio = edf.id_edificio
            JOIN departamentos d ON de.id_depto = d.id_depto
            WHERE edf.id_edificio = ? AND a.fecha BETWEEN ? AND ?
            ORDER BY a.fecha, a.hora, a.id_empleado
        ");
            $asDesStmt->execute([$idEdificio, $dateInit, $datefin]);
            $raw = $asDesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Agrupar por empleado+fecha para poder determinar descripciones (entrada/salida/descanso/comida)
            $byEmpDate = [];
            foreach ($raw as $r) {
                $key = $r['id_empleado'] . '|' . $r['fecha'] . '|' . $r['id_depto_edificio'];
                $byEmpDate[$key]['meta'] = [
                    'id_empleado' => $r['id_empleado'],
                    'nombre' => $r['nombre'],
                    'fecha' => $r['fecha'],
                    'id_depto_edificio' => $r['id_depto_edificio'],
                ];
                $byEmpDate[$key]['horas'][] = $r['hora'];
            }

            $rowIdx = 2;
            foreach ($byEmpDate as $pack) {
                $meta = $pack['meta'];
                $horas = $reduceNearDuplicates($pack['horas'], 60);
                sort($horas);
                $fechaStr = $meta['fecha'];

                // Determinar horario para esa fecha
                $diaN = (int) DateTime::createFromFormat('Y-m-d', $fechaStr)->format('N');
                $hor = $horarios[$meta['id_depto_edificio']][$diaN] ?? null;

                // Precalculos
                $descanso = $hor['descanso'] ?? null;
                $rangoDescMin = $parseMinutesOrHMS($hor['rango_descanso'] ?? 15);
                $comida = $hor['comida'] ?? null;
                $entradaTol = $hor ? (function ($addSeconds, $hor) {
                    $tol = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hor['tolerancia']);
                    $base = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 00:00:00');
                    $tolSec = ($tol && $base) ? $tol->getTimestamp() - $base->getTimestamp() : 0;
                    return $addSeconds($hor['entrada'], $tolSec);
                })($addSeconds, $hor) : null;
                // Guardar para el posible manejo de salidas antes de tiempo $salidaTolNeg10 = $hor ? $addSeconds($hor['salida'], -600) : null;

                // Encontrar pares alrededor de descanso / comida para etiquetar
                $descOutIn = [null, null];
                $comOutIn = [null, null];
                if ($hor) {
                    if (!empty($descanso)) {
                        $descOutIn = $closestPairAround($horas, $descanso, max(30, $rangoDescMin));
                    }
                    if (!empty($comida)) {
                        $comOutIn = $closestPairAround($horas, $comida, 90);
                    }
                }

                // Emisión de filas: por cada hora del día, con su etiqueta y tiempos
                foreach ($horas as $idx => $h) {
                    $descripcion = '';
                    $tiempoExtra = 0;
                    $tiempoFaltante = 0;

                    if ($hor) {
                        // Entrada: primer registro del día
                        if ($idx === 0) {
                            $descripcion = 'Entrada';
                            if ($entradaTol && $diffSec($h, $entradaTol) > 0) {
                                $tiempoFaltante = $diffSec($h, $entradaTol);
                            }
                        }
                        // Salida: último registro del día
                        if ($idx === count($horas) - 1) {
                            $descripcion = $descripcion ? $descripcion . ' / Salida' : 'Salida';
                            if ($hor['salida'] && $diffSec($h, $hor['salida']) > 0) {
                                $tiempoExtra = $diffSec($h, $hor['salida']);
                            }
                        }

                        // Descanso etiquetas
                        if ($descOutIn[0] && $descOutIn[1]) {
                            if ($h === $descOutIn[0]) {
                                $descripcion = $descripcion ? $descripcion . ' / Salida descanso' : 'Salida descanso';
                            } elseif ($h === $descOutIn[1]) {
                                $descripcion = $descripcion ? $descripcion . ' / Regreso descanso' : 'Regreso descanso';
                            }
                        }

                        // Comida etiquetas
                        if ($comOutIn[0] && $comOutIn[1]) {
                            if ($h === $comOutIn[0]) {
                                $descripcion = $descripcion ? $descripcion . ' / Salida comida' : 'Salida comida';
                            } elseif ($h === $comOutIn[1]) {
                                $descripcion = $descripcion ? $descripcion . ' / Regreso comida' : 'Regreso comida';
                            }
                        }
                    }

                    if ($descripcion === '') {
                        // Si no pudimos clasificar, lo dejamos vacío o "Otro"
                        $descripcion = 'Otro';
                    }

                    $sheet->setCellValue('A' . $rowIdx, $meta['id_empleado']);
                    $sheet->setCellValue('B' . $rowIdx, $meta['nombre']);
                    $sheet->setCellValue('C' . $rowIdx, $fechaStr);
                    $sheet->setCellValue('D' . $rowIdx, $h);
                    $sheet->setCellValue('E' . $rowIdx, $descripcion);
                    $sheet->setCellValue('F' . $rowIdx, $toHMS($tiempoExtra));
                    $sheet->setCellValue('G' . $rowIdx, $toHMS($tiempoFaltante));
                    $rowIdx++;
                }
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // ========== 5) HOJAS CALENDARIO (una por cada depto_edificio) ==========
        foreach ($deptosEdificios as $de) {
            $idDE = (int) $de['id_depto_edificio'];
            $nombreCalendario = 'Calendario - ' . $de['departamento'] . ' - ' . $de['edificio'];

            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($safeSheetTitle($nombreCalendario));

            // ----- Rango de Fechas -----
            $dates = [];
            $start = new DateTime($dateInit);
            $end = new DateTime($datefin);
            while ($start <= $end) {
                $dates[] = $start->format('Y-m-d');
                $start->modify('+1 day');
            }

            // ----- Encabezados -----
            $sheet->setCellValue('A1', 'COLABORADOR');
            $sheet->setCellValue('B1', 'ID');
            $colIndex = 'C';
            foreach ($dates as $date) {
                $sheet->setCellValue($colIndex . '1', (new DateTime($date))->format('d'));
                $colIndex++;
            }

            // ----- Traer Empleados -----
            $empleadosStmt = $db->prepare("SELECT id_empleado, nombre, id_depto_edificio 
                                   FROM empleados 
                                   WHERE id_depto_edificio = ? 
                                   ORDER BY nombre");
            $empleadosStmt->execute([$idDE]);
            $empleados = $empleadosStmt->fetchAll(PDO::FETCH_ASSOC);

            // ----- Asistencias -----
            $asisStmt = $db->prepare("
        SELECT id_empleado, fecha, hora 
        FROM asistencias
        WHERE fecha BETWEEN ? AND ? AND id_empleado IN (
            SELECT id_empleado FROM empleados WHERE id_depto_edificio = ?
        )
    ");
            $asisStmt->execute([$dateInit, $datefin, $idDE]);
            $asistencias = $asisStmt->fetchAll(PDO::FETCH_ASSOC);
            $asistMap = [];
            foreach ($asistencias as $a) {
                $asistMap[$a['id_empleado']][$a['fecha']][] = $a['hora']; // <-- guardamos todos los registros
            }

            // ----- Justificantes -----
            $justStmt = $db->prepare("
        SELECT id_empleado, fecha
        FROM justificantes
        WHERE fecha BETWEEN ? AND ? AND id_empleado IN (
            SELECT id_empleado FROM empleados WHERE id_depto_edificio = ?
        )
    ");
            $justStmt->execute([$dateInit, $datefin, $idDE]);
            $justificantes = $justStmt->fetchAll(PDO::FETCH_ASSOC);
            $justMap = [];
            foreach ($justificantes as $j) {
                $justMap[$j['id_empleado']][$j['fecha']] = true;
            }

            // ----- Estilos -----
            $styleA = ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => '00FF00']]]; // Verde 
            $styleLate = ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'FFA500']]]; // Naranja 
            $styleF = ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'FF0000']]]; // Rojo 
            $styleJ = ['fill' => ['fillType' => 'solid', 'color' => ['rgb' => '0000FF']]]; // Azul

            // ----- Llenar Datos -----
            $row = 2;
            foreach ($empleados as $emp) {
                $sheet->setCellValue('A' . $row, $emp['nombre']);
                $sheet->setCellValue('B' . $row, $emp['id_empleado']);

                $col = 'C';
                foreach ($dates as $date) {
                    $dayIndex = (int) (new DateTime($date))->format('N'); // 1=Lun..7=Dom
                    $cellValue = '';
                    $style = $styleF; // Default Falta

                    $hor = $horarios[$idDE][$dayIndex] ?? null;
                    if (!$hor) {
                        $cellValue = '';
                        $style = null;
                    } else {
                        $horaEntrada = $hor['entrada'];
                        $tol = $sec($hor['tolerancia']) - $sec('00:00:00');
                        $horaLimite = $addSeconds($horaEntrada, $tol);

                        if (isset($asistMap[$emp['id_empleado']][$date])) {
                            // Tomar el primer registro del día
                            $horaAsistencia = min($asistMap[$emp['id_empleado']][$date]);
                            if ($diffSec($horaAsistencia, $horaLimite) > 0) {
                                $cellValue = $horaAsistencia; // Retardo
                                $style = $styleLate;
                            } else {
                                $cellValue = 'A'; // Asistencia puntual
                                $style = $styleA;
                            }
                        } elseif (isset($justMap[$emp['id_empleado']][$date])) {
                            $cellValue = 'J'; // Justificado
                            $style = $styleJ;
                        } else {
                            $cellValue = 'F'; // Falta
                            $style = $styleF;
                        }
                    }

                    $sheet->setCellValue($col . $row, $cellValue);
                    if ($style) {
                        $sheet->getStyle($col . $row)->applyFromArray($style);
                    }
                    $col++;
                }
                $row++;
            }

            // Ajustar ancho columnas
            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // ========== 6) EXPORTAR ==========
        // Ponemos como primera hoja la del primer Resumen
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Reporte_Asistencias.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
?>