<?php
	class Document extends Controlador {
		private $modeloVisit;
		private $modeloDocument;
		private $modeloProject;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloDocument = $this->modelo('Documents');
			$this->modeloProject = $this->modelo('Projects');
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'document';
		}

		function index($id_project = NULL) {
			isUserLoggedIn();
			if ($this->datos['user']['int_role'] <= 2) {
				$this->datos['proyectos'] = $this->modeloProject->getProjects_sf();
			} else {
				$this->datos['proyectos'] = $this->modeloProject->getMyProjects_sf( $this->datos['user']['id'] );
			}
			
			$this->datos['sidebar-item'] = 'documentos';
			$this->datos['project']['id'] = $id_project;
			$this->vista("admin/table_documents", $this->datos);
			// print_r($this->datos['proyectos']);
		}
	}
?>