<?php

require "../../PUBLIC/config/conexion.php";

try {
    // Obtener roles
    $roles_query = "SELECT rol_id, rol_nombre FROM roles";
    $stmt = $conn->prepare($roles_query);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener escuelas
    $escuelas_query = "SELECT escuela_id, escuela_nombre FROM escuelas WHERE escuela_estado = :estado";
    $stmt = $conn->prepare($escuelas_query);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();
    $escuelas_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar el formulario de registro
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Iniciar transacción
        $conn->beginTransaction();

        // Obtener datos del formulario
        $usuario_documento = $_POST['usuario_documento'];
        $usuario_tipo_documento = $_POST['usuario_tipo_documento'];
        $usuario_nombre = $_POST['usuario_nombre'];
        $usuario_apellido = $_POST['usuario_apellido'];
        $usuario_correo = $_POST['usuario_correo'];
        $usuario_password = $_POST["usuario_password"];
        $usuario_telefono = $_POST['usuario_telefono'];
        $usuario_direccion = $_POST['usuario_direccion'];
        $usuario_nacimiento = $_POST['usuario_nacimiento'];
        $rol_id = $_POST['rol_id'];
        $escuela_id = isset($_POST['escuela_id']) ? $_POST['escuela_id'] : null;

        // Verificar si todos los campos requeridos están presentes
        if (empty($usuario_documento) || empty($usuario_tipo_documento) || empty($usuario_nombre) || 
            empty($usuario_apellido) || empty($usuario_correo) || empty($usuario_password) || 
            empty($usuario_telefono) || empty($usuario_direccion) || empty($usuario_nacimiento) || 
            empty($rol_id)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

            // validar numero de telefono y documento
            if (filter_var($usuario_documento, FILTER_VALIDATE_INT)) {
            } else {
                throw new Exception("El documento debe ser un valor numerico.");
            }
            if (filter_var($usuario_telefono, FILTER_VALIDATE_INT)) {
            } else {
                throw new Exception("El telefono debe ser un valor numerico.");
            }

            // Sanitizar nombres y apellidos
            $usuario_apellido = filter_var($usuario_apellido, FILTER_SANITIZE_STRING); 
            if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $usuario_apellido)) {
            } else {
                throw new Exception("El apellido debe ser un valor de tipo texto sin caracteres especiales.");
            }
            
            $usuario_nombre = filter_var($usuario_nombre, FILTER_SANITIZE_STRING); 
            if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $usuario_nombre)) {
            } else {
                throw new Exception("El nombre debe ser un valor de tipo texto sin caracteres especiales.");
            }
            
        // Consulta de inserción

        $insert_query = "INSERT INTO usuarios (usuario_documento, usuario_tipo_documento, usuario_nombre, 
                         usuario_apellido, usuario_correo, usuario_password, usuario_telefono, 
                         usuario_direccion, usuario_nacimiento, rol_id, escuela_id) 
                         VALUES (:documento, :tipo_documento, :nombre, :apellido, :correo, :password, 
                         :telefono, :direccion, :nacimiento, :rol_id, :escuela_id)";

        $stmt = $conn->prepare($insert_query);

        // Asignar valores a la consulta
        $stmt->bindValue(':documento', $usuario_documento, PDO::PARAM_INT);
        $stmt->bindValue(':tipo_documento', $usuario_tipo_documento, PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $usuario_nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $usuario_apellido, PDO::PARAM_STR);
        $stmt->bindValue(':correo', $usuario_correo, PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($usuario_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $usuario_telefono, PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $usuario_direccion, PDO::PARAM_STR);
        $stmt->bindValue(':nacimiento', $usuario_nacimiento, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $rol_id, PDO::PARAM_INT);
        $stmt->bindValue(':escuela_id', $escuela_id, PDO::PARAM_INT);

        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();
         
        // Mostrar el mensaje de éxito
        echo json_encode(["success" => true, "message" => "Usuario registrado con éxito"]);


    }
} catch (Exception $e) {
    // Revertir la transacción en caso de error
        $conn->rollBack();

    // Mostrar el mensaje de error
    echo json_encode(["success" => false, "message" => "Error en el registro: " . $e->getMessage()]);
    
}

?>
