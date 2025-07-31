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

.selection:focus{
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.9);
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
  }

  input:focus{
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.9);
  }
    </style>
</head>
<body>

<?php require_once '../app/views/templates/header.php'; ?>

<div class="containercontainer-fluid">
    <div class="row">

    <?php require_once '../app/views/templates/menu.php'; ?>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1>Alta de nuevo Usuario</h1>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>usuario/registrar">
        <label for="name">Nombre del empleado</label>
        <input type="text" id="name" name="name" required>

        <label for="user">Usuario a usar</label>
        <input type="text" id="user" name="user" required>

        <label for="psswrd">Contraseña</label>
        <input class="" type="password" id="psswrd" name="psswrd" required>

        <label for="rol">Rol del empleado</label>
        <select class="selection" id="rol" name="rol">
            <option disabled selected value="">-- Escoja un rol --</option>
            <option value="surtidor">Surtidor</option>
            <option value="administrador">Administrador</option>
            <option value="gestor">Gestor</option>
        </select>

        <input type="hidden" id="creator" name="creator" value="<?= htmlspecialchars($name); ?>">

        <button type="submit">AGREGAR</button>
    </form>
    </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (!empty($message)) : ?>
        alert("<?= htmlspecialchars($message) ?>");
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
        alert("<?= htmlspecialchars($error_message) ?>");
    <?php endif; ?>
});
</script>

<script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
</body>
</html>
