<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tardanzas</title>
    <link rel="stylesheet" href="../Css/Tardanzas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/Css/Tardanzas.css">
    <link rel="stylesheet" href="../Css/Estilos.css">
    <style>
        .btn-amarillo {
            background-color: #FFD700;
            color: #ffffffff;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .btn-amarillo:hover {
            background-color: #FFC107;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        @media print {
            .controls-container, .table__header button, .no-print {
                display: none !important;
            }
            
            body, .body, .table, .table__body {
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                background-color: white !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                padding: 0 !important;
            }
            
            .table__header {
                display: none !important;
            }
            
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 0 auto !important;
            }
            
            th, td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
                text-align: center !important;
            }
            
            th {
                background-color: #f2f2f2 !important;
                font-weight: bold !important;
            }
            
            .print-header {
                display: block !important;
                text-align: center !important;
                margin: 0 auto 20px auto !important;
                width: 100% !important;
            }
            
            .print-header h2, .print-header h3 {
                margin: 5px 0 !important;
            }
            
            .print-footer {
                display: block !important;
                text-align: center !important;
                margin-top: 20px !important;
                font-size: 12px !important;
                width: 100% !important;
            }
            
            /* Ocultar el menú y el usuario en el header durante la impresión */
            header, nav, aside, .usuario-info, footer {
                display: none !important;
            }
            
            /* Contenedor principal centrado */
            @page {
                size: A4;
                margin: 1cm;
            }
            
            .print-container {
                display: block !important;
                width: 90% !important;
                max-width: 800px !important;
                margin: 0 auto !important;
                padding: 20px !important;
            }
        }
        
        .print-header, .print-footer, .print-container {
            display: none;
        }
    </style>
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <div class="header-content">
                    <h1>Registro de Tardanzas</h1>
                    <div class="controls-container">
                        <div class="date-selector">
                            <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="search-container">
                            <input type="text" id="buscarEstudiante" placeholder="Buscar por nombre o apellido">
                            <button id="btnBuscar">Buscar</button>
                            <button id="btnImprimir" class="btn-amarillo">Imprimir Reporte</button>
                        </div>
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
                            <th>Estudiante</th>
                            <th>Grado</th>
                            <th>Sección/Taller</th>
                            <th>Hora</th>
                            <th>Fecha</th>
                            <th>Justificación</th>
                        </tr>
                    </thead>
                    <tbody id="tardanzasBody">
                        <?php
                        $pdo = conectarDB();
                        if ($pdo) {
                            try {
                                $fecha_actual = date('Y-m-d');
                                $stmt = $pdo->prepare('
                                    SELECT e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado", 
                                           t."Fecha", t."Justificacion"
                                    FROM "Tardanzas" t
                                    JOIN "Estudiante" e ON t."Matricula_estudiantes" = e."Matricula"
                                    WHERE DATE(t."Fecha") = ?
                                    ORDER BY t."Fecha" DESC
                                ');
                                $stmt->execute([$fecha_actual]);

                                $count = 0;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $count++;
                                    echo "<tr>";
                                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                                    echo "<td>{$row['Grado']}</td>";
                                    echo "<td>{$row['Seccion/Taller']}</td>";
                                    echo "<td>" . date('h:i A', strtotime($row['Fecha'])) . "</td>";
                                    echo "<td>" . date('d/m/Y', strtotime($row['Fecha'])) . "</td>";
                                    echo "<td>{$row['Justificacion']}</td>";
                                    echo "</tr>";
                                }
                                
                                if ($count === 0) {
                                    echo "<tr><td colspan='6' class='text-center'>No hay tardanzas registradas para esta fecha</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
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

    <script>
    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        
        // Guardar la posición de los controles antes de la actualización
        const controlsContainer = document.querySelector('.controls-container');
        const controlsPosition = controlsContainer.getBoundingClientRect();
        
        fetch(`actualizar_tardanzas.php?fecha=${fecha}`)
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
                    document.getElementById('tardanzasBody').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    }
    </script>
    <?php
    include '../Componentes/footer.php';?>
</body>
</html>