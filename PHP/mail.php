<?php
require "conexion.php";
require_once (__DIR__ . '/PHPMailer/Exception.php');
require_once (__DIR__ . '/PHPMailer/PHPMailer.php');
require_once (__DIR__ . '/PHPMailer/SMTP.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


// Verificar que el usuario exista 
$usuario_correo = isset($_POST['email']) ? $_POST['email'] : null;

if ($usuario_correo) {
    $sql = "SELECT usuario_documento, usuario_nombre FROM usuarios WHERE usuario_correo = '$usuario_correo' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuario_documento = $row['usuario_documento'];
        $usuario_nombre = $row['usuario_nombre'];

        // Generar token único
        $token = bin2hex(random_bytes(50));
      // En recovery.php, después de generar el token
      $token = bin2hex(random_bytes(50));
      $sql_insert = "INSERT INTO recuperacion_cuentas (usuario_documento, token, email) VALUES (?, ?, ?)";
      $stmt_insert = $conn->prepare($sql_insert);
      $stmt_insert->bind_param("sss", $usuario_documento, $token, $usuario_correo);
      $stmt_insert->execute();



        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'classmentacademy@gmail.com';
            $mail->Password = 'pcfc kwyy dgxv wslx';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('classmentacademy@gmail.com', 'Classment Academy');
            $mail->addAddress($usuario_correo, $usuario_nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Hola $usuario_nombre, <br><br>Has solicitado recuperar tu contraseña. Por favor, visita el siguiente enlace para restablecer tu contraseña: <br><br><a href='reset_password.php?token=$token'>Restablecer contraseña</a><br><br>Este enlace expirará en 1 hora.<br><br>Si no has solicitado este cambio, por favor ignora este correo.";
            $mail->send();
            echo 'Se ha enviado un correo con instrucciones para restablecer tu contraseña.';
        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}";
        }
    } else {
        echo 'El usuario no existe o no se encuentra activo';
    }
} else {
    echo 'No se proporcionó un correo electrónico';
}