<?php
	# Clase para realizar todas las peticiones
	class Request extends Controlador {
		private $datos = [];
		private $response;
		private $modeloUser,
			$modeloClient,
			$modeloVisit,
			$modeloProject,
			$modeloFile,
			$modeloCalendar;
		
		// Constructor
		function __construct() {
			session_start();
			$this->modeloUser = $this->modelo('Users');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloFile = $this->modelo('Files');
			$this->modeloCalendar = $this->modelo('Calendars');
			$this->datos['user'] = datos_session_usuario();
			$this->response = array('success' => false);
		}

		function index() {
			$this->response['error'] = 'Sin petición o ruta invalida';
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ USUARIOS ] ------------------------- 	

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
				} else {
					$this->response['error']['message'] = "Oops.. hubo un error al tratar de enviar el correo";
				}
			} catch (Exception $e) {
				$this->response['error']['message'] = $e->getMessage();
				$this->response['error']['code'] = $e->getCode();
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

			// Paso 1: ver si existe este correo
			$r = $this->modeloUser->getUser_email( $datos['email'] );

			if ($r) {
				$this->response['error']['message'] = 'Este correo existe';
				header('Content-Type: application/json');
				echo json_encode($this->response);
				exit;
			}

			// Paso 2: insertar
			$response = $this->modeloUser->addUser($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			// enviamos correo de bienvenida
			if ($this->response['success']) {
				try {
				$correo = new Correo();
				$correo->subject("Bienvenida");
				$correo->addAddress( $datos['email'] );
				$correo->html_template("welcome", $datos);
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

			}

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getUsers() {
			$this->response['data'] = $this->modeloUser->getUsers();
			foreach ($this->response['data'] as &$value) {
				$value->btn_update =  '<button class="btn btn-sfvi-1" name="update" data-option="update"><i class="fa-light fa-pen"></i></button>';
			}
			
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateUser(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['email']  = isset($_POST['email']) ? $_POST['email'] : '';
			$datos['role']  = isset($_POST['role']) ? $_POST['role'] : NULL;
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : NULL;
			$datos['surnames']  = isset($_POST['surnames']) ? $_POST['surnames'] : NULL;
			$datos['password']  = isset($_POST['password']) ? $_POST['password'] : NULL;	
			
			$r = $this->modeloUser->getUser_email( $datos['email'] );
			if ($datos['role'] == NULL || $datos['role'] == '') {
				$datos['role'] = $r->role;
			}

			if ($r && $r->id != $datos['id']) {
				$this->response['error']['message'] = 'Este correo existe';
				header('Content-Type: application/json');
				echo json_encode($this->response);
				exit;
			}

			$response = $this->modeloUser->updateUser($datos);
			$datos['password']  = isset($_POST['password']) ? $_POST['password'] : '';			
			if (isset($datos['role'])) {
				$response = $this->modeloUser->updateUser($datos);
			} else {
				$response = $this->modeloUser->updateUser_profile($datos);
			}
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ CLIENTES ] ------------------------- 	

		function addClient() {
			$datos['type'] = isset($_POST['type']) ? $_POST['type'] : 1;
			$datos['name'] = isset($_POST['name']) ? $_POST['name'] : NULL;
			$datos['surnames'] = isset($_POST['surnames']) ? $_POST['surnames'] : NULL;
			$datos['state'] = isset($_POST['state']) ? $_POST['state'] : NULL;
			$datos['municipality'] = isset($_POST['municipality']) ? $_POST['municipality'] : NULL;
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : NULL;
			$datos['phone'] = isset($_POST['phone']) ? $_POST['phone'] : NULL;
			$datos['rfc'] = isset($_POST['rfc']) ? $_POST['rfc'] : NULL;

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
				$btn= '<button class="btn btn-sfvi-1" name="update" data-option="update"><i class="fa-light fa-pen"></i></button>';
				$value->btn_update = $btn;
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateClient(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['type_of_client']  = isset($_POST['type']) ? $_POST['type'] : NULL;  
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : NULL;
			$datos['surnames']  = isset($_POST['surnames']) ? $_POST['surnames'] : NULL;
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : NULL;
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : NULL;
			$datos['email']  = isset($_POST['email']) ? $_POST['email'] : NULL;
			$datos['phone']  = isset($_POST['phone']) ? $_POST['phone'] : NULL;
			$datos['rfc']  = isset($_POST['rfc']) ? $_POST['rfc'] : NULL;
			

			$response = $this->modeloClient->updateClient($datos);
			$this->response['success'] = $response->success;
			if ($response->error) {$this->response['error'] = $response->error; }

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		
		// TODO ------------------------- [ VISITAS ] ------------------------- 

		function addVisit(){
			$datos['id_project']  = isset($_POST['project']) ? $_POST['project'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : NULL;
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : NULL;
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : NULL;
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : NULL;
			$datos['note']  = isset($_POST['note']) ? $_POST['note'] : NULL;

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
				$value->btn_pdf = '';
				$value->btn_info = "<button class=\"btn btn-sfvi-1 me-1\" name=\"info\" data-option=\"show_info\"><i class=\"fa-light fa-circle-info\"></i></button>";
				$value->btn_action = "<button class=\"btn btn-sfvi-1\" name=\"update\" data-option=\"update\"><i class=\"fa-light fa-pen\"></i></button>";
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateVisit(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['id_project']  = isset($_POST['project']) ? $_POST['project'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			// $datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['id_status']  = isset($_POST['id_status']) ? $_POST['id_status'] : 1;
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : NULL;
			$datos['start_date_old']  = isset($_POST['start_date_old']) ? $_POST['start_date_old'] : NULL;
			$datos['note']  = isset($_POST['note']) ? $_POST['note'] : NULL;
			
			// le damos valos a la fecha vieja si no tiene
			if ($datos['start_date_old'] == NULL || $datos['start_date_old'] == '') {
				$datos['start_date_old'] = $datos['start_date'];
			}

			// Verificamos si la fecha fue modificada
			if ($datos['start_date'] != $datos['start_date_old']) {
				$datos['id_status'] = 5;
			}

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

		function generatePdf() {
			$this->response['data'] = $this->modeloVisit->getVisits();
			foreach ($this->response['data'] as &$value) {
				$value->btn_pdf = "<a href=\"".RUTA_URL."Visit/generatePdf/{$value->id}/\">{$value->folio}</a>" ;
			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ ANTE PROYECTOS ] -------------------------
		function getAnteProjects() {
			// Cargar los proyectos dependiendo del usuario
			if ($this->datos['user']['int_role'] <= 2 || $this->datos['user']['int_role'] == 3) {
				$this->response['data'] = $this->modeloProject->getAnteProjects();
			} else {
				$this->response['data'] = $this->modeloProject->getMyAnteProjects( $this->datos['user']['id'] );
			}

			foreach ($this->response['data'] as &$value) {
				$value->btn_project = "<a href=\"".RUTA_URL."AnteProject/stages/{$value->id}/\" class=\"btn btn-sfvi-1 text-truncate\">{$value->name}</a>";
				$value->btn_action = '<button class="btn btn-sfvi-1" name="update" data-option="update-anteproject"><i class="fa-solid fa-pen"></i></button>';
				$value->btn_action_create = "<i class=\"fa-solid fa-hourglass-start fa-spin fa-xl\" title=\"Etapas en proceso\"></i>";
				if ($value->percentage == 100 && $value->file_acep && $this->datos['user']['int_role'] <= 2) {
					$value->btn_action_create = '<button class="btn btn-sfvi-1" data-option="createProject"><i class="fa-solid fa-plus"></i></button>';
				} elseif ($value->percentage == 100 && $value->file_acep === 1) {
					$value->btn_action_create = "<i class=\"fa-regular fa-clock fa-fade fa-xl\" title=\"Esperando su creación\"></i>";
				} elseif ($value->percentage == 100 && $value->file_acep == 0 && $value->file_acep != null) {
					$value->btn_action_create = "<i class=\"fa-solid fa-face-pensive fa-fade fa-xl\" title=\"Cancelado\"></i>";
				}
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ PROYECTOS ] -------------------------
		// copia
		function addProject(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos["id_project"] = $datos['id'];
			$datos['tb']  = isset($_POST['tb']) ? trim($_POST['tb']) : NULL;
			$datos['name']  = isset($_POST['name']) ? trim($_POST['name']) : 'unknown';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : NULL;
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : '';
			$datos['id_subcategory']  = isset($_POST['id_subcategory']) ? $_POST['id_subcategory'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : $this->datos['user']['id'];
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
			$datos['period']  = isset($_POST['period']) ? $_POST['period'] : 30;
			$datos['panels']  = isset($_POST['panels']) ? $_POST['panels'] : NULL;
			$datos['module_capacity']  = isset($_POST['module_capacity']) ? $_POST['module_capacity'] : NULL;
			$datos['efficiency']  = isset($_POST['efficiency']) ? $_POST['efficiency'] : NULL;
			
			# Paso 1: Guardar la informacion.
			$response = $this->modeloProject->addProject($datos);
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }
			
			# Paso 2: Crear carpetas del proyecto basado en su categoria
			if ($this->response['success']) {
				$datos["id_project"] = $response->id;
				$arr_subdirectory = getProjectSubfolderNames( $datos['id_category'] );
				foreach($arr_subdirectory as $subdirectory) {
					$targetDirectory = trim($datos['name']);
					$targetDirectory = strtoupper($targetDirectory);
					$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
					$subdirectory = $targetDirectory . $subdirectory;
					$this->modeloFile->makeDirectory( $subdirectory );
				}
			}

			# Paso 3: Crear un registro vacio de las etapas del proyecto
			if ($this->response['success'] && $datos['id_category'] == 1) {
				$this->modeloProject->createStages($datos["id_project"], "FIDE");
			} elseif ($this->response['success'] && $datos['id_category'] == 2) {
				$this->modeloProject->createStages($datos["id_project"], "CONTADO");
			} else {
				$this->response['error']['message'] = "Categoria de proyecto desconocido";
			}

			# Paso 4: guardar el archivo de cotizacion en caso de existir
			if (isset($_FILES['quotation']) && $this->response['success']) {
				$info = getDatosDeGuardadoDelArchivoDeProyecto( 'cotizacion' );
				$targetDirectory = trim($datos['name']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
				$targetDirectory .= $info['dirs_to_save'][0] . '/';
				$r_file = $this->modeloFile->saveFile($_FILES["quotation"], $targetDirectory, "quotation");
				if ($r_file->success) {
					$categoria = $datos['id_category'] == 1 ? 'fide' : 'contado';
					$datos["table"] = "p_{$categoria}_stage1";
					$datos["data_key"] = "cotizacion";
					$datos["data_value"] = $r_file->data["file"]["full_path"];
					$datos["where"] = "WHERE id_project = {$datos["id_project"]}";
					// Guardamos la ruta en la tabla
					$this->modeloProject->updateDataInTable($datos);
				} else {
					$this->response['error']['message'] = "Proyecto guardado, pero hubo un error al guardar el archivo";
				}
			}

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function addAnteProject(){
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : NULL;
			$datos["id_project"] = $datos['id'];
			$datos['tb']  = isset($_POST['tb']) ? trim($_POST['tb']) : NULL;
			$datos['name']  = isset($_POST['name']) ? trim($_POST['name']) : 'unknown';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : NULL;
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : 1;
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : '';
			$datos['id_subcategory']  = isset($_POST['id_subcategory']) ? $_POST['id_subcategory'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : $this->datos['user']['id'];
			$datos['quotation_num']  = isset($_POST['quotation_num']) ? $_POST['quotation_num'] : '';
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : '';
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : NULL;
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : NULL;
			$datos['colony']  = isset($_POST['colony']) ? $_POST['colony'] : NULL;
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : NULL;
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : NULL;
			$datos['antepercentage']  = isset($_POST['antepercentage']) ? $_POST['antepercentage'] : 0;
			$datos['percentage']  = isset($_POST['percentage']) ? $_POST['percentage'] : 0;
			$datos['lat']  = isset($_POST['lat']) ? $_POST['lat'] : '';
			$datos['lng']  = isset($_POST['lng']) ? $_POST['lng'] : '';
			$datos['period']  = isset($_POST['period']) ? $_POST['period'] : 30;
			$datos['panels']  = isset($_POST['panels']) ? $_POST['panels'] : NULL;
			$datos['module_capacity']  = isset($_POST['module_capacity']) ? $_POST['module_capacity'] : NULL;
			$datos['efficiency']  = isset($_POST['efficiency']) ? $_POST['efficiency'] : NULL;
			
			# Paso 1: Guardar la informacion.
			$response = $this->modeloProject->addAnteProject($datos);
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }
			
			# Paso 2: Crear carpetas del proyecto basado en su categoria
			if ($this->response['success']) {
				$datos["id_project"] = $response->id;
			}

			# Paso 3: Crear un registro vacio de las etapas del proyecto
			$this->modeloProject->createStagesAnte($datos["id_project"]);

			# Paso 4: guardar el archivo de cotizacion en caso de existir
			if (isset($_FILES['quotation']) && $this->response['success']) {
				$info = getDatosDeGuardadoDelArchivoDeProyecto( 'cotizacion' );
				$targetDirectory = trim($datos['name']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
				$targetDirectory .= $info['dirs_to_save'][0] . '/';
				$r_file = $this->modeloFile->saveFile($_FILES["quotation"], $targetDirectory, "quotation");
				if ($r_file->success) {
					$categoria = $datos['id_category'] == 1 ? 'fide' : 'contado';
					$datos["table"] = "p_{$categoria}_stage1";
					$datos["data_key"] = "cotizacion";
					$datos["data_value"] = $r_file->data["file"]["full_path"];
					$datos["where"] = "WHERE id_project = {$datos["id_project"]}";
					// Guardamos la ruta en la tabla
					$this->modeloProject->updateDataInTable($datos);
				} else {
					$this->response['error']['message'] = "Proyecto guardado, pero hubo un error al guardar el archivo";
				}
			}

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function createProject() {
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : NULL;
			$datos["id_project"] = $datos['id'];
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : NULL;
			$datos['id_subcategory']  = isset($_POST['id_subcategory']) ? $_POST['id_subcategory'] : NULL;
			
			# Paso 1: Cambiamos de anteproyecto a proyecto (update)
			$this->modeloProject->createProject( $datos['id'] );
			$this->response['success'] = TRUE;

			# Paso x: Crear un registros vacios de las etapas del proyecto
			if ($this->response['success'] && $datos['id_category'] == 1) {
				$this->modeloProject->createStages($datos["id_project"], "FIDE");
			} elseif ($this->response['success'] && $datos['id_category'] == 2) {
				$this->modeloProject->createStages($datos["id_project"], "CONTADO");
			} else {
				$this->response['error']['message'] = "Categoria de proyecto desconocido";
			}

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getProjects() {
			// Cargar los proyectos dependiendo del usuario
			if ($this->datos['user']['int_role'] <= 2 || $this->datos['user']['int_role'] == 3) {
				$this->response['data'] = $this->modeloProject->getProjects();
			} else {
				$this->response['data'] = $this->modeloProject->getMyProjects( $this->datos['user']['id'] );
			}

			foreach ($this->response['data'] as &$value) {
				$value->btn_project = "<a href=\"".RUTA_URL."Project/stages/{$value->id}/\" class=\"btn btn-sfvi-1 text-truncate\">{$value->name}</a>";
				$value->btn_action_docs = "<a href=\"".RUTA_URL."Document/{$value->id}\" class=\"btn btn-sfvi-1\"><i class=\"fa-regular fa-folder-open\"></i></a>";
				$value->btn_action_visit = '<button class="btn btn-sfvi-1" name="visit" data-option="add_visit"><i class="fa-solid fa-user-plus"></i></button>';
				$value->btn_action = '<button class="btn btn-sfvi-1" name="update" data-option="update"><i class="fa-solid fa-pen"></i></button>';
			}
			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateAnteProject() {
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : 'unknown';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : NULL;
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : NULL;
			$datos['id_subcategory']  = isset($_POST['id_subcategory']) ? $_POST['id_subcategory'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : NULL;
			$datos['id_fide']  = isset($_POST['id_fide']) ? $_POST['id_fide'] : NULL;
			$datos['charge']  = isset($_POST['charge']) ? $_POST['charge'] : '';
			$datos['street']  = isset($_POST['street']) ? $_POST['street'] : '';
			$datos['colony']  = isset($_POST['colony']) ? $_POST['colony'] : '';
			$datos['municipality']  = isset($_POST['municipality']) ? $_POST['municipality'] : '';
			$datos['state']  = isset($_POST['state']) ? $_POST['state'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : '';
			$datos['lat']  = isset($_POST['lat']) ? $_POST['lat'] : '';
			$datos['lng']  = isset($_POST['lng']) ? $_POST['lng'] : '';
			$datos['panels']  = isset($_POST['panels']) ? $_POST['panels'] : '';
			$datos['module_capacity']  = isset($_POST['module_capacity']) ? $_POST['module_capacity'] : '';
			$datos['efficiency']  = isset($_POST['efficiency']) ? $_POST['efficiency'] : '';
			
			# Ejecutar
			$response = $this->modeloProject->updateAnteProject($datos);
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }
			
			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;	
		}

		function updateProject() {
			$datos['id']  = isset($_POST['id']) ? $_POST['id'] : 0;
			$datos['tb']  = isset($_POST['tb']) ? $_POST['tb'] : NULL;
			$datos['name']  = isset($_POST['name']) ? $_POST['name'] : 'unknown';
			$datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : NULL;
			$datos['id_category']  = isset($_POST['id_category']) ? $_POST['id_category'] : NULL;
			$datos['id_subcategory']  = isset($_POST['id_subcategory']) ? $_POST['id_subcategory'] : NULL;
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
			$datos['panels']  = isset($_POST['panels']) ? $_POST['panels'] : '';
			$datos['module_capacity']  = isset($_POST['module_capacity']) ? $_POST['module_capacity'] : '';
			$datos['efficiency']  = isset($_POST['efficiency']) ? $_POST['efficiency'] : '';

			# Ejecutar
			$response = $this->modeloProject->updateProject($datos);
			$this->response['success'] = $response->success;
			if (isset($response->error)) {$this->response['error'] = $response->error; }

			# Paso 2: Si se recibe un archivo guardarlo en el servidor.
			if (isset($_FILES['quotation']) && $this->response['success']) {
				$info = getDatosDeGuardadoDelArchivoDeProyecto( 'cotizacion' );
				$targetDirectory = trim($datos['name']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';
				$targetDirectory .= $info['dirs_to_save'][0] . '/';
				$r_file = $this->modeloFile->saveFile($_FILES["quotation"], $targetDirectory, "quotation");
				if ($r_file->success) {
					$categoria = $datos['id_category'] == 1 ? 'fide' : 'contado';
					$datos["table"] = "p_{$categoria}_stage1";
					$datos["data_key"] = "cotizacion";
					$datos["data_value"] = $r_file->data["file"]["full_path"];
					$datos["where"] = "WHERE id_project = {$datos["id_project"]}";
					// Guardamos la ruta en la tabla
					$this->modeloProject->updateDataInTable($datos);
				} else {
					$this->response['error']['message'] = "Proyecto guardado, pero hubo un error al guardar el archivo";
				}
			}

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;			
		}

		function updateStageData() {
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : NULL;
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : NULL;
			$datos['project_name']  = isset($_POST['project_name']) ? $_POST['project_name'] : 'unknown';
			$datos['category']  = isset($_POST['category']) ? $_POST['category'] : 'contado';
			$datos['stage']  = isset($_POST['stage']) ? $_POST['stage'] : '1';
			$datos['table']  = "p_{$datos['category']}_stage{$datos['stage']}";
			$datos['table'] = strtolower($datos['table']);
			$datos['data_key'] = isset($_POST['data_key']) ? $_POST['data_key'] : NULL;
			$datos['data_value'] = NULL;
			$datos['where'] = "WHERE id_project = {$datos['id_project']}";
			$saved = false;	// Indicamos si hara un update en la tabla

			if (!empty($_FILES)) {
				// Se guarda un archivo de una etapa
				foreach (array_keys($_FILES) as $nombreCampo) { $datos['data_key'] = $nombreCampo; }

				if ($datos['category'] == "ante") {
					$info["dirs_to_save"] = ["ANTEPROJET"];
					$info["new_name"] = NULL;
				} else {
					$info = getDatosDeGuardadoDelArchivoDeProyecto( $datos['data_key'] );
				}

				$targetDirectory = trim($datos['project_name']);
				$targetDirectory = strtoupper($targetDirectory);
				$targetDirectory = RUTA_DOCS . $targetDirectory . '/';

				$key =  $datos['data_key'];
				$new_name = $info['new_name'];
				$directory_1 = $targetDirectory . $info['dirs_to_save'][0] . '/'; 
				if ($new_name) {
					$r_file = $this->modeloFile->saveFile($_FILES[$datos['data_key']], $directory_1, $new_name );
				} else {
					$r_file = $this->modeloFile->saveFile($_FILES[$datos['data_key']], $directory_1 );
				}

				//  Este archivo se debe de guardar en mas de una carpeta
				if ( $r_file->success and count( $info['dirs_to_save'] ) > 1 ) {
					$i = 0;
					foreach ($info['dirs_to_save'] as $data) {
						if ($i !== 0) {
							$directory_x = $targetDirectory . $info['dirs_to_save'][$i] . '/';
							$this->modeloFile->makeDirectory($directory_x);
							copy($r_file->data["file"]['full_path'], $directory_x . $r_file->data["file"]['name']);
						}
						$i += 1;
					}
				}

				// Estructuramos la respuesta
				if ($r_file->success) {
					$datos['data_value'] = $r_file->data["file"]["full_path"];
					$this->response['data']['project']['id'] = $datos['id_project'];
					$this->response['data']['project']['id_type'] = $datos['id_type'];
					$this->response['data']['file']['name'] = $r_file->data["file"]['name'];
					$this->response['data']['file']['path'] = $r_file->data["file"]['path'];
					$saved = true;
				}
			}

			// Guardar datos en la tabla seleccionada
			if ($saved) {
				$r_db = $this->modeloProject->updateDataInTable($datos);
				$this->response['success'] = $r_db->success;
				if (isset($r_db->error)) {$this->response['error'] = $r_db->error; }
			}
			
			// Responder
			$this->response['table'] = $datos['table'];
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function updateStageData_check() {
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : NULL;
			$datos['stage']  = isset($_POST['stage']) ? $_POST['stage'] : NULL;
			$datos['table']  = "p_ante_stage{$datos['stage']}";
			$datos['data_key'] =  NULL;
			$datos['data_value'] =  NULL;
			$datos['where'] = "WHERE id_project = {$datos['id_project']}";
			$r = ['success' => false];

			// Buscamos la clave
			if (array_key_exists("visto_dg", $_POST)) {
				$datos['data_key'] = "visto_dg";
			} elseif (array_key_exists("file_segui", $_POST)) {
				$datos['data_key'] = "file_segui";
			} elseif (array_key_exists("file_acep", $_POST)) {
				$datos['data_key'] = "file_acep";
			}

			// Asignamos valor
			$datos['data_value'] = $datos['data_key'] != NULL ? $_POST[$datos['data_key']] :  'Naiugdfwds';

			// Ejecutamos consulta
			if ($datos['data_key'] != "file_acep") {
				$datos['data_value'] = $datos['data_value'] == 0 ? NULL : $datos['data_value'];
				$r = $this->modeloProject->updateDataInTable($datos);
			} elseif ($datos['data_key'] == "file_acep") {
				$r = $this->modeloProject->updateDataInTable($datos);
				// cambiamos el porcentaje
				$datos['table'] = 'project';
				$datos['data_key'] = 'percentage';
				$datos['data_value'] = 100;
				$datos['where'] = "WHERE id = {$datos['id_project']}";
				$r = $this->modeloProject->updateDataInTable($datos);
			}

			$this->response['success'] = $r->success;
			if (isset($r->error)) {$this->response['error'] = $r->error; }

			// Responder
			$this->response['table'] = $datos['table'];
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		
		// TODO ------------------------- [ DOCUMENTOS ] -------------------------
		function getDocs() {
			if (!isset($_GET['id_project']) || $_GET['id_project'] == NULL || $_GET['id_project'] == "") {
				header('Content-Type: application/json');
				echo json_encode($this->response);
				exit;
			}

			// Tenemos el id del proyecto
			$datos['project']['id'] = isset($_GET['id_project']) ? $_GET['id_project'] : 0;
			$project = $this->modeloProject->getProject($datos['project']['id']);
			$ruta = RUTA_DOCS . strtoupper($project->name);
			$datos['docs'] = [];
			function contenido($directorio, &$data) {
				$archivos = scandir($directorio);
				foreach ($archivos as $archivo) {
					if ($archivo != "." && $archivo != "..") {
						$rutaCompleta = $directorio . '/' . $archivo;
						if (is_dir($rutaCompleta)) {
							// Si es una carpeta, inicializa un nuevo array para esa carpeta y llama recursivamente a la función
							$data[$archivo] = [];
							contenido($rutaCompleta, $data[$archivo]);
						} else {
							// Si es un archivo, añade la ruta completa al array de la carpeta actual
							// $data[] = $rutaCompleta;
							$data[] = str_replace(RUTA_DOCS, "", $rutaCompleta);
						}
					}
				}
			}

			// Llama a la función para llenar el array
			contenido($ruta, $datos['docs']);

			# Estructuramos la respuesta
			$this->response['success'] = TRUE;
			$this->response['data']['docs'] = $datos['docs'];
			$this->response['data']['project']['id'] = $datos['project']['id'];
			
			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;	
		}

		// TODO ------------------------- [ CALENADARIO ] -------------------------

		function addEventCaledar() {
			// Recibimos datos del form mediante peticion
			$datos['id_user']  = $this->datos['user']['id'];
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : NULL;
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : NULL;
			$datos['title']  = isset($_POST['title']) ? $_POST['title'] : NULL;
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : NULL;
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : NULL;
			$datos['end_date']  = isset($_POST['end_date']) ? $_POST['end_date'] : NULL;
			$datos['color']  = isset($_POST['color']) ? $_POST['color'] : NULL;

			$r = $this->modeloCalendar->addEvent( $datos );

			$this->response['success'] = $r->success;
			if (isset($r->error)) {$this->r['error'] = $r->error; }

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getEventCaledar() {
			// Paso 1: obtener los datos del calendario
			$this->response['data'] = $this->modeloCalendar->getEvents();

			// Paso 2: carga la segunda tabla (Visitas)
			$t2 = $this->modeloVisit->getMisVisitas( $this->datos['user']['id'] );
			// $t2 = $this->modeloVisit->getMisVisitas( 1 );
			
			// Paso X: hacer suma u fusionar los datos que parezcan semejantes es decir parecer a la tabla calendar
			foreach ($t2 as $value) {
				$this->response['data'][] = [
					'title' => 'Visita',
					'description' => $value->description,
					'start_date' => $value->start_date,
					'end_date' => $value->end_date,
					'color' => '#FFD300',
				];
			}

			$this->response['success'] = true;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		// TODO ------------------------- [ CHAT ] -------------------------
		function addComments() {
			$datos['id_user']  = $this->datos['user']['id'];
			$datos['id_project']  = isset($_POST['id_project']) ? $_POST['id_project'] : NULL;
			$datos['stage']  = isset($_POST['stage']) ? $_POST['stage'] : NULL;
			$datos['message']  = isset($_POST['message']) ? $_POST['message'] : NULL;

			$r = $this->modeloProject->addComments( $datos );

			$this->response['success'] = $r->success;
			if (isset($r->error)) {$this->response['error'] = $r->error; }
			
			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}


		function getComments() {
			$my_id_user = $this->datos['user']['id'];
			$datos['id_project']  = isset($_GET['id_project']) ? $_GET['id_project'] : NULL;
			$datos['stage']  = isset($_GET['stage']) ? $_GET['stage'] : NULL;
			$r = $this->modeloProject->getComments( $datos );
			$this->response['success'] = TRUE;
			$this->response['data'] = $r->data;

			foreach ($this->response['data'] as &$value) {
				if ($value->id_user == $my_id_user) { $value->author = 'Tu'; }
			}

			# Respuesta
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

	} # fin de las vistas
?>
