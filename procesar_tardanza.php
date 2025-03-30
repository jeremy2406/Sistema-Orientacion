<?php
include 'Componentes/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $justificacion = $_POST['justificacion'];
    // Add your logic here to save the tardanza to the database
    
    // Redirect back to the students page
    header('Location: estudiantes.php');
    exit();
}
?>