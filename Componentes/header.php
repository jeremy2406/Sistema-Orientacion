<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Orientación</title>
    <link rel="stylesheet" href="./Css/Estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/Sistema-Orientacion/Css/footer.css">
</head>
<body>
<header>
    <?php
    $nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
    ?>
    <div class="user-info">
        <i class="fas fa-user"></i> <?php echo $nombre_usuario; ?> 
        <div class="dropdown-menu">
            <a href="./Login/cerrar_sesion.php">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</header>
