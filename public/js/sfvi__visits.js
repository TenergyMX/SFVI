$(window).on("load", function () {
    // Tabla de Visitas
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

    const opcionesMapa = {
        center: {
            lat: parseFloat(20.50947009600965),
            lng: parseFloat(-100.51961774497228),
        },
        zoom: 13,
    };

    var obj_map = createMap_id("#mdl_info_visit .map", opcionesMapa);

    $(document).on("click", "[data-option]", function () {
        let option = $(this).attr("data-option"); // [create / add , update, delete]
        switch (option) {
            case "create":
            case "add":
                var formulario = $("#mdl_crud_visit form");
                formulario[0].reset();
                formulario.find("[name='select_project']").prop("disabled", false);
                formulario.find("[name='select_project']").show();
                formulario.find("[name='str_proyecto']").prop("disabled", true);
                formulario.find("[name='str_proyecto']").hide();

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
                var formulario = $("#mdl_crud_visit form");
                console.log(data);

                // formulario.find("[name='select_project']").prop("disabled", true);

                formulario.find("[name='select_project']").hide();
                formulario.find("[name='select_project']").val(data["id_project"]);
                formulario.find("[name='int_proyecto']").val(data["id_project"]);
                formulario.find("[name='str_proyecto']").val(data["project_name"]);
                formulario.find("[name='str_proyecto']").prop("readonly", true);
                formulario.find("[name='str_proyecto']").show();

                $("#mdl_crud_visit form [name='id']").val(data["id"]);
                $("#mdl_crud_visit form [name='id_type']").val(data["id_type"]);
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
    });
});
