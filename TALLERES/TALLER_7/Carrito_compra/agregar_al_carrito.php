<?php
include 'config_sesion.php';

$productos = [
    1 => ['nombre' => 'Laptop', 'precio' => 1200.00],
    2 => ['nombre' => 'Monitor', 'precio' => 300.00],
    3 => ['nombre' => 'Teclado', 'precio' => 90.00],
    4 => ['nombre' => 'Mouse', 'precio' => 20.00],
    5 => ['nombre' => 'Audifonos', 'precio' => 50.00],
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);

    if ($id_producto !== false && $id_producto !== null && array_key_exists($id_producto, $productos)) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]['cantidad']++;
        } else {
            $_SESSION['carrito'][$id_producto] = [
                'nombre' => $productos[$id_producto]['nombre'],
                'precio' => $productos[$id_producto]['precio'],
                'cantidad' => 1,
            ];
        }
        header('Location: ver_carrito.php');
        exit;
    } else {
        header('Location: productos.php?error=producto_invalido');
        exit;
    }


}
?>