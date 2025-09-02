<?php

const INVENTARIO_FILE = 'inventarios.json';

function leerInventario(): array {
    if (!file_exists(INVENTARIO_FILE)) {
        return [];
    }
    $json_content = file_get_contents(INVENTARIO_FILE);
    return json_decode($json_content, true);
}

function ordenarInventario(array $inventario): array {
    usort($inventario, function($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });
    return $inventario;
}

function mostrarInventario(array $inventario): void {
    $inventario_ordenado = ordenarInventario($inventario);
    echo "Resumen del Inventario\n";
    echo "--------------------------------------------------\n";
    foreach ($inventario_ordenado as $producto) {
        echo "Producto: " . $producto['nombre'] . ", Precio: $" . number_format($producto['precio'], 2) . ", Cantidad: " . $producto['cantidad'] . "\n";
    }
    echo "--------------------------------------------------\n\n";
}


function calcularValorTotal(array $inventario): float {
    $valores = array_map(function($producto) {
        return $producto['precio'] * $producto['cantidad'];
    }, $inventario);
    return array_sum($valores);
}


function generarInformeStockBajo(array $inventario, int $umbral = 5): array {
    $stock_bajo = array_filter($inventario, function($producto) use ($umbral) {
        return $producto['cantidad'] < $umbral;
    });
    return ordenarInventario($stock_bajo);
}


$inventario = leerInventario();

if (empty($inventario)) {
    echo "No se pudo leer el inventario o el archivo esta vacio.\n";
    exit;
}

//Inventario ordenado
mostrarInventario($inventario);

$valor_total = calcularValorTotal($inventario);
echo "Valor Total del Inventario\n";
echo "El valor total de todo el inventario es: $" . number_format($valor_total, 2) . "\n\n";

$productos_stock_bajo = generarInformeStockBajo($inventario);

echo "Informe de Productos con Stock Bajo\n";

if (empty($productos_stock_bajo)) {
    echo "Todos los productos tienen un stock suficiente\n";
} else {
    foreach ($productos_stock_bajo as $producto) {
        echo "Producto: " . $producto['nombre'] . ", Cantidad actual: " . $producto['cantidad'] . "\n";
    }
}


?>