<?php include 'config.php'; ?> <!-- o donde esté BASE_URL -->

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
        <li><a href="<?= BASE_URL ?>Estudiantes/6to/6toDAAI.php" data-tooltip="Estudiantes"><i class="fas fa-handshake"></i><span class="link_name">Estudiantes</span></a></li>
        <li><a href="<?= BASE_URL ?>Excusa/Excusas.php" data-tooltip="Excusas"><i class="fas fa-user-graduate"></i><span class="link_name">Excusas</span></a></li>
        <li><a href="<?= BASE_URL ?>Calendario/Calendario.php" data-tooltip="Calendario"><i class="fas fa-book-open"></i><span class="link_name">Calendario</span></a></li>
        <li><a href="<?= BASE_URL ?>Tardanzas/Tardanzas.php" data-tooltip="Tardanzas"><i class="fas fa-book"></i><span class="link_name">Tardanzas</span></a></li>
        <li><a href="<?= BASE_URL ?>Faltas/Faltas.php" data-tooltip="Faltas"><i class="fas fa-cogs"></i><span class="link_name">Faltas</span></a></li>
        <li><a href="<?= BASE_URL ?>Reporte/Reportes.php" data-tooltip="Reportes"><i class="fas fa-chart-bar"></i><span class="link_name">Reportes</span></a></li>
        <li><a href="#" data-tooltip="Manual de Convivencia"><i class="fas fa-chart-bar"></i><span class="link_name">Manual de Convivencia</span></a></li>
    </ul>
</aside>

<script src="<?= BASE_URL ?>Js/script.js"></script>
