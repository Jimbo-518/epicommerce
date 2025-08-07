<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= BASE_URL ?>dashboard">EPICOMMERCE</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= BASE_URL ?>dashboard">Inicio</a></li>
                <li style="display: <?php echo $activarrol = ($edificio != "cedis")? "none" : ""; ?>;"><a href="<?= BASE_URL ?>buscador">Buscador</a></li>
                <li><a href="<?= BASE_URL ?>login/logout">Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
</nav>