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
    // Tabla anteproyectos
    var tbl_anteprojects = new DataTable("#table_ante_projects", {
        ajax: {
            url: RUTA_URL + "Request/getAnteProjects/",
            dataSrc: "data",
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Proyecto", data: "btn_project" },
            { title: "Cliente", data: "customer_fullName" },
            { title: "Progreso (%)", data: "antepercentage" },
            { title: "Crear", data: "btn_action_create" },
            { title: "Editar", data: "btn_action" },
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    // Tabla de proyectos
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
        $("#mdl_crud_proyect form :input").removeClass("is-invalid is-valid");
        $("#mdl_crud_proyect .col-existing-file").addClass("d-none");
        var form = $("#mdl_crud_proyect form");

        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "draft":
            case "createAnteProject":
                $("#mdl_crud_proyect form")[0].reset();
                $("#mdl_crud_proyect .modal-header .modal-title").html("CREAR ANTEPROYECTO");
                $("#mdl_crud_proyect .modal-body form [type='submit']").hide();
                $("#mdl_crud_proyect .modal-body form [name='createAnteProject']").show();
                // ocultamos :input que no requiera el anteproyecto
                form.find(".col-project").hide();
                // Mostramos el modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update-anteproject":
            case "updateAnteProject":
                $("#mdl_crud_proyect .modal-header .modal-title").html("ACTUALIZAR ANTEPROYECTO");
                // ocultamos :input que no requiera el anteproyecto
                $("#mdl_crud_proyect .modal-body form [type='submit']").hide();
                $("#mdl_crud_proyect .modal-body form [name='updateAnteProject']").show();
                form.find(".col-project").hide();
                // cargamos la info de la tabla al modal
                var fila = $(this).closest("tr");
                var data = tbl_anteprojects.row(fila).data();
                $.each(data, function (index, value) {
                    if (index != "quotation") {
                        form.find(`[name="${index}"]`).val(value);
                    }
                });
                // Mostramos el modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "createProject":
                $("#mdl_crud_proyect form")[0].reset();
                $("#mdl_crud_proyect .modal-header .modal-title").html("CREAR PROYECTO");
                $("#mdl_crud_proyect .modal-body form [type='submit']").hide();
                $("#mdl_crud_proyect .modal-body form [name='createProject']").show();
                // mostramos :input que no requiera el anteproyecto
                form.find(".col-project").show();
                // cargamos la info de la tabla al modal
                var fila = $(this).closest("tr");
                var data = tbl_anteprojects.row(fila).data();
                $.each(data, function (index, value) {
                    if (index != "quotation") {
                        form.find(`[name="${index}"]`).val(value);
                    }
                });
                // Mostramos el modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update":
            case "updateProject":
                $("#mdl_crud_proyect .modal-header .modal-title").html("ACTUALIZAR PROYECTO");
                // ocultamos :input que no requiera el anteproyecto
                $("#mdl_crud_proyect .modal-body form [type='submit']").hide();
                $("#mdl_crud_proyect .modal-body form [name='updateProject']").show();
                form.find(".col-project").show();
                // cargamos la info de la tabla al modal
                var fila = $(this).closest("tr");
                var data = tbl_projects.row(fila).data();
                $.each(data, function (index, value) {
                    if (index != "quotation") {
                        form.find(`[name="${index}"]`).val(value);
                    }
                });
                // cargamos la fecha al input
                if (data["start_date"] != null) {
                    form.find("[name='start_date']").val(data["start_date"].split(" ")[0]);
                }
                // Mostramos el modal
                $("#mdl_crud_proyect").modal("show");
                break;
            case "refresh_table-anteproject":
                tbl_anteprojects.ajax.reload();
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

        if (form.find("[name='id_type']").val() == 1) {
            form.find("[name='tb']").removeClass("is-invalid").addClass("is-valid");
            form.find("[name='quotation']").removeClass("is-invalid").addClass("is-valid");
            form.find("[name='quotation_num']").removeClass("is-invalid").addClass("is-valid");
            form.find("[name='id_fide']").removeClass("is-invalid").addClass("is-valid");
            form.find("[name='start_date']").removeClass("is-invalid").addClass("is-valid");
        }

        // Validar
        if ($("#mdl_crud_proyect form .is-invalid").length > 0) {
            Swal.fire("Oops", "Te hace falta rellenar campos", "warning");
            return;
        }

        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = new FormData($("#mdl_crud_proyect form")[0]);
        // var url = RUTA_URL + "Request/" + (option == "add" ? "addAnteProject" : "updateProject");
        var url = RUTA_URL + "Request/";

        if (option == "createAnteProject") {
            url += "addAnteProject";
        } else if (option == "updateAnteProject") {
            url += "updateAnteProject";
        } else if (option == "createProject") {
            url += "createProject";
        } else if (option == "updateProject") {
            url += "updateProject";
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
                console.log(response);
                if (response.success) {
                    Swal.fire("Good job!", "Accion exitosa", "success");
                    try {
                        tbl_anteprojects.ajax.reload();
                        tbl_projects.ajax.reload();
                    } catch (error) {}
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
    console.log("click en calc-paneles");
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

$("form.form-anteproject-stages").on("submit", function (e) {
    e.preventDefault();
    var datos = new FormData($(this)[0]);
    var formulario_mdl = $("#mdl_info_stages form");
    datos.append("id", formulario_mdl.find("[name='id']").val());

    $.ajax({
        type: "POST",
        url: RUTA_URL + "Request/vulpes/",
        data: datos,
        contentType: false,
        processData: false,
        beforeSend: function () {},
        success: function (response) {
            Swal.fire("¡Bien hecho!", "Accion exitosa", "success");
            calcularProgresoEtapa(1);
        },
        error: function (error) {},
        complete: function () {},
    });
});

$("form.form-project-stages").on("submit", function (e) {
    e.preventDefault();
    var formulario = $(this);
    var datos = new FormData($(this)[0]);
    datos.set("id_type", 1);
    var id_project = formulario.find("[name='id_project']").val();
    var stage = formulario.find("[name='stage']").val();
    var id_type = formulario.find("[name='id_type']").val();

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
                console.log("Ocurrio algo");
                console.log(data);
                return false;
            }

            // success
            $(`#pills-ante-etapa-${stage}-tab`).attr("data-finished-stage", 1);
            formulario.find(":input").prop("disabled", false);
            let path =
                typeof data !== "undefined" &&
                typeof data["data"] !== "undefined" &&
                typeof data["data"]["file"] !== "undefined" &&
                typeof data["data"]["file"]["path"] !== "undefined"
                    ? data["data"]["file"]["path"]
                    : "file.file";
            let btn;

            if (id_type == 1 || id_type == "1") {
                btn = $(`#pills-ante-etapa-${stage}-files .btn-checkbox`);
                let extension = "file";
                let partes = path.split("/");
                let nombreArchivoConExtension = partes[partes.length - 1];
                let nombreYExtension = nombreArchivoConExtension.split(".");
                let extensionArchivo = nombreYExtension[1];
                extension = extensionArchivo;
                btn.addClass("px-2");
                btn.html(`<img
                    src="${RUTA_URL}img/icons/icon-${extension}.png"
                    alt="icono"
                    class="img-fluid"
                    onerror="this.src='${RUTA_URL}img/icons/icon-file.png'"
                />`);
            } else {
                console.log("Trabajando....");
            }

            // cabiamos el boton del check
            try {
                btn.removeClass("p-0 m-0");
                btn.addClass("checked");
                btn.attr("data-id-project", id_project);
                btn.attr("data-path", path);
            } catch (error) {}

            // Le avisamos al usuario
            calcularProgresoEtapa(id_type);
            Swal.fire("Good job!", "Accion exitosa", "success");
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

$("body select.btn-checkbox").on("change", function () {
    var obj_input = $(this);
    let stage = $(this).attr("data-stage");

    Swal.fire({
        title: "¿Estas seguro de continuar el proceso?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, estoy seguro.",
    }).then((result) => {
        if (result.isConfirmed) {
            $(`#pills-ante-etapa-${stage}-files form.form-project-stages`).submit();
        } else {
            $(this).val(0);
        }
    });
});

// Confirmar etapa en la que se encuentra
function check_stage(id_type = 1) {
    var bool = 0;
    switch (id_type) {
        case 1:
        case "anteproyecto":
            for (let i = 1; i < 8; i++) {
                let tab = $(`#pills-ante-etapa-${i}-files .btn-checkbox`);
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

// cambiar de etapa
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
            progreso_maximo: 0,
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
            resultado["general"]["progreso_maximo"] = 600;
            var list_objs = $("[data-finished-stage]");
            $.each(list_objs, function (i, obj) {
                success = $(obj).attr("data-finished-stage");
                if (!success || success == 0) {
                    stage = i;
                    return false;
                }
            });
            resultado["general"]["progreso_completado"] = stage > 0 ? stage * 100 : 0;
            if (
                resultado["general"]["progreso_completado"] > 600 &&
                resultado["general"]["progreso_completado"] <= 700
            ) {
                resultado["general"]["progreso_completado"] = 600;
            }
            break;
        case 2:
            console.log("Proyecto");
        default:
            console.log("Tipo desconocido ['Anteproyecto', 'Proyecto']");
            console.log(id_type);
    }
    // Efecto, Cambio de la la barra de porcentaje
    $(".container-progress-bar .progress").css(
        "width",
        resultado["general"]["progreso_completado"] + "%"
    );
    $(".container-progress-bar .progress-bar").css(
        "width",
        resultado["general"]["progreso_completado"] + "%"
    );
    // Cambia de etapa con un tab
    setTimeout(function () {
        project_stage_transition(`#pills-${category}-etapa-${stage + 1}-tab`);
    }, 505);
    return resultado;
}

// TODO ------------------------- [ CHAT] -------------------------

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
