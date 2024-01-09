<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <title>Perfil</title>
    </head>

    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <!-- navegacion  -->
                    <div class="container mb-4">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 border" style="background-color: #eee;">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="<?= RUTA_URL?>">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <!-- contenido -->
                    <div class="container-fluid">
                        <!-- CONTENIDO -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card placeholder-glow">
                                    <div class="card-body text-center">
                                        <!-- avatar -->
                                        <?php if (isset($datos['profile']['url_avatar'])) : ?>
                                        <div
                                            class="mx-auto w-100"
                                            style="max-width:150px; height:150px"
                                        >
                                            <img
                                                src="<?= $datos['profile']['url_avatar'] ?>" alt="avatar"
                                                class="img-fluid">
                                        </div>
                                        <?php else : ?>
                                        <div
                                            class="mx-auto placeholder"
                                            style="width:150px; height:150px;"
                                        >
                                            <img
                                                src="#" alt="avatar"
                                                class="img-fluid">
                                        </div>
                                        <?php endif; ?>
                                        <h5 class="my-3">
                                            <?= $datos['profile']['surnames'].' '.$datos['profile']['name'] ?>
                                        </h5>
                                        <p class="card-text">
                                            <?= $datos['profile']['str_role'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" name="form-profile" class="">
                                            <div class="row">
                                                <div class="col">
                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= $datos['profile']['id'] ?>"
                                                    >
                                                </div>
                                                <div class="col"></div>
                                            </div>
                                            <div class="row">
                                                <label class="col col-form-label fw-bold">
                                                    Nombre
                                                </label>
                                                <div class="col-md-9 col-lg-9">
                                                    <input 
                                                        type="text"
                                                        name="name"
                                                        class="form-control-plaintext text-truncate"
                                                        value="<?= $datos['profile']['name'] ?>">
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <label class="col col-form-label fw-bold">
                                                    Apellidos
                                                </label>
                                                <div class="col-md-9 col-lg-9">
                                                    <input 
                                                        type="text"
                                                        name="surnames"
                                                        class="form-control-plaintext text-truncate"
                                                        value="<?= $datos['profile']['surnames'] ?>">
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <label class="col col-form-label fw-bold">
                                                    Correo
                                                </label>
                                                <div class="col-md-9 col-lg-9">
                                                    <input 
                                                        type="text"
                                                        name="email"
                                                        class="form-control-plaintext text-truncate"
                                                        value="<?= $datos['profile']['email'] ?>">
                                                </div>
                                            </div>
                                            <?php if (
                                                $datos['user']['id'] == $datos['profile']['id'] ||
                                                $datos['user']['role'] <= 2
                                            ) : ?>
                                            <div class="row">
                                                <div class="col">
                                                    <button type="submit" class="btn btn-primary me-2">
                                                        Actualizar
                                                    </button>
                                                    <button type="button" class="btn btn-info">
                                                        Cambiar contraseña
                                                    </button>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END CONTENIDO -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>    
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script></script>
    </body>
</html>