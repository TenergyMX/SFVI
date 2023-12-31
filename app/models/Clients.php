<?php
	class Clients {
		function __construct() {
			$this->db = new Database;
		}
		
		function addClient($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO clients(type_of_client, name, surnames, state, municipality, email, phone, rfc) VALUES(:type_of_client, :name, :surnames, :state, :municipality, :email, :phone, :rfc)");				
				$this->db->bind(':type_of_client', $datos["type"]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':surnames', $datos["surnames"]);
				$this->db->bind(':state', $datos["state"]);
                $this->db->bind(':municipality', $datos["municipality"]);
                $this->db->bind(':email', $datos["email"]);
                $this->db->bind(':phone', $datos["phone"]);
                $this->db->bind(':rfc', $datos["rfc"]);
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

		function getClient($datos = []) {
			$this->db->query("SELECT * FROM clients s WHERE s.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getClients() {
			$this->db->query("SELECT * FROM clients u");
			return $this->db->registros();
		}
	}
?>