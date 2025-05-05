<?php
ob_start(); // Inicia el buffer de salida para evitar errores de headers

session_start();

// Conexión y configuración
include_once __DIR__ . '/../Componentes/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Componentes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_falta = $_POST['tipo_falta'] ?? '';
    $justificacion = $_POST['justificacion'] ?? '';
    $id_estudiante = $_POST['matricula_estudiantes'] ?? '';

    date_default_timezone_set('America/Santo_Domingo');
    $fecha = date('Y-m-d H:i:s');

    // Puntos asignados según tipo de falta
    $puntos = match($tipo_falta) {
        'leve' => 1,
        'grave' => 3,
        'muy-grave' => 5,
        default => 0
    };

    $pdo = conectarDB();
    if ($pdo) {
        try {
            // Iniciar transacción
            $pdo->beginTransaction();

            // Insertar falta
            $stmt = $pdo->prepare('INSERT INTO "Faltas" ("matricula_estudiantes", "tipo_falta", "fecha", "justificacion") VALUES (?, ?, ?, ?)');
            $stmt->execute([$id_estudiante, $tipo_falta, $fecha, $justificacion]);

            // Sumar puntos
            $stmt = $pdo->prepare('UPDATE "Estudiante" SET "puntos_faltas" = COALESCE("puntos_faltas", 0) + ? WHERE "Matricula" = ?');
            $stmt->execute([$puntos, $id_estudiante]);

            // Obtener nuevos puntos
            $stmt = $pdo->prepare('SELECT "puntos_faltas" FROM "Estudiante" WHERE "Matricula" = ?');
            $stmt->execute([$id_estudiante]);
            $puntosTotales = $stmt->fetchColumn();

            // Determinar nuevo status
            $nuevoStatus = match(true) {
                $puntosTotales >= 5 => 'MUY GRAVE',
                $puntosTotales >= 3 => 'GRAVE',
                default => 'CORRECTO'
            };

            // Actualizar status
            $stmt = $pdo->prepare('UPDATE "Estudiante" SET "Status" = ? WHERE "Matricula" = ?');
            $stmt->execute([$nuevoStatus, $id_estudiante]);

            // Confirmar
            $pdo->commit();

            $_SESSION['swal_message'] = [
                'title' => '¡Éxito!',
                'text' => 'Falta registrada y estado actualizado correctamente',
                'icon' => 'success'
            ];
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['swal_message'] = [
                'title' => 'Error',
                'text' => 'Error al registrar la falta: ' . $e->getMessage(),
                'icon' => 'error'
            ];
        }
    }

    // Redirigir a la página anterior o a BASE_URL
    $redirect_to = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    header('Location: ' . $redirect_to);
    exit();
}

ob_end_flush(); // Finaliza el buffer de salida
?>
