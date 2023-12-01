<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Dashboard</title>
    </head>

    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <main class="d-flex w-100 h-100">
            <div class="container d-flex flex-column">
                <div class="row vh-100">
                    <div
                        class="col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100"
                    >
                        <div class="d-table-cell align-middle">
                            <div class="text-center">
                                <h1 class="display-6 fw-bold">NO AUTORIZADO</h1>
                                <p class="h2">Tu rol no cuenta con los permisos para esta vista</p>
                                <p class="lead fw-normal mt-3 mb-4">
                                    Tu rol es: <?php echo trim($datos['user']['str_role']); ?>
                                </p>
                                <a
                                    class="btn btn-primary btn-lg"
                                    href="<?php echo RUTA_URL; ?>"
                                >
                                    Ir al inicio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script></script>
    </body>
</html>