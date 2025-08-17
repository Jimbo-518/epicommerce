<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>INVENTARIO ECOMMERCE</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
</head>

<style>
    table {
        width: 100%;
        max-width: 900px;
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

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <h1>Lista de empleados: </h1>
                <hr>

                <?php if (!empty($empleados)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Edificio</th>
                                <th>Área</th>
                                <th>Vacaciones</th>
                                <th>Reporte</th>
                                <th>-</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($empleados as $empleado): ?>
                                <tr>
                                    <td><?= htmlspecialchars($empleado['id_empleado']) ?></td>
                                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                                    <td><?= htmlspecialchars($empleado['edificio']) ?></td>
                                    <td><?= htmlspecialchars($empleado['area']) ?></td>
                                    <td><?= htmlspecialchars($empleado['vacaciones']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btnAbrirModal" data-toggle="modal"
                                            data-target="#modalDescargarPDF"
                                            data-id="<?= $empleado['id_empleado'] ?>">PDF</button>
                                    </td>

                                    <td>
                                        <a href="<?= BASE_URL ?>editarEmpleado/editarempleado?id=<?= $empleado['id_empleado'] ?>"
                                            class="btn btn-info">Editar</a>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>bajaEmpleado?id=<?= $empleado['id_empleado'] ?>"
                                            class="btn btn-warning">Baja</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                <?php else: ?>
                    <p>No hay empleados registrados.</p>
                <?php endif; ?>

                <!-- Modal Descargar Reporte -->
                <div class="modal fade" id="modalDescargarPDF" tabindex="-1" aria-labelledby="modalDescargarPDF"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDescargarPDF">Selecioné las fechas para el Reporte
                                </h5>
                            </div>

                            <div class="modal-body">
                                <form action="<?= BASE_URL ?>verempleados/generarReporte" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="hidden" name="id_empleado" id="id_empleado" value="">

                                        <label for="archivoAsistencias" class="form-label">Desde</label>
                                        <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio"
                                            required>
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
            </div>
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