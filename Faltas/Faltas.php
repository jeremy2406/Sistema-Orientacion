<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Faltas</title>
    <link rel="stylesheet" href="../Css/Faltas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/Css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <div class="header-content">
                    <h1>Registro de Faltas</h1>
                    <div class="controls-wrapper">
                        <div class="date-control">
                            <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" class="date-input">
                        </div>
                        <div class="search-control">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="buscarEstudiante" placeholder="Buscar por nombre..." class="search-input">
                        </div>
                        <div class="buttons-control">
                            <button id="btnBuscar" class="btn-buscar">Buscar</button>
                            <button id="btnReset" class="btn-resetear">Resetear</button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Grado</th>
                            <th>Sección/Taller</th>
                            <th>Tipo de Falta</th>
                            <th>Fecha</th>
                            <th>Justificación</th>
                        </tr>
                    </thead>
                    <tbody id="faltasBody">
                        <?php
                        $pdo = conectarDB();
                        if ($pdo) {
                            try {
                                $fecha_actual = date('Y-m-d');
                                $stmt = $pdo->prepare('
                                    SELECT 
                                        e."Matricula",
                                        e."Nombre", 
                                        e."Apellido", 
                                        e."Seccion/Taller", 
                                        e."Grado", 
                                        f."fecha", 
                                        f."tipo_falta", 
                                        f."justificacion"
                                    FROM "Faltas" f
                                    JOIN "Estudiante" e ON f."matricula_estudiantes" = e."Matricula"
                                    WHERE DATE(f."fecha") = ?
                                    ORDER BY f."fecha" DESC
                                ');
                                $stmt->execute([$fecha_actual]);

                                $count = 0;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $count++;
                                    $tipo_falta_class = strtolower(str_replace(' ', '-', $row['tipo_falta']));
                                    echo "<tr>";
                                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                                    echo "<td>{$row['Grado']}</td>";
                                    echo "<td>{$row['Seccion/Taller']}</td>";
                                    echo "<td><span class='falta-status {$tipo_falta_class}'>{$row['tipo_falta']}</span></td>";
                                    echo "<td>" . date('d/m/Y h:i A', strtotime($row['fecha'])) . "</td>";
                                    echo "<td>{$row['justificacion']}</td>";
                                    echo "</tr>";
                                }

                                if ($count === 0) {
                                    echo "<tr><td colspan='6' class='text-center'>No hay faltas registradas para esta fecha</td></tr>";
                                }
                                
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <style>
    .table__header {
        background-color: #0D3757;
        padding: 15px 20px;
        color: white;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        gap: 90px; /* Espacio entre el título y los controles */
    }

    .header-content h1 {
        margin: 0;
        font-size: 24px;
        flex: 0 0 auto;
        padding-right: 90px; /* Espacio a la derecha del título */
    }

    .controls-wrapper {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 15px;
        margin-left: auto;
        padding-left: 90px; /* Espacio a la izquierda de los controles */
    }

    .date-control {
        background: white;
        border-radius: 5px;
        overflow: hidden;
    }

    .date-input {
        border: none;
        outline: none;
        padding: 8px 12px;
        font-size: 14px;
        color: #333;
    }

    .search-control {
        position: relative;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        width: 300px;
    }

    .search-icon {
        color: white;
        margin-right: 8px;
    }

    .search-input {
        background: transparent;
        border: none;
        outline: none;
        color: white;
        width: 100%;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .buttons-control {
        display: flex;
        gap: 10px;
    }

    .btn-buscar, .btn-resetear {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-buscar {
        background: #3498db;
        color: white;
    }

    .btn-resetear {
        background: #e74c3c;
        color: white;
    }

    /* Estilos responsivos */
    @media (min-width: 992px) {
        .controls-wrapper {
            flex-direction: row;
            align-items: center;
        }
    }

    @media (max-width: 991px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .controls-wrapper {
            width: 100%;
            justify-content: flex-end;
            padding-left: 0;
        }
        
        .header-content h1 {
            padding-right: 0;
        }
    }

    @media (max-width: 768px) {
        .controls-wrapper {
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        
        .search-control {
            width: 100%;
        }
        
        .buttons-control {
            width: 100%;
        }
        
        .btn-buscar, .btn-resetear {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .header-content h1 {
            width: 100%;
            text-align: center;
        }
        
        .controls-wrapper {
            align-items: center;
        }
    }
    </style>

    <script>
    // Función para actualizar la tabla
    function actualizarTabla(fecha) {
        fetch(`actualizar_faltas.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('faltasBody').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para buscar estudiante
    function buscarEstudiante() {
        const busqueda = document.getElementById('buscarEstudiante').value.trim();
        if (busqueda.length > 0) {
            fetch(`buscar_estudiante.php?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('faltasBody').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Event Listeners
    document.getElementById('fecha').addEventListener('change', function() {
        actualizarTabla(this.value);
    });

    document.getElementById('btnBuscar').addEventListener('click', buscarEstudiante);

    document.getElementById('buscarEstudiante').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            buscarEstudiante();
        }
    });

    document.getElementById('btnReset').addEventListener('click', function() {
        document.getElementById('buscarEstudiante').value = '';
        document.getElementById('fecha').value = '<?php echo date('Y-m-d'); ?>';
        actualizarTabla('<?php echo date('Y-m-d'); ?>');
    });
    </script>

    <?php include '../Componentes/footer.php';?>
</body>
</html>