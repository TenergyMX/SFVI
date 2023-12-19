<?php
	class Document extends Controlador {
		private $modeloVisit;
		private $datos = [];
		// Constructor
		function __construct() {
			session_start();
			$this->modeloVisit = $this->modelo('Visits');
		}

		function index() {
			$this->vista("Admin/table_documents", $this->datos); 
		}
		
	}
?>