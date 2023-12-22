// TODO ------------------------- [ Variables Globales ] -------------------------
const opcionesMapa = {
    center: {
        lat: parseFloat(20.50947009600965),
        lng: parseFloat(-100.51961774497228),
    },
    zoom: 13,
};

// TODO ------------------------- [ USUARIOS ] -------------------------
function ftable_users() {
    var tbl_users = new DataTable("#table_users", {
        ajax: {
            url: RUTA_URL + "Request/getUsers/",
            dataSrc: "data",
        },
        columns: [
            { title: "id", data: "id" },
            { title: "Rol", data: "str_role" },
            { title: "Nombre", data: "name" },
            { title: "Apellidos", data: "surnames" },
            { title: "Correo Electrónico", data: "email" },
        ],

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    // Configurar el modal
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                $("#mdl_crud_user .modal-header .modal-title").html("AGREGAR USUAIO");
                $("#mdl_crud_user .modal-body form [name='add']").show();
                $("#mdl_crud_user .modal-body form [name='update']").hide();
                $("#mdl_crud_user").modal("show");
                break;
            case "update":
                $("#mdl_crud_user .modal-header .modal-title").html("ACTUALIZAR USUARIO");
                $("#mdl_crud_user .modal-body form [name='add']").hide();
                $("#mdl_crud_user .modal-body form [name='update']").show();
                $("#mdl_crud_user").modal("show");
                break;
            default:
                alert("Opcion no valida");
        }
        // end option
    });

    $("[name='role']").on("change", function () {
        var select_clients = $("#mdl_crud_user form [name='name-clients']");
        let val = parseInt($('[name="role"] option:selected').val());

        if (val !== 6) {
            select_clients.hide();
            return;
        }
        select_clients.show();
        select_clients.html(
            '<option value="" disabled selected>Selecciona el cliente</option>'
        );

        $.ajax({
            url: RUTA_URL + "Request/getClients/",
            beforeSend: function () {},
            success: function (response) {
                $.each(response.data, function (key, value) {
                    select_clients.append(
                        `<option value="${key}">${value.name} ${value.surnames}</option>`
                    );
                });

                $("[name='name-clients']").on("change", function () {
                    let client = parseInt(
                        $('[name="name-clients"] option:selected').val()
                    );

                    var datos_client = response.data[client];
                    $("#mdl_crud_user .modal-body form [name='name']").val(
                        datos_client["name"]
                    );
                    $("#mdl_crud_user .modal-body form [name='surnames']").val(
                        datos_client["surnames"]
                    );
                    $("#mdl_crud_user .modal-body form [name='email']").val(
                        datos_client["email"]
                    );
                });
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log("hubo un error: " + errorThrow);
                Swal.fire("Error", errorThrow, "error");
            },
            complete: function () {},
        });
        // end
    });

    // Create and update user
    $("#mdl_crud_user form").on("submit", function (e) {
        e.preventDefault();

        let pass_1 = $("#mdl_crud_user form [name='password']").val();
        let pass_2 = $("#mdl_crud_user form [name='confirmation']").val();

        if (pass_1 != pass_2) {
            alert("La contraseña no coincide");
            return;
        }

        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = $(this).serialize();
        var url = RUTA_URL + "Request/" + (option == "add" ? "addUser" : "updateUser");

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            beforeSend: function () {
                $("#mdl_crud_user form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                tbl_users.ajax.reload();
                $("#mdl_crud_user").modal("hide");
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", "fallo algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log("hubo un error: " + errorThrow);
                Swal.fire("Error del servidor", errorThrow, "error");
            },
            complete: function () {
                $("#mdl_crud_user form [type='submit']").prop("disabled", false);
            },
        });
    });
    // End
}

