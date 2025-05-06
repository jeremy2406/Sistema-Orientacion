<?php
session_start();
include '../Componentes/conexion.php';
define('ROOT_PATH', realpath(__DIR__ . '/../../'));
include_once ROOT_PATH . '/Componentes/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $pdo = conectarDB();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('DELETE FROM "Excusas" WHERE "ID" = ?');
            $result = $stmt->execute([$id]);
            
            if ($result) {
                $_SESSION['swal_message'] = [
                    'title' => '¡Éxito!',
                    'text' => 'Excusa eliminada correctamente',
                    'icon' => 'success'
                ];
            } else {
                $_SESSION['swal_message'] = [
                    'title' => 'Error',
                    'text' => 'No se pudo eliminar la excusa',
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
    
    header('Location: Excusas.php');
    exit();
} else {
    $_SESSION['swal_message'] = [
        'title' => 'Error',
        'text' => 'ID de excusa no válido',
        'icon' => 'error'
    ];
    header('Location: Excusas.php');
    exit();
}
?>