<?php

require (__DIR__ . '/../services/PHPMailer/src/Exception.php');
require (__DIR__ . '/../services/PHPMailer/src/PHPMailer.php');
require (__DIR__ . '/../services/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer{

    public function send($to, $subject, $message){
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'classmentacademy@gmail.com';
        $mail->Password = 'p c f c k w y y d g x v w s l x';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('classmentacademy@gmail.com', 'Classment Academy');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';

        try {
            $mail->send();            
            return ['success' => true, 'message' => 'Correo enviado correctamente.'];
        } catch (Exception $e) {
            return ['success'=> false,'message'=> 'Error al enviar la información.'];
        }
    } 
}   