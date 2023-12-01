<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Dashboard</title>
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
                                    Enter your email to reset your password.
                                </p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-3">
                                        <form>
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
                                                <a class="btn btn-lg btn-primary" href="/"
                                                    >Reset password</a
                                                >
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                Don't have an account?
                                <a href="/pages-sign-up">Sign up</a>
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
