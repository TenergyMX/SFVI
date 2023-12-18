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
                        <?php if ($datos['proyecto']->id_category == 1) : ?>
                            <div class="row">
                                <div class="text-center">
                                    <div class="text-center fw-bolder fs-3">
                                        <?php if ($datos["proyecto"]->folio != null ||
                                        $datos["proyecto"]->folio != '') : ?>
                                        <p>
                                            Nombre Proyecto:
                                            <?php echo $datos["proyecto"]->folio; ?>
                                        </p>
                                        <?php else: ?>
                                        <p>Proyecto no disponible</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                               <!--  <div class="col-6">
                                    <h5 class="h1">FIDE</h5>
                                </div> -->
                                <div class="col-6 text-start">
                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-option="create"
                                    >
                                        Ver información
                                    </button>
                                </div>
                            </div>
                            <?php require_once(RUTA_APP.'/views/admin/inc/stage_fide.html'); ?>
                        <?php elseif ($datos['proyecto']->id_category == 2) : ?>
                            <div class="row">
                                <div class="text-center fw-bolder fs-3">
                                    <?php if ($datos["proyecto"]->folio != null ||
                                    $datos["proyecto"]->folio != '') : ?>
                                    <p>
                                        Nombre Proyecto:
                                        <?php echo $datos["proyecto"]->folio; ?>
                                    </p>
                                    <?php else: ?>
                                    <p>Proyecto no disponible</p>
                                    <?php endif; ?>
                                </div>
                                <!-- <div class="col-6">
                                    <h5 class="h1">CONTADO</h5>
                                </div> -->
                                <div class="col-6 text-start">
                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-option="create"
                                    >
                                        Ver información
                                    </button>
                                </div>
                            </div>
                            <?php require_once(RUTA_APP.'/views/admin/inc/stage_contado.html'); ?>
                        <?php else: ?> 
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">No tengo idea y eso que soy el sistema</p>
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
        <script>
        fprojects_stages();
        </script>
    </body>
</html>