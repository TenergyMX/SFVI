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
                console.log(response);
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", response["error"]["message"], "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log(errorThrow);
                Swal.fire("Error del servidor", errorThrow["message"], "error");
            },
            complete: function () {
                $("#mdl_crud_user form [type='submit']").prop("disabled", false);
            },
        });
    });
    // End
}

$("form[name='form-profile']").on("submit", function (e) {
    e.preventDefault();
    var datos = $(this).serialize();
    var url = RUTA_URL + "Request/" + "updateUser/";
    $.ajax({
        type: "POST",
        url: url,
        data: datos,
        success: function (response) {
            console.log(response);
            if (response.success) {
                Swal.fire("Good job!", "Accion exitosa", "success");
            } else {
                Swal.fire("Oops", response.error["message"], "error");
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.log(errorThrow);
            Swal.fire("Error del servidor", errorThrow["message"], "error");
        },
    });
});

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

                $("#mdl_crud_client form [name='id']").val(data["id"]);
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

    // Create and update
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
                $("#mdl_crud_client form [type='submit']").prop("disabled", false);
                tbl_clients.ajax.reload();
                $("#mdl_crud_client").modal("hide");
                if (response.success) {
                    Swal.fire("", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", response["error"]["message"], "error");
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
            { title: "Estatus", data: "str_status_of_visit" },
            { title: "Proyecto", data: "project_name" },
            { title: "Info. visit", data: "btn_action" },
            { title: "Editar", data: "btn_update" },
        ],
        columnDefs: [
            {
                targets: 2, // La columna "Hora y fecha" está en la posición 2
                render: function (data, type, row, meta) {
                    // Formatear la fecha a d/m/a h:m:s
                    var fecha = new Date(data);
                    var dia = ("0" + fecha.getDate()).slice(-2);
                    var mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
                    var anio = fecha.getFullYear();
                    var horas = ("0" + fecha.getHours()).slice(-2);
                    var minutos = ("0" + fecha.getMinutes()).slice(-2);
                    var segundos = ("0" + fecha.getSeconds()).slice(-2);
                    return dia + "/" + mes + "/" + anio + " " + horas + ":" + minutos + ":" + segundos;
                },
            },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).attr("data-id", data.id);
            var cell = $(row).children("td:eq(3)");
            // Añadir una clase CSS según el estatus
            switch (data.str_status_of_visit) {
                case "Nueva":
                    cell.css("color", "#F3AA21");
                    cell.css("font-weight", "bold");
                    break;
                case "En proceso":
                    cell.css("color", "#0090CB");
                    cell.css("font-weight", "bold");
                    break;
                case "Finalizada":
                    cell.css("color", "#026B00");
                    cell.css("font-weight", "bold");
                    break;
                case "Atrasada":
                    cell.css("color", "#B51300");
                    cell.css("font-weight", "bold");
                    break;
                case "Reagendada":
                    cell.css("color", "#6300B6");
                    cell.css("font-weight", "bold");
                    break;
                default:
                    cell.css("color", "black");
                    cell.css("background-color", "white");
            }
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
                $("#mdl_crud_visit .modal-body form [name='note']").hide();
                $("#mdl_crud_visit").modal("show");
                break;
            case "update":
                $("#mdl_crud_visit .modal-header .modal-title").html("ACTUALIZAR VISITA");
                $("#mdl_crud_visit .modal-body form [name='add']").hide();
                $("#mdl_crud_visit .modal-body form [name='update']").show();
                $("#mdl_crud_visit").modal("show");
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_crud_visit form [name='id']").val(data["id"]);
                $("#mdl_crud_visit form [name='id_type']").val(data["id_type"]);
                $("#mdl_crud_visit form [name='project']").val(data["id_project"]);
                $("#mdl_crud_visit form [name='id_user']").val(data["id_user"]);
                $("#mdl_crud_visit form [name='description']").val(data["description"]);
                $("#mdl_crud_visit form [name='start_date']").val(data["start_date"]);
                $("#mdl_crud_visit form [name='start_date_old']").val(data["start_date"]);

                if (data["id_status"] == 3) {
                    $("#mdl_crud_visit [name='update']").prop("disabled", true);
                } else {
                    $("#mdl_crud_visit [name='update']").prop("disabled", false);
                }
                break;
            case "show_info":
                $("#mdl_info_visit .modal-header .modal-title").html("INFORMACIÓN DE LA VISITA");
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_info_visit form [name='id']").val(data["id"]);
                $("#mdl_info_visit form [name='lat']").val(parseFloat(data["lat"]));
                $("#mdl_info_visit form [name='lng']").val(parseFloat(data["lon"]));
                $("#mdl_info_visit form [name='type']").val(data["str_type_of_visit"]);
                $("#mdl_info_visit form [name='razon']").val(data["description"]);
                $("#mdl_info_visit form [name='proyect']").val(data["project_folio"]);
                $("#mdl_info_visit form [name='date']").val(data["start_date"]);
                $("#mdl_info_visit form [name='visit']").val(data["str_fullname"]);
                $("#mdl_info_visit form [name='note']").val(data["note"]);

                if (data["id_status"] == 3) {
                    $("#mdl_info_visit [name='close']").prop("disabled", true);
                } else {
                    $("#mdl_info_visit [name='close']").prop("disabled", false);
                }

                // Mapa
                if (data["lat"] != null) {
                    update_map_location(obj_map, parseFloat(data["lat"]), parseFloat(data["lon"]));
                    obj_map.marcador.setVisible(true);
                } else {
                    obj_map.marcador.setVisible(false);
                }

                // cargar ruta para el PDF
                $("#mdl_info_visit form a[name='pdf']").attr(
                    "href",
                    `${RUTA_URL}Visit/generatePdf/${data["id"]}/`
                );

                // mostrar le modal
                $("#mdl_info_visit").modal("show");
                break;
            case "refresh_table":
                tbl_visits.ajax.reload();
                break;
            case "close":
                $("#mdl_info_visit").modal("hide"); // Cerrar el modal primero

                var datos = $("#mdl_info_visit form").serialize();
                var url = RUTA_URL + "Request/updateStatusVisit/";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: datos,

                    success: function (response) {
                        if (response.success) {
                            Swal.fire("Visita actualizada!", "", "success");
                            tbl_visits.ajax.reload();
                        } else {
                            Swal.fire("Oops", "Fallo algo en la actualización", "error");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        console.log("Hubo un error: ", errorThrow);
                        Swal.fire("Error del servidor", ":(", "error");
                    },
                });
                break;
            case "pdf":
                $("#mdl_info_visit").modal("hide"); // Cerrar el modal primero
                console.log("Click en el botón PDF");
                break;
            default:
                alert("Opcion no valida (tbl_visits)");
        }
        // end option
    });

    // End
}

