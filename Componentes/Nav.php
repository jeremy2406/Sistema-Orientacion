<?php include 'config.php'; ?><!-- o donde esté BASE_URL -->

<aside class="sidebar">
    <div class="logo_content">
        <div class="logo">
            <a href="<?= BASE_URL ?>Componentes/index.php">
                <img src="<?= BASE_URL ?>Imagenes/Logo.png" alt="Logo">
            </a>
            <a class="logo_name" href="<?= BASE_URL ?>Componentes/index.php">
                Orientación
            </a>
        </div>
        <i class="fa-solid fa-bars" id="btn"></i>
    </div>

    <ul class="nav_list">
    <li class="submenu_toggle">
        <a href="#" data-tooltip="Estudiantes">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Estudiantes</span>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="<?= BASE_URL ?>Estudiantes/3ro/3base.php">3ro</a></li>
            <li><a href="<?= BASE_URL ?>Estudiantes/4to/4base.php">4to</a></li>
            <li><a href="<?= BASE_URL ?>Estudiantes/5to/5base.php">5to</a></li>
            <li><a href="<?= BASE_URL ?>Estudiantes/6to/6base.php">6to</a></li>
        </ul>
    </li>
    <li><a href="<?= BASE_URL ?>Excusa/Excusas.php" data-tooltip="Excusas"><i class="fas fa-user-graduate"></i><span class="link_name">Excusas</span></a></li>
    <li><a href="<?= BASE_URL ?>Calendario/Calendario.php" data-tooltip="Calendario"><i class="fa-solid fa-calendar-days"></i><span class="link_name">Calendario</span></a></li>
    <li><a href="<?= BASE_URL ?>Tardanzas/Tardanzas.php" data-tooltip="Tardanzas"><i class="fas fa-book"></i><span class="link_name">Tardanzas</span></a></li>
    <li><a href="<?= BASE_URL ?>Faltas/Faltas.php" data-tooltip="Faltas"><i class="fas fa-cogs"></i><span class="link_name">Faltas</span></a></li>
    <li><a href="<?= BASE_URL ?>Reporte/Reportes.php" data-tooltip="Reportes"><i class="fas fa-chart-bar"></i><span class="link_name">Reportes</span></a></li>
    <li><a href="#" data-tooltip="Manual de Convivencia"><i class="fa-solid fa-book"></i><span class="link_name">M. de Convivencia</span></a></li>
</ul>

</aside>

<script src="<?= BASE_URL ?>Js/script.js"></script>
