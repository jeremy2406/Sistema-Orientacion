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
        WHERE LOWER(e."Nombre") LIKE LOWER(?) OR LOWER(e."Apellido") LIKE LOWER(?)
        ORDER BY f."fecha" DESC
    ');
    
    $parametro = "%{$busqueda}%";
    $stmt->execute([$parametro, $parametro]);
    
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
        echo "<tr><td colspan='6' class='text-center'>No se encontraron faltas para el estudiante: '{$busqueda}'</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='6' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
}
?>