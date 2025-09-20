<?php

require_once 'Empleado.php';
require_once 'Gerente.php';
require_once 'Desarrollador.php';
require_once 'Empresa.php';

$miEmpresa = new Empresa();

$gerente1 = new Gerente("Ana Aparicio", "G101", 60000, "Ventas");
$desarrollador1 = new Desarrollador("Luis Rodriguez", "D201", 40000, "PHP", "Senior");
$desarrollador2 = new Desarrollador("Maria Campiz", "D202", 30000, "Java", "Junior");
$empleadoRegular = new Empleado("Pedro Santos", "E301", 20000);

$miEmpresa->agregarEmpleado($gerente1);
$miEmpresa->agregarEmpleado($desarrollador1);
$miEmpresa->agregarEmpleado($desarrollador2);
$miEmpresa->agregarEmpleado($empleadoRegular);

$miEmpresa->listarEmpleados();
$miEmpresa->calcularNominaTotal();
$miEmpresa->realizarEvaluaciones();

echo $gerente1->asignarBono(5000) . "\n";
$miEmpresa->calcularNominaTotal();

?>