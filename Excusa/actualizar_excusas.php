<?php
include '../Componentes/conexion.php';

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $pdo = conectarDB();
    
    if ($pdo) {
        try {
            // Modificamos la consulta para asegurarnos de que funcione correctamente
            $stmt = $pdo->prepare('
                SELECT e."Nombre", e."Apellido", e."Seccion/Taller", e."Grado",
                       ex."Fecha", ex."Tutor",ex."Justificacion"
                FROM "Excusas" ex
                JOIN "Estudiante" e ON ex."Matricula_estudiantes" = e."Matricula"
                WHERE CAST(ex."Fecha" AS DATE) = ?
                ORDER BY ex."Fecha" DESC
            ');
            $stmt->execute([$fecha]);
            
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
                echo "<tr><td colspan='6' class='text-center'>No hay excusas registradas para esta fecha</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Error de conexión a la base de datos</td></tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Fecha no especificada</td></tr>";
}
?>