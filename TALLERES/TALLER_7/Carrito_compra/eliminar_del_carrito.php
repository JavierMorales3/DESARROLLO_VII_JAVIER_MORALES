<?php
include 'config_sesion.php';
if (isset($_GET['id'])) {
    $id_producto =  filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
}
?>