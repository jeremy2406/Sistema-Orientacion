<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Excusa</title>
    <link rel="stylesheet" href="../Css/Faltas.css">
    <link rel="stylesheet" href="../Css/Estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="body">
        <main class="form-container">
            <section class="form-header">
                <h1>Registrar Excusa</h1>
            </section>
            <section class="form-body">
                <form id="excusaForm">
                    <div class="form-group">
                        <label for="matricula">Matrícula del Estudiante:</label>
                        <input type="text" id="matricula" name="matricula" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="datetime-local" id="fecha" name="fecha" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="justificacion">Justificación:</label>
                        <textarea id="justificacion" name="justificacion" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-submit">Registrar Excusa</button>
                    </div>
                </form>
                
                <div id="studentInfo" class="student-info" style="display: none;">
                    <h3>Información del Estudiante</h3>
                    <p><strong>Nombre:</strong> <span id="nombreEstudiante"></span></p>
                    <p><strong>Grado:</strong> <span id="gradoEstudiante"></span></p>
                    <p><strong>Sección/Taller:</strong> <span id="seccionEstudiante"></span></p>
                </div>
            </section>
        </main>
    </div>

    <script>
    document.getElementById('excusaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('procesar_excusa.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    document.getElementById('justificacion').value = '';
                    
                    // Mostrar información del estudiante
                    document.getElementById('nombreEstudiante').textContent = data.estudiante.Nombre + ' ' + data.estudiante.Apellido;
                    document.getElementById('gradoEstudiante').textContent = data.estudiante.Grado;
                    document.getElementById('seccionEstudiante').textContent = data.estudiante['Seccion/Taller'];
                    document.getElementById('studentInfo').style.display = 'block';
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ha ocurrido un error al procesar la solicitud',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });
    </script>
</body>
</html>