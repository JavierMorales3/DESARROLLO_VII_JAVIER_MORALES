<?php
include 'config.php';

$json_data = file_get_contents('estudiantes.json');
$students = json_decode($json_data, true) ?? [];

if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dasboard de Profesores</title>
    <style> body { font-family: sans-serif;}</style>
</head>
<body>
    <h2>Bienvenido, Profesor <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Listado de Estudiantes</p>
    <?php if (!empty($students)): ?>
        <ul>
            <?php foreach ($students as $item): ?>
                <li>
                   (Nombre: <?php echo htmlspecialchars($item['nombre_estudiante']); ?>) 
                   (Materia: <?php echo htmlspecialchars($item['clase']); ?>)
                   (Nota de Materia: <?php echo number_format($item['nota']); ?>)
                </li> 
            <?php endforeach; ?>
        </ul>        
    <?php else: ?>
        <p>No tienes estudiantes matriculados disponibles de momento.</p>
    <?php endif; ?>

    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</body>
</html>
