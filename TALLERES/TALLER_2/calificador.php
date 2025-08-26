
<?php
echo "<h2>Calificaciones de estudiante Javier Morales</h2>";

// Declaracion de variable
$calificacion = 85;

if ($calificacion >= 90) {
    echo "Tu calificación es A.<br>";
    $letra = "A";
} elseif ($calificacion >= 80) {
    echo "Tu calificación es B.<br>";
    $letra = "B";
} elseif ($calificacion >= 70) {
    echo "Tu calificación es C.<br>";
    $letra = "C";
} elseif ($calificacion >= 60) {
    echo "Tu calificación es D.<br>";
    $letra = "D";
} else {
    echo "Tu calificación es F.<br>";
    $letra = "F";
}
echo "<br>";

$resultadoTernario = ($calificacion >= 60) ? "Aprobado" : "Reprobado";
echo "Resultado (ternario): $resultadoTernario<br><br>";

switch ($letra) {
    case "A":
        echo "Excelente trabajo.<br>";
        break;
    case "B":
        echo "Buen trabajo.<br>";
        break;
    case "C":
        echo "Trabajo aceptable.<br>";
        break;
    case "D":
        echo "Necesitas mejorar.<br>";
        break;
    default:
        echo "Debes esforzarte más.<br>";
}
echo "<br>";


?>
    
