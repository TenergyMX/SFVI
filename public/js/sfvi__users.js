$(window).on("load", function () {
    var mdl_user = $("#mdl_crud_user");

    // Tabla de usuarios
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
                        `<option value="${value.id}">${value.name} ${value.surnames}</option>`
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
});
