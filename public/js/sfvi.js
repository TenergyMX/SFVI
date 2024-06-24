var opcionesMap = {
    center: {
        lat: parseFloat(20.50947009600965),
        lng: parseFloat(-100.51961774497228),
    },
    zoom: 13,
};

//Es la función del mapa
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
            tbl_visits.ajax.reload();
            $("#mdl_crud_visit").modal("hide");
        },
        error: function (jqXHR, textStatus, errorThrow) {
            console.error(errorThrow);
            Swal.fire("Oops", "Error del servidor", "error");
        },
        complete: function () {},
    });
});

// TODO ------------------------- [ Funciones Globales ] -------------------------
//Esta función esta creada para los modales de proyecto y anteproyecto
function calc_panels() {
    var form = $("#mdl_crud_project form");
    var charge = form.find("[name='charge']").val();
    var periodo = form.find("[name='period']").val();
    var hsp = $("[name='state'] option:selected").attr("data-hsp");
    var eficiencia_panel = form.find("[name='efficiency']").val();
    var capacidad_panel = form.find("[name='module_capacity']").val();

    charge = charge ? parseFloat(charge) : 0; // ! unidad: kWh
    eficiencia_panel = eficiencia_panel ? parseFloat(eficiencia_panel) / 100 : 0;
    capacidad_panel = capacidad_panel ? parseFloat(capacidad_panel) : 0;

    // console.log(charge);
    // console.log(periodo);
    // console.log(hsp);
    // console.log(eficiencia_panel);
    // console.log(capacidad_panel);

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
