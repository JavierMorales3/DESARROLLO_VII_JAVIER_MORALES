# Título del Proyecto

Sistema de Gestión de Estudiantes

## Sinopsis

Este proyecto implementa un sistema básico de gestión de información académica utilizando Programación Orientada a Objetos (POO) en PHP, enfocándose en la claridad y el uso eficiente de funciones de arreglos.


### Objetivo Y Requisitos Utilizados

El objetivo principal es administrar estudiantes, calcular promedios, generar rankings y realizar búsquedas filtradas.

Los requisitos son:

Implementar POO con las clases Estudiante y SistemaGestionEstudiantes.

Se utilizar array_map, array_reduce y array_filter para calculos y filtros complejos (promedios, busquedas).

Se utiliza type hinting en todos los métodos y propiedades para asegurar la correcta manipulación de datos.

El metodo obtenerEstudiante() retorna null si un ID no existe para el amnejo de errores.

El metodo  __toString() para la impresion de los detalles de un estudiante.

### Estructura del codigo o sistema 

1. Clase Estudiante
Gestiona los datos (id, nombre, carrera, materias) y sus calificaciones.

Método obtenerPromedio(): Calcula la media de calificaciones utilizando array_map y array_reduce.

2. Clase SistemaGestionEstudiantes
Administra la lista de estudiantes y las funcionalidades de gestion.

calcularPromedioGeneral(): Calcula el promedio global del sistema.

obtenerEstudiantesPorCarrera(): Filtra estudiantes de una carrera especifica.

buscarEstudiantes(): Permite la busqueda parcial e insensible a mayusculas o minusculas por nombre o carrera.

generarRanking(): Ordena a los estudiantes por promedio descendente utilizando usort.

graduarEstudiante(): Retira un estudiante de la lista de activos.

## Ejecutando las pruebas ⚙️

El script incluye un bloque de prueba que:

Crea y agrega 5 estudiantes de ejemplo.

Demuestra el promedio general, el filtrado por carrera y el uso de la busqueda.

Imprime el ranking de estudiantes ordenado por promedio.

Muestra la funcionalidad de graduacion y el manejo de errores.

### Creador

Javier Morales, 20-0062-007469

