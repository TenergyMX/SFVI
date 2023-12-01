<?php
	class Client extends Controlador {
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			$this->modeloClient = $this->modelo('Clients');
		}

		function index() {
			$this->response['error'] = 'without request';
            $this->vista("Admin/table_clients", $this->datos);
		}


		function profile($id = null) {
			echo 'Vista Perfil';
		}


        function ver_clientes() {
			$this->vista("Admin/table_clients");
		}

	}
?>