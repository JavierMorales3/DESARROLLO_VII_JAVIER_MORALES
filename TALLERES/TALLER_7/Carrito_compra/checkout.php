<?php
include 'config_sesion.php';

$resumen_compra = $_SESSION['carrito'] ?? [];
$total_compra = 0;
foreach ($resumen_compra as $item) {
    $total_compra += $item['precio'] * $item['cantidad'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!empty($nombre_usuario)) {
        $duracion = time() + (86400); 
        $opciones_cookie = [
            'expires' => $duracion,
            'path' => '/',
            'httponly' => true,
            'secure' => true, 
            'samesite' => 'Lax'
        ];
        setcookie('usuario_compra', $nombre_usuario, $opciones_cookie);
    }

    unset($_SESSION['carrito']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body>
    <h1>Resumen Compra</h1>
    <?php if (!empty($resumen_compra)): ?>
        <ul>
            <?php foreach ($resumen_compra as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['nombre']); ?> (Cantidad: <?php echo htmlspecialchars($item['cantidad']); ?>) y Subtotal: $<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <h3>Total: $<?php echo number_format($total_compra, 2); ?></h3>
        <hr>
        
        <?php if (!isset($_COOKIE['usuario_compra'])): ?>
            <form action="checkout.php" method="post">
                <label for="nombre_usuario">Nombre:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>
                <button type="submit">Guardar Nombre</button>
            </form>
        <?php else: ?>
            <p>Hola de nuevo, <?php echo htmlspecialchars($_COOKIE['usuario_compra']); ?> Gracias por la compra</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No hay productos para terminar la compra. Debe, <a href="productos.php">vuelve a la tienda</a>.</p>
    <?php endif; ?>
</body>
</html>
