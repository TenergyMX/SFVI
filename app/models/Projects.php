<?php
	class Projects {
		private $db;
		
		function __construct() {
			$this->db = new Database;
		}
		
		function addAnteProject($datos = []) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("INSERT INTO
					project(
						tb,
						name,
						id_type,
						id_client,
						id_category,
						id_subcategory,
						id_user,
						quotation_num,
						id_fide,
						charge,
						street,
						colony,
						municipality,
						state,
						start_date,
						percentage,
						lat,
						lng,
						period,
						panels,
						module_capacity,
						efficiency
					) VALUES(
						:tb,
						:name,
						:id_type,
						:id_client,
						:id_category,
						:id_subcategory,
						:id_user,
						:quotation_num,
						:id_fide,
						:charge,
						:street,
						:colony,
						:municipality,
						:state,
						:start_date,
						:percentage,
						:lat,
						:lng,
						:period,
						:panels,
						:module_capacity,
						:efficiency
					)
				");
				$this->db->bind(':tb', NULL);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':id_type', $datos["id_type"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':id_category', $datos["id_category"]);
				$this->db->bind(':id_subcategory', $datos["id_subcategory"]);
				$this->db->bind(':id_user', $datos["id_user"]);
				$this->db->bind(':quotation_num', $datos["quotation_num"]);
				$this->db->bind(':id_fide', $datos["id_fide"]);
				$this->db->bind(':charge', $datos["charge"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':percentage', $datos["percentage"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				$this->db->bind(':period', $datos["period"]);
				$this->db->bind(':panels', $datos["panels"]);
				$this->db->bind(':module_capacity', $datos["module_capacity"]);
				$this->db->bind(':efficiency', $datos["efficiency"]);

				// Ejecutamos y retornamos el id
				$id = $this->db->execute2();
				if ($id) {
					$resultado->success = true;
					$resultado->id = $id;
				} else {
					$resultado->error['message'] = 'No se pudo insertar los datos en la tabla (project)';
				}
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		function getProject($id) {
			$this->db->query("SELECT p.*,
				c.description as str_category,
				sc.description as str_subcategory
				FROM project p
				LEFT JOIN category c
				ON p.id_category = c.id
				LEFT JOIN subcategory sc
				ON p.id_subcategory = sc.id
				WHERE p.id = :id
			");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}
		
		function getAnteProjects() {
			$this->db->query("SELECT p.*,
				CONCAT(u.name, ' ', u.surnames) AS customer_fullName,
                pas7.file_acep
				FROM project p
				LEFT JOIN users u
	 			ON p.id_client = u.id
				LEFT JOIN p_ante_stage7 pas7
                ON p.id = pas7.id_project
				WHERE id_type = 1;
			");
			return $this->db->registros();
		}

		function createProject($id_project = 0) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("UPDATE project SET 
					id_type = 2,
					percentage = 0
					WHERE id = :id
				");
				$this->db->bind(':id', $id_project);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'Ocurrio un error al tratar de crear el Proyecto';
				}
			} catch (Exception $e) {
				$resultado->error["message"] = $e->getMessage();
				$resultado->error["code"] = $e->getCode();
			}
			return $resultado;
		}


		function getProjects() {
			$this->db->query("SELECT p.*,
				CONCAT(u.name, ' ', u.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN users u
				ON p.id_client = u.id
				WHERE id_type = 2;
			");
			return $this->db->registros();
		}

		function getMyAnteProjects($id_user = 1) {
			$this->db->query("SELECT p.*,
				CONCAT(u.name, ' ', u.surnames) AS customer_fullName,
                pas7.file_acep
				FROM project p
				LEFT JOIN users u
				ON p.id_client = u.id
                LEFT JOIN p_ante_stage7 pas7
                ON p.id = pas7.id_project
				WHERE (p.id_client = :id_user OR p.id_user = :id_user)
				AND id_type = 1;
			");
			$this->db->bind(':id_user', $id_user);
			return $this->db->registros();
		}

		function getProjects_sf() {
			$this->db->query("SELECT p.*,
				CONCAT(u.name, ' ', u.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN users u
				ON p.id_client = u.id;
			");
			return $this->db->registros();
		}

		function getMyProjects($id_user = 1) {
			$this->db->query("SELECT p.*,
				CONCAT(u.name, ' ', u.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN users u
				ON p.id_client = u.id
				WHERE (p.id_client = :id_user OR p.id_user = :id_user)
				AND id_type = 2;
			");
			$this->db->bind(':id_user', $id_user);
			return $this->db->registros();
		}

		function getMyProjects_sf($id_user = 1) {
			$this->db->query("SELECT p.*,
				CONCAT(c.name, ' ', c.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN clients c
				ON p.id_client = c.id
				WHERE (p.id_client = :id_user OR p.id_user = :id_user);
			");
			$this->db->bind(':id_user', $id_user);
			return $this->db->registros();
		}

		function updateAnteProject($datos = []) {
			try {				
				$resultado = (object) ["success" => false];
				$this->db->query("UPDATE project SET
					id_client = :id_client,
					id_category= :id_category,
					id_subcategory= :id_subcategory,
					id_fide= :id_fide,
					charge= :charge,
					street= :street,
					colony= :colony,
					municipality= :municipality,
					state= :state,
					start_date= :start_date,
					lat= :lat,
					lng= :lng,
					panels= :panels,
					module_capacity= :module_capacity,
					efficiency= :efficiency
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				// $this->db->bind(':tb', $datos["tb"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':id_category', $datos["id_category"]);
				$this->db->bind(':id_subcategory', $datos["id_subcategory"]);
				$this->db->bind(':id_fide', $datos["id_fide"]);
				$this->db->bind(':charge', $datos["charge"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				$this->db->bind(':panels', $datos["panels"]);
				$this->db->bind(':module_capacity', $datos["module_capacity"]);
				$this->db->bind(':efficiency', $datos["efficiency"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = [
						'message' => 'Ocurrio un error al tratar de actualizar los datos en la tabla'
					];
				}
			} catch (Exception $e) {
				$resultado->error = [
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				];
			}
			return $resultado;
		}

		function updateProject($datos = []) {
			try {				
				$resultado = (object) ["success" => false];
				$this->db->query("UPDATE project SET
					-- tb = :tb,
					id_client = :id_client,
					id_category = :id_category,
					id_subcategory = :id_subcategory,
					id_fide = :id_fide,
					charge = :charge,
					street = :street,
					colony = :colony,
					municipality = :municipality,
					state = :state,
					start_date = :start_date,
					lat = :lat,
					lng = :lng,
					panels = :panels,
					module_capacity = :module_capacity,
					efficiency = :efficiency
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				// $this->db->bind(':tb', $datos["tb"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':id_category', $datos["id_category"]);
				$this->db->bind(':id_subcategory', $datos["id_subcategory"]);
				$this->db->bind(':id_fide', $datos["id_fide"]);
				$this->db->bind(':charge', $datos["charge"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				$this->db->bind(':panels', $datos["panels"]);
				$this->db->bind(':module_capacity', $datos["module_capacity"]);
				$this->db->bind(':efficiency', $datos["efficiency"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = [
						'message' => 'Ocurrio un error al tratar de actualizar los datos en la tabla'
					];
				}
			} catch (Exception $e) {
				$resultado->error = [
					'message' => $e->getMessage(),
					'code' => $e->getCode()
				];
			}
			return $resultado;
		}

		# Obtener la hora sol pico de cada estado
		function getStates() {
			$this->db->query("SELECT * FROM hsp;");
			return $this->db->registros();
		}

		function getVisitantes() {
			$this->db->query("SELECT * FROM users u WHERE u.role = 4;");
			return $this->db->registros();
		}

		function getClientsProjects($id = 0) {
			$this->db->query("SELECT * FROM clients c inner join project p on c.id = p.id_client;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		} 

		// TODO ------------------------- [ CREACIÓN DE ETAPAS ] ----------------------
		function createStagesAnte($id_project = NULL) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->beginTransaction();
				$number_of_stages = 7;

				// Utiliza un bucle para insertar las etapas
				for ($i = 1; $i <= $number_of_stages; $i++) {
					$this->db->query("INSERT INTO p_ante_stage{$i} (id_project) VALUES (:id_project)");
					$this->db->bind(':id_project', $id_project);
					$this->db->execute();
				}

				// Confirma la transacción
				$this->db->commit();

				// Establece el éxito en TRUE
				$resultado->success = true;
			} catch (Exception $e) {
				// Revierte la transacción en caso de error
				$this->db->rollBack();
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function createStages($id_project = NULL, $category = "FIDE") {
			$resultado = (object) ["success" => false];
			try {
				$this->db->beginTransaction();
				$number_of_stages = strtoupper($category) == 'FI|DE' ? 8 : 6;
				$category = strtolower($category);

				// Utiliza un bucle para insertar las etapas
				for ($i = 1; $i <= $number_of_stages; $i++) {
					$this->db->query("INSERT INTO p_{$category}_stage{$i} (id_project) VALUES (:id_project)");
					$this->db->bind(':id_project', $id_project);
					$this->db->execute();
				}

				// Confirma la transacción
				$this->db->commit();

				// Establece el éxito en TRUE
				$resultado->success = true;
			} catch (Exception $e) {
				// Revierte la transacción en caso de error
				$this->db->rollBack();
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}


		// TODO ------------------------- [ ETAPAS FIDE ] -----------------------------
		function getFideEtapa1($id=0){
			$this->db->query("SELECT * FROM p_fide_stage1 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa2($id=0){
			$this->db->query("SELECT * FROM p_fide_stage2 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa3($id=0){
			$this->db->query("SELECT * FROM p_fide_stage3 WHERE id_project=:id");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa4($id=0){
			$this->db->query("SELECT * FROM p_fide_stage4 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa5($id=0){
			$this->db->query("SELECT * FROM p_fide_stage5 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa6($id=0){
			$this->db->query("SELECT * FROM p_fide_stage6 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa7($id=0){
			$this->db->query("SELECT * FROM p_fide_stage7 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa8($id=0){
			$this->db->query("SELECT * FROM p_fide_stage8 WHERE id_project = :id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		// TODO ------------------------- [ ETAPAS CONTADO ] --------------------------

		function getContadoEtapa1($id=0){
			$this->db->query("SELECT * FROM p_contado_stage1 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa2($id=0){
			$this->db->query("SELECT * FROM p_contado_stage2 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa3($id=0){
			$this->db->query("SELECT * FROM p_contado_stage3 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa4($id=0){
			$this->db->query("SELECT * FROM p_contado_stage4 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa5($id=0){
			$this->db->query("SELECT * FROM p_contado_stage5 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa6($id=0){
			$this->db->query("SELECT * FROM p_contado_stage6 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		// TODO ------------------------- [ ETAPAS ANTE ] -----------------------------
		function getAnteEtapa1($id=0){
			$this->db->query("SELECT * FROM p_ante_stage1 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa2($id=0){
			$this->db->query("SELECT * FROM p_ante_stage2 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa3($id=0){
			$this->db->query("SELECT * FROM p_ante_stage3 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa4($id=0){
			$this->db->query("SELECT * FROM p_ante_stage4 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa5($id=0){
			$this->db->query("SELECT * FROM p_ante_stage5 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa6($id=0){
			$this->db->query("SELECT * FROM p_ante_stage6 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getAnteEtapa7($id=0){
			$this->db->query("SELECT * FROM p_ante_stage7 WHERE id_project=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		// TODO ------------------------- [ CHAT ] -----------------------------
		
		function addComments($datos = []) {
			$resultado = (object) ["success" => false];

			try {
				$this->db->query("INSERT INTO project_chat
					(id_user, id_project, stage, message) VALUES
					(:id_user, :id_project, :stage, :message);
				");
				$this->db->bind(':id_user', $datos['id_user']);
				$this->db->bind(':id_project', $datos['id_project']);
				$this->db->bind(':stage', $datos['stage']);
				$this->db->bind(':message', $datos['message']);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'Ocurrio un error al tratar de agregar un comentario';
				}
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function getComments($datos = []) {
			$resultado = (object) ["success" => false];
			try {
				$this->db->query("SELECT sf.*, u.name AS author
					FROM project_chat sf
					LEFT JOIN users u ON sf.id_user = u.id
					WHERE id_project = :id_project AND stage = :stage
				");
				$this->db->bind(':id_project', $datos['id_project']);
				$this->db->bind(':stage', $datos['stage']);
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}	

		// TODO ------------------------- [ XD] -----------------------------

		// No usar esta función de ser necesaria
		function updateDataInTable($datos = []) {
			$resultado = (object) ["success" => false];
			try {				
				$this->db->query("UPDATE {$datos["table"]} SET
					{$datos["data_key"]} = :dato
					{$datos["where"]}
				");
				$this->db->bind(':dato', $datos["data_value"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->success = FALSE;
					$resultado->error['message'] = "Oops, ocurrio un error al actualizar el dato";
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		
	}
?>