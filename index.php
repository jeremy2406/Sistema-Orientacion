<?php include 'Componentes/header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script src="https://unpkg.com/@supabase/supabase-js@2">
    import { createClient } from 'jsr:@supabase/supabase-js@2'

    import { createClient } from '@supabase/supabase-js'

// Create a single supabase client for interacting with your database
const supabase = createClient('https://xyzcompany.supabase.co', 'public-anon-key')
console.log(supabase)
</script>

<div class="main-content">
    <h2>Panel de Administración</h2>
    <div class="dashboard">
        <div class="card users">
            <i class="fas fa-users"></i> 
            Estudiantes 
            <span id="total-estudiantes">...</span>
        </div>
        <div class="card books"><i class="fas fa-book"></i> Faltas <span>4</span></div>
        <div class="card authors"><i class="fas fa-user-tie"></i> .... <span>3</span></div>
        <div class="card editorial"><i class="fas fa-tag"></i> ... <span>1</span></div>
        <div class="card students"><i class="fas fa-graduation-cap"></i> ..... <span>2</span></div>
        <div class="card loans"><i class="fas fa-hourglass-half"></i> .... <span>0</span></div>
        <div class="card subjects"><i class="fas fa-list"></i> ...... <span>4</span></div>
        <div class="card settings"><i class="fas fa-cog"></i> ......</div>
    </div>

  

<?php include 'Componentes/footer.php'; ?>


 (Admin)
Logo
Orientación

    Estudiantes
    Excusas
    Calendario
    Tardanzas
    Faltas
    Reportes