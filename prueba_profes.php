<?php   
require_once 'PHP/conexion.php';

$profesores_query = "SELECT p.*, es.escuela_nombre, c.curso_nombre FROM profesores_cursos p
INNER JOIN escuelas es ON p.escuela_id = es.escuela_id
INNER JOIN cursos c ON p.curso_id = c.curso_id";
$stmt = $conn->prepare($profesores_query);
$stmt->execute();
$profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($profesores as $profesor) {
    $profesores_array = array(
        "profesor_id" => $profesor['profesor_id'],
        "usuario_documento" => $profesor['usuario_documento'],
        "escuela_nombre" => $profesor['escuela_nombre'],
        "curso_nombre" => $profesor['curso_nombre']
    );

    echo "<pre>";
    print_r($profesores_array);
    echo "</pre>";
}

