<?php
	class Document extends Controlador {
		private $modeloVisit;
		private $modeloDocument;
		// private $modeloClient;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloDocument = $this->modelo('Documents');
			// $this->modeloClient = $this->modelo('Clients');
		}

		function index() {
			$this->datos['nombre_proyectos'] = $this->modeloDocument->getProjects(); 
			$this->datos['nombre_clientes'] = $this->modeloDocument->getClients();
			$this->vista("Admin/table_documents", $this->datos); 
		}
		
	}
?>