// TODO ------------------------- [ CLIENTES ] -------------------------
function ftable_clients() {
    var tbl_clients = new DataTable("#table_clients", {
        ajax: {
            url: RUTA_URL + "Request/getClients/",
            dataSrc: "data",
        },
        columns: [
            { title: "id", data: "id" },
            { title: "Nombre", data: "name" },
            { title: "Apellidos", data: "surnames" },
            { title: "Estado", data: "state" },
            { title: "Telefono", data: "phone" },
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    // Configurar el modal
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                $("#mdl_crud_client .modal-header .modal-title").html("AGREGAR CLIENTE");
                $("#mdl_crud_client .modal-body form [name='add']").show();
                $("#mdl_crud_client .modal-body form [name='update']").hide();
                $("#mdl_crud_client").modal("show");
                break;
            case "update":
                $("#mdl_crud_client .modal-header .modal-title").html(
                    "ACTUALIZAR CLIENTE"
                );
                $("#mdl_crud_client .modal-body form [name='add']").hide();
                $("#mdl_crud_client .modal-body form [name='update']").show();
                $("#mdl_crud_client").modal("show");
                break;
            default:
                alert("Opcion no valida");
        }
        // end option
    });

    // Create and update user
    $("#mdl_crud_client form").on("submit", function (e) {
        e.preventDefault();
        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = $(this).serialize();
        var url =
            RUTA_URL + "Request/" + (option == "add" ? "addClient" : "updateClient");
        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            beforeSend: function () {
                $("#mdl_crud_client form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                tbl_clients.ajax.reload();
                $("#mdl_crud_client").modal("hide");
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", "fallo algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log("hubo un error: " + errorThrow);
                Swal.fire("Error del servidor", errorThrow, "error");
            },
            complete: function () {
                $("#mdl_crud_client form [type='submit']").prop("disabled", false);
            },
        });
    });
    // End
}
// TODO ------------------------- [ VISITAS ] -------------------------
function ftable_visits() {
    var tbl_visits = new DataTable("#table_visits", {
        ajax: {
            url: RUTA_URL + "Request/getVisits/",
            dataSrc: "data",
        },
        columns: [
            { title: "Tipo de visita", data: "str_type_of_visit" },
            { title: "Nombre visitante", data: "str_fullname" },
            { title: "Hora y fecha", data: "start_date" },
            { title: "Estatus", data: "id_status" },
            { title: "Proyecto", data: "project_folio" },
            { title: "Acción", data: "btn_action" },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).attr("data-id", data.id);
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    var obj_map = createMap_id("#mdl_info_visit .map", opcionesMapa);

    // Configurar el modal
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                $("#mdl_crud_visit .modal-header .modal-title").html("AGREGAR VISITA");
                $("#mdl_crud_visit .modal-body form [name='add']").show();
                $("#mdl_crud_visit .modal-body form [name='update']").hide();
                $("#mdl_crud_visit").modal("show");
                break;
            case "update":
                $("#mdl_crud_visit .modal-header .modal-title").html("ACTUALIZAR VISITA");
                $("#mdl_crud_visit .modal-body form [name='add']").hide();
                $("#mdl_crud_visit .modal-body form [name='update']").show();
                $("#mdl_crud_visit").modal("show");
                break;
            case "show_info":
                $("#mdl_info_visit .modal-header .modal-title").html(
                    "INFORMACIÓN DE LA VISITA"
                );
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_info_visit form [name='id']").val(data["id"]);
                $("#mdl_info_visit form [name='lat']").val(parseFloat(data["lat"]));
                $("#mdl_info_visit form [name='lng']").val(parseFloat(data["lon"]));
                $("#mdl_info_visit form [name='type']").val(data["str_type_of_visit"]);
                $("#mdl_info_visit form [name='project']").val(data["id_project"]);
                $("#mdl_info_visit form [name='visit']").val(data["str_fullname"]);
                $("#mdl_info_visit form [name='razon']").val(data["description"]);

                // Mapa
                if (data["lat"] != null) {
                    update_map_location(
                        obj_map,
                        parseFloat(data["lat"]),
                        parseFloat(data["lon"])
                    );
                    obj_map.marcador.setVisible(true);
                } else {
                    obj_map.marcador.setVisible(false);
                }
                // mostrar le modal
                $("#mdl_info_visit").modal("show");
                break;
            case "refresh_table":
                tbl_visits.ajax.reload();
                break;
            default:
                alert("Opcion no valida (tabla visistas)");
        }
        // end option
    });

    $("#mdl_update_visit form").on("submit", function (e) {
        e.preventDefault();
        var datos = $(this).serialize();
        var url = RUTA_URL + "Request/updateVisit/";

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            beforeSend: function () {
                $("#mdl_update_visit form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                $("#mdl_update_visit").modal("hide");
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", "fallo algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log("hubo un error: ", errorThrow);
                Swal.fire("Error del servidor", ":(", "error");
            },
            complete: function () {
                $("#mdl_update_visit form [type='submit']").prop("disabled", false);
            },
        });
    });
    // End
}
// TODO ------------------------- [ PROYECTOS ] -------------------------
function ftable_projects() {
    var tbl_proyects = new DataTable("#table_proyects", {
        ajax: {
            url: RUTA_URL + "Request/getProjects/",
            dataSrc: "data",
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Proyecto", data: "btn_folio" },
            { title: "Cliente", data: "id_client" },
            { title: "Avance (%)", data: "percentage" },
            { title: "Documentación", data: "btn_docs" },
            { title: "Visitas", data: "btn_action_visit" },
            { title: "Editar", data: "btn_action" },
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    var obj_map = createMap_id("#mdl_crud_proyect .map", opcionesMapa);

    // Configurar el modal
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                $("#mdl_crud_proyect form")[0].reset();
                $("#mdl_crud_proyect form :input").removeClass("is-invalid is-valid");
                $("#mdl_crud_proyect .modal-header .modal-title").html(
                    "AGREGAR PROYECTO"
                );
                $("#mdl_crud_proyect .modal-body form [name='add']").show();
                $("#mdl_crud_proyect .modal-body form [name='update']").hide();
                $("#mdl_crud_proyect .col-existing-file").addClass("d-none");
                $("#mdl_crud_proyect #pills-address small.address").html("");
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update":
                $("#mdl_crud_proyect #pills-address small.address").html("");
                $("#mdl_crud_proyect form :input").removeClass("is-invalid is-valid");
                $("#mdl_crud_proyect .modal-header .modal-title").html(
                    "ACTUALIZAR PROYECTO"
                );
                $("#mdl_crud_proyect .modal-body form [name='add']").hide();
                $("#mdl_crud_proyect .modal-body form [name='update']").show();
                // Asignar valores
                var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                var formulario = $("#mdl_crud_proyect .modal-body form");
                $.each(data, function (index, value) {
                    if (index != "quotation") {
                        formulario.find(`[name="${index}"]`).val(value);
                    }
                });
                formulario.find("[name='lng']").val(data["lon"]);
                formulario
                    .find("[name='start_date']")
                    .val(data["start_date"].split(" ")[0]);
                formulario
                    .find("[name='existing_file']")
                    .attr(
                        "onclick",
                        `open_window('${RUTA_URL}File/getProjectFile/${data["id"]}/${data["quotation"]}')`
                    );

                if (data["quotation"] !== "") {
                    $(".col-existing-file").removeClass("d-none");
                } else {
                    $(".col-existing-file").addClass("d-none");
                }

                // Actualizar las cordenas
                if (data["lat"] != null) {
                    update_map_location(
                        obj_map,
                        parseFloat(data["lat"]),
                        parseFloat(data["lon"])
                    );
                    obj_map.marcador.setVisible(true);
                }
                // Mostrar modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update_project":
                $("#mdl_update_proyect .modal-header .modal-title").html(
                    "ACTUALIZAR PROYECTO"
                );
                $("#mdl_update_proyect .modal-body form [name='add']").hide();
                $("#mdl_update_proyect .modal-body form [name='update']").show();
                var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                $("#mdl_update_proyect form [name='id']").val(data["id"]);
                $("#mdl_update_proyect form [name='tb_project']").val(
                    fila.find("td:eq(1)").text()
                );
                $("#mdl_update_proyect form [name='id_client']").val(
                    fila.find("td:eq(2)").text()
                );
                $("#mdl_update_proyect form [name = 'folio']").val(data["folio"]);
                $("#mdl_update_proyect form [name='quotation']").val(data["quotations"]);
                $("#mdl_update_proyect form [name = 'quotation_num']").val(
                    data["quotations_num"]
                );
                $("#mdl_update_proyect form [name = 'id_fide']").val(data["id_fide"]);
                $("#mdl_update_proyect form [name='tb_project']").val(data["tb_project"]);
                $("#mdl_update_proyect form [name='charge']").val(data["charge"]);
                $("#mdl_update_proyect form [name = 'street']").val(data["street"]);
                $("#mdl_update_proyect form [name='cologne']").val(data["cologne"]);
                $("#mdl_update_proyect form [name = 'municipality']").val(
                    data["municipality"]
                );
                $("#mdl_update_proyect form [name = 'address']").val(data["address"]);
                $("#mdl_update_proyect form [name='start_date']").val(data["start_date"]);
                $("#mdl_update_proyect").modal("show");
                break;
            case "show_visits":
                $("#mdl_crud_visit .modal-header .modal-title").html("AGREGAR VISITA");
                $("#mdl_crud_visit .modal-body form [name='add']").show();
                $("#mdl_crud_visit .modal-body form [name='update']").hide();
                $("#mdl_crud_visit").modal("show");
                break;
            case "add_visit":
                var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                $("#mdl_crud_visit form [name='project']").val(data["id"]);
                $("#mdl_crud_visit").modal("show");
                break;
            case "refresh_table":
                tbl_proyects.ajax.reload();
                break;
            default:
                alert("Opcion no valida");
        }
        // end option
    });

    // Create and update proyect
    $("#mdl_crud_proyect form").on("submit", function (e) {
        e.preventDefault();
        $("#mdl_crud_proyect form :input").removeClass("is-invalid is-valid");

        // Validar que los inputs que tenga contenido
        $("#mdl_crud_proyect form :input").each(function () {
            var isEmpty =
                $(this).val() === "" ||
                $(this).val() === null ||
                $(this).val() === undefined;
            $(this).toggleClass("is-invalid", isEmpty).toggleClass("is-valid", !isEmpty);
        });
        // inputs opcionales marcar si o si como validos
        var form = $("#mdl_crud_proyect form");
        form.find("[name='id_fide']").removeClass("is-invalid").addClass("is-valid");
        form.find("[name='folio']").removeClass("is-invalid").addClass("is-valid");
        form.find("[name='quotation']").removeClass("is-invalid").addClass("is-valid");
        $("#mdl_crud_proyect button").removeClass("is-invalid is-valid");
        // Validar
        if ($("#mdl_crud_proyect form .is-invalid").length > 0) {
            Swal.fire("Oops", "Te hace falta rellenar campos", "warning");
            return;
        }

        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = new FormData($("#mdl_crud_proyect form")[0]);
        var url =
            RUTA_URL + "Request/" + (option == "add" ? "addProyect" : "updateProject");
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
                console.log(response);
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                    tbl_proyects.ajax.reload();
                    $("#mdl_crud_proyect").modal("hide");
                } else {
                    Swal.fire("Oops", "fallo algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.error(errorThrow);
                Swal.fire("Oops", "Error del servidor", "error");
            },
            complete: function () {
                $("#mdl_crud_proyect form [type='submit']").prop("disabled", false);
            },
        });
    });

    $("#mdl_update_proyect form").on("submit", function (e) {
        e.preventDefault();
        var datos = $(this).serialize();
        var url = RUTA_URL + "Request/updateProyect/";

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            beforeSend: function () {
                $("#mdl_update_proyect form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                $("#mdl_update_proyect").modal("hide");
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", "fallo algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log("hubo un error: ", errorThrow);
                Swal.fire("Error del servidor", ":(", "error");
            },
            complete: function () {
                $("#mdl_update_proyect form [type='submit']").prop("disabled", false);
            },
        });
    });

    $("#mdl_crud_proyect form [name='state']").on("change", function () {
        // var input_municipality = $("#mdl_crud_proyect form [name='municipality']");
        var option = $(this).find("option:selected");
        var estado = option.attr("data-state");
        var formulario = $("#mdl_crud_proyect .modal-body form");
        var street = formulario.find("[name='street']").val();
        var colony = formulario.find("[name='colony']").val();
        var municipality = formulario.find("[name='municipality']").val();
        var address = "";
        address = street != "" ? `${street},` : "";
        address = colony != "" ? `${address} col. ${colony},` : "";
        address = municipality != "" ? `${address} ${municipality},` : "";
        address = estado != "" ? `${address} ${estado}` : "";
        $("#pills-address small.address").html(address);
        address_on_the_map(obj_map, address);
    });

    // End project
}

