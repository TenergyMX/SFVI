<?php
	class Documents {
		function __construct() {
			$this->db = new Database;
		}
		
		function addDocument($datos = []) {
			try {
				
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

		function getProjects() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		}
			
		function getClients() {
			$this->db->query("SELECT * FROM clients c;");
			return $this->db->registros();
		}

	}
?>