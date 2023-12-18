<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Correo {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configurarSMTP();
    }

    private function configurarSMTP() {
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host = 'tenergy.com.mx';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'hermes@tenergy.com.mx';
        $this->mail->Password = 'Energia2023!';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = 465;
    }

    public function addAddress($destinatario = '') {
        $this->mail->addAddress($destinatario);
    }

    public function enviarCorreo($destinatario, $asunto, $plantilla) {
        try {
            $this->mail->setFrom('hermes@tenergy.com.mx', 'Ulices de Tenergy');
            // $this->mail->addAddress($destinatario);
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->ContentType = 'text/html; charset=UTF-8';
            $this->mail->Subject = $asunto;
            
            // Aquí puedes ajustar el contenido del cuerpo del correo según tus necesidades
            $this->mail->Body = $plantilla;

            // Adjuntar imagen
            $imagen_path = 'img/logo.png';
            $this->mail->AddEmbeddedImage($imagen_path, 'logo', 'imagen.png', 'base64', 'image/png');

            $this->mail->send();
            echo 'Message has been sent - Enviado correctamente';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
?>
