<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Login</title>
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
                                <h1 class="h2">¡Bienvenido!</h1>
                                <p class="lead">Inicia sesión en tu cuenta para continuar</p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-3">
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label class="form-label">Correo electrónico</label>
                                                <input
                                                    class="form-control form-control-lg"
                                                    type="email"
                                                    name="email"
                                                    placeholder="Ingresa tu correo electrónico"
                                                    value="<?php echo $datos["email"]; ?>"
                                                />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Contraseña</label>
                                                <input
                                                    class="form-control form-control-lg"
                                                    type="password"
                                                    name="password"
                                                    placeholder="Ingresa tu contraseña"
                                                    value=""
                                                />
                                                <small>
                                                    <a href="<?php echo RUTA_URL.'User/resetPassword/'?>">
														¿Has olvidado tu contraseña?
													</a>
                                                </small>
                                            </div>
                                            <div>
                                                <div
                                                    class="form-check align-items-center d-none"
                                                >
                                                    <input
                                                        id="customControlInline"
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        value="remember-me"
                                                        name="remember-me"
                                                        checked
                                                    />
                                                    <label
                                                        class="form-check-label text-small"
                                                        for="customControlInline"
                                                    >
														Remember me
													</label>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <button type="submit" class="btn btn-lg btn-primary">
                                                    Iniciar sesión
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                ¿No tienes cuenta?
                                <a href="<?php echo RUTA_URL; ?>User/register/">Sign up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>  
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            $( document ).ready(function() {
                <?php if (isset($datos['alert'])) : ?>
                Swal.fire("Oops", "<?php echo $datos['alert']; ?>", "error");
                <?php endif; ?>
            });
        </script>
    </body>
</html>

<!-- beautify ignore:end -->
