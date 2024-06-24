$(window).on("load", function () {
    var tbl_equipos = new DataTable("#table_equipos", {
        ajax: {
            url: RUTA_URL + "Request/getEquipments/",
            dataSrc: "data",
        },
        columns: [
            { title: "TAB", data: "equipment_tab" },
            {
                title: "Descripción",
                // data: "description"
                data: function (data) {
                    return "<div>" + data.sort_equipment_description + "</div>";
                },
            },
            { title: "Proveedor", data: "supplier_name" },
            { title: "Categoria", data: "equipment_category" },
            { title: "Precio", data: "price" },
            { title: "Moneda", data: "coin_code" },
            { title: "Opciones", data: "btn_action" },
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
    });

    tbl_equipos.on("click", "td:nth-child(2)", function (e) {
        e.preventDefault();
        var fila = $(this).closest("tr");
        var data = tbl_equipos.row(fila).data();
        var moda_equipment = $("#mdl_info_equipment");

        moda_equipment.find("form [name='tab']").val(data["equipment_tab"]);
        moda_equipment.find("form [name='description']").val(data["equipment_description"]); // Corregido aquí
        moda_equipment.modal("show");
    });

    $("[data-refresh-table]").on("click", function (e) {
        tbl_equipos.ajax.reload();
    });

    $(document).on("click", "[data-option]", function (e) {
        let obj = $(this);
        let option = obj.attr("data-option");
        switch (option) {
            case "add-equipement":
                var modal = $("#mdl_add_equipment");
                modal.find(".modal-title").html("Agregar datos");
                modal.modal("show");
                modal.find("form")[0].reset();
                modal.find("form [type='submit']").hide();
                modal.find("form [name='add']").show();
                modal.find("[name='listSupplier']").selectpicker("refresh");
                modal.find("[name='listCoin']").selectpicker("refresh");
                break;
            case "add-tabs":
                var modal = $("#mdl_add_tabs");
                modal.find("form")[0].reset();
                modal.modal("show");
                modal.find(".modal-title").html("Agregar nuevo equipo");
                modal.find("[type='submit'][name='add']").show();
                modal.find("[type='submit'][name='update']").hide();
                break;
            case "updateEquipment_to_supplier":
                var fila = $(this).closest("tr");
                var data = tbl_equipos.row(fila).data();
                var modal = $("#mdl_add_equipment");
                modal.find("form")[0].reset();
                modal.find(".modal-title").html("Actualizar equipo");
                modal.find("[type='submit'][name='add']").hide();
                modal.find("[type='submit'][name='update']").show();
                modal.modal("show");
                modal.find("[name='txtTab']").val(data["equipment_tab"]);
                modal.find("[name='txtDescripcion']").val(data["equipment_description"]);
                modal.find("[name='category_description']").val(data["equipment_category"]);
                modal.find("[name='txtPrecio']").val(data["price"]);
                modal.find("[name='listSupplier']").val(data["supplier_id"]);
                modal.find("[name='listCoin']").val(data["coin_id"]);
                modal.find("[name='proveedor__tab_id']").val(data["id"]);
                modal.find("[name='pdf_path']").val(data["pdf"]);
                modal.find("[name='listSupplier']").selectpicker("refresh");
                modal.find("[name='listCoin']").selectpicker("refresh");
                break;
            default:
                console.log("...");
        }
    });

    // Obterner info del equipo escrito
    $("#mdl_add_equipment form [name='txtTab']").on("input", function (e) {
        var formulario = $("#mdl_add_equipment form");
        var text = $(this).val();

        formulario.find("[name='txtDescripcion']").val(null);
        formulario.find("[name='category_id']").val(null);
        formulario.find("[name='category_description']").val(null);

        if (text.length >= 8) {
            $.ajax({
                type: "GET",
                url: RUTA_URL + "Request/getEquipment/",
                data: {
                    tab: text,
                },
                success: function (response) {
                    console.log(response);
                    formulario.find("[name='txtDescripcion']").val(response["data"]["description"]);
                    formulario.find("[name='category_id']").val(response["data"]["category"]);
                    formulario
                        .find("[name='category_description']")
                        .val(response["data"]["category_description"]);
                    // formulario.find("[name='pdf_path']").val( )
                },
                error: function (jqXHR, textStatus, errorThrow) {
                    Swal.fire("Oops", "algo ocurrio :C", "error");
                    console.log(errorThrow);
                },
            });
        }
    });

    // submit - enviar
    $("#mdl_add_equipment form").on("submit", function (e) {
        e.preventDefault();
        var submit = $("button[type='submit']:focus", this).attr("name");
        var url = RUTA_URL;
        var datos = new FormData(this);

        if (submit == "add") {
            url += "Request/addEquipment_to_supplier/";
        } else if (submit == "update") {
            url += "Request/updateEquipment_to_supplier/";
        } else {
        }

        let supplier_name = $("#mdl_add_equipment [name='listSupplier'] option:selected").text();
        datos.append("listSupplier_str", supplier_name);

        $.ajax({
            type: "POST",
            url: url,
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                if (!response.success && response.error) {
                    Swal.fire("Exito", response.error["message"], "error");
                    return;
                } else if (!response.success && response.warning) {
                    Swal.fire("Exito", response.warning["message"], "warning");
                    return;
                } else if (!response.success) {
                    console.log(response);
                    Swal.fire("Error", "Ocurrio un error inesperado", "error");
                    return;
                }
                Swal.fire("Exito", "Todo correcto", "success");
                $("#mdl_add_equipment").modal("hide");
                $("#mdl_add_equipment form :input").val(null);
                tbl_equipos.ajax.reload();
            },
            error: function (jqXHR, textStatus, errorThrow) {
                console.log(errorThrow);
            },
        });
    });

    $.ajax({
        type: "GET",
        url: RUTA_URL + "Request/getCoin/",
        success: function (response) {
            if (response.success) {
                var modal = $("#mdl_add_equipment");
                var select = modal.find("[name='listCoin']");
                select.empty();
                select.append('<option value="">Seleccione una opción</option>');
                $.each(response.data, function (index, d) {
                    select.append(`<option value= "${d.id}">${d.code}</option>`);
                });
                select.selectpicker("refresh");
            } else {
                console.log(response);
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.log(errorThrow);
            Swal.fire("Oops", "algo ocurrio :C", "error");
        },
    });

    $.ajax({
        type: "GET",
        url: RUTA_URL + "Request/getSupplier/",
        success: function (response) {
            if (response.success) {
                var modal = $("#mdl_add_equipment");
                var select = modal.find("[name='listSupplier']");
                select.empty();
                select.append('<option value="">Seleccione una opción</option>');
                $.each(response.data, function (index, d) {
                    select.append(`<option value= "${d.id}">${d.name}</option>`);
                });
                select.selectpicker("refresh");
            } else {
                console.log(response);
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.log(errorThrow);
            Swal.fire("Oops", "algo ocurrio :C", "error");
        },
    });

    $.ajax({
        type: "GET",
        url: RUTA_URL + "Request/getCategory/",
        success: function (response) {
            if (response.success) {
                var modal = $("#mdl_add_tabs");
                var select = modal.find("[name='listCategory']");
                select.empty();
                select.append('<option value="">Seleccione una opción</option>');
                $.each(response.data, function (index, d) {
                    select.append(`<option value= "${d.id}">${d.description}</option>`);
                });
                select.selectpicker("refresh");
            } else {
                console.log(response);
            }
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.log(errorThrow);
            Swal.fire("Oops", "algo ocurrio :C", "error");
        },
    });
});
