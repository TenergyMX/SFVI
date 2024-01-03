<?php
	class Visit extends Controlador {
		private $modeloVisit;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->datos['user']['str_role'] = isset($_SESSION['user']['str_role']) ? $_SESSION['user']['str_role'] : 'Cliente';
			$this->datos['sidebar-item'] = 'visitas';
		}

		function index() {
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->vista("Admin/table_visits", $this->datos);  
		}

		function status($id=0){
			$this->datos['estatus'] = $this->modeloVisit->updateStatusVisit($id);
			$this->vista("Admin/table_visits", $this->datos);
		}  
		
	}
?>