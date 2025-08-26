<?php
$current_page = $_GET['url'] ?? 'dashboard';
?>

<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li class="<?= ($current_page === 'dashboard') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>dashboard">INICIO</a>
        </li>

        <?php foreach ($permisos as $par): ?>
            <li class="<?= ($current_page === $par['url']) ? 'active' : '' ?>">
                <a href="<?= BASE_URL . $par['url'] ?>"><?= htmlspecialchars($par['nombre']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>