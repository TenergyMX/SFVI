<?php
    function getProjectSubfolderNames($category) {
        if ($category == NULL) { return []; }

        $datos = [
            "CONTADO" => ["INFORMACION_CLIENTE", "COTIZACION", "INGENIERIA", "CFE", "TENERGY"],
            "FIDE" => ["INFORMACION_CLIENTE", "INGENIERIA", "FORMATO_DE_EPP", "FIDE", "CFE", "TENERGY"]
        ];
        $datos['1'] = $datos[1] = $datos['FIDE'];
        $datos['2'] = $datos[2] = $datos['CONTADO'];
        $default = [];
        return isset($datos[$category]) ? $datos[$category] : $default;
    }

    function getDatosDeGuardadoDelArchivoDeProyecto($clave) {
        $datos = [
            'recibo_CFE' => [
                'new_name' => 'recibo_CFE',
                'dirs_to_save' => ['INFORMACION_CLIENTE', 'COTIZACION']
            ],
            'cotizacion' => [
                'new_name' => 'cotizacion',
                'dirs_to_save' => ['COTIZACION']
            ],
            'CSF' => [
                'new_name' => 'CSF',
                'dirs_to_save' => ['INFORMACION_CLIENTE']
            ],
            'INE' => [
                'new_name' => 'INE',
                'dirs_to_save' => ['INFORMACION_CLIENTE']
            ],
            'pago_anticipo' => [
                'new_name' => 'comprobante_anticipo',
                'dirs_to_save' => ['TENERGY']
            ],
            'planos' => [
                'new_name' => 'planos',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'f_registro' => [
                'new_name' => 'for_registro',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'material_equipo' => [
                'new_name' => 'lista_materiales_equipo',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'contenido_armado' => [
                'new_name' => 'contenido_armado',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'checkList' => [
                'new_name' => 'check_supervisión',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'trabajo_alturas' => [
                'new_name' => 'for_trabajo_alturas',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'EPP' => [
                'new_name' => 'lista_materiales_equipo',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'memoria_calculo' => [
                'new_name' => 'memoria_cálculo',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'valid_compras' => [
                'new_name' => 'validación_compras',
                'dirs_to_save' => ['TENERGY']
            ],
            'report_fotografico' => [
                'new_name' => 'reporte_fotográfico',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'pruebas_funcionamiento' => [
                'new_name' => 'pruebas_funcionamiento',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'interconexion' => [
                'new_name' => 'contrato_interconexión',
                'dirs_to_save' => ['CFE']
            ],
            'contraprestacion' => [
                'new_name' => 'contrato_contraprestacion',
                'dirs_to_save' => ['CFE']
            ],
            'anexo_2' => [
                'new_name' => 'anexo_2',
                'dirs_to_save' => ['CFE']
            ],
            'img_medidor_b' => [
                'new_name' => 'medidor_bidereccional',
                'dirs_to_save' => ['CFE']
            ],
            'comprobante_pago' => [
                'new_name' => 'comprobante_finiquito',
                'dirs_to_save' => ['TENERGY']
            ],
            'liberacion_proyecto' => [
                'new_name' => 'liberación_pago_Tenergy',
                'dirs_to_save' => ['TENERGY'],
                'description' => 'Liberacion de pago Tenergy'
            ],
            'pruebas_funcionamiento_finales' => [
                'new_name' => 'operacion_mante',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'operacion_mante' => [
                'new_name' => 'Operación_mantenimiento_SFVI',
                'dirs_to_save' => ['INGENIERIA']
            ],
            'garantias' => [
                'new_name' => 'garantias',
                'dirs_to_save' => ['INGENIERIA']
            ],
        ];

        $default = [
            'new_name' => NULL,
            'dirs_to_save' => ['UNKNOWN']
        ];

        return isset($datos[$clave]) ? $datos[$clave] : $default;
    }

    // Esta funcion quita RUTA_DOCS del las ruta de la db
    function generate_project_file_path($datos = []) {
        if (isset($datos['stage'][0])) {
            return $datos;
        }

        if (count($datos['stage']) == 6) {
            // etapa 1
            $i = 0;
            $datos['stage'][$i]->recibo_CFE = str_replace(RUTA_DOCS, "", $datos['stage'][$i]->recibo_CFE);
            $datos['stage'][$i]->cotizacion = str_replace(RUTA_DOCS, "", $datos['stage'][$i]->cotizacion);
            // etapa 2
            $i = 1;
            $datos['stage'][$i]->CSF = $datos['stage'][$i]->CSF != null ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->CSF) : NULL;
            $datos['stage'][$i]->INE = $datos['stage'][$i]->INE != null ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->INE) : NULL;
            $datos['stage'][$i]->pago_anticipo = $datos['stage'][$i]->pago_anticipo != null ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->pago_anticipo) : null;
            // etapa 3
            $i = 2;
            $datos['stage'][$i]->planos = $datos['stage'][$i]->planos != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->planos) : NULL;
            $datos['stage'][$i]->f_registro = $datos['stage'][$i]->f_registro != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->f_registro) : NULL;
            $datos['stage'][$i]->material_equipo = $datos['stage'][$i]->material_equipo != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->material_equipo) : NULL;
            $datos['stage'][$i]->contenido_armado = $datos['stage'][$i]->contenido_armado != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contenido_armado) : NULL;
            $datos['stage'][$i]->checkList = $datos['stage'][$i]->checkList != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->checkList) : NULL;
            $datos['stage'][$i]->trabajo_alturas = $datos['stage'][$i]->trabajo_alturas != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->trabajo_alturas) : NULL;
            $datos['stage'][$i]->EPP = $datos['stage'][$i]->EPP != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->EPP) : NULL;
            $datos['stage'][$i]->memoria_calculo = $datos['stage'][$i]->memoria_calculo != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->memoria_calculo) : NULL;
            $datos['stage'][$i]->valid_compras = $datos['stage'][$i]->valid_compras != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->valid_compras) : NULL;
        } else {
            // etapa 4
            $i = 3;
            $datos['stage'][$i]->contrato_tripartita = $datos['stage'][$i]->contrato_tripartita != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contrato_tripartita) : NULL;
            $datos['stage'][$i]->pagare = $datos['stage'][$i]->pagare != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->pagare) : NULL;
            $datos['stage'][$i]->sol_credito = $datos['stage'][$i]->sol_credito != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->sol_credito) : NULL;
            $datos['stage'][$i]->insentivo_energetico = $datos['stage'][$i]->insentivo_energetico != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->insentivo_energetico) : NULL;
            $datos['stage'][$i]->card_comp_OS = $datos['stage'][$i]->card_comp_OS != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->card_comp_OS) : NULL;
            $datos['stage'][$i]->card_reconocimiento = $datos['stage'][$i]->card_reconocimiento != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->card_reconocimiento) : NULL;

        }

        // Respuesta
        return $datos;
    }

?>