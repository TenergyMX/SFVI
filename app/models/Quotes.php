<?php
	class Quotes {
		function __construct() {
			$this->db = new Database;
		}

		function addEquipment_to_supplier($datos) {
			$resultado = (object) ["success" => false];
            try {
                $this->db->query("INSERT INTO proveedor__tabs(
					supplier,
					equipment,
					price,
					coin,
					pdf
				) VALUES (
					:supplier,
					:equipment,
					:price,
					:coin,
					:pdf
				)
				");
				$this->db->bind(':equipment', $datos["tab"]);
				$this->db->bind(':supplier', $datos["supplier"]);
				$this->db->bind(':price', $datos["price"]);
				$this->db->bind(':coin', $datos["coin"]);
				$this->db->bind(':pdf', $datos["pdf_path"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'No se pudo insertar los datos (marca)';
				}
            } catch (Exception $e) {
                $resultado->error["message"] = $e->getMessage();
				$resultado->error["code"] = $e->getCode();
            }
			return $resultado;
		}

		function getEquipment_to_supplier() {}

		function updateEquipment_to_supplier($datos) {
			$resultado = (object) ["success" => false];
			try {
                $this->db->query("UPDATE proveedor__tabs SET
					supplier = :supplier,
					equipment = :equipment,
					price = :price,
					coin = :coin,
					pdf = :pdf
					WHERE id = :id
				");
				$this->db->bind(':id', $datos["id"]);
				$this->db->bind(':equipment', $datos["tab"]);
				$this->db->bind(':supplier', $datos["supplier"]);
				$this->db->bind(':price', $datos["price"]);
				$this->db->bind(':coin', $datos["coin"]);
				$this->db->bind(':pdf', $datos["pdf_path"]);
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'No se pudo insertar los datos (marca)';
				}
            } catch (Exception $e) {
                $resultado->error["message"] = $e->getMessage();
				$resultado->error["code"] = $e->getCode();
            }
			return $resultado;
		}

		function getEquipment($tab = "") {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT e.*,
					ec.description AS category_description
					FROM equipment e
					LEFT JOIN equipment__category ec ON e.category = ec.id
					WHERE tab = :tab;
				");
				$this->db->bind(':tab', $tab);
				$resultado->success = true;
				$resultado->data = $this->db->registro();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		function getEquipments_from_supplier($supplier) {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT 
					pt.*
					FROM proveedor__tabs AS pt
					WHERE supplier = :supplier;
				");
				// Vincula el valor del parámetro
				$this->db->bind(':supplier', $supplier);
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}


		function getEquipments() {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT
					pt.id,
					p.id AS supplier_id,
                    p.name AS supplier_name,
                    e.tab AS equipment_tab,
                    e.description AS equipment_description,
                    ec.description AS equipment_category,
                    pt.price AS price,
					c.id AS coin_id,
					c.code AS coin_code,
                    c.coin AS coin_coin,
                    pt.pdf
					FROM proveedor__tabs AS pt
                    LEFT JOIN proveedor AS p ON pt.supplier = p.id
                    LEFT JOIN equipment AS e ON pt.equipment = e.tab
                    LEFT JOIN equipment__category AS ec ON e.category = ec.id
					LEFT JOIN coin AS c ON pt.coin = c.id
                    ORDER BY `pt`.`equipment` ASC;
				");
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function getQuotes_of_project($id_project = NULL) {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT * FROM quote__equipment");
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		function getCategory() {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT * FROM equipment__category;
				");
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function getSupplier() {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT * FROM proveedor;
				");
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}
		
		function getCoin() {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("SELECT * FROM coin;
				");
				$resultado->success = true;
				$resultado->data = $this->db->registros();
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		function addEquipment($datos = []) {
			$resultado = (object) ["success" => false];
            try {
                $this->db->query("INSERT INTO equipment(
					tab,
					description,
					category,
					price,
					coin
					) 
					VALUES(
					:tab,
					:description,
					:category
					:price,
					:coin
				)");
				
				$this->db->bind(':tab', $datos["tab"]);
				$this->db->bind(':description', $datos["description"]);
				$this->db->bind(':category', $datos["category"]);
				$this->db->bind(':price', $datos["price"]);
				$this->db->bind(':coin', $datos["coin"]);


				// Ejecutamos y retornamos el id
				$id = $this->db->execute2();
				if ($id) {
					$resultado->success = true;
					$resultado->id = $id;
				} else {
					$resultado->error['message'] = 'No se pudo insertar los datos (marca)';
				}
            } catch (Exception $e) {
                $resultado->error["message"] = $e->getMessage();
				$resultado->error["code"] = $e->getCode();
            }
			return $resultado;
		}

		function addTabs($datos = []) {
			$resultado = (object) array('success' => false);
			try {
				$this->db->query("INSERT INTO equipment(
					tab, 
					description,
					category 
				) VALUES(
					:tab, 
					:description,
					:category 

				)");
			
				$this->db->bind(':tab', $datos["tab"]);
				$this->db->bind(':description', $datos["description"]);
				$this->db->bind(':category', $datos["category"]);
				
				if ($this->db->execute()) {
					$resultado->success = true;
				} else {
					$resultado->error['message'] = 'No se pudo insertar los datos (project)';
				}
			} catch (Exception $e) {
				$resultado->error['message'] = $e->getMessage();
				$resultado->error['code'] = $e->getCode();
			}
			return $resultado;
		}

		
	}
	
?>