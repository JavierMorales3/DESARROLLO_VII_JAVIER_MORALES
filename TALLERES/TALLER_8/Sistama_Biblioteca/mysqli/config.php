<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'biblioteca';
$port = 3306;

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Error de conexión a la base de datos: ' . htmlspecialchars($mysqli->connect_error));
}
$mysqli->set_charset('utf8mb4');
