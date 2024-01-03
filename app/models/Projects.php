<?php
	class Projects {
		private $db;
		
		function __construct() {
			$this->db = new Database;
		}
		
		function addProject($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO
					project(
						tb,
						name,
						id_client,
						id_category,
						id_subcategory,
						quotation_num,
						street,
						colony,
						municipality,
						state,
						start_date,
						percentage,
						lat,
						lon,
						panels,
						module_capacity,
						efficiency
					) VALUES(
						:tb,
						:name,
						:id_client,
						:id_category,
						:id_subcategory,
						:quotation_num,
						:street,
						:colony,
						:municipality,
						:state,
						:start_date,
						:percentage,
						:lat,
						:lng,
						:panels,
						:module_capacity,
						:efficiency
					)
				");
				$this->db->bind(':tb', $datos["tb"]);
				$this->db->bind(':name', $datos["name"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':id_category', $datos["id_category"]);
				$this->db->bind(':id_subcategory', $datos["id_subcategory"]);
				// $this->db->bind(':quotation', $datos["quotation"]);
				$this->db->bind(':quotation_num', $datos["quotation_num"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':percentage', $datos["percentage"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				$this->db->bind(':panels', $datos["panels"]);
				$this->db->bind(':module_capacity', $datos["module_capacity"]);
				$this->db->bind(':efficiency', $datos["efficiency"]);

				// Ejecutamos y retornamos el id
				$id = $this->db->execute2();
				if ($id) {
					$resultado->success = true;
					$resultado->id = $id;
				} else {
					$resultado->error = 'No se pudo insertar los datos en la tabla (project)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function addProjectquotation($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE project SET
					quotation = :quotation
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':quotation', $datos["quotation"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'Oops (project)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
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
		
		function getProjects() {
			$this->db->query("SELECT p.*,
				CONCAT(c.name, ' ', c.surnames) AS customer_fullName
				FROM project p
				LEFT JOIN clients c
				ON p.id_client = c.id;
			");
			return $this->db->registros();
		}

		function getStages() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		}

		function getClientes() {
			$this->db->query("SELECT * FROM clients c;");
			return $this->db->registros();

		}

		function updateProject($datos = []) {
			try {				
				$resultado = (object) ["success" => false];
				$this->db->query("UPDATE project SET
					tb = :tb,
					name = :name,
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
					lon = :lng,
					panels = :panels,
					module_capacity = :module_capacity,
					efficiency = :efficiency
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':tb', $datos["tb"]);
				$this->db->bind(':name', $datos["name"]);
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
		function createStages($id_project = NULL, $category = "FIDE") {
			$resultado = (object) ["success" => false];
			try {
				$this->db->beginTransaction();
				$number_of_stages = strtoupper($category) == 'FIDE' ? 7 : 6;
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

		// No usar esta función de ser necesaria
		function updateDataInTable($datos = []) {
			try {				
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE {$datos["table"]} SET
					{$datos["data_key"]} = :dato
					WHERE id = :id
				");
				$this->db->bind(':id', 1);
				$this->db->bind(':dato', $datos["data_value"]);
				if ($this->db->execute()) {
					$resultado = (object) ["success" => true];
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}
	}
?>