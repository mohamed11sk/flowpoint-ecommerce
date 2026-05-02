<?php
$dsn = 'mysql:host=localhost;dbname=ecommerce_store;charset=utf8';
$user = 'root';
$pass = '';

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>

