<?php
session_start();
include '../Componentes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $justificacion = $_POST['justificacion'];
    $id_estudiante = $_POST['estudiante_id']; 
    
    date_default_timezone_set('America/Santo_Domingo');
    $fecha = date('Y-m-d H:i:s', strtotime('now')); 

    $pdo = conectarDB();
    if ($pdo) {
        try {
            $checkStmt = $pdo->prepare('SELECT "Matricula" FROM "Estudiante" WHERE "Matricula" = ?');
            $checkStmt->execute([$id_estudiante]); 
            
            if ($checkStmt->fetch()) {
                $stmt = $pdo->prepare('INSERT INTO "Tardanzas" ("Matricula_estudiantes", "Fecha", "Justificacion") VALUES (?, ?, ?)');
                $result = $stmt->execute([$id_estudiante, $fecha, $justificacion]);
                
                if ($result) {
                    $_SESSION['swal_message'] = [
                        'title' => '¡Éxito!',
                        'text' => 'Tardanza registrada correctamente',
                        'icon' => 'success'
                    ];
                }
            } else {
                $_SESSION['swal_message'] = [
                    'title' => 'Error',
                    'text' => 'Estudiante no encontrado en la base de datos',
                    'icon' => 'error'
                ];
            }
        } catch (PDOException $e) {
            $_SESSION['swal_message'] = [
                'title' => 'Error de Base de Datos',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ];
        }
    }
    
    header('Location: ../Estudiantes/Estudiantes.php');
    exit();
}
?>