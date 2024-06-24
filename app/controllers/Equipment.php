<?php
	class Equipment extends Controlador {
		private $modeloVisit;
		private $modeloDocument;
		private $modeloUser;
		// private $modeloClient;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloDocument = $this->modelo('Documents');
			$this->modeloUser = $this->modelo('Users');
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'Equipment';
		}

		function index() {
			isUserLoggedIn();
			// $this->datos['proyectos'] = $this->modeloDocument->getProjects();
			// $this->datos['usuarios'] = $this->modeloUser->getUsers();
			$this->vista("admin/table_Equipment", $this->datos); 
		}
	}
?>