<?php
$host = '127.0.0.1';
$db   = 'biblioteca';
$user = 'root';
$pass = '123456';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Error de conexión: ' . htmlspecialchars($e->getMessage()));
}
