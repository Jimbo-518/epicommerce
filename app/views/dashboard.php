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
                <div class="row placeholders">
<!-- ------------------------------------------------------------------------------------ -->
                    <?php if (in_array("buscador", $widgets)): ?>
                        <div class="col-xs-12 col-sm-3 placeholder">
                            <a href="<?= BASE_URL ?>buscador">
                                <img src="./assets/img/busqueda.png" width="150" class="img-responsive"
                                    alt="Generic placeholder thumbnail">
                            </a>
                            <hr>
                            <a class="btn btn-primary" href="<?= BASE_URL ?>buscador"> Buscador </a>
                        </div>
                    <?php endif; ?>
<!-- ------------------------------------------------------------------------------------ -->
                    <?php if (in_array("misasistencias", $widgets)): ?>
                        <div class="col-md-3">
                            <center>
                                <img src="./assets/img/fecha-del-calendario.png" alt="ícono de documento por subir"
                                    height="150px">
                                <hr>
                                <button type="button" class="btn btn-primary btnAbrirModal" data-toggle="modal"
                                    data-target="#modalDescargarAsistencias" data-id="<?php echo $id_empleado; ?>"> Mis
                                    Asistencias </button>
                            </center>
                        </div>

                        <!-- Modal Ver mis Asistencias -->
                        <div class="modal fade" id="modalDescargarAsistencias" tabindex="-1"
                            aria-labelledby="modalDescargarAsistencias" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDescargarAsistencias">Selecioné las fechas para el
                                            Reporte
                                        </h5>
                                    </div>

                                    <div class="modal-body">
                                        <form action="<?= BASE_URL ?>verempleados/generarReporte" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="form-group">
                                                <input type="hidden" name="id_empleado" id="id_empleado" value="">

                                                <label for="archivoAsistencias" class="form-label">Desde</label>
                                                <input class="form-control" type="date" id="fecha_inicio"
                                                    name="fecha_inicio" required>
                                                <label for="archivoAsistencias" class="form-label">Hasta</label>
                                                <input class="form-control" type="date" id="fecha_fin" name="fecha_fin"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Ver</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
<!-- ------------------------------------------------------------------------------------ -->
                    <?php if (in_array("upasistencias", $widgets)): ?>
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
                    <?php endif; ?>
<!-- ------------------------------------------------------------------------------------ -->
                    <?php if (in_array("reporte", $widgets)): ?>
                        <div class="col-md-3">
                            <center>
                                <img src="./assets/img/xlsx.png" alt="ícono de documento excel" height="150px">
                                <hr>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalDescargarReporte">
                                    Descargar Reporte
                                </button>
                            </center>
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
                </div>
            <?php endif; ?>
<!-- ------------------------------------------------------------------------------------ -->
            <?php if (in_array("estadisticas", $widgets)): ?>
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
            <?php endif; ?>
        </div>
    </div>
    <script src="<?= BASE_URL ?>assets/js/jquery.min.js.descarga"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js.descarga"></script>
    <script>
        $(document).on("click", ".btnAbrirModal", function () {
            var id = $(this).data("id");
            $("#id_empleado").val(id);
        });
    </script>
</body>
</html>