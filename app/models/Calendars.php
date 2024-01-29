<?php
	class Calendars {
		function __construct() {
			$this->db = new Database;
		}
		
		function addEvent($datos = []) {
			$resultado = (object) ["success" => false];
			try {				
				$this->db->query("INSERT INTO calendar(id_user, title, description, start_date, end_date, color)
					VALUES(:id_user, :title, :description, :start_date, :end_date, :color)
				");

				$this->db->bind(':id_user', $datos["id_user"]);
				$this->db->bind(':title', $datos["title"]);
				$this->db->bind(':description', $datos["description"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':end_date', $datos["end_date"]);
				$this->db->bind(':color', $datos["color"]);

				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = "Oops, ocurrio un error al actualizar el dato";
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
				//$this->datos = array_merge($eventos, (array) $this->resultado); no estoy segura 
			}
			return $resultado;
		}

		function getEvents() {
			$this->db->query("SELECT * FROM calendar");
			return $this->db->registros();
		}
	}
	
?>