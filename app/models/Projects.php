<?php
	class Projects {
		private $db;
		
		function __construct() {
			$this->db = new Database;
		}
		
		function addProyect($datos = []) {
			try {
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("INSERT INTO
					project(
						folio,
						id_client,
						quotations,
						quotations_num,
						street,
						colony,
						municipality,
						state,
						start_date,
						lat,
						lon
					) VALUES(
						:folio,
						:id_client,
						:quotation,
						:quotation_num,
						:street,
						:colony,
						:municipality,
						:state,
						:start_date,
						:lat,
						:lng
					)
				");
				$this->db->bind(':folio', $datos["folio"]);
				$this->db->bind(':id_client', $datos["id_client"]);
				$this->db->bind(':quotation', $datos["quotation"]);
				$this->db->bind(':quotation_num', $datos["quotation_num"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error = 'No se pudo insertar los datos en la tabla (project)';
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
		}

		function getProject($id) {
			$this->db->query("SELECT * FROM project p WHERE p.id = :id");
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
				$resultado = (object) ["success" => false, "error" => ''];
				$this->db->query("UPDATE project SET
					folio = :folio,
					id_fide = :id_fide,
					street = :street,
					colony = :colony,
					municipality = :municipality,
					state = :state,
					start_date = :start_date,
					lat = :lat,
					lon = :lng
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':folio', $datos["folio"]);
				$this->db->bind(':id_fide', $datos["id_fide"]);
				$this->db->bind(':street', $datos["street"]);
				$this->db->bind(':colony', $datos["colony"]);
				$this->db->bind(':municipality', $datos["municipality"]);
				$this->db->bind(':state', $datos["state"]);
				$this->db->bind(':start_date', $datos["start_date"]);
				$this->db->bind(':lat', $datos["lat"]);
				$this->db->bind(':lng', $datos["lng"]);
				if ($this->db->execute()) {
					$resultado = (object) ["success" => true];
				}
				return $resultado;
			} catch (Exception $e) {
				$resultado = (object) ["success" => false, "error" => $e];
				return $resultado;
			}
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

		/* function getProyectos() {
			$this->db->query("SELECT * FROM project p;");
			return $this->db->registros();
		} */

		/* function getDocumentos($id){
			$this->db->query("SELECT * FROM project p WHERE p.id = :id");
			$this->db->bind(':id', $id);
			return $this->db->registro(); 
		} */

		/* function getFechaInicio($id){ 
			$this->db->query("SELECT start_date FROM project p WHERE p.id = :id;");
			$this->db->bind(':id', $id);

			return $this->db->registros();
		} */


		/* -----------------------ETAPAS FIDE------------------------ */
		function getFideEtapa1($id=0){
			$this->db->query("SELECT * FROM p_fide_stage1 WHERE id_project7=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa2($id=0){
			$this->db->query("SELECT * FROM p_fide_stage2 WHERE id_project8=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa3($id=0){
			$this->db->query("SELECT * FROM p_fide_stage3 WHERE id_project9=:id");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa4($id=0){
			$this->db->query("SELECT * FROM p_fide_stage4 WHERE id_project10=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa5($id=0){
			$this->db->query("SELECT * FROM p_fide_stage5 WHERE id_project11=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa6($id=0){
			$this->db->query("SELECT * FROM p_fide_stage6 WHERE id_project12=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa7($id=0){
			$this->db->query("SELECT * FROM p_fide_stage7 WHERE id_project13=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getFideEtapa8($id=0){
			$this->db->query("SELECT * FROM p_fide_stage8 WHERE id_project14=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}
     /* --------------------------ETAPAS CONTADO---------------------- */
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
			$this->db->query("SELECT * FROM p_contado_stage3 WHERE id_project3=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa4($id=0){
			$this->db->query("SELECT * FROM p_contado_stage4 WHERE id_project4=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa5($id=0){
			$this->db->query("SELECT * FROM p_contado_stage5 WHERE id_project5=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}

		function getContadoEtapa6($id=0){
			$this->db->query("SELECT * FROM p_contado_stage6 WHERE id_project6=:id;");
			$this->db->bind(':id', $id);
			return $this->db->registro();
		}


/* 		function getClients() {}

		function getClientsAll() {}

		function updateUser($datos = []) {}

		function deleteUser($datos = []) {} */
	}
?>