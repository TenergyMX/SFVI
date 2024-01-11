<?php
	class File extends Controlador {
		private $modeloProject;
		private $response;
        
		function __construct() {
			session_start();
			$this->modeloProject = $this->modelo('Projects');
			$this->response = (object) array('success' => true);
			$this->datos['user']['id'] = 1;
			$this->datos['user']['str_role'] = 'Administrador';
        }

		function index() {
            echo "Bienvenido al controlador FILE";
        }

        function get_project_file($id_project = 0) {
            $path = isset($_GET["path"]) ? $_GET["path"] : '';
            if ($id_project == 0 ||  $path == '') {
                $this->response->success = false;
                $this->response->error = "false";
                header('Content-Type: application/json');
                echo json_encode($this->response);
                exit;
            }

            $targetFile = RUTA_DOCS ."{$path}";

            if (!file_exists($targetFile)) {
                $this->response->success = false;
                $this->response->error = "El Archivo no existe en el proyecto solicitado";
            }

            if (!$this->response->success) {
                header('Content-Type: application/json');
                echo json_encode($this->response);
                exit;
            }

            // Procesar archivo
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($targetFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($targetFile));
            readfile($targetFile);
            exit;
        }
	}
?>