<?php
	class Offline extends Controlador {
		// Constructor
		function __construct() {}

		function index() {
			$this->vista("others/offline");
		}
	}
?>