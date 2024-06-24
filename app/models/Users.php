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
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("SELECT u.*,
					r.description AS str_role
					FROM users u
					LEFT JOIN role r 
					ON u.role = r.id
					WHERE u.email = :email
				");
				$this->db->bind(':email', $datos["email"]);
				// Ejecucion de la consulta
				$response = $this->db->registro();;
				if ($response) {
					if (password_verify($datos['password'], $response->password)) {	
						$resultado->success = TRUE;				
						$resultado->data = $response;
					} else {
						$resultado->error['message'] = 'Incorrect password';
					}
				} else {
					$resultado->error['message'] = 'Email not found!';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function addUser($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO users(id_client,  role, email, password, name, surnames) VALUES(:id_client, :role, :email, :password, :name, :surnames)");
				$this->db->bind(':id_client', $datos["id_client"]); 
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
			$resultado = (object) ["success" => false];
			try {				
				$this->db->query("UPDATE users SET id_client = :id_client, email = :email, role = :role, name = :name, surnames = :surnames WHERE id = :id");
				$this->db->bind(':id_client', $datos["id_client"]); 
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':email', $datos["email"]);
				$this->db->bind(':role', $datos["role"]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':surnames', $datos["surnames"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'No se pudo realizar las modificaciones en la tabla (visit)';
				}

				return $resultado;
			} catch (Exception $e) {
				$resultado->error = [
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				];
			}
			return $resultado;
		}

		function updateUser_profile($datos = []) {
			$resultado = (object) ["success" => false];
			try {				
				$this->db->query("UPDATE users SET email = :email, name = :name, surnames = :surnames WHERE id = :id");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':email', $datos["email"]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':surnames', $datos["surnames"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'No se pudo realizar las modificaciones en la tabla (Usuarios)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado->error = [
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				];
			}
			return $resultado;
		}

		function getUser($datos = []) {
			$this->db->query("SELECT * FROM user s WHERE s.id = :id");
			$this->db->bind(':id', $datos["id"]);
			return $this->db->registro();
		}

		function getUser_email($email = 'ejemplo@gmail.com') {
			$this->db->query("SELECT * FROM users u WHERE u.email = :email");
			$this->db->bind(':email', $email);
			return $this->db->registro();
		}

		function getUsers() {
			$this->db->query("SELECT u.*, r.description str_role FROM users u LEFT JOIN role r on u.role = r.id;");
			return $this->db->registros();
		}

		function get_customer_users() {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("SELECT u.id AS user_id,
					c.id client_id,
					u.email,
					u.name,
					u.surnames,
					c.state,
					c.municipality,
					c.phone,
					c.rfc
					FROM users u
					INNER JOIN clients c ON u.id_client = c.id;
				");
				$resultado->data = $this->db->registros();
				$resultado->success = true;
			} catch (Exception $e) {
				$resultado->error = [
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				];
			}
			return $resultado;
		}

		function deleteUser($datos = []) {}

		function updatePassword($datos = []) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("UPDATE users SET
					password = :password,
					token = NULL
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':password', $datos["password"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = "Oops, ocurrio un error al actualizar la contraseña";
				}
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function addtokenUser_email($email = 0) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("UPDATE users SET token = SHA2(CONCAT(UUID(), NOW()), 256) WHERE email = :email");
				$this->db->bind(':email', $email);
				// Ejecucion de la consulta
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = "Oops, ocurrio un error";
				}
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
	}
?>