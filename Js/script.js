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
