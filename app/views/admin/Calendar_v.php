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
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="calendar" class="calendar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                   <div class="card-header text-center" style="background-color: #012130;">
                                        <span class="text-white h4  font-weight-bold">AGREGAR ACTIVIDAD</span>
                                    </div>
                                    <div class="card-body">
                                        <form name="form-calendar">
                                            <div class="mb-2">
                                                <label for="">Titulo</label>
                                                <input type="text" name="title" class="form-control">
                                            </div>
                                             <div class="mb-2">
                                                <label for="">Descripcion</label>
                                                <textarea type="text" name="description" class="form-control" aria-label="With textarea"></textarea>
                                            </div>
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
                                            <div class="mb-2">
                                                <label for="project">
                                                    Usuario:
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="id_user" class="form-control" required>
                                                    <option selected>Seleccione un usuario...</option>
                                                    <?php foreach($datos['usuarios'] as $dato) : ?>
                                                        <option value="<?= $dato->id; ?>">
                                                            <?= $dato->name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="">
                                            </div>
                                            <div class="">
                                            <label for="colorPicker">Color:</label>
                                            
                                            <div class="row gx-3">
                                            <div class="col">
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
 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                            <ul>   
                                            </ul>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label for="">Fecha de inicio</label>
                                                    <input
                                                        type="date"
                                                        name="start_date"
                                                        class="form-control form-control-sm fecha"
                                                        id="fecha"
                                                        required 
                                                    >
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Fecha Fin</label>
                                                    <input
                                                        type="date"
                                                        name="end_date"
                                                        class="form-control form-control-sm fecha"
                                                        id="fecha"
                                                        required
                                                    >
                                                </div>
                                            </div>
                                            <div class="row g-3 text-center">
                                                <div class="col-md-6">
                                                    <button
                                                        type="submit"
                                                        name="submit"
                                                        class="btn btn-primary w-100"
                                                   >
                                                            Enviar
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button
                                                            type="reset"
                                                            class="btn btn-danger w-100"
                                                        >
                                                    Cancelar
                                                    </button>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/modals/mdl_info_calendar.html'); ?>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <!-- Modal para agregar evento -->
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/es.js'></script>
        <script>
            $( document ).ready(function() {
                calendar();
            });
        </script>
    </body>
</html>
