<?php
	class Client extends Controlador { 
		private $modeloClient;
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			$this->modeloClient = $this->modelo('Clients');
			$this->datos['user'] = datos_session_usuario();		
			$this->datos['sidebar-item'] = 'clientes';
		}

		function index() {
			isUserLoggedIn();
            $this->vista("admin/table_clients", $this->datos);
		}
	}
?>