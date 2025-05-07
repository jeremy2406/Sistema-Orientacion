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
    <link rel="stylesheet" href="<?= BASE_URL ?>/Css/Faltas.css">
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
                            <button id="btnImprimir" class="btn-amarillo">Imprimir Reporte</button>
                        </div>
                    </div>
                </div>
            </section>
            <div class="print-header">
                <h2>Instituto Politécnico Industrial Don Bosco</h2>
                <h3>Reporte de Faltas</h3>
                <p>Fecha de impresión: <?php echo date('d/m/Y H:i:s'); ?></p>
            </div>
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
            <div class="print-footer">
                <p>Este documento es un reporte oficial de faltas del Instituto Politécnico José María Mancebo.</p>
                <p>Generado por el Sistema de Orientación.</p>
            </div>
        </main>
    </div>
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

    // Función para imprimir el reporte
    document.getElementById('btnImprimir').addEventListener('click', function() {
        // Actualizar la fecha en el encabezado de impresión
        const fechaActual = new Date();
        const fechaFormateada = fechaActual.toLocaleString('es-ES');
        
        // Preparar para imprimir
        window.print();
    });
    </script>

    <?php include '../Componentes/footer.php';?>
</body>
</html>