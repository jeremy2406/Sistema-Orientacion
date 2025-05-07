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
            // Iniciar transacción
            $pdo->beginTransaction();
            
            // Verificar si el estudiante existe
            $checkStmt = $pdo->prepare('SELECT "Matricula" FROM "Estudiante" WHERE "Matricula" = ?');
            $checkStmt->execute([$id_estudiante]);

            if ($checkStmt->fetch()) {
                // Insertar tardanza
                $stmt = $pdo->prepare('INSERT INTO "Tardanzas" ("Matricula_estudiantes", "Fecha", "Justificacion") VALUES (?, ?, ?)');
                $result = $stmt->execute([$id_estudiante, $fecha, $justificacion]);

                if ($result) {
                    // Actualizar el contador de tardanzas del estudiante
                    // Modificamos esta línea para convertir explícitamente a entero
                    $updateStmt = $pdo->prepare('UPDATE "Estudiante" SET "Tardanzas" = CAST(COALESCE("Tardanzas", \'0\') AS INTEGER) + 1 WHERE "Matricula" = ?');
                    $updateStmt->execute([$id_estudiante]);
                    
                    // Obtener el número actualizado de tardanzas
                    $countStmt = $pdo->prepare('SELECT "Tardanzas" FROM "Estudiante" WHERE "Matricula" = ?');
                    $countStmt->execute([$id_estudiante]);
                    $tardanzas = $countStmt->fetchColumn();
                    
                    // Confirmar la transacción
                    $pdo->commit();
                    
                    // Devolver respuesta con el número de tardanzas actualizado
                    $_SESSION['swal_message'] = [
                        'title' => '¡Éxito!',
                        'text' => 'Tardanza registrada correctamente',
                        'icon' => 'success',
                        'tardanzas' => $tardanzas
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
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            
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