// TODO ------------------------- [ PROYECTOS ] -------------------------
function ftable_projects() {
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
                var data = tbl_projects.row(fila).data();
                var formulario = $("#mdl_crud_proyect .modal-body form");
                formulario.find(".col-existing-file").addClass("d-none");

                $.each(data, function (index, value) {
                    if (index != "quotation") {
                        formulario.find(`[name="${index}"]`).val(value);
                    }
                });

                formulario.find("[name='lng']").val(data["lon"]);
                if (data["start_date"] != null) {
                    formulario.find("[name='start_date']").val(data["start_date"].split(" ")[0]);
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
                var data = tbl_projects.row(fila).data();
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
            case "info_stages":
                $("#mdl_info_stages .modal-header .modal-title").html("INFORMACIÓN DEL PROYECTO");
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                $("#mdl_info_stages").modal("show");
            case "add_visit":
                var fila = $(this).closest("tr");
                var data = tbl_projects.row(fila).data();
                $("#mdl_crud_visit form [name='project']").val(data["id"]);
                $("#mdl_crud_visit").modal("show");
                break;
            case "refresh_table":
                tbl_projects.ajax.reload();
                break;
            default:
                alert("Opcion no valida");
        }
        // end option
    });

    // ubicacion del proyecto
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
        address = $.trim(address);
        // $("#pills-address small.address").html(address);
        address_on_the_map(obj_map, address);
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
        var url = RUTA_URL + "Request/" + (option == "add" ? "addProject" : "updateProject");
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
                    tbl_projects.ajax.reload();
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

    // End
}

