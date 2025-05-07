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
        SELECT 
            e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado", 
             ex."Fecha", ex."Tutor", ex."Justificacion"
        FROM "Excusas" ex
        JOIN "Estudiante" e ON ex."Matricula_estudiantes" = e."Matricula"
        WHERE LOWER(e."Nombre") LIKE LOWER(?) OR LOWER(e."Apellido") LIKE LOWER(?)
        ORDER BY ex."Fecha" DESC
    ');
    
    $parametro = "%{$busqueda}%";
    $stmt->execute([$parametro, $parametro]);
    
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
        echo "<tr><td colspan='6' class='text-center'>No se encontraron faltas para el estudiante: '{$busqueda}'</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='6' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
}
?>