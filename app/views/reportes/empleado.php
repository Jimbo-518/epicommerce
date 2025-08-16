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
            <p>Desde <?= htmlspecialchars($fecha_inicio) ?> hasta <?= htmlspecialchars($fecha_fin) ?></p>
        </div>
        <div class="col-xs-6">
            <br>
            <img src="<?= BASE_URL ?>assets/img/logoCole.png" alt="logo de COLE" height="200px">
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

    <h3>Asistencias</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Entrada</th>
                <th>Entrada comida</th>
                <th>Salida comida</th>
                <th>Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($asistencias)): ?>
                <?php foreach ($asistencias as $asis): ?>
                    <tr>
                        <td><?= htmlspecialchars($asis['fecha']) ?></td>
                        <td><?= htmlspecialchars($asis['entrada']) ?></td>
                        <td><?= htmlspecialchars($asis['entrada_comida']) ?></td>
                        <td><?= htmlspecialchars($asis['salida_comida']) ?></td>
                        <td><?= htmlspecialchars($asis['salida']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay registros en este rango de fechas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
