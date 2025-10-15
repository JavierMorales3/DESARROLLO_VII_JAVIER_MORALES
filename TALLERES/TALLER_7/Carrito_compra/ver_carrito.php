<?php
include 'config_sesion.php';

$total_compra = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
</head>
<body>
    <h1>Carrito</h1>
    <a href="productos.php">Volver a Productos</a>
    <hr>
    <?php if (empty($_SESSION['carrito'])): ?>
        <p>Carrito vacio</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrito'] as $id => $item):
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total_compra += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="eliminar_del_carrito.php?id=<?php echo htmlspecialchars($id); ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total de compra</td>
                    <td>$<?php echo number_format($total_compra, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <hr>
        <a href="checkout.php">Finalizar compra</a>
    <?php endif; ?>
</body>
</html>
