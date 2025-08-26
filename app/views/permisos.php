<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="">

    <title>INVENTARIO JOES</title>

    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">

    <script src="<?= BASE_URL ?>assets/js/ie-emulation-modes-warning.js.descarga"></script>
    <style>
        input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        label {
            cursor: pointer;
            text-indent: -9999px;
            /* Reducimos el ancho y alto del contenedor */
            width: 60px;
            height: 30px;
            background: grey;
            display: block;
            border-radius: 100px;
            position: relative;
        }

        label:after {
            content: '';
            position: absolute;
            /* Reducimos el tamaño y la posición del "círculo" */
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: #fff;
            border-radius: 90px;
            transition: 0.3s;
        }

        input:checked+label {
            background: #2bd126ff;
        }

        input:checked+label:after {
            /* Ajustamos la posición final */
            left: calc(100% - 3px);
            transform: translateX(-100%);
        }

        label:active:after {
            width: 130px;
        }
    </style>
</head>

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <div class="container-fluid">
                <div class="row">

                    <?php require_once '../app/views/templates/menu.php'; ?>

                    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                        <h1 class="page-header">Permisos para el Departamento:
                            <?= htmlspecialchars($datos_depto['departamento']) ?>
                        </h1>
                        <hr>
                        <form action="permisos/guardar_permisos" method="post">
                            <input type="hidden" name="id_depto"
                                value="<?= htmlspecialchars($datos_depto['id_depto']) ?>">
                            <table>
                                <?php foreach ($paginaslist as $pagina): ?>
                                    <tr>
                                        <td class="col-md-4">
                                            <h3><?= htmlspecialchars($pagina['nombre']) ?></h3>
                                        </td>
                                        <td class="col-md-4">
                                            <?php
                                            $isChecked = in_array($pagina['id_pagina'], $permisos_depto);
                                            $switchId = 'switch-' . htmlspecialchars($pagina['id_pagina']);
                                            ?>
                                            <input type="checkbox" id="<?= $switchId ?>" name="permisos[]"
                                                value="<?= htmlspecialchars($pagina['id_pagina']) ?>" <?= $isChecked ? 'checked' : '' ?> />
                                            <label for="<?= $switchId ?>">Toggle</label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <hr>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/holder.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>