function calc_panels() {
    var form = $("#mdl_crud_proyect form");
    var charge = form.find("[name='charge']").val();
    var periodo = form.find("[name='period']").val();
    var hsp = $("[name='state'] option:selected").attr("data-hsp");
    var eficiencia_panel = form.find("[name='efficiency']").val();
    var capacidad_panel = form.find("[name='module_capacity']").val();

    charge = charge ? parseFloat(charge) : 0; // ! unidad: kWh
    eficiencia_panel = eficiencia_panel ? parseFloat(eficiencia_panel) / 100 : 0;
    capacidad_panel = capacidad_panel ? parseFloat(capacidad_panel) : 0;

    // calcular...
    var consumo_diario = charge / periodo; // ! Unidad: kWh
    // Pasar carga a cantidad W
    var carga_diaria = consumo_diario * 1000; // ! unidad: W
    // paneles
    var num_panels = carga_diaria / (hsp * eficiencia_panel * capacidad_panel);
    if (Number.isFinite(num_panels)) {
        num_panels = Math.ceil(num_panels);
    } else {
        num_panels = 0;
    }
    form.find("[name='panels']").val(num_panels);
    return num_panels;
}

$("form .calc-panels").on("input", function () {
    calc_panels();
});

// TODO ------------------------- [ ETAPAS PROYECTOS ] -------------------------

function fprojects_stages() {
    var tbl_projects = new DataTable("#table_proyects", {});
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "info_stages":
                $("#mdl_info_stages .modal-header .modal-title").html("INFORMACIÓN DEL PROYECTO");
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                $("#mdl_info_stages").modal("show");
                break;
        }
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

$("body").on("click", "button.download, .download-file", function () {
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

// TODO ------------------------- [ DOCUMENTOS GENERALES] -------------------------
$("[name='project-documents']").on("change", function (e) {
    e.preventDefault();
    var _id_project = $(this).val();

    $.ajax({
        type: "GET",
        url: RUTA_URL + "Request/getDocs/",
        data: {
            id_project: _id_project,
        },
        success: function (response) {
            var folder_names = $("div[data-folder-name]");
            folder_names.hide();

            if (!("data" in response)) {
                console.log(response);
                console.log("Sin datos");
                return;
            }

            // Mostrar las carpetas del proyecto
            $.each(response.data.docs, function (key, value) {
                if (Array.isArray(value)) {
                    // console.log("Es un array:", key, "=>", value);
                    // folder_names.filter(`[data-folder-name="${key}"]`).show();
                    filter = folder_names.filter(`[data-folder-name="${key}"]`);
                    if (filter.length > 0) {
                        filter.show();
                    } else {
                        // Crear folder en caso de no existir
                        var clonedFolder = $($("div[data-folder-name]")[0]).clone();
                        clonedFolder.show();
                        clonedFolder.attr("data-folder-name", key);
                        clonedFolder.find(".folder-name").html(key);
                        clonedFolder.appendTo(".row-folders");
                    }
                }
            });

            $("[data-folder-name]").on("click", function (e) {
                e.preventDefault();
                var key = $(this).attr("data-folder-name");
                var html = "";
                $("#mdl_project_documentsLabel").html(key);
                $.each(response.data.docs[key], function (key, value) {
                    // Dividir la ruta en partes usando el separador "/"
                    var partes = value.split("/");

                    // Tomar la última parte que debería ser el nombre del archivo
                    var nombreArchivoConExtension = partes[partes.length - 1];

                    // Dividir el nombre del archivo en nombre y extensión usando el punto "."
                    var nombreYExtension = nombreArchivoConExtension.split(".");
                    var nombreArchivo = nombreYExtension[0];
                    var extensionArchivo = nombreYExtension[1];
                    var icon = RUTA_URL + "img/icons/icon-" + extensionArchivo + ".png";

                    html += `<div class="col-6 col-md-3 col-lg-3">
                        <div
                            class="file download-file p-2"
                            data-id-project="${response.data.project["id"]}"
                            data-path="${value}"
                        >
                            <div class="file-icon px-1">
                                <img
                                    src="${icon}"
                                    alt="icon image"
                                    class="card-img w-100"
                                    onerror="this.src='${RUTA_URL}img/icons/icon-file.png'"
                                />
                            </div>
                            <div class="file-name text-center">${nombreArchivo}</div>
                        </div>
                    </div>
                    `;
                });

                // Cargar las columnas de los archivos...
                $(".row-files").html(html);

                // Mostrar el modal
                $("#mdl_project_documents").modal("show");
            });
        },
        error: function (jqXHR, textStatus, errorThrow) {},
        complete: function () {},
    });
});
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
    // Obtener la fecha y hora actual
    var fechaActual = new Date();
    // Obtener la fecha y hora seleccionada por el usuario
    var nuevaFecha = new Date($("#mdl_crud_visit form [name='start_date']").val());
    // Comparar con la fecha actual
    if (nuevaFecha < fechaActual) {
        // Mostrar mensaje de error o tomar alguna acción
        alert("La fecha de visita no puede ser anterior a la fecha y hora actuales.");
        return;
    }

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

// TODO ------------------------- [ CALENDARIO ] -------------------------
function calendar() {
    // Variable para almacenar el color seleccionado por el usuario
    // var selectedColor = "#008080"; // Valor por defecto o un color predefinido

    // Manejador de eventos para el cambio de color en el input type color
    // $("#colorPicker").on("input", function () {
    //     selectedColor = $(this).val();
    // });

    var calendar = $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay",
        },
        locale: "es",
        defaultView: "month",
        editable: true,
        events: function (start, end, timezone, callback) {
            // Realiza una solicitud Ajax para obtener los eventos
            $.ajax({
                url: RUTA_URL + "Request/getEventCaledar",
                type: "GET",
                success: function (response) {
                    var events = [];
                    if (response["success"]) {
                        $.each(response["data"], function (index, value) {
                            events.push({
                                id: value.id,
                                title: value.title,
                                description: value.description,
                                start: value.start_date,
                                end: value.end_date,
                                color: value.color,
                            });
                        });
                    } else {
                        console.log("Error al obtener eventos");
                    }
                    callback(events);
                },
                error: function (error) {
                    console.error("Error al cargar eventos:", error);
                },
            });
        },
        eventClick: function (event) {
            var form = $("#mdl_info_calendar form");
            form.find("[name='title']").val(event.title);
            form.find("[name='description']").val(event.description);

            $("#mdl_info_calendar").modal("show");
        },
    });

    // calendar.render();

    // Actualizar calendario
    function updateCalendar() {
        calendar.fullCalendar("refetchEvents");
    }

    $(".fc-prev-button").html('<i class="fa-solid fa-arrow-left"></i>');
    $(".fc-next-button").html('<i class="fa-solid fa-arrow-right"></i>');

    $("[name='form-calendar']").on("submit", function (e) {
        e.preventDefault();

        var datos = $(this).serialize();

        $.ajax({
            type: "POST",
            url: RUTA_URL + "Request/addEventCaledar",
            data: datos,
            beforeSend: function () {},
            success: function (response) {
                if (response.success) {
                    $("[name='form-calendar']")[0].reset();

                    updateCalendar();
                    Swal.fire("Good job!", "Accion exitosa", "success");
                } else {
                    Swal.fire("Oops", "paso algo", "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrow) {},
            complete: function () {},
        });
    });
}

$("[name='color']").on("change", function () {
    console.log($(this));
    valor = $(this).val();
    $(".row [name='bg']").val(valor);
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
