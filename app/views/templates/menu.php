<?php
$current_page = $_GET['url'] ?? 'dashboard';
?>

<div class="col-sm-3 col-md-2 sidebar">
    <?php /* Inicio de apartado para bodega */ if ($edificio == "cedis"): ?>
        <ul class="nav nav-sidebar">
            <li class="<?= ($current_page === 'dashboard') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>dashboard">INICIO</a>
            </li>
            <li class="<?= ($current_page === 'buscador') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>buscador">Buscador</a>
            </li>
            <?php if ($area != 'empleado'): ?>
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
    <?php /* Fin de apartado para bodega */ else:
        /* Inicio de apartado para Administración */ ?>
        <ul class="nav nav-sidebar">
            <li class="<?= ($current_page === 'dashboard') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>dashboard">INICIO</a>
            </li>
            <li class="<?= ($current_page === 'justificantes') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>justificantes">Justificantes</a>
            </li>
            <?php if ($area != 'empleado'): ?>
                <li class="<?= ($current_page === 'usuario/altauser') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>usuario/altauser">Alta de Usuarios</a>
                </li>
                <li class="<?= ($current_page === 'verempleados/verempleados') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>verempleados/verempleados">Ver empleados</a>
                </li>
                <li class="<?= ($current_page === 'verjustificantes') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>verjustificantes">Ver justificantes</a>
                </li>
                <li class="<?= ($current_page === 'deptosyedificios') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>deptosyedificios">Edificios y departamentos</a>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; /* Fin de apartado para administración */ ?>
</div>