$("form.form-project-stages").on("submit", function (e) {
    e.preventDefault();
    var formulario = $(this);
    var datos = new FormData($(this)[0]);
    datos.append("id", 2);
    datos.append("folio", "ulices-kun");
    datos.append("category", "contado");
    datos.append("stage", 1);

    $.ajax({
        type: "POST",
        url: RUTA_URL + "Request/updateStageData/",
        data: datos,
        contentType: false,
        processData: false,
        beforeSend: function () {
            formulario.find(":input").prop("disabled", true);
        },
        success: function (response) {
            // const id = response["data"]["id"];
            // const fileName = response["data"]["fileName"];
            formulario.find(":input").prop("disabled", false);
            // formulario
            //     .closest(".accordion-item")
            //     .find(".icon-checkbox")
            //     .addClass("checked");
            // formulario.closest(".accordion-item").find(".col-download").show();
            // formulario
            //     .closest(".accordion-item")
            //     .find("button.download")
            //     .attr(
            //         "onclick",
            //         `open_window('${RUTA_URL}File/getProjectFile/${id}/${fileName}')`
            //     );
            // Swal.fire("Good job!", "Accion exitosa", "success");
        },
        error: function (error) {},
        complete: function () {
            formulario.find(":input").prop("disabled", false);
        },
    });
});

