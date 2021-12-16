var StatusCaja = {
    cache: {},


    apertura: function () {


    },


    guardar: function (operacion, form, modal) {


        StatusCaja.cache.submitaperuracaja.addClass('disabled');
        if ($("#caja_id").val() == '') {
            Utilities.alertModal('Debe seleccionar una caja');
            StatusCaja.cache.submitaperuracaja.removeClass('disabled');
            return false;
        }

        Utilities.showPreloader();
        var verySession = UtilitiesService.verySession();
        verySession.success(function (data) {
            if (data == "false") {	//if no errors            {
                Utilities.hiddePreloader();
                alert('El tiempo de su sessión ha expirado');
                location.href = baseurl + 'inicio';
            } else {
                console.log($("#frmAperturaCaja").serialize());
                var ajaxguardar = StatusCajaService.guardar($("#" + form).serialize());
                ajaxguardar.success(function (data) {
                    if (data.result !="error") {
                        var op = (operacion == "APERTURA") ? 'aperturada' : 'cerrada';

                        Utilities.alertModal(' La caja ha sido ' + op + ' <p> Cajero responsable: ' + data.cajeronombre + '</p>', 'success', true);
                        StatusCaja.cache.submitaperuracaja.removeClass('disabled');
                        $("#" + modal).modal('hide');
                        Utilities.hiddePreloader();
                        console.log(operacion);
                        if (operacion == 'APERTURA') {
                            $("#apertura_caja_link").css('display', 'none');
                            $("#apertura_caja_li").css('display', 'none');
                            $("#alertmoney").css('display', 'none');
                            $("#cierre_caja_link").css('display', 'block');
                            $("#cierre_caja_li").css('display', 'block');
                            $("#alertbottom2").fadeOut(350);
                        }
                        else {
                            $("#apertura_caja_link").css('display', 'block');
                            $("#apertura_caja_li").css('display', 'block');
                            $("#alertmoney").css('display', 'block');
                            $("#cierre_caja_link").css('display', 'none')
                            $("#cierre_caja_li").css('display', 'none');
                            $("#alertbottom2").fadeIn(350);

                            StatusCaja.imprimir(data.id);
                        }

                    }
                    else {
                        Utilities.hiddePreloader();
                        StatusCaja.cache.submitaperuracaja.removeClass('disabled');
                        Utilities.alertModal('<h4>Ha ocurrido un error </h4><p>' + data.msg + '</p>', 'danger', true)

                        return false;
                    }
                });
                ajaxguardar.error(function () {
                    Utilities.hiddePreloader();
                    StatusCaja.cache.submitaperuracaja.removeClass('disabled');
                    Utilities.alertModal('<h4>Ha ocurrido un error </h4> <p>Intente nuevamente</p>', 'danger', true)

                    return false;
                });
            }
        });
    },

    imprimir: function (id) {

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var EMPRESA_NOMBRE = $("#EMPRESA_NOMBRE").val();
        var REGIMEN_CONTRIBUTIVO = $("#REGIMEN_CONTRIBUTIVO").val();
        var EMPRESA_DIRECCION = $("#EMPRESA_DIRECCION").val();
        var EMPRESA_TELEFONO = $("#EMPRESA_TELEFONO").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var NIT = $("#NIT").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;
        if (is_nube) {

            $.ajax({
                url: baseurl + 'api/StatusCaja/data_print',
                type: 'GET',
                data: {id: id},
                success: function (data) {
                    var urltickera = TICKERA_URL;
                    var url = urltickera + '/directPrintCierre/';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            id: id,
                            cierrecaja:data.cierrecaja,
                            last_venta:data.last_venta,
                            first_venta : data.first_venta,
                            formaspago: data.formaspago,
                            calculodevoluciones: data.calculodevoluciones,
                            calculodevoluciones_credito: data.calculodevoluciones_credito,
                            credito: data.credito,
                            abonosacarteraresult: data.abonosacarteraresult,
                            calculoanulaciones: data.calculoanulaciones,
                            calculoanulaciones_credito: data.calculoanulaciones_credito,
                            baseurl:baseurl,
                            MENSAJE_FACTURA: MENSAJE_FACTURA,
                            MOSTRAR_PROSODE: MOSTRAR_PROSODE,
                            EMPRESA_NOMBRE: EMPRESA_NOMBRE,
                            REGIMEN_CONTRIBUTIVO: REGIMEN_CONTRIBUTIVO,
                            EMPRESA_DIRECCION: EMPRESA_DIRECCION,
                            EMPRESA_TELEFONO: EMPRESA_TELEFONO,
                            NIT: NIT,
                            impresora: IMPRESORA,
                            totalventascondescuento: data.totalventascondescuento
                        },
                        success: function (data) {
                            Utilities.alertModal(' <p>Se ha enviado el documento a la impresora</p>', 'success', true);

                        }, error: function () {
                            Utilities.alertModal('Ha ocurrido un error, por favor contacte con soporte', 'error', true);
                        }
                    });


                }, error: function () {

                }
            });

        } else {

            var url = baseurl + 'statusCaja/directPrintCierre/';
            $.ajax({
                url: url,
                type: 'POST',
                data: "id=" + id,
                success: function (data) {

                    Utilities.alertModal(' <p>Se ha enviado el documento a la impresora</p>', 'success', true);
                },
                error: function () {
                    Utilities.alertModal('Ha ocurrido un error, por favor contacte con soporte', 'error', true);
                }
            });
        }


    },

    preview: function (id) {


        var url = baseurl + 'statusCaja/preview/';
        $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            success: function (data) {
                $("#globalModal").html(data);
                $("#globalModal").modal('show');

            }
        });


    },

    events: function (cajasabiertas, aperturocaja, caja_id) {

        console.log(aperturocaja);

        if (cajasabiertas > 0 && aperturocaja == '' && caja_id == '') {
            $("#alertbottom").fadeToggle(350);

        }

        if (caja_id === '' && cajasabiertas <= 0) {

            $("#alertbottom2").fadeToggle(350);
        }

        $("#apertura_caja_link").on('click', function (e) {
            e.preventDefault();
            console.log('click');
            var ajaxCajaSession = StatusCajaService.getCajaSession();
            ajaxCajaSession.success(function (data) {
                if (data.result == Responses.OK) {
                    $.ajax({
                        type: 'POST',
                        data: $("#frmAperturaCaja").serialize(),
                        url: baseurl + StatusCajaService.urlController + '/apertura',
                        success: function (data) {

                            $("#apertura_caja").html(data);
                            $("#apertura_caja").modal('show');
                        }

                    });
                } else {
                    Utilities.alertModal(data.message, 'error', 70000);
                }
            });
            ajaxCajaSession.error(function () {
                Utilities.alertModal(Messages.GLOBAL_ERROR);
            });


        });


        $("#cierre_caja_link").on('click', function (e) {
            e.preventDefault();
            console.log('click');

            var ajaxCajasAbiertas = VentaService.getVentaByStatus('EN ESPERA', $("#GLOBAL_ID_LOCAL").val());
            ajaxCajasAbiertas.success(function (data) {

                if (data.ventas.length > 0) {

                    Utilities.alertModal(Messages.ALERT_CIERRE_CAJA_VENTAS_EN_ESPERA);
                    return false;
                } else {

                    var ajaxCajaSession = StatusCajaService.getCajaSession();
                    ajaxCajaSession.success(function (data) {

                        if (data.result == 'error') {
                            $.ajax({
                                type: 'POST',
                                data: $("#frmCierreCaja").serialize(),
                                url: baseurl + StatusCajaService.urlController + '/cierre',
                                success: function (data) {
                                    console.log('asdasd');
                                    $("#cierre_caja").html(data);
                                    $("#cierre_caja").modal('show');
                                }

                            });
                        } else {
                            Utilities.alertModal('Ya cerraste la caja desde otro computador, refresca para actualizar', 'error', 70000);
                        }
                    });
                }

            });

            ajaxCajasAbiertas.error(function () {
                Utilities.alertModal(Messages.GLOBAL_ERROR);
            });


        });

    },

    inizializeDomCache: function () {
        this.cache.submitaperuracaja = $('#submitaperuracaja');


    },
    selectCaja: function (id, caja_id) {
        $.ajax({
            type: 'POST',
            data: {id: id, caja_id: caja_id},
            url: baseurl + StatusCajaService.urlController + '/selectCaja',
            success: function (data) {
                Utilities.alertModal('Se ha seleccionado la caja ' + id, 'success');
                $("#alertmoney").css('display', 'none');
                $("#alertbottom").fadeToggle(350);
                $("#licaja_" + id).addClass('active');
                $("#licaja_" + id).removeAttr('onclick');

            }

        });

    },
    init: function (cajasabiertas, aperturocaja, caja_id) {


        this.events(cajasabiertas, aperturocaja, caja_id);
        this.inizializeDomCache();
    }
}



