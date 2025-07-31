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
        .read-to-me2bh0oahaee1-hover {
            background-color: #FFB77D !important;
            color: #000 !important;
            border-radius: 4px !important;
            box-decoration-break: clone !important;
            -webkit-box-decoration-break: clone !important;
            cursor: pointer !important;
            transition: .3s background-color !important;
        }

        .read-to-me2bh0oahaee1-highlight {
            background-color: #FFDBBE !important;
            color: #000 !important;
            border-radius: 4px !important;
            box-decoration-break: clone !important;
            -webkit-box-decoration-break: clone !important;
            transition: .3s background-color !important;
        }

        .read-to-me2bh0oahaee1-floating-loader {
            position: absolute;
            width: 30px;
            height: 30px;
            background: url('chrome-extension://anpkfnccdmljhdegcaonjffhjhhcalaj/icons/icon-loader-orange.svg') no-repeat center/cover;
            animation: loading 1.1s linear infinite;
            z-index: 100000;
        }

        .read-to-me2bh0oahaee1-highlight * {
            color: #000 !important;
        }

        @keyframes loading {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error {
            color: red;
            margin-top: 20px;
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
                        <h1 class="page-header">DESCARGAR BASE DE DATOS</h1>
                        <h3>Perfecto para análisis más detallados</h3>
                        <br>

                        <form id="loadForm" action="<?= BASE_URL ?>exportfile/descargar" method="POST" enctype="multipart/form-data">
                            <label for="">Seleccione la base que quiere descargar: </label>
                            <br>
                            <input required type="radio" name="marca" id="joes" value="joes" />
                            <label for="joes">JOE'S</label>
                            <br>
                            <input required type="radio" name="marca" id="trueReligion" value="true-religion" />
                            <label for="true">TRUE RELIGION</label>
                            <br>
                            <button style="margin-top: 20px;" type="submit"
                                class="btn btn-primary col-sm-2">Exportar</button>
                        </form>

                        <?php if (!empty($error_message)): ?>
                            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($message)): ?>
                            <div class="message">
                                <h3><?php echo htmlspecialchars($message); ?></h3>
                            </div>
                        <?php endif; ?>
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