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
                <h1 class="page-header">Hola <?= htmlspecialchars($name) ?></h1>
                <h3>¿Qué haremos hoy...?</h3>

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
                <th>Por Acabarse (<5)</th>
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
            </div>
        </div>
    </div>
</body>
</html>