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
        <li class="submenu_toggle" id="estudiantes-menu">
            <a href="#" data-tooltip="Estudiantes">
                <i class="fa-solid fa-users"></i>
                <span class="link_name">Estudiantes</span>
            </a>
            <div class="submenu">
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
        <li class="nav-item"><a href="<?= BASE_URL ?>Reporte/Reportes.php" data-tooltip="Reportes"><i class="fas fa-chart-bar"></i><span class="link_name">Reportes</span></a></li>
        <li class="nav-item"><a href="#" data-tooltip="Manual de Convivencia"><i class="fa-solid fa-book"></i><span class="link_name">M. de Convivencia</span></a></li>
    </ul>
</aside>

<div id="overlay"></div>

<style>
/* Estilos para el menú desplegable */
.submenu {
    display: none;
    padding: 10px;
    transition: all 0.3s ease;
    background-color: #0D3757;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    position: absolute;
    z-index: 1000;
    width: 80px;
    right: -90px;
    top: 0; /* Cambiado de -5px a 0 para posicionar en la parte superior */
}

.submenu.show {
    display: block;
}

.submenu li {
    padding: 0;
    margin: 8px 0;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.submenu li a {
    display: block;
    text-align: center;
    padding: 12px 8px;
    color: #ffffff;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-weight: bold;
    font-size: 16px;
}

.submenu li a:hover {
    background-color: rgba(255, 255, 255, 0.15);
    color: #4ecdc4;
}

.submenu li:last-child a {
    margin-bottom: 0;
}

.arrow {
    transition: transform 0.3s ease;
    position: absolute;
    right: 15px;
    opacity: 0;
}

.submenu_toggle:hover .arrow,
.submenu_toggle.active .arrow {
    opacity: 1;
}

.arrow.rotate {
    transform: rotate(180deg);
}

.submenu_toggle.active > a {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.submenu_toggle {
    position: relative;
}

/* Overlay para oscurecer el resto del menú */
#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 900;
}

/* Estilo para opacar los otros elementos del menú */
.nav-item.fade {
    opacity: 0.3;
    pointer-events: none;
}

/* Ajuste para cuando la barra lateral está colapsada */
.sidebar:not(.active) .submenu {
    right: -90px;
}

/* Ajuste para cuando la barra lateral está expandida */
.sidebar.active .submenu {
    right: -90px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el elemento del menú de estudiantes
    const estudiantesMenu = document.getElementById('estudiantes-menu');
    const overlay = document.getElementById('overlay');
    const navItems = document.querySelectorAll('.nav-item');
    let menuOpen = false;
    
    // Agregar evento de clic al menú de estudiantes
    estudiantesMenu.addEventListener('click', function(e) {
        // Prevenir comportamiento predeterminado del enlace
        if (e.target.tagName === 'A' || e.target.closest('a').getAttribute('href') === '#') {
            e.preventDefault();
        }
        
        // Obtener el submenú
        const submenu = this.querySelector('.submenu');
        
        // Si el menú ya está abierto, cerrarlo al hacer clic de nuevo
        if (menuOpen) {
            submenu.classList.remove('show');
            this.classList.remove('active');
            overlay.style.display = 'none';
            navItems.forEach(item => {
                item.classList.remove('fade');
            });
            const arrow = this.querySelector('.arrow');
            if (arrow) {
                arrow.classList.remove('rotate');
            }
            menuOpen = false;
        } else {
            // Abrir el menú
            submenu.classList.add('show');
            this.classList.add('active');
            overlay.style.display = 'block';
            navItems.forEach(item => {
                item.classList.add('fade');
            });
            const arrow = this.querySelector('.arrow');
            if (arrow) {
                arrow.classList.add('rotate');
            }
            menuOpen = true;
        }
    });
    
    // Cerrar el submenú al hacer clic en el overlay
    overlay.addEventListener('click', function() {
        const submenu = document.querySelector('.submenu');
        if (submenu.classList.contains('show')) {
            submenu.classList.remove('show');
            estudiantesMenu.classList.remove('active');
            overlay.style.display = 'none';
            navItems.forEach(item => {
                item.classList.remove('fade');
            });
            const arrow = estudiantesMenu.querySelector('.arrow');
            if (arrow) {
                arrow.classList.remove('rotate');
            }
            menuOpen = false;
        }
    });
    
    // Permitir que los enlaces del submenú funcionen sin cerrar el submenú
    const submenuLinks = document.querySelectorAll('.submenu a');
    submenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar que el clic se propague al elemento padre
            
            // Prevenir el comportamiento por defecto para evitar problemas con el último elemento
            if (this.parentElement === this.parentElement.parentElement.lastElementChild) {
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, 50);
                e.preventDefault();
            }
        });
    });
});
</script>

<script src="<?= BASE_URL ?>Js/script.js"></script>
