<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Nueva contraseña</title>
    </head>

    <body
        data-theme="default"
        data-layout="fluid"
        data-sidebar-position="left"
        data-sidebar-layout="default"
    >
        <main class="d-flex w-100 h-100">
            <div class="container d-flex flex-column">
                <div class="row vh-100">
                    <div
                        class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100"
                    >
                        <div class="d-table-cell align-middle">
                            <div class="text-center mt-4">
                                <h1 class="h2">Nueva Contraseña</h1>
                                <p class="lead">
                                    Digita tu nueva contraseña
                                </p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-3">
                                        <form name="new-password" method="POST">
                                            <div class="mb-0">
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    class="form-control form-control-lg"
                                                    value="<?= $datos['id'] ?>"
                                                >
                                            </div>
                                            <div class="mb-3">
                                                <input
                                                    class="form-control form-control-lg"
                                                    type="password"
                                                    name="password"
                                                    placeholder="Enter your password"
                                                />
                                            </div>
                                            <div class="mb-3">
                                                <input
                                                    class="form-control form-control-lg"
                                                    type="password"
                                                    name="password2"
                                                    placeholder="Confirm your password"
                                                />
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <button type="submit" name="new_password" class="btn btn-lg btn-primary">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                <?= trim($datos["alert-script"]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>  
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            <?php if ($datos["alert-script"]) : ?>
                Swal.fire("Oops", "<?= trim($datos["alert-script"]) ?>", "error");
            <?php endif; ?>
        </script>
    </body>
</html>