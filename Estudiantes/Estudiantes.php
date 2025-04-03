<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>

    <link rel="stylesheet" href="../Css/Estudiantes.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <h1>Estudiantes</h1>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar por nombre..." id="searchInput">
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>Matricula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado</th>
                            <th>Sección/Taller</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pdo = conectarDB();
                        if ($pdo) {
                            try {
                                $stmt = $pdo->query('SELECT "Matricula", "Nombre", "Apellido", "Grado", "Seccion/Taller", "Status" FROM "Estudiante"');
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $statusClass = str_replace(' ', '-', strtolower($row['Status']));  
                                    echo "<tr class='student-row' 
                                        data-student='{$row['Nombre']} {$row['Apellido']}' 
                                        data-image='https://kzzpdsbtrujsssojvpzc.supabase.co/storage/v1/object/public/imagenes-usuarios/User.png'
                                        data-id='{$row['Matricula']}'>";  
                                    echo "<td>{$row['Matricula']}</td>";
                                    echo "<td class='con-imagen'>
                                        <img src='https://kzzpdsbtrujsssojvpzc.supabase.co/storage/v1/object/public/imagenes-usuarios/User.png' alt='user icon'>
                                        {$row['Nombre']}
                                    </td>";
                                    echo "<td>{$row['Apellido']}</td>";
                                    echo "<td>{$row['Grado']}</td>";
                                    echo "<td>{$row['Seccion/Taller']}</td>";
                                    echo "<td><p class='status " . $statusClass . "'>{$row['Status']}</p></td>";
                                    echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo 'Error en la consulta: ' . $e->getMessage();
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

<div id="studentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="student-info">
            <img id="modalStudentImage" src="" alt="Student">
            <h2 id="modalStudentName"></h2>
        </div>
        <form id="tardanzaForm" action="./procesar_tardanza.php" method="POST">
            <h3>Registrar Tardanza</h3>
            <div class="form-group">
                <label for="justificacion">Justificación:</label>
                <textarea id="justificacion" name="justificacion" required></textarea>
            </div>
            <input type="hidden" id="estudiante_id" name="estudiante_id" value="">
            <input type="hidden" id="fecha_actual" name="fecha_actual" value="">
            <button type="submit" class="submit-btn">Registrar Tardanza</button>
        </form>

        <script>
        document.getElementById('tardanzaForm').addEventListener('submit', function(e) {
            const now = new Date();
            document.getElementById('fecha_actual').value = now.toISOString().slice(0, 19).replace('T', ' ');
        });
        </script>

        <script>
        document.querySelectorAll('.student-row').forEach(row => {
            row.addEventListener('click', function() {
                const studentName = this.getAttribute('data-student');
                const studentImage = this.getAttribute('data-image');
                const studentId = this.getAttribute('data-id');
                
                modalStudentName.textContent = studentName;
                modalStudentImage.src = studentImage;
                document.getElementById('estudiante_id').value = studentId;
                modal.style.display = "block";
            });
        });
        </script>
    </div>
</div>

<script>
const modal = document.getElementById("studentModal");
const span = document.getElementsByClassName("close")[0];
const modalStudentName = document.getElementById("modalStudentName");
const modalStudentImage = document.getElementById("modalStudentImage");

document.querySelectorAll('.student-row').forEach(row => {
    row.addEventListener('click', function() {
        const studentName = this.getAttribute('data-student');
        const studentImage = this.getAttribute('data-image');
        
        modalStudentName.textContent = studentName;
        modalStudentImage.src = studentImage;
        modal.style.display = "block";
    });
});

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('.table__body tbody tr');
    
    tableRows.forEach(row => {
        let nombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if(nombre.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
<?php if (isset($_SESSION['swal_message'])): ?>
<script>
    Swal.fire({
        title: <?= json_encode($_SESSION['swal_message']['title']) ?>,
        text: <?= json_encode($_SESSION['swal_message']['text']) ?>,
        icon: <?= json_encode($_SESSION['swal_message']['icon']) ?>,
        confirmButtonColor: '#0D3757'
    });
</script>
<?php 
    unset($_SESSION['swal_message']);
endif; 
?>
</body>

<?php include '../Componentes/footer.php'; ?>
</html>