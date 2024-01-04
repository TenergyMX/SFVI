<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <?php require_once(RUTA_APP.'/views/templates/head-pwa.html'); ?>
        <title>Información Visita</title>
    </head>
    <body>
        <div class="container-fluid my-0">
            <div
                class="card border-0 bg-transparent"
                style="min-height: 100vh; box-shadow: none"
            >
                <div class="card-header p-0">
                    <img
                        src="<?php echo RUTA_URL; ?>img/avatars/head.svg"
                        class="img-fluid w-100"
                    />
                    <div class="h2 text-center" style="font-family: 'SF Pro Display'">
                        INFORMACIÓN VISITA:
                        <br />
                        <?= $datos['info']->description ?>
                    </div>
                </div>

                <div class="card-body">
                    <form
                        action=""
                        method="get"
                        class="mx-auto"
                        style="max-width: 750px; width: 100%"
                    >
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm">
                                Tipo de visita:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="type"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->str_type_of_visit ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm">
                                Razón de visita:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="razon"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->description ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label
                                for=""
                                class="col-form-label col-sm"
                                style="box-shadow: inset"
                            >
                                Proyecto:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="proyect"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->project_folio ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm">
                                Fecha de visita:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="date"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->start_date ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm">
                                Visitante:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="visit"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->str_fullname ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm"> Nota: </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    name="note"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->note ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="" class="col-form-label col-sm">
                                Dirección de la visita:
                            </label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    name="direction"
                                    class="form-control-plaintext"
                                    value="<?= $datos['info']->direccion ?>"
                                    readonly
                                />
                            </div>
                        </div>
                    </form>
                    <div class="container-map print-map">
                        <div class="map"></div>
                    </div>
                </div>
                <div class="card-footer p-0">
                    <img
                        src="<?php echo RUTA_URL; ?>img/avatars/footer.svg"
                        class="img-fluid w-100"
                    />
                </div>
            </div>
        </div>

        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <script>
            $(document).ready(function() {
                var opcionesMap = {
                    center: {
                        lat: parseFloat(<?= $datos['info']->lat ?>),
                        lng: parseFloat(<?= $datos['info']->lon ?>)
                    },
                    zoom: 13,
                }
                try {
                    var obj_map = createMap_id(".map", opcionesMap);
                } catch (error) {
                    console.log("oops, ocurrio un error con el mapa");
                }
                // inmpirmir
                setTimeout(() => {
                    window.print();
                }, 500);
            });
        </script>
    </body>
</html>
