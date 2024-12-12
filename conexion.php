<?php
// conexión a la base de datos
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = 'OLIMPIADAs2603';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
