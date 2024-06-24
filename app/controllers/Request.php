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
			$modeloCalendar,
			$modeloQuote;
		
		// Constructor
		function __construct() {
			session_start();
			$this->modeloUser = $this->modelo('Users');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->modeloProject = $this->modelo('Projects');
			$this->modeloFile = $this->modelo('Files');
			$this->modeloCalendar = $this->modelo('Calendars');
			$this->modeloQuote = $this->modelo('Quotes');
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

			if (!$user) {
				$this->response['warning']['message'] = "Este correo no existe";
				$this->response['error']['message'] = "Este correo no existe";
				header('Content-Type: application/json');
				echo json_encode($this->response);
				exit;
			}

			$datos['{{ link_to_change_password }}'] = RUTA_URL."User/resetPassword/{$user->email}/{$user->token}";
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
			$datos['id_client'] = isset($_POST['name-clients']) ? $_POST['name-clients'] : NULL;
			$datos['email'] = isset($_POST['email']) ? $_POST['email'] : NULL;
			$datos['role'] = isset($_POST['role']) ? $_POST['role'] : NULL;
			$datos['name'] = isset($_POST['name']) ? $_POST['name'] : NULL;
			$datos['surnames'] = isset($_POST['surnames']) ? $_POST['surnames'] : NULL; 
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
			$datos['id_client'] = isset($_POST['name-clients']) ? $_POST['name-clients'] : NULL;
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
			$datos['id_project']  = isset($_POST['select_project']) ? $_POST['select_project'] : NULL;
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
			$this->modeloVisit->update_visits_status();
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
			$datos['id_project']  = isset($_POST['select_project']) ? $_POST['select_project'] : '';
			$datos['id_user']  = isset($_POST['id_user']) ? $_POST['id_user'] : '';
			// $datos['id_client']  = isset($_POST['id_client']) ? $_POST['id_client'] : '';
			$datos['id_type']  = isset($_POST['id_type']) ? $_POST['id_type'] : '';
			$datos['id_status']  = isset($_POST['id_status']) ? $_POST['id_status'] : 1;
			$datos['description']  = isset($_POST['description']) ? $_POST['description'] : '';
			$datos['start_date']  = isset($_POST['start_date']) ? $_POST['start_date'] : NULL;
			$datos['start_date_old']  = isset($_POST['start_date_old']) ? $_POST['start_date_old'] : NULL;
			$datos['note']  = isset($_POST['note']) ? $_POST['note'] : NULL;
			$this->response["POST"] = $datos;
			
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

		function update_visits_status() {
			$this->modeloVisit->update_visits_status();

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

		function getStages_of_project() {
			$response = (object) ["success" => false];
			$datos['project_id']  = isset($_GET['project_id']) ? $_GET['project_id'] : NULL;

			if ($datos['project_id'] == NULL) {
				$response->error["message"] = "Sin id del proyecto";
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
			
			$project = $this->modeloProject->getProject( $datos['project_id']  );

			// trabajar aqui
			if ($project->id_category == 1) {
				// ! FIDE
				$response->data['stage']["1"] = $this->modeloProject->getFideEtapa1($project->id);
				$response->data['stage']["2"] = $this->modeloProject->getFideEtapa2($project->id);
				$response->data['stage']["3"] = $this->modeloProject->getFideEtapa3($project->id);
				$response->data['stage']["4"] = $this->modeloProject->getFideEtapa4($project->id);
				$response->data['stage']["5"] = $this->modeloProject->getFideEtapa5($project->id);
				$response->data['stage']["6"] = $this->modeloProject->getFideEtapa6($project->id);
				$response->data['stage']["7"] = $this->modeloProject->getFideEtapa7($project->id);
				$response->data['stage']["8"] = $this->modeloProject->getFideEtapa8($project->id);
			} else {
				// ! CONTADO
				$response->data["stage"]["1"] = $this->modeloProject->getContadoEtapa1($project->id);
				$response->data["stage"]["2"] = $this->modeloProject->getContadoEtapa2($project->id);
				$response->data["stage"]["3"] = $this->modeloProject->getContadoEtapa3($project->id);
				$response->data["stage"]["4"] = $this->modeloProject->getContadoEtapa4($project->id);
				$response->data["stage"]["5"] = $this->modeloProject->getContadoEtapa5($project->id);
				$response->data["stage"]["6"] = $this->modeloProject->getContadoEtapa6($project->id);
			}

			$response->success = true;
			header('Content-Type: application/json');
			echo json_encode($response);
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

		// TODO ------------------------- [ COTIZADOR ] -------------------------

		function getQuotes() {
			$datos['type']  = isset($_GET['type']) ? $_GET['type'] : NULL;

			
			if ( $datos['type'] == "anteproject") {
				$this->response['data'] = $this->modeloProject->getAnteProjects();
			} else {
				$this->response['data'] = $this->modeloProject->getProjects();
			}

			foreach ($this->response['data'] as &$value) {
				$value->btn_project = "<button type=\"button\" class=\"btn btn-sfvi-1\" data-id=\"{$value->id}\" data-quote>{$value->name}</button>";
			}

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function addEquipment() {
			$response = (object) ["success" => false];
            $datos['tab'] = isset($_POST['txtTab']) ? $_POST['txtTab'] : NULL;
            $datos['description'] = isset($_POST['txtDescripcion']) ? $_POST['txtDescripcion'] : NULL;
            $datos['category'] = isset($_POST['listCategory']) ? $_POST['listCategory'] : NULL;
            $datos['price'] = isset($_POST['txtPrecio']) ? $_POST['txtPrecio'] : NULL;
            $datos['coin'] = isset($_POST['listMoneda']) ? $_POST['listMoneda'] : NULL;

            $equipos = $this->modeloQuote->addEquipment( $datos );
            
            if ($equipos->success) {
                $response["success"] = true;
            } else {
                $sresponse["error"] = $equipos->error;
            }
            header('Content-Type: application/json');
			echo json_encode($response);
			exit;
        }

		function addEquipment_to_supplier() {
			$response = (object) ["success" => false];
            $datos['tab'] = isset($_POST['txtTab']) ? trim($_POST['txtTab']) : NULL;
            $datos['supplier'] = isset($_POST['listSupplier']) ? $_POST['listSupplier'] : NULL;
            $datos['supplier_name'] = isset($_POST['listSupplier_str']) ? trim($_POST['listSupplier_str']) : NULL;
            $datos['price'] = isset($_POST['txtPrecio']) ? $_POST['txtPrecio'] : NULL;
            $datos['coin'] = isset($_POST['listCoin']) ? $_POST['listCoin'] : NULL;
            $datos['pdf'] = isset($_FILES['doc_equippment']) ? $_FILES['doc_equippment'] : NULL;
			$datos['pdf_path'] = NULL; // Ruta de donde se guarda el pdf

			$response->info = $datos;
			
			if (!$datos['supplier'] || !$datos['coin']) {
				$response->success = false;
				$response->warning = ["message" => "¡Rellena los campos faltantes!"];
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}
			
			$r = $this->modeloQuote->getEquipments();

			foreach($r->data as $value) {
				if ($value->equipment_tab == $datos['tab'] && $value->supplier_id == $datos['supplier']) {
					$response->success = false;
					$response->warning = ["message" => "El tab  \"{$datos['tab']}\" del proveedor \"{$datos['supplier_name']}\" ya existe en la lista"];
					header('Content-Type: application/json');
					echo json_encode($response);
					exit;
				}
			}

				
			if ($datos['pdf']) {
				$targetDirectory = RUTA_PUBLIC . 'pdf/equipments/' . $datos['supplier_name'] . "/";
				$this->modeloFile->makeDirectory($targetDirectory); // Crear directorios
				// Proceder a aguardar
				$r_file = $this->modeloFile->saveFile($datos['pdf'], $targetDirectory, $datos['tab']);
				if ($r_file->success) {
					$datos['pdf_path'] = $r_file->data["file"]["full_path"];
					$datos['pdf_path'] = str_replace(RUTA_PUBLIC, RUTA_URL, $datos['pdf_path']);
				}
				$response->infoFIle = $r_file;
			}

			$r = $this->modeloQuote->addEquipment_to_supplier($datos);

			if ($r->success) {
				$response->success = true;
			} else {
				$response->success = false;
				$response->error = $r->error;
			}
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}

		function addTabs() {
            $response = (object) ["success" => false];
            $datos['tab'] = isset($_POST['txtTab']) ? trim($_POST['txtTab']) : NULL;
            $datos['description'] = isset($_POST['txtDescripcion']) ? $_POST['txtDescripcion'] : NULL;
            $datos['category'] = isset($_POST['listCategory']) ? $_POST['listCategory'] : NULL;
            
            
            $tabs = $this->modeloQuote->addTabs( $datos );
            if ($tabs->success) {
                $response->success = true;
            } else {
                $response->error = $tabs->error;
            }
            header('Content-Type: application/json');
			echo json_encode($response);
			exit;
        }

		function updateEquipment_to_supplier() {
			$response = (object) ["success" => false];
			$datos["id"] = isset($_POST["proveedor__tab_id"]) ? $_POST["proveedor__tab_id"] : 0;
            $datos['tab'] = isset($_POST['txtTab']) ? trim($_POST['txtTab']) : NULL;
            $datos['supplier'] = isset($_POST['listSupplier']) ? $_POST['listSupplier'] : NULL;
            $datos['supplier_name'] = isset($_POST['listSupplier_str']) ? trim($_POST['listSupplier_str']) : NULL;
            $datos['price'] = isset($_POST['txtPrecio']) ? $_POST['txtPrecio'] : NULL;
            $datos['coin'] = isset($_POST['listCoin']) ? $_POST['listCoin'] : NULL;
            $datos['pdf'] = isset($_FILES['doc_equippment']) ? $_FILES['doc_equippment'] : NULL;
			$datos['pdf_path'] = isset($_POST['pdf_path']) ? $_POST['pdf_path'] : NULL;

			$response->POST = $datos;

			if ($datos['pdf']) {
				$targetDirectory = RUTA_PUBLIC . 'pdf/equipments/' . $datos['supplier_name'] . "/";
				$this->modeloFile->makeDirectory($targetDirectory); // Crear directorios
				// Proceder a aguardar
				$r_file = $this->modeloFile->saveFile($datos['pdf'], $targetDirectory, $datos['tab']);
				if ($r_file->success) {
					$datos['pdf_path'] = $r_file->data["file"]["full_path"];
					$datos['pdf_path'] = str_replace(RUTA_PUBLIC, RUTA_URL, $datos['pdf_path']);
				}
				$response->infoFIle = $r_file;
			}

			$r = $this->modeloQuote->updateEquipment_to_supplier($datos);

			if ($r->success) {
				$response->success = true;
			} else {
				$response->success = false;
				$response->error = $r->error;
			}

			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}

		function getEquipment() {
			$response = (object) ["success" => true];
			$datos['tab']  = isset($_GET['tab']) ? $_GET['tab'] : NULL;
			$r = $this->modeloQuote->getEquipment( $datos['tab'] );

			if ($r->success) {
				$response->success = true;
				$response->data = $r->data;
			}
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}

		function getEquipments() {
			$r = $this->modeloQuote->getEquipments();
			if (!$r->success) {
				$this->response["success"] = FALSE;
				$this->response["error"] = $r->error;	
			}
			$this->response["success"] = TRUE;
			$this->response["data"] = $r->data;

			foreach ($this->response['data'] as &$value) {
				if (strlen($value->equipment_description) > 20) {
					$value->sort_equipment_description = substr($value->equipment_description, 0, 20) . "...";
				}
				$value->btn_action = "<div class=\"d-flex justify-content-center\">";
				if ($value->pdf != Null || $value->pdf != "") {
					$value->btn_action .= "<a \"
						href=\"{$value->pdf}\".
						download
						class=\"btn btn-sfvi-1\"
					>".
					"<i class=\"fa-regular fa-file-pdf\"></i>".
					"</a>";
				} else {
					$value->btn_action .= "<button type=\"button\" class=\"btn btn-secondary\">".
					"<i class=\"fa-solid fa-file-slash\"></i>".
					"</button>";
				}

				$value->btn_action .= "<button type=\"button\" name=\"update\" data-option=\"updateEquipment_to_supplier\" class=\"btn btn-primary ms-1\">
					<i class=\"fa-solid fa-pen\"></i>
				</button>";
				$value->btn_action .= "</div>";
			}

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getCategory() {
			$r = $this->modeloQuote->getCategory();
			if (!$r->success) {
				$this->response["success"] = FALSE;
				$this->response["error"] = $r->error;	
			}
			$this->response["success"] = TRUE;
			$this->response["data"] = $r->data;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getSupplier() {
			$r = $this->modeloQuote->getSupplier();
			if (!$r->success) {
				$this->response["success"] = FALSE;
				$this->response["error"] = $r->error;	
			}
			$this->response["success"] = TRUE;
			$this->response["data"] = $r->data;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}

		function getCoin() {
			$r = $this->modeloQuote->getCoin();
			if (!$r->success) {
				$this->response["success"] = FALSE;
				$this->response["error"] = $r->error;	
			}
			$this->response["success"] = TRUE;
			$this->response["data"] = $r->data;
			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}
		

		function getQuotes_of_project() {
			$datos['id_project']  = isset($_GET['id_project']) ? $_GET['id_project'] : NULL;

			header('Content-Type: application/json');
			echo json_encode($this->response);
			exit;
		}



		// TODO ------------------------- [ ARCHIVOS ] -------------------------

		function view_doc_file() {
			$response = (object) ["success" => false];
			$datos['short_url']  = isset($_GET['short_url']) ? $_GET['short_url'] : NULL;
			$archivo = RUTA_DOCS . $datos['short_url'];
			// ?short_url=VISTAMAR/COTIZACION/quotation.pdf

			if ($datos['short_url'] == null || $datos['short_url'] == '') {
				$response->error["message"] = "Sin ruta corta del archivo";
				header('Content-Type: application/json');
				echo json_encode($response);
				exit;
			}

			// Comprobamos si el archivo existe
			if (file_exists($archivo)) {
				// Establecemos las cabeceras para indicar al navegador que es un archivo PDF
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="' . basename($archivo) . '"');
				header('Content-Length: ' . filesize($archivo));

				// Leemos y mostramos el contenido del archivo
				readfile($archivo);
			} else {
				// Manejo de error si el archivo no existe
				echo "El archivo no existe.";
			}
		}

		function update_file_of_stage_of_project() {
			$response = (object) ["success" => true]; 
			$datos['stage']  = isset($_POST['stage_id']) ? $_POST['stage_id'] : NULL;
			$datos['name_db']  = isset($_POST['paso_name_db']) ? $_POST['paso_name_db'] : NULL;
			$datos['file']  = isset($_FILES['file']) ? $_FILES['file'] : NULL;
			$info = getDatosDeGuardadoDelArchivoDeProyecto( $datos['name_db'] );
			$targetDirectory = RUTA_DOCS . $targetDirectory . '/';

			$targetDirectory .= $info['dirs_to_save'][0] . '/';


			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
		}
		
	}
?>


