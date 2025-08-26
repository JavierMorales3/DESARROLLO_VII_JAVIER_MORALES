<?php

/**
 *
 * @return array Un array donde uno es un libro.
 */
function obtenerLibros() {
    return [
        [
            'titulo' => 'Cien años de soledad',
            'autor' => 'Gabriel García Márquez',
            'anio_publicacion' => 1967,
            'genero' => 'Realismo mágico',
            'descripcion' => 'La novela que narra la historia de la familia Buendía a lo largo de siete generaciones.'
        ],
        [
            'titulo' => '1984',
            'autor' => 'George Orwell',
            'anio_publicacion' => 1949,
            'genero' => 'Distopía',
            'descripcion' => 'Una sociedad totalitaria controlada por el Partido y el Gran Hermano.'
        ],
        [
            'titulo' => 'El Quijote',
            'autor' => 'Miguel de Cervantes',
            'anio_publicacion' => 1605,
            'genero' => 'Novela',
            'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],
        [
            'titulo' => 'Orgullo y prejuicio',
            'autor' => 'Jane Austen',
            'anio_publicacion' => 1813,
            'genero' => 'Romance',
            'descripcion' => 'La historia de Elizabeth Bennet y su relación con el señor Darcy.'
        ],
        [
            'titulo' => 'Harry Potter y la piedra filosofal',
            'autor' => 'J. K. Rowling',
            'anio_publicacion' => 1997,
            'genero' => 'Fantasía',
            'descripcion' => 'La introducción al mundo mágico de Harry Potter, un joven mago huérfano.'
        ]
    ];
}

/**
 * Muestra los detalles de un libro en formato HTML.
 *
 * @param array $libro Un array con los datos del libro.
 * @return string Una cadena HTML con los detalles formateados.
 */
function mostrarDetallesLibro($libro) {
    return "
    <div class='book-item'>
        <div class='book-details'>
            <h3>" . htmlspecialchars($libro['titulo']) . "</h3>
            <p><strong>Autor:</strong> " . htmlspecialchars($libro['autor']) . "</p>
            <p><strong>Año:</strong> " . htmlspecialchars($libro['anio_publicacion']) . "</p>
            <p><strong>Género:</strong> " . htmlspecialchars($libro['genero']) . "</p>
            <p>" . htmlspecialchars($libro['descripcion']) . "</p>
        </div>
    </div>";
}

/**
 * Ordena un array de libros por un campo específico.
 *
 * @param array $libros El array de libros a ordenar.
 * @param string $campo El campo por el cual ordenar (ej: 'titulo', 'autor').
 * @return array El array de libros ordenado.
 */
function ordenarLibros($libros, $campo) {
    usort($libros, function($a, $b) use ($campo) {
        return strcmp($a[$campo], $b[$campo]);
    });
    return $libros;
}

?>