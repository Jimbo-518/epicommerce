<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">

    <style>
        .form-container {
            margin-top: 0;
            padding: 30px;
            color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            margin-bottom: 20px;
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

        .title-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 0;
            text-align: center;
            color: white;
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
                <h1>Editar Empleado</h1>
            </div>
            <br><br>
            <div class="form-container" style="background-color: #292F39;">
                <h2 class="form-header text-center">Modifique los datos y guarde los cambios</h2>
                <form action="<?= BASE_URL ?>editarEmpleado/editarUsuario" method="POST">
                    <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($empleadodata['id_empleado']) ?>">

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                               value="<?= htmlspecialchars($empleadodata['nombre']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="edificio">Edificio:</label>
                        <select name="edificio" id="edificio" class="form-control" required>
                            <option value="">-- Seleccione un edificio --</option>
                            <?php foreach ($edificioslist as $edif): ?>
                                <option value="<?= htmlspecialchars($edif['edificio']) ?>"
                                    <?= ($edif['edificio'] === $info_adicional['edificio']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($edif['edificio']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="area">Área:</label>
                        <select name="area" id="area" class="form-control" required>
                            <option value="">-- Seleccione un área --</option>
                            <?php foreach ($areaslist as $ar): ?>
                                <option value="<?= htmlspecialchars($ar['departamento']) ?>"
                                    <?= ($ar['departamento'] === $info_adicional['departamento']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ar['departamento']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="vacaciones">Vacaciones:</label>
                        <input type="number" id="vacaciones" name="vacaciones" class="form-control"
                               value="<?= htmlspecialchars($empleadodata['vacaciones']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-custom btn-block">Guardar cambios</button>
                    <a href="<?= BASE_URL ?>verempleados/verempleados" class="btn btn-back btn-block">Regresar</a>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>