// TODO ------------------------- [ Eventos Globales ] -------------------------

$("#mdl_crud_visit form").on("submit", function (e) {
    e.preventDefault();
    var datos = $(this).serialize();
    var option = $('button[type="submit"]:focus', this).attr("name");
    var url = RUTA_URL + "Request/" + (option == "add" ? "addVisit" : "updateVisit");

    $.ajax({
        type: "POST",
        url: url,
        data: datos,
        beforeSend: function () {},
        success: function (response) {
            if (response.success) {
                Swal.fire("Good job!", "Accion exitosa", "success");
                $("#mdl_crud_visit").modal("hide");
            } else {
                Swal.fire("Oops", "fallo algo", "error");
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.error(errorThrow);
            Swal.fire("Oops", "Error del servidor", "error");
        },
        complete: function () {},
    });
});

$("form[name='reset-password']").on("submit", function (e) {
    e.preventDefault();
    var datos = $(this).serialize();
    var btn_submit = $("form[name='reset-password'] [type='submit']");
    $.ajax({
        type: "POST",
        url: RUTA_URL + "Request/request_password_change/",
        data: datos,
        beforeSend: function () {
            btn_submit.prop("disabled", true);
            btn_submit.html(
                '<i class="fa-regular fa-loader fa-spin me-2"></i> Procesando'
            );
        },
        success: function (response) {
            btn_submit.prop("disabled", false);
            btn_submit.html("Reset Password");
            if (response.success) {
                Swal.fire("Good job!", "Accion exitosa", "success");
            } else {
                Swal.fire("Oops", "fallo algo", "error");
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.error(errorThrow);
            Swal.fire("Oops", "Error del servidor", "error");
        },
        complete: function () {},
    });
});

// TODO ------------------------- [ Funciones Globales ] -------------------------
function createMap_id(id_map, opcionesMapa) {
    var mapa = new google.maps.Map(document.querySelector(id_map), opcionesMapa);
    var marcador = new google.maps.Marker({
        position: opcionesMapa.center,
        map: mapa,
        title: "Ubicación",
        draggable: true,
    });
    var $formulario = $(id_map).closest("form");
    var $input_lat = $formulario.find('input[name="lat"]');
    var $input_lng = $formulario.find('input[name="lng"]');

    $input_lat.val(opcionesMapa.center.lat);
    $input_lng.val(opcionesMapa.center.lng);

    const locationButton = document.createElement("button");
    locationButton.setAttribute("type", "button");
    locationButton.setAttribute("name", "btn-location");
    locationButton.textContent = "Ubicarme";
    locationButton.classList.add("btn", "btn-danger", "mt-3");
    mapa.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
    locationButton.addEventListener("click", function () {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                var nuevaPosicion = new google.maps.LatLng(
                    position.coords.latitude,
                    position.coords.longitude
                );
                marcador.setPosition(nuevaPosicion);
                mapa.setCenter(nuevaPosicion);
                // actualizar los inputs
                $input_lat.val(position.coords.latitude);
                $input_lng.val(position.coords.longitude);
            },
            function () {
                console.error("Error al obtener la ubicación.");
            }
        );
    });

    // Evento que se dispara al mover el marcador
    google.maps.event.addListener(marcador, "dragend", function () {
        var nuevaPosicion = marcador.getPosition();
        $input_lat.val(nuevaPosicion.lat());
        $input_lng.val(nuevaPosicion.lng());
    });

    return { mapa: mapa, marcador: marcador };
}

