<?php
	# Clase para realizar todas las peticiones
	class Request extends Controlador {
		private $datos = [];
		private $response;
		private $modeloProject;

		// Constructor
		function __construct() {
			session_start();
			$this->modeloUser = $this->modelo('Users');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloProject = $this->modelo('Projects');
			$this->response = array('success' => false);
		}

		function index() {
			$this->response['error'] = 'Sin petición o ruta invalida';
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function addUser() {
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['role'] = isset($_POST['role']) ? $_POST['role'] : '';
			$datos['name'] = isset($_POST['name']) ? $_POST['name'] : '';
			$datos['surnames'] = isset($_POST['surnames']) ? $_POST['surnames'] : ''; 
			$datos['password'] = isset($_POST['password']) ? $_POST['password'] : '';

			$response = $this->modeloUser->addUser($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getUsers() {
			$this->response['data'] = $this->modeloUser->getUsers();
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		/* ------------------------------------ CLIENTES ------------------------------ */
		function addClient() {
			$datos['type'] = isset($_POST['type']) ? $_POST['type'] : 1;
			$datos['name'] = isset($_POST['name']) ? $_POST['name'] : '';
			$datos['surnames'] = isset($_POST['surnames']) ? $_POST['surnames'] : '';
			$datos['state'] = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['municipality'] = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
			$datos['rfc'] = isset($_POST['rfc']) ? $_POST['rfc'] : '';

			$response = $this->modeloClient->addClient($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getClients() {
			$this->response['data'] = $this->modeloClient->getClients();
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		/* ------------------------------------VISITAS--------------------------- */
		function addVisit(){
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			$datos['star_date']  = isset($_POST['star_date']) ? $_POST['star_date'] : '';

			$response = $this->modeloVisit->addVisit($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getVisits() {
			$this->response['data'] = $this->modeloVisit->getVisits();
			foreach ($this->response['data'] as &$value) {
				$btn = '<button class="btn btn-success me-1" name="info" data-option="show_info"><i class="fa-light fa-circle-info"></i></button>';
				$btn .= '<button class="btn btn-primary" name="update" data-option="update"><i class="fa-light fa-pen"></i></button>';
				$value->btn_action = $btn;
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateVisit(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			

			$response = $this->modeloVisit->updateVisit($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		/* --------------------------------------PROYECTOS----------------------------- */

		function addProyect(){
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['quotation']  = isset($_POST['quotation']) ? $_POST['quotation'] : '';
			$datos['quotation_num']  = isset($_POST['quotation_num']) ? $_POST['quotation_num'] : '';
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : '';
			$datos['tb_project']  = isset($_POST['tb_project']) ? $_POST['tb_project'] : '';
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : '';
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : '';
			$datos['cologne']  = isset($_POST['cologne']) ? $_POST['cologne'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';

			$response = $this->modeloProyect->addProyect($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getProjects() {
			$this->response['data'] = $this->modeloProject->getProjects();
			foreach ($this->response['data'] as &$value) {
				$btn = '<button class="btn btn-success me-1" name="info" data-option="show_info"><i class="fa-light fa-circle-info"></i></button>';
				$btn .= '<button class="btn btn-primary" name="update" data-option="update_info"><i class="fa-light fa-pen"></i></button>';
				$value->btn_action = $btn;
			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}


/* 		-------------------------------------ETAPAS PROYECTOS------------------------------------- */
/* 		function addStage(){
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['quotation']  = isset($_POST['quotation']) ? $_POST['quotation'] : '';
			$datos['quotation_num']  = isset($_POST['quotation_num']) ? $_POST['quotation_num'] : '';
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : '';
			$datos['tb_project']  = isset($_POST['tb_project']) ? $_POST['tb_project'] : '';
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : '';
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : '';
			$datos['cologne']  = isset($_POST['cologne']) ? $_POST['cologne'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';

			$response = $this->modeloProyect->addProyect($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getStages() {
			$this->response['data'] = $this->modeloProyect->getProyects();
			foreach ($this->response['data'] as &$value) {
				$btn = '<button class="btn btn-success me-1" name="info" data-option="show_info"><i class="fa-light fa-circle-info"></i></button>';
				$btn .= '<button class="btn btn-primary" name="update" data-option="update_info"><i class="fa-light fa-pen"></i></button>';// el punto es para concatenar string o caracteres en php 
				$value->btn_action = $btn;
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		} */
		

	} # fin de las vistas
?>