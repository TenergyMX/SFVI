var datos = $(this).serialize();
var url = RUTA_URL + "Request/updateVisit/";

var fechaActual = $("#mdl_crud_visit form [name='start_date']").val(); // Cambiado de 'new_date' a 'fecha_actual'
var nuevaFecha = $("#mdl_crud_visit form [name='new_date']").val();

if (fechaActual !== nuevaFecha) {
    // La fecha ha cambiado, realiza la actualización y cambia el estado a 5
    url = RUTA_URL + "Request/updateStatusReagendada/";
}

$.ajax({
    type: "POST",
    url: url,
    data: datos,
    beforeSend: function () {
        $("#mdl_crud_visit form [type='submit']").prop("disabled", true);
    },
    success: function (response) {
        $("#mdl_crud_visit").modal("hide");
        if (response.success) {
            Swal.fire("Good job!", "Accion exitosa", "success");
        } else {
            Swal.fire("Oops", "Fallo algo", "error");
        }
    },
    error: function (jqXHR, textStatus, errorThrow) {
        console.log("Hubo un error: ", errorThrow);
        Swal.fire("Error del servidor", ":(", "error");
    },
    complete: function () {
        $("#mdl_crud_visit form [type='submit']").prop("disabled", false);
    },
});
