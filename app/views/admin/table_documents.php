<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <link rel="canonical" href="<?= RUTA_URL ?>Documents/">
        <title>Documentos</title>
    </head>
    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container">
                        <div class="row gx-2 justify-content-center mb-5">
                            <label class="col-auto col-form-label">
                                Proyecto:
                            </label>
                            <div class="col-12 col-sm-4">
                                <select name="project-documents" class="form-control">
                                    <option value="">
                                        Selecione
                                    </option>
                                    <?php foreach($datos['proyectos']->data as $dato) : ?>
                                    <option
                                        value="<?= $dato->id; ?>"
                                    >
                                        <?= $dato->name; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row g-3 row-folders">
                            <!-- CFE -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="CFE" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">CFE</div>
                            </div>
                            <!-- COTIZACIÓN -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="COTIZACION" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">COTIZACIÓN</div>
                            </div>
                            <!-- FIDE -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="FIDE" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">FIDE</div>
                            </div>
                            <!-- FORMATO_DE_EPP -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="FORMATO_DE_EPP" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">FORMATO DE EPP</div>
                            </div>
                            <!-- INFORMACION_CLIENTE -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="INFORMACION_CLIENTE" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">INFORMACIÓN CLIENTE</div>
                            </div>
                            <!-- INGENIERIA -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="INGENIERIA" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">INGENIERIA</div>
                            </div>
                            <!-- TENERGY -->
                            <div class="col-6 col-md-3 col-lg-2" data-folder-name="TENERGY" style="display:none">
                                <div class="folder">
                                    <div class="folder__back">
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="paper"></div>
                                        <div class="folder__front"></div>
                                        <div class="folder__front right"></div>
                                    </div>
                                </div>
            					<div class="folder-name">TENERGY</div>
                            </div>
                            <!-- END -->
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_project_documents.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
         <script src="<?= RUTA_URL ?>js/sfvi__documents.js"></script>
        <script>
            <?php if ($datos['project']['id'] != NULL) : ?>
                const miSelect = $("[name='project-documents']");
                const miOption = miSelect.find("option[value='<?= $datos['project']['id']?>']");
                miOption.prop('selected', true);
                miSelect.trigger('change');
            <?php endif; ?>
        </script>
    </body>
</html>