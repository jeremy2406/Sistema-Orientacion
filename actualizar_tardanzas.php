<?php
include 'Componentes/conexion.php';

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $pdo = conectarDB();
    
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('
                SELECT e."Nombre", e."Apellido", e."Seccion/Taller", 
                       t."Fecha", t."Justificacion"
                FROM "Tardanzas" t
                JOIN "Estudiante" e ON t."Matricula_estudiantes" = e."Matricula"
                WHERE DATE(t."Fecha") = ?
                ORDER BY t."Fecha" DESC
            ');
            $stmt->execute([$fecha]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['Nombre']} {$row['Apellido']}</td>";
                echo "<td>{$row['Seccion/Taller']}</td>";
                echo "<td>" . date('h:i A', strtotime($row['Fecha'])) . "</td>";
                echo "<td>{$row['Justificacion']}</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
}
?>