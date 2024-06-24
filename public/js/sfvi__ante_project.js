$(window).on("load", function () {
    var mdl_project = $("#mdl_crud_project");
    var tbl_anteprojects = new DataTable("#table_ante_projects", {
        ajax: {
            url: RUTA_URL + "Request/getAnteProjects/",
            dataSrc: "data",
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Proyecto", data: "btn_project" },
            { title: "Cliente", data: "customer_fullName" },
            { title: "Progreso (%)", data: "percentage" },
            { title: "Crear", data: "btn_action_create" },
            {
                title: "Editar",
                data: null,
                render: function (data, type, row) {
                    return `<button type="button" class="btn btn-sfvi-1" data-option="update-ante-project">
                        <i class="fa-solid fa-pen"></i>
                    </button>`;
                },
            },
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    const opcionesMapa = {
        center: {
            lat: parseFloat(20.50947009600965),
            lng: parseFloat(-100.51961774497228),
        },
        zoom: 13,
    };

    var obj_map = createMap_id("#mdl_crud_project .map", opcionesMapa);

    $(document).on("click", "[data-option]", function (e) {
        var option = $(this).attr("data-option");
        switch (option) {
            case "add-ante-project":
                mdl_project.find(".modal-title").html("AÑADIR ANTE PROYECTO");
                mdl_project.find("[type='submit']").hide();
                mdl_project.find("[name='addAnteProject']").show();
                mdl_project.find("form")[0].reset();
                mdl_project.find("#pills-project :input").prop("disabled", false);
                mdl_project.find("#pills-project .col-project :input").prop("disabled", true);
                mdl_project.find("#pills-quote :input").prop("disabled", false);
                mdl_project.find("[name='btn-location']").show();
                mdl_project.modal("show");
                $("#pills-project [name='charge']").prop("disabled", true);
                $("#pills-project [name='module_capacity']").prop("disabled", true);
                $("#pills-project [name='efficiency']").prop("disabled", true);
                $("#pills-project [name='period']").prop("disabled", true);
                $("#pills-project [name='panels']").prop("disabled", true);
                break;
            case "update-ante-project":
                mdl_project.find(".col-project :input").prop("disabled", true);
                mdl_project.find(".modal-title").html("ACTUALIZAR ANTE PROYECTO");
                mdl_project.find("[type='submit']").hide();
                mdl_project.find("[name='updateAnteProject']").show();
                mdl_project.find("form")[0].reset();

                var fila = $(this).closest("tr");
                var data = tbl_anteprojects.row(fila).data();
                var form = mdl_project.find("form");

                $.each(data, function (index, value) {
                    form.find(`[name="${index}"]`).val(value);
                });

                // cargamos la fecha al input
                if (data["start_date"] != null) {
                    form.find("[name='start_date']").val(data["start_date"].split(" ")[0]);
                }

                // cargamos visualmente la ubicacion del proyecto en el mapa
                _lat = form.find("[name='lat']").val();
                _lng = form.find("[name='lng']").val();
                update_map_location(obj_map, _lat, _lng, 18);

                mdl_project.modal("show");
                break;
            case "create-project":
                break;
        }
    });

    $("[data-refresh-table]").on("click", function (e) {
        tbl_anteprojects.ajax.reload();
    });

    mdl_project.find(".col-project").hide();
    mdl_project.find(".col-project :input").prop("disabled", true);

    // Ubicacion del proyecto
    mdl_project.find("form [name='state']").on("change", function () {
        var option = $(this).find("option:selected");
        var estado = option.attr("data-state");
        var formulario = mdl_project.find("form");
        var street = formulario.find("[name='street']").val();
        var colony = formulario.find("[name='colony']").val();
        var municipality = formulario.find("[name='municipality']").val();
        var address = "";
        address = street != "" ? `${street},` : "";
        address = colony != "" ? `${address} col. ${colony},` : "";
        address = municipality != "" ? `${address} ${municipality},` : "";
        address = estado != "" ? `${address} ${estado}` : "";
        //address = $.trim(address);
        // $("#pills-address small.address").html(address);
        console.log("La direccion es: ", address);
        address_on_the_map(obj_map, address);
    });

    $("form .calc-panels").on("input", function () {
        calc_panels();
    });

    $("#pills-quote .copy-value-to").on("input", function (e) {
        e.preventDefault();
        var obj = $(this);
        var name = obj.attr("name");
        $(`#pills-project [name="${name}"]`).val(obj.val());
        $(`#pills-project [name="${name}"]`).trigger("input");
    });

    $(".select-months select").on("change", function (e) {
        var selects = $(".select-months");
        var month_1 = parseInt(selects.find("[name='month-1'] option:selected").val(), 10);
        var month_2 = parseInt(selects.find("[name='month-2'] option:selected").val(), 10);
        var meses_1 = $(`[data-month-1]`);
        var meses_2 = $(`[data-month-2]`);

        meses_1.hide();
        meses_2.hide();

        if (month_1 < month_2) {
            console.log("if");
            for (let i = month_1; i <= month_2; i++) {
                $(`[data-month-1='${i}']`).show();
            }
        } else if (month_1 == month_2) {
            console.log("==");
            $(`[data-month-1='${month_1}']`).show();
        } else {
            console.log("else");
            for (let i = month_1; i <= 11; i++) {
                $(`[data-month-1='${i}']`).show();
            }
            for (let i = 0; i <= month_2; i++) {
                $(`[data-month-2='${i}']`).show();
            }
        }

        var periodo = $("[name='period']").val();
        var meses_visibles = $("[data-month]:visible");

        if (periodo == 59 || periodo == "59") {
            var visible = true;
            for (let i = 0; i <= meses_visibles.length; i++) {
                if (visible) {
                    visible = false;
                } else {
                    meses_visibles.eq(i).hide();
                    visible = true;
                }
            }
        }
    });

    $("[data-month] input").on("change", function (e) {
        var obj_month = $("[data-month]:visible");
        var total = parseFloat(0);
        var promedio = parseFloat(0);
        $.each(obj_month, function (i, obj) {
            var valor = $(obj).find("input").val();
            if (valor == "") {
                valor = 0;
            }
            valor = parseFloat(valor);
            total += valor;
        });

        promedio = total / obj_month.length;
        console.log(total);
        console.log(obj_month.length);
        console.log(promedio);
        mdl_project.find("[name='charge']").val(promedio);
        mdl_project.find("[name='charge']").trigger("input");
    });

    mdl_project.find("form").on("submit", function (e) {
        e.preventDefault();
        mdl_project.find("#pills-quote :input").prop("disabled", true);
        mdl_project.find(":input").removeClass("is-invalid is-valid");
        mdl_project.find(":input:not(button):not([disabled])").each(function () {
            var isEmpty = $(this).val() === "" || $(this).val() === null || $(this).val() === undefined;
            $(this).toggleClass("is-invalid", isEmpty).toggleClass("is-valid", !isEmpty);
        });
        mdl_project.find("#pills-quote :input").addClass("is-valid").removeClass("is-invalid");

        var is_invalid = mdl_project.find("form .is-invalid").length;
        if (is_invalid > 0) {
            Swal.fire("Oops", `Te hace falta rellenar campos (${is_invalid})`, "warning");
            return;
        }

        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = new FormData($("#mdl_crud_project form")[0]);
        var url = RUTA_URL + "Request/";

        if (option == "addAnteProject") {
            url += "addAnteProject";
        } else if (option == "updateAnteProject") {
            url += "updateAnteProject";
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
                tbl_anteprojects.ajax.reload();
                mdl_project.modal("hide");
            },
            error: function (jqXHR, textStatus, errorThrow) {
                Swal.fire("Oops", "Error del servidor", errorThrow);
                console.error(errorThrow);
            },
            complete: function () {
                mdl_project.find("#pills-quote :input").prop("disabled", false);
                $("#mdl_crud_proyect form [type='submit']").prop("disabled", false);
            },
        });
    });
});
