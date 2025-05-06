<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_set_cookie_params([ 
        'httponly' => true, 
        'secure' => true,  
        'samesite' => 'Strict' 
    ]); 
    session_start(); 
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Orientación</title>
    <link rel="shortcut icon" href="Imagenes/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Css/Estilos.css">
    <link rel="stylesheet" href="../../Css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Madimi+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <?php
    $nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
    ?>
    <div class="user-info">
        <i class="fas fa-user"></i> <?php echo $nombre_usuario; ?> 
        <div class="dropdown-menu">
            <a href="../Login/Login.html">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</header>
</body>

