<?php
// recuperar_contrasena.php

require_once 'conexion.php';
require 'vendor/autoload.php'; // corregir ruta y agregar la librería

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Verificar si el email existe en la base de datos
    $stmt = $conn->prepare("SELECT usuario_documento FROM usuarios WHERE usuario_correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $usuario_documento = $usuario['usuario_documento'];
        
        // Generar token único
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // El token expira en 1 hora
        
        // Actualizar la tabla de usuarios con el token y la fecha de expiración
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expiry = ? WHERE usuario_documento = ?");
        $stmt->bind_param("ssi", $token, $expiry, $usuario_documento);
        $stmt->execute();
        
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Cambiar esto al servidor SMTP correspondiente
            $mail->SMTPAuth   = true;
            $mail->Username   = 'correo@gmail.com'; // Cambiar esto al correo de classment-academy
            $mail->Password   = 'contrasena'; // Cambiar contrasena
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Destinatarios
            $mail->setFrom('noreply@midominio.com', 'mi Nombre');
            $mail->addAddress($email);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Para restablecer tu contraseña, haz clic en el siguiente enlace:<br><br>
                              <a href='http://tudominio.com/reset_password.php?token=$token'>Restablecer contraseña</a>";

            $mail->send();
            echo "Se ha enviado un correo electrónico con instrucciones para recuperar tu contraseña.";
        } catch (Exception $e) {
            echo "Hubo un error al enviar el correo electrónico: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró ninguna cuenta asociada a ese correo electrónico.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" required placeholder="Ingresa tu correo electrónico">
    <button type="submit">Recuperar contraseña</button>
</form>