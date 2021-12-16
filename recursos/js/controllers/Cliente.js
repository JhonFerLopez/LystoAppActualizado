var Cliente = {

    googleMap: false,
    borrar: function (id, nom) {

        $('#borrar').modal('show');
        $("#id_borrar").attr('value', id);
        $("#nom_borrar").attr('value', nom);
    },


    editar: function (id) {

        Utilities.showPreloader();
        $.ajax({
            url: baseurl + 'cliente/form/' + id,
            type: 'post',

            success: function (data) {
                Utilities.hiddePreloader();
                $("#agregar").html(data);
                $('#agregar').modal({show: true, keyboard: false, backdrop: 'static'});
            },
            error: function (error) {

                Utilities.hiddePreloader();
                Utilities.alertModal('Ha ocurrido un erro', 'error');
            }

        });


    },

    agregar: function () {

        Utilities.showPreloader();

        $.ajax({
            url: baseurl + 'cliente/form',
            type: 'post',
            success: function (data) {
                Utilities.hiddePreloader();
                $("#agregar").html(data);
                $('#agregar').modal();
            },
            error: function (error) {

                Utilities.hiddePreloader();
                Utilities.alertModal('ha ocurrido un error', 'warning');
            }

        });


    },


    ajaxgrupo: function () {
        return $.ajax({
            url: baseurl + 'cliente'

        })
    },

    eliminar: function () {

        App.formSubmitAjax($("#formeliminar").attr('action'), Cliente.ajaxgrupo, 'borrar', 'formeliminar');
    },
    guardar: function () {
        Utilities.showPreloader();
        $("#guardar").addClass('disabled');


        if ($("#identificacion").val() == '') {


            Utilities.alertModal('<h4>Debe ingresar la identificaci&oacute;n</h4>', 'warning');
            $("#guardar").removeClass('disabled');
            Utilities.hiddePreloader();
            return false;
        }

        if ($("#grupo_id").val() == '') {

            Utilities.alertModal('<h4>Debe seleccionar el Tipo de cliente</h4>', 'warning');

            $("#guardar").prop('disabled', false);
            $(this).prop('disabled', true);
            Utilities.hiddePreloader();
            return false;
        }

        if ($("#id_pais").val() == '') {

            Utilities.alertModal('<h4>Debe seleccionar el pais</h4>', 'warning');
            Utilities.hiddePreloader();
            $(this).prop('disabled', true);
            $("#guardar").prop('disabled', false);
            return false;
        }


        if ($("#estado_id").val() == '') {

            Utilities.alertModal('<h4>Debe seleccionar el estado</h4>', 'warning');

            Utilities.hiddePreloader();
            $(this).prop('disabled', true);
            $("#guardar").prop('disabled', false);
            return false;
        }


        if ($("#ciudad_id").val() == '') {

            Utilities.alertModal('<h4>Debe seleccionar la ciudad</h4>', 'warning');

            Utilities.hiddePreloader();
            $(this).prop('disabled', true);
            $("#guardar").prop('disabled', false);
            return false;
        }

        var ajaxreturn = ClienteService.save($("#formagregar").serialize());


        ajaxreturn.success(function (data) {

            if (data.success != undefined) {

                Utilities.hiddePreloader();
                $('#agregar').modal('hide');
                setTimeout(function () {
                    if ($('#agregarclienteventa').length == 0) {

                        var ajaxgrupo = Cliente.ajaxgrupo();
                        ajaxgrupo.success(function (data2) {
                            $('#page-content').html(data2);


                            $("#successspan").html(data.success);
                            $("#success").css('display', 'block');

                        });

                    }
                    else {

                        $('#agregarclienteventa').modal('hide');
                        var ajaxall = ClienteService.getAll();
                        ajaxall.success(function (data2) {

                            var newOption = "";
                            Venta.definirselectcliente();
                            jQuery.each(data2.clientes, function (i, value) {
                               newOption = new Option(value.nombres + ' ' + value.apellidos, value.id_cliente, false, false);
                                Venta.selectcliente.append(newOption);
                            });
                            Venta.selectcliente.val(data.id).trigger('change.select2');
                        })
                    }
                },1000);


            } else {
                Utilities.hiddePreloader();
                Utilities.alertModal(data.error, 'warning');
            }


        });
        ajaxreturn.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error', 'warning');
        });


    },

    validaVentacredito: function () {
        if ($("#valida_venta_credito").is(":checked")) {
            $(".validacredito").css("display", "block");
        } else {
            $(".validacredito").css("display", "none");
            $("#dias_credito").val("");
        }
    },
    events: function () {

        Cliente.validaVentacredito();
        $("#valida_venta_credito").on("change", function () {
            Cliente.validaVentacredito();
        })

        $(".chosen").chosen({
            width: "100%",
            search_contains: true
        });
        $("#fecha_nacimiento").datepicker();

        /*
        if ($('#latitud').val() == '0') {

            // (setTimeout(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (objPosition) {
                    var lon = objPosition.coords.longitude;
                    var lat = objPosition.coords.latitude;

                    console.log('lon',lon)
                    console.log('lat',lat)
                    $('#longitud').val(lon);
                    $('#latitud').val(lat);
                    console.log('objPosition',objPosition)
                   var mapa= $('#us2').locationpicker({
                        location: {latitude: lat, longitude: lon},
                        radius: 50,
                        inputBinding: {
                            latitudeInput: $('#latitud'),
                            longitudeInput: $('#longitud'),
                            locationNameInput: $('#location')
                        },
                        enableAutocomplete: true,
                        onchanged: function (currentLocation, radius, isMarkerDropped) {
                            (currentLocation.latitude + ", " + currentLocation.longitude);


                        }
                    });

                    console.log('mapa',mapa);
                }, function (objPositionError) {
                    switch (objPositionError.code) {
                        case objPositionError.PERMISSION_DENIED:
                            alert("No se ha permitido el acceso a la posición del usuario.");
                            break;
                        case objPositionError.POSITION_UNAVAILABLE:
                            alert("No se ha podido acceder a la información de su posición.");
                            break;
                        case objPositionError.TIMEOUT:
                            alert("El servicio ha tardado demasiado tiempo en responder.");
                            break;
                        default:
                            alert("Error desconocido.");
                    }
                }, {
                    maximumAge: 75000,
                    timeout: 15000
                });
            }
            else {
                alert("Su navegador no soporta la API de geolocalización.");
            }
            // })(), 5000);
        }
        else {
            $('#us2').locationpicker({
                location: {latitude: $('#latitud').val(), longitude: $('#longitud').val()},
                radius: 50,
                inputBinding: {
                    latitudeInput: $('#latitud'),
                    longitudeInput: $('#longitud'),
                    locationNameInput: $('#location')
                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    (currentLocation.latitude + ", " + currentLocation.longitude);

                }
            });

        }
        */
    },

    init: function () {
        Cliente.events();
    }
}



