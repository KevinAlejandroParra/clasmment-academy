<?php
session_start();
require "PHP/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $user = $_POST['usuario_correo'];
        $pass = $_POST['usuario_password'];

        $usuarios_query = "SELECT * FROM usuarios WHERE usuario_correo = :correo AND usuario_estado = :estado";
        $stmt = $conn->prepare($usuarios_query);
        $stmt->bindValue(":correo", $user, PDO::PARAM_STR);
        $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verificar la contraseña usando password_verify()
            if (password_verify($pass, $usuario['usuario_password'])) {

                // guardar la info del usuario en la sesion
                $_SESSION['usuario_documento'] = $usuario['usuario_documento'];
                $_SESSION['usuario_nombre'] = $usuario['usuario_nombre'];
                $_SESSION['usuario_apellido'] = $usuario['usuario_apellido'];
                $_SESSION['rol_id'] = $usuario['rol_id'];
                $_SESSION['escuela_id'] = $usuario['escuela_id'];
                $_SESSION['usuario_correo'] = $usuario['usuario_correo'];
                $_SESSION['usuario_telefono'] = $usuario['usuario_telefono'];
                $_SESSION['usuario_direccion'] = $usuario['usuario_direccion'];
                $_SESSION['usuario_nacimiento'] = $usuario['usuario_nacimiento'];
                $_SESSION['usuario_imagen_url'] = $usuario['usuario_imagen_url'];


                echo "Inicio de sesión exitoso";
                // Redirigir a la página principal o dashboard
                header("Location: index.php");
                exit;
            } else {
                echo "Correo electrónico o contraseña incorrectos";
            }
        } else {
            echo "Correo electrónico o contraseña incorrectos";
        }

}
?>