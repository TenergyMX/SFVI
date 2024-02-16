<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>ETAPAS</title>
    </head>

    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container-fluid">
                        <!-- NUEVO CONTENIDO -->
                        
                        <!-- Cargar etapas -->
                        <?php require_once(RUTA_APP.'/views/admin/inc/stage_ante.html'); ?>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_stages.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            check_stage(1);
            calcularProgresoEtapa(1);
        </script>
        <script>
            var tiempo_de_mensajes = 4000; // por cada mil es un segundo
            $( document ).ready(function() {
                setInterval(function() {
                    // state_chat(<?= $datos['proyecto']->id ?>, 1);
                    // state_chat(<?= $datos['proyecto']->id ?>, 3);
                    // state_chat(<?= $datos['proyecto']->id ?>, 4);
                    // state_chat(<?= $datos['proyecto']->id ?>, 5);
                    // state_chat(<?= $datos['proyecto']->id ?>, 6);
                    // state_chat(<?= $datos['proyecto']->id ?>, 7);
                    // state_chat(<?= $datos['proyecto']->id ?>, 2);
                }, tiempo_de_mensajes);

                <?php if ($datos['user']['int_role'] <= 2) : ?>
                <?php elseif ($datos['user']['int_role'] == 3) : ?>
                    var hijos = $("[type='file']");
                    var padre;
                    var objetivo;

                    $.each(hijos, function (index, value) {
                        padre = $(value).closest(".tab-stage");
                        objetivo = padre.find(".btn-checkbox");

                        if (objetivo.hasClass("checked")) {
                            $(value).prop("disabled", true);
                        }
                    });
                <?php else: ?>
                    $("[type='file']").prop("disabled", true);
                <?php endif; ?>
            });
        </script>
    </body>
</html>