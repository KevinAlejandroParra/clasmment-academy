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




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Iniciar la transacción
        $conn->beginTransaction();

        // Datos del formulario
        $escuela_id = $_POST['escuela_id'];
        $escuela_nombre = $_POST['escuela_nombre'];
        $escuela_documento = $_POST['escuela_documento'];
        $escuela_descripcion = $_POST['escuela_descripcion'];
        $escuela_telefono = $_POST['escuela_telefono'];
        $escuela_direccion = $_POST['escuela_direccion'];
        $escuela_correo = $_POST['escuela_correo'];
        $escuela_password = $_POST['escuela_password'];
        $escuela_fecha_creacion = $_POST['escuela_fecha_creacion'];

        // Verificar que todos los campos estén presentes
        if (empty($escuela_id) || empty($escuela_nombre) || empty($escuela_documento) || empty($escuela_descripcion) ||
            empty($escuela_telefono) || empty($escuela_direccion) || empty($escuela_correo) || 
            empty($escuela_password) || empty($escuela_fecha_creacion)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        // Consulta de inserción
        $insert_query = "INSERT INTO `escuelas`(escuela_id, escuela_nombre, escuela_documento, escuela_descripcion, 
                                                escuela_telefono, escuela_direccion, escuela_correo, escuela_password, 
                                                escuela_fecha_creacion) 
                         VALUES (:id, :nombre, :documento, :descripcion, :telefono, :direccion, :correo, :password, :fecha_creacion)";
        
        $stmt = $conn->prepare($insert_query);

        // Asignar valores a la consulta
        $stmt->bindValue(':id', $escuela_id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $escuela_nombre, PDO::PARAM_STR);
        $stmt->bindValue(':documento', $escuela_documento, PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $escuela_descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $escuela_telefono, PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $escuela_direccion, PDO::PARAM_STR);
        $stmt->bindValue(':correo', $escuela_correo, PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($escuela_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':fecha_creacion', $escuela_fecha_creacion, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        // Mensaje de éxito
        $mensaje = "Escuela registrada con éxito";

    } catch (Exception $e) {
        // Si hay algún error, revertir la transacción
        $conn->rollBack();
        
        // Mostrar el mensaje de error
        $mensaje = "Error en el registro: " . $e->getMessage();
    }
}

// Mostrar el mensaje en la interfaz de usuario
echo $mensaje;

?>