function deleteMap_id(id_map) {}

function update_map_location(_obj_map, _lat, _lng, _zoom = 13) {
    var nuevaPosicion = new google.maps.LatLng(_lat, _lng);
    _obj_map.marcador.setPosition(nuevaPosicion);
    _obj_map.mapa.setCenter(nuevaPosicion);
    _obj_map.mapa.setZoom(_zoom);
    _obj_map.mapa.panTo(nuevaPosicion);
}

function address_on_the_map(_obj_map, _address = "Queretaro") {
    let $formulario = $(".map").closest("form");
    let $input_lat = $formulario.find('input[name="lat"]');
    let $input_lng = $formulario.find('input[name="lng"]');
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: _address }, function (results, status) {
        if (status === "OK" && results.length > 0) {
            var ubicacion = results[0].geometry.location;
            // asignar a los inputs
            $input_lat.val(ubicacion.lat());
            $input_lng.val(ubicacion.lng());
            // Centrar el mapa en las coordenadas obtenidas
            _obj_map.marcador.setPosition(ubicacion);
            _obj_map.mapa.setCenter(ubicacion);
            _obj_map.mapa.panTo(ubicacion);
        } else {
            console.error("Error al geocodificar el lugar:", status);
        }
    });
}

function open_window($url = "") {
    var ventana = window.open($url, "_blank");
}
