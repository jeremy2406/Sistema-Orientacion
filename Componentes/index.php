<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>
<?php include '../Componentes/conexion.php'; ?>

<?php
$pdo = conectarDB();

$totalEstudiantes = 0;
$totalFaltas = 0;
$totalExcusas = 0;
$totalTardanzas = 0;
$totalCalendario = 0;

if ($pdo) {
    try {
        // Modificar estas líneas para obtener el valor escalar
        $stmt = $pdo->query('SELECT COUNT(*) FROM "Estudiante"');
        $totalEstudiantes = $stmt->fetchColumn();
        
        $stmt = $pdo->query('SELECT COUNT(*) FROM "Faltas"');
        $totalFaltas = $stmt->fetchColumn();
        
        $stmt = $pdo->query('SELECT COUNT(*) FROM "Excusas"');
        $totalExcusas = $stmt->fetchColumn();
        
        $stmt = $pdo->query('SELECT COUNT(*) FROM "Tardanzas"');
        $totalTardanzas = $stmt->fetchColumn();
    } catch (PDOException $e) {
        echo 'Error al obtener los totales: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../Css/Estilos.css">
    <link rel="stylesheet" href="../Css/footer.css">
</head>
<body>
<div class="main-content">
    <h2>Panel de Administración</h2>
    <div class="dashboard">
        <div class="card users">
            <i class="fas fa-users"></i> 
            <span>Estudiantes</span>
            <span><?= $totalEstudiantes?></span>
        </div>
        <div class="card books">
            <i class="fas fa-cogs"></i>
            <span>Faltas</span>
            <span><?= $totalFaltas ?></span>
        </div>
        <div class="card authors">
            <i class="fas fa-user-tie"></i>
            <span>Excusas</span>
            <span><?= $totalExcusas ?></span>
        </div>
        
        <div class="card students">
            <i class="fas fa-graduation-cap"></i>
            <span>Tardanzas</span>
            <span><?= $totalTardanzas ?></span>
        </div>
       
        <div class="card settings">
            <i class="fas fa-cog"></i>
            <span>Configuración</span>
        </div>
    </div>
</div>
</body>
</html>

<?php include '../Componentes/footer.php'; ?>
