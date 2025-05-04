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
    <link rel="stylesheet" href="../Css/Estilos.css">
</head>
<body>
    <div class="body">
        <main class="table">
            <section class="table__header">
                <h1>Registro de Tardanzas</h1>
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
                            <th>Hora</th>
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

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                                    echo "<td>{$row['Grado']}</td>";
                                    echo "<td>{$row['Seccion/Taller']}</td>";
                                    echo "<td>" . date('h:i A', strtotime($row['Fecha'])) . "</td>";
                                    echo "<td>{$row['Justificacion']}</td>";
                                    echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
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
        fetch(`actualizar_tardanzas.php?fecha=${fecha}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('tardanzasBody').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });
    </script>
    <?php
    include '../Componentes/footer.php';?>
</body>
</html>