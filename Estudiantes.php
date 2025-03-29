<?php include 'Componentes/header.php'; ?>
<?php include 'Componentes/Nav.php'; ?>
<?php include 'Componentes/conexion.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>
    <link rel="stylesheet" href="./Css/Estudiantes.css">
</head>
<body>
    
</body>
</html>

<div class="body">
    <main class="table">
        <section class="table__header">
            <h1>Estudiantes</h1>
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
                            $stmt = $pdo->query('SELECT "Matricula", "Nombre", "Apellido", "Grado", "Seccion/Taller", "Status" FROM "Estudiantes"');
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $statusClass = str_replace(' ', '-', strtolower($row['Status']));  
                                echo "<tr>";
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

<?php include 'Componentes/footer.php'; ?>