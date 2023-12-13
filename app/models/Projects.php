<?php
	class Projects {
		private $db;
		
		function __construct() {
			$this->db = new Database;
		}
		
		function addProyect($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO
					project(
						folio,
						id_client,
						quotations,
						quotations_num,
						street,
						colony,
						municipality,
						state,
						start_date,
						lat,
						lon
					) VALUES(
						:folio,
						:id_client,
						:quotation,
						:quotation_num,
						:street,
						:colony,
						:municipality,
						:state,
						:start_date,
						:lat,
						:lng
					)
				");
				$this->db->bind(':folio', $datos["folio"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':quotation', $datos["quotation"]);
				$this->db->bind(':quotation_num', $datos["quotation_num"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
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

		function getProject($datos = []) {
			$this->db->query("SELECT * FROM project p WHERE p.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}
		
		function getProjects() {
			$this->db->query("SELECT p.*,
				CONCAT(c.name, ' ', c.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN clients c
				ON p.id_client = c.id;
			");
			return $this->db->registros();
		}

		function updateProject($datos = []) {
			try {				
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE project SET
					folio = :folio,
					id_fide = :id_fide,
					street = :street,
					colony = :colony,
					municipality = :municipality,
					state = :state,
					start_date = :start_date,
					lat = :lat,
					lon = :lng
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':folio', $datos["folio"]);
				$this->db->bind(':id_fide', $datos["id_fide"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				if ($this->db->execute()) {
					$resultado = (object) ["success" => true];
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		# Obtener la hora sol pico de cada estado
		function getStates() {
			$this->db->query("SELECT * FROM hsp;");
			return $this->db->registros();
		}
	}
?>