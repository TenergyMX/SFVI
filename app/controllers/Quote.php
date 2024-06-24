<?php
	class Quote extends Controlador {
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'cotizador';
		}

		
		function index($id = "") {
			// isUserLoggedIn();
			if ($id != "") {
            $this->vista("admin/templates", $this->datos);
				exit;
			}


			$this->vista("admin/quote", $this->datos);
		}


	}
?>
