<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Horarios</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once '../app/views/templates/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <?php require_once '../app/views/templates/menu.php'; ?>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Gestionar Horarios</h1>
                <hr>

                <h3>Agregar Nuevo Horario</h3>
                <form action="<?= BASE_URL ?>horarios/guardar" method="POST" class="form-horizontal">
                    <input type="hidden" name="id_depto_edificio" value="<?= htmlspecialchars($id_depto_edificio) ?>">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Día</label>
                        <div class="col-sm-4">
                            <select name="id_dia" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($dias as $d): ?>
                                    <option value="<?= $d['id_dia'] ?>"><?= htmlspecialchars($d['dia']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Entrada</label>
                        <div class="col-sm-4">
                            <input type="time" name="entrada" class="form-control" required>
                        </div>
                        <label class="col-sm-2 control-label">Tolerancia (min)</label>
                        <div class="col-sm-4">
                            <input type="text" name="tolerancia" class="form-control" placeholder="Ej: 10">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Salida</label>
                        <div class="col-sm-4">
                            <input type="time" name="salida" class="form-control" required>
                        </div>
                        <label class="col-sm-2 control-label">Comida</label>
                        <div class="col-sm-4">
                            <input type="time" name="comida" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Descanso (min)</label>
                        <div class="col-sm-4">
                            <input type="text" name="descanso" class="form-control" placeholder="Ej: 15">
                        </div>
                        <label class="col-sm-2 control-label">Rango Descanso</label>
                        <div class="col-sm-2">
                            <input type="time" name="rango_inicio" class="form-control" placeholder="Inicio">
                        </div>
                        <div class="col-sm-2">
                            <input type="time" name="rango_fin" class="form-control" placeholder="Fin">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>

                <hr>
                <h3>Horarios Registrados</h3>
                <?php if (!empty($horarios)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Entrada</th>
                                <th>Tolerancia</th>
                                <th>Salida</th>
                                <th>Comida</th>
                                <th>Descanso</th>
                                <th>Rango Descanso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['dia']) ?></td>
                                    <td><?= htmlspecialchars($h['entrada']) ?></td>
                                    <td><?= htmlspecialchars($h['tolerancia']) ?> min</td>
                                    <td><?= htmlspecialchars($h['salida']) ?></td>
                                    <td><?= htmlspecialchars($h['comida']) ?></td>
                                    <td><?= htmlspecialchars($h['descanso']) ?> min</td>
                                    <td><?= htmlspecialchars($h['rango_descanso']) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>horarios/eliminar" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_horario" value="<?= $h['id_horario'] ?>">
                                            <input type="hidden" name="id_depto_edificio" value="<?= $id_depto_edificio ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay horarios registrados.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group text-center" style="margin-top:20px;">
            <a href="<?= BASE_URL ?>deptosyedificios" class="btn btn-secondary">← Regresar</a>
        </div>
    </div>
</body>

</html>