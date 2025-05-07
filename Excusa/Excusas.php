<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excusas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Css/Tardanzas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/Css/Estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Agregar jQuery y jQuery UI para autocompletado -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .table__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #0D3757;
            color: white;
        }
        
        .header-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .date-input {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            text-align: center;
        }
        
        .btn-agregar {
            background-color: rgb(75, 180, 26);
            color: #ffffffff;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .btn-agregar:hover {
            background-color:rgb(75, 180, 26);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #000;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-submit {
            background-color: #0D3757;
            width: 100%;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }
        
        /* Estilos para el autocompletado */
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 9999 !important;
        }
        
        .ui-menu-item {
            padding: 5px;
            cursor: pointer;
        }
        
        .ui-menu-item:hover {
            background-color: #f0f0f0;
        }
        
        .ui-state-active, .ui-widget-content .ui-state-active {
            background-color: #0D3757 !important;
            color: white !important;
            border: 1px solid #0D3757 !important;
        }
    </style>
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <h1>Registro de Excusas</h1>
                <div class="header-controls">
                    <input type="date" id="fecha" class="date-input" value="<?php echo date('Y-m-d'); ?>">
                    <div class="search-container">
                            <input type="text" id="buscarEstudiante" placeholder="Buscar por nombre o apellido">
                            <button id="btnBuscar">Buscar</button>
                            <button id="btnImprimir" class="btn-amarillo">Imprimir Reporte</button>
                            <button id="openModalBtn" class="btn-agregar">Agregar Excusa</button>
                        </div>
                </div>
            </section>
            <div class="print-header">
                <h2>Instituto Politécnico Industrial Don Bosco</h2>
                <h3>Reporte de Tardanzas</h3>
                <p>Fecha de impresión: <?php echo date('d/m/Y H:i:s'); ?></p>
            </div>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">ESTUDIANTE</th>
                            <th class="text-center">GRADO</th>
                            <th class="text-center">SECCIÓN/TALLER</th>
                            <th class="text-center">FECHA</th>
                            <th class="text-center">TUTOR</th>
                            <th class="text-center">JUSTIFICACIÓN</th>
                        </tr>
                    </thead>
                    <tbody id="excusasBody">
                        <?php
                        $pdo = conectarDB();
                        if ($pdo) {
                            try {
                                $fecha_actual = date('Y-m-d');
                                $stmt = $pdo->prepare('
                                    SELECT e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado", 
                                           ex."Fecha", ex."Tutor", ex."Justificacion"
                                    FROM "Excusas" ex
                                    JOIN "Estudiante" e ON ex."Matricula_estudiantes" = e."Matricula"
                                    WHERE CAST(ex."Fecha" AS DATE) = ?
                                    ORDER BY ex."Fecha" DESC
                                ');
                                $stmt->execute([$fecha_actual]);
                                
                                $count = 0;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $count++;
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$row['Nombre']} {$row['Apellido']}</td>";
                                    echo "<td class='text-center'>{$row['Grado']}</td>";
                                    echo "<td class='text-center'>{$row['Seccion/Taller']}</td>";
                                    echo "<td class='text-center'>" . date('d/m/Y', strtotime($row['Fecha'])) . "</td>";
                                    echo "<td class='text-center'>{$row['Tutor']}</td>";
                                    echo "<td class='text-center'>{$row['Justificacion']}</td>";
                                    echo "</tr>";
                                }
                                
                                if ($count === 0) {
                                    echo "<tr><td colspan='5' class='text-center'>No hay excusas registradas para esta fecha</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='5' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <div class="print-footer">
                <p>Este documento es un reporte oficial de tardanzas del Instituto Politécnico José María Mancebo.</p>
                <p>Generado por el Sistema de Orientación.</p>
            </div>
        </main>
    </div>

    <!-- Modal para agregar excusa -->
    <div id="excusaModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Agregar Excusa</h2>
            <form id="excusaForm" method="POST" action="procesar_excusa.php">
            <div class="form-group">
                    <label for="grado">Grado:</label>
                    <select id="grado" name="grado" required>
                        <option value="">Seleccionar Grado</option>
                        <option value="3ro">3ro</option>
                        <option value="4to">4to</option>
                        <option value="5to">5to</option>
                        <option value="6to">6to</option>
                    </select>
            </div>
                <div class="form-group">
                    <label for="taller">Sección/Taller:</label>
                    <select id="taller" name="taller" required>
                        <option value="">Seleccionar Sección/Taller</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                        <option value="G">G</option>
                        <option value="DAAI">DAAI</option>
                        <option value="EE">EE</option>
                        <option value="EER">EER</option>
                        <option value="ER">ER</option>
                        <option value="GAT">GAT</option>
                        <option value="LT">LT</option>
                        <option value="MEC">MEC</option>
                        <option value="MG">MG</option>
                        <option value="RAA">RAA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="tutor">Responsable del Estudiante:</label>
                    <select id="tutor" name="tutor" required>
                        <option value="">Seleccionar Responsable</option>
                        <option value="Padre">Padre</option>
                        <option value="Madre">Madre</option>
                        <option value="Tutor">Tutor</option>
                    </select>
            </div>
                <div class="form-group">
                    <label for="fecha_excusa">Fecha:</label>
                    <input type="date" id="fecha_excusa" name="fecha_excusa" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="excusa_comun">Excusas más comunes:</label>
                    <select id="excusa_comun" name="excusa_comun">
                        <option value="">Seleccionar excusa</option>
                        <option value="Salud">Salud</option>
                        <option value="Familiar">Problema familiar</option>
                        <option value="Transporte">Problema de transporte</option>
                        <option value="Clima">Condiciones climáticas</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="justificacion">Justificación:</label>
                    <textarea id="justificacion" name="justificacion" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Guardar</button>
            </form>
        </div>
    </div>

    <script>
    // Funcionalidad para cambiar la fecha
    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        fetch(`actualizar_excusas.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('excusasBody').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });

    // Funcionalidad para el modal
    const modal = document.getElementById("excusaModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementsByClassName("close")[0];

    // Función para limpiar el formulario
    function limpiarFormulario() {
        document.getElementById('excusaForm').reset();
        estudianteSeleccionado = null;
    }

    openModalBtn.onclick = function() {
        modal.style.display = "block";
    }

    closeBtn.onclick = function() {
        modal.style.display = "none";
        limpiarFormulario();
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            limpiarFormulario();
        }
    }

    // Autocompletar justificación al seleccionar excusa común
    document.getElementById('excusa_comun').addEventListener('change', function() {
        const excusaComun = this.value;
        const justificacionField = document.getElementById('justificacion');
        
        switch(excusaComun) {
            case 'Salud':
                justificacionField.value = 'El estudiante no pudo asistir por motivos de salud.';
                break;
            case 'Familiar':
                justificacionField.value = 'El estudiante no pudo asistir debido a un problema familiar.';
                break;
            case 'Transporte':
                justificacionField.value = 'El estudiante no pudo asistir debido a problemas de transporte.';
                break;
            case 'Clima':
                justificacionField.value = 'El estudiante no pudo asistir debido a condiciones climáticas adversas.';
                break;
            case 'Otro':
                justificacionField.value = '';
                break;
            default:
                justificacionField.value = '';
        }
    });

    // Manejo del formulario
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
                    text: 'Excusa registrada correctamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    modal.style.display = "none";
                    document.getElementById('excusaForm').reset();
                    
                    // Actualizar la tabla con la fecha actual
                    const fechaActual = document.getElementById('fecha').value;
                    fetch(`actualizar_excusas.php?fecha=${fechaActual}`)
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('excusasBody').innerHTML = data;
                        });
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al registrar la excusa',
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


    // Funcionalidad de autocompletado para el nombre basado en grado y sección/taller
    let estudianteSeleccionado = null;
    
    function actualizarAutocompletado() {
        const grado = $('#grado').val();
        const taller = $('#taller').val();
        
        if (grado && taller) {
            $('#nombre').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'buscar_estudiantes.php',
                        dataType: 'json',
                        data: {
                            term: request.term,
                            grado: grado,
                            taller: taller
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    estudianteSeleccionado = ui.item;
                    $('#nombre').val(ui.item.nombre);
                    $('#apellido').val(ui.item.apellido);
                    return false;
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.nombre + " " + item.apellido + "</div>")
                    .appendTo(ul);
            };
        } else {
            // Si no se han seleccionado ambos campos, desactivar autocompletado
            if ($('#nombre').autocomplete("instance")) {
                $('#nombre').autocomplete("destroy");
            }
        }
    }
    
    // Activar autocompletado cuando cambien los selectores de grado y taller
    $('#grado, #taller').on('change', function() {
        actualizarAutocompletado();
    });
    
    // Limpiar campos de nombre y apellido cuando se cambie el grado o taller
    $('#grado, #taller').on('change', function() {
        $('#nombre').val('');
        $('#apellido').val('');
        estudianteSeleccionado = null;
    });

    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        
        // Guardar la posición de los controles antes de la actualización
        const controlsContainer = document.querySelector('.controls-container');
        const controlsPosition = controlsContainer.getBoundingClientRect();
        
        fetch(`actualizar_excusa.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                // Solo actualizar el contenido de la tabla, no toda la página
                document.getElementById('tardanzasBody').innerHTML = data;
                
                // Asegurarse de que los controles mantengan su posición
                window.requestAnimationFrame(() => {
                    if (window.innerWidth > 992) {
                        controlsContainer.style.position = 'absolute';
                        controlsContainer.style.right = '15px';
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('btnBuscar').addEventListener('click', function() {
        buscarEstudiante();
    });

    document.getElementById('buscarEstudiante').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            buscarEstudiante();
        }
    });

    // Función para buscar estudiante
    function buscarEstudiante() {
        const busqueda = document.getElementById('buscarEstudiante').value.trim();
        if (busqueda.length > 0) {
            fetch(`buscar_estudiante.php?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('excusasBody').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Función para actualizar la tabla
    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        fetch(`actualizar_excusa.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('excusasBody').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });

    // Función para imprimir el reporte
    document.getElementById('btnImprimir').addEventListener('click', function() {
        // Actualizar la fecha en el encabezado de impresión
        const fechaActual = new Date();
        const fechaFormateada = fechaActual.toLocaleString('es-ES');
        
        // Preparar para imprimir
        window.print();
    });

    function buscarEstudiante() {
        const busqueda = document.getElementById('buscarEstudiante').value.trim();
        if (busqueda.length > 0) {
            fetch(`buscar_estudiante.php?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('excusasBody').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    }

    
    </script>
    <?php
    include '../Componentes/footer.php';?>
</body>
</html>

</script>

