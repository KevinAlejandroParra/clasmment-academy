<?php

require "conexion.php";
   // consulta del estado de la escuela 
   $escuelas_query = "SELECT escuela_id, escuela_nombre FROM escuelas WHERE escuela_id = :estado";
    $stmt = $conn->prepare($escuelas_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();
    $escuelas_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    $conn -> beginTransaction();

    // Datos del formulario

    $escuela_id = $_POST['escuela_id'];
    $escuela_nombre = $_POST['escuela_nombre'];
    $escuela_documento = $_POST['escuela_documento'];
    $escuela_descripcion = $_POST ['escuela_descripcion'];
    $escuela_telefono = $_POST ['escuela_telefono'];
    $escuela_direccion = $_POST ['escuela_direccion'];
    $escuela_correo = $_POST ['escuela_correo'];
    $escuela_password = $_POST ['escuela_password'];
    $escuela_fecha_creacion = $_POST ['escuela_fecha_creacion']; 

    // verifico que los valores de los campoes esten presentes 

    if (empty($escuela_id) || empty($escuela_nombre)|| empty ($escuela_documento) || empty($escuela_descripcion)
        || empty($escuela_telefono) || empty($escuela_direccion) || empty($escuela_correo) || empty($escuela_password)
        || empty($escuela_fecha_creacion)) {
        throw new Exception("Todos los campos son obligatorios.");
    }

    $insert_query = "INSERT INTO `escuelas`(escuela_id, escuela_nombre, escuela_documento, escuela_descripcion, escuela_telefono, 
 VALUES (:id,:nombre,:documento,:descripcion,:telefono,:direccion,correo,:password,:escuela_fecha_creacion)";
    
    $stmt= $conn->prepare($insert_query);

    $stmt->bindvalue(':id',$escuela_id, PDO::PARAM_INT);
    $stmt->bindvalue(':nombre',$escuela_nombre, PDO::PARAM_STR);
    $stmt->bindvalue(':documento',$escuela_standard, PDO::PARAM_STR);
    $stmt->bindvalue(':descripcion',$escuela_descripcion, PDO::PARAM_STR);
    $stmt->bindvalue(':telefono',$escuela_telefono, PDO::PARAM_STR);
    $stmt->bindvalue(':direccion',$escuela_direccion, PDO::PARAM_STR);
    $stmt->bindvalue(':correo',$escuela_correo, PDO::PARAM_STR);
    $stmt->bindvalue(':password', password_hash($escuela_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->bindvalue(':escuela_fecha_creacion',$escuela_fecha_creacion, PDO::PARAM_STR);

    $stmt->execute();

    //confirmar transacción
    $conn->commit();

    //mensaje 
    $mensaje = "Escuela registrada con éxito";
?>
