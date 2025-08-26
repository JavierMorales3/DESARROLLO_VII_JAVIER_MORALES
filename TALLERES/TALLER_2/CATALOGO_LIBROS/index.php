<?php

// Incluimos el archivo de funciones que contiene la lógica de los datos
require_once 'includes/funciones.php';

// Obtenemos la lista de libros del array
$libros = obtenerLibros();

// (Opcional) Ordenar los libros por título
$libros = ordenarLibros($libros, 'titulo');

// Incluimos el encabezado común de la página
require_once 'includes/header.php';

?>

<div class="book-list">
    <?php
    // Iteramos sobre cada libro y mostramos sus detalles
    foreach ($libros as $libro) {
        echo mostrarDetallesLibro($libro);
    }
    ?>
</div>

<?php

// Incluimos el pie de página común
require_once 'includes/footer.php';

?>