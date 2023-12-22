<?php
	class Project extends Controlador {
		private $modeloProject;
		private $modeloClient;
		private $modeloVisit;
		private $datos = [];

		// Constructor
		function __construct() {
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			session_start();
		}

		function index() {
			isUserLoggedIn();
			$this->datos['nombre_clientes'] = $this->modeloClient->getClients();
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombre_proyectos'] = $this->modeloProject->getProjects();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->vista("Admin/table_projects", $this->datos);
		}

		function stages($id=0) {
			isUserLoggedIn();
			$this->datos['proyecto'] = $this->modeloProject->getProject($id);
			if ($this->datos['proyecto']->id_category == 1) {
				# code
			} elseif ($this->datos['proyecto']->id_category == 2) {
				$this->datos["stage"][] = $this->modeloProject->getContadoEtapa1($id);
				$this->datos["stage"][] = $this->modeloProject->getContadoEtapa2($id);
			}
			$this->vista("Admin/project_stages", $this->datos);
		}

		function documents($id=0) {
			isUserLoggedIn();
			$this->datos['documento'] = $this->modeloPoject->getProyect($id);
			$this->vista("Admin/individual_documents", $this->datos);
		}
		
	}
?>