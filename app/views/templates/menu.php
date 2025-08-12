<?php 
$current_page = $_GET['url'] ?? 'dashboard';
?>

<div class="col-sm-3 col-md-2 sidebar">
    <!-- Lista Para el menú de Cedis -->
    <ul style="display: <?php echo $activarrol = ($edificio != "cedis")? "none" : ""; ?>;" class="nav nav-sidebar">
        <li class="<?= ($current_page === 'dashboard') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>dashboard">INICIO</a>
        </li>
        <li class="<?= ($current_page === 'buscador') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>buscador">Buscador</a>
        </li>
        <?php if ($area != 'surtidor'): ?>
            <li class="<?= ($current_page === 'exportfile') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>exportfile">Descargar inventario</a>
            </li>
            <li class="<?= ($current_page === 'importfile') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>importfile">Cargar Inventario</a>
            </li>
            <li class="<?= ($current_page === 'usuario/altauser') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>usuario/altauser">Alta de Usuarios</a>
            </li>
            <li class="<?= ($current_page === 'help') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>help">AYUDA</a>
            </li>
        <?php endif; ?>
    </ul>
    <!-- Lista Para el menú de Administración -->
    <ul style="display: <?php echo $activarrol = ($edificio != "cedis")? "" : "none"; ?>;" class="nav nav-sidebar">
        <li class="<?= ($current_page === 'dashboard') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>dashboard">INICIO</a>
        </li>
        <?php //if ($rol == 'rh' || $rol == 'contabilidad'): ?>
            <li class="<?= ($current_page === 'usuario/altauser') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>usuario/altauser">Alta de Usuarios</a>
            </li>
            <li class="<?= ($current_page === 'usuario/verempleados') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>usuario/verempleados">Ver empleados</a>
            </li>
            
            <li>Justificantes</li>
        <?php //endif; ?>
    </ul>

</div>
