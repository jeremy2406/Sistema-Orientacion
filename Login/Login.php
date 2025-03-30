<?php
session_start();
include '../Componentes/conexion.php';

// Solo procesamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Acceder'])) {
    $matricula = $_POST['Matricula'] ?? '';
    $nombre = $_POST['Nombre_Estudiante'] ?? '';
    $contrasena = $_POST['Contrasena'] ?? '';

    $pdo = conectarDB();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM "Usuarios" WHERE "Matricula" = ? AND "Nombre" = ? AND "Contrasena" = ?');
            $stmt->execute([$matricula, $nombre, $contrasena]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verificar que las claves existan antes de usarlas
                $_SESSION['usuario_id'] = $usuario['id'] ?? null;
                $_SESSION['nombre'] = $usuario['Nombre'] ?? '';
                $_SESSION['rol'] = $usuario['Rol'] ?? 'estudiante'; // Valor por defecto si no existe

                // Asegurarse de que no haya salida antes de la redirección
                if (ob_get_length()) ob_clean();
                header('Location: ../index.php');
                exit();
            } else {
                $errorLogin = true;
            }
        } catch (PDOException $e) {
            $dbError = $e->getMessage();
        }
    }
}

// Asegurarse de que no haya salida antes de este punto
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php if (isset($errorLogin) && $errorLogin): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error de acceso',
            text: 'Usuario o contraseña incorrectos',
            confirmButtonColor: '#0D3757',
            customClass: {
                popup: 'swal2-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './Login.html';
            }
        });
    </script>
<?php endif; ?>

<?php if (isset($dbError)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error de base de datos',
            text: <?= json_encode($dbError) ?>,
            confirmButtonColor: '#0D3757'
        });
    </script>
<?php endif; ?>
</body>
</html>
