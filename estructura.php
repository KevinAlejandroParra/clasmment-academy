<?php   
require_once 'PHP/conexion.php';

// Consulta para obtener los datos de los profesores
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

     //consulta sql
     $escuelas_query = "SELECT e.*, c.curso_nombre FROM escuelas e 
     INNER JOIN cursos c ON c.escuela_id = e.escuela_id
     WHERE escuela_estado = :estado";
     $stmt = $conn->prepare($escuelas_query);
     $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
     $stmt->execute();
     $escuelaswithcursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($escuelaswithcursos as $escuela) {
    $escuelas_array = array(
        "escuela_id" => $escuela['escuela_id'],
        "curso_nombre" => $escuela['curso_nombre']
    );
    echo "<pre>";
    print_r($escuelas_array);
    echo "</pre>";
}
}
?>
<!DOCTYPE html>
<html data-theme="black" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</head>
