var CuentasPorPagar = {
    metodos_pago: new Array(),
    facturas: new Array(),
    cache: {}, removeItem: function(arr, what) {
        var found = arr.indexOf(what);

        while (found !== -1) {
            arr.splice(found, 1);
            found = arr.indexOf(what);
        }
    },
    buscarRecibos: function () {
        Utilities.showPreloader();

        var busqueda = CuentasPorPagarService.buscarJson('getComprasCreditoPendienteJson', CuentasPorPagar.cache.frmBuscar.serialize());
        busqueda.success(function (data) {
            var html = '';
            var saldo = 0;
            jQuery.each(data, function (i, value){
                data[i]['proveedor_direccion']="";
                //[i].splice('proveedor_direccion',1);
                html += '<tr>';
                html += '<td><input type="checkbox" value="' + value.id_ingreso + '"  name="select_factura[]" class="select_factura"> ' + value.documento_numero + '</td>';
                html += '<td>' + value.monto_pendiente + '</td>';
                html += '<td>' + value.label_dias + '</td>';
                //html += '<td>';
              //  html += '<div class="btn-group"> <a class="btn btn-default btn-xs tip" title="Ver Venta" onclick="CuentasPorPagar.verVentaCredito(' + value.id_ingreso + ')" ><i class="fa fa-search"></i> </a></td>';
                html += '</tr>';
                saldo = parseFloat(value.monto_pendiente_nf) + saldo;
            });

            CuentasPorPagar.cache.tbody.html(html);
            CuentasPorPagar.cache.saldo.val(saldo);
            CuentasPorPagar.cache.monto_seleccionado.val(0);
            CuentasPorPagar.facturas = data;
            CuentasPorPagar.handleChekbox();
            Utilities.hiddePreloader();
        });

    },
	visualizar: function (id) {

		$.ajax({
			url: baseurl+'cartera/verVentaCredito',
			type: 'post',
			data: {'credito_id': id},
			success: function (data) {

				$("#globalModal").html(data);
				$('#globalModal').modal('show');
			}

		})
	},
    cerrar_visualizar: function () {
        CuentasPorPagar.cache.visualizar_cada_historial.modal('hide');
        CuentasPorPagar.buscarRecibos();

    },
    getNextRecibo: function(){
        var ajax = CuentasPorPagarService.getNextRecibo();
        ajax.success(function (data) {

            CuentasPorPagar.cache.id_recibo.val(data.recibo);
        });
    },
	//aparentemente no se usa
    visualizar_monto_abonado: function (id_historial, id_venta) {
        var ajax = CuentasPorPagarService.imprimirPagoPendiente({
            'id_venta': id_venta,
            'id_historial': id_historial
        });
        ajax.success(function (data) {
            CuentasPorPagar.cache.visualizar_cada_historial.html(data);
            CuentasPorPagar.cache.visualizar_cada_historial.modal('show');
        });
    },
    verVentaCredito: function (id) {
        var ajax = CuentasPorPagarService.verVentaCredito(id);
        ajax.success(function (data) {
            $('#mvisualizarVenta').html(data);
            $('#mvisualizarVenta').modal('show');
        });
    },
    guardarPago: function (total_ingreso, suma, id_ingreso) {

        var pago = {};
        if (CuentasPorPagar.cache.metodo.val() == "") {
            Utilities.alertModal('<h4>Debe seleccionar un metodo de pago</h4>', 'warning', true);
            return false;
        }
        /*if (CuentasPorPagar.cache.codigo_banco.val() == "") {
         Utilities.alertModal('<h4>Debe seleccionar un banco</h4>', 'warning', true);
         return false;
         }*/
        var cantidad_pagar = parseFloat(CuentasPorPagar.cache.valor_abonar.val());
        if (cantidad_pagar == '' || isNaN(cantidad_pagar)) {
            Utilities.alertModal('<h4>Ingrese una cantidad</h4>', 'warning', true);
            return false;
        }

        if (parseFloat(cantidad_pagar.toFixed(2)) > parseFloat(CuentasPorPagar.cache.monto_seleccionado.val())) {
            Utilities.alertModal('<h4>Debe ingresar un monto menor o igual al total de facturas seleccionadas</h4>', 'warning', true);
            return false;
        }

        if (cantidad_pagar <= 0) {
            Utilities.alertModal('<h4>Debe ingresar un monto mayor a 0</h4>', 'warning', true);
            return false;
        }
        pago.fecha_consignacion = $('#fecha_consignacion').val();
        pago.metodo = CuentasPorPagar.cache.metodo.val();
        pago.cuota = CuentasPorPagar.cache.valor_abonar.val();
        pago.usuario = currentuser;
        pago.banco = CuentasPorPagar.cache.codigo_banco.val();
        pago.observaciones_adicionales = CuentasPorPagar.cache.observaciones_adicionales.val();
        var lista_factura_enviar = new Array();
        jQuery.each($(".select_factura:checked"), function (i, value) {

            jQuery.each(CuentasPorPagar.facturas, function (j, factura) {
                if (factura.id_ingreso == value.value) {
                    lista_factura_enviar.push(factura);
                }
            });
        });
        pago.lst_factura = lista_factura_enviar;
        var miJSON = JSON.stringify(pago);
        CuentasPorPagar.cache.guardarPago.addClass('disabled');
        Utilities.showPreloader();
        var verySession = UtilitiesService.verySession();
        verySession.success(function (data) {
            if (data == "false") {	//if no errors            {
                Utilities.hiddePreloader();
                alert('El tiempo de su sessión ha expirado');
                location.href = baseurl + 'inicio';
            } else {
                var ajaxguardar = CuentasPorPagarService.guardarPago(miJSON);
                ajaxguardar.success(function (data) {
                    if (data.success == 'success' && data.error == undefined) {
                        var imprimir = CuentasPorPagarService.imprimirPagoPendiente({
                            'ingreso_id': data.ingreso_id,
                            'id_historial': data.id_historial
                        });
                        imprimir.success(function (data2) {
                            $('#mvisualizarVenta').modal('hide');
                            CuentasPorPagar.cache.visualizar_cada_historial.html(data2);
                            CuentasPorPagar.cache.visualizar_cada_historial.modal('show');
                            CuentasPorPagar.buscarRecibos();

                        });
                        imprimir.error(function (error) {
                            CuentasPorPagar.cache.guardarPago.removeClass('disabled');
                            Utilities.alertModal('<h4>Ha ocurrido un error </h4>', 'danger', true)
                            $('#mvisualizarVenta').modal('hide');
                        });
                        CuentasPorPagar.cache.guardarPago.removeClass('disabled');
                        CuentasPorPagar.cache.valor_abonar.val(0);
                        CuentasPorPagar.cache.metodo.val('').trigger("chosen:updated");
                        CuentasPorPagar.cache.codigo_banco.val('').trigger("chosen:updated");
                        CuentasPorPagar.cache.saldo.val(0);
                        CuentasPorPagar.cache.numero_documento.val('');
                        CuentasPorPagar.cache.fecha_consignacion.val('');
                        CuentasPorPagar.cache.observaciones_adicionales.val('');
                        CuentasPorPagar.cache.monto_seleccionado.val(0);
                        CuentasPorPagar.getNextRecibo();
                        Utilities.hiddePreloader();
                    }
                    else {
                        Utilities.hiddePreloader();
                        CuentasPorPagar.cache.guardarPago.removeClass('disabled');
                        Utilities.alertModal('<h4>Ha ocurrido un error </h4><p>' + data.error + '</p>', 'danger', true)
                        $('#mvisualizarVenta').modal('hide');
                        return false;
                    }
                });
                ajaxguardar.error(function () {
                    Utilities.hiddePreloader();
                    CuentasPorPagar.cache.guardarPago.removeClass('disabled');
                    Utilities.alertModal('<h4>Ha ocurrido un error </h4> <p>Intente nuevamente</p>', 'danger', true)
                    $('#mvisualizarVenta').modal('hide');
                    return false;
                });
            }
        });
    },
    events: function () {

    },
    handleChekbox: function () {
        $('.select_factura').on('change', function () { // on change of state
            var valselected = this.value;
            var objectslected = {};
            jQuery.each(CuentasPorPagar.facturas, function (i, value) {
                if (value.id_ingreso == valselected) {
                    objectslected = value;
                }
            });
            if (this.checked) // if changed state is "CHECKED"
            {

                var newval = parseFloat(CuentasPorPagar.cache.monto_seleccionado.val()) + parseFloat(objectslected.monto_pendiente_nf);
            } else {
                var newval = parseFloat(CuentasPorPagar.cache.monto_seleccionado.val()) - parseFloat(objectslected.monto_pendiente_nf);
            }

            CuentasPorPagar.cache.monto_seleccionado.val(newval.toFixed(2));
        });
    },
    toogleBanco: function () {
        var selectedMetodo = {};
        console.log(CuentasPorPagar.metodos_pago);
        jQuery.each(CuentasPorPagar.metodos_pago, function (i, value) {
            if (value.id_metodo == CuentasPorPagar.cache.metodo.val()) {
                selectedMetodo = value;
                console.log(selectedMetodo.centros_bancos);
                if (selectedMetodo.centros_bancos == '1') {
                    CuentasPorPagar.cache.carterabanco.show();
                } else {
                    CuentasPorPagar.cache.carterabanco.hide();
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
        this.cache.visualizar_cada_historial = $('#visualizar_cada_historial');
        this.cache.mvisualizarVenta = $('#mvisualizarVenta');
        this.cache.guardarPago = $('#guardarPago');
        this.cache.id_recibo = $('#id_recibo');
        this.cache.numero_documento = $('#numero_documento');
        this.cache.fecha_consignacion = $('#fecha_consignacion');
    },
    init: function (metodos_pago) {
        this.metodos_pago = metodos_pago;
        this.events();
        this.inizializeDomCache();
    }
}



