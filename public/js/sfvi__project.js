$(window).on("load", function () {
    var mdl_project = $("#mdl_crud_project");
    var tbl_projects = new DataTable("#table_proyects", {
        ajax: {
            url: RUTA_URL + "Request/getProjects/",
            dataSrc: "data",
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Proyecto", data: "btn_project" },
            { title: "Cliente", data: "customer_fullName" },
            { title: "Avance (%)", data: "percentage" },
            { title: "Documentación", data: "btn_action_docs" },
            { title: "Visitas", data: "btn_action_visit" },
            {
                title: "Editar",
                data: null,
                render: function (data, type, row) {
                    return `<button type="button" class="btn btn-sfvi-1" data-option="update-project">
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
            case "add-project":
                mdl_project.find(".modal-title").html("AÑADIR PROYECTO");
                mdl_project.find("#pills-quote-tab").hide();
                mdl_project.find("[type='submit']").hide();
                mdl_project.find("[name='addProject']").show();
                mdl_project.find("form")[0].reset();
                mdl_project.find("#pills-project :input").prop("disabled", false);
                mdl_project.modal("show");
                break;
            case "update-project":
                mdl_project.find(".modal-title").html("ACTUALIZAR PROYECTO");
                mdl_project.find("[type='submit']").hide();
                mdl_project.find("[name='updateProject']").show();
                mdl_project.find("form")[0].reset();

                var fila = $(this).closest("tr");
                var data = tbl_projects.row(fila).data();
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
            case "add-visit":
            case "add_visit":
                var fila = $(this).closest("tr");
                var data = tbl_projects.row(fila).data();
                console.log(data);
                $("#mdl_crud_visit form [name='select_project']").val(data["id"]);
                $("#mdl_crud_visit form [name='project_name']").val(data["name"]);
                $("#mdl_crud_visit form [name='project_id']").val(data["id"]);
                $("#mdl_crud_visit form [name='select_project']").hide();
                $("#mdl_crud_visit form [name='project_name']").show();
                $("#mdl_crud_visit").modal("show");
                break;
        }
    });

    mdl_project.find("#pills-quote :input").prop("disabled", true);

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

    mdl_project.find("form").on("submit", function (e) {
        e.preventDefault();
        mdl_project.find(":input").removeClass("is-invalid is-valid");
        mdl_project.find(":input:not(button)").each(function () {
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

        if (option == "createAnteProject") {
            url += "addAnteProject";
        } else if (option == "updateAnteProject") {
            url += "updateAnteProject";
        } else if (option == "createProject") {
            url += "createProject/";
        } else if (option == "updateProject") {
            url += "updateProject";
        } else if (option == "addProject") {
            url += "addProject";
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
                tbl_projects.ajax.reload();
                mdl_project.modal("hide");
            },
            error: function (jqXHR, textStatus, errorThrow) {
                Swal.fire("Oops", "Error del servidor", errorThrow);
                console.error(errorThrow);
            },
            complete: function () {
                $("#mdl_crud_proyect form [type='submit']").prop("disabled", false);
            },
        });
    });

    $("form .calc-panels").on("input", function () {
        calc_panels();
    });
});
