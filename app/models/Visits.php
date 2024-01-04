<?php
	class Visits {
		function __construct() {
			$this->db = new Database;
		}
		
		function addVisit($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO visit(
						id_project,
						id_user,
						id_type,
						description,
						start_date,
						id_status
					) VALUES(
						:id_project,
						:id_user,
						:id_type,
						:description,
						:start_date,
						1
					);
				");				
				$this->db->bind(':id_project', $datos["id_project"]);
				$this->db->bind(':id_user', $datos["id_user"]);
				$this->db->bind(':id_type', $datos["id_type"]);
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
				$this->db->query("UPDATE visit SET id_project = :id_project, id_user = :id_user, id_type = :id_type, id_status = :id_status, description = :description, start_date = :start_date, note = :note WHERE id = :id");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':id_project', $datos["id_project"]);
				$this->db->bind(':id_user', $datos["id_user"]);
				// $this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':id_type', $datos["id_type"]);
				$this->db->bind(':id_status', $datos["id_status"]);
				$this->db->bind(':description', $datos["description"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':note', $datos["note"]);


				// $this->db->bind(':end_date', $datos["end_date"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo realizar las modificaciones en la tabla (visit)';
				}
				// var_dump($datos);
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function getVisit($datos = []) {
			$this->db->query("SELECT v.*,
					CONCAT(u.name, ' ', u.surnames) AS str_fullname,
					tvisit.description AS str_type_of_visit,
					p.folio AS project_folio,
					svisit.description AS str_status_of_visit,
					CONCAT(p.street, ', ', p.colony, ', ', p.municipality, ', ', p.state) AS direccion,    
					p.lat,
					p.lon
					FROM visit v
					LEFT JOIN project p
					ON v.id_project = p.id
					LEFT JOIN users u
					ON v.id_user = u.id
					LEFT JOIN type_of_visit tvisit
					ON v.id_type = tvisit.id
					LEFT JOIN status_of_visit svisit
					ON v.id_status = svisit.id
					WHERE v.id = :id
				");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getVisits() {
			try {
				$this->db->query("SELECT v.*,
					CONCAT(u.name, ' ', u.surnames) AS str_fullname,
					tvisit.description AS str_type_of_visit,
					p.folio AS project_folio,
					svisit.description AS str_status_of_visit,
					p.lat,
					p.lon
					FROM visit v
					LEFT JOIN project p
					ON v.id_project = p.id
					LEFT JOIN users u
					ON v.id_user = u.id
					LEFT JOIN type_of_visit tvisit
					ON v.id_type = tvisit.id
					LEFT JOIN status_of_visit svisit
					ON v.id_status = svisit.id
				");
				return $this->db->registros();
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}	
		}
		
		function getVisitantes() {
			$this->db->query("SELECT * FROM users u WHERE u.role != 6");
			return $this->db->registros();
		}

		function getProyectos() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		}

		

		function updateStatusVisit($datos = []) {
			try {				
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE visit SET id_status = :nuevo_id_status WHERE id = :id");
				$this->db->bind(':nuevo_id_status', 3);
				$this->db->bind(':id', $datos["id"]);

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


		function getDateVisit($datos = []){
			try {
				$this->db->query("SELECT start_date FROM visit WHERE id = :id");
				$this->db->bind(':id', $id);

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

		function updateVisitsAfter24Hours($datos = []) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("UPDATE visit SET id_status = 2 WHERE TIMESTAMPDIFF(HOUR, create_at, NOW()) < 48 ");
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error["message"] = 'No se pudo realizar la consulta para obtener visitas que han pasado más de 24 horas.';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado->success = true;
				$resultado->error["message"] = $e->getMessage();
				return $resultado;
			}

		}

		



	}