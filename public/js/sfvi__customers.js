$(window).on("load", function () {
    // Tabla de Clientes
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
    // End

    $("body").on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                var obj_modal = $("#mdl_crud_client");
                $("#mdl_crud_client .modal-header .modal-title").html("AGREGAR CLIENTE");
                $("#mdl_crud_client .modal-body form [name='add']").show();
                $("#mdl_crud_client .modal-body form [name='update']").hide();
                obj_modal.find("form")[0].reset();
                obj_modal.modal("show");
                break;
            case "update":
                var obj_modal = $("#mdl_crud_client");
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

                $("#mdl_crud_client form [type='submit']").prop("disabled", false);
                tbl_clients.ajax.reload();
                $("#mdl_crud_client").modal("hide");
            },
            error: function (jqXHR, textStatus, errorThrow) {
                Swal.fire("Error del servidor", "Error en el servidor", "error");
                console.log("hubo un error: " + errorThrow);
            },
            complete: function () {
                $("#mdl_crud_client form [type='submit']").prop("disabled", false);
            },
        });
    });
});
