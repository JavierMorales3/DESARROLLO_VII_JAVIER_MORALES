<?php

include 'operaciones_cadenas.php';

$Oraciones = [
    "Tres por tres son nueve",
    "Ocho por ocho son sesentaicuatro",
    "Colocar la caja encima de las cajas",
    "Uno de naranja y uno de limon"
];

echo "<!DOCTYPE html>
<html>
<head>
    <title>Ejecicio de Palabras en Oracion (Parcial)</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #f8c5c5ff; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1> Los Resultados de la consulta</h1>

<table>
    <thead>
        <tr>
            <th>Oracion</th>
            <th>Palabra repetidas en oracion</th>
            <th>Palabra capitalizadas</th>
        </tr>
    </thead>
    <tbody>";

foreach ($Oraciones as $frase) {
    $conteo = contar_palabras_repetidas($frase);
    $capitalizacion = capitalizar_palabras($frase);

    echo "<tr>";
    echo "<td>" . htmlspecialchars($frase) . "</td>";
    echo "<td>" . json_encode($conteo) . "</td>";
    echo "<td>" . htmlspecialchars($capitalizacion) . "</td>";
    echo "</tr>";
}

echo "</tbody>
</table>

</body>
</html>";

?>