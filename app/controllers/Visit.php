<?php
	class Visit extends Controlador {
		private $modeloVisit;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
		}

		function index() {
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
		/* 	$this->vista("Admin/table_visits", $this->datos); */
			$this->datos['nombre_proyectos'] = $this->modeloVisit->getProyectos();
			$this->vista("Admin/table_visits", $this->datos); 
		}
		
	}
?>