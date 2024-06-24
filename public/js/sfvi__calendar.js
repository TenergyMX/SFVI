$(window).on("load", function () {
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

    function updateCalendar() {
        calendar.fullCalendar("refetchEvents");
    }

    $(".fc-prev-button").html('<i class="fa-solid fa-arrow-left"></i>');
    $(".fc-next-button").html('<i class="fa-solid fa-arrow-right"></i>');

    $("[name='color']").on("change", function () {
        valor = $(this).val();
        $(".row [name='bg']").val(valor);
    });

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
});
