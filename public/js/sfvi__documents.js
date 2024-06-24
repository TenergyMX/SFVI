$(window).on("load", function () {
    var mdl_documents = $("#mdl_crud_documents");
    //Etapa de documentos
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
});
