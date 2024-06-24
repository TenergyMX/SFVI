var obj_map;
$(window).on("load", function () {
    try {
        obj_map = createMap_id("#mdl_info_stages .map", opcionesMap);
    } catch (error) {
        console.log("Error en el mapa");
    }

    var pond = FilePond.create(document.querySelector('input[name="file"]'), {
        labelIdle:
            'Arrastra y suelta tus archivos aquí o <span class="filepond--label-action"> selecciona archivos </span>', // Cambia el mensaje de entrada
    });

    // Opciones
    $(document).on("click", "[data-option]", function () {
        var obj = $(this);
        let option = $(this).attr("data-option"); // Posibles valores: [create / add , update, delete]
        switch (option) {
            case "info-stages":
                // Establecer el título del modal y mostrar elementos relevantes del formulario
                $("#mdl_info_stages .modal-header .modal-title").html("INFORMACIÓN DEL PROYECTO");
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                // Mostrar el modal
                $("#mdl_info_stages").modal("show");
                break;
            case "update-file":
                var obj_modal = $("#mdl_crud_file");
                obj_modal.find("[name='stage_id']").val(obj.attr("data-stage-id"));
                obj_modal.find("[name='paso_id']").val(obj.attr("data-paso-id"));
                obj_modal.find("[name='paso_name']").val(obj.attr("data-paso-name"));
                obj_modal.find("[name='paso_name_db']").val(obj.attr("data-file-name"));
                $("#mdl_crud_file").modal("show");
                break;
        }
    });

    // Subir el archivo
    $("#mdl_crud_file form").on("submit", function (e) {
        e.preventDefault();
        var files = pond.getFiles();
        var datos = new FormData($("#mdl_crud_file form")[0]);
        var url = RUTA_URL + "Request/update_file_of_stage_of_project/";

        if (files.length !== 0) {
            var fileInput = pond.getFiles()[0].file;
            datos.set("file", fileInput);
        }

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#mdl_crud_proyect form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                if (!response["success"] && response["error"]) {
                    Swal.fire("Error", response.error["message"], "error");
                    return;
                } else if (!response["success"] && response["warning"]) {
                    Swal.fire("Advertencia", response.warning["message"], "warning");
                    return;
                } else if (!response["success"]) {
                    console.log(response);
                    Swal.fire("Error", "Ocurrio un error inesperado", "error");
                    return;
                }
                Swal.fire("Exito", "Proceso realizado con exito", "success");
            },
            error: function (jqXHR, textStatus, errorThrow) {
                Swal.fire("Oops", "Error del servidor", errorThrow);
                console.error(errorThrow);
            },
            complete: function () {},
        });
    });

    load_stages();
});

function project_stage_transition(tab = "") {
    $(".step-wizard-item").removeClass("current-item");
    $(tab).closest(".step-wizard-item").addClass("current-item");
    $(tab).tab("show");
}

function calcularProgresoEtapa(id_type = 1) {
    var resultado = {
        success: true,
        data: [],
        general: {
            progreso_completado: 0,
            progreso_maximo: 100,
            progreso_global: 0,
        },
        current_stage: null,
    };
    var category = "ante";
    var stage = 0;

    switch (id_type) {
        case 1:
        case "1":
        case "ante":
        case "anteproyecto":
            var list_objs = $("[data-finished-stage]");
            resultado["general"]["progreso_maximo"] = 700;
            $.each(list_objs, function (i, obj) {
                success = $(obj).attr("data-finished-stage");
                resultado["current_stage"] = i;
                if (!success || success == 0 || success == "0") {
                    stage = i;
                    return false;
                }
            });
            resultado["current_stage"] += 1;
            stage = resultado["current_stage"] == 7 ? 7 : stage;
            resultado["general"]["progreso_completado"] = stage > 0 ? stage * 100 : 0;
            break;
        case 2:
        case "2":
        case "proyecto":
            var etapas = $(".tab-stage"); // objetos
            category = $("[name='str_category']").val().toLowerCase();
            resultado["general"]["progreso_maximo"] = etapas.length * 100;

            for (let i = 0; i < etapas.length; i++) {
                let etapaActual = etapas.eq(i);
                let checkboxes = etapaActual.find(".icon-checkbox");
                let totalCheckboxes = checkboxes.length;
                let completados = checkboxes.filter(".checked").length;

                let porcentaje_completado =
                    totalCheckboxes > 0 ? (completados / totalCheckboxes) * 100 : 0;

                resultado["current_stage"] = i + 1;
                resultado["data"][i] = {
                    stage: i + 1,
                    progress: porcentaje_completado,
                };

                if (porcentaje_completado < 100) {
                    break;
                }
            }

            // Sumamos los porcentajes obtenidos
            $.each(resultado["data"], function (i, value) {
                resultado["general"]["progreso_completado"] += value["progress"];
            });

            // Botones
            $(".tab-stage").find(".btn-next").slice(0, resultado["current_stage"]).show();
            $(".tab-stage")
                .eq(resultado["current_stage"] - 1)
                .find(".btn-next")
                .hide();
            break;
        default:
            console.log("Id type desconocido: " + id_type);
            console.log(type(id_type));
            break;
    }

    let porcentaje_visual = resultado["general"]["progreso_completado"];
    resultado["general"]["progreso_global"] =
        (resultado["general"]["progreso_completado"] / resultado["general"]["progreso_maximo"]) * 100;
    if (resultado["general"]["progreso_completado"] == resultado["general"]["progreso_maximo"]) {
        porcentaje_visual = resultado["general"]["progreso_completado"] - 100;
    }

    // tabs
    $(`.step-wizard-item:lt(${resultado["current_stage"]}) .progress-count`).prop("disabled", false);
    $(`.step-wizard-item:gt(${resultado["current_stage"] - 1}) .progress-count`).prop("disabled", true);

    // Efecto, Cambio de la la barra de porcentaje
    $(".container-progress-bar .progress").css("width", porcentaje_visual + "%");
    $(".container-progress-bar .progress-bar").css("width", "100%");
    $(".progreso-global .progress-bar").css("width", resultado["general"]["progreso_global"] + "%");
    $(".progreso-global .progress-bar").html(resultado["general"]["progreso_global"].toFixed(2));
    $(".progreso-global .progress-bar").attr(
        "title",
        resultado["general"]["progreso_global"].toFixed(2)
    );
    $("span.progreso").html(`[${resultado["general"]["progreso_global"].toFixed(2)}%]`);

    // Cambia de etapa con un tab
    setTimeout(function () {
        console.log(`#pills-${category}-etapa-${resultado["current_stage"]}-tab`);
        project_stage_transition(`#pills-${category}-etapa-${resultado["current_stage"]}-tab`);
    }, 505);

    // Retornamos el resultado
    return resultado;
}

function check_stage(id_type = 1) {
    var bool = 0;
    switch (id_type) {
        case 1:
        case "anteproyecto":
            for (let i = 1; i < 8; i++) {
                let tab = $(`#pills-ante-etapa-${i}-files .btn-checkbox`);
                tab.hasClass("checked");
                if (tab.hasClass("checked")) {
                    bool = 1;
                } else {
                    bool = 0;
                }
                // cambiamos el estatus de la etapa en el nav
                $(`#pills-ante-etapa-${i}-tab`).attr("data-finished-stage", bool);
            }
            break;
        case 2:
        case "proyecto":
            console.log("Proyecto");
            break;
        default:
            console.log("Tipo de proyecto desconocido ['Anteproyecto', 'Proyecto']");
    }
}

function load_stages() {
    $.ajax({
        type: "GET",
        url: RUTA_URL + "Request/getStages_of_project/",
        data: {
            project_id: project_id,
        },
        beforeSend: function () {},
        success: function (response) {
            if (!response["success"] && response["error"]) {
                Swal.fire("Error", response.error["message"], "error");
                return;
            } else if (!response["success"] && response["warning"]) {
                Swal.fire("Advertencia", response.warning["message"], "warning");
                return;
            } else if (!response["success"]) {
                console.log(response);
                Swal.fire("Error", "Ocurrio un error inesperado", "error");
                return;
            }

            $.each(response["data"]["stage"], function (index, value) {
                $.each(value, function (index2, value2) {
                    var contenedor = $(`.accordion-item__${index2}`);
                    if (value2) {
                        contenedor.find(".icon-checkbox").addClass("checked");
                        contenedor.find(".input-group input").attr("placeholder", "Con archivo cargado");
                        console.log(`.accordion-item__${index2}`);
                    } else {
                    }
                });
            });

            calcularProgresoEtapa(2);
            check_stage(1);
        },
        error: function (jqXHR, textStatus, errorThrow) {},
        complete: function () {},
    });
}
