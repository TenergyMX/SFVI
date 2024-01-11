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

		function index($id_project = NULL) {
			isUserLoggedIn();
			$this->datos['proyectos'] = $this->modeloDocument->getProjects();
			$this->datos['sidebar-item'] = 'documentos';
			$this->datos['project']['id'] = $id_project;
			$this->vista("admin/table_documents", $this->datos); 
		}
	}
?>