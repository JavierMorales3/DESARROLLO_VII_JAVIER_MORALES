<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

$uploadDir = 'uploads/';
$dataFile = 'datos.json';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function calcularEdad($fechaNacimiento) {
    $fechaNac = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
    return $edad;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];
    $datos_previos = [];
    $campos_map = [
        'nombre' => 'Nombre',
        'email' => 'Email',
        'fechanacimiento' => 'Fechanacimiento',
        'sitioWeb' => 'SitioWeb',
        'genero' => 'Genero',
        'comentarios' => 'Comentarios'
    ];

    // Procesar y validar cada campo
    foreach ($campos_map as $post_key => $func_suffix) {
        if (isset($_POST[$post_key])) {
            $valor = $_POST[$post_key];
            $valorSanitizado = call_user_func("sanitizar" . $func_suffix, $valor);
            $datos_previos[$post_key] = $valor; 
            $datos[$post_key] = $valorSanitizado;

            if (!call_user_func("validar" . $func_suffix, $valorSanitizado)) {
                $errores[] = "El campo " . ucfirst(str_replace('Web', ' Web', $post_key)) . " no es válido.";
            }
        }
    }
    
    if (isset($_POST['intereses']) && is_array($_POST['intereses'])) {
        $interesesSanitizados = sanitizarIntereses($_POST['intereses']);
        $datos_previos['intereses'] = $_POST['intereses'];
        $datos['intereses'] = $interesesSanitizados;
        
        if (!validarIntereses($interesesSanitizados)) {
            $errores[] = "El campo Intereses no es válido.";
        }
    } else {
        $datos_previos['intereses'] = [];
        $datos['intereses'] = [];
    }

    if (isset($datos['fechanacimiento']) && !in_array("El campo Fechanacimiento no es válido.", $errores)) {
        $datos['edad'] = calcularEdad($datos['fechanacimiento']);
    }

    // Procesar la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        if (!validarFotoPerfil($_FILES['foto_perfil'], $uploadDir)) {
            $errores[] = "La foto de perfil no es válida o su nombre ya existe. Por favor, intente con otro nombre de archivo.";
        } else {
            $extension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
            $nombreBase = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_FILENAME);
            $nombreUnico = uniqid($nombreBase . '_', true) . '.' . $extension;
            $rutaDestino = $uploadDir . $nombreUnico;
            
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Hubo un error al subir la foto de perfil.";
            }
        }
    }

    //Mostrar resultados o errores
    if (empty($errores)) {
        $registros = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
        if (!is_array($registros)) {
            $registros = [];
        }
        $registros[] = $datos;
        file_put_contents($dataFile, json_encode($registros, JSON_PRETTY_PRINT));

        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr>";

            if ($campo === 'fechanacimiento'){
                echo "<th>" . 'Fecha de Nacimiento' . "</th>";
            } elseif ($campo === 'sitioWeb'){
                echo "<th>" . 'Sitio Web' . "</th>";
            } else {
                echo "<th>" . ucfirst($campo) . "</th>";
            }
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>" . (is_array($valor) ? implode(", ", $valor) : htmlspecialchars($valor)) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><a href='registro.php'>Ver Registro de Datos Almacenados</a> | <a href='formulario.html'>Volver al formulario</a>";

    } else {

        echo "<h2>Errores:</h2>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        include 'formulario.html';
    }

} else {
    $datos_previos = [];
    include 'formulario.html';
}
?>
