<?php

include 'Funciones_gimnasio.php';

$precios = [
    'basica' => 80,
    'premium' => 120,
    'vip' => 180,
    'familiar' => 250,
    'corporativa' => 200
];

$miembros = [
    'Juan Perez' => ['tipo' => 'premium', 'antiguedad' => 15],
    'Ana Garcia' => ['tipo' => 'basica', 'antiguedad' => 2],
    'Carlos Lopez' => ['tipo' => 'vip', 'antiguedad' => 30],
    'Maria Rodriguez' => ['tipo' => 'familiar', 'antiguedad' => 8],
    'Luis Martinez' => ['tipo' => 'corporativa', 'antiguedad' => 18]
];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Membresias Gimnasio (Parcial)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; color: #555; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<h1>Detalle de Membresias</h1>

<table>
    <thead>
        <tr>
            <th>Nombre del Miembro</th>
            <th>Tipo de Membresia</th>
            <th>Antiguedad</th>
            <th>Cuota Inicial</th>
            <th>Descuento</th>
            <th>Monto Descuento</th>
            <th>Costo Seguro Medico</th>
            <th>Cuota Final</th>
        </tr>
    </thead>
    <tbody>";

foreach ($miembros as $nombre => $datos) {
    $tipo = $datos['tipo'];
    $antiguedad = $datos['antiguedad'];
    $cuota = $precios[$tipo];

    $descuento_porcentaje = calcular_promocion($antiguedad);
    $seguro_medico = calcular_seguro_medico($cuota);
    $cuota_final = calcular_cuota_final($cuota, $descuento_porcentaje, $seguro_medico);
    $descuento_monto = $cuota * ($descuento_porcentaje / 100);

    echo "<tr>";
    echo "<td>" . htmlspecialchars($nombre) . "</td>";
    echo "<td>" . htmlspecialchars($tipo) . "</td>";
    echo "<td>" . htmlspecialchars($antiguedad) . "</td>";
    echo "<td>$" . number_format($cuota, 2) . "</td>";
    echo "<td>" . htmlspecialchars($descuento_porcentaje) . "%</td>";
    echo "<td>$" . number_format($descuento_monto, 2) . "</td>";
    echo "<td>$" . number_format($seguro_medico, 2) . "</td>";
    echo "<td>$" . number_format($cuota_final, 2) . "</td>";
    echo "</tr>";
}

echo "</tbody>
</table>

</body>
</html>";

?>