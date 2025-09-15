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
    <title>Registrar Justificante</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/altauser.css" rel="stylesheet">

    <style>
        .selection {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
        }

        .selection:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.9);
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.9);
        }

        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>
    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1>Registrar Justificante</h1>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>justificantes/store" method="POST" enctype="multipart/form-data">
                    <label for="id_empleado">Empleado</label>
                    <input type="text" list="empleadoslist" placeholder="Busque un empleado" id="nombre_empleado"
                        class="selection" required>
                    <input type="hidden" name="id_empleado" id="id_empleado_hidden">

                    <datalist id="empleadoslist">
                        <option value="todos">Todos</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <option data-id="<?= $empleado['id_empleado'] ?>"
                                value="<?= htmlspecialchars($empleado['nombre']) ?>">
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                    </input>
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" required>

                    <label for="descripcion">Tipo</label>
                    <select name="descripcion" id="descripcion" class="selection" required>
                        <option value="vacaciones">Vacaciones</option>
                        <option value="permiso">Permiso</option>
                        <option value="medico">Médico</option>
                        <option value="home">Home Office</option>
                    </select>

                    <label for="evidencia">Evidencia</label>
                    <input type="file" name="evidencia" id="evidencia" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                    <input type="hidden" name="edificio" value="<?= htmlspecialchars($edificio); ?>">
                    <input type="hidden" name="creator" value="<?= htmlspecialchars($name); ?>">

                    <button type="submit" class="btn btn-success" style="margin-top:15px;">Guardar</button>
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

    <script>
        const nombreInput = document.getElementById('nombre_empleado');
        const idInputOculto = document.getElementById('id_empleado_hidden');
        const datalist = document.getElementById('empleadoslist');

        nombreInput.addEventListener('input', function () {
            const opcionSeleccionada = datalist.querySelector(`option[value="${this.value}"]`);

            if (opcionSeleccionada) {
                idInputOculto.value = opcionSeleccionada.getAttribute('data-id');
            } else {
                idInputOculto.value = '';
            }
        });
    </script>
</body>

</html>