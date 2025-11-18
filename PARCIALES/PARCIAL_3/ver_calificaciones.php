<?php
include 'config.php';

$json_data = file_get_contents('calificaciones.json');
$grades = json_decode($json_data, true) ?? [];


if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Calificaciones</title>
    <style> body { font-family: sans-serif;}</style>
</head>
<body>
    <h2>Bienvenido, Profesor <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Estas son tus Calificaciones</p>
    <?php if (!empty($grades)): ?>
        <ul>
            <?php foreach ($grades as $item): ?>
                <li>
                   (Materia: <?php echo htmlspecialchars($item['materia']); ?>) 
                   (Nota de Materia: <?php echo number_format($item['nota']); ?>)
                </li> 
            <?php endforeach; ?>
        </ul>        
    <?php else: ?>
        <p>No tienes calificaciones disponibles de momento.</p>
    <?php endif; ?>
    
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</body>
</html>
