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
                
                $fechaInicial = $value->start_date;
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
	}
?>