var Cartera = {
    metodos_pago: new Array(),
    facturas: new Array(),
    cache: {},
    recibo: "", //recibo temporal luego de enviarse a imprimir
    TIPO_IMPRESION: '',
    IMPRESORA: '',
    USUARIO_IMPRESORA: '',
    /*Una de las veces cuando se ejecuta, es para buscar las facturas cuando se cambia el select de cliente*/
    buscarRecibos: function () {
        if (Cartera.cache.frmBuscar != undefined) {
            Utilities.showPreloader();
            var busqueda = CarteraService.buscarJson('getFacturasCreditoPendienteJson', Cartera.cache.frmBuscar.serialize());
            busqueda.success(function (data) {
                var html = '';
                var saldo = 0;
                jQuery.each(data, function (i, value) {

                   
                    var documento_numero = (value.documento_Numero == null && value.fe_numero == 0) ? 'SALDO INICIAL' : value.documento_Numero;
                    html += '<tr>';
                    if (value.fe_numero == 0 || value.fe_numero ==null || value.fe_numero ==''){
                        html += '<td><input type="checkbox" value="' + value.credito_id + '"  name="select_factura[]" class="select_factura"> '
                            + documento_numero + '</td>';
                    }
                    if (value.fe_numero != 0 && value.fe_numero !=null && value.fe_numero !=''){
                        console.log('value', value.fe_numero);
                        html += '<td><input type="checkbox" value="' + value.credito_id + '"  name="select_factura[]" class="select_factura"> <div class="alert alert-success">' + value.fe_prefijo +'-'+  value.fe_numero + '</div></td>';
                    }
                    html += '<td>' + value.monto_pendiente + '</td>';
                    html += '<td>' + value.label_dias + '</td>';
                    html += '<td>';
                    html += '<div class="btn-group"> <a class="btn btn-default btnguardarPago-xs tip" title="Ver Venta" onclick="Cartera.verVentaCredito(' + value.credito_id + ')" ><i class="fa fa-search"></i> </a></td>';
                    html += '</tr>';
                    saldo = parseFloat(value.monto_pendiente.toFixed(2)) + parseFloat(saldo.toFixed(2));
                });
                Cartera.cache.tbody.html(html);
                Cartera.cache.saldo.val(saldo.toFixed(2));
                Cartera.cache.monto_seleccionado.val(0);
                Cartera.cache.observaciones_adicionales.val('');
                Cartera.facturas = data;
                Cartera.handleChekbox();
                Utilities.hiddePreloader();
            });
        }
    },

    cerrar_visualizar: function () {
        Cartera.cache.visualizar_cada_historial.modal('hide');
        Cartera.buscarRecibos();

    },
    getNextRecibo: function () {
        var ajax = CarteraService.getNextRecibo();
        ajax.success(function (data) {

            Cartera.cache.id_recibo.val(data.recibo);
        });
    },

    visualizar_monto_abonado: function (id_historial, id_venta) {
        var ajax = CarteraService.imprimirPagoPendiente({
            'id_venta': id_venta,
            'id_historial': id_historial
        });
        ajax.success(function (data) {
            Cartera.cache.visualizar_cada_historial.html(data);
            Cartera.cache.visualizar_cada_historial.modal('show');
        });
    },

    visualizar: function (id) {

        $.ajax({
            url: baseurl + 'cartera/verVentaCredito',
            type: 'post',
            data: { 'credito_id': id },
            success: function (data) {

                $("#globalModal").html(data);
                $('#globalModal').modal('show');
            }

        })
    },

    modalObservaciones: function (observacion) {

        $('#p_observacion').html('')
        $('#p_observacion').html(observacion)
        $('#modal_mostrar_observacion').modal('show');
        $('#modal_mostrar_observacion').css('z-index', '1500');
    },
    verVentaCredito: function (id) {
        var ajax = CarteraService.verVentaCredito(id);
        ajax.success(function (data) {
            Cartera.cache.mvisualizarVenta.html(data);
            Cartera.cache.mvisualizarVenta.modal('show');
        });
    },
    guardarPago: function (imprimir) {
        $('.btn_imprimir_recibo').prop('disabled', true);

        Cartera.recibo = "";
        var pago = {};
        if (Cartera.cache.metodo.val() == "") {
            Utilities.alertModal('<h4>Debe seleccionar un metodo de pago</h4>', 'warning', true);
            $('.btn_imprimir_recibo').prop('disabled', false);
            return false;
        }
        /*if (Cartera.cache.codigo_banco.val() == "") {
         Utilities.alertModal('<h4>Debe seleccionar un banco</h4>', 'warning', true);
         return false;
         }*/
        var cantidad_pagar = parseFloat(Cartera.cache.valor_abonar.val());
        console.log(cantidad_pagar);
        if (cantidad_pagar == '' || isNaN(cantidad_pagar)) {
            Utilities.alertModal('<h4>Ingrese una cantidad</h4>', 'warning', true);
            $('.btn_imprimir_recibo').prop('disabled', false);
            return false;
        }
        if (cantidad_pagar > (Cartera.cache.monto_seleccionado.val())) {
            Utilities.alertModal('<h4>Debe ingresar un monto menor o igual al total de facturas seleccionadas</h4>', 'warning', true);
            $('.btn_imprimir_recibo').prop('disabled', false);
            return false;
        }

        if (cantidad_pagar <= 0) {
            Utilities.alertModal('<h4>Debe ingresar un monto mayor a 0</h4>', 'warning', true);
            $('.btn_imprimir_recibo').prop('disabled', false);
            return false;
        }



        pago.metodo = Cartera.cache.metodo.val();
        pago.cuota = Cartera.cache.valor_abonar.val();
        pago.usuario = currentuser;
        pago.banco = Cartera.cache.codigo_banco.val();
        pago.observaciones_adicionales = Cartera.cache.observaciones_adicionales.val();
        var lista_factura_enviar = new Array();
        jQuery.each($(".select_factura:checked"), function (i, value) {

            jQuery.each(Cartera.facturas, function (j, factura) {
                if (factura.credito_id == value.value) {
                    lista_factura_enviar.push(factura);
                }
            });
        });
        pago.lst_factura = lista_factura_enviar;
        var miJSON = JSON.stringify(pago);
        Cartera.cache.guardarPago.addClass('disabled');
        Utilities.showPreloader();


        var verySession = UtilitiesService.verySession();
        verySession.success(function (data) {
            if (data == "false") {	//if no errors            {
                Utilities.hiddePreloader();
                alert('El tiempo de su sessión ha expirado');
                location.href = baseurl + 'inicio';
            } else {
                var ajaxguardar = CarteraService.guardarPago(miJSON);
                ajaxguardar.success(function (data) {
                    if (data.success == 'success' && data.error == undefined) {


                        if (imprimir) {


                            Cartera.recibo = data.recibo;
                            Cartera.cargaData_Impresion(data.recibo);


                        } else {

                            $('#generarventa').modal('hide');

                            Cartera.cache.mvisualizarVenta.modal('hide');
                            Cartera.cache.guardarPago.removeClass('disabled');


                            Cartera.buscarRecibos();
                            Utilities.alertModal('El recibo ha sido generado', 'success');
                        }


                        /* var imprimir = CarteraService.imprimirPagoPendiente({
                         'id_venta': data.id_venta,
                         'id_historial': data.id_historial
                         });*/

                        Cartera.cache.guardarPago.removeClass('disabled');
                        Cartera.cache.valor_abonar.val(0);
                        Cartera.cache.metodo.val('').trigger("chosen:updated");
                        Cartera.cache.codigo_banco.val('').trigger("chosen:updated");
                        Cartera.cache.saldo.val(0);
                        Cartera.cache.numero_documento.val('');
                        Cartera.cache.fecha_consignacion.val('');
                        Cartera.cache.observaciones_adicionales.val('');
                        Cartera.cache.monto_seleccionado.val(0);
                        Cartera.getNextRecibo();
                        Utilities.hiddePreloader();
                    } else {
                        Utilities.hiddePreloader();
                        Cartera.cache.guardarPago.removeClass('disabled');
                        Utilities.alertModal('<h4>Ha ocurrido un error </h4><p>' + data.error + '</p>', 'danger', true)
                        Cartera.cache.mvisualizarVenta.modal('hide');
                        return false;
                    }
                    $('.btn_imprimir_recibo').prop('disabled', false);
                });
                ajaxguardar.error(function () {

                    Utilities.hiddePreloader();
                    Cartera.cache.guardarPago.removeClass('disabled');
                    Utilities.alertModal('<h4>Ha ocurrido un error </h4> <p>Intente nuevamente</p>', 'danger', true)
                    Cartera.cahce.mvisualizarVenta.modal('hide');
                    $('.btn_imprimir_recibo').prop('disabled', false);
                    return false;
                });
            }
        });
    },
    events: function () {

    },

    /*ingresa aqui cuando se confirma que se quiere una copia*/
    imprimircopia: function () {

        Cartera.cargaData_Impresion(Cartera.recibo);

    },
    cargaData_Impresion: function (recibo) {

        var is_nube = this.TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        if (is_nube) {

            /**
             * busco la data que se va a imprimir, para enviarla a la tiquetera local
             * @type {string}
             */
            var url = baseurl + 'cartera/getDataToPrintCarteraTiqLocal/';
            $.ajax({
                url: url,
                type: 'POST',
                data: { recibo: recibo },
                success: function (datatosend) {

                    datatosend = JSON.parse(datatosend)
                    url = Cartera.USUARIO_IMPRESORA + '/directPrintCartera/';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            cartera: datatosend,
                            IMPRESORA: Cartera.IMPRESORA
                        },
                        success: function (data) {
                            Cartera.cache.mvisualizarVenta.modal('hide');
                            Cartera.cache.visualizar_cada_historial.html(data);
                            $('#generarventa').modal('hide');
                            Cartera.buscarRecibos();
                            Utilities.alertModal('La factura se ha enviado a la impresora', 'success');
                            $("#mCopiaReciboCartera").modal('show');

                        }, error: function () {
                            Cartera.cache.mvisualizarVenta.modal('hide');
                            Cartera.cache.guardarPago.removeClass('disabled');
                            $('#generarventa').modal('hide');

                            Cartera.buscarRecibos();
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                        }
                    });

                }, error: function () {
                    Cartera.cache.mvisualizarVenta.modal('hide');
                    Cartera.cache.guardarPago.removeClass('disabled');
                    $('#generarventa').modal('hide');

                    Cartera.buscarRecibos();
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });

        } else {


            var url = baseurl + 'cartera/directPrint/';
            $.ajax({
                url: url,
                type: 'POST',
                data: { recibo: recibo },
                success: function (data) {


                    Cartera.cache.mvisualizarVenta.modal('hide');
                    Cartera.cache.visualizar_cada_historial.html(data);
                    $('#generarventa').modal('hide');
                    Cartera.buscarRecibos();
                    Utilities.alertModal('La factura se ha enviado a la impresora', 'success');
                    $("#mCopiaReciboCartera").modal('show');

                }, error: function () {
                    Cartera.cache.mvisualizarVenta.modal('hide');
                    Cartera.cache.guardarPago.removeClass('disabled');
                    $('#generarventa').modal('hide');

                    Cartera.buscarRecibos();
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });

        }


    },

    confirmAnularRecibo: function (recibo, clave_maestra, clave_maestra_anular_cartera) {
        $('#globalModal').modal('hide');
        if (clave_maestra_anular_cartera) {
            swal({
                title: 'Ingresa la clave maestra',
                text: "Para poder anular el recibo",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: 'Clave maestra'
            }, function (inputValue) {
                if (inputValue === false) {
                    swal.showInputError("Por favor ingrese la clave maestra!");
                    return false
                }
                if (inputValue === "") {
                    swal.showInputError("Por favor ingrese la clave maestra!");
                    return false
                }
                if (inputValue != clave_maestra) {
                    swal.showInputError("La clave maestra es incorrecta");
                    return false
                }

                Cartera.anularRecibo(recibo);
            });


        } else {
            swal({
                title: 'Estás seguro de anular el recibo?',
                text: "Esta acción no se podrá revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, anularlo!',
                cancelButtonText: 'No, cancelar'
            }, function (isConfirm) {
                if (isConfirm) {
                    Cartera.anularRecibo(recibo);


                }
            });

        }


    },
    anularRecibo: function (recibo) {


        var url = baseurl + 'cartera/anularRecibo/';
        $.ajax({
            url: url,
            type: 'POST',
            data: { recibo: recibo },
            dataType: 'json',
            success: function (data) {


                console.log(data);
                if (data.success != undefined) {
                    $('#generarventa').modal('hide');
                    swal.close();
                    Cartera.buscarRecibos();
                    Utilities.alertModal('Se ha anulado el recibo', 'success');
                    $("#mCopiaReciboCartera").modal('show');
                } else {
                    Cartera.cache.mvisualizarVenta.modal('hide');
                    Cartera.cache.guardarPago.removeClass('disabled');
                    $('#generarventa').modal('hide');

                    Cartera.buscarRecibos();
                    Utilities.alertModal(data.error);
                }

            }, error: function () {
                Cartera.cache.mvisualizarVenta.modal('hide');
                Cartera.cache.guardarPago.removeClass('disabled');
                $('#generarventa').modal('hide');

                Cartera.buscarRecibos();
                Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
            }
        });


    },


    handleChekbox: function () {
        $('.select_factura').on('change', function () { // on change of state
            var valselected = this.value;
            var objectslected = {};
            jQuery.each(Cartera.facturas, function (i, value) {
                if (value.credito_id == valselected) {
                    objectslected = value;
                }
            });
            if (this.checked) // if changed state is "CHECKED"
            {
                console.log(Cartera.cache.monto_seleccionado.val());
                console.log(objectslected.monto_pendiente);
                var newval = parseFloat(Cartera.cache.monto_seleccionado.val()) + parseFloat(objectslected.monto_pendiente);
            } else {
                var newval = parseFloat(Cartera.cache.monto_seleccionado.val()) - parseFloat(objectslected.monto_pendiente);
            }
            Cartera.cache.monto_seleccionado.val(newval.toFixed(2));
        });
    },
    toogleBanco: function () {
        var selectedMetodo = {};
        console.log(Cartera.metodos_pago);
        jQuery.each(Cartera.metodos_pago, function (i, value) {
            if (value.id_metodo == Cartera.cache.metodo.val()) {
                selectedMetodo = value;
                console.log(selectedMetodo.centros_bancos);
                if (selectedMetodo.centros_bancos == '1') {
                    Cartera.cache.carterabanco.show();
                } else {
                    Cartera.cache.carterabanco.hide();
                }
            }
        });
    },

    inizializeDomCache: function () {
        this.cache.monto_seleccionado = $('#monto_seleccionado');
        this.cache.metodo = $('#metodo');
        this.cache.codigo_banco = $('#codigo_banco');
        this.cache.observaciones_adicionales = $('#observaciones_adicionales');
        this.cache.carterabanco = $('.carterabanco');
        this.cache.select_factura = $('.select_factura');
        this.cache.saldo = $('#saldo');
        this.cache.frmBuscar = $('#frmBuscar');
        this.cache.valor_abonar = $('#valor_abonar');
        this.cache.tbody = $('#lstPagP tbody');
        this.cache.select_factura = $('.select_factura');
        this.cache.visualizar_cada_historial = $('#visualizar_cada_historial');
        this.cache.mvisualizarVenta = $('#mvisualizarVenta');
        this.cache.guardarPago = $('#guardarPago');
        this.cache.id_recibo = $('#id_recibo');
        this.cache.numero_documento = $('#numero_documento');
        this.cache.fecha_consignacion = $('#fecha_consignacion');
    },
    init: function (metodos_pago, TIPO_IMPRESION, IMPRESORA, USUARIO_IMPRESORA) {
        this.metodos_pago = metodos_pago;
        this.TIPO_IMPRESION = TIPO_IMPRESION;
        this.IMPRESORA = IMPRESORA;
        this.USUARIO_IMPRESORA = USUARIO_IMPRESORA;
        this.events();
        this.inizializeDomCache();
    }
}



