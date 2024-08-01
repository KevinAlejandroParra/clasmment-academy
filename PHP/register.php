<?php

require "conexion.php";

// Obtener roles
$roles_query = "SELECT rol_id, rol_nombre FROM roles";
$roles_result = $conn->query($roles_query);
$roles = [];

if ($roles_result->num_rows > 0) {
    while ($row = $roles_result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Obtener escuelas
$escuelas_query = "SELECT escuela_id, escuela_nombre FROM escuelas WHERE escuela_estado = 'activo'";
$escuelas_result = $conn->query($escuelas_query);
$escuelas = [];

if ($escuelas_result->num_rows > 0) {
    while ($row = $escuelas_result->fetch_assoc()) {
        $escuelas[] = $row;
    }
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_documento = $_POST['usuario_documento'];
    $usuario_tipo_documento = $_POST['usuario_tipo_documento'];
    $usuario_nombre = $_POST['usuario_nombre'];
    $usuario_apellido = $_POST['usuario_apellido'];
    $usuario_correo = $_POST['usuario_correo'];
    $usuario_password=$_POST["usuario_password"];
    $usuario_telefono = $_POST['usuario_telefono'];
    $usuario_direccion = $_POST['usuario_direccion'];
    $usuario_nacimiento = $_POST['usuario_nacimiento'];
    $rol_id = $_POST['rol_id'];
    $escuela_id = isset($_POST['escuela_id']) ? $_POST['escuela_id'] : null;

    // Insertar el nuevo usuario en la base de datos
    $insert_query = "INSERT INTO usuarios (usuario_documento, usuario_tipo_documento, usuario_nombre, usuario_apellido, usuario_correo, usuario_password, usuario_telefono, usuario_direccion, usuario_nacimiento, rol_id, escuela_id) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssssssii", $usuario_documento, $usuario_tipo_documento, $usuario_nombre, $usuario_apellido, $usuario_correo, $usuario_password, $usuario_telefono, $usuario_direccion, $usuario_nacimiento, $rol_id, $escuela_id);

    if ($stmt->execute()) {
        echo "<script>alert('el ingreso se realizo correctamente')</script>";
        header("Location: ../login.php");
    } else {
        echo "Error: " . $stmt->error;
    }
$conn->close();
}
   
?>
