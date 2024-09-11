<?php
require "conexion.php";

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
        // redireccionar al index
        header("Location: index.php");
        exit();

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
