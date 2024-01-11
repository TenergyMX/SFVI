<?php
	class Calc {
		private $db;
        private $response;
		
		function __construct() {
			$this->db = new Database;
            $this->response = (object) ["success" => FALSE];
		}
		
        # calculos para el administrador
        function calcTenergy() {
            $this->response->data['individual'] = [];
            $this->db->query("SELECT * FROM hsp");
            $aux = $this->db->registros();
            $datos_hsp = [];
            foreach ($aux as $objeto) {
                $datos_hsp[strtoupper($objeto->estado)] = $objeto;
            }


            $this->db->query("SELECT * FROM project p");
            $datos_project =  $this->db->registros();
            $hps_promedio = 0;
            $generacion_t = 0;

            foreach ($datos_project as $key => $value) {
                foreach ($datos_hsp as $key_2 => $value_2) {
                    if (strpos(strtoupper($value->state), $key_2) !== false) {
                        $hps_promedio = $value_2->promedio;
                        break;
                    }
                }
                
                $fechaInicial = $value->end_date;
                $fechaActual = date('Y-m-d');
                // Calcular la diferencia en segundos entre las dos fechas
                $diferenciaEnSegundos = strtotime($fechaActual) - strtotime($fechaInicial);
                // Calcular la cantidad de días redondeando hacia abajo
                $diasPasados = floor($diferenciaEnSegundos / (60 * 60 * 24));
                $diasPasados = $diasPasados >= 1 ? $diasPasados : 1;

                $generacion_t += (($value->module_capacity * $hps_promedio * ($value->efficiency/100) * $value->panels)/1000)*$diasPasados;
                // paso 2:
                $this->response->data['individual'][] = [
                    'project' => $value->name,
                    'tenergy' => (($value->module_capacity * $hps_promedio * ($value->efficiency/100) * $value->panels)/1000)*$diasPasados
                ];
            }
            // $this->response
            $this->response->data['general'] = $generacion_t;
            return $this->response;
        }

        function calcTipos_de_venta() {
            $response = (object) ["success" => FALSE];
            try {
                $this->db->query("SELECT 
                    SUM(CASE WHEN id_category = 1 THEN 1 ELSE 0 END) AS fide,
                    SUM(CASE WHEN id_category = 2 THEN 1 ELSE 0 END) AS contado,
                    COUNT(id) AS cant_projects
                    FROM project;
                ");
                $resultado = $this->db->registro();
                $resultado->fide = $resultado->fide ? $resultado->fide : 0;
                $resultado->contado = $resultado->contado ? $resultado->contado: 0;
                
                // calculo
                $propiedades = ['fide', 'contado'];
                foreach ($propiedades as $propiedad) {
                    $response->data[$propiedad] = ($resultado->$propiedad > 0) ? number_format(($resultado->$propiedad * 100) / $resultado->cant_projects, 2) : 0;
                }

                $response->success = TRUE;
            } catch (\DivisionByZeroError $e) {
                $response->data['fide'] = number_format(0, 2);
                $response->data['contado'] = number_format(0, 2);
                $response->error['message'] = "No se puede divir entre 0";
                $response->success = TRUE;
            } catch (Error $e) {
                $response->error['message'] = $e->getMessage();
				$response->error['code'] = $e->getCode();
                $response->success = FALSE;
            }
            return $response;
        }


        function calcTipos_de_proyect() {
            $response = (object) ["success" => FALSE];
            try {
                $this->db->query("SELECT 
                    SUM(CASE WHEN id_subcategory = 1 THEN 1 ELSE 0 END) AS domestico,
                    SUM(CASE WHEN id_subcategory = 2 THEN 1 ELSE 0 END) AS comercial,
                    SUM(CASE WHEN id_subcategory = 3 THEN 1 ELSE 0 END) AS industrial,
                    COUNT(id) AS cant_projects
                    FROM project;
                ");
                $resultado = $this->db->registro();
                $resultado->domestico = $resultado->domestico ? $resultado->domestico : 0;
                $resultado->comercial = $resultado->comercial ? $resultado->comercial: 0;
                $resultado->industrial = $resultado->industrial ? $resultado->industrial: 0;

                // calculo
                $tipos = ['domestico', 'comercial', 'industrial'];
                foreach ($tipos as $tipo) {
                    $response->data[$tipo] = ($resultado->$tipo > 0) ? number_format(($resultado->$tipo * 100) / $resultado->cant_projects, 2) : 0;
                }
                $response->success = TRUE;
            } catch (\DivisionByZeroError $e) {
                $response->data['domestico'] = number_format(0, 2);
                $response->data['comercial'] = number_format(0, 2);
                $response->data['industrial'] = number_format(0, 2);
                $response->error["message"] = "No se puede divir entre 0";
                $response->success = TRUE;
            } catch (Error $e) {
                $response->error['message'] = $e->getMessage();
				$response->error['code'] = $e->getCode();
                $response->success = FALSE;
            }
            return $response;
        }
	}
?>