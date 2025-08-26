<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        .book-list {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .book-item {
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
            display: flex;
            gap: 20px;
        }
        .book-item:last-child {
            border-bottom: none;
        }
        .book-details {
            display: flex;
            flex-direction: column;
        }
        .book-details h3 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .book-details p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <header>
        <h1>Catálogo de Libros</h1>
    </header>
    <div class="container">