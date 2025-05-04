<?php
include '../Componentes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $grado = $_POST['grado'] ?? '';
    $taller = $_POST['taller'] ?? '';
    $fecha = $_POST['fecha_excusa'] ?? '';
    $justificacion = $_POST['justificacion'] ?? '';
    
    if (empty($nombre) || empty($apellido) || empty($grado) || empty($taller) || empty($fecha) || empty($justificacion)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
    
    $pdo = conectarDB();
    
    if ($pdo) {
        try {
            // Buscar la matrícula del estudiante
            $checkStmt = $pdo->prepare('SELECT "Matricula" FROM "Estudiante" WHERE "Nombre" = ? AND "Apellido" = ? AND "Grado" = ? AND "Seccion/Taller" = ?');
            $checkStmt->execute([$nombre, $apellido, $grado, $taller]);
            $estudiante = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($estudiante) {
                $matricula = $estudiante['Matricula'];
                
                // Insertar la excusa
                $stmt = $pdo->prepare('INSERT INTO "Excusas" ("Matricula_estudiantes", "Fecha", "Justificacion") VALUES (?, ?, ?)');
                $result = $stmt->execute([$matricula, $fecha, $justificacion]);
                
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al registrar la excusa']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado en la base de datos']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>