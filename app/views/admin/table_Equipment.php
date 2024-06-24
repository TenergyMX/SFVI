<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Dashboard</title>
    </head>

    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container-fluid">
                        <!-- NUEVO CONTENIDO -->
                       <div class="mb-3">
                            <button type="button" name="btn"  data-option="add-equipement" class="btn btn-sfvi-1">
                                <i class="fa-regular fa-parachute-box"></i>
                                Asignar proveedor al TAB
                            </button>
                            <button type="button" name="btn"  data-option="add-tabs" class="btn btn-sfvi-1">
                                <i class="fa-solid fa-plus"></i>
                                Agregar TAB
                            </button>
                            <button type="button" data-refresh-table='table_equipos' name="btn-refrech" class="btn btn-sfvi-1">
                                Actualizar tabla
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- x -->
                                <div class="table-responsive">
                                    <table class="table w-100 table-center align-middle" id="table_equipos">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <!-- <th></th> -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <!-- x -->
                            </div>
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>    
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_add_equipment.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_add_tabs.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_equipment.html'); ?>
        <script src="<?= RUTA_URL ?>js/sfvi__equipment.js"></script>
    </body>
</html>
