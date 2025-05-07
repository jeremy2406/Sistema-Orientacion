<?php
include '../Componentes/conexion.php';

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $pdo = conectarDB();
    
    if ($pdo) {
        try {
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
            $stmt->execute([$fecha]);
            
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
            echo "<tr><td colspan='6' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
}
?>

<style>
    .text-center {
    padding-left: 250px;
    text-align: center;
}
</style>