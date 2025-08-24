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
        body {
            padding-top: 70px;
        }

        table {
            width: 100%;
            max-width: 1000px;
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
            background-color: rgb(186, 226, 252);
        }

        table caption {
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
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
                        <h1 class="page-header">Edificios y Departamentos</h1>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (!empty($edificioslist)): ?>
                                    <h3>Edificios</h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Edificio</th>
                                                <th>Ubicación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($edificioslist as $edif): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($edif['id_edificio']) ?></td>
                                                    <td><?= htmlspecialchars($edif['edificio']) ?></td>
                                                    <td><?= htmlspecialchars($edif['ubicacion']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tr>
                                            <form action="">
                                                <td>
                                                    <input style="width: 100%;" type="text" name="edificio"
                                                        placeholder="Nuevo Edificio" required>
                                                </td>
                                                <td>
                                                    <input style="width: 100%;" type="text" name="ubicacion"
                                                        placeholder="Ubicación" required>
                                                </td>
                                                <td>
                                                    <button style="width: 100%;" type="submit"
                                                        class="btn btn-primary">Agregar</button>
                                                </td>
                                            </form>
                                        </tr>

                                    </table>
                                <?php else: ?>
                                    <p>No hay edificios registrados.</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <?php if (!empty($areaslist)): ?>
                                    <h3>Departamentos</h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Departamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($areaslist as $ar): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($ar['id_depto']) ?></td>
                                                    <td><?= htmlspecialchars($ar['departamento']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <form action="">
                                                    <td>
                                                        <input style="width: 100%;" type="text" name="edificio"
                                                            placeholder="Nuevo Departamento" required>
                                                    </td>
                                                    <td>
                                                        <button style="width: 100%;" type="submit"
                                                            class="btn btn-primary">Agregar</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tbody>

                                    </table>
                                <?php else: ?>
                                    <p>No hay departamentos registrados.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php if (!empty($relacioneslist)): ?>
                                    <h3>Relaciones:</h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Departamento</th>
                                                <th>Edificio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($relacioneslist as $rel): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($rel['id_depto_edificio']) ?></td>
                                                    <td><?= htmlspecialchars($rel['departamento']) ?></td>
                                                    <td><?= htmlspecialchars($rel['edificio']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <form action="">
                                                    <td>
                                                        <select name="departamento" id="departamento">
                                                            <option value="">-- Seleccione un área --</option>
                                                            <?php foreach ($areaslist as $ar): ?>
                                                                <option value="<?= htmlspecialchars($ar['departamento']) ?>">
                                                                    <?= htmlspecialchars($ar['departamento']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select  name="edificio" id="edificio">
                                                            <option value="">-- Seleccione un edificio --</option>
                                                            <?php foreach ($edificioslist as $edif): ?>
                                                                <option value="<?= htmlspecialchars($edif['edificio']) ?>">
                                                                    <?= htmlspecialchars($edif['edificio']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button style="width: 100%;" type="submit"
                                                            class="btn btn-primary">Agregar</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tbody>

                                    </table>
                                <?php else: ?>
                                    <p>No hay departamentos registrados.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-4"></div>
                    </div>
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