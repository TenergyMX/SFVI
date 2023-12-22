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

		function index() {}

        function getProjectFile($id = 0, $fileName = 'quotation.pdf') {
            $project = $this->modeloProject->getProject($id);

            if (!$project) {
                $this->response->success = false;
                $this->response->error = "ID desconocido";
                header('Content-Type: application/json');
                echo json_encode($this->response);
                exit;
            }

            $user_access = $project->id_user;
            $this->response->project = $project->folio;
            $targetFile = RUTA_DOCS ."{$project->folio}/{$fileName}";

            if (!file_exists($targetFile)) {
                $this->response->success = false;
                $this->response->error = "El Archivo ({$fileName}) no existe en el proyecto solicitado";
                $this->response->error = $targetFile;
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