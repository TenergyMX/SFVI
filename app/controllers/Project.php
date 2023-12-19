<?php
	class Project extends Controlador {
		private $modeloProject;
		private $modeloClient;
		private $modeloVisit;
		private $datos = [];

		// Constructor
		function __construct() {
			$this->modeloProject = $this->modelo('Projects');
			session_start();

		}

		function index() {
			/* $this->datos['nombre_proyectos'] = $this->modeloVisit->getProjects(); */
			/* $this->datos['nombre_clientes'] = $this->modeloClient->getClients(); */
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			/* $this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes(); */
			$this->vista("Admin/table_projects", $this->datos);
		}

		function ver_proyectos() {
			$this->vista("Admin/table_projects");
		} 


		function stages($id=0) {
			$this->datos['proyecto'] = $this->modeloProject->getProject($id);
			$this->vista("Admin/project_stages", $this->datos);
		}

		function Etapas($id=0){
		    $this->datos['proyecto'] = $this->modeloProject->getStages($id);
			$this->vista("Admin/project_stages", $this->datos);
			if ($this->datos['proyecto']->id_category == 1) {
				echo 'Proyecto FIDE. Nombre: '.$this->datos['proyecto']->folio;
			} else {
				echo 'Proyecto Contado. Nombre: '.$this->datos['proyecto'] ->folio;
			}
		}

		function documents($id=0) {
			$this->datos['documento'] = $this->modeloPoject->getProyect($id);
			$this->vista("Admin/individual_documents", $this->datos);
		}

		function Documentos($id=0){
			$this->datos['documento'] = $this->modeloProject->getProyect($id);
			$this->vista("Admin/individual_document", $this->datos);
			if ($this->datos['documento']->id_category == 1) {
				echo 'Proyecto FIDE. Nombre: '.$this->datos['documento']->folio;
			} else {
				echo 'Proyecto no disponible';
			}
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
		}

<<<<<<< HEAD
		function index() {
			isUserLoggedIn();
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->datos['nombre_clientes'] = $this->modeloClient->getClients();
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->vista("Admin/table_projects", $this->datos);
		}
=======
		
>>>>>>> origin
	}
?>