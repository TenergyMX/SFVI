<?php
	class Prueba extends Controlador {
		private $response;
		// Constructor
		function __construct() {
			$this->response = [];
		}

		function index() {
			echo 'Prueba....';
			echo 'ruta_public: '. RUTA_PUBLIC;
		}


		function mail() {
			$datos['email'] = "19307018@utcgg.edu.mx";

			try {
				$correo = new Correo();
				$correo->subject("Prubas locas");
				$correo->addAddress( $datos['email'] );
				$correo->html_template("welcome", $datos);
				$r = $correo->enviar();
				// print_r($r);
				if ($r->success) {
					$this->response['success'] = true;
					$this->response['msg'] = "Correo enviado para el cambio de contraseña";
					echo "Correo enviado";
				} else {
					$this->response['error'] = "Oops.. hubo un error al tratar de enviar el correo";
					echo "Correo no Enviado</br>";
					print_r($r);
				}
			} catch (Exception $e) {
				$this->response['msg'] = "catch";
				$this->response['error'] = $e;
				$this->response['error'] = "Oops.. Error al procesar el correo";
			}
			echo "</br>Listo mi lord";
		}
	}
?>