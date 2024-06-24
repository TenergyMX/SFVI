$(window).on("load", function () {
    var mdl_quote = $("#mdl_crud_quote");
    ftable_quote();

    //Etapa de cotización
    //Generamos la tabla de equipos de la cotización
});

function ftable_quote() {
    var id_project = 0;

    var tbl_1 = new DataTable("#table_quote", {
        ajax: {
            url: RUTA_URL + "Request/getQuotes/",
            dataSrc: "data",
            data: function (d) {
                d.type = $('[name="select-type-project"]').val();
            },
            complete: function () {
                if ($('[name="select-type-project"]').val() === "anteproject") {
                    tbl_1.column(2).visible(true);
                } else {
                    tbl_1.column(2).visible(false);
                }
            },
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Proyecto", data: "btn_project" },
            {
                title: "Opciones",
                data: function (d) {
                    return `
                    <button type="button" class="btn btn-sfvi-1">Aceptar</button>
                    <button type="button" class="btn btn-danger">Rechazar</button>
                `;
                },
                visible: false,
            },
            {
                title: "Notas",
                data: function (d) {
                    return "Sin notas";
                },
            },
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    $(document).on("click", "[data-quote]", function (event) {
        var contenido_1 = $("#col-content-1");
        var contenido_2 = $("#col-content-2");

        // Obtener la información de la fila
        var fila = $(this).closest("tr");
        var data = tbl_1.row(fila).data();
        id_project = data["id"];

        // Actualizar la tabla
        tbl_2.ajax.reload();

        // ocultar y mostrar
        contenido_1.hide();
        contenido_2.show(250);
    });

    $("#col-content-1 [name='select-type-project']").on("change", function () {
        var valor = $(this).val();
        tbl_1.ajax.reload();
    });

    $("#btn-regresar").on("click", function (event) {
        $("#col-content-1").show();
        $("#col-content-2").hide();
    });

    var tbl_2 = new DataTable("#quotes_of_project", {
        ajax: {
            url: RUTA_URL + "Request/getQuotes/",
            dataSrc: "data",
            data: function (d) {
                d.id_project = id_project;
            },
        },
        columns: [
            { title: "ID", data: "id" },
            { title: "Folio", data: "id" },
            { title: "Descripción", data: "id" },
            { title: "Precio", data: "id" },
            { title: "Marca", data: "id" },
            { title: "Modelo ", data: "id" },
            { title: "PDF ", data: "id" },
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });
}
