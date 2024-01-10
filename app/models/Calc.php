<?php
	class Calc {
		private $db;
        private $response;
		
		function __construct() {
			$this->db = new Database;
            $this->response = (object) ["success" => false];
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


            $this->db->query("SELECT * FROM project");
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

                $generacion_t += (($value->module_capacity * $hps_promedio * ($value->efficiency/100) * $value->panels)/1000)*$diasPasados;
                
                // paso 2:
                $this->response->data['individual'][] = [
                    'project' => $value->folio,
                    'tenergy' => (($value->module_capacity * $hps_promedio * ($value->efficiency/100) * $value->panels)/1000)*$diasPasados
                ];
            }
            // $this->response
            $this->response->data['general'] = $generacion_t;
            return $this->response;
        }

        function calcTipos_de_venta() {
            $this->db->query("SELECT 
                SUM(CASE WHEN id_category = 1 THEN 1 ELSE 0 END) AS contado,
                SUM(CASE WHEN id_category = 2 THEN 1 ELSE 0 END) AS fide,
                COUNT(id) AS cant_projects
                FROM project;
            ");
            $resultado = $this->db->registro();

            $this->response->success = TRUE;
            $this->response->data['fide'] = number_format(($resultado->fide * 100) / $resultado->cant_projects, 2);
            $this->response->data['contado'] = number_format(($resultado->contado * 100) / $resultado->cant_projects, 2);
            return $this->response;
}


        function calcTipos_de_proyect() {
            $this->db->query("SELECT 
                SUM(CASE WHEN id_subcategory = 1 THEN 1 ELSE 0 END) AS domestico,
                SUM(CASE WHEN id_subcategory = 2 THEN 1 ELSE 0 END) AS comercial,
                SUM(CASE WHEN id_subcategory = 3 THEN 1 ELSE 0 END) AS industrial,
                COUNT(id) AS cant_projects
                FROM project;
            ");
            $resultado = $this->db->registro();

            $this->response->success = TRUE;
            $this->response->data['domestico'] = number_format(($resultado->domestico * 100) / $resultado->cant_projects, 2);
            $this->response->data['comercial'] = number_format(($resultado->comercial * 100) / $resultado->cant_projects, 2);
            $this->response->data['industrial'] = number_format(($resultado->industrial * 100) / $resultado->cant_projects, 2);
            return $this->response;
        }
	}
?>