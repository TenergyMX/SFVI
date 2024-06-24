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
                    <div class="row">
                        <div class="col-12" id="col-content-1">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <select name="select-type-project" class="form-control">
                                                <option value="anteproject">
                                                    Anteproyecto
                                                </option>
                                                <option value="project">
                                                    Proyecto
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table w-100 table-center align-middle" id="table_quote">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="col-content-2" style="display:none;">
                            <!-- x -->
                            <div class="card">
                                <div class="card-body">
                                    <!-- x -->
                                    <button type="button" name="btn" id="btn-regresar" class="btn btn-sfvi-1 mb-3">
                                        <i class="fa-solid fa-arrow-left me-2"></i>
                                        Regresar
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-light table-center w-100" id="quotes_of_project">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Folio</th>
                                                    <th>Descripción</th>
                                                    <th>Precio</th>
                                                    <th>PDF</th>
                                                    <th>8</th>
                                                    <th>9</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- x -->
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
        </div>
    </div>
    <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_quote.html'); ?>
    <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
     <script src="<?= RUTA_URL ?>js/sfvi__quote.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".filter-btn").on("click", function () {
                var targetId = $(this).data("target");
                $("[id^='col-content']").hide();
                $("#" + targetId).show();
            });
        });

        //ftable_quote();
    </script>
</body>
</html>
