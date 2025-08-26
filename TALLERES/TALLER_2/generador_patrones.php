<?php

// Triángulo de asteriscos con bucle for
echo "<h2>Triángulo de asteriscos</h2>";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "* ";
    }
    echo "<br>";
}

echo "<hr>";

// Números impares del 1 al 20 con bucle while
echo "<h2>Números impares del 1 al 20</h2>";
$num = 1;
while ($num <= 20) {
    if ($num % 2 != 0) {
        echo $num . "<br>";
    }
    $num++;
}

echo "<hr>";

// Contador regresivo con bucle do-while
echo "<h2>Contador regresivo de 10 a 1</h2>";
$contador = 10;
do {
    if ($contador != 5) {
        echo $contador . "<br>";
    }
    $contador--;
} while ($contador >= 1);

?>