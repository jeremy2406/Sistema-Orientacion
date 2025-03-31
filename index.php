<?php include 'Componentes/header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>

<div class="main-content">
    <h2>Panel de Administración</h2>
    <div class="dashboard">
        <div class="card users">
            <i class="fas fa-users"></i> 
            <span>Estudiantes</span>
            <span id="total-estudiantes">...</span>
        </div>
        <div class="card books">
            <i class="fas fa-book"></i>
            <span>Faltas</span>
            <span>4</span>
        </div>
        <div class="card authors">
            <i class="fas fa-user-tie"></i>
            <span>Excusas</span>
            <span>3</span>
        </div>
        <div class="card editorial">
            <i class="fas fa-tag"></i>
            <span>Reportes</span>
            <span>1</span>
        </div>
        <div class="card students">
            <i class="fas fa-graduation-cap"></i>
            <span>Tardanzas</span>
            <span>2</span>
        </div>
        <div class="card loans">
            <i class="fas fa-hourglass-half"></i>
            <span>Calendario</span>
            <span>0</span>
        </div>
        <div class="card subjects">
            <i class="fas fa-list"></i>
            <span>Registros</span>
            <span>4</span>
        </div>
        <div class="card settings">
            <i class="fas fa-cog"></i>
            <span>Configuración</span>
        </div>
    </div>
</div>

<?php include 'Componentes/footer.php'; ?>
