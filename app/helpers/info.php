<?php
    // TODO | Archivo para la modificación de los datos

    function datos_session_usuario() {
        // retornamos los datos tipo session del usuario que entra al sistema
        return [
            'id' => isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0,
            'role' => isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : 6,
            'int_role' => isset($_SESSION['user']['int_role']) ? $_SESSION['user']['int_role'] : 6,
            'str_role' => isset($_SESSION['user']['str_role']) ? $_SESSION['user']['str_role'] : 'Cliente',
            'name' => isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Usuario',
            'surnames' => isset($_SESSION['user']['surnames']) ? $_SESSION['user']['surnames'] : NULL,
            'email' => isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'ejemplo@tenergy.com.mx',
            'url_avatar' => RUTA_URL . 'img/avatars/user.png'
        ];
    }

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
            'recibo_CFE' => ['new_name' => 'recibo_CFE', 'dirs_to_save' => ['INFORMACION_CLIENTE', 'COTIZACION']],
            'cotizacion' => ['new_name' => 'cotizacion', 'dirs_to_save' => ['COTIZACION']],
            'CSF' => ['new_name' => 'CSF', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'INE' => ['new_name' => 'INE', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'pago_anticipo' => ['new_name' => 'comprobante_anticipo', 'dirs_to_save' => ['TENERGY']],
            'planos' => ['new_name' => 'planos', 'dirs_to_save' => ['INGENIERIA']],
            'f_registro' => ['new_name' => 'f_registro', 'dirs_to_save' => ['INGENIERIA']],
            'material_equipo' => ['new_name' => 'material_equipo', 'dirs_to_save' => ['INGENIERIA']],
            'contenido_armado' => ['new_name' => 'contenido_armado', 'dirs_to_save' => ['INGENIERIA']],
            'checkList' => ['new_name' => 'check_supervisión', 'dirs_to_save' => ['INGENIERIA']],
            'trabajo_alturas' => ['new_name' => 'for_trabajo_alturas', 'dirs_to_save' => ['INGENIERIA']],
            'EPP' => ['new_name' => 'lista_materiales_equipo', 'dirs_to_save' => ['INGENIERIA']],
            'memoria_calculo' => ['new_name' => 'memoria_cálculo', 'dirs_to_save' => ['INGENIERIA']],
            'valid_compras' => ['new_name' => 'validación_compras', 'dirs_to_save' => ['TENERGY']],
            'report_fotografico' => ['new_name' => 'reporte_fotográfico', 'dirs_to_save' => ['INGENIERIA']],
            'pruebas_funcionamiento' => ['new_name' => 'pruebas_funcionamiento', 'dirs_to_save' => ['INGENIERIA']],
            'interconexion' => ['new_name' => 'contrato_interconexión', 'dirs_to_save' => ['CFE']],
            'contraprestacion' => ['new_name' => 'contrato_contraprestacion', 'dirs_to_save' => ['CFE']],
            'anexo_2' => ['new_name' => 'anexo_2', 'dirs_to_save' => ['CFE']],
            'img_medidor_b' => ['new_name' => 'medidor_bidereccional', 'dirs_to_save' => ['CFE']],
            'comprobante_pago' => ['new_name' => 'comprobante_finiquito', 'dirs_to_save' => ['TENERGY']],
            'liberacion_proyecto' => ['new_name' => 'liberación_pago_Tenergy', 'dirs_to_save' => ['TENERGY'], 'description' => 'Liberacion de pago Tenergy'],
            'pruebas_funcionamiento_finales' => ['new_name' => 'operacion_mante', 'dirs_to_save' => ['INGENIERIA']],
            'operacion_mante' => ['new_name' => 'Operación_mantenimiento_SFVI', 'dirs_to_save' => ['INGENIERIA']],
            'garantias' => ['new_name' => 'garantias', 'dirs_to_save' => ['INGENIERIA']],
            'CSF_PM' => ['new_name' => 'CSF_PM', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'acta_constitutiva_pm' => ['new_name' => 'acta_constitutiva_pm', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'poder_notarial_pm' => ['new_name' => 'poder_notarial_pm', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'buro_de_credito' => ['new_name' => 'buro_de_credito', 'dirs_to_save' => ['FIDE']],
            'INE_solidario' => ['new_name' => 'INE_solidario', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'CSF_solidario' => ['new_name' => 'CSF_solidario', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'CSF_repre_legal' => ['new_name' => 'CSF_repre_legal', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'recibo_CFE_aval' => ['new_name' => 'recibo_CFE_aval', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'com_domi_aval' => ['new_name' => 'com_domi_aval', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'com_dom_rl' => ['new_name' => 'com_dom_rl', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'com_dom_negocio' => ['new_name' => 'com_dom_negocio', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'INE_rl_negocio' => ['new_name' => 'INE_rl_negocio', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'predial_negocio' => ['new_name' => 'predial_negocio', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'img_medidor' => ['new_name' => 'img_medidor', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'img_fachada' => ['new_name' => 'img_fachada', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'ref_personal_1' => ['new_name' => 'ref_personal_1', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'ref_personal_2' => ['new_name' => 'ref_personal_2', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'anexo_tecnico' => ['new_name' => 'anexo_tecnico', 'dirs_to_save' => ['INFORMACION_CLIENTE']],
            'contrato_tripartita' => ['new_name' => 'contrato_tripartita', 'dirs_to_save' => ['FIDE']],
            'pagare' => ['new_name' => 'pagare', 'dirs_to_save' => ['FIDE']],
            'sol_credito' => ['new_name' => 'sol_credito', 'dirs_to_save' => ['FIDE']],
            'insentivo_energetico' => ['new_name' => 'insentivo_energetico', 'dirs_to_save' => ['FIDE']],
            'card_comp_OS' => ['new_name' => 'card_comp_OS', 'dirs_to_save' => ['FIDE']],
            'card_reconocimiento' => ['new_name' => 'card_reconocimiento', 'dirs_to_save' => ['FIDE']],
            'f_registro' => ['new_name' => 'f_registro', 'dirs_to_save' => ['INGENIERIA']],
            'material_equipo' => ['new_name' => 'material_equipo', 'dirs_to_save' => ['INGENIERIA']],
            'contenido_armado' => ['new_name' => 'contenido_armado', 'dirs_to_save' => ['INGENIERIA']],
            'checkList' => ['new_name' => 'checkList', 'dirs_to_save' => ['INGENIERIA']],
            'trabajo_alturas' => ['new_name' => 'trabajo_alturas', 'dirs_to_save' => ['INGENIERIA']],
            'EPP' => ['new_name' => 'EPP', 'dirs_to_save' => ['INGENIERIA']],
            'memoria_calculo' => ['new_name' => 'memoria_calculo', 'dirs_to_save' => ['INGENIERIA']],
            'valid_compras' => ['new_name' => 'valid_compras', 'dirs_to_save' => ['TENERGY']],
            'repor_fotografico' => ['new_name' => 'repor_fotografico', 'dirs_to_save' => ['INGENIERIA']],
            'p_funcionamiento' => ['new_name' => 'p_funcionamiento', 'dirs_to_save' => ['INGENIERIA']],
            'contrato_interconexion' => ['new_name' => 'contrato_interconexion', 'dirs_to_save' => ['CFE']],
            'contrato_contrapestacion' => ['new_name' => 'contrato_contrapestacion', 'dirs_to_save' => ['CFE']],
            'anexo_2' => ['new_name' => 'anexo_2', 'dirs_to_save' => ['CFE']],
            'img_medidor_b' => ['new_name' => 'img_medidor_b', 'dirs_to_save' => ['INGENIERIA']],
            'doc_finales_fide' => ['new_name' => 'doc_finales_fide', 'dirs_to_save' => ['TENERGY']],
        ];

        $default = [
            'new_name' => NULL,
            'dirs_to_save' => ['UNKNOWN']
        ];

        return isset($datos[$clave]) ? $datos[$clave] : $default;
    }

    // Esta funcion quita RUTA_DOCS del las ruta de la db
    function generate_project_file_path($datos = []) {
        if (count($datos['stage']) < 1) {
            return $datos;
        }

        if (count($datos['stage']) == 6) {
            // CONTADO
            // etapa 1
            $i = 0;
            $datos['stage'][$i]->recibo_CFE = $datos['stage'][$i]->recibo_CFE != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->recibo_CFE) : NULL;
            $datos['stage'][$i]->cotizacion = $datos['stage'][$i]->cotizacion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->cotizacion) : NULL;
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
            // etapa 4
            $i = 3;
            $datos['stage'][$i]->report_fotografico = $datos['stage'][$i]->report_fotografico != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->report_fotografico) : NULL;
            $datos['stage'][$i]->pruebas_funcionamiento = $datos['stage'][$i]->pruebas_funcionamiento != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->pruebas_funcionamiento) : NULL;
            // etapa 5
            $i = 4;
            $datos['stage'][$i]->interconexion = $datos['stage'][$i]->interconexion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->interconexion) : NULL;
            $datos['stage'][$i]->contraprestacion = $datos['stage'][$i]->contraprestacion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contraprestacion) : NULL;
            $datos['stage'][$i]->anexo_2 = $datos['stage'][$i]->anexo_2 != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->anexo_2) : NULL;
            $datos['stage'][$i]->img_medidor_b = $datos['stage'][$i]->img_medidor_b != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->img_medidor_b) : NULL;
            // etapa 6
            $i = 5;
            $datos['stage'][$i]->comprobante_pago = $datos['stage'][$i]->comprobante_pago != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->comprobante_pago) : NULL;
            $datos['stage'][$i]->liberacion_proyecto = $datos['stage'][$i]->liberacion_proyecto != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->liberacion_proyecto) : NULL;
            $datos['stage'][$i]->pruebas_funcionamiento_finales = $datos['stage'][$i]->pruebas_funcionamiento_finales != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->pruebas_funcionamiento_finales) : NULL;
            $datos['stage'][$i]->operacion_mante = $datos['stage'][$i]->operacion_mante != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->operacion_mante) : NULL;
            $datos['stage'][$i]->garantias = $datos['stage'][$i]->garantias != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->garantias) : NULL;
        } else {
            // FIDE
            // etapa 1
            $i = 0;
            $datos['stage'][$i]->recibo_CFE = $datos['stage'][$i]->recibo_CFE != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->recibo_CFE) : NULL;
            $datos['stage'][$i]->cotizacion = $datos['stage'][$i]->cotizacion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->cotizacion) : NULL;
            // etapa 2
            $i = 1;
            $datos['stage'][$i]->CSF_PM = $datos['stage'][$i]->CSF_PM != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->CSF_PM) : NULL;
            $datos['stage'][$i]->acta_constitutiva_pm = $datos['stage'][$i]->acta_constitutiva_pm != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->acta_constitutiva_pm) : NULL;
            $datos['stage'][$i]->poder_notarial_pm = $datos['stage'][$i]->poder_notarial_pm != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->poder_notarial_pm) : NULL;
            $datos['stage'][$i]->INE = $datos['stage'][$i]->INE != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->INE) : NULL;
            $datos['stage'][$i]->buro_de_credito = $datos['stage'][$i]->buro_de_credito != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->buro_de_credito) : NULL;
            $datos['stage'][$i]->recibo_CFE = $datos['stage'][$i]->recibo_CFE != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->recibo_CFE) : NULL;
            // etapa 3
            $i = 2;
            $datos['stage'][$i]->CSF_solidario = $datos['stage'][$i]->CSF_solidario != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->CSF_solidario) : NULL;
            $datos['stage'][$i]->CSF_repre_legal = $datos['stage'][$i]->CSF_repre_legal != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->CSF_repre_legal) : NULL;
            $datos['stage'][$i]->INE_solidario = $datos['stage'][$i]->INE_solidario != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->INE_solidario) : NULL;
            $datos['stage'][$i]->recibo_CFE_aval = $datos['stage'][$i]->recibo_CFE_aval != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->recibo_CFE_aval) : NULL;
            $datos['stage'][$i]->com_domi_aval = $datos['stage'][$i]->com_domi_aval != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->com_domi_aval) : NULL;
            $datos['stage'][$i]->com_dom_rl = $datos['stage'][$i]->com_dom_rl != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->com_dom_rl) : NULL;
            $datos['stage'][$i]->com_dom_negocio = $datos['stage'][$i]->com_dom_negocio != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->com_dom_negocio) : NULL;
            $datos['stage'][$i]->INE_rl_negocio = $datos['stage'][$i]->INE_rl_negocio != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->INE_rl_negocio) : NULL;
            $datos['stage'][$i]->predial_negocio = $datos['stage'][$i]->predial_negocio != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->predial_negocio) : NULL;
            $datos['stage'][$i]->img_medidor = $datos['stage'][$i]->img_medidor != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->img_medidor) : NULL;
            $datos['stage'][$i]->img_fachada = $datos['stage'][$i]->img_fachada != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->img_fachada) : NULL;
            $datos['stage'][$i]->ref_personal_1 = $datos['stage'][$i]->ref_personal_1 != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->ref_personal_1) : NULL;
            $datos['stage'][$i]->ref_personal_2 = $datos['stage'][$i]->ref_personal_2 != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->ref_personal_2) : NULL;
            $datos['stage'][$i]->anexo_tecnico = $datos['stage'][$i]->anexo_tecnico != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->anexo_tecnico) : NULL;
            // etapa 4
            $i = 3;
            $datos['stage'][$i]->contrato_tripartita = $datos['stage'][$i]->contrato_tripartita != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contrato_tripartita) : NULL;
            $datos['stage'][$i]->pagare = $datos['stage'][$i]->pagare != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->pagare) : NULL;
            $datos['stage'][$i]->sol_credito = $datos['stage'][$i]->sol_credito != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->sol_credito) : NULL;
            $datos['stage'][$i]->insentivo_energetico = $datos['stage'][$i]->insentivo_energetico != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->insentivo_energetico) : NULL;
            $datos['stage'][$i]->card_comp_OS = $datos['stage'][$i]->card_comp_OS != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->card_comp_OS) : NULL;
            $datos['stage'][$i]->card_reconocimiento = $datos['stage'][$i]->card_reconocimiento != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->card_reconocimiento) : NULL;
            // etapa 5
            $i = 4;
            $datos['stage'][$i]->planos = $datos['stage'][$i]->planos != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->planos) : NULL;
            $datos['stage'][$i]->f_registro = $datos['stage'][$i]->f_registro != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->f_registro) : NULL;
            $datos['stage'][$i]->material_equipo = $datos['stage'][$i]->material_equipo != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->material_equipo) : NULL;
            $datos['stage'][$i]->contenido_armado = $datos['stage'][$i]->contenido_armado != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contenido_armado) : NULL;
            $datos['stage'][$i]->checkList = $datos['stage'][$i]->checkList != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->checkList) : NULL;
            $datos['stage'][$i]->trabajo_alturas = $datos['stage'][$i]->trabajo_alturas != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->trabajo_alturas) : NULL;
            $datos['stage'][$i]->EPP = $datos['stage'][$i]->EPP != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->EPP) : NULL;
            $datos['stage'][$i]->memoria_calculo = $datos['stage'][$i]->memoria_calculo != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->memoria_calculo) : NULL;
            $datos['stage'][$i]->valid_compras = $datos['stage'][$i]->valid_compras != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->valid_compras) : NULL;
            // etapa 6
            $i = 5;
            $datos['stage'][$i]->repor_fotografico = $datos['stage'][$i]->repor_fotografico != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->repor_fotografico) : NULL;
            $datos['stage'][$i]->p_funcionamiento = $datos['stage'][$i]->p_funcionamiento != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->p_funcionamiento) : NULL;
            // etapa 7
            $i = 6;
            $datos['stage'][$i]->contrato_interconexion = $datos['stage'][$i]->contrato_interconexion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contrato_interconexion) : NULL;
            $datos['stage'][$i]->contrato_contrapestacion = $datos['stage'][$i]->contrato_contrapestacion != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->contrato_contrapestacion) : NULL;
            $datos['stage'][$i]->anexo_2 = $datos['stage'][$i]->anexo_2 != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->anexo_2) : NULL;
            $datos['stage'][$i]->img_medidor_b = $datos['stage'][$i]->img_medidor_b != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->img_medidor_b) : NULL;
            // etapa 8
            $i = 7;
            $datos['stage'][$i]->liberacion_proyecto = $datos['stage'][$i]->liberacion_proyecto != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->liberacion_proyecto) : NULL;
            $datos['stage'][$i]->operacion_mante = $datos['stage'][$i]->operacion_mante != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->operacion_mante) : NULL;
            $datos['stage'][$i]->garantias = $datos['stage'][$i]->garantias != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->garantias) : NULL;
            $datos['stage'][$i]->doc_finales_fide = $datos['stage'][$i]->doc_finales_fide != NULL ? str_replace(RUTA_DOCS, "", $datos['stage'][$i]->doc_finales_fide) : NULL;

        }

        // Respuesta
        return $datos;
    }

?>