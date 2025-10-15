<?php
include 'config_sesion.php';

if (isset($_GET['id'])) {
    $id_producto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id_producto !== false && $id_producto !== null && isset($_SESSION['carrito'][$id_producto])) {
        unset($_SESSION['carrito'][$id_producto]);
    }
}

header('Location: ver_carrito.php');
exit;
?>
