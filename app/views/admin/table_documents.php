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
                                <div class="card">
                                    <div class="card-body">
                                        
                                       <!--  <div class="mb-3">
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                data-option="create"
                                            >
                                                Agregar Proyecto
                                            </button>
                                        </div> -->
                                        <div id="table_documents_filter" class="dataTables_filter">
                                               <label>Buscar:
                                               <input type="search" class placeholder aria-controls="table_documents">
                                               </label>
                                        </div>
                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                        <span>Información del cliente</span>
                                                    </button>
                                                  
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                                    <div class="accordion-body">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                            
                                                    </div>
                                                    <h3 class="text-center" style="background-color: rgb(96, 221, 152)">
                                                        CLIENTE
                                                    </h3>
                                                    <div class="accordion-body">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                    <h3 class="text-center" style="background-color: rgb(96, 221, 152)">
                                                        CLIENTE
                                                    </h3>
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                                    <label for="" class="form-label"> Selecciona el documento </label>
                                                                    <input
                                                                        type="file"
                                                                        id="select"
                                                                        name="select"
                                                                    />
                                                                </div>
                                                                <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                    <h3 class="text-center" style="background-color: rgb(96, 221, 152)">
                                                        CLIENTE
                                                    </h3>
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                                    <label for="" class="form-label"> Selecciona el documento </label>
                                                                    <input
                                                                        type="file"
                                                                        id="select"
                                                                        name="select"
                                                                    />
                                                                </div>
                                                                <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                     
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                                        <p>Documentación Cotización</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label"> Selecciona el documento </label>
                                                            <input
                                                                type="file"
                                                                id="select"
                                                                name="select"
                                                            />
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                                        <p>Documentación Ingeniería</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label"> Selecciona el documento </label>
                                                            <input
                                                                type="file"
                                                                id="select"
                                                                name="select"
                                                            />
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                                        <p>Documentación FIDE</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label"> Selecciona el documento </label>
                                                            <input
                                                                type="file"
                                                                id="select"
                                                                name="select"
                                                            />
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingFive">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                                        <p>Documentación CFE</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
                                                    <div class="accordion-body">
                                                        <div class="mb-3">
                                                            <label for="" class="form-label"> Selecciona el documento </label>
                                                            <input
                                                                type="file"
                                                                id="select"
                                                                name="select"
                                                            />
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
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
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
             </div>
      </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_update_proyect.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_crud_visit.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            ftable_proyects();
        </script>
    </body>
</html>
