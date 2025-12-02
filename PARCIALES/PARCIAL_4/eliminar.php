<?php
require_once "database.php";

$sql = "SELECT * FROM productos";
$result = mysqli_query($conn, $sql);

if($result){
    if(mysqli_num_rows($result) > 0){
        echo "<h3>Eliminacion de Productos</h3>";
        echo "<table>";
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nombre</th>";
                echo "<th>Categoria</th>";
                echo "<th>Precio</th>";
                echo "<th>Cantidad</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['categoria'] . "</td>";
                echo "<td>" . $row['precio'] . "</td>";
                echo "<td>" . $row['cantidad'] . "</td>";
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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM productos WHERE id = ?";

    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            echo "Producto creado con éxito.";
            header("Location: index.php");
        } else{
            echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
        }
    }
    
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
    <header>
        <h1>Escribe el producto que deseas eliminar</h1>
    </header>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div><label>ID Producto</label><input type="text" name="id" required></div>
    <input type="submit" value="Eliminar Producto">
</form>
