<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            text-align: center;
        }
        main {
            padding: 40px;
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 15px;
            padding: 15px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #2980b9;
        }
        footer {
            text-align: center;
            padding: 15px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Biblioteca (PDO)</h1>
    </header>

    <main>
        <h2>Menu</h2>
        <a href="libros.php">Gestionar libros</a>
        <a href="usuarios.php">Gestionar usuarios</a>
        <a href="prestamos.php">Prestamos</a>
    </main>

</body>
</html>
