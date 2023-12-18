// TODO: Moodulo usuarios
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

/* -------------------------------------CLIENTES------------------------------- */
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
                // tbl_clients.ajax.reload();
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
/* -------------------------------------VISITAS------------------------------- */
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
            { title: "Proyecto", data: null },
            { title: "Acción", data: "btn_action" },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).attr("data-id", data.id);
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    var opcionesMapa = {
        center: {
            lat: parseFloat(20.50947009600965),
            lng: parseFloat(-100.51961774497228),
        },
        zoom: 13,
    };

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
                opcionesMapa.center.lat = parseFloat(data["lat"]);
                opcionesMapa.center.lng = parseFloat(data["lon"]);
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
            default:
                alert("Opcion no valida (tabla visistas)");
        }
        // end option
    });
    //modal informacion visita
    /*    $("body").on("click", "[btn_action]", function () {
        let option = $(this).attr("btn_action");
        switch (option) {
            case "create":
            case "add":
                $("#mdl_info_visit .modal-header .modal-title").html("NOTA VISITA");
                $("#mdl_info_visit .modal-body form [name='add']").show();
                $("#mdl_info_visit .modal-body form [name='update']").hide();
                $("#mdl_info_visit").modal("show");
                break;
            case "update":
                $("#mdl_info_visit .modal-header .modal-title").html("ACTUALIZAR VISITA");
                $("#mdl_info_visit .modal-body form [name='add']").hide();
                $("#mdl_info_visit .modal-body form [name='update']").show();
                $("#mdl_info_visit").modal("show");
                break;
            case "show_info":
                var fila = $(this).closest("tr");
                var id = $(this).closest("tr").data("id");
                $("#mdl_info_visit form [name='tipo']").val("id_type");
                $("#mdl_info_visit form [name='tipo']").val(fila.find("td:eq(0)").text());
                $("#mdl_info_visit form [name = 'razon']").val(
                    fila.find("td:eq(6)").text()
                );
                $("#mdl_info_visit form [name='proyect']").val(
                    fila.find("td:eq(4)").text()
                );
                $("#mdl_info_visit form [name = 'visit']").val(
                    fila.find("td:eq(1)").text()
                );
                Swal.fire(
                    "ELIMINADO!",
                    "El dato ha sido eliminado correctamente",
                    "success"
                );

                break;
            default:
                alert("Opcion no valida");
        }
        // end option
    }); */

    // Create and update visit

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
/* --------------------------------------PROYECTOS--------------------------------------------- */
function ftable_proyects() {
    var tbl_proyects = new DataTable("#table_proyects", {
        ajax: {
            url: RUTA_URL + "Request/getProjects/",
            dataSrc: "data",
        },
        columns: [
            { title: "Id", data: "id" },
            /*{
                title: "Proyecto",
                data: "tb_project",
                render: function (data, type, row) {
                    return (
                        '<a href="' +
                        RUTA_URL +
                        "project_Stages/" +
                        data +
                        '">' +
                        data +
                        "</a>"
    
                    );
                },
            },*/
            { title: "Proyecto", data: "btn_folio" },
            { title: "Cliente", data: "id_client" },
            { title: "Avance", data: "percentage" },
            { title: "Documentación", data: "btn_docs" },
            /*  {
                title: "Documentación",
                data: "id_client",
                render: function (data, type, row) {
                    return (
                        '<a href="' +
                        RUTA_URL +
                        "Document/" +
                        data +
                        '"><button class="btn btn-success"><i class="fa-solid fa-folder"></i></button>' +
                        "</a>"
                    );
                },
            }, */
            { title: "Visitas", data: "btn_action_visit" },
            { title: "Editar", data: "btn_action_update" },
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
                $("#mdl_crud_proyect .modal-header .modal-title").html(
                    "AGREGAR PROYECTO"
                );
                $("#mdl_crud_proyect .modal-body form [name='add']").show();
                $("#mdl_crud_proyect .modal-body form [name='update']").hide();
                $("#mdl_crud_proyect").modal("show");
                break;
            case "update":
                $("#mdl_crud_proyect .modal-header .modal-title").html(
                    "ACTUALIZAR PROYECTO"
                );
                $("#mdl_crud_proyect .modal-body form [name='add']").hide();
                $("#mdl_crud_proyect .modal-body form [name='update']").show();
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
            /*  case "info_stages":
                $("#mdl_info_stages .modal-header .modal-title").html(
                    "INFORMACIÓN DEL PROYECTO"
                );
                $("#mdl_info_stages .modal-body form [name='add']").show();
                $("#mdl_info_stages .modal-body form [name='update']").hide();
                $("#mdl_info_stages").modal("show");

                var fila = $(this).closest("tr");
                var data = tbl_proyects.row(fila).data();
                $("#mdl_info_stages form [name='id']").val(data["id"]);
                /* $("#mdl_info_visit form [name='tipo']").val("id_type"); */
            /*  $("#mdl_info_stages form [name='folio']").val(
                    fila.find("td:eq(1)").text()
                );
                $("#mdl_info_stages form [name = 'id_client']").val(
                    fila.find("td:eq(2)").text()
                );
                $("#mdl_info_stages").modal("show");

                break; */
            default:
                alert("Opcion no valida");
        }
        // end option
    });

    // Create and update proyect
    $("#mdl_crud_proyect form").on("submit", function (e) {
        e.preventDefault();
        var option = $('button[type="submit"]:focus', this).attr("name");
        var datos = $(this).serialize();
        var url =
            RUTA_URL + "Request/" + (option == "add" ? "addProyect" : "updateProyect");
        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            beforeSend: function () {
                $("#mdl_crud_proyect form [type='submit']").prop("disabled", true);
            },
            success: function (response) {
                // tbl_clients.ajax.reload();
                $("#mdl_crud_proyect").modal("hide");
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

    function marcarCheckbox(checkboxId) {
        // Obtener el checkbox por su ID
        var checkbox = document.getElementById(checkboxId);

        // Marcar el checkbox si está desmarcado, y viceversa
        checkbox.checked = !checkbox.checked;
    }

    // End
}
/* --------------------------------------ETAPAS PROYECTOS--------------------------------------------- */
