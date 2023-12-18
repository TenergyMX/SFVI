<?php
	class Project extends Controlador {
		private $modeloProject;
<<<<<<< HEAD
=======
		private $modeloClient;
		private $modeloVisit;
>>>>>>> origin/project_module
		private $datos = [];

		// Constructor
		function __construct() {
			$this->modeloProject = $this->modelo('Projects');
			session_start();
<<<<<<< HEAD

		}

		function index() {
			$this->datos['nombres_visitantes'] = $this->modeloProject->getVisitantes();
			$this->datos['nombre_proyectos'] = $this->modeloProject->getProyectos();
/* 			$this->datos['fecha_inicio'] = $this->modeloProject->getFechaInicio();
 */			$this->vista("Admin/table_projects", $this->datos);
		}

		function ver_proyectos() {
			$this->vista("Admin/table_projects");
		} 


		function stages($id=0) {
			$this->datos['proyecto'] = $this->modeloProject->getProyect($id);
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
			$this->datos['documento'] = $this->modeloProject->getProyect($id);
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
=======
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
		}

		function index() {
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->datos['nombre_clientes'] = $this->modeloClient->getClients();
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->vista("Admin/table_projects", $this->datos);
>>>>>>> origin/project_module
		}
	}
?>