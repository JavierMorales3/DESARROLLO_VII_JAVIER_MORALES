<?php

function calcular_promocion($antiguedad_meses) {
    if ($antiguedad_meses < 6) {
        return 0;
    } elseif ($antiguedad_meses >= 6 && $antiguedad_meses <= 12) {
        return 5;
    } elseif ($antiguedad_meses > 12 && $antiguedad_meses <= 18) {
        return 12;
    } elseif ($antiguedad_meses > 18 && $antiguedad_meses <= 24) {
        return 15;
    } elseif ($antiguedad_meses > 24) {
        return 20;
    }
    return 0;

}

function calcular_seguro_medico($cuota_base) {
    return $cuota_base * 0.05;
}

function calcular_cuota_final($cuota_base, $descuento_porcentaje, $seguro_medico) {
    $descuento_monto = $cuota_base * ($descuento_porcentaje / 100);
    return $cuota_base - $descuento_monto + $seguro_medico;
}

?>