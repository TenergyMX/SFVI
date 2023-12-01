<?php
	class Prueba extends Controlador {
		// Constructor
		function __construct() {}

		function index() {
			echo 'Prueba....';
			echo 'ruta_public: '. RUTA_PUBLIC;
		}
	}
?>