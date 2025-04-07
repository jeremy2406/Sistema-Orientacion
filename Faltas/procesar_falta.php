<?php
session_start();
include '../Componentes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_falta = $_POST['tipo_falta'];
    $justificacion = $_POST['justificacion'];
    $id_estudiante = $_POST['matricula_estudiantes'];
    
    date_default_timezone_set('America/Santo_Domingo');
    $fecha = date('Y-m-d H:i:s');

    // Puntos según tipo de falta
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

            // Insertar la falta
            $stmt = $pdo->prepare('INSERT INTO "Faltas" ("matricula_estudiantes", "tipo_falta", "fecha", "justificacion") VALUES (?, ?, ?, ?)');
            $stmt->execute([$id_estudiante, $tipo_falta, $fecha, $justificacion]);

            // Actualizar puntos del estudiante
            $stmt = $pdo->prepare('UPDATE "Estudiante" SET "puntos_faltas" = COALESCE("puntos_faltas", 0) + ? WHERE "Matricula" = ?');
            $stmt->execute([$puntos, $id_estudiante]);

            // Obtener puntos totales
            $stmt = $pdo->prepare('SELECT "puntos_faltas" FROM "Estudiante" WHERE "Matricula" = ?');
            $stmt->execute([$id_estudiante]);
            $puntosTotales = $stmt->fetchColumn();

            // Determinar nuevo status
            $nuevoStatus = match(true) {
                $puntosTotales >= 10 => 'MUY GRAVE',
                $puntosTotales >= 5 => 'GRAVE',
                default => 'CORRECTO'
            };

            // Actualizar status
            $stmt = $pdo->prepare('UPDATE "Estudiante" SET "Status" = ? WHERE "Matricula" = ?');
            $stmt->execute([$nuevoStatus, $id_estudiante]);

            // Confirmar transacción
            $pdo->commit();

            $_SESSION['swal_message'] = [
                'title' => '¡Éxito!',
                'text' => 'Falta registrada y estado actualizado correctamente',
                'icon' => 'success'
            ];
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $pdo->rollBack();
            $_SESSION['swal_message'] = [
                'title' => 'Error',
                'text' => 'Error al registrar la falta: ' . $e->getMessage(),
                'icon' => 'error'
            ];
        }
    }
    
    header('Location: ../Estudiantes/Estudiantes.php');
    exit();
}
?>