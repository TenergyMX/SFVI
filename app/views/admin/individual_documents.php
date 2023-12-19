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
                            <div class="col-12" style="background-position-x:center;">
                                <div class="row">
                                    <div class="text-center fw-bolder fs-3">
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
                                <h2 class="text-start fs-3">DOCUMENTOS</h2>
                                <div class="card">
                                    <div class="card-body">
                                     
                                        <div id="table_documents_filter" class="dataTables_filter">
                                               <label>Buscar:
                                               <input type="search" class placeholder aria-controls="table_documents">
                                               </label>
                                        </div>
                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                        <p class="text-start fs-4">Documentos</p>
                                                    </button>
                                                  
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                                    <div class="accordion-body">
                                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                                    <div class="mb-3 ">
                                                                        <label for="" class="form-label"> Selecciona el documento </label>
                                                                        <p>
                                                                        <input
                                                                            type="file"
                                                                            id="select"
                                                                            name="select"
                                                                        />
                                                                    </div>
                                                                    <button type="button" class="btn btn-success" name="add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                                        </svg></button>

                                                                 </div>
                                                                
                                                            <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                                        <p class="text-start fs-4">Documentos</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                                                    <div class="accordion-body">
                                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-success" name="add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                                        </svg></button>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                   </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                                        <p class="text-start fs-4">Documentos</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                                    <div class="accordion-body">
                                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-success" name="add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                                        </svg></button>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                                        <p class="text-start fs-4">Documentos</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                                                    <div class="accordion-body">
                                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-success" name="add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                                        </svg></button>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" name="add">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingFive">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                                        <p class="text-start fs-4">Documentos</p>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
                                                    <div class="accordion-body">
                                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label"> Selecciona el documento </label>
                                                                <input
                                                                    type="file"
                                                                    id="select"
                                                                    name="select"
                                                                />
                                                            </div>
                                                            <button type="button" class="btn btn-success" name="add"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                                        </svg></button>
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
                            <!-- <div class="col-xl d-none">
                                <div class="card">
                                    <div class="card-body"></div>
                                </div>
                            </div> -->
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            ftable_proyects();
        </script>
    </body>
</html>
