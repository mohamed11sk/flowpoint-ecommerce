<?php
$dsn = 'mysql:host=sql308.infinityfree.com;dbname=if0_41799164_connection;charset=utf8';
$user = 'if0_41799164';
$pass = 'b1TmPsLeIxkcb0';

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

