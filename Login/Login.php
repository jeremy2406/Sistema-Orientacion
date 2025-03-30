<?php
include '../Componentes/conexion.php';
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php
if (isset($_POST['Acceder'])) {
    $matricula = $_POST['Matricula'];
    $nombre = $_POST['Nombre_Estudiante'];
    $contrasena = $_POST['Contrasena'];

    $pdo = conectarDB();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM "Usuarios" WHERE "Matricula" = ? AND "Nombre" = ? AND "Contrasena" = ?');
            $stmt->execute([$matricula, $nombre, $contrasena]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['Nombre'];
                $_SESSION['rol'] = $usuario['Rol'];
                
                header('Location: ../index.php');
                exit();
            } else {
                echo "<script>
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
                            window.location.href='./Login.html';
                        }
                    });
                </script>";
            }
        } catch (PDOException $e) {
            echo 'Error en la consulta: ' . $e->getMessage();
        }
    }
}
?>
</body>
</html>