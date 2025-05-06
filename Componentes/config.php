<?php
    // Detecta si estás en localhost o en un servidor
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        define('BASE_URL', '/Sistema-Orientacion/'); // RUTA EN LOCAL
    } else {
        define('BASE_URL', '/'); // RUTA EN PRODUCCIÓN (Render)
    }
    
?>