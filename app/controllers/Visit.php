<?php
	class Visit extends Controlador {
		private $modeloVisit;
		private $modeloProject;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'visitas';
		}

		function index() {
			isUserLoggedIn();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->vista("admin/table_visits", $this->datos);  
		}
  
		function generatePdf($id=0){
			$this->datos['info'] = $this->modeloVisit->getVisit(['id' => $id]);
			$this->vista("admin/view_pdf", $this->datos);
		}
	}
?>