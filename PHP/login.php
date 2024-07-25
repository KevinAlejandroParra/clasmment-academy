<?php
session_start();
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = $_POST['usuario_correo'];
    $pass = $_POST['usuario_password'];

    $query = "SELECT * FROM usuarios WHERE usuario_correo = '".$user."'";
    $ejecutar = mysqli_query($conn, $query);
    $nr = mysqli_num_rows($ejecutar);

    if ($nr === 1){

        while($row = $ejecutar->fetch_array()){
            $usuario = $row['usuario_nombre'];
            
            if ($row["usuario_password"] === $pass ) {
                $_SESSION['usuario_correo'] = $usuario;
        
                header("Location:index.php");
            } else {
                echo ("<script>alert('CONTRASEÑA INCORRECTA, INTÉNTALO DE NUEVO')</script>");
            }
        }
    } else {
        echo ("<script>alert('Usuario NO encontrado, inténtalo de nuevo')</script>");
    }
}




?>
