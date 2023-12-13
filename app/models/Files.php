<?php
	class Files {
        private $db;
        private $response;

		function __construct() {
			$this->db = new Database;
            $this->response = (object) ["success" => false];
		}
		
        function saveFile($file, $url, $new_name = '') {
            $originalFileName = pathinfo($file["name"], PATHINFO_FILENAME);
		    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
			
            // Nombre del archivo
            if ($new_name == '') {
                $newFileName = $originalFileName . "." . $fileExtension;
            } else {
                $newFileName = $new_name . "." . $fileExtension;
            }

            // Directorio
            if (!file_exists($url)) {
            	mkdir($url, 0777, true);
        	}

            $targetFile = $url . $newFileName;

            // Eliminar archivo anterior
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }

            // Guardar el archivo
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
				$this->response->success = true;
                $this->response->targetFile = $targetFile;
			} else {
				$this->response["error"] = "Oops, ocurrio un error al guardar el archivo";
            }

            return $this->response;
        }
	}
?>