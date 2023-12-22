<?php
	class Users {
		function __construct() {
			$this->db = new Database;
		}

		function generatePassword() {
			$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$password = substr(str_shuffle($caracteres), 0, 8);
			return $password;
		}

		function login($datos = []) {
			try {
				$this->db->query("SELECT * FROM users s WHERE s.email = :email");
				$this->db->bind(':email', $datos["email"]);
				// Ejecucion de la consulta
				$resultado = $this->db->registro();
				if ($resultado) {
					if (password_verify($datos['password'], $resultado->password)) {					
						$resultado = (object) ["success" => true, "data" => $resultado];
					} else {
						$resultado = (object) ["success" => false, "error" => 'Incorrect password'];
					}
				} else {
					$resultado = (object) ["success" => false, "error" => 'Email not found!'];
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}
		
		function addUser($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO users(role, email, password, name, surnames) VALUES(:role, :email, :password, :name, :surnames)");
				$this->db->bind(':role', $datos["role"]);
				$this->db->bind(':email', $datos["email"]);
				$this->db->bind(':password', $datos["password"]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':surnames', $datos["surnames"]);
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

		function updateUser($datos = []) {
			try {				
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE users SET email = :email, name = :name, surnames = :surnames, password =:password WHERE id = :id");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':email', $datos["email"]);
				// $this->db->bind(':role ', $datos["role "]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':surnames', $datos["surnames"]);
				$this->db->bind(':password', $datos["password"]);
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

		function getUser($datos = []) {
			$this->db->query("SELECT * FROM user s WHERE s.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getUsers() {
			$this->db->query("SELECT u.*, r.description str_role FROM users u LEFT JOIN role r on u.role = r.id;");
			return $this->db->registros();
		}

		function getClients() {}

		function getClientsAll() {}

		/* function updateUser($datos = []) {} */

		function deleteUser($datos = []) {}
	}
?>