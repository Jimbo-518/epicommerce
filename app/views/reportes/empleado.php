<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleado</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="row">
        <div class="col-xs-6">
            <br><br><br><br>
            <h1>Reporte de Asistencias</h1>
        </div>
        <div class="col-xs-6">
            <br>
            <img src="<?php echo BASE_URL; ?>assets/img/logoCole.png" alt="logo de COLE" height="200px">
     </div>
    </div>

    <table class="table table-striped">
        <tr>
            <th>Nombre</th>
            <td><?= htmlspecialchars($empleado['nombre']) ?></td>
        </tr>
        <tr>
            <th>Vacaciones restantes</th>
            <td><?= htmlspecialchars($empleado['vacaciones']) ?></td>
        </tr>
    </table>
</body>
</html>