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
    <link href="<?= BASE_URL ?>assets/css/carga.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>assets/js/ie-emulation-modes-warning.js.descarga"></script>
</head>

<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <div class="container-fluid">
                <div class="row">

                    <?php require_once '../app/views/templates/menu.php'; ?>

                    <!-- SVG con círculos que forman el efecto animado -->
                    <svg class="pl" width="240" height="240" viewBox="0 0 240 240">
                        <!-- Primer círculo animado con la clase pl_ring--a -->
                        <circle class="pl_ring pl_ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000"
                            stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round">
                        </circle>
                        <!-- Segundo círculo animado con la clase pl_ring--b -->
                        <circle class="pl_ring pl_ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000"
                            stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round">
                        </circle>
                        <!-- Tercer círculo animado con la clase pl_ring--c -->
                        <circle class="pl_ring pl_ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000"
                            stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                        <!-- Cuarto círculo animado con la clase pl_ring--d -->
                        <circle class="pl_ring pl_ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000"
                            stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                    </svg>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const archivo = "<?= $archivo ?>";

            if (archivo) {
                fetch("<?= BASE_URL ?>importfile/procesarArchivo?archivo=" + archivo)
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        window.location.href = "<?= BASE_URL ?>importfile";
                    });
            }
        });
    </script>




    <script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/holder.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/ie10-viewport-bug-workaround.js"></script>

</body>

</html>