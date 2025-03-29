<?php
function conectarDB() {
    $host = 'aws-0-us-east-1.pooler.supabase.com';
    $dbname = 'postgres';
    $user = 'postgres.kzzpdsbtrujsssojvpzc';
    $password = '1234567891';
    $port = '5432';

    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        return null;
    }
}
?>