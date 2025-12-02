


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Productos</title>
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
        <h1>Gestion de Productos TechParts</h1>
    </header>
    
    <main>
            <a href="crear.php">Crear Nuevo Producto</a>
            <a href="editar.php">Editar Producto</a>
            <a href="eliminar.php">Eliminar Producto</a>
    </main>

</body>
</html>

<?php
require_once "database.php";

$sql = "SELECT * FROM productos";
$result = mysqli_query($conn, $sql);

if($result){
    if(mysqli_num_rows($result) > 0){
        echo "<h3>Listado de Productos:</h3>";
        echo "<table>";
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nombre</th>";
                echo "<th>Categoria</th>";
                echo "<th>Precio</th>";
                echo "<th>Cantidad</th>";
                echo "<th>Fecha de Registro</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['categoria'] . "</td>";
                echo "<td>" . $row['precio'] . "</td>";
                echo "<td>" . $row['cantidad'] . "</td>";
                echo "<td>" . $row['fecha_registro'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($result);
    } else{
        echo "No se encontraron registros.";
    }
} else{
    echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
}
require 'layout.php';
mysqli_close($conn);
?>




