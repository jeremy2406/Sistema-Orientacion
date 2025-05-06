<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_set_cookie_params([ 
        'httponly' => true, 
        'secure' => true,  
        'samesite' => 'Strict' 
    ]); 
    session_start(); 
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Orientación</title>
    <link rel="shortcut icon" href="Imagenes/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Css/Estilos.css">
    <link rel="stylesheet" href="../../Css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Madimi+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php
    $nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
    ?>
    <div class="user-info">
        <i class="fas fa-user"></i> <?php echo $nombre_usuario; ?> 
        <div class="dropdown-menu">
            <a href="../Login/Login.html">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</header>

<script>
    let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function(){
  sidebar.classList.toggle("active");
  
  // Si se cierra la barra lateral, cerrar todos los submenús
  if (!sidebar.classList.contains("active")) {
    document.querySelectorAll(".submenu_toggle").forEach(function(item){
      item.classList.remove("open");
    });
  }
}

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  // Seleccionar todos los elementos con clase submenu_toggle
  const submenuToggles = document.querySelectorAll('.submenu_toggle');
  
  // Agregar evento de clic a cada elemento
  submenuToggles.forEach(toggle => {
    toggle.addEventListener("click", function(e) {
      // Prevenir comportamiento predeterminado del enlace
      e.preventDefault();
      
      // Alternar la clase 'open' para mostrar/ocultar el submenú
      this.classList.toggle("open");
    });
  });
});

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
</body>

