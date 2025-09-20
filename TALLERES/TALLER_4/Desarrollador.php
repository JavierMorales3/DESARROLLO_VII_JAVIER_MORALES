<?php

require_once 'Empleado.php';
require_once 'Evaluable.php';

class Desarrollador extends Empleado implements Evaluable {
    private $lenguajeProgramacion;
    private $nivelExperiencia;

    public function __construct($nombre, $idEmpleado, $salarioBase, $lenguajeProgramacion, $nivelExperiencia) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->lenguajeProgramacion = $lenguajeProgramacion;
        $this->nivelExperiencia = $nivelExperiencia;
    }

    public function getLenguajeProgramacion() {
        return $this->lenguajeProgramacion;
    }

    public function setLenguajeProgramacion($lenguajeProgramacion) {
        $this->lenguajeProgramacion = $lenguajeProgramacion;
    }

    public function getNivelExperiencia() {
        return $this->nivelExperiencia;
    }

    public function setNivelExperiencia($nivelExperiencia) {
        $this->nivelExperiencia = $nivelExperiencia;
    }

    public function evaluarDesempenio() {

        switch ($this->nivelExperiencia) {
            case 'Junior':
                return "Evaluacion de desempeño para el desarrollador: cumple las expectativas, necesita una capacitacion adicional en " . $this->getLenguajeProgramacion() . ".";
            case 'Senior':
                return "Evaluacion de desempeño para el desarrollador: excede las expectativas, se recomienda un aumento en el salario.";
            default:
                return "Evaluacion de desempeño para el desarrollador: satisfactorio.";
        }
    }
}