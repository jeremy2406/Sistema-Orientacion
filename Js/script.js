let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function(){
  sidebar.classList.toggle("active");

 
  if (!sidebar.classList.contains("active")) {
    document.querySelectorAll(".submenu_toggle").forEach(function(item){
      item.classList.remove("open");
    });
  }
}

let submenuToggles = document.querySelectorAll(".submenu_toggle");

submenuToggles.forEach(function(toggle){
  toggle.addEventListener("click", function(e){
    e.preventDefault();
    if (sidebar.classList.contains("active")) {
      this.classList.toggle("open");
    }
  });
});
