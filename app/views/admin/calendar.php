<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
    <title>VISITAS</title>
</head>
    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="calendar" class="calendar"></div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($datos['user']['int_role'] < 6) : ?>
                            <div class="col-md-4">
                                <div class="card">
                                   <div class="card-header text-center" style="background-color: #012130;">
                                        <span class="text-white h4  font-weight-bold">AGREGAR ACTIVIDAD</span>
                                    </div>
                                    <div class="card-body">
                                        <form
                                            name="form-calendar"
                                            class="form-calendar"
                                        >
                                            <!-- x -->
                                            <div class="mb-2">
                                                <label for="">Título
                                                      <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="title" class="form-control"required>
                                            </div>
                                            <!-- x -->
                                            <div class="mb-2">
                                                <label for="">Descripción
                                                      <span class="text-danger">*</span>
                                                </label>
                                                <textarea type="text" name="description" class="form-control" aria-label="With textarea"></textarea required>
                                            </div>
                                            <!-- x -->
                                            <div class="mb-2">
                                                <label for="project">
                                                    Proyecto
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="id_project" class="form-control" required>
                                                    <option selected>Seleccione un proyecto...</option>
                                                    <?php foreach($datos['proyectos'] as $dato) : ?>
                                                        <option value="<?= $dato->id; ?>">
                                                            <?= $dato->name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- x -->
                                            <div class="mb-2">
                                                <label for="project">
                                                    Usuario:
                                                </label>
                                                <select name="id_user" class="form-control">
                                                    <option selected>Seleccione un usuario...</option>
                                                    <?php foreach($datos['usuarios'] as $dato) : ?>
                                                        <option value="<?= $dato->id; ?>">
                                                            <?= $dato->name.' '. $dato->surnames; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- x -->
                                            <div class="row gx-3 mb-2">
                                                <label for="project" class="col-12">
                                                    Color:
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-9">
                                                    <select name="color" class="form-control"id="colorSelect">
                                                        <option value="#ffffff" selected>Seleccione un color...</option>
                                                        <option value="#ff0000">Rojo</option> 
                                                        <option value="#00ff00">Verde</option>
                                                        <option value="#0000ff">Azul</option>
                                                        <option value="#800000">Maroon</option>
                                                        <option value="#ff00ff">Magenta</option>
                                                        <option value="#00ffff">Cian</option>
                                                        <option value="#ffa500">Naranja</option>
                                                        <option value="#800080">Púrpura</option>
                                                        <option value="#008000">Verde oscuro</option>
                                                        <option value="#708090">Gris</option> 
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <input type="color" name="bg" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <!-- x -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label for="">Fecha de inicio</label>
                                                    <input
                                                        type="date"
                                                        name="start_date"
                                                        class="form-control form-control-sm fecha"
                                                        id="fecha_inicio"
                                                        required 
                                                    >
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Fecha Fin</label>
                                                    <input
                                                        type="date"
                                                        name="end_date"
                                                        class="form-control form-control-sm fecha"
                                                        id="fecha_fin"
                                                        required
                                                    >
                                                </div>
                                            </div>
                                            <!-- submit -->
                                            <div class="row g-3">
                                                <div class="col-sm">
                                                    <button type="submit" class="btn btn-dark w-100">
                                                        Guardar
                                                    </button>
                                                </div>
                                                <div class="col-sm">
                                                    <button type="reset" class="btn btn-danger w-100">
                                                        Limpiar
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- end -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_calendar.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/es.js'></script>
        <script src="<?= RUTA_URL ?>js/sfvi__calendar.js"></script>
        <script>            
            document.addEventListener("DOMContentLoaded", (event) => {
                const fechaInicio = document.getElementById("fecha_inicio");
                const fechaFin = document.getElementById("fecha_fin");
                const hoy = new Date();
                const dia = ("0" + hoy.getDate()).slice(-2);
                const mes = ("0" + (hoy.getMonth() + 1)).slice(-2);
                const hoyStr = hoy.getFullYear() + "-" + mes + "-" + dia;
                fechaInicio.setAttribute("min", hoyStr);
                fechaFin.setAttribute("min", hoyStr);

                fechaInicio.addEventListener("change", (event) => {
                    fechaFin.setAttribute("min", fechaInicio.value);
                });
            });
        </script>
    </body>
</html>
