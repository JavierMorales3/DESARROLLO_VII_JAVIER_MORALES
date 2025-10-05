<?php
$dataFile = 'datos.json';
$registros = [];

if (file_exists($dataFile)) {
    $contenido = file_get_contents($dataFile);
    $registros = json_decode($contenido, true);
    if (!is_array($registros)) {
        $registros = [];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Datos de Persona</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Registro de Datos Acumulados</h2>
    
    <?php if (empty($registros)): ?>
        <p>No hay registros existentes.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <?php 
                    $claves = [];
                    foreach ($registros as $registro) {
                        $claves = array_unique(array_merge($claves, array_keys($registro)));
                    }
                    foreach ($claves as $clave): 
                    ?>
                        <th><?php echo ucfirst(str_replace('_', ' ', $clave)); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <?php foreach ($claves as $clave): ?>
                            <td>
                                <?php 
                                    if (!isset($registro[$clave])) {
                                        echo 'N/A';
                                    } elseif (is_array($registro[$clave])) {
                                        echo implode(", ", $registro[$clave]);
                                    } elseif ($clave === 'foto_perfil') {
                                        echo "<img src='" . htmlspecialchars($registro[$clave]) . "' width='50' alt='Foto de Perfil'>";
                                    } else {
                                        echo htmlspecialchars($registro[$clave]);
                                    }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><br><a href="formulario.html">Volver al Formulario</a></p>
</body>
</html>