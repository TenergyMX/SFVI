<?php
	class Visits {
		function __construct() {
			$this->db = new Database;
		}
		
		function addVisit($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO visit(/* id_type, */ id_user, description, start_date) VALUES(/* :id_type, */ :id_user, :description, :start_date)");				
				/* $this->db->bind(':id_type', $datos["visit"]); */
				$this->db->bind(':id_user', $datos["id_user"]);
				$this->db->bind(':description', $datos["description"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo insertar los datos en la tabla (visit)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function updateVisit($datos = []) {
			try {				
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE visit SET description = :description WHERE id = :id");
				$this->db->bind(':id', $datos["id"]);
				// $this->db->bind(':id_type', $datos["id_type"]);
				$this->db->bind(':description', $datos["description"]);
				// $this->db->bind(':id_project', $datos["id_project"]);
				// $this->db->bind(':id_user', $datos["id_user"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo realizar las modificaciones en la tabla (visit)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function getVisit($datos = []) {
			$this->db->query("SELECT * FROM visit s WHERE s.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getVisits() {
			try {
				$this->db->query("SELECT v.*,
					CONCAT(u.name, ' ', u.surnames) AS str_fullname,
					tvisit.description as str_type_of_visit,
					p.lat,
					p.lon
					FROM visit v
					LEFT JOIN project p
					ON v.id_project = p.id
					LEFT JOIN users u
					ON v.id_user = u.id
					LEFT JOIN type_of_visit tvisit
					ON v.id_type = tvisit.id
				");
				return $this->db->registros();
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}	
		}
		
		function getVisitantes() {
			$this->db->query("SELECT * FROM users u WHERE u.role = 4;");
			return $this->db->registros();
		}

		function getProyectos() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		}


	}