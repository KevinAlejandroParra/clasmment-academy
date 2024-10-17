<?php
require "../../PUBLIC/config/conexion.php";

    try {
        // Iniciar la transacción
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn->beginTransaction();

        // Datos del formulario
        $escuela_nombre = $_POST['escuela_nombre'];
        $escuela_nit = $_POST['escuela_nit'];
        $escuela_descripcion = $_POST['escuela_descripcion'];
        $escuela_telefono = $_POST['escuela_telefono'];
        $escuela_direccion = $_POST['escuela_direccion'];
        $escuela_correo = $_POST['escuela_correo'];
        $escuela_password = $_POST['escuela_password'];
        $escuela_fecha_creacion = $_POST['escuela_fecha_creacion'];

        // Verificar que todos los campos estén presentes
        if (empty($escuela_nombre) || empty($escuela_nit) || empty($escuela_descripcion) ||
            empty($escuela_telefono) || empty($escuela_direccion) || empty($escuela_correo) || 
            empty($escuela_password) || empty($escuela_fecha_creacion)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

                    // validar numero de telefono y nit
                    if (filter_var($escuela_telefono, FILTER_VALIDATE_INT)) {
                    } else {
                        throw new Exception("El telefono debe ser un valor numerico.");
                    }

                    if (filter_var($escuela_nit, FILTER_VALIDATE_INT)) {
                    } else {
                    throw new Exception("El nit debe ser un valor numerico.");
                }
        
                    // Sanitizar nombres y apellidos
                    $escuela_nombre = filter_var($escuela_nombre, FILTER_SANITIZE_STRING); 
                    if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $escuela_nombre)) {
                    } else {
                        throw new Exception("El nombre debe ser un valor de tipo texto sin caracteres especiales.");
                    }
                    
                    $escuela_descripcion = filter_var($escuela_descripcion, FILTER_SANITIZE_STRING); 
                    if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $escuela_descripcion)) {
                    } else {
                        throw new Exception("La descripción debe ser un valor de tipo texto sin caracteres especiales.");
                    }

        // Consulta de inserción
        $insert_query = "INSERT INTO `escuelas`(escuela_nombre, escuela_nit, escuela_descripcion, 
                                                escuela_telefono, escuela_direccion, escuela_correo, escuela_password, 
                                                escuela_fecha_creacion) 
                         VALUES (:nombre, :documento, :descripcion, :telefono, :direccion, :correo, :password, :fecha_creacion)";
        
        $stmt = $conn->prepare($insert_query);

        // Asignar valores a la consulta
        $stmt->bindValue(':nombre', $escuela_nombre, PDO::PARAM_STR);
        $stmt->bindValue(':documento', $escuela_nit, PDO::PARAM_STR);
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

        // Mostrar el mensaje de éxito
        echo json_encode(["success" => true, "message" => "Escuela registrada con éxito"]);

        }
    } catch (Exception $e) {
        // Si hay algún error, revertir la transacción
        $conn->rollBack();

        // Mostrar el mensaje de error
        echo json_encode(["success" => false, "message" => "Error en el registro: " . $e->getMessage()]);
    }

?>

