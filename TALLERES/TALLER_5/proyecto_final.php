<?php

class Estudiante
{
    private int $id;
    private string $nombre;
    private int $edad;
    private string $carrera;
    private array $materias = [];

    public function __construct(int $id, string $nombre, int $edad, string $carrera)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->carrera = $carrera;
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCarrera(): string { return $this->carrera; }
    public function getMaterias(): array { return $this->materias; }

    public function agregarMateria(string $materia, float $calificacion): void
    {
        $this->materias[$materia] = ['calificacion' => $calificacion, 'nombre' => $materia];
    }

    public function obtenerPromedio(): float
    {
        if (empty($this->materias)) return 0.0;

        $calificaciones = array_map(fn($m) => $m['calificacion'], $this->materias);
        $totalCalificaciones = array_reduce($calificaciones, fn($sum, $cal) => $sum + $cal, 0.0);

        return round($totalCalificaciones / count($this->materias), 2);
    }

    public function __toString(): string
    {
        $salida = "ID: {$this->id} | Nombre: {$this->nombre} | Carrera: {$this->carrera} | Promedio: {$this->obtenerPromedio()}\n";
        $salida .= "Materias: ";
        $detalles = array_map(fn($m) => "{$m['nombre']}: {$m['calificacion']}", $this->materias);
        $salida .= implode(", ", $detalles) . "\n";
        return $salida;
    }
}

class SistemaGestionEstudiantes
{
    private array $estudiantes = [];
    private array $graduados = [];

    public function agregarEstudiante(Estudiante $estudiante): void
    {
        $this->estudiantes[$estudiante->getId()] = $estudiante;
    }

    public function obtenerEstudiante(int $id): ?Estudiante
    {
        return $this->estudiantes[$id] ?? null;
    }

    public function listarEstudiantes(): array
    {
        return $this->estudiantes;
    }

    public function calcularPromedioGeneral(): float
    {
        if (empty($this->estudiantes)) return 0.0;
        
        $promedios = array_map(fn(Estudiante $e) => $e->obtenerPromedio(), $this->estudiantes);
        $sumaPromedios = array_reduce($promedios, fn($sum, $prom) => $sum + $prom, 0.0);

        return round($sumaPromedios / count($this->estudiantes), 2);
    }

    public function obtenerEstudiantesPorCarrera(string $carrera): array
    {
        $carreraLower = strtolower($carrera);
        return array_filter(
            $this->estudiantes,
            fn(Estudiante $e) => strtolower($e->getCarrera()) === $carreraLower
        );
    }

    public function buscarEstudiantes(string $query): array
    {
        $queryLower = strtolower($query);
        return array_filter(
            $this->estudiantes,
            fn(Estudiante $e) => str_contains(strtolower($e->getNombre()), $queryLower) ||
                                str_contains(strtolower($e->getCarrera()), $queryLower)
        );
    }

    public function generarRanking(): array
    {
        $ranking = $this->estudiantes;
        usort($ranking, fn(Estudiante $a, Estudiante $b) => $b->obtenerPromedio() <=> $a->obtenerPromedio());
        return $ranking;
    }

    public function graduarEstudiante(int $id): bool
    {
        if (!isset($this->estudiantes[$id])) return false;

        $this->graduados[] = $this->estudiantes[$id];
        unset($this->estudiantes[$id]);
        return true;
    }
}

function imprimirTitulo(string $texto): void
{
    echo "\n--- {$texto} ---\n";
}

$sistema = new SistemaGestionEstudiantes();

$e1 = new Estudiante(101, "Ana Garcia", 20, "Ingenieria de Software");
$e1->agregarMateria("Algoritmos", 95.5);
$e1->agregarMateria("POO PHP", 98.0);
$sistema->agregarEstudiante($e1);

$e2 = new Estudiante(102, "Luis Torres", 21, "Ingenieria de Software");
$e2->agregarMateria("Algoritmos", 85.0);
$e2->agregarMateria("Bases de Datos", 65.0);
$sistema->agregarEstudiante($e2);

$e3 = new Estudiante(201, "Maria Diaz", 19, "Marketing Digital");
$e3->agregarMateria("SEO", 99.0);
$e3->agregarMateria("Publicidad", 95.0);
$sistema->agregarEstudiante($e3);

$e4 = new Estudiante(301, "Javier Cruz", 24, "Contabilidad");
$e4->agregarMateria("Finanzas", 55.0);
$e4->agregarMateria("Auditoria", 60.0);
$sistema->agregarEstudiante($e4);

$e5 = new Estudiante(401, "Pedro Ruiz", 25, "Ingenieria Industrial");
$e5->agregarMateria("Logistica", 88.0);
$sistema->agregarEstudiante($e5);

imprimirTitulo("1. DETALLES Y PROMEDIO GENERAL");
echo "Detalles de Ana Garcia:\n";
echo $e1;
echo "\nPromedio General de todos los estudiantes: " . $sistema->calcularPromedioGeneral() . "\n";


imprimirTitulo("2. FILTRADO POR CARRERA");
$md_estudiantes = $sistema->obtenerEstudiantesPorCarrera("Marketing Digital");
foreach ($md_estudiantes as $e) {
    echo "- {$e->getNombre()} (Promedio: {$e->obtenerPromedio()})\n";
}


imprimirTitulo("3. BUSQUEDA");
$resultados = $sistema->buscarEstudiantes("ING");
foreach ($resultados as $e) {
    echo "- Encontrado: {$e->getNombre()} (Carrera: {$e->getCarrera()})\n";
}


imprimirTitulo("4. RANKING DE ESTUDIANTES");
$ranking = $sistema->generarRanking();
$pos = 1;
foreach ($ranking as $e) {
    echo "{$pos}. {$e->getNombre()} - Promedio: {$e->obtenerPromedio()}\n";
    $pos++;
}

imprimirTitulo("5. GRADUACION");
echo "Total de estudiantes activos: " . count($sistema->listarEstudiantes()) . "\n";
echo "Graduando a Pedro Ruiz. Resultado: " . ($sistema->graduarEstudiante(401) ? "EXITOSO" : "FALLIDO") . "\n";
echo "Total de estudiantes activos restantes: " . count($sistema->listarEstudiantes()) . "\n";
echo "Buscar estudiante inexistente: " . ($sistema->obtenerEstudiante(999) === null ? "ID no encontrado" : "ERROR") . "\n";

?>