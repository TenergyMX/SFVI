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
            { title: "Editar", data: "btn_update" },
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
                $("#mdl_crud_user .modal-header .modal-title").html("AGREGAR USUARIO");
                var formulario = $("#mdl_crud_user .modal-body form");
                formulario.find("[name='add']").show();
                formulario.find("[name='update']").hide();
                formulario.find(".password").show();
                formulario.find("[type='password']").prop("disabled", false);
                formulario.find(".user-resetPassword").hide();
                formulario[0].reset();
                $("#mdl_crud_user").modal("show");
                break;
            case "update":
                $("#mdl_crud_user .modal-header .modal-title").html("ACTUALIZAR USUARIO");
                var formulario = $("#mdl_crud_user .modal-body form");
                formulario.find("[name='add']").hide();
                formulario.find("[name='update']").show();
                formulario.find(".password").hide();
                formulario.find("[type='password']").prop("disabled", true);
                formulario.find(".user-resetPassword").show();
                // Obtener la información de la fila
                var fila = $(this).closest("tr");
                var data = tbl_users.row(fila).data();
                // cargar la información
                formulario.find("[name='id']").val(data["id"]);
                formulario.find("[name='email']").val(data["email"]);
                formulario.find("[name='role']").val(data["role"]);
                formulario.find("[name='name']").val(data["name"]);
                formulario.find("[name='surnames']").val(data["surnames"]);
                formulario
                    .find(".user-resetPassword button")
                    .attr("onclick", `resetPassword('${data["email"]}')`);
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
        select_clients.html('<option value="" disabled selected>Selecciona el cliente</option>');

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
                    let client = parseInt($('[name="name-clients"] option:selected').val());

                    var datos_client = response.data[client];
                    $("#mdl_crud_user .modal-body form [name='name']").val(datos_client["name"]);
                    $("#mdl_crud_user .modal-body form [name='surnames']").val(datos_client["surnames"]);
                    $("#mdl_crud_user .modal-body form [name='email']").val(datos_client["email"]);
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
                console.log(errorThrow["message"]);
                Swal.fire("Error del servidor", errorThrow["message"], "error");
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
            { title: "Editar", data: "btn_update" },
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
                $("#mdl_crud_client .modal-header .modal-title").html("ACTUALIZAR CLIENTE");
                $("#mdl_crud_client .modal-body form [name='add']").hide();
                $("#mdl_crud_client .modal-body form [name='update']").show();
                $("#mdl_crud_client").modal("show");
                var fila = $(this).closest("tr");
                var data = tbl_clients.row(fila).data();
                $("#mdl_crud_client form [name='type']").val(data["type_of_client"]);
                $("#mdl_crud_client form [name='name']").val(data["name"]);
                $("#mdl_crud_client form [name='surnames']").val(data["surnames"]);
                $("#mdl_crud_client form [name='state']").val(data["state"]);
                $("#mdl_crud_client form [name='municipality']").val(data["municipality"]);
                $("#mdl_crud_client form [name='type']").prop("readonly", true);
                $("#mdl_crud_client form [name='email']").val(data["email"]);
                $("#mdl_crud_client form [name='phone']").val(data["phone"]);
                $("#mdl_crud_client form [name='rfc']").val(data["rfc"]);
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
        var url = RUTA_URL + "Request/" + (option == "add" ? "addClient" : "updateClient");
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
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_crud_visit form [name='id_type']").val(data["id_type"]);
                $("#mdl_crud_visit form [name='project']").val(data["id_project"]);
                $("#mdl_crud_visit form [name='id_user']").val(data["id_user"]);
                $("#mdl_crud_visit form [name='description']").val(data["description"]);
                $("#mdl_crud_visit form [name='start_date']").val(data["start_date"]);
                break;

            case "show_info":
                $("#mdl_info_visit .modal-header .modal-title").html("INFORMACIÓN DE LA VISITA");
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_info_visit form [name='id']").val(data["id"]);
                $("#mdl_info_visit form [name='lat']").val(parseFloat(data["lat"]));
                $("#mdl_info_visit form [name='lng']").val(parseFloat(data["lon"]));
                $("#mdl_info_visit form [name='type']").val(data["str_type_of_visit"]);
                $("#mdl_info_visit form [name='proyect']").val(data["project_folio"]);
                $("#mdl_info_visit form [name='date']").val(data["start_date"]);
                $("#mdl_info_visit form [name='visit']").val(data["str_fullname"]);
                $("#mdl_info_visit form [name='razon']").val(data["description"]);

                // Mapa
                if (data["lat"] != null) {
                    update_map_location(obj_map, parseFloat(data["lat"]), parseFloat(data["lon"]));
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
            /* case "update":
                $("#mdl_update_visit .modal-header .modal-title").html("ACTUALIZAR ITA");
                $("#mdl_update_visit .modal-body form [name='add']").hide();
                $("#mdl_update_visit .modal-body form [name='update']").show();
                $("#mdl_update_visit").modal("show");
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_update_visit form [name='tipo']").val(data["id_type"]);
                $("#mdl_update_visit form [name='proyect']").val(data["project_folio"]);
                $("#mdl_update_visit form [name='visit']").val(data["str_fullname"]);
                $("#mdl_update_visit form [name='razon']").val(data["description"]);
                $("#mdl_update_visit form [name='razon']").val(data["start_date"]);
                break; */
            case "close":
                $("#mdl_info_visit").modal("hide");
                cahngeStatus();
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

    $("#mdl_info_visit form").on("submit", function (e) {
        e.preventDefault();
        var datos = $(this).serialize();
        var url = RUTA_URL + "Request/updateStatusVisit/";

        $.ajax({
            type: "POST",
            url: url,
            data: { id: id, nuevoEstado: "cerrado" },
            success: function (response) {
                $("#mdl_info_visit").modal("hide");
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
            { title: "Proyecto", data: "btn_project_code" },
            { title: "Cliente", data: "customer_fullName" },
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
                $("#mdl_crud_proyect .modal-header .modal-title").html("AGREGAR PROYECTO");
                $("#mdl_crud_proyect .modal-body form [name='add']").show();
                $("#mdl_crud_proyect .modal-body form [name='update']").hide();
                $("#mdl_crud_proyect .col-existing-file").addClass("d-none");
                $("#mdl_crud_proyect #pills-address small.address").html("");
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update":
                $("#mdl_crud_proyect #pills-address small.address").html("");
                $("#mdl_crud_proyect form :input").removeClass("is-invalid is-valid");
                $("#mdl_crud_proyect .modal-header .modal-title").html("ACTUALIZAR PROYECTO");
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
                if (data["start_date"] != null) {
                    console.log(data["start_date"]);
                    formulario.find("[name='start_date']").val(data["start_date"].split(" ")[0]);
                }
                formulario.find("[name='existing_file']").attr("onclick", `open_window('${RUTA_URL}')`);
                if (data["quotation"] !== null) {
                    $(".col-existing-file").removeClass("d-none");
                } else {
                    $(".col-existing-file").addClass("d-none");
                }

                // Actualizar las cordenas
                if (data["lat"] != null) {
                    update_map_location(obj_map, parseFloat(data["lat"]), parseFloat(data["lon"]));
                    obj_map.marcador.setVisible(true);
                }
                // Mostrar modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update_project":
                $("#mdl_update_proyect .modal-header .modal-title").html("ACTUALIZAR PROYECTO");
                $("#mdl_update_proyect .modal-body form [name='add']").hide();
                $("#mdl_update_proyect .modal-body form [name='update']").show();
                var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                $("#mdl_update_proyect form [name='id']").val(data["id"]);
                $("#mdl_update_proyect form [name='tb_project']").val(fila.find("td:eq(1)").text());
                $("#mdl_update_proyect form [name='id_client']").val(fila.find("td:eq(2)").text());
                $("#mdl_update_proyect form [name = 'folio']").val(data["folio"]);
                $("#mdl_update_proyect form [name='quotation']").val(data["quotations"]);
                $("#mdl_update_proyect form [name = 'quotation_num']").val(data["quotations_num"]);
                $("#mdl_update_proyect form [name = 'id_fide']").val(data["id_fide"]);
                $("#mdl_update_proyect form [name='tb_project']").val(data["tb_project"]);
                $("#mdl_update_proyect form [name='charge']").val(data["charge"]);
                $("#mdl_update_proyect form [name = 'street']").val(data["street"]);
                $("#mdl_update_proyect form [name='cologne']").val(data["cologne"]);
                $("#mdl_update_proyect form [name = 'municipality']").val(data["municipality"]);
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
            case "info_stages":
                $("#mdl_info_stages .modal-header .modal-title").html("INFORMACIÓN DEL PROYECTO");
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                $("#mdl_info_stages").modal("show");

            /* var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                $("#mdl_info_stages form [name='id']").val(data["id"]); */
            /* $("#mdl_info_visit form [name='tipo']").val("id_type"); */
            /*  $("#mdl_info_stages form [name='folio']").val(
                    fila.find("td:eq(1)").text()
                );
                $("#mdl_info_stages form [name = 'id_client']").val(
                    fila.find("td:eq(2)").text()
                );
                $("#mdl_info_stages").modal("show");

                break; */
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
            var isEmpty = $(this).val() === "" || $(this).val() === null || $(this).val() === undefined;
            $(this).toggleClass("is-invalid", isEmpty).toggleClass("is-valid", !isEmpty);
        });
        // inputs opcionales marcar si o si como validos
        var form = $("#mdl_crud_proyect form");
        form.find("[name='id_fide']").removeClass("is-invalid").addClass("is-valid");
        form.find("[name='tb']").removeClass("is-invalid").addClass("is-valid");
        form.find("[name='quotation']").removeClass("is-invalid").addClass("is-valid");
        $("#mdl_crud_proyect button").removeClass("is-invalid is-valid");
        // Validar
        if ($("#mdl_crud_proyect form .is-invalid").length > 0) {
            Swal.fire("Oops", "Te hace falta rellenar campos", "warning");
            return;
        }

        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = new FormData($("#mdl_crud_proyect form")[0]);
        var url = RUTA_URL + "Request/" + (option == "add" ? "addProyect" : "updateProject");
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
                    Swal.fire("Oops", response.error.message, "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.error(errorThrow);
                Swal.fire("Oops", "Error del servidor", errorThrow);
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
                    Swal.fire("Oops", "fallo algo", response.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                Swal.fire("Error del servidor", ":(", errorThrow);
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
    var formulario_mdl = $("#mdl_info_stages form");
    var datos = new FormData($(this)[0]);
    datos.append("id", formulario_mdl.find("[name='id']").val());
    datos.append("name_project", formulario_mdl.find("[name='project_name']").val());
    datos.append("category", formulario_mdl.find("[name='str_category']").val());

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
            const id_project = response["data"]["project"]["id"];
            const path = response["data"]["file"]["path"];
            formulario.find(":input").prop("disabled", false);
            formulario.closest(".accordion-item").find(".icon-checkbox").addClass("checked");
            formulario.closest(".accordion-item").find(".col-download").show();
            formulario
                .closest(".accordion-item")
                .find("button.download")
                .attr("data-id-project", id_project);
            formulario.closest(".accordion-item").find("button.download").attr("data-path", path);
            Swal.fire("Good job!", "Accion exitosa", "success");
            var r = calcularProgresoEtapa();
        },
        error: function (error) {},
        complete: function () {
            formulario.find(":input").prop("disabled", false);
        },
    });
});

// Mostrar la información del proyecto en la etapas
$(".btn-info-project").on("click", function () {
    $("#mdl_info_stages").modal("show");
});

$("button.download").on("click", function () {
    var id_project = $(this).attr("data-id-project");
    var path = $(this).attr("data-path");
    open_window(`${RUTA_URL}File/get_project_file/${id_project}?path=${path}`);
});

// cambiar de etapa
function project_stage_transition(tab = "") {
    $(".step-wizard-item").removeClass("current-item");
    $(tab).closest(".step-wizard-item").addClass("current-item");
    $(tab).tab("show");
}

function calcularProgresoEtapa() {
    $(".container-progress-bar .progress").css("width", "0%");
    $(".container-progress-bar .progress-bar").css("width", "100%");
    const category = $("[name='str_category']").val().toLowerCase();
    var progresoGlobal = 0;
    var etapas = $(".tab-stage");
    var resultado = {
        success: true,
        data: [],
        general: {
            completado: 0,
            a_completar: 0,
            progreso: 0,
        },
        current_stage: null,
    };

    if (etapas.length == 0) {
        return;
    }

    for (let indiceEtapa = 0; indiceEtapa < etapas.length; indiceEtapa++) {
        let etapaActual = etapas.eq(indiceEtapa);
        let checkboxes = etapaActual.find(".icon-checkbox");
        let totalCheckboxes = checkboxes.length;
        let completados = checkboxes.filter(".checked").length;

        let porcentajeCompletado = totalCheckboxes > 0 ? (completados / totalCheckboxes) * 100 : 0;

        resultado.data[indiceEtapa] = {
            stage: indiceEtapa + 1,
            progress: porcentajeCompletado,
        };

        if (completados > 0) {
            resultado.ultimaEtapa = indiceEtapa + 1;
            progresoGlobal += porcentajeCompletado;
            resultado.general["completado"] = progresoGlobal;
        }

        if (porcentajeCompletado == 100) {
            resultado.ultimaEtapa = resultado.ultimaEtapa + 1;
        }
    }

    resultado.general["a_completar"] = etapas.length * 100;
    resultado.general["progreso"] =
        (resultado.general["completado"] / resultado.general["a_completar"]) * 100;

    // estilos
    $(".tab-stage .stage-header button").hide();
    for (let i = 0; i < resultado.ultimaEtapa; i++) {
        $(".tab-stage").eq(i).find(".stage-header button").show();
    }

    $(".tab-stage")
        .eq(resultado.ultimaEtapa - 1)
        .find(".btn-next")
        .hide();

    // Correguir en caso de existir
    console.log(resultado.ultimaEtapa);

    if (resultado.ultimaEtapa > etapas.length) {
        resultado.ultimaEtapa = etapas.length;
    }

    // Barra de progreso
    if (resultado.general["completado"] >= 500) {
        let menos = (etapas.length - 1) * 100;
        menos = resultado.general["completado"] - menos;
        let completado = resultado.general["completado"] - menos;
        $(".container-progress-bar .progress").css("width", completado + "%");
    } else {
        let completado = resultado.general["completado"];
        $(".container-progress-bar .progress").css("width", completado + "%");
    }

    if (resultado.ultimaEtapa > 1) {
        setTimeout(function () {
            project_stage_transition(`#pills-${category}-etapa-${resultado.ultimaEtapa}-tab`);
            console.log(`#pills-${category}-etapa-${resultado.ultimaEtapa}-tab`);
            if (resultado.ultimaEtapa == etapas.length && resultado.general["progreso"] == 100) {
                setTimeout(function () {
                    $(".step-wizard-item").removeClass("current-item");
                }, 100);
            }
        }, 505);
        console.log("si mijo");
    } else {
        console.log("akjbsa");
    }
    let mdl_progress_bar = $("#mdl_info_stages .progress .progress-bar");
    mdl_progress_bar.css("width", resultado.general["progreso"] + "%");
    mdl_progress_bar.html(resultado.general["progreso"].toFixed(2) + "%");
    mdl_progress_bar.attr("title", resultado.general["progreso"].toFixed(2) + "%");
    $("span.progreso").html(`[${resultado.general["progreso"].toFixed(2)}%]`);
    console.log(resultado);
    return resultado;
}

// TODO ------------------------- [ Eventos Globales ] -------------------------

// Envia un link al correo del usuario para cambiar su contraseña
function resetPassword(_email = "") {
    Swal.fire({
        title: "Procesando...",
        html: "Espere un momento por favor, mientras procesamos tu peticion",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        type: "POST",
        url: RUTA_URL + "Request/request_password_change/",
        data: {
            email: _email,
        },
        success: function (response) {
            Swal.close();
            Swal.fire({
                icon: "success",
                title: "Exito",
                text: "Revisa tu correo ;)",
                timer: 3000, // Tiempo en milisegundos (en este caso, 2 segundos)
                timerProgressBar: true, // Barra de progreso que muestra el tiempo restante
            });
        },
        error: function (jqXHR, textStatus, errorThrow) {
            Swal.close();
            Swal.fire("Oops", "fallo algo", "error");
        },
        complete: function () {},
    });
}

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
            btn_submit.html('<i class="fa-regular fa-loader fa-spin me-2"></i> Procesando');
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
