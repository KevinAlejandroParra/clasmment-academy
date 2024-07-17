<?php

require "conexion.php";

$nombre=$_POST["usuario_nombre"];
$apellido=$_POST["usuario_apellido"];
$correo=$_POST["usuario_correo"];
$tipodocumento=$_POST["usuario_tipo_documento"];
$documento=$_POST["usuario_documento"];
$nacimiento=$_POST["usuario_nacimiento"];


$sql="INSERT INTO usuarios (usuario_nombre,usuario_apellido,usuario_correo,usuario_tipo_documento,usuario_documento,usuario_nacimiento) VALUES
('$nombre','$apellido','$correo','$tipodocumento',$documento,$nacimiento);  ";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('el ingreso se realizo correctamente')</script>";
    header("Location: ../login.php");
    


  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }





