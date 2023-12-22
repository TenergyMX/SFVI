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
                            <?php require_once(RUTA_APP.'/views/admin/inc/stage_fide.html'); ?>
                        <?php elseif ($datos['proyecto']->id_category == 2) : ?>
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
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
        // fprojects_stages();
        </script>
    </body>
</html>