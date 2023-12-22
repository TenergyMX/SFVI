<?php
	# Clase para realizar todas las peticiones
	class Request extends Controlador {
		private $datos = [];
		private $response;
		private $modeloProject;
		private $modeloFile;

		// Constructor
		function __construct() {
			session_start();
			$this->modeloUser = $this->modelo('Users');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloFile = $this->modelo('Files');
			$this->response = array('success' => false);
		}

		function index() {
			$this->response['error'] = 'Sin petición o ruta invalida';
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function request_password_change() {
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : 'test@gmail.com';

			$this->modeloUser->addtokenUser_email( $datos['email'] );
			$user = $this->modeloUser->getUser_email( $datos['email'] );
			$datos['{link_to_change_password}'] = RUTA_URL."User/resetPassword/{$user->email}/{$user->token}";
			// *Creamos el correo
			try {
				$correo = new Correo();
				$correo->subject("Recuperar cuenta");
				$correo->addAddress( $datos['email'] );
				$correo->html_template("reset-password", $datos);
				$r = $correo->enviar();
				if ($r->success) {
					$this->response['success'] = true;
					$this->response['msg'] = "Correo enviado para el cambio de contraseña";
				} else {
					$this->response['error'] = "Oops.. hubo un error al tratar de enviar el correo";
				}
			} catch (Exception $e) {
				$this->response['msg'] = "catch";
				$this->response['error'] = $e;
				$this->response['error'] = "Oops.. Error al procesar el correo";
			}			

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function addUser() {
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['role'] = isset($_POST['role']) ? $_POST['role'] : '';
			$datos['name'] = isset($_POST['name']) ? $_POST['name'] : '';
			$datos['surnames'] = isset($_POST['surnames']) ? $_POST['surnames'] : ''; 
			$datos['password'] = isset($_POST['password']) ? $_POST['password'] : '1234';
			$datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);

			$response = $this->modeloUser->addUser($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getUsers() {
			$this->response['data'] = $this->modeloUser->getUsers();
			foreach ($this->response['data'] as &$value) {
				$btn= '<button class="btn btn-primary" name="update" data-option="update"><i class="fa-light fa-pen"></i></button>';
				$value->btn_update = $btn;
			}
			
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateUser(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['email']  = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['role']  = isset($_POST['role']) ? $_POST['role'] : '';
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : '';
			$datos['surnames']  = isset($_POST['surnames']) ? $_POST['surnames'] : '';
			$datos['password']  = isset($_POST['password']) ? $_POST['password'] : '';
			

			$response = $this->modeloUser->updateUser($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		// ------------------------------------ CLIENTES ------------------------------ 
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
			foreach ($this->response['data'] as &$value) {
				$btn= '<button class="btn btn-primary" name="update" data-option="update"><i class="fa-light fa-pen"></i></button>';
				$value->btn_update = $btn;
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateClient(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['type_of_client']  = isset($_POST['type_of_client']) ? $_POST['type_of_client'] : '';
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : '';
			$datos['surnames']  = isset($_POST['surnames']) ? $_POST['surnames'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['email']  = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['phone']  = isset($_POST['phone']) ? $_POST['phone'] : '';
			$datos['rfc']  = isset($_POST['rfc']) ? $_POST['rfc'] : '';
			

			$response = $this->modeloClient->updateClient($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		
		// TODO ------------------------- [ VISITAS ] ------------------------- 	
		function addVisit(){
			$datos['id_project']  = isset($_POST['project']) ? $_POST['project'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';

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
				// $btn = '<button class="btn btn-success me-1" name="add" data-option="add"><i class="fa-light fa-circle-info"></i></button>';
				$value->btn_action = $btn;
				// $value->btn_close = $btn_close;
				
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateVisit(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['id_status']  = isset($_POST['id_status']) ? $_POST['id_status'] : '';
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';
			$datos['end_date']  = isset($_POST['end_date']) ? $_POST['end_date'] : '';
			

			$response = $this->modeloVisit->updateVisit($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateStatusVisit(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['id_status']  = isset($_POST['id_status']) ? $_POST['id_status'] : '';

			$response = $this->modeloVisit->updateStatusVisit($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ PROYECTOS ] -------------------------

		function addProyect(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['folio']  = isset($_POST['folio']) ? $_POST['folio'] : 0;
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			$datos['quotation']  = '';
			$datos['quotation_num']  = isset($_POST['quotation_num']) ? $_POST['quotation_num'] : '';
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : '';
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : '';
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : '';
			$datos['colony']  = isset($_POST['colony']) ? $_POST['colony'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';
			$datos['percentage']  = isset($_POST['percentage']) ? $_POST['percentage'] : 0;
			$datos['lat']  = isset($_POST['lat']) ? $_POST['lat'] : '';
			$datos['lng']  = isset($_POST['lng']) ? $_POST['lng'] : '';
			$datos['panels']  = isset($_POST['panels']) ? $_POST['panels'] : '';
			$datos['module_capacity']  = isset($_POST['module_capacity']) ? $_POST['module_capacity'] : '';
			$datos['efficiency']  = isset($_POST['efficiency']) ? $_POST['efficiency'] : '';
			# Paso 1: Guardar la informacion.
			$response = $this->modeloProject->addProject($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			# Paso 2: Si se recibe un archivo guardarlo en el servidor.
			if (isset($_FILES['quotation']) && $this->response['success']) {
				$targetDirectory = trim($datos['folio']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
				$r_file = $this->modeloFile->saveFile($_FILES["quotation"], $targetDirectory, "quotation");
				if ($r_file->success) {
					$datos["id"] = $response->id;
					$datos["quotation"] = $r_file->targetFile;
					$this->modeloProject->addProjectquotation($datos);
				} else {
					$this->response['error'] = "Proyecto guardo, pero hubo un error al guardar el archivo";
				}
			}
			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getProjects() {
			$this->response['data'] = $this->modeloProject->getProjects();
			foreach ($this->response['data'] as &$value) {
				// Modificar datos de la db
				if (is_string($value->quotation) && $value->quotation !== '') {
					$filename = pathinfo($value->quotation, PATHINFO_BASENAME);
					$value->quotation = $filename;
				}
				// Botones de acciones
				$value->btn_folio = "<a href=\"".RUTA_URL."Project/stages/{$value->id}/\">{$value->folio}</a>";
				$value->btn_docs = "<a href=\"".RUTA_URL."Project/documents/{$value->id}/\">{$value->folio}</a>";
				$value->btn_action_docs = '<button class="btn btn-success me-1" name="docs" data-option="show_docs"><i class="fa-regular fa-folder-open"></i></button>';
				$value->btn_action_visit = '<button class="btn btn-success me-1" name="add_visit" data-option="add_visit"><i class="fa-solid fa-user-plus"></i></button>';
				$value->btn_action = '<button class="btn btn-primary" name="update" data-option="update"><i class="fa-solid fa-pen"></i></button>';
			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

			function getStages() {
			$this->response['data'] = $this->modeloProject->getStages();
			foreach ($this->response['data'] as &$value) {
				$btn_stages= '<button class="btn btn-primary" name="stages" data-option="info_stages"><i class="fa-light fa-pen"></i></button>';
				$value->btn_action_stages = $btn_stages;

			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateProject() {
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['folio']  = isset($_POST['folio']) ? $_POST['folio'] : '';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : NULL;
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : NULL;
			// $datos['quotation']  = isset($_POST['quotation']) ? $_POST['quotation'] : '';
			$datos['quotation_num']  = isset($_POST['quotation_num']) ? $_POST['quotation_num'] : 0;
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : NULL;
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : '';
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : '';
			$datos['colony']  = isset($_POST['colony']) ? $_POST['colony'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';
			$datos['lat']  = isset($_POST['lat']) ? $_POST['lat'] : '';
			$datos['lng']  = isset($_POST['lng']) ? $_POST['lng'] : '';
			# Ejecutar
			$response = $this->modeloProject->updateProject($datos);
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }

			# Paso 2: Si se recibe un archivo guardarlo en el servidor.
			if (isset($_FILES['quotation']) && $this->response['success']) {
				$targetDirectory = trim($datos['folio']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
				$r_file = $this->modeloFile->saveFile($_FILES["quotation"], $targetDirectory, "quotation");
				if ($r_file->success) {
					$datos["quotation"] = $r_file->targetFile;
					$this->modeloProject->addProjectquotation($datos);
				} else {
					$this->response['error'] = "Proyecto guardo, pero hubo un error al guardar el archivo";
				}
			}
			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;			
		}

		function updateStageData() {
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['folio']  = isset($_POST['folio']) ? $_POST['folio'] : 'unknown';
			$datos['category']  = isset($_POST['category']) ? $_POST['category'] : 'contado';
			$datos['stage']  = isset($_POST['stage']) ? $_POST['stage'] : '1';
			$datos['table']  = "p_{$datos['category']}_stage{$datos['stage']}";
			$datos['data_key'] = "cotizacion";
			$datos['data_value'] = NULL;
			$datos['info'] = [
				'recibo_CFE' => [
					'new_name' => 'recibo_CFE',
					'dirs_to_save' => ['INFORMACION_CLIENTE', 'COTIZACION']
				],
				'cotizacion' => [
					'new_name' => 'cotizacion',
					'dirs_to_save' => ['COTIZACION']
				]
			];

			// Detectar si se subio un arhivo
			if (!empty($_FILES)) {
				foreach (array_keys($_FILES) as $nombreCampo) { $datos['data_key'] = $nombreCampo; }
				$targetDirectory = trim($datos['folio']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';

				$a = $datos['info'][ $datos['data_key'] ]['dirs_to_save'];
				$new_name = $datos['info'][ $datos['data_key'] ]['new_name'];
				foreach (['INFORMACION_CLIENTE', 'DOS'] as $data) {
					$dir = $targetDirectory . $data . '/';
					// $r_file = $this->modeloFile->saveFile($_FILES["recibo_CFE"], $dir, $new_name);
				}
				$file = $_FILES["recibo_CFE"];
				$file2 = array_merge([], $file);

				$dir = $targetDirectory . 'INFORMACION_CLIENTE/';
				$r_file = $this->modeloFile->saveFile($file, $dir, $new_name);
				$this->response["r_1"] = $r_file;

				$dir = $targetDirectory . 'COTIZACION/';
				$r_file = $this->modeloFile->saveFile($file2, $dir, $new_name);
				$this->response["r_2"] = $r_file;

				// $r_file = $this->modeloFile->saveFile($_FILES["{$datos['data_key']}"], $targetDirectory, $datos['data_key'] );
				// if ($r_file->success) {
				// 	$datos["data_value"] = $r_file->targetFile;
				// 	$r2 = $this->modeloProject->updateDataInTable($datos);
				// 	$this->response['data']['id'] = $datos['id'];
				// 	$this->response['data']['fileName'] = $r_file->data["file_name"];
				// 	$this->response['success'] = $r2->success;
				// } else {
				// 	$this->response['error'] = "Oops, hubo un error al guardar el archivo";
				// }
			}
			// Responder
			$this->response['table'] = $datos['table'];
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getDocuments() {
			$this->response['data'] = $this->modeloProject->getProjects();
			foreach ($this->response['data'] as &$value) {
				$btn_docs = '<button class="btn btn-success me-1" name="docs" data-option="show_dcs"><i class="fa-light fa-circle-info"></i></button>';
				$btn_visit = '<button class="btn btn-success me-1" name="visit" data-option="show_visits"><i class="fa-light fa-circle-info"></i></button>';
				$btn = '<button class="btn btn-success me-1" name="info" data-option="add"><i class="fa-light fa-circle-info"></i></button>';
				$btn_update= '<button class="btn btn-primary" name="update" data-option="update_project"><i class="fa-light fa-pen"></i></button>';
				$btn_stages= '<button class="btn btn-primary" name="stages" data-option="info_stages"><i class="fa-light fa-pen"></i></button>';
				$value->btn_action_docs = $btn_docs; 
			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		
 
		
	} # fin de las vistas
?>