<?php
    // Clase controlador Principal
    // Se encarga de poder cargar los modelos y las vista
    class Controlador {
        function __construct() {}

        public function modelo($modelo) {
            require_once('../app/models/' . $modelo . '.php');
            // Instaciamos el modelo
            return new $modelo();
        }

        public function vista($vista, $datos = []) {
            // Checar si el archivo vista existe
            if (file_exists('../app/views/'. $vista . '.php')) {
                require_once('../app/views/' . $vista . '.php'); 
            } else {
                // El aarchivo de la vista no existe
                die("La vista no existe");
            }
        }
    }
?>