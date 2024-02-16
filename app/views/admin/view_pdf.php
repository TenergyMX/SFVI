<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <style>
            @media print {
                body {
                    margin: 0;
                }
                @page {
                    margin: 0.5cm;
                }
            }
        </style>
        <title>Información Visita</title>
    </head>
    <body>
        <div class="container-fluid my-0">
            <div
                class="card border-0 bg-transparent"
                style="min-height: 100vh; box-shadow: none"
            >
                <div class="card-header bg-transparent p-0">
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
                <div class="card-body bg-transparent">
                    <form
                        action=""
                        method="get"
                        class="mx-auto"
                        style="max-width: 750px; width: 100%"
                    >
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4">
                                Tipo de visita:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="type"
                                    class="form-control-plaintext fs-4"
                                
                                    value="<?= $datos['info']->str_type_of_visit ?>" 
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4">
                                Razón de visita:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="razon"
                                    class="form-control-plaintext fs-4"
                                    value="<?= $datos['info']->description ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label
                                for=""
                                class="col-form-label col-sm-auto  fw-bolder fs-4"
                                style="box-shadow: inset"
                            >
                                Proyecto:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="proyect"
                                    class="form-control-plaintext fs-4"
                                    value="<?= $datos['info']->project_folio ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4">
                                Fecha de visita:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="date"
                                    class="form-control-plaintext fs-4"
                                    value="<?= $datos['info']->start_date ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4">
                                Visitante:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="visit"
                                    class="form-control-plaintext fs-4"
                                    value="<?= $datos['info']->str_fullname ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4"> Nota: </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="note"
                                    class="form-control-plaintext fs-4 "
                                    value="<?= $datos['info']->note ?>"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="mb-2 row gx-2">
                            <label for="" class="col-form-label col-sm-auto  fw-bolder fs-4">
                                Dirección de la visita:
                            </label>
                            <div class="col-sm">
                                <input
                                    type="text"
                                    name="direction"
                                    class="form-control-plaintext fs-4"
                                    value="<?= $datos['info']->direccion ?>"
                                    readonly
                                />
                            </div>
                        </div>
                    </form>
                   <div class="d-flex justify-content-center align-items-center" >
                        <div class="container-map print-map" style="width: 800px; height: 400px;">
                            <div class="map"></div>
                        </div>
                    </div>


                </div>
                <div class="card-footer bg-transparent p-0">
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
                        lng: parseFloat(<?= $datos['info']->lng ?>)
                    },
                    zoom: 18,
                }
                try {
                    var obj_map = createMap_id(".map", opcionesMap);
                    $("[name='btn-location']").hide();
                } catch (error) {
                    console.log("oops, ocurrio un error con el mapa");
                }
                // inmpirmir
                setTimeout(() => {
                    $("[name='btn-location']").hide();
                    window.print();
                }, 550);
            });
        </script>
    </body>
</html>
