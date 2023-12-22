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
				$this->response->success = false;
				$this->response->error = "Oops, ocurrio un error al guardar el archivo";
            }
            $this->response->data['targetFile'] = $targetFile;
            $this->response->data["file_name"] = $newFileName;
            // Enviar respuesta
            return $this->response;
        }

        function promise_saveFile($file, $url = '', $new_name = '') {
            return new SimplePromise(function ($resolve, $reject) use ($file, $url, $new_name) {
                // usleep(2000000);
                $this->saveFile($file, $url, $new_name);
                $jsonResult = json_encode(['message' => 'Operación exitosa']);
                $resolve($jsonResult);
            });
        }
	}
?>