<?php
	class Cliente extends Controlador {
		// Constructor
		function __construct() {}

		function index() {
			echo 'Cliente....';
			echo 'ruta_public: '. RUTA_PUBLIC;
		}
	}
?>