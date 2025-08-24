<?php
$justificantes = $justificantes ?? [];

$absUploads = realpath(dirname(__DIR__, 2) . '/storage/uploads/justificantes/');

$webUploads = BASE_URL . 'verarchivo.php?file=';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver Justificantes</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
        }

        table {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 16px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            border: 1px solid #dddddd;
            padding: 12px;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #555;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: rgb(186, 226, 252);
        }

        table caption {
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Lista de Justificantes</h1>
                <hr>

                <?php if (!empty($justificantes)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Auditor</th>
                                <th>Evidencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($justificantes as $j): ?>
                                <?php
                                $nombreArchivo = $j['evidencia'] ?? '';
                                $archivoServidor = $absUploads . DIRECTORY_SEPARATOR . $nombreArchivo;
                                $existeArchivo = file_exists($archivoServidor);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($j['id_justificante']) ?></td>
                                    <td><?= htmlspecialchars($j['nombre'] ?? 'Desconocido') ?></td>
                                    <td><?= htmlspecialchars($j['fecha']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($j['descripcion'])) ?></td>
                                    <td><?= htmlspecialchars($j['auditor']) ?></td>
                                    <td>
                                        <?php if ($existeArchivo): ?>
                                            <a href="<?= $webUploads . urlencode($nombreArchivo) ?>" target="_blank"
                                                class="btn btn-primary btn-sm">Ver justificante</a>
                                        <?php else: ?>
                                            <span class="text-muted">Sin archivo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay justificantes registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
</body>

</html>