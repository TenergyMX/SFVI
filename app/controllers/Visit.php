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
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->vista("Admin/table_visits", $this->datos);  
		}

		function status($id=0){
			$this->datos['estatus'] = $this->modeloVisit->updateStatusVisit($id);
			$this->vista("Admin/table_visits", $this->datos);
		}  


		function statusAfter24Hours($id=0){
			$this->datos['estatusAfter'] = $this->modeloVisit->updateVisitsAfter24Hours($id);
			$this->vista("Admin/table_visits", $this->datos);
		}  

		function generatePdf($id=0){
			$this->datos['info'] = $this->modeloVisit->getVisit(['id' => $id]);
			$this->vista("Admin/view_pdf", $this->datos);
		}  
		
	}
?>