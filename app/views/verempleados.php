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
      background-color:rgb(186, 226, 252);
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
                                    <form action="<?= BASE_URL ?>verempleados/generarReporte" method="post">
                                    <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($empleado['id_empleado']) ?>">
                                    <td><?= htmlspecialchars($empleado['id_empleado']) ?></td>
                                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                                    <td><?= htmlspecialchars($empleado['edificio']) ?></td>
                                    <td><?= htmlspecialchars($empleado['area']) ?></td>
                                    <td><?= htmlspecialchars($empleado['vacaciones']) ?></td>
                                    <td><button type="isset" class="btn btn-danger">PDF</button></td>
                                    <td><button class="btn btn-info">EDITAR</button></td>
                                    <td><button class="btn btn-warning">BAJA</button></td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay empleados registrados.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>

</html>