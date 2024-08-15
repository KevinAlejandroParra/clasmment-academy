<?php 
require "conexion.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require_once (__DIR__ . '/PHPMailer/Exception.php');
require_once (__DIR__ . '/PHPMailer/PHPMailer.php');
require_once (__DIR__ . '/PHPMailer/SMTP.php');




//verificar que el usuario exista o este activo
$usuario_correo = isset($_POST['usuario_correo']) ? $_POST['usuario_correo'] : null;



if($result->num_rows > 0){
  $mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'classmentacademy@gmail.com';
  $mail->Password = 'p c f c k w y y d g x v w s l x';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

    $mail->setFrom('CORREO_ELECTRONICO_FROM', 'NOMBRE_FORM');
    $mail->addAddress('CORREO_ELECTRONICO_PARA','NOMBRE_ELECTRONICO_PARA');
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de contraseña';
    $mail->Body    = 'Hola, este es un correo generado para solicitar tu recuperación de contraseña, por favor, visita la página de <a href="localhost/spending_tracker/change_password.php?id='.$row['id'].'">Recuperación de contraseña</a>';

    $mail->send();
    header("Location: ../index.php?message=ok");
} catch (Exception $e) {
  header("Location: ../index.php?message=error");
}

}else{
  header("Location: ../index.php?message=not_found");
}

?>