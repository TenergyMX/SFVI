<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>DOCUMENTACIÓN PROYECTO</title>
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
                            <div class="col" >
                                <div class="row">
                                    <div class="text-center fw-bolder fs-2">
                                        <?php if ($datos["documento"]->folio != null ||
                                        $datos["documento"]->folio != '') : ?>
                                        <p>
                                            Proyecto:
                                            <?php echo $datos["documento"]->folio; ?>
                                        </p>
                                        <?php else: ?>
                                        <p>Documentos no disponibles</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                 <!-- <div class="row">
                                    <div class="text-center fw-bolder fs-2"> -->
                                        <!-- ?php if ($datos["cliente"]->name != null || -->
                                        <!-- $datos["cliente"]->name != '') : ?> -->
                                        <!-- <p>
                                            Cliente: -->
                                            <!-- ?php echo $datos["cliente"]->name; ?> 
                                        </p> -->
                                        <!-- ?php else: ?> -->
                                        <!-- <p>Documentos no disponibles</p> -->
                                        <!-- ?php endif; ?> -->
                                    <!-- </div>
                                 </div>  -->
                                 <!-- <h2 class="text-start fs-3">DOCUMENTOS</h2> -->
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
                                                            <div class="col-8 col-lg-4 mb-3">
                                                                <div class="mb-3">
                                                                    <button 
                                                                    type="button" 
                                                                    class="btn btn-outline-success custom-btn" 
                                                                    name="category_1"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
                                                                    <span class="fs-4">Información del cliente</span>
                                                                    <p>
                                                                        <p class="text-center fs-6">Total documentos</p>
                                                                </button>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <button 
                                                                    type="button" 
                                                                    class="btn btn-outline-success custom-btn" 
                                                                    name="category_2"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
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
                                                                    class="btn btn-outline-success custom-btn"
                                                                    name="category_3"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
                                                                    
                                                                    <span class="fs-4">Documentación ingeniería</span>
                                                                    <p>
                                                                        <p class="text-center fs-6">Total documentos</p>
                                                                </button>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <button 
                                                                    type="button" 
                                                                    class="btn btn-outline-success custom-btn" 
                                                                    name="category_4"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
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
                                                                    class="btn btn-outline-success custom-btn" 
                                                                    name="category_5"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
                                                                    <span class="fs-4">Documentación CFE</span>
                                                                    <p>
                                                                        <p>
                                                                        <p class="text-center fs-6">Total documentos</p>
                                                                </button>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <button 
                                                                    type="button" 
                                                                    class="btn btn-outline-success custom-btn" 
                                                                    name="category_6"
                                                                    data-option="show_doc"
                                                                    style="box-shadow:4px 4px #ABEBC6;">
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
                            <!-- <div class="col-xl d-none">
                                <div class="card">
                                    <div class="card-body"></div>
                                </div>
                            </div> -->
                        </div>
                        
                        <!-- END CONTENIDO -->
                        
                    </div>
                    
                </main>
                
                </div>
                
            </div>
           
        </div>
         <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_documents.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            fprojects_stages();
        </script>
    </body>
</html>
