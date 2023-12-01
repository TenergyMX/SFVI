<?php

	/*
		Mapear la url ingresada en el navegador,
		1. Controlador
		2. Metodo
		3. Parametro
		Ejemplo: /articulos/actualizar/4
	*/

	class Core {
		protected $currentControlador = "User";
		protected $currentMetodo = "index";
		protected $parametros = [];
		// public $url;

		// Constructor
		public function __construct() {
			// print_r($this->getURL());
			$url = $this->getURL();

			if (isset($url)) {
				// Buscar en controladores si el controlador existe
				if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php' )) {
					// Si existe se setea como controlador por defecto
					$this->currentControlador = ucwords($url[0]);

					unset($url[0]);
				}
			}

			// Requerir el controlador
			require_once('../app/controllers/' . $this->currentControlador . '.php');
			$this->currentControlador = new $this->currentControlador;

			// Checar la segunda parte de la url que seria el metodo
			if (isset($url[1])) {
				if (method_exists($this->currentControlador, $url[1])) {
					$this->currentMetodo = $url[1];
					unset($url[1]);
				}
			}

			// echo $this->currentMetodo;
			// Obtener Parametros
			$this->parametros = $url ? array_values($url) : [];

			// Llamar callback con parametros array
			call_user_func_array([$this->currentControlador, $this->currentMetodo], $this->parametros);
		}

		public function getURL() {
			// echo $_GET['url'];

			if (isset($_GET['url'])) {
				$url = rtrim($_GET['url'], '/');
				$url = filter_var($url, FILTER_SANITIZE_URL);
				$url = explode('/', $url);
				return $url;
			}
		}
	}
?>