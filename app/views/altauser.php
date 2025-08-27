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
    <title>Alta de Usuario</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
</head>

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="containercontainer-fluid">
        <div class="row">

            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1>Alta de nuevo Usuario</h1>
                <hr>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <form method="POST" action="altauser/registrar">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="id" class="form-label">ID en el Checador</label>
                            <input class="form-control form-group" type="number" id="id" name="id" required>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre completo del empleado</label>
                            <input class="form-control form-group" type="text" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="user" class="form-label">Usuario a usar</label>
                            <input class="form-control form-group" type="text" id="user" name="user" required>
                        </div>
                        <div class="col-md-6">
                            <label for="psswrd" class="form-label">Contraseña</label>
                            <input class="form-control form-group" type="password" id="psswrd" name="psswrd"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="edificio">Edificio:</label>
                            <select name="edificio" id="edificio" class="form-control form-group" required>
                                <option value="">-- Seleccione un edificio --</option>
                                <?php foreach ($edificioslist as $edif): ?>
                                    <option value="<?= htmlspecialchars($edif['edificio']) ?>">
                                        <?= htmlspecialchars($edif['edificio']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="area">Área o departamento:</label>
                            <select name="area" id="area" class="form-control form-group" required>
                                <option value="">-- Seleccione un área --</option>
                                <?php foreach ($areaslist as $ar): ?>
                                    <option value="<?= htmlspecialchars($ar['departamento']) ?>">
                                        <?= htmlspecialchars($ar['departamento']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="vacaciones" class="form-label">Días de vacaciones tomados:</label>
                            <input class="form-control form-group" type="number" id="vacaciones" name="vacaciones"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_ingreso" class="form-label">Fecha de ingreso:</label>
                            <input class="form-control form-group" type="date" id="fecha_ingreso" name="fecha_ingreso"
                                required>
                        </div>
                    </div>
                                    <br>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <button class="btn btn-primary form-control" type="submit">AGREGAR</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (!empty($message)): ?>
                alert("<?= htmlspecialchars($message) ?>");
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                alert("<?= htmlspecialchars($error_message) ?>");
            <?php endif; ?>
        });
    </script>

    <script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
</body>

</html>