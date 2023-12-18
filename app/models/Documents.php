<?php
	class Documnets {
		function __construct() {
			$this->db = new Database;
		}
		
		function addDocument($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO clients(type_of_client, name, surnames, state, municipality, email, phone, rfc) VALUES(:type_of_client, :name, :surnames, :state, :municipality, :email, :phone, :rfc)");				
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo insertar los datos en la tabla (user)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function getDocument($datos = []) {
			$this->db->query("SELECT * FROM clients s WHERE s.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getDocuments() {
			$this->db->query("SELECT * FROM project p");
			return $this->db->registros();
		}
	}
?>