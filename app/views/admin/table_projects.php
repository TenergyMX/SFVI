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
                                            title="Crear proyecto"
                                            data-option="add-project"
                                        >
                                         <i class="fa-regular fa-plus me-1"></i>
                                            Crear proyecto
                                        </button>
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
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_project.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_visit.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script src="<?= RUTA_URL ?>js/sfvi__project.js"></script>
        <style>
            <?php if ($datos['user']['int_role'] >= 6) : ?>
            table thead th:nth-last-child(-n+2),
            table tbody td:nth-last-child(-n+2) {
                display: none;
            }
            <?php endif; ?>
        </style>
    </body>
</html>
