<?php
    include 'config.php';
    $json_data = file_get_contents('usuarios.json');
    $users = json_decode($json_data, true) ?? [];

    foreach ($users as $user): { 

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];

            if($usuario === $user['nombre'] && $contrasena === $user['contrasena']) {

                if(isset($user['rol']) && $user['rol'] === "profesor") {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: ver_estudiantes.php");
                    exit();

                } else if(isset($user['rol']) && $user['rol'] === "estudiante") {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: ver_calificaciones.php");
                    exit();
                }
            } else {
                $error = "Usuario o contraseña incorrectos";
                break;
            }

        }
    } endforeach
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login Dashboard</h2>
    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
    <form method="post" action="">
        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" 
         required 
         minlength="3" 
         pattern="[A-Za-z0-9]{3}"><br><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required minlength="5"><br><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>
