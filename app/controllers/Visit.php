<?php
	class Visit extends Controlador {
		private $modeloVisit;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->datos['user'] = datos_session_usuario();
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