<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>VISITAS</title>
    </head>

    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container-fluid">
                        <!-- NUEVO CONTENIDO -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                title="Agregar Visita"
                                                data-option="create"
                                            >
                                                <i class="fa-regular fa-plus me-1"></i>
                                                Agregar Visita
                                                
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-info"
                                                title="Actualizar tabla"
                                                data-option="refresh_table"
                                            >
                                                <i class="fa-regular fa-arrows-rotate me-1"></i>
                                                Refrescar tabla
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-center w-100" id="table_visits">
                                                <thead>
                                                    <tr>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>4</th>
                                                        <th>5</th>
                                                        <th>6</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                    <div class="container"></div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_visit.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_visit.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_update_visit.html'); ?>  
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            $( document ).ready(function() {
                ftable_visits();
            });
        </script>
    </body>
</html>