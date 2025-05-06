<?php
include '../Componentes/conexion.php';

// Verificar que se recibieron los parámetros necesarios
if (isset($_GET['grado']) && isset($_GET['taller']) && isset($_GET['term'])) {
    $grado = $_GET['grado'];
    $taller = $_GET['taller'];
    $term = $_GET['term'];
    
    $pdo = conectarDB();
    
    if ($pdo) {
        try {
            // Buscar estudiantes que coincidan con el grado, sección/taller y término de búsqueda
            $stmt = $pdo->prepare('
                SELECT "Matricula", "Nombre", "Apellido" 
                FROM "Estudiante" 
                WHERE "Grado" = ? 
                AND "Seccion/Taller" = ? 
                AND (LOWER("Nombre") LIKE LOWER(?) OR LOWER("Apellido") LIKE LOWER(?))
                ORDER BY "Nombre", "Apellido"
                LIMIT 10
            ');
            
            $searchTerm = '%' . $term . '%';
            $stmt->execute([$grado, $taller, $searchTerm, $searchTerm]);
            
            $estudiantes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $estudiantes[] = [
                    'id' => $row['Matricula'],
                    'nombre' => $row['Nombre'],
                    'apellido' => $row['Apellido'],
                    'label' => $row['Nombre'] . ' ' . $row['Apellido'],
                    'value' => $row['Nombre']
                ];
            }
            
            // Devolver resultados en formato JSON
            header('Content-Type: application/json');
            echo json_encode($estudiantes);
            
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Parámetros incompletos']);
}
?>