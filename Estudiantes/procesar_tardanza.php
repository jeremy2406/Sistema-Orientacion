<?php
ob_start(); // Inicia el buffer de salida para evitar errores de headers

session_start();

// Conexión a la base de datos
include_once __DIR__ . '/../Componentes/conexion.php';

// Incluye configuración de rutas (detecta local o producción automáticamente)
include_once $_SERVER['DOCUMENT_ROOT'] . '/Componentes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $justificacion = $_POST['justificacion'] ?? '';
    $id_estudiante = $_POST['estudiante_id'] ?? '';

    // Fecha y hora de RD
    date_default_timezone_set('America/Santo_Domingo');
    $fecha = date('Y-m-d H:i:s');

    $pdo = conectarDB();
    if ($pdo) {
        try {
            // Verificar si el estudiante existe
            $checkStmt = $pdo->prepare('SELECT "Matricula" FROM "Estudiante" WHERE "Matricula" = ?');
            $checkStmt->execute([$id_estudiante]);

            if ($checkStmt->fetch()) {
                // Insertar tardanza
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

    // Redirigir al formulario anterior (página desde donde se envió)
    $redirect_to = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    header('Location: ' . $redirect_to);
    exit();
}

ob_end_flush(); // Cierra el buffer de salida
?>
