<?php
$message = Session::get('message');
$error_message = Session::get('error_message');

Session::remove('message');
Session::remove('error_message');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>INVENTARIO ECOMMERCE</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
</head>

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Hola <?= htmlspecialchars($name); ?></h1>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <?php /* Inicio de apartado para bodega */ if ($edificio == "cedis"):;?>
                        <div class="row placeholders">
                            <div class="col-xs-12 col-sm-3 placeholder">
                                <a href="<?= BASE_URL ?>buscador">
                                    <img src="./assets/img/TRUElogo.png" width="200" height="200" class="img-responsive"
                                        alt="Generic placeholder thumbnail">
                                </a>
                                <h4>TRUE RELIGION</h4>
                                <span class="text-muted">Encuentre sus prendas con más facilidad</span>
                            </div>

                            <div class="col-xs-12 col-sm-3 placeholder">
                                <a href="<?= BASE_URL ?>buscador">
                                    <img src="./assets/img/js.png" width="200" height="200" class="img-responsive"
                                        alt="Generic placeholder thumbnail">
                                </a>
                                <h4>JOE'S</h4>
                                <span class="text-muted">Encuentre sus prendas con más facilidad</span>
                            </div>
                        </div>

                        <h1 class="sub-header">Estadísticas</h1>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelos</th>
                                    <th>Total Prendas</th>
                                    <th>Por Acabarse (<5) </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventario as $marca): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($marca['Marca']) ?></td>
                                        <td><?= htmlspecialchars($marca['total_modelos']) ?></td>
                                        <td><?= htmlspecialchars($marca['total_prendas']) ?></td>
                                        <td><?= htmlspecialchars($marca['por_acabarse']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                <?php /* Fin de apartado para bodega */ else:;
                /* Inicio de apartado para Administración */?>
                    <div class="row">
                        <div class="col-md-3">
                            <center>
                                <img src="./assets/img/xlsx.png" alt="ícono de documento excel" height="150px">
                                <hr>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalDescargarReporte">
                                    Subir Asistencias
                                </button>
                            </center>
                        </div>
                        <div class="col-md-3">
                            <center>
                                <img src="./assets/img/carga-en-la-nube.png" alt="ícono de documento por subir"
                                    height="150px">
                                <hr>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalSubirAsistencias">
                                    Subir Asistencias
                                </button>
                            </center>
                        </div>
                    </div>

                    <!-- Modal Subir Asistencias -->
                    <div class="modal fade" id="modalSubirAsistencias" tabindex="-1"
                        aria-labelledby="modalSubirAsistenciasLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalSubirAsistenciasLabel">Subir archivo de asistencias
                                    </h5>
                                </div>

                                <div class="modal-body">
                                    <form action="<?= BASE_URL ?>dashboard/pantallaCarga" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="archivoAsistencias" class="form-label">Selecciona archivo
                                                Excel</label>
                                            <input class="form-control" type="file" id="archivo_dat" name="archivo_dat"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Subir</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Descargar Reporte -->
                    <div class="modal fade" id="modalDescargarReporte" tabindex="-1" aria-labelledby="modalDescargarReporte"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDescargarReporte">Selecioné las fechas para el informe
                                    </h5>
                                </div>

                                <div class="modal-body">
                                    <form action="<?= BASE_URL ?>dashboard/generarInforme" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="archivoAsistencias" class="form-label">Desde</label>
                                            <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio"
                                                required>
                                            <label for="archivoAsistencias" class="form-label">Hasta</label>
                                            <input class="form-control" type="date" id="fecha_fin" name="fecha_fin"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Descargar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; /* Fin de apartado para administración */?>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>assets/js/jquery.min.js.descarga"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js.descarga"></script>

</body>

</html>