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
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'document';
		}

		function index() {
			$this->datos['nombre_proyectos'] = $this->modeloDocument->getProjects(); 
			$this->datos['nombre_clientes'] = $this->modeloDocument->getClients();
			$this->datos['sidebar-item'] = 'documentos';
			$this->vista("Admin/table_documents", $this->datos); 
		}
		
	}
?>