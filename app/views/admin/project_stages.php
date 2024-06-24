<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <script>
            const project_id = "<?= $datos['proyecto']->id;  ?>";
        </script>
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
                        <div class="d-flex justify-content-center align-items-center mb-4">
                            <h2 class="text-center d-inline-block mb-0"
                                style="font-family: 'SF Pro Display', sans-serif;"
                            >
                                <?= (isset($datos['proyecto']->tb) && $datos['proyecto']->tb !== null) 
                                    ? $datos['proyecto']->tb . ' - ' . $datos['proyecto']->name 
                                    : $datos['proyecto']->name;
                                ?>
                                <span
                                    class="progreso"
                                    title="Progreso actual"
                                    style="font-family: 'SF Pro Display', sans-serif;"
                                >
                                    [0%]
                                </span>
                            </h2>
                            <button
                                type="button"
                                class="btn btn-sm btn-info p-0 rounded-circle ms-2 btn-info-project"
                                style="width: 20px; height: 20px"
                                title="Información del proyecto"
                                data-option="info-stages"
                            >
                                <i class="fa-solid fa-info"></i>
                            </button>
                        </div>
                        <!-- Cargar etapas -->
                        <?php if ($datos['proyecto']->id_category == 1) : ?>
                            <?php require_once(RUTA_APP.'/views/admin/inc/stage_fide.html'); ?>
                        <?php elseif ($datos['proyecto']->id_category == 2) : ?>
                            <?php require_once(RUTA_APP.'/views/admin/inc/stage_contado.html'); ?>
                        <?php elseif ($datos['proyecto']->id_category == null) : ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="h2 card-text">Oops...</h5>
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="card-text">
                                                Proyecto sin categoria
                                            </p>
                                            <p class="card-text">
                                                Reporte esta falla a nuestros tecnicos.
                                            </p>
                                        </div>
                                        <div class="col-sm text-center text-md-end">
                                            <img
                                                src="https://pa1.aminoapps.com/6498/1df7f04504b369f082737e18e39d52520bcffba6_hq.gif"
                                                alt="img"
                                                class="img-fluid"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?> 
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-text">Oops...</h5>
                                    <p class="card-text">Categoria de proyecto Invalido</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_stages.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_file.html'); ?>
        <script src="<?= RUTA_URL ?>js/sfvi__project_stages.js"></script>
        <script>
            // $( document ).ready(function() {
            //     var opcionesMap = {
            //         center: {
            //             lat: parseFloat(<?= $datos['proyecto']->lat; ?>),
            //             lng: parseFloat(<?= $datos['proyecto']->lng; ?>),
            //         },
            //         zoom: 13,
            //     };
            //     try {
            //         var obj_map = createMap_id("#mdl_info_stages .container-map .map", opcionesMap);
            //         obj_map.marcador.draggable = false;
            //     } catch (error) {
            //         console.log("hubo un problema al cargar el mapa");
            //     }            
            //     calcularProgresoEtapa(2);

            //     <?php if ($datos['user']['int_role'] <= 2) : ?>
            //     <?php elseif ($datos['user']['int_role'] == 3) : ?>
            //         var hijos = $("[type='file']");
            //         var padre;
            //         var objetivo;

            //         $.each(hijos, function (index, value) {
            //             padre = $(value).closest(".accordion-item");
            //             objetivo = padre.find(".icon-checkbox");
                        
            //             if (objetivo.hasClass("checked")) {
            //                 $(value).prop("disabled", true);
            //             }
            //         });
            //     <?php else: ?>
            //         $("[type='file']").prop("disabled", true);
            //     <?php endif; ?>
            // });
        </script>
        <script>
            $(document).ready(function() {
                         
            });
        </script>
        <style>
            .cke_chrome {
                box-shadow: none;
                /* border-color: #ebedf2; */
            }

            .filepond--credits {
                display: none;
                height: 0px;
            }

            .filepond-container {
                width: 100%;
            }

            .filepond--root {
                margin-bottom: 0;
                height: 100%;
            }

            .filepond--panel-root {
                background-color: transparent;
                border: 1px solid #ced4da;
            }
        </style>
    </body>
</html>