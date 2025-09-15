<?php

function contar_palabras_repetidas($texto) {
    $texto = strtolower(trim($texto));
    $palabras = explode(" ", $texto);
    $contadorP = [];

    foreach ($palabras as $palabra) {
        if ($palabra !== '') {
            if (isset($contadorP[$palabra])) {
                $contadorP[$palabra]++;
            } else {
                $contadorP[$palabra] = 1;
            }
        }
    }
    
    return $contadorP;
}


function capitalizar_palabras($texto) {
    $palabras = explode(" ", $texto);
    $resultados = [];

    foreach ($palabras as $palabra) {
        if ($palabra !== '') {
            $primera_letra = strtoupper(substr($palabra, 0, 1));
            $resto_palabra = strtolower(substr($palabra, 1));
            $resultados[] = $primera_letra . $resto_palabra;
        }
    }
    return implode(" ", $resultados);
}

?>