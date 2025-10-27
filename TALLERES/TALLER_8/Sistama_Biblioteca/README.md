# Sistema de Gestión de Biblioteca (Javier Morales, 20-0062-007469)
Gestion de biblioteca que contiene implementaciones de MySQLi y PDO en php.

## Instrucciones

1. Base de datos (para crearla).
CREATE DATABASE biblioteca;
USE biblioteca;

CREATE TABLE libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  autor VARCHAR(255) NOT NULL,
  isbn VARCHAR(30) NOT NULL UNIQUE,
  anio_publicacion INT,
  cantidad INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prestamos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  libro_id INT NOT NULL,
  fecha_prestamo DATE NOT NULL,
  fecha_devolucion DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE
);


2. Ajustar las credenciales (a gusto de cambairlas por otras):

$host = '127.0.0.1';
$db   = 'biblioteca';
$user = 'root';
$pass = '';

## Consideraciones
- Todas las consultas usan sentencias preparadas.
- Para operaciones que modifican varias tablas se usan transacciones.
- Una paginacion simple por parametro (10 items por pagina).

## Comparacion MySQLi y PDO 
- Ambos soportan sentencias preparadas y transacciones.
- PDO permite cambiar facilmente el motor de base de datos.
- PDO suele ofrecer una API más limpia y manejo de excepciones nativo.
- MySQLi puede ser ligeramente mas rapido en entornos MySQL nativos y permite uso procedural o orientado a objetos.

