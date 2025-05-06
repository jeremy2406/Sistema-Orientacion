<?php include '../../Componentes/header.php'; ?>
<?php include '../../Componentes/Nav.php'; ?>
<?php include '../../Componentes/conexion.php'; ?>
<?php define('ROOT_PATH', realpath(__DIR__ . '/../../'));
include_once ROOT_PATH . '/Componentes/config.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>
    <link rel="stylesheet" href=" ../Css/Estilos.css">
    <link rel="stylesheet" href="../../Css/Estudiantes.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <h1>5to LT - Jose Maria Mancebo</h1>
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
                            <th>Taller/Seccion</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pdo = conectarDB();
                        if ($pdo) {
                            try {
                                $grado = "5to";
                                $seccion = "LT";

                                $stmt = $pdo->prepare('SELECT "Matricula", "Nombre", "Apellido", "Grado", "Seccion/Taller", "Status" FROM "Estudiante" WHERE "Grado" = :grado AND "Seccion/Taller" = :seccion');
                                $stmt->bindParam(':grado', $grado, PDO::PARAM_STR);
                                $stmt->bindParam(':seccion', $seccion, PDO::PARAM_STR);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $statusClass = str_replace(' ', '-', strtolower($row['Status']));  
                                    echo "<tr class='student-row' 
                                        data-student='{$row['Nombre']} {$row['Apellido']}' 
                                        data-id='{$row['Matricula']}'>";  
                                    echo "<td>{$row['Matricula']}</td>";
                                    echo "<td>{$row['Nombre']}</td>";
                                    echo "<td>{$row['Apellido']}</td>";
                                    echo "<td>{$row['Grado']}</td>";
                                    echo "<td>{$row['Seccion/Taller']}</td>";
                                    echo "<td class='status-cell'>
                                        <p class='status " . $statusClass . "'>{$row['Status']}</p>
                                    </td>";
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
        <span class="close">×</span>
        <div class="student-info">
            <h2 id="modalStudentName"></h2>
        </div>
        <form id="tardanzaForm" action="<?= BASE_URL ?>Estudiantes/procesar_tardanza.php" method="POST">
            <h3>Registrar Tardanza</h3>
            <div class="form-group">
                <label for="tipo_falta">Tipo de Tardanza:</label>
                <select id="tipo_falta" name="tipo_falta" required>
                    <option value="">Seleccione el tipo de tardanza</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Salud">Salud</option>
                    <option value="Se_quedo_Dormido">Se quedo Dormido</option>
                </select>
            </div>
            <div class="form-group">
                <label for="justificacion">Justificación:</label>
                <textarea id="justificacion" name="justificacion" required></textarea>
            </div>
            <input type="hidden" id="estudiante_id" name="estudiante_id" value="">
            <input type="hidden" id="fecha_actual" name="fecha_actual" value="">
            <button type="submit" class="submit-btn">Registrar Tardanza</button>
        </form>
    </div>
</div>

<div id="faltaModal" class="modal">
    <div class="modal-content">
        <span class="close">×</span>
        <div class="student-info">
            <h2 id="faltaModalStudentName"></h2>
        </div>
        <div class="faltas-summary">
            <div class="falta-count leve">
                <label>Leves</label>
                <span>0</span>
            </div>
            <div class="falta-count grave">
                <label>Graves</label>
                <span>0</span>
            </div>
            <div class="falta-count muy-grave">
                <label>Muy Graves</label>
                <span>0</span>
            </div>
        </div>
        <form id="faltaModalForm" action="<?= BASE_URL ?>Faltas/procesar_falta.php" method="POST">
            <h3>Registrar Falta</h3>
            <div class="form-group">
                <label for="fecha_falta">Fecha:</label>
                <input type="datetime-local" id="fecha_falta" name="fecha_falta" required>
            </div>
            <div class="form-group">
                <label for="tipo_falta">Tipo de Falta:</label>
                <select id="tipo_falta" name="tipo_falta" required>
                    <option value="">Seleccione el tipo de falta</option>
                    <option value="leve">Leve</option>
                    <option value="grave">Grave</option>
                    <option value="muy-grave">Muy Grave</option>
                </select>
            </div>
            <div class="form-group">
                <label for="justificacion">Justificación:</label>
                <textarea id="justificacion" name="justificacion" required></textarea>
            </div>
            <input type="hidden" id="matricula_estudiantes" name="matricula_estudiantes" value="">
            <button type="submit" class="submit-btn">Registrar Falta</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add SweetAlert handling for both forms
    document.getElementById('tardanzaForm').addEventListener('submit', function(e) {
        const now = new Date();
        document.getElementById('fecha_actual').value = now.toISOString().slice(0, 19).replace('T', ' ');
    });

    document.getElementById('faltaModalForm').addEventListener('submit', function(e) {
        const now = new Date();
        document.getElementById('fecha_falta').value = now.toISOString().slice(0, 16);
    });

    // Autocompletar justificación basado en el tipo de tardanza seleccionado
    document.getElementById('tipo_falta').addEventListener('change', function() {
        const justificacionField = document.getElementById('justificacion');
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value !== "") {
            justificacionField.value = selectedOption.text;
        } else {
            justificacionField.value = '';
        }
    });

    // Show SweetAlert if there's a message in session
    <?php if (isset($_SESSION['swal_message'])): ?>
    Swal.fire({
        title: <?= json_encode($_SESSION['swal_message']['title']) ?>,
        text: <?= json_encode($_SESSION['swal_message']['text']) ?>,
        icon: <?= json_encode($_SESSION['swal_message']['icon']) ?>,
        confirmButtonColor: '#0D3757'
    });
    <?php 
        unset($_SESSION['swal_message']);
    endif; 
    ?>

    // Tardanza modal handling
    document.querySelectorAll('.student-row').forEach(row => {
        row.addEventListener('click', function() {
            const studentName = this.getAttribute('data-student');
            const studentId = this.getAttribute('data-id');
            
            document.getElementById('modalStudentName').textContent = studentName;
            document.getElementById('estudiante_id').value = studentId;
            document.getElementById('studentModal').style.display = "block";
        });
    });

    // Faltas modal handling
    document.querySelectorAll('.status-cell').forEach(cell => {
        cell.addEventListener('click', async function(e) {
            e.stopPropagation();
            const row = this.closest('tr');
            const studentId = row.getAttribute('data-id');
            const studentName = row.getAttribute('data-student');
            
            try {
                const response = await fetch(`../get_faltas_count.php?id=${studentId}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const counts = await response.json();
                
                document.querySelector('.falta-count.leve span').textContent = counts.leves;
                document.querySelector('.falta-count.grave span').textContent = counts.graves;
                document.querySelector('.falta-count.muy-grave span').textContent = counts.muy_graves;
            } catch (error) {
                console.error('Error fetching faltas:', error);
            }
            
            document.getElementById('faltaModalStudentName').textContent = studentName;
            document.getElementById('matricula_estudiantes').value = studentId;
            document.getElementById('faltaModal').style.display = "block";
        });
    });

    // Close buttons for both modals
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.onclick = function() {
            this.closest('.modal').style.display = "none";
        }
    });

    // Click outside to close modals
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    // Set default datetime for falta form
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('fecha_falta').value = now.toISOString().slice(0, 16);
});

document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('.table__body tbody tr');

    tableRows.forEach(row => {
        let nombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        let apellido = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

        if(nombre.includes(searchText)) {
            row.style.display = '';
        } else if (apellido.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
</body>

<?php include '../../Componentes/footer.php'; ?>
</html>