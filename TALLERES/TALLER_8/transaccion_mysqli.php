<?php
require_once "config_mysqli.php";
mysqli_begin_transaction($conn);

try {
    // 1. Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta de usuario: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $email);
    $nombre = "Nuevo Usuario";
    $email = "nuevo@example.com";
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        throw new Exception("Error al ejecutar la inserción de usuario: " . mysqli_stmt_error($stmt));
    }   
    $usuario_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // 2. Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta de publicación: " . mysqli_error($conn));
    }   
    mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $titulo, $contenido);
    $titulo = "Nueva Publicación";
    $contenido = "Contenido de la nueva publicación";
    $result = mysqli_stmt_execute($stmt); 
    if (!$result) {
        throw new Exception("Error al ejecutar la inserción de publicación: " . mysqli_stmt_error($stmt));
    }   
    mysqli_stmt_close($stmt);
    mysqli_commit($conn);
    echo "Transacción completada con éxito.";
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error en la transacción: " . $e->getMessage();
}

mysqli_close($conn);
?>