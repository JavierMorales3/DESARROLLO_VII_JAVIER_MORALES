
<?php
// Definición de variables
$nombre = "Javier Morales";
$edad = 22;
$correo = "javier.morales3@utp.ac.pa";
$telefono = "503-4264-4729";

// Definición de constante
define("OCUPACION", "Estudiante de Desarrollo de Software");

// Creación de mensaje usando diferentes métodos de concatenación e impresión
$mensaje1 = "Hola, mi nombre es " . $nombre . " y tengo " . $edad . " años.";
$mensaje2 = "Mi correo es $correo y numero de telefono es $telefono, y soy " . OCUPACION . ".";

echo $mensaje1 . "<br>";
print($mensaje2 . "<br>");

printf("En resumen: %s, %d años, %s, %s<br>", $nombre, $edad, $correo, OCUPACION);

echo "<br>Información de debugging:<br>";
var_dump($nombre);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($correo);
echo "<br>";
var_dump($telefono);
echo "<br>";
var_dump(OCUPACION);
echo "<br>";
?>
                    
