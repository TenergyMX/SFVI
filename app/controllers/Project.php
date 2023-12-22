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
			$id_project = $this->datos['proyecto']->id; 
			$id_client = $this->datos['proyecto']->id_client;
			$this->datos['cliente'] = $this->modeloClient->getClient(1);


			if ($this->datos['proyecto']->id_category == 1) {
				$this->datos['etapas'][0] = $this->modeloProject->getFideEtapa1($id_project);
				$this->datos['etapas'][1] = $this->modeloProject->getFideEtapa2($id_project);
				$this->datos['etapas'][2] = $this->modeloProject->getFideEtapa3($id_project);
				$this->datos['etapas'][3] = $this->modeloProject->getFideEtapa4($id_project);
				$this->datos['etapas'][4] = $this->modeloProject->getFideEtapa5($id_project);
				$this->datos['etapas'][5] = $this->modeloProject->getFideEtapa6($id_project);
				$this->datos['etapas'][6] = $this->modeloProject->getFideEtapa7($id_project);
				// $this->datos['etapas'][7] = $this->modeloProject->getFideEtapa8($id_project);
			} else {
				$this->datos['etapas'][0] = $this->modeloProject->getContadoEtapa1($id_project);
				$this->datos['etapas'][1] = $this->modeloProject->getContadoEtapa2($id_project);
				$this->datos['etapas'][2] = $this->modeloProject->getContadoEtapa3($id_project);
				$this->datos['etapas'][3] = $this->modeloProject->getContadoEtapa4($id_project);
				$this->datos['etapas'][4] = $this->modeloProject->getContadoEtapa5($id_project);
				$this->datos['etapas'][5] = $this->modeloProject->getContadoEtapa6($id_project);
			}
			// Cargar la vista
			$this->vista("Admin/project_stages", $this->datos);
		}

		function Etapas($id=0){
		    $this->datos['proyecto'] = $this->modeloProject->getStages($id);
			$this->vista("Admin/project_stages", $this->datos);
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

		

		function Documentos($id=0){
			$this->datos['documento'] = $this->modeloProject->getProject($id);
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

		function Clients($id=0){
			$this->datos['cliente'] = $this->modeloProject->getClientsProjects($id);
			$this->vista("Admin/project_stages", $this->datos);
		}  

        //  funcion para obtener el cliente de los proyectos mandados llamar en el modelo projects
		// function documentsClients($id=0){
			// $this->datos['cliente'] = $this->modeloProject->getClientsDocument($id);
			// $this->vista("Admin/individual_documents", $this->datos);
		// }

		
	}
?>