<?php
require 'PHP/conexion.php';

$aliados_query = "SELECT * FROM escuelas WHERE :estado = escuela_estado";
$stmt = $conn->bindvalue(':estado', 'activo', PDO::PARAM_STR);
$stmt->execute();
$aliados = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>