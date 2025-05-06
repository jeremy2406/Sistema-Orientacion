<?php include 'config.php'; ?><!-- o donde esté BASE_URL -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>Css/Estilos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>Css/Estudiantes.css">
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
        <li class="submenu_toggle" id="estudiantes-menu">
            <a href="#" data-tooltip="Estudiantes">
                <i class="fa-solid fa-users"></i>
                <span class="link_name">Estudiantes</span>
            </a>
            <div class="submenu" style="position: absolute; z-index: 1100; background-color: #0D3757; left: 100%; top: 0; display: none;">
                <a href="<?= BASE_URL ?>Estudiantes/3ro/3base.php">3ro</a>
                <a href="<?= BASE_URL ?>Estudiantes/4to/4base.php">4to</a>
                <a href="<?= BASE_URL ?>Estudiantes/5to/5base.php">5to</a>
                <a href="<?= BASE_URL ?>Estudiantes/6to/6base.php">6to</a>
            </div>
        </li>
        <li class="nav-item"><a href="<?= BASE_URL ?>Excusa/Excusas.php" data-tooltip="Excusas"><i class="fas fa-user-graduate"></i><span class="link_name">Excusas</span></a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>Calendario/Calendario.php" data-tooltip="Calendario"><i class="fa-solid fa-calendar-days"></i><span class="link_name">Calendario</span></a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>Tardanzas/Tardanzas.php" data-tooltip="Tardanzas"><i class="fas fa-book"></i><span class="link_name">Tardanzas</span></a></li>
        <li class="nav-item"><a href="<?= BASE_URL ?>Faltas/Faltas.php" data-tooltip="Faltas"><i class="fas fa-cogs"></i><span class="link_name">Faltas</span></a></li>
        <li class="nav-item"><a href="#" data-tooltip="Manual de Convivencia"><i class="fa-solid fa-book"></i><span class="link_name">M. de Convivencia</span></a></li>
    </ul>
</aside>

<div id="overlay"></div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo del menú desplegable de estudiantes
    const estudiantesMenu = document.getElementById('estudiantes-menu');
    const submenu = estudiantesMenu.querySelector('.submenu');
    
    estudiantesMenu.addEventListener('mouseenter', function() {
        submenu.style.display = 'block';
    });
    
    estudiantesMenu.addEventListener('mouseleave', function() {
        submenu.style.display = 'none';
    });
    
    // Asegurarse de que el submenú esté por encima de otros elementos
    submenu.style.zIndex = '1100';
});
</script>
</html>
<script src="<?= BASE_URL ?>Js/script.js"></script>
