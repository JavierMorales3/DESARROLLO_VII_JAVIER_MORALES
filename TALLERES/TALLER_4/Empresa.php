<?php

require_once 'Evaluable.php';

class Empresa {
    private $empleados = [];

    public function agregarEmpleado(Empleado $empleado) {

        $this->empleados[] = $empleado;
        echo "Empleado " . $empleado->getNombre() . " agregado.\n";
    }

    public function listarEmpleados() {

        echo "Lista de Empleados";
        foreach ($this->empleados as $empleado) {

            echo "Nombre: " . $empleado->getNombre() . ", ID: " . $empleado->getIdEmpleado() . ", Salario: $" . $empleado->getSalarioBase() . "\n";
            if ($empleado instanceof Gerente) {
                echo "  -> Tipo: Gerente, Departamento: " . $empleado->getDepartamento() . "\n";
            } elseif ($empleado instanceof Desarrollador) {
                echo "  -> Tipo: Desarrollador, Lenguaje: " . $empleado->getLenguajeProgramacion() . ", Nivel: " . $empleado->getNivelExperiencia() . "\n";
            }
        }

    }

    public function calcularNominaTotal() {

        $nominaTotal = 0;
        foreach ($this->empleados as $empleado) {
            $nominaTotal += $empleado->getSalarioBase();
        }
        echo "Nomina total de la empresa: $$nominaTotal\n";
    }

    public function realizarEvaluaciones() {

        foreach ($this->empleados as $empleado) {
            if ($empleado instanceof Evaluable) {
                echo $empleado->evaluarDesempenio() . "\n";
            } else {
                echo "El empleado " . $empleado->getNombre() . " no es evaluable.\n";
            }
        }
    }
}