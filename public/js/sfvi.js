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
            { title: "Info. visit", data: "btn_info" },
            { title: "Editar", data: "btn_action" },
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

    setInterval(function () {
        $("[name='btn-location']").hide();
    }, 500);

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

                $("#mdl_crud_visit form [name='id']").val(data["id"]);
                $("#mdl_crud_visit form [name='id_type']").val(data["id_type"]);
                $("#mdl_crud_visit form [name='project']").val(data["id_project"]);
                $("#mdl_crud_visit form [name='id_user']").val(data["id_user"]);
                $("#mdl_crud_visit form [name='description']").val(data["description"]);
                $("#mdl_crud_visit form [name='start_date']").val(data["start_date"]);
                $("#mdl_crud_visit form [name='start_date_old']").val(data["start_date"]);
                $("#mdl_info_visit form [name='note']").val(data["note"]);

                if (data["id_status"] == 3) {
                    $("#mdl_crud_visit [name='update']").prop("readonly", true);
                } else {
                    $("#mdl_crud_visit [name='update']").prop("readonly", false);
                }
                break;
            case "show_info":
                $("#mdl_info_visit .modal-header .modal-title").html("INFORMACIÓN DE LA VISITA");
                var fila = $(this).closest("tr");
                var data = tbl_visits.row(fila).data();

                $("#mdl_info_visit form [name='id']").val(data["id"]);
                $("#mdl_info_visit form [name='type']").val(data["str_type_of_visit"]);
                $("#mdl_info_visit form [name='razon']").val(data["description"]);
                $("#mdl_info_visit form [name='proyect']").val(data["project_name"]);
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
                    update_map_location(obj_map, parseFloat(data["lat"]), parseFloat(data["lng"]));
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
            { title: "Progreso (%)", data: "percentage" },
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
                var modal = $("#mdl_crud_proyect");
                var form = modal.find("form");
                // limpiar formulario
                form[0].reset();

                // cargamos la info de la tabla al modal
                var fila = $(this).closest("tr");
                var data = tbl_anteprojects.row(fila).data();
                $.each(data, function (index, value) {
                    try {
                        form.find(`[name="${index}"]`).val(value);
                    } catch (error) {}
                });

                // bloqueamos la edicion de algunos inputs
                $.each(form.find(":input:not(button)"), function (index, obj) {
                    if ($(this).val() !== null && $(this).val() !== 0 && $(this).val() !== "") {
                        $(this).prop("disabled", true);
                    } else {
                        $(this).prop("disabled", false);
                    }
                });

                // Descbloqueamos :input's especifico
                form.find("[name='id']").prop("disabled", false);
                form.find("[name='id_category']").prop("disabled", false);

                // cargamos visualmente la ubicacion del proyecto en el mapa
                _lat = form.find("[name='lat']").val();
                _lng = form.find("[name='lng']").val();
                update_map_location(obj_map, _lat, _lng, 18);

                form.find("[type='submit']").hide();
                form.find("[name='createProject']").show();
                modal.modal("show");
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
            case "add_visit":
                var fila = $(this).closest("tr");
                var data = tbl_projects.row(fila).data();
                $("#mdl_crud_visit form [name='project']").val(data["id"]);
                $("#mdl_crud_visit").modal("show");
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
            url += "createProject/";
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
// Función para manejar las etapas de los proyectos
function fprojects_stages() {
    // Inicializar DataTable para la tabla de proyectos
    var tbl_projects = new DataTable("#table_proyects", {});
    // Manejador de eventos para hacer clic en elementos con atributo data-option
    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // Posibles valores: [create / add , update, delete]
        switch (option) {
            case "create":
            case "info_stages":
                // Establecer el título del modal y mostrar elementos relevantes del formulario
                $("#mdl_info_stages .modal-header .modal-title").html("INFORMACIÓN DEL PROYECTO");
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                // Mostrar el modal
                $("#mdl_info_stages").modal("show");
                break;
        }
    });
    // Fin del proyecto
}

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

// TODO ------------------------- [ CHAT] -------------------------

$("form.form-chat-messages").on("submit", function (e) {
    e.preventDefault();

    // Deshabilitar el scroll
    $("body").css("overflow", "hidden");

    var form = $(this); // Obtener el formulario
    var datos = form.serialize();
    var _id_project = $(this).find("[name='id_project']").val();
    var _stage = $(this).find("[name='stage']").val();

    $.ajax({
        type: "POST",
        url: "http://localhost/SFVI/Request/addComments",
        data: datos,
        success: function (data) {
            // Vaciar el mensaje del formulario seleccionado
            form.find("[name='message']").val("");
            state_chat(_id_project, _stage);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            Swal.fire("Oops", "Fallo algo", "error");
        },
        complete: function () {
            // Habilitar el scroll después de completar la petición AJAX
            $("body").css("overflow", "auto");
        },
    });
});

function state_chat(_id_project = 0, _stage = 1) {
    var contenedor = $(`#pills-ante-etapa-${_stage}-notes .chat-messages`);
    $.ajax({
        url: "http://localhost/SFVI/Request/getComments",
        data: {
            id_project: _id_project,
            stage: _stage,
        },
        success: function (d) {
            if (!d["success"]) {
                return;
            }

            if (d["data"].length < 1) {
                return;
            }

            let html = "";
            $.each(d["data"], function (index, value) {
                let class_1 = value["author"] == "Tu" ? "chat-message-right" : "chat-message-left";
                let author = value["author"];
                html += `<div class="${class_1} pb-1">
                            <div class="avatar">
                                <img
                                    src="${RUTA_URL}img/avatars/user.png"
                                    class="rounded-circle mr-1 me-1"
                                    alt="Chris Wood"
                                    width="80"
                                    height="80"
                                />
                            </div>
                            <div class="me-3">
                                <div
                                    class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3 body"
                                >
                                    <div class="author fw-bold">${author}</div>
                                    <div class="message">${value["message"]}</div>
                                </div>
                                <small class="date">${value["created_at"]}</small>
                            </div>
                        </div>`;
            });
            // Escribir los mensajes
            contenedor.html(html);
            // Desplazar hacia abajo después de actualizar los mensajes
            alto = contenedor[0].scrollHeight;
            contenedor.scrollTop(alto);
            alto = contenedor[0].scrollHeight;
        },
        error: function () {
            console.log("error");
        },
        complete: function () {},
    });
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

// TODO ------------------------- [ CALENDARIO ] -------------------------
function calendar() {
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
    valor = $(this).val();
    $(".row [name='bg']").val(valor);
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
    var formulario = $(this);
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
                formulario[0].reset();
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
    locationButton.classList.add("btn", "btn-danger", "mt-3");
    locationButton.textContent = "Ubicarme";

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
