<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>DOCUMENTACIÓN GENERAL</title>
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
                                <h2 class="text-center mt-8" style="padding-top: -50%;">DOCUMENTACIÓN GENERAL</h2>
                                <div class="container d-flex justify-content-center align-items-center">
                                        <div class="card" style="max-width: 800px; /* border: 2px solid #D4E6F1 ; */">
                                            <div class="card-body">
                                          
                                                <div class="table-responsive"></div>
                                                    <div id="table_documents_filter" class="dataTables_filter d-flex justify-content-end">
                                                        <label>Buscar:
                                                        <input type="search" class placeholder aria-controls="table_documents">
                                                        </label>
                                                    </div>
                                                    <p>
                                                    <div class="row ">
                                                        <div class="col-12 col-lg-6 mb-2">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label fs-4"> Proyecto: </label>
                                                                    <select
                                                                        name="project"
                                                                        id=""
                                                                        class="form-select"
                                                                        aria-describedby="project"
                                                                        placeholder="Proyecto"
                                                                        style="width: 361px;"
                                                                    >
                                                                        <option value="" data-folio="" selected disabled>
                                                                            <span class="fs-4" >Seleccione el Proyecto</span>
                                                                        </option>
                                                                        <?php foreach($datos['nombre_proyectos'] as $key =>
                                                                        $data) : ?>
                                                                        <option value="<?php echo $data->id; ?>">
                                                                            <?php echo $data->folio; ?>
                                                                        </option>
                                                                        <?php endforeach; ?>style="height: 30px; font-size: 12px;"
                                                                    </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="container d-flex justify-content-center align-items-center">
                                                    <div class="card" style="background-color: transparent;">
                                                        <div class="row">
                                                            <style>
                                                                .custom-btn {
                                                                    width: 200px;
                                                                    height: 160px;

                                                                }
                                                            </style>

                                                                    <div class="row">
                                                                        <div class="col-8 col-lg-4 mb-3 rounded-4">
                                                                            <div class="mb-3 rounded-4">
                                                                                <button 
                                                                                type="button" 
                                                                                class="btn custom-btn" 
                                                                                name="category_1"
                                                                                data-option="show_doc"
                                                                                >
                                                                                <span class="fs-4">Información del cliente</span>
                                                                                <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <button 
                                                                                type="button" 
                                                                                class="btn custom-btn" 
                                                                                name="category_2"
                                                                                data-option="show_doc"
                                                                                >
                                                                                <span class="fs-4">Documentación cotización</span>
                                                                                <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-8 col-lg-4 mb-3">
                                                                            <div class="mb-3">
                                                                                <button
                                                                                type="button"
                                                                                class="btn custom-btn"
                                                                                name="category_3"
                                                                                data-option="show_doc"
                                                                                >
                                                                                
                                                                                <span class="fs-4">Documentación ingeniería</span>
                                                                                <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <button 
                                                                                type="button" 
                                                                                class="btn custom-btn" 
                                                                                name="category_4"
                                                                                data-option="show_doc"
                                                                               >
                                                                                <span class="fs-4">Documentación FIDE</span>
                                                                                <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-8 col-lg-4 mb-3">
                                                                            <div class="mb-3">
                                                                                <button 
                                                                                type="button" 
                                                                                class="btn custom-btn" 
                                                                                name="category_5"
                                                                                data-option="show_doc"
                                                                                >
                                                                                <span class="fs-4">Documentación CFE</span>
                                                                                <p>
                                                                                    <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <button 
                                                                                type="button" 
                                                                                class="btn custom-btn" 
                                                                                name="category_6"
                                                                                data-option="show_doc"
                                                                                >
                                                                                <span class="fs-4">Documentación Tenergy</span>
                                                                                <p>
                                                                                    <p class="text-center fs-6">Total documentos</p>
                                                                            </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-xl d-none">
                                <div class="card">
                                    <div class="card-body"></div>
                                </div>
                            </div>
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                  </main>
             </div>
      </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_update_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_documents.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            ftable_documents();
        </script>
    </body>
</html>
