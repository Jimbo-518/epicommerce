<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Baja de Empleado</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">

    <style>
        .form-container {
            margin-top: 0;
            padding: 30px;
            color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #292F39;
        }

        .title-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 0;
            text-align: center;
            color: white;
        }

        .btn-custom {
            background-color: rgb(14, 36, 138);
            border: none;
            transition: background-color 0.5s, color 0.5s;
        }

        .btn-custom:hover {
            background-color: rgb(121, 133, 247);
            color: white;
        }

        .btn-back {
            background-color: black;
            color: white;
            border: 1px solid black;
            transition: background-color 0.4s, color 0.4s, border-color 0.4s;
        }

        .btn-back:hover {
            background-color: white;
            color: black;
            border-color: black;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <div class="title-container">
                    <h1>Baja de Empleado</h1>
                </div>
                <br><br>

                <div class="form-container">
                    <h2 class="form-header text-center">Confirmar Baja</h2>
                    <p class="text-center">
                        ¿Está seguro que desea dar de baja al empleado
                        <strong><?= htmlspecialchars($empleado['nombre']) ?></strong>?
                    </p>

                    
                    <center>
                    <form method="post" action="<?= BASE_URL ?>bajaEmpleado/bajaUsuario">
                        <input type="hidden" name="id_empleado"
                            value="<?= htmlspecialchars($empleado['id_empleado']) ?>">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-danger" style="flex: 0 0 48%;">Sí, eliminar</button>
                            <a href="<?= BASE_URL ?>verempleados/verempleados" class="btn btn-back"
                                style="flex: 0 0 48%;">No, regresar</a>
                        </div>
                    </form>
                    </center>
                    <br>
                </div>
            </div>
        </div>
    </div>

</body>

</html>