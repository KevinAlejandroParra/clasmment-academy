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
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'classmentacademy@gmail.com';
            $mail->Password = 'v o e y c f c y c r b m n s x y';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('classmentacademy@gmail.com', 'Classment Academy');
            $mail->addAddress($usuario_correo, $usuario_nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - Classment Academy 🔐';
            $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #E8EB5C; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
                    .header { background-color: #E8EB5C; color: white; padding: 10px; text-align: center; }
                    .content { background-color: white; padding: 20px; border-radius: 5px; }
                    .button { display: inline-block; padding: 10px 20px; background-color: #E8EB5C; color: white; text-decoration: none; border-radius: 5px; }
                    .footer { text-align: center; margin-top: 20px; font-size: 0.8em; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🦁 Classment Academy 💪</h1>
                    </div>
                    <div class='content'>
                        <h2>Hola $usuario_nombre 👋</h2>
                        <p>Has solicitado recuperar tu contraseña. No te preocupes, ¡estamos aquí para ayudarte! 😊</p>
                        <p>Por favor, haz clic en el siguiente botón para restablecer tu contraseña:</p>
                        <p style='text-align: center;'>
                            <a href='http://localhost:3000/PHP/reset_password.php?token=$token' class='button'>Restablecer contraseña 🔑</a>
                        </p>
                        <p><strong>Nota:</strong> Este enlace expirará en 1 hora por razones de seguridad. ⏳</p>
                        <p>Si no has solicitado este cambio, por favor ignora este correo o contacta con nuestro equipo de soporte.</p>
                        <p> 📞 +57 3212376552  </p>
                    </div>
                    <div class='footer'>
                        <p>© 2024 Classment Academy. Todos los derechos reservados.</p>
                        <p>Si necesitas ayuda, no dudes en contactarnos: classmentacademy@gmail.com 📩</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            $mail->AltBody = 'hola puedes revisar este enlace para restablecer tu contraseña: http://localhost:3000/PHP/reset_password.php?token=' . $token;
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