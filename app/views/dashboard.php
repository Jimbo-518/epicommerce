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

            <!--Aquí inicia el contenido específico del área de bodega -->
            <div style="display: <?php echo $ocultarbloque = ($edificio != "cedis")? "none": ""; ?>;">
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
                </div><!--Aquí termina el contenido específico del área de bodega -->
            <!-- Comienza el contenido para el área administrativa--> 
            <div class="row">
                <div class="col-md-3">
                    <center>
                    <img src="./assets/img/xlsx.png" alt="ícono de documento excel" height="150px">
                    <hr>
                    <a href="<?= BASE_URL ?>usuario/generarInforme" class="btn btn-primary">Descargar Informe</a>
                    </center>
                </div>
                <div class="col-md-3">
                    <center>
                    <img src="./assets/img/carga-en-la-nube.png" alt="ícono de documento excel por subir" height="150px">
                    <hr>
                    <a href="<?= BASE_URL ?>usuario/generarInforme" class="btn btn-primary">Subir Asistencias</a>
                    </center>
                </div>
            </div>
            <!--Aquí termina el contenido específico del área administrativa -->
            </div>
        </div>
    </div>
</body>
</html>