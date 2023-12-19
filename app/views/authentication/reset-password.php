<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Reset password</title>
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
                                <h1 class="h2">Reset password</h1>
                                <p class="lead">
                                    Ingresa tu correo electrónico para restablecer tu contraseña
                                </p>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-3">
                                        <form  name="reset-password">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input
                                                    class="form-control form-control-lg"
                                                    type="email"
                                                    name="email"
                                                    placeholder="Enter your email"
                                                />
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <button type="submit" class="btn btn-lg btn-primary">
                                                    Reset password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                ¿Tienes cuenta?
                                <a href="<?= RUTA_URL.'User/login/' ?>">
                                    Ir al login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>  
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script></script>
    </body>
</html>

<!-- beautify ignore:end -->
