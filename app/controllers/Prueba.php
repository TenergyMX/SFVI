<?php
	class Prueba extends Controlador {
		// Constructor
		function __construct() {}

		function index() {
			echo 'Prueba....';
			echo 'ruta_public: '. RUTA_PUBLIC;
		}


		function mail() {
			try {
				$correo = new Correo();
				$destinatario = 'ulices.gutierrez@tenergy.com.mx';
				$asunto = 'Este asunto es prueba';
				$plantilla = "<p>Este mensaje es de prueba</p>";
				// $plantilla = file_get_contents('template.html');
				$correo->addAddress('19307018@utcgg.edu.mx');
				$correo->addAddress('ulices.gutierrez@tenergy.com.mx');
				$correo->enviarCorreo($destinatario, $asunto, $plantilla);
			} catch (\Throwable $th) {
				echo "ERROR: ".$th;
			}
			echo "</br>Listo mi lord";
		}
	}
?>