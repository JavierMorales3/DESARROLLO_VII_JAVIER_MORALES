<?php
include 'config_sesion.php';

$productos = [
    1 => ['nombre' => 'Laptop', 'precio' => 1200.00],
    2 => ['nombre' => 'Monitor', 'precio' => 300.00],
    3 => ['nombre' => 'Teclado', 'precio' => 90.00],
    4 => ['nombre' => 'Mouse', 'precio' => 20.00],
    5 => ['nombre' => 'Audifonos', 'precio' => 50.00],
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
</head>
<body>
    <h1>Productos Disponibles</h1>
    <a href="ver_carrito.php">Ver Carrito</a>
    <hr>
    <?php foreach ($productos as $id => $producto): ?>
        <div class="producto">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
            <form action="agregar_al_carrito.php" method="post">
                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id); ?>">
                <button type="submit">Agregar al Carrito</button>
            </form>
        </div>
    <?php endforeach; ?>
    <hr>
    <?php
    if (isset($_COOKIE['usuario_compra'])) {
        echo "<p>Hola de nuevo, " . htmlspecialchars($_COOKIE['usuario_compra']) . ".</p>";
    }
    ?>
</body>
</html>