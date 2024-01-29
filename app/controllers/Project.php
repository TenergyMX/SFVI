<?php
	class Project extends Controlador {
		private $modeloProject;
		private $modeloClient;
		private $modeloVisit;
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'proyectos';
		}

		function index() {
			isUserLoggedIn();
			$this->datos['nombre_clientes'] = $this->modeloClient->getClients();
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombre_proyectos'] = $this->modeloProject->getProjects();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			// Cargar la vista
			$this->vista("admin/table_projects", $this->datos);	
		}

		function stages($id = 0) {
			isUserLoggedIn();
			$this->datos['proyecto'] = $this->modeloProject->getProject($id);
			$id_project = $this->datos['proyecto']->id;
			$id_client = $this->datos['proyecto']->id_client;
			$this->datos['cliente'] = $this->modeloClient->getClient($id_client);

			if ($this->datos['proyecto']->id_category == 1) {
				$this->datos['stage'][0] = $this->modeloProject->getFideEtapa1($id_project);
				$this->datos['stage'][1] = $this->modeloProject->getFideEtapa2($id_project);
				$this->datos['stage'][2] = $this->modeloProject->getFideEtapa3($id_project);
				$this->datos['stage'][3] = $this->modeloProject->getFideEtapa4($id_project);
				$this->datos['stage'][4] = $this->modeloProject->getFideEtapa5($id_project);
				$this->datos['stage'][5] = $this->modeloProject->getFideEtapa6($id_project);
				$this->datos['stage'][6] = $this->modeloProject->getFideEtapa7($id_project);
				$this->datos['stage'][7] = $this->modeloProject->getFideEtapa8($id_project);
			} else {
				$this->datos['stage'][0] = $this->modeloProject->getContadoEtapa1($id_project);
				$this->datos['stage'][1] = $this->modeloProject->getContadoEtapa2($id_project);
				$this->datos['stage'][2] = $this->modeloProject->getContadoEtapa3($id_project);
				$this->datos['stage'][3] = $this->modeloProject->getContadoEtapa4($id_project);
				$this->datos['stage'][4] = $this->modeloProject->getContadoEtapa5($id_project);
				$this->datos['stage'][5] = $this->modeloProject->getContadoEtapa6($id_project);
			}
			generate_project_file_path( $this->datos );
			// Cargar la vista
			$this->vista("admin/project_stages", $this->datos);
		}

		function project() {
			
		}

		function anteproject() {
			isUserLoggedIn();
			$this->datos['nombre_clientes'] = $this->modeloClient->getClients();
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombre_proyectos'] = $this->modeloProject->getProjects();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			// cargar vista
			$this->vista("admin/table_ante_projects", $this->datos);
		}

		function anteStages() {
			
		}
	}
?>