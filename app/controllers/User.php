<?php
	class User extends Controlador {
		private $modeloUser;
		private $modeloCalc;
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			$this->modeloUser = $this->modelo('Users');
			$this->modeloCalc = $this->modelo('Calc');
			$this->datos['user']['str_role'] = isset($_SESSION['user']['str_role']) ? $_SESSION['user']['str_role'] : 'Cliente';
			$this->datos['sidebar-item'] = 'dashboard';
		}

		function index() {
			isUserLoggedIn();
			$this->datos['calculo Tenergy'] = $this->modeloCalc->calcTenergy();
			$this->vista("Admin/index", $this->datos);
		}

		function register() {
            $this->vista("authentication/register");
		}

		function login() {
			$datos = [
				"email" => '',
				"password" => '',
			];

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$datos["email"] = isset($_POST["email"]) ? trim($_POST["email"]) : '';
				$datos["password"] = isset($_POST["password"]) ? trim($_POST["password"]) : '';

				$response = $this->modeloUser->login($datos);
				if ($response->success) {
					$_SESSION['session'] = true;
					$_SESSION['user']['id'] = $response->data->id;
					$_SESSION['user']['int_rol'] = $response->data->rol;
					$_SESSION['user']['str_role'] = $response->data->str_role;
					$_SESSION['user']['name'] = $response->data->name;
					$_SESSION['user']['surnames'] = $response->data->surnames;
					$_SESSION['user']['email'] = $response->data->email;
					// $_SESSION['user']['password'] = $response->data->password;
					header("location:" . RUTA_URL. '');
					exit;
				} else {
					$datos['alert'] = $response->error;
					$datos['alert'] = 'Error en las credenciales';
				}
			}
			// ? Carga la vista
			$this->vista("authentication/login", $datos);
		}

		function resetPassword($id = '', $token = '') {
			$datos = [ "alert-script" => "", "email" => $id, "token" => $token ];

			// ? Vista para enviar token al correo del usuario
			if (empty($id)) {
				$this->vista("authentication/reset-password", $datos);
				exit;
			}

			$user = $this->modeloUser->getUser_email($datos["email"]);

			if (!$user) {
				$datos["alert-script"] = "Usuario no encontrado";
				echo $datos["alert-script"];
				exit;
			}

			$datos["id"] = $user->id;
			if (!hash_equals($user->token , $datos["token"])) {
				$datos["alert-script"] = "Error: el token no coincide";
				echo $datos["alert-script"];
				exit;
			}

			if ($_POST) {
				$datos['password'] = trim($_POST['password']);
				$datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);
				$resultado = $this->modeloUser->updatePassword($datos);
				if ($resultado) {
					header('Location: ' . RUTA_URL . 'User/login/');
					exit;
				} else {
					$datos["alert-script"] = "Ocurrió un error inesperado";
				}
			}
			// ? Vista No. 2: token valido
			$this->vista("authentication/new-password", $datos);
			exit;
		}

		function logout() {
			unset($_SESSION);
			session_destroy();
			header('location:' . RUTA_URL);
		}

		function profile($id = null) {
			echo 'Vista Perfil';
		}

		function table() {
			isUserLoggedIn();
			$this->datos['sidebar-item'] = 'usuarios';
			if ($this->datos['user']['str_role'] == 'Administrador') {
				$this->vista("Admin/table_users", $this->datos);
			} else {
				$this->vista("authentication/not-authorized", $this->datos);
			}
		}

		function clients() {
			isUserLoggedIn();
			$this->datos['sidebar-item'] = 'clientes';
			if ($this->datos['user']['str_role'] == 'Administrador') {
				$this->vista("Admin/table_clients", $this->datos);
			} else {
				$this->vista("authentication/not-authorized", $this->datos);
			}
		}

	}
?>