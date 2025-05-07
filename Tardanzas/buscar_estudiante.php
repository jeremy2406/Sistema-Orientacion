<?php
include '../Componentes/conexion.php';

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

if (empty($busqueda)) {
    echo "<tr><td colspan='6' class='text-center'>Por favor ingrese un nombre o apellido para buscar</td></tr>";
    exit;
}

$pdo = conectarDB();
if (!$pdo) {
    echo "<tr><td colspan='6' class='text-center'>Error de conexión a la base de datos</td></tr>";
    exit;
}

try {
    // Buscar estudiantes por nombre o apellido
    $stmt = $pdo->prepare('
        SELECT e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado", 
               t."Fecha", t."Justificacion"
        FROM "Tardanzas" t
        JOIN "Estudiante" e ON t."Matricula_estudiantes" = e."Matricula"
        WHERE LOWER(e."Nombre") LIKE LOWER(?) OR LOWER(e."Apellido") LIKE LOWER(?)
        ORDER BY t."Fecha" DESC
    ');
    
    $parametro = "%{$busqueda}%";
    $stmt->execute([$parametro, $parametro]);
    
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
        echo "<tr><td colspan='6' class='text-center'>No se encontraron tardanzas para el estudiante: '{$busqueda}'</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
}
?>