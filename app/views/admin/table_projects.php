<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>PROYECTOS</title>
    </head>

    <body
        data-theme="default"
        data-layout="fluid"
        data-sidebar-position="left"
        data-sidebar-layout="default"
    >
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
                                    <div class="card-header">
                                        <button
                                            type="button"
                                            class="btn btn-sfvi-1"
                                            title="Refrescar tabla"
                                            data-option="refresh_table"
                                        >
                                            <i class="fa-regular fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table w-100 table-center"
                                                id="table_proyects"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Proyecto</th>
                                                        <th>Cliente</th>
                                                        <th>Avance(%)</th>
                                                        <th>Documentación</th>
                                                        <th>Visitas</th>
                                                        <th>Editar</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl d-none"></div>
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                    <div class="container-fluid"></div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_stages.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_update_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_visit.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            $( document ).ready(function() {
                ftable_projects();
            });
        </script>
    </body>
</html>
