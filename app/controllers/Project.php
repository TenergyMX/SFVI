<?php
	class Project extends Controlador {
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
		}

		function index() {
			// $this->datos['nombre_clientes'] = $this->modeloProyect->getClientes();
			// $this->datos['nombre_estado'] = $this->modeloProyect->getEstados();
			$this->vista("Admin/table_projects", $this->datos);
		}
	}
?>