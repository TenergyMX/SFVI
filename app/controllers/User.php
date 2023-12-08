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
			$this->datos['user']['str_role'] = 'Administrador';
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
					$_SESSION['user']['str_rol'] = $response->data->str_rol;
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

		function resetPassword($id = 0) {
			# vista 1: enviar correo
			# vista 2: confirmar token
			$datos = [];
			$this->vista("authentication/reset-password", $datos);
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
			if ($this->datos['user']['str_role'] == 'Administrador') {
				$this->vista("Admin/table_users", $this->datos);
			} else {
				$this->vista("authentication/not-authorized", $this->datos);
			}
		}

		function clients() {
			isUserLoggedIn();
			if ($this->datos['user']['str_role'] == 'Administrador') {
				$this->vista("Admin/table_clients", $this->datos);
			} else {
				$this->vista("authentication/not-authorized", $this->datos);
			}
		}

	}
?>