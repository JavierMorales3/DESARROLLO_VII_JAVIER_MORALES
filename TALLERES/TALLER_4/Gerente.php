<?php

require_once 'Empleado.php';
require_once 'Evaluable.php';

class Gerente extends Empleado implements Evaluable {
    private $departamento;

    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function asignarBono($monto) {
        $this->salarioBase += $monto;
        return "Bono de $$monto para " . $this->getNombre();
    }

    public function evaluarDesempenio() {
        $bono = $this->salarioBase * 0.15;
        return "Evaluacion de desempeno para el gerente es sobresaliente. Se recomienda un bono de 15% del salario, que serian $$bono.";
    }
}