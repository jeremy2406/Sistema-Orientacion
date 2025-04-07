<?php
include '../Componentes/conexion.php';

if (isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];
    $pdo = conectarDB();

    try {
        $stmt = $pdo->prepare('
            SELECT 
                COUNT(CASE WHEN tipo_falta = \'leve\' THEN 1 END) as leves,
                COUNT(CASE WHEN tipo_falta = \'grave\' THEN 1 END) as graves,
                COUNT(CASE WHEN tipo_falta = \'muy-grave\' THEN 1 END) as muy_graves
            FROM "Faltas"
            WHERE "matricula_estudiantes" = ?
        ');
        $stmt->execute([$id_estudiante]);
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Ensure we return 0 instead of null
        $counts['leves'] = $counts['leves'] ?? 0;
        $counts['graves'] = $counts['graves'] ?? 0;
        $counts['muy_graves'] = $counts['muy_graves'] ?? 0;
        
        header('Content-Type: application/json');
        echo json_encode($counts);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>