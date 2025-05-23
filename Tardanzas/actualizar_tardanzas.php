<?php
include '../Componentes/conexion.php';

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$pdo = conectarDB();
if (!$pdo) {
    echo "<tr><td colspan='6' class='text-center'>Error de conexión a la base de datos</td></tr>";
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado", 
               t."Fecha", t."Justificacion"
        FROM "Tardanzas" t
        JOIN "Estudiante" e ON t."Matricula_estudiantes" = e."Matricula"
        WHERE DATE(t."Fecha") = ?
        ORDER BY t."Fecha" DESC
    ');
    $stmt->execute([$fecha]);

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
?>
<style>
    .text-center {
    text-align: center;
}
</style>