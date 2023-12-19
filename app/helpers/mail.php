<?php
    // Todo | nota: Necesario instalar "PHPMailer"
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Correo {
        private $mail;
        private $imagen_path;

        public function __construct() {
            $this->mail = new PHPMailer(true);
            $this->imagen_path = [
                'logo' => RUTA_PUBLIC.'img/avatars/avatar.jpg',
                'logo_tenergy' => RUTA_PUBLIC.'img/avatars/avatar.jpg'
            ];
            $this->configurarSMTP();
        }

        private function configurarSMTP() {
            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();
            $this->mail->Host = EMAIL_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = EMAIL_USERNAME;
            $this->mail->Password = EMAIL_PASSWORD;
            $this->mail->SMTPSecure = 'ssl';
            $this->mail->Port = 465;
        }

        public function addAddress($destinatario = '') {
            $this->mail->addAddress($destinatario);
        }

        public function subject($asunto = 'Sin asunto') {
            $this->mail->Subject = $asunto;
        }

        function html_template($template, $datos = []) {
            
            switch ($template) {
                case 'welcome':
                    $template = file_get_contents(RUTA_APP.'/views/mail/welcome.html');
                    $this->mail->Body = $template;
                    break;
                case 'reset-password':
                    $template = file_get_contents(RUTA_APP.'/views/mail/reset-password.html');
                    $template = str_replace(array_keys($datos), array_values($datos), $template);
                    $this->mail->Body = $template;
                    $this->mail->AddEmbeddedImage($this->imagen_path['logo'], 'logo', 'imagen.jpg', 'base64', 'image/jpg');
                    break;
                default:
                    $this->mail->Body = "nada";
                    break;
            }
        }

        public function enviar() {
            try {
                $this->mail->setFrom('hermes@tenergy.com.mx', 'Ulices de Tenergy');
                // $this->mail->addAddress($destinatario);
                $this->mail->isHTML(true);
                $this->mail->CharSet = 'UTF-8';
                $this->mail->ContentType = 'text/html; charset=UTF-8';
                
                $this->mail->send();
                return (object) [
                    "success" => true,
                    "msg" => "Enviado correctamente"
                ];
            } catch (Exception $e) {
                return (object) [
                    "success" => false,
                    "error" => "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}"
                ];
            }
        }
    }
?>
