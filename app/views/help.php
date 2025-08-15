<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>INVENTARIO ECOMMERCE</title>
    <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">

    <style>
        .form-container {
            margin-top: 20px;
            padding: 30px;
            color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .form-container.active {
            display: block;
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

        .btn-outline-secondary {
            color: white;
            border-color: white;
        }

        .btn-outline-secondary:hover {
            background-color: white;
            color: #800000;
        }

        .toggle-btn {
            margin: 20px 0;
            padding: 20px;
            cursor: pointer;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .toggle-btn:hover {
            opacity: 0.8;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .title-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            width: 80%;
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
                <H1>AYUDA</H1>
                <h3>Encuentre la solución a los problemas más comunes</h3>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>" role="alert">
                        <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <div class="toggle-btn" data-target="#form1" style="background-color: #292F39;">+ Vaciar Base</div>
                <div id="form1" class="form-container" style="background-color: #292F39;">
                    <h2 class="form-header text-center">Este es un proceso que no se puede deshacer</h2>
                    <h2 class="form-header text-center">Asegurese de haber hecho un respaldo antes</h2>
                    <form id="DeleteForm" action="<?= BASE_URL ?>help/deleteBD" method="POST">
                        <div class="form-group">
                            <label for="user">Usuario:</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="psswrd">Psswrd:</label>
                            <input type="text" class="form-control" id="psswrd" name="psswrd" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block" name="deleteBD">Vaciar BD</button>
                    </form>
                </div>

                <div class="toggle-btn" data-target="#form2" style="background-color: #292F39;">+ Devolución de prenda
                </div>
                <div id="form2" class="form-container" style="background-color: #292F39;">
                    <h2 class="form-header text-center">Ingrese los datos solicitados</h2>
                    <form id="devPrenda" action="<?= BASE_URL ?>help/devolucionPrenda" method="post">
                        <div class="form-group">
                            <label for="user">Usuario:</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="psswrd">Psswrd:</label>
                            <input type="text" class="form-control" id="psswrd" name="psswrd" required>
                        </div>
                        <div class="form-group">
                            <label for="upc">UPC:</label>
                            <input type="text" class="form-control" id="upc" name="upc" required>
                        </div>
                        <div class="form-group">
                            <label for="mar">Marbete:</label>
                            <input type="text" class="form-control" id="mar" name="mar" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block" name="backP">Devolver Prenda</button>
                    </form>
                </div>

                <div class="toggle-btn" data-target="#form3" style="background-color: #292F39;">+ Error en Prenda</div>
                <div id="form3" class="form-container" style="background-color: #292F39;">
                    <h2 class="form-header text-center">En caso de prendas destalladas o modelos incorrectos</h2>
                    <h2 class="form-header text-center">Ingrese los datos solicitados</h2>
                    <form id="errorPrenda" action="<?= BASE_URL ?>help/errorPrenda" method="post">
                        <div class="form-group">
                            <label for="user">Usuario:</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="psswrd">Psswrd:</label>
                            <input type="text" class="form-control" id="psswrd" name="psswrd" required>
                        </div>
                        <div class="form-group">
                            <label for="upc">UPC actual:</label>
                            <input type="text" class="form-control" id="upc" name="upc" required>
                        </div>
                        <div class="form-group">
                            <label for="mar">Marbete actual:</label>
                            <input type="text" class="form-control" id="mar" name="mar" required>
                        </div>
                        <div class="form-group">
                            <label for="upcC">UPC correcto:</label>
                            <input type="text" class="form-control" id="upcC" name="upcC" required>
                        </div>
                        <div class="form-group">
                            <label for="marC">Marbete correcto:</label>
                            <input type="text" class="form-control" id="marC" name="marC" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block" name="backP">Devolver Prenda</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', () => {
                const target = document.querySelector(button.getAttribute('data-target'));
                if (target.style.display === 'none' || target.style.display === '') {
                    target.style.display = 'block';
                } else {
                    target.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>