<?php
require_once "config_pdo.php";

try {
    $pdo->beginTransaction();

    // 1. Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nombre' => 'Nuevo Usuario', ':email' => 'nuevo@example.com']);
    if ($stmt->errorCode() !== '00000') {
        throw new Exception("Error al insertar usuario: " . $stmt->errorInfo()[2]);
    }  
    $usuario_id = $pdo->lastInsertId();

    // 2. Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (:usuario_id, :titulo, :contenido)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':titulo' => 'Nueva Publicación',
        ':contenido' => 'Contenido de la nueva publicación'
    ]);
    if ($stmt->errorCode() !== '00000') {
        throw new Exception("Error al insertar publicación: " . $stmt->errorInfo()[2]);
    }

    $pdo->commit();
    echo "Transacción completada con éxito.";
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error en la transacción: " . $e->getMessage();
}
?>