<?php
	class Projects {
		private $db;
		
		function __construct() {
			$this->db = new Database;
		}
		
		function addProyect($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO project(folio, id_client, quotation, quotation_num, id_fide, tb_project, charge, address, start_date) VALUES(:folio, :id_client, :quotation, :quotation_num, :id_fide, :tb_project, :charge, :address, :start_date)");				
				$this->db->bind(':folio', $datos["folio"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':quotation', $datos["quotation"]);
				$this->db->bind(':quotation_num', $datos["quotation_num"]);
                $this->db->bind(':id_fide', $datos["id_fide"]);
                $this->db->bind(':tb_project', $datos["tb_project"]);
                $this->db->bind(':charge', $datos["charge"]);
                $this->db->bind(':address', $datos["address"]);
				$this->db->bind(':start_date', $datos["start_date"]); 
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo insertar los datos en la tabla (project)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function getProyect($datos = []) {
			$this->db->query("SELECT * FROM project p WHERE p.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}
		
		function getProjects() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		}

		function getClientes() {
			$this->db->query("SELECT * FROM clients c;");
			return $this->db->registros();
		}

		function getEstados() {
			$this->db->query("SELECT * FROM hsp;");
			return $this->db->registros();
		}

/* 		function getClients() {}

		function getClientsAll() {}

		function updateUser($datos = []) {}

		function deleteUser($datos = []) {} */
	}
?>