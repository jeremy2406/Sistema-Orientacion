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
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <h1>Registro de Faltas</h1>
                <div class="date-selector">
                    <label for="fecha">Seleccionar Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>">
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

    <script>
    document.getElementById('fecha').addEventListener('change', function() {
        const fecha = this.value;
        fetch(`actualizar_faltas.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('faltasBody').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });
    
    // Modal handling
    const modal = document.getElementById("studentModal");
    const span = document.getElementsByClassName("close")[0];
    
    span.onclick = function() {
        modal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>

    <?php include '../Componentes/footer.php';?>
</body>
</html>