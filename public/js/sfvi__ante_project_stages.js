var obj_map;
$(window).on("load", function () {
    $("form.form-project-stages").on("submit", function (e) {
        e.preventDefault();
        var formulario = $(this);
        var modal_info = $("#mdl_info_stages");
        var datos = new FormData($(this)[0]);
        var id_type = $("[name='id_type']").val();
        var id_project = formulario.find("[name='id_project']").val();
        var stage = formulario.find("[name='stage']").val();
        var category = formulario.find("[name='category']").val();
        var project_name = modal_info.find("[name='project_name']").val();
        datos.set("id_type", id_type);
        datos.set("project_name", project_name);

        var url = RUTA_URL + "Request/updateStageData/";
        if (datos.has("check")) {
            url = RUTA_URL + "Request/updateStageData_check/";
        }

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            contentType: false,
            processData: false,
            beforeSend: function () {
                formulario.find(":input").prop("disabled", true);
            },
            success: function (data) {
                if (!data["success"]) {
                    console.log(data);
                    Swal.fire("Oops", data["error"]["message"], "error");
                    return false;
                }

                if (id_type == 1 || id_type == "1") {
                    category = "ante";
                }

                $(`#pills-${category}-etapa-${stage}-tab`).attr("data-finished-stage", 1);
                formulario.find(":input").prop("disabled", false);
                let path = null;
                let btn; // boton para descargar el archivo
                let extension = null; // Extension del archivo

                if (data["path"]) {
                    path = data["path"];
                    extension = path.split(".").pop() || "file";
                }

                if (id_type == 1 || id_type == "1") {
                    btn = $(`#pills-ante-etapa-${stage}-files .btn-checkbox`);
                    btn.addClass("px-2");
                } else if (id_type == 2 || id_type == "2") {
                    btn = formulario.closest(".accordion-item").find(".icon-checkbox");
                } else {
                    console.log("Desconozco el id_type: " + id_type);
                }

                // Agregamos los atributos necesarios para que el boton pueda descargar el archivo relacionado
                try {
                    btn.addClass("checked");
                    btn.attr("data-id-project", id_project);
                    btn.attr("data-path", path);
                    btn.show();
                } catch (error) {
                    console.error(error);
                }

                // Le avisamos al usuario
                calcularProgresoEtapa(id_type);
                Swal.fire("Good job!", "Accion exitosa", "success");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                Swal.fire("Oops", "Tenemos un promebla :( actualiza la pagina", "error");
            },
            complete: function () {
                formulario.find(":input").prop("disabled", false);
            },
        });
    });

    function check_stage(id_type = 1) {
        console.log("check_stage");
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

    check_stage(1);
    calcularProgresoEtapa(1);
});

function project_stage_transition(tab = "") {
    $(".step-wizard-item").removeClass("current-item");
    $(tab).closest(".step-wizard-item").addClass("current-item");
    $(tab).tab("show");
}

function calcularProgresoEtapa(id_type = 1) {
    console.log("calcularProgresoEtapa");
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
                console.log(success);
                if (!success || success == 0 || success == "0") {
                    stage = i;
                    return false;
                }
            });

            resultado["current_stage"] += 1;
            console.log(resultado["current_stage"]);
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
