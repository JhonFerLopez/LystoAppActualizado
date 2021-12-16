var Venta = {
    unidades: new Array(),
    admite_datos_cliente: 1,
    droguerias: new Array(),
    edicion: 0,
    tipos_devlucion: new Array(),
    tipos_venta: new Array(),
    lst_producto: new Array(),
    invoice_lines: new Array(),
    lstaeliminar: new Array(),
    clientes: new Array(),
    last_factura: '',
    afiliado_descuentos: new Array(),
    cliente: new Array(),
    total: 0,
    tablalistaventa: null,
    devolver: '',
    notadebito: false,
    zipkey: '',
    uuid: '',
    XmlFileName: '',
    fe_numero: '',
    fe_status: 'REGISTRADO',
    fe_reponseDian: '',
    fe_transactionDian: '',
    fe_resolution_id: '',
    fe_type_document: '',
    fe_prefijo: '',
    fe_issue_date: '',
    countproducto: 0,
    FACT_E_habilitacionn: 0,
    FACT_E_syncrono: 0,
    tablaproductos: new Array(),
    manejaformaspago: false,
    submited: false,
    dom: {},
    preciocero: false,
    tablaDomicilios: new Array(),
    tablaHistorial: new Array(),
    datossesion: new Array(),//para guardar de forma temporal los datos de sesion
    domicilioselected: "",//cuandos e selecciona en la lista de domicilios, a un domicilio
    mapaDomicilios: "",
    markersMapa: "",
    estatusasignado: "",
    toastCliSelected: "", //es el mensaje de alerta(toast) que se ejecuta cuando se selecciona un cliente
    selectcliente: "",
    cache: function (selector) {
        if (undefined === this.dom[selector])
            this.dom[selector] = $(selector);

        return this.dom[selector];
    },


    resetFields: function () {
        Venta.notadebito = false;
        Venta.zipkey = '';
        Venta.uuid = '';
        Venta.XmlFileName = '';
        Venta.fe_numero = '';
        fe_status = 'REGISTRADO';
        Venta.fe_reponseDian = '';
        Venta.fe_transactionDian = '';
        Venta.fe_resolution_id = '';
        Venta.fe_type_document = '';
        Venta.fe_prefijo = '';
        Venta.fe_issue_date = '';

    },

    loadVentaWindows: function (urlRefresh) {

        $.ajax({
            url: baseurl + 'venta/index',
            success: function (datat) {

                if ($("#ventamodal").length > 0) {
                    $("#ventamodal").on("hidden.bs.modal", function () {
                        $('#page-content').html(datat);
                    });
                    $("#ventamodal").modal('hide');

                    $('#page-content').html(datat);

                } else {
                    $('#page-content').html(datat);
                }


            }
        });
    },
    cargaData_Impresion: function (idventa, id_devolucion, from_historial = 0) {


        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var ABRIR_CAJA_REGISTRADORA = $("#ABRIR_CAJA_REGISTRADORA").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        if (is_nube) {


            if (id_devolucion != undefined && id_devolucion != '') {
                Venta.printDevolucion(id_devolucion, idventa);
            } else {
                $.ajax({
                    url: baseurl + 'api/Venta/get_data_for_cloud_print',
                    type: 'POST',
                    data: { idventa: idventa },
                    success: function (data) {
                        var urltickera = TICKERA_URL;
                        //  var url = baseurl + 'venta/directPrint/' + id_venta;
                        var url = urltickera + '/directPrint/' + idventa;


                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                idventa: idventa,
                                ventas: data.ventas,
                                impuestos: data.impuestos,
                                devolucion: Venta.devolver,
                                id_devolucion: id_devolucion,
                                impresora: IMPRESORA,
                                VENDEDOR_EN_FACTURA: $("#VENDEDOR_EN_FACTURA").val(),
                                MENSAJE_FACTURA: MENSAJE_FACTURA,
                                MOSTRAR_PROSODE: MOSTRAR_PROSODE,
                                ABRIR_CAJA_REGISTRADORA: ABRIR_CAJA_REGISTRADORA,
                                from_historial: from_historial
                            },
                            success: function (data) {
                                Utilities.alertModal('La factura se ha enviado a la impresora', 'success');

                            }, error: function () {
                                Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                            }
                        });


                    }, error: function () {

                    }
                });
            }

        } else {

            var url = baseurl + 'venta/directPrint/' + idventa;


            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    idventa: idventa,
                    devolucion: Venta.devolver,
                    VENDEDOR_EN_FACTURA: $("#VENDEDOR_EN_FACTURA").val(),
                    id_devolucion: id_devolucion,
                    from_historial: from_historial
                },
                success: function (data) {
                    Utilities.alertModal('La factura se ha enviado a la impresora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });

        }


    },

    abrirCajaRegistradora: function (idventa, id_devolucion, from_historial = 0) {


        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var ABRIR_CAJA_REGISTRADORA = $("#ABRIR_CAJA_REGISTRADORA").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        if (is_nube) {


            var urltickera = TICKERA_URL;

            var url = urltickera + '/abrirCajaregistradora';


            $.ajax({
                url: url,
                type: 'POST',
                data: {

                    impresora: IMPRESORA,
                    MENSAJE_FACTURA: MENSAJE_FACTURA,
                    MOSTRAR_PROSODE: MOSTRAR_PROSODE,
                    ABRIR_CAJA_REGISTRADORA: ABRIR_CAJA_REGISTRADORA,
                },
                success: function (data) {
                    Utilities.alertModal('Se ha abierto la caja registradora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido abrir la caja registradora, contacte con soporte');
                }
            });



        } else {

            var url = baseurl + 'venta/abrirCajaregistradora/' + idventa;


            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    idventa: idventa,
                    devolucion: Venta.devolver,
                    id_devolucion: id_devolucion,
                    from_historial: from_historial
                },
                success: function (data) {
                    Utilities.alertModal('Se ha abierto la caja registradora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido abrir la caja registadora, contacte con soporte');
                }
            });

        }


    },


    verVenta: function (id_venta) {


        return $.ajax({
            url: baseurl + 'venta/verVenta',
            type: 'POST',
            data: "idventa=" + id_venta,
            success: function (data) {
                $("#mvisualizarVenta").html(data);
                $("#mvisualizarVenta").modal('show');
            }
        });
    },


    verDevolucion: function (id_devolucion, id_venta) {


        return $.ajax({
            url: baseurl + 'venta/verDevolucion',
            type: 'POST',
            data: { idventa: id_venta, id_devolucion: id_devolucion },
            success: function (data) {
                $("#mvisualizarVenta").html(data);
                $("#mvisualizarVenta").modal('show');
            }
        });
    },

    printDevolucion: function (id_devolucion, id_venta) {

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        if (is_nube) {

            $.ajax({
                url: baseurl + 'api/Venta/get_data_for_cloud_print',
                type: 'POST',
                data: { idventa: id_venta, id_devolucion: id_devolucion },
                success: function (data) {
                    var urltickera = TICKERA_URL;
                    //  var url = baseurl + 'venta/directPrint/' + id_venta;
                    var url = urltickera + '/directPrintDevolucion';


                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            idventa: id_venta,
                            detalle_devolucion: data.detalle_devolucion,
                            ventas: data.ventas,
                            impuestos: data.impuestos,
                            devolucion: Venta.devolver,
                            id_devolucion: id_devolucion,
                            impresora: IMPRESORA,
                            MENSAJE_FACTURA: MENSAJE_FACTURA,
                            MOSTRAR_PROSODE: MOSTRAR_PROSODE
                        },
                        success: function (data) {
                            Utilities.alertModal('La factura se ha enviado a la impresora', 'success');

                        }, error: function () {
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                        }
                    });


                }, error: function () {

                }
            });

        } else {


            var url = baseurl + 'venta/directPrintDevolucion/';
            $.ajax({
                url: url,
                type: 'POST',
                data: { idventa: id_venta, id_devolucion: id_devolucion },
                success: function (data) {
                    Utilities.alertModal('La factura se ha enviado a la impresora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });
        }

    },
    get_ventas: function () {
        Utilities.showPreloader();
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var locales = $("#locales").val();
        var estatus = $("#estatus").val();
        var listar = $("#listar").val();
        var id_cliente = $("#id_cliente").val();
        var id_departamento = $('#id_departamento').val();
        var id_ciudad = $('#id_ciudad').val();
        var id_barrio = $('#id_barrio').val();

        $.ajax({
            url: baseurl + 'venta/get_ventas',
            data: {
                'id_local': locales,
                'desde': fercha_desde,
                'hasta': fercha_hasta,
                'estatus': estatus,
                'listar': listar,
                'id_cliente': id_cliente,
                'id_departamento': id_departamento,
                'id_ciudad': id_ciudad,
                'id_barrio': id_barrio,
            },
            type: 'POST',
            success: function (data) {
                Utilities.hiddePreloader();
                if (data.length > 0) {
                    $("#tabla").html(data);
                }
                $("#tablaresult").dataTable();
            },
            error: function () {
                Utilities.hiddePreloader();
                Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
            }
        })
    },
    get_devoluciones: function () {
        Utilities.showPreloader();
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var locales = $("#locales").val();
        var estatus = $("#estatus").val();
        var listar = $("#listar").val();

        $.ajax({
            url: baseurl + 'venta/get_devoluciones',
            data: {
                'id_local': locales,
                'desde': fercha_desde,
                'hasta': fercha_hasta,
                'estatus': estatus,
                'listar': listar
            },
            type: 'POST',
            success: function (data) {
                Utilities.hiddePreloader();
                if (data.length > 0) {
                    $("#tabla").html(data);
                }
                $("#tablaresult").dataTable();
            },
            error: function () {
                Utilities.hiddePreloader();
                Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
            }
        })
    },
    deleteVenta: function (id_venta) {


        Utilities.showPreloader();
        return $.ajax({
            url: baseurl + 'venta/deleteVenta',
            type: 'POST',
            data: "idventa=" + id_venta,
            success: function (data) {
                Utilities.hiddePreloader();
                if (data.result = 'success') {
                    Utilities.alertModal('Le venta ' + id_venta + ' ha sido eliminada ', 'success');
                    Venta.get_ventas();
                } else {
                    Utilities.alertModal('Ha ocurrido un error, por favor intente nuevamente');
                }

            }, error: function (e) {
                console.log(e);
                Utilities.hiddePreloader();
                Utilities.alertModal('Ha ocurrido un error, por favor intente nuevamente');
            }
        });
    },

    cargaData_DocumentoFiscal: function (id_venta) {
        return $.ajax({
            url: baseurl + 'venta/verDocumentoFisal',
            type: 'POST',
            data: "idventa=" + id_venta,
            success: function (data) {
                $("#mvisualizarVenta").html(data);
                $("#mvisualizarVenta").modal('show');
            }
        });
    },

    devolverventa: function (id, notadebito) {

        return $.ajax({
            url: baseurl + 'venta',
            data: { 'idventa': id, 'devolver': 1, notadebito: notadebito },
            type: 'post',
            success: function (data) {
                $("#page-content").html(data);
            },

            error: function (error) {
                console.log(error);
            }
        });


    },

    setFocusOnfirstrow: function (json) {

        if ($(".ui-selected").length == 0) {

            Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(0)"));
        }


        return true;
    },
    buscarproductos: function (valor) {
        Utilities.showPreloader();
        var table = $('#tablaproductos').DataTable();
        table.destroy();

        $("#productostbody").html('');
        Utilities.hiddePreloader();
        var MOSTRAR_SIN_STOCK = parseFloat($('#MOSTRAR_SIN_STOCK').val());
        var VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS = parseFloat($('#VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS').val());
        Venta.tablaproductos = TablesDatatablesLazzy.init(baseurl + 'api/productos/specialSearchLazzy', 0, 'tablaproductos',
            {
                local: $("#idlocal").val(),
                constock: MOSTRAR_SIN_STOCK == 1 ? false : true,
                operacion: 'VENTA',
                'search': valor
            },
            '',
            Venta.setFocusOnfirstrow,
            false,
            false,
            [],
            VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS ? true : false,
            true,
            '50vh'
        );


        // $("#tablaproductos_filter input").val(valor);



        $("#seleccionunidades").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });

    },

    validateDiscount: function (event, product_id) {

        var total = $("#totalprod_" + product_id).val();


        if (parseFloat(total) < 0) {
            alert();
            return false;

        }


    },

    blocktheother: function (inputtoblock, product_id) {


        $("#descuentoenvalor").val(0);
        $("#descuentoenporcentaje").val(0);
        $("#descuentoenvalorhidden").val(0);
        $("#descuentoenporcentajehidden").val(0);


        if (inputtoblock == 1) {
            $("#desc_por_" + product_id).val(0);
        } else {
            $("#desc_val_" + product_id).val(0);
        }
    },

    changeCliente: function () {
        var id = $("#id_cliente").val();
        var ajaxc = ClienteService.get(id);
        ajaxc.success(function (data) {

            if (data.cliente != undefined) {

                $("#afiliado").val(data.cliente.afiliado);
                Venta.afiliado_descuentos = data.cliente.afiliado_descuentos;
                Venta.cliente = data.cliente;

                jQuery.each(Venta.lst_producto, function (i, value) {
                    Venta.calculatotales(value.id_producto, null);
                })

            }
        });
    },

    calculatotales: function (producto_id, unidad_id) {

        if (Venta.devolver == 'true') {
            var cantidad = $("#item_" + unidad_id + "_" + producto_id).val() == '' ? 0 : parseFloat($("#item_" + unidad_id + "_" + producto_id).val());
            var cantidad_dev = $("#item_dev_" + unidad_id + "_" + producto_id).val() == '' ? 0 : parseFloat($("#item_dev_" + unidad_id + "_" + producto_id).val());
            if (parseFloat(cantidad_dev) > parseFloat(cantidad) && Venta.notadebito == '0') {
                Utilities.alertModal('La cantidad a devolver no puede ser mayor a ' + cantidad, 'warning');
                $("#item_dev_" + unidad_id + "_" + producto_id).val(0);

            }
        }
        var prod;
        $.each(Venta.lst_producto, function (i, value) {

            if (value.id_producto == producto_id) {
                prod = value;
            }
        });


        var condicion_pago = null;

        jQuery.each(Venta.tipos_venta, function (i, value) {
            if (parseInt(value.tipo_venta_id) == parseInt($("#tipoventa").val())) {
                condicion_pago = value.condicion_pago;
            }
        });

        var afiliado = $("#afiliado").val();


        var precios = InventarioService.buscarExistenciayPrecios(producto_id, condicion_pago, prod.is_paquete);

        precios.success(function (data) {

            var cantidad = $("#item_" + unidad_id + "_" + producto_id).val() == '' ? 0 : parseFloat($("#item_" + unidad_id + "_" + producto_id).val());
            var cantidad_dev = $("#item_dev_" + unidad_id + "_" + producto_id).val() == '' ? 0 : parseFloat($("#item_dev_" + unidad_id + "_" + producto_id).val());

            if (isNaN(cantidad)) {
                cantidad = 0;
            }
            if (isNaN(cantidad_dev)) {
                cantidad_dev = 0;
            }

            /***************************************************/
            //A MEDIR STOCK SI ES PAQUETE
            /***************************************************/

            if (data.precios[0].is_paquete == '1') {

                var nombres_sin_stock = new Array(); //almacena los nombres de los productos
                //que conforman el paquete, que no tienen stock
                var cont_nombres_sin_stock = 0; //el comtador del anterior arreglo


                var haystock = true;
                /// aqui cada stockv es un producto del paquete
                jQuery.each(data.stock, function (prodi, prod) {
                    if (prod.control_inven == '1') {

                        // si el proudcto genera control de inventario entnces me toca alidar stock

                        var caja = prod.unidades_medida[0];
                        if (prod.unidades_medida.length === 3) {
                            var blister = prod.unidades_medida[1];
                            var unidad = prod.unidades_medida[2];
                        } else if (prod.unidades_medida.length == 2) {
                            var unidad = prod.unidades_medida[1];
                        }


                        //NECESITO CALCULAR CUANTAS UNIDADES MINIMAS POR CADA PRODUCTO DEL PAQUETE SE NECESITAN PARA SUPLIR LA CAJA
                        //MULTIPLICADO POR LA CANTIDAD DE CAJAS QUE ESTOY VENDIENDO
                        var totalunidadesminimasnecesito = 0;
                        jQuery.each(prod.unidades, function (unidadesi, unidadesv) {
                            var unidadesnecesito = parseFloat(unidadesv.cantidad);
                            var nuevo_total_minimas = 0;

                            //aqui suma a ese total minimas el total minimas de la cantidad qu estoy metiendo
                            if (unidadesv.unidad_id == caja.id_unidad) {
                                nuevo_total_minimas = parseFloat(nuevo_total_minimas) + (parseFloat(unidadesnecesito) * parseFloat(caja.unidades));
                            }
                            if (blister != undefined && unidadesv.unidad_id == blister.id_unidad) {
                                nuevo_total_minimas = parseFloat(nuevo_total_minimas) + (parseFloat(unidadesnecesito) * parseFloat(unidad.unidades));
                            }
                            if (unidad != undefined && unidadesv.unidad_id == unidad.id_unidad) {
                                nuevo_total_minimas = parseFloat(nuevo_total_minimas) + parseFloat(unidadesnecesito);

                            }
                            totalunidadesminimasnecesito = parseFloat(totalunidadesminimasnecesito) + parseFloat(nuevo_total_minimas);
                            totalunidadesminimasnecesito = totalunidadesminimasnecesito * cantidad;

                        });


                        //AHORA NECSITO CALCULAR CUANTAS UNIDADES MINIMAS DE ESE PRODUCTO TENGO EN STOCK
                        var totalunidadesminimasstock = 0;
                        jQuery.each(prod.existencia, function (unidadesi, unidadesv) {
                            var total_minimas_en_stok = 0;

                            if (unidadesv.id_unidad == caja.id_unidad) {

                                total_minimas_en_stok = parseFloat(total_minimas_en_stok) + (parseFloat(unidadesv.cantidad) * parseFloat(caja.unidades));
                            }
                            if (blister != undefined && unidadesv.id_unidad == blister.id_unidad) {
                                total_minimas_en_stok = parseFloat(total_minimas_en_stok) + (parseFloat(unidadesv.cantidad) * parseFloat(unidad.unidades));
                            }
                            if (unidad != undefined && unidadesv.id_unidad == unidad.id_unidad) {
                                total_minimas_en_stok = parseFloat(total_minimas_en_stok) + parseFloat(unidadesv.cantidad);
                            }
                            totalunidadesminimasstock = parseFloat(totalunidadesminimasstock) + parseFloat(total_minimas_en_stok);

                        });


                        if (parseFloat(totalunidadesminimasnecesito) > parseFloat(totalunidadesminimasstock)) {
                            //almaceno el nombre del producto sins tock
                            nombres_sin_stock[cont_nombres_sin_stock] = prod.producto_nombre;
                            cont_nombres_sin_stock++;
                            haystock = false;
                        }

                    }
                });


                if (haystock === false && (Venta.devolver != 'true' || Venta.notadebito == true)) {

                    //muestro los nombres en un alert
                    jQuery.each(nombres_sin_stock, function (key, indice) {
                        Utilities.alertModal('No hay stock suficiente para el producto ' + indice, 'warning', 6000);
                    })

                    $("#item_" + unidad_id + "_" + producto_id).val(0);
                    $("#subtotal_" + unidad_id + "_" + producto_id).val(0);
                } else {
                    Venta.totalesUpdate(unidad_id, data);
                }


            } else {

                if (data.precios[0].control_inven == '1') {

                    var haystock = true;

                    var caja = data.precios[0];
                    if (data.precios.length === 3) {
                        var blister = data.precios[1];
                        var unidad = data.precios[2];
                    } else if (data.precios.length == 2) {
                        var unidad = data.precios[1];
                    }
                    var total_unidades_minimas_en_venta = 0;


                    // aqui calculo el total unidades minimas que hay en total en la venta  del array , es decir lo que tengo en el arrey producto
                    //de la venta sin sumar la  cantidad que estoy metiendo nueva
                    jQuery.each(Venta.lst_producto, function (p, product) {
                        if (parseInt(product.id_producto) == parseInt(producto_id)) {

                            jQuery.each(product.unidades, function (u, unid) {
                                if (unidad_id != unid.id_unidad) { // solo hago esto si la unidad no es la unidad actual donde meti la cantidad,

                                    if (Venta.notadebito == true) {
                                        if (unid.cantidad_dev != undefined) {
                                            var cantidadenelarray = parseFloat(unid.cantidad_dev);
                                        } else {

                                            cantidadenelarray = 0;
                                        }
                                    } else {

                                        if (unid.cantidad != undefined) {
                                            var cantidadenelarray = parseFloat(unid.cantidad);
                                        } else {

                                            cantidadenelarray = 0;
                                        }
                                    }

                                    if (parseInt(unid.id_unidad) == parseInt(caja.id_unidad)) {

                                        total_unidades_minimas_en_venta = parseFloat(total_unidades_minimas_en_venta) + (parseFloat(cantidadenelarray) * parseFloat(caja.unidades));
                                    }

                                    if (blister != undefined && parseInt(unid.id_unidad) == parseInt(blister.id_unidad)) {

                                        total_unidades_minimas_en_venta = parseFloat(total_unidades_minimas_en_venta) + (parseFloat(cantidadenelarray) * parseFloat(unidad.unidades));
                                    }
                                    if (unidad != undefined && parseInt(unid.id_unidad) == parseInt(unidad.id_unidad)) {
                                        total_unidades_minimas_en_venta = parseFloat(total_unidades_minimas_en_venta) + parseFloat(cantidadenelarray);
                                    }
                                }
                            });
                        }
                    });


                    var nuevo_total_minimas = total_unidades_minimas_en_venta;


                    //aqui suma a ese total minimas el total minimas de la cantidad qu estoy metiendo
                    let cantidad_comparar;
                    if (Venta.notadebito === true) {
                        cantidad_comparar = cantidad_dev;
                    } else {
                        cantidad_comparar = cantidad;
                    }

                    if (unidad_id == caja.id_unidad) {
                        nuevo_total_minimas = parseFloat(nuevo_total_minimas) + (parseFloat(cantidad_comparar) * parseFloat(caja.unidades));
                    }
                    if (blister != undefined && unidad_id == blister.id_unidad) {
                        nuevo_total_minimas = parseFloat(nuevo_total_minimas) + (parseFloat(cantidad_comparar) * parseFloat(unidad.unidades));
                    }
                    if (unidad != undefined && unidad_id == unidad.id_unidad) {
                        nuevo_total_minimas = parseFloat(nuevo_total_minimas) + parseFloat(cantidad_comparar);
                    }


                    var total_minimas_en_stok = 0;


                    jQuery.each(data.stock, function (stocki, stock) {


                        if (stock.id_unidad == caja.id_unidad) {

                            total_minimas_en_stok = total_minimas_en_stok + (parseFloat(stock.cantidad) * parseFloat(caja.unidades));
                        }
                        if (blister != undefined && stock.id_unidad == blister.id_unidad) {
                            total_minimas_en_stok = total_minimas_en_stok + (parseFloat(stock.cantidad) * parseFloat(unidad.unidades));
                        }
                        if (unidad != undefined && stock.id_unidad == unidad.id_unidad) {
                            total_minimas_en_stok = parseFloat(total_minimas_en_stok) + parseFloat(stock.cantidad);
                        }

                    });


                    if (parseFloat(nuevo_total_minimas) > parseFloat(total_minimas_en_stok)) {
                        haystock = false;
                    }


                    if (haystock === false && (Venta.devolver != 'true')) {
                        Utilities.alertModal('No hay stock suficiente', 'warning');
                        $("#item_" + unidad_id + "_" + producto_id).val(0);
                        $("#subtotal_" + unidad_id + "_" + producto_id).val(0);
                    } else {
                        if (haystock === false && (Venta.notadebito == true)) {
                            Utilities.alertModal('No hay stock suficiente', 'warning');
                            $("#item_dev_" + unidad_id + "_" + producto_id).val(0);
                            // $("#subtotal_" + unidad_id + "_" + producto_id).val(0);
                        } else {
                            Venta.totalesUpdate(unidad_id, data);
                        }
                    }


                } else {

                    Venta.totalesUpdate(unidad_id, data);
                }
            }


        }
        );
    },

    totalesUpdate: function (unidad_id, data) {


        var producto_id = data.precios[0].producto_id;


        jQuery.each(Venta.lst_producto, function (j, value) {

            var totalprod = 0;
            var unidades_prod = new Array();
            if (parseInt(producto_id) == parseInt(value.id_producto)) {


                for (var i = 0; i < data.precios.length; i++) {

                    var cantidad = $("#item_" + data.precios[i].id_unidad + "_" + producto_id).val() == '' ? 0
                        : parseFloat($("#item_" + data.precios[i].id_unidad + "_" + producto_id).val());
                    var cantidad_dev = $("#item_dev_" + data.precios[i].id_unidad + "_" + producto_id).val() == '' ? 0
                        : parseFloat($("#item_dev_" + data.precios[i].id_unidad + "_" + producto_id).val());

                    if (isNaN(cantidad_dev)) {
                        cantidad_dev = 0;
                    }
                    var precio = parseFloat(data.precios[i].precio);
                    if (isNaN(precio)) {
                        precio = 0;
                    }

                    if (precio == 0) {

                        //  Utilities.alertModal('<h4>Error</h4> <p>La unidad seleccioanda no tiene precio configurado</p>', 'danger', true);
                    }

                    var cantidadnueva = Venta.devolver == 'true' ? (parseFloat(cantidad) - parseFloat(cantidad_dev)) : cantidad;

                    if (isNaN(cantidadnueva)) {
                        cantidadnueva = 0;
                    }
                    var subtotal_item = parseFloat(cantidadnueva) * parseFloat(precio);
                    totalprod = subtotal_item + totalprod;

                    totalprod = parseFloat(totalprod);

                    var unidad = {};
                    unidad.id_unidad = data.precios[i].id_unidad;
                    unidad.cantidad = cantidad;
                    unidad.cantidad_dev = cantidad_dev;
                    unidad.unidades = data.precios[i].unidades;
                    unidad.fe_unidad = data.precios[i].fe_unidad;
                    unidad.abreviatura = data.precios[i].abreviatura;
                    unidad.nombre_unidad = data.precios[i].nombre_unidad;
                    unidad.producto_comision = data.precios[i].producto_comision;
                    unidad.costo = data.precios[i].costo_unitario;
                    var porcentaje_impuesto = parseFloat(value.porcentaje_impuesto);


                    if (Venta.devolver != 'true' || Venta.notadebito == true) {
                        if (data.precios[0].precio_abierto == "1" && Venta.edicion == 0) {


                            jQuery.each(value.unidades, function (iu, vu) {
                                if (data.precios[i].id_unidad == vu.id_unidad) {
                                    unidad.precio = vu.precio;
                                    unidad.precio_sin_iva = vu.precio;
                                    if (isNaN(unidad.precio)) {
                                        unidad.precio = precio;
                                        unidad.precio_sin_iva = precio;
                                    }
                                    if (porcentaje_impuesto > 0) {
                                        var impuesto_dividir = (parseFloat(porcentaje_impuesto) / 100) + 1;
                                        var porimpuesto = parseFloat(impuesto_dividir);
                                        var gravado = parseFloat((unidad.precio) / porimpuesto);
                                        unidad.precio_sin_iva = gravado;
                                    }

                                }

                            });

                        } else {
                            if (Venta.edicion == 0) {
                                unidad.precio = precio;
                                unidad.precio_sin_iva = precio;
                                if (porcentaje_impuesto > 0) {
                                    var impuesto_dividir = (parseFloat(porcentaje_impuesto) / 100) + 1;
                                    var porimpuesto = parseFloat(impuesto_dividir);
                                    var gravado = parseFloat((unidad.precio) / porimpuesto);
                                    unidad.precio_sin_iva = gravado;
                                }
                            } else {
                                jQuery.each(value.unidades, function (iu, vu) {
                                    if (data.precios[i].id_unidad == vu.id_unidad) {
                                        unidad.precio = vu.precio;
                                        unidad.precio_sin_iva = vu.precio;
                                        if (isNaN(unidad.precio)) {
                                            unidad.precio = precio;
                                            unidad.precio_sin_iva = precio;
                                        }

                                        if (porcentaje_impuesto > 0) {
                                            var impuesto_dividir = (parseFloat(porcentaje_impuesto) / 100) + 1;
                                            var porimpuesto = parseFloat(impuesto_dividir);
                                            var gravado = parseFloat((unidad.precio) / porimpuesto);
                                            unidad.precio_sin_iva = gravado;
                                        }
                                    }
                                });
                            }
                        }
                    } else {

                        //aqui entra solo cuando es devolucion apra traer los precios que tenia al momento en que se hizo la venta
                        jQuery.each(value.unidades, function (iu, vu) {
                            if (data.precios[i].id_unidad == vu.id_unidad) {
                                unidad.precio = vu.precio;
                                unidad.precio_sin_iva = vu.precio;


                                if (porcentaje_impuesto > 0) {
                                    var impuesto_dividir = (parseFloat(porcentaje_impuesto) / 100) + 1;
                                    var porimpuesto = parseFloat(impuesto_dividir);
                                    var gravado = parseFloat((unidad.precio) / porimpuesto);
                                    unidad.precio_sin_iva = gravado;
                                }
                            }
                        });
                    }


                    /*jQuery.each(Venta.lst_producto, function (p, product) {

                     jQuery.each(product.unidades, function (u, unid) {
                     if (unidad_id == unid.id_unidad  ) {
                     if (unidad.precio == 0) { //TODO REVISAR QUE SOLO SALGA CUANDO DEBE SALIR
                     Utilities.alertModal('El producto no tiene precios configurados para la unidad seleccionada en este tipo de venta', 'info', 6000);
                     }
                     }
                     });
                     });*/

                    unidades_prod.push(unidad);

                }


                var prod = value;
                prod.unidades = unidades_prod;

                Venta.lst_producto.splice(j, 1);

                Venta.lst_producto.push(prod);


            }
        });


        Venta.TotalesTodoslosProductos();

    },


    TotalesTodoslosProductos: function () {

        Venta.preciocero = false;

        var totalpagarDesc = parseFloat($("#totApagar").val());
        Venta.resetTotals();
        var descuentoGlobal = 0;

        var totalventatemp = 0;
        var afiliado = $("#afiliado").val();

        descuentoGlobal = 0;

        var descuentovalorGlobal = $("#descuentoenvalor").val() == '' ? 0 : parseFloat($("#descuentoenvalor").val());
        var descuentoporcentajeGlobal = $("#descuentoenporcentaje").val() == '' ? 0 : parseFloat($("#descuentoenporcentaje").val());


        if (isNaN(descuentovalorGlobal) || descuentovalorGlobal > totalpagarDesc) {
            descuentovalorGlobal = 0;
            $("#descuentoenvalor").val('');
        }

        if (isNaN(descuentoporcentajeGlobal) || descuentoporcentajeGlobal > 100) {
            descuentoporcentajeGlobal = 0;
            $("#descuentoenporcentaje").val('')
        }

        if (descuentovalorGlobal > 0 || descuentoporcentajeGlobal > 0) {

            var totalventatemp = 0;
            /**********PRIMERO CALCULO DEL TOTAL DE TODA LA VENTA , LO NECESITARE PARA PODER SACAR LOS PORCENTAES DE LOS DESCUENTO GLOBALES *****/
            jQuery.each(Venta.lst_producto, function (j, value) {
                //console.log(value);
                jQuery.each(value.unidades, function (i, val) {
                    var precio = val.precio;


                    var porcentaje_descuento_tipo = 0;
                    if (afiliado != '' && Venta.admite_datos_cliente == 1) {
                        // El cliente esta asociado a una empresa afiliada por lo tanto hay que configurar los descuentos por tipo de producto
                        jQuery.each(Venta.afiliado_descuentos, function (o, descu) {

                            if (parseFloat(descu.tipo_prod_id) == parseFloat(value.producto_tipo) && parseFloat(descu.unidad_id) == parseFloat(val.id_unidad)) {
                                porcentaje_descuento_tipo = parseFloat(descu.porcentaje);


                                if (porcentaje_descuento_tipo != null && porcentaje_descuento_tipo != null && porcentaje_descuento_tipo != ''
                                    && porcentaje_descuento_tipo != '0') {

                                    var result_resta = 100 - parseFloat(porcentaje_descuento_tipo);
                                    if (result_resta > 0) { // el resultado lo DISMINUYO
                                        var porcentaje_disminuir = (precio * result_resta) / 100;
                                        precio = precio - porcentaje_disminuir;

                                    } else { // el resultado lo AUMENTO

                                        result_resta = parseFloat(porcentaje_descuento_tipo) - 100;

                                        var porcentaje_aumentar = (precio * result_resta) / 100;

                                        precio = precio + porcentaje_aumentar;

                                        //result_resta =  parseFloat(porcentaje_descuento_tipo) -100;
                                    }

                                }

                            }
                        });

                    }

                    if (Venta.notadebito == '1') {
                        var cantidadnueva = Venta.devolver == 'true' ? (parseFloat(val.cantidad) + parseFloat(val.cantidad_dev)) : val.cantidad;
                    } else {
                        var cantidadnueva = Venta.devolver == 'true' ? (parseFloat(val.cantidad) - parseFloat(val.cantidad_dev)) : val.cantidad;
                    }

                    if (isNaN(cantidadnueva)) {
                        cantidadnueva = 0;
                    }
                    if (precio == undefined || isNaN(precio)) {
                        precio = 0;
                    }

                    var subtotal_item = parseFloat(cantidadnueva) * parseFloat(precio);

                    totalventatemp = (subtotal_item + parseFloat(totalventatemp)).toFixed(2);
                });
            });

        }


        if (descuentoporcentajeGlobal > 0) {

            descuentoporcentajeGlobal = (totalventatemp * descuentoporcentajeGlobal) / 100;
        }
        descuentoGlobal = parseFloat(descuentovalorGlobal) + parseFloat(descuentoporcentajeGlobal);


        var nuevalistaprod = new Array();
        /********calculo los totales normal***/
        jQuery.each(Venta.lst_producto, function (j, value) {
            //console.log(value);

            var totalprod = 0;
            var descuento = 0;
            var desc_porcentaje = 0;
            var impuesto = 0;
            var otro_impuesto = 0;
            var impuesto_fijo = 0;
            var otro_impuesto_fijo = 0;
            var otro_impuesto_devolver = 0;
            var gravado1 = 0;
            var gravado2 = 0;
            var gravado = 0;
            var excluido1 = 0;
            var excluido2 = 0;
            var excluido = 0;
            var porcentaje_comision = 0;
            var porcentaje_impuesto = parseFloat(value.porcentaje_impuesto);
            var tipo_impuesto = value.tipo_impuesto;
            var porcentaje_otro_impuesto = value.porcentaje_otro_impuesto;
            var tipo_otro_impuesto = value.tipo_otro_impuesto;

            var unidades_prod = new Array();

            var cantidadnueva = 0;
            var cantidad_dev = 0;
            jQuery.each(value.unidades, function (i, val) {

                porcentaje_comision = val.producto_comision;

                if (isNaN(porcentaje_comision)) {
                    porcentaje_comision = 0;
                }

                if (isNaN(porcentaje_impuesto)) {
                    porcentaje_impuesto = 0;
                }
                if (isNaN(porcentaje_otro_impuesto)) {
                    porcentaje_otro_impuesto = 0;
                }
                var precio = parseFloat(val.precio);
                if (precio <= 0 && val.cantidad > 0) {

                    Venta.preciocero = true;
                }
                //console.log(precio);
                var porcentaje_descuento_tipo = 0;
                //console.log(afiliado);
                if (afiliado != '' && Venta.admite_datos_cliente == 1) { // El cliente esta asociado a una empresa afiliada por lo tanto hay que configurar los descuentos por tipo de producto
                    jQuery.each(Venta.afiliado_descuentos, function (o, descu) {

                        if (parseFloat(descu.tipo_prod_id) == parseFloat(value.producto_tipo) && parseFloat(descu.unidad_id) == parseFloat(val.id_unidad)) {
                            porcentaje_descuento_tipo = parseFloat(descu.porcentaje);
                            //console.log(descu);
                            if (porcentaje_descuento_tipo != null && porcentaje_descuento_tipo != null && porcentaje_descuento_tipo != ''
                                && porcentaje_descuento_tipo != '0') {

                                //console.log(porcentaje_descuento_tipo);
                                var result_resta = 100 - parseFloat(porcentaje_descuento_tipo);
                                //console.log(result_resta);
                                if (result_resta > 0) { // el resultado lo DISMINUYO
                                    //console.log(result_resta);
                                    var porcentaje_disminuir = (precio * result_resta) / 100;
                                    //console.log(porcentaje_disminuir);
                                    precio = precio - porcentaje_disminuir;
                                    //console.log('nuevo precio luego de disminuir ' + precio)
                                } else { // el resultado lo AUMENTO
                                    //console.log(porcentaje_descuento_tipo);

                                    result_resta = parseFloat(porcentaje_descuento_tipo) - 100;
                                    //console.log(result_resta);
                                    var porcentaje_aumentar = (precio * result_resta) / 100;
                                    //console.log(porcentaje_aumentar);
                                    precio = precio + porcentaje_aumentar;
                                    //console.log('nuevo precio luego de aumentar ' + precio)
                                }
                            }

                        }
                    });

                }

                if (Venta.notadebito == '1') {
                    cantidadnueva = Venta.devolver == 'true' ? (parseFloat(val.cantidad) + parseFloat(val.cantidad_dev)) : val.cantidad;
                } else {
                    cantidadnueva = Venta.devolver == 'true' ? (parseFloat(val.cantidad) - parseFloat(val.cantidad_dev)) : val.cantidad;
                }


                cantidad_dev = val.cantidad_dev;
                if (isNaN(cantidadnueva)) {
                    cantidadnueva = 0;
                }

                if (precio == undefined) {
                    precio = 0;

                }

                var subtotal_item = parseFloat(cantidadnueva) * parseFloat(precio);

                if (!isNaN(subtotal_item)) {
                    $("#subtotal_" + val.id_unidad + "_" + value.id_producto).val(subtotal_item.toLocaleString('de-DE', { maximumFractionDigits: 2 }));
                } else {
                    subtotal_item = 0;
                }

                if (val.cantidad > 0 || (Venta.notadebito == true && cantidadnueva > 0)) {
                    totalprod = subtotal_item + totalprod;

                }


            });


            var descuentovalor = parseFloat($("#desc_val_" + value.id_producto).val());

            if (isNaN(descuentovalor) || descuentovalor > totalprod) {

                if (Venta.devolver != 'true') {
                    descuentovalor = 0;

                    $("#desc_val_" + value.id_producto).val('');
                }
            } else {
                Venta.descuentovalor = Venta.descuentovalor + descuentovalor;
                if (descuentovalor > 0) {
                    document.getElementById('descuentoenvalorhidden').value = parseFloat(Venta.descuentovalor);
                }
            }
            var descuentoporcentaje = parseFloat($("#desc_por_" + value.id_producto).val());
            var porcent_des = parseFloat($("#desc_por_" + value.id_producto).val());
            if (isNaN(porcent_des) || porcent_des > 100) {
                porcent_des = 0;
            }


            if (isNaN(descuentoporcentaje) || descuentoporcentaje > 100) {
                if (Venta.devolver != 'true') {
                    descuentoporcentaje = 0;
                    $("#desc_por_" + value.id_producto).val('');
                }
            }

            if (descuentoporcentaje > 0) {
                descuentoporcentaje = (totalprod * descuentoporcentaje) / 100;

                Venta.descuentoporcentaje = Venta.descuentoporcentaje + descuentoporcentaje;
                if (descuentoporcentaje > 0) {
                    document.getElementById('descuentoenporcentajehidden').value = parseFloat(Venta.descuentoporcentaje);
                }
            }


            descuento = parseFloat(descuentovalor) + parseFloat(descuentoporcentaje);

            if (isNaN(descuento)) {
                descuento = 0;
            }

            //calculos desglosados para el descuento global
            if (descuentoGlobal > 0 && descuento <= 0) {

                var porcentajedesgloce = (totalprod * 100) / totalventatemp;

                var descuentodesgloce = (porcentajedesgloce * descuentoGlobal) / 100;

                descuento = descuentodesgloce.toFixed(2);


            }
            if (isNaN(descuento)) {
                descuento = 0;
            }

            if (totalprod > 0 || Venta.devolver == 'true') {

                //solo SI EL PRODUCTO GRAVA IVA

                if (porcentaje_impuesto > 0) {


                    /* if (tipo_impuesto == 'FIJO') {
                     gravado1 = parseFloat((totalprod - descuento));
                     impuesto_fijo = porcentaje_impuesto + impuesto_fijo;

                     } else {*/

                    var impuesto_dividir = (parseFloat(porcentaje_impuesto) / 100) + 1;

                    var porimpuesto = parseFloat(impuesto_dividir);
                    gravado1 = parseFloat((totalprod - descuento) / porimpuesto);

                    impuesto = ((gravado1 * porcentaje_impuesto) / 100) + impuesto;


                    // }
                } else {
                    excluido1 = parseFloat(totalprod - descuento);
                }


                //OTRO IMPUESTO (EJEMPLO IMPUESTO A LAS BOLSSAS)
                if (porcentaje_otro_impuesto > 0) {


                    if (tipo_otro_impuesto == 'FIJO') {


                        //esto es porque si ya calculo el impuesto ya tiene el gravado
                        if (porcentaje_impuesto <= 0) {
                            //   gravado2 = parseFloat((totalprod - descuento));
                        }
                        otro_impuesto_fijo = (parseFloat(porcentaje_otro_impuesto) * cantidadnueva) + otro_impuesto_fijo;


                        if (Venta.devolver == 'true') {

                            otro_impuesto_devolver = (parseFloat(porcentaje_otro_impuesto) * cantidad_dev) + otro_impuesto_devolver;
                        }


                    }
                    /* else {


                     var impuesto_dividir_otro = (parseFloat(porcentaje_otro_impuesto) / 100) + 1;
                     console.log(impuesto_dividir);
                     var porimpuesto_otro = parseFloat(impuesto_dividir_otro);


                     //esto es porque si ya calculo el impuesto ya tiene el gravado

                     gravado2 = parseFloat((totalprod - descuento) / porimpuesto_otro);

                     console.log(gravado2);
                     otro_impuesto = ((gravado2 * porcentaje_otro_impuesto) / 100) + otro_impuesto;

                     }*/
                } else {
                    if (excluido1 <= 0) {
                        //   excluido2 = parseFloat(totalprod - descuento);
                    }
                }

            }

            gravado = gravado + (gravado1 + gravado2);
            excluido = excluido + (excluido1 + excluido2);


            $("#totalprod_" + value.id_producto).val((totalprod - descuento).toLocaleString('de-DE', { maximumFractionDigits: 2 }));

            Venta.total = (totalprod - descuento) + impuesto_fijo + otro_impuesto_fijo + Venta.total;

            Venta.nuevoimpuesto = parseFloat(Venta.nuevoimpuesto) + parseFloat(impuesto)
                + parseFloat(impuesto_fijo);

            Venta.otrosimpuestos = parseFloat(Venta.otrosimpuestos)
                + parseFloat(otro_impuesto) + parseFloat(otro_impuesto_fijo);


            Venta.nuevosubtotal = parseFloat(parseFloat(totalprod - impuesto)) + Venta.nuevosubtotal;


            if (porcentaje_impuesto > 0 || porcentaje_otro_impuesto > 0) {
                Venta.nuevogravado = parseFloat(gravado) + Venta.nuevogravado;
            } else {

                Venta.nuevoexcluido = excluido + Venta.nuevoexcluido;
            }


            if (Venta.nuevosubtotal >= 0) {
                document.getElementById('subtotal').value = parseFloat(Venta.nuevosubtotal);
            }
            if (Venta.nuevoexcluido >= 0) {
                document.getElementById('excluido').value = parseFloat(Venta.nuevoexcluido);

            }

            if (Venta.total >= 0) {
                document.getElementById('totApagar').value = parseFloat(Venta.total);
                document.getElementById('totApagar2').value = parseFloat(Venta.total).toLocaleString('de-DE', { maximumFractionDigits: 2 });
            }
            if (Venta.nuevoimpuesto >= 0) {
                document.getElementById('iva').value = parseFloat(Venta.nuevoimpuesto);
                document.getElementById('iva2').value = parseFloat(Venta.nuevoimpuesto).toLocaleString('de-DE', { maximumFractionDigits: 2 });
            }

            if (Venta.otrosimpuestos >= 0) {
                document.getElementById('otros_impuestos').value = parseFloat(Venta.otrosimpuestos);
                document.getElementById('otros_impuestos2').value = parseFloat(Venta.otrosimpuestos).toLocaleString('de-DE', { maximumFractionDigits: 2 });
            }
            if (Venta.nuevogravado >= 0) {
                document.getElementById('basegravada').value = parseFloat(Venta.nuevogravado);
                document.getElementById('basegravada2').value = parseFloat(Venta.nuevogravado).toLocaleString('de-DE', { maximumFractionDigits: 2 });
            }


            if (Venta.devolver != 'true' || Venta.notadebito == true) {
                document.getElementById('dineroentregado').value = '';
                document.getElementById('cambiomostrar').value = '';
                document.getElementById('cambio').value = 0;
            }


            var prod = value;
            prod.impuesto = impuesto;
            prod.otro_impuesto = otro_impuesto + otro_impuesto_fijo;
            prod.descuento = descuento;
            prod.desc_porcentaje = porcent_des;
            prod.subtotal = (parseFloat(totalprod - impuesto));
            prod.total = totalprod;
            prod.porcentaje_comision = parseFloat(porcentaje_comision);
            prod.otro_impuesto_devolver = parseFloat(otro_impuesto_devolver);


            nuevalistaprod.push(prod);

        }
        )
            ;

        Venta.lst_producto = nuevalistaprod;


    },
    resetTotals: function () {
        Venta.total = 0;
        Venta.nuevoexcluido = 0;
        Venta.nuevogravado = 0;
        Venta.nuevosubtotal = 0;
        Venta.nuevoimpuesto = 0;
        Venta.otrosimpuestos = 0;
        Venta.descuentovalor = 0;
        Venta.descuentoporcentaje = 0;
        if (Venta.nuevosubtotal >= 0) {
            document.getElementById('subtotal').value = parseFloat(Venta.nuevosubtotal);
        }
        if (Venta.nuevoexcluido >= 0) {
            document.getElementById('excluido').value = parseFloat(Venta.nuevoexcluido);

        }

        if (Venta.total >= 0) {
            document.getElementById('totApagar').value = parseFloat(Venta.total);
            document.getElementById('totApagar2').value = parseFloat(Venta.total).toLocaleString('de-DE', { maximumFractionDigits: 2 });
        }
        if (Venta.nuevoimpuesto >= 0) {
            document.getElementById('iva').value = parseFloat(Venta.nuevoimpuesto);
            document.getElementById('iva2').value = parseFloat(Venta.nuevoimpuesto).toLocaleString('de-DE', { maximumFractionDigits: 2 });
        }

        if (Venta.otrosimpuestos >= 0) {
            document.getElementById('otros_impuestos').value = parseFloat(Venta.otrosimpuestos);
            document.getElementById('otros_impuestos2').value = parseFloat(Venta.otrosimpuestos).toLocaleString('de-DE', { maximumFractionDigits: 2 });
        }

        if (Venta.nuevogravado >= 0) {
            document.getElementById('basegravada').value = parseFloat(Venta.nuevogravado);
            document.getElementById('basegravada2').value = parseFloat(Venta.nuevogravado).toLocaleString('de-DE', { maximumFractionDigits: 2 });
        }

    }
    ,
    deleteproducto: function (id_producto) {


        var lista_vieja = Venta.lst_producto;

        var lista_nueva = new Array();

        Venta.countproducto--;

        Venta.lstaeliminar = new Array();


        jQuery.each(lista_vieja, function (i, value) {
            if (value["id_producto"] === id_producto) {

                Venta.tablalistaventa.row('#producto_' + value.id_producto).remove().draw();
            } else {
                //almaceno los que no estoy eliminando
                var retorno = lista_vieja[i];
                lista_nueva.push(retorno);
            }
        });


        Venta.lst_producto = lista_nueva;

        var rowsCount = Venta.tablalistaventa.data().length;
        var row = Venta.tablalistaventa.row(rowsCount - 1);
        row.remove().draw();


        Venta.appendtrvacio();
        // Utilities.setfocus(".inputsearchproduct:last-child");
        var count = parseFloat(Venta.tablalistaventa.rows().count()) - 1;
        Venta.tablalistaventa.cell(count, 2).focus();


        Venta.TotalesTodoslosProductos();

    }
    ,
    appendtrvacio: function () {


        var newrow = {};

        newrow[0] = "<input type='text' id='inputsearchproduct' class='form-control inputsearchproduct' placeholder='Ctrl + G' value='' >";

        var count = 1;
        jQuery.each(Venta.unidades, function (i, value) {
            newrow[count] = "<input type='text' id='' readonly class='form-control' value='0' >";
            count++;
            newrow[count] = "<input type='text' id='' readonly class='form-control' value='0' >";
            count++;

            if (Venta.devolver == 'true') {
                newrow[count] = "<input type='text' id='' readonly class='form-control' value='0' >";
                count++;
            }
        });
        newrow[count] = "<input type='number'  value='0' readonly class='form-control'>";
        count++;
        newrow[count] = "<input type='number'  value='0' readonly class='form-control'>";
        count++;
        newrow[count] = "<input type='text'  class='form-control' readonly value='0'>";
        count++;
        newrow[count] = "<input type='text'  class='form-control' readonly value='0'>";


        var rowNode = Venta.tablalistaventa.row.add(newrow).draw().node();
        $(rowNode).attr("id", 'trvacio');

        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                Venta.buscarproductos($(this).val());
            }

        });


    }
    ,

    mobileSearch() {

        Venta.buscarproductos($(".inputsearchproduct").val());
    },
    /*cuenta cuantas formas de pago se escribieron algun valor, en formas de pago en ventas*/
    getTotalFromasPago: function () {
        var totalformas_depago = 0;
        jQuery.each($(".formsdepagoinput"), function (i, value) {

            var inputval = parseFloat($(this).val());

            if (isNaN(inputval)) {
                inputval = 0;
            }
            totalformas_depago = totalformas_depago + inputval;

        });

        return totalformas_depago;
    }
    ,
    /*cuando se presiona aceptar en el modal de formas de pago*/
    guardarFormasDePago: function () {

        var totalformas_depago = Venta.getTotalFromasPago();


        if (totalformas_depago > 0) {
            if (parseFloat(totalformas_depago) < parseFloat($("#totApagar").val()) || parseFloat(totalformas_depago) > parseFloat($("#totApagar").val())) {
                Utilities.alertModal('Debe ingresar un monto igual  al total a pagar');
                return false;
            } else {
                $("#dineroentregado").val(totalformas_depago);
                $("#dineroentregado").attr('disabled', true);
                $("#formasdepagomodal").modal('hide');
            }
        } else {
            $("#dineroentregado").attr('disabled', false);
            $("#formasdepagomodal").modal('hide');
        }


    }
    ,


    ver_catalogo: function (tipo_catalogo) {
        Utilities.showPreloader();
        if (tipo_catalogo == 'COOPIDRGOGAS') {
            var url = baseurl + 'producto/ver_catalogo_coopidrogras/';
            $.ajax({
                url: url,
                type: 'post',
                success: function (data) {
                    Utilities.hiddePreloader();
                    setTimeout(function () {
                        $('#catalogo_template').html(data);
                        $('#catalogo_template').modal({ show: true, keyboard: false, backdrop: 'static' });
                    }, 10)

                },
                error: function (error) {
                    Utilities.hiddePreloader();
                    Utilities.alertModal(' Ha ocurrido un error', 'warning');
                }

            })

        } else {

            var ajax = ProductoService.specialSearch({ 'local': $("#idlocal").val() }, baseurl, sessionStorage.api_key);
            ajax.success(function (data) {
                var html = '';

                jQuery.each(data.productos, function (i, value) {
                    var have_existencia = false;

                    jQuery.each(value.existencia, function (j, exis) {
                        if (parseFloat(exis.cantidad) > 0) {
                            have_existencia = true;
                        }
                    });

                    if (have_existencia) {
                        var comisiona = '';
                        if (value['producto_comision'] != null && value['producto_comision'] != '0') {
                            comisiona = 'comisiona';
                        }


                        html += '<tr id="' + value['producto_id'] + '" tabindex="' + i + '" data-name="' + value['producto_nombre'] + '" class="' + comisiona + '">';
                        html += '<td>' + value['producto_id'] + '</td>';
                        html += '<td>' + value['producto_nombre'] + '</td>';
                        html += '<td>';
                        if (value['ubicacion_nombre'] != null) {
                            html += value['ubicacion_nombre'];
                        }

                        html + '</td>';

                        html += '<td>';
                        if (value['componentes'] != null) {
                            jQuery.each(value['componentes'], function (j, value2) {
                                html += ' ' + value2['componente_nombre'] + ' ';

                            });
                        }
                        html += '</td>';

                        html += '</tr>';
                    }

                });
                $("#tbodycatalogo").html(html);
                Utilities.hiddePreloader();
                $("#modalcatalogo").modal('show');

            });

        }


    }
    ,


    MostrarExistenciaProducto: function (id) {

        $.each(Venta.unidades, function (key, value) {
            $("#contenido_" + value.id_unidad).html(0);
            $("#existencia_" + value.id_unidad).html(0);
            $("#precio_venta_" + value.id_unidad).html(0);
        });

        if (id != undefined) {

            var afiliado = $("#afiliado").val();
            var condicion_pago = $("#condicion_pago_id").val();


            var prod;

            $.each(Venta.lst_producto, function (i, value) {


                if (value.id_producto == id) {
                    prod = value;
                }
            });

            var is_pqeuete = 0;

            if (prod != undefined) {
                is_pqeuete = prod.is_paquete
            }
            var ajax = InventarioService.buscarExistenciayPrecios(id, condicion_pago, is_pqeuete);
            ajax.success(function (data) {
                $.each(data.stock, function (key, value) {

                    $("#contenido_" + value.id_unidad).html(value.unidades);
                    $("#existencia_" + value.id_unidad).html(value.cantidad);

                });


                $.each(data.precios, function (key, value) {

                    var precio = parseFloat(value.precio).toFixed(2)
                    if (isNaN(precio)) {
                        precio = 0;
                    }
                    if (precio == 0) {
                        // Venta.preciocero = true;

                    }

                    $("#precio_venta_" + value.id_unidad).html(precio);

                });


                if (data.precios[0] != undefined) {
                    if (data.precios[0].producto_mensaje != '' && data.precios[0].producto_mensaje != null) {
                        $("#mensajeproducto").text(data.precios[0].producto_mensaje);
                        $("#mensajeprodcalert").fadeIn(350);
                    } else {
                        $("#mensajeprodcalert").fadeOut(350);
                    }
                }


            });
        }
    }
    ,
    buscarventasabiertas: function () {

        $.ajax({
            url: baseurl + 'venta/get_ventas_por_status',
            type: 'POST',
            data: { 'estatus': 'EN ESPERA' },
            success: function (data) {
                $("#ventasabiertas").html(data);
            }
        });
        $("#ventasabiertas").modal('show');
    }
    ,

    getTipoVenta: function () {


        $.ajax({
            url: baseurl + 'tipo_venta/get',
            type: 'POST',
            dataType: 'json',
            data: { 'id': $("#tipoventa").val() },
            success: function (data) {

                Venta.admite_datos_cliente = data.admite_datos_cliente;

                $("#diascondicionpagoinput").val(data.dias);
                $("#maneja_descuentos").val(data.maneja_descuentos);
                $("#maneja_impresion").val(data.maneja_impresion);
                $("#documento_generar").val(data.documento_generar);
                $("#condicion_pago").val(data.nombre_condiciones);
                $("#condicion_pago_id").val(data.id_condiciones);
                $("#fe_payment_form_id").val(data.fe_payment_form_id);

                if (data.dias >= 1) {

                    $("#dineroentregado").prop('readonly', true);
                    if (Venta.devolver != 'true' || Venta.notadebito == true) {
                        $("#dineroentregado").prop('value', 0);
                    }
                } else {
                    if (Venta.devolver != 'true' || Venta.notadebito == true) {
                        $("#dineroentregado").prop('readonly', false);
                    }
                }


                if (data.maneja_formas_pago == undefined) {

                    Venta.manejaformaspago = false;
                    $("#formadepago").prop('disabled', true);
                } else {
                    if (data.maneja_formas_pago == '1') {
                        Venta.manejaformaspago = true;
                        $("#formadepago").prop('disabled', false);
                    } else {
                        $("#formadepago").prop('disabled', true);
                        Venta.manejaformaspago = false;
                    }
                }

                if (data.maneja_descuentos == '1') {
                    $("#descuentoenvalor").attr('disabled', false);
                    $("#descuentoenporcentaje").attr('disabled', false);
                    $("#descuentoenporcentaje").attr('disabled', false);

                    $("input[id^='desc_val_']").attr('disabled', false);


                    $("input[id^='desc_por_']").attr('disabled', false);


                } else {
                    $("#descuentoenvalor").attr('disabled', true);
                    $("#descuentoenporcentaje").attr('disabled', true);

                    $("input[id^='desc_val_']").val(0);
                    $("input[id^='desc_val_']").attr('disabled', true);

                    $("input[id^='desc_por_']").val(0);
                    $("input[id^='desc_por_']").attr('disabled', true);

                }

                var idventa = $("#idventa").val();


                if (Venta.devolver != 'true') {


                    if (data.solicita_cod_vendedor == '1') {
                        $("#id_vendedor").attr('disabled', false);
                        $("#id_vendedor").trigger("chosen:updated");
                    } else {
                        $("#id_vendedor").attr('disabled', true);
                        $("#id_vendedor").trigger("chosen:updated");
                    }
                    if (data.admite_datos_cliente == '1') {

                        $("#id_cliente").attr('disabled', false);
                    } else {

                        $("#id_cliente").attr('disabled', true);
                    }
                }

                if (idventa != '') {
                    $("#id_vendedor").attr('disabled', true);
                    $("#id_vendedor").trigger("chosen:updated");
                    $("#id_vendedor").attr('disabled', false);
                }

                jQuery.each(Venta.lst_producto, function (i, value) {
                    Venta.calculatotales(value.id_producto, null);
                })


            }
        });

    },

    cotizar: function () {
        var totap = parseFloat($('#totApagar').val());

        if (totap <= 0) {
            Utilities.alertModal('<h4>Error</h4> <p> No puede cotizar una venta con monto igual a cero </p>', 'warning', true);
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        } else {
            var miJSON = JSON.stringify(Venta.lst_producto);
            $.ajax({
                url: baseurl + 'venta/cotizar',
                type: 'POST',
                data: $('#frmVenta').serialize() + '&lst_producto=' + miJSON,
                success: function (data) {

                    $("#mvcotizarVenta").html(data);
                    $("#mvcotizarVenta").modal('show');
                }
            });
        }

    },

    confirmPrintCotizar() {

        var miJSON = JSON.stringify(Venta.lst_producto);

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;
        let data_send = { id_vendedor: $("#id_vendedor").val(), id_cajero: $("#cajero").val() };

        if (is_nube) {
            if ($("#id_cliente").is(':enabled')) {
                data_send.id_cliente = $("#id_cliente").val();
            }
            $.ajax({
                url: baseurl + 'api/Venta/get_data_for_cloud_print_cotizacion',
                type: 'POST',
                data: $('#frmVenta').serialize() + '&lst_producto=' + miJSON,

                success: function (response) {
                    var urltickera = TICKERA_URL;
                    //  var url = baseurl + 'venta/directPrint/' + id_venta;
                    var url = urltickera + '/directPrintCotizar';

                    console.log('response', response)
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { ventas: response },
                        success: function (data) {
                            Utilities.alertModal('La cotizacion se ha enviado a la impresora', 'success');

                        }, error: function () {
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                        }
                    });


                }, error: function () {

                }
            });


        } else {


            var url = baseurl + 'venta/directPrintCotizar/';

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#frmVenta').serialize() + '&lst_producto=' + miJSON,
                success: function (data) {
                    Utilities.alertModal('La cotizacion  se ha enviado a la impresora', 'success', 6000);
                    //Utilities.alertModal('<h4>Felicidades</h4> <p>La venta se ha guardado</p>', 'success', true);
                }, error: function () {
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });
        }

    },

    /**
     * ejecuta la carga del pdf en carta.
     */
    printCotizarPdf() {
        var venta_stataus = $("#venta_status").val();
        var importe = parseFloat($('#dineroentregado').val());

        var pagado = 0;
        if ($('#pagado').length > 0) {
            pagado = parseFloat($('#pagado').val());
        }
        var totap = parseFloat($('#totApagar').val());

        if (pagado != 0) {
            totap = totap - pagado;
            pagado = 0;

        }
        var venta_tipo = $("#venta_tipo").val();
        var tipo_devolucion = Venta.getTipoDevolucion();
        var tipo_venta = Venta.getVentaTipo();
        var cliente = Venta.getClienteVenta();
        var miJSON = JSON.stringify(Venta.lst_producto);
        var formaspago = $("#formaspagoform").serialize();
        var dataventa = $('#frmVenta').serialize() + "&" + formaspago + '&lst_producto=' + miJSON + '&devolver=' + Venta.devolver
            + '&notadebito=' + Venta.notadebito
            + '&tipo_devolucion_obj=' + tipo_devolucion + '&tipo_venta=' + tipo_venta + '&cliente=' + JSON.stringify(cliente) + '&zipkey=' + Venta.zipkey + '&uuid=' + Venta.uuid + '&XmlFileName=' + Venta.XmlFileName + '&fe_numero='
            + Venta.fe_numero + '&fe_resolution_id=' + Venta.fe_resolution_id + '&fe_issue_date=' + Venta.fe_issue_date
            + '&fe_type_document=' + Venta.fe_type_document + '&fe_prefijo=' + Venta.fe_prefijo + '&fe_status=' + Venta.fe_status
            + '&fe_reponseDian=' + JSON.stringify(Venta.fe_transactionDian);

        var ajaxguardar = VentaService.printCotizarPdf(dataventa);
        ajaxguardar.success(function (data) {
            window.open(baseurl + data.file_name);
        });


    },

    diretPrintCotizar: function () {

        var maneja_impresion = $("#maneja_impresion").val();

        var title = 'Imprimir Cotización';
        if (maneja_impresion == 1) {
            swal({
                title: title,
                text: 'Seleccione el medio de impresión',
                type: "success",
                showCancelButton: true,
                cancelButtonText: 'POS',
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-success",
                confirmButtonText: 'CARTA',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    Venta.printCotizarPdf();

                } else {
                    Venta.confirmPrintCotizar();
                }
            });
        } else {
            Venta.confirmPrintCotizar();
        }

    },

    printComprobanteDiario: function (fecha_Desde, id) {


        Utilities.showPreloader();

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var EMPRESA_NOMBRE = $("#EMPRESA_NOMBRE").val();
        var EMPRESA_DIRECCION = $("#EMPRESA_DIRECCION").val();
        var EMPRESA_TELEFONO = $("#EMPRESA_TELEFONO").val();
        var USUARIO_SESSION = $("#USUARIO_SESSION").val();
        var REGIMEN_CONTRIBUTIVO = $("#REGIMEN_CONTRIBUTIVO").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var NIT = $("#NIT").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;
        if (is_nube) {

            $.ajax({
                url: baseurl + 'api/StatusCaja/data_print_comprobante_diario',
                type: 'GET',
                data: {
                    id: id,
                    fecha_Desde: fecha_Desde,
                    'REGIMEN_CONTRIBUTIVO': REGIMEN_CONTRIBUTIVO,
                    'USUARIO_SESSION': USUARIO_SESSION
                },
                success: function (data) {
                    var urltickera = TICKERA_URL;
                    var url = urltickera + '/comprobanteDiarioPrint/';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            REGIMEN_CONTRIBUTIVO: data.REGIMEN_CONTRIBUTIVO,

                            credito: data.credito,
                            impuestos: data.impuestos,
                            calculoanulaciones: data.calculoanulaciones,
                            calculoanulaciones_credito: data.calculoanulaciones_credito,
                            abonosacarteraresult: data.abonosacarteraresult,
                            calculodevoluciones: data.calculodevoluciones,
                            calculodevoluciones_credito: data.calculodevoluciones_credito,
                            grupos: data.grupos,
                            insert: data.insert,
                            fecha_impreso: data.fecha_impreso,
                            fecha_generado: data.fecha_generado,
                            fecha_Desde: fecha_Desde,
                            baseurl: baseurl,
                            USUARIO_SESSION: USUARIO_SESSION,
                            MENSAJE_FACTURA: MENSAJE_FACTURA,
                            MOSTRAR_PROSODE: MOSTRAR_PROSODE,
                            EMPRESA_NOMBRE: EMPRESA_NOMBRE,
                            REGIMEN_CONTRIBUTIVO: REGIMEN_CONTRIBUTIVO,
                            EMPRESA_DIRECCION: EMPRESA_DIRECCION,
                            EMPRESA_TELEFONO: EMPRESA_TELEFONO,
                            NIT: NIT,
                            impresora: IMPRESORA,
                            formaspago: data.formaspago,
                            totalesreales: data.totalesreales,
                            totalventascondescuento: data.totalventascondescuento,
                            first_venta: data.first_venta,
                            last_venta: data.last_venta
                        },
                        success: function (data2) {
                            Utilities.hiddePreloader();
                            TablesDatatables.init(0, 'history', 'desc');

                            if (data2.result == 'success') {
                                Utilities.alertModal('El reporte  se ha enviado a la impresora', 'success', false);

                            } else {
                                Utilities.alertModal(data.result, 'error');
                            }

                        }, error: function () {
                            Utilities.hiddePreloader();
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte', 'error');
                        }
                    });


                }, error: function () {
                    Utilities.hiddePreloader();
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte', 'error');
                }
            });

        } else {

            var url = baseurl + 'venta/comprobanteDiarioPrint/';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: { fecha_Desde: fecha_Desde, id: id },
                success: function (data) {
                    Utilities.hiddePreloader();
                    TablesDatatables.init(0, 'history', 'desc');
                    if (data.result == 'success') {
                        Utilities.alertModal('El reporte  se ha enviado a la impresora', 'success', false);


                    } else {
                        Utilities.alertModal(data.result, 'error');
                    }

                }, error: function () {
                    Utilities.hiddePreloader();
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte', 'error');
                }
            });
        }


    }
    ,

    agregarCliente: function () {

        $("#agregarclienteventa").load(baseurl + 'cliente/form');
        $("#agregarclienteventa").modal({ show: true, keyboard: false, backdrop: 'static' })

    }
    ,


    //agregar ceros a la izquierda
    pad_with_zeroes: function (number, length) {

        var my_string = '' + number;
        while (my_string.length < length) {
            my_string = '0' + my_string;
        }

        return my_string;

    }
    ,


    addProductoToArray: function (producto_id, producto_nombre, porcentaje_impuesto, porcentaje_otro_impuesto, tipo_impuesto,
        tipo_otro_impuesto, is_paquete, control_inven, producto_tipo, producto_codigo_interno,
        fe_type_item_identification_id, fe_impuesto, fe_otro_impuesto, detalle_unidad) {


        if (is_paquete == undefined) {
            is_paquete = 0;
        }


        var producto = {};
        producto.id_producto = producto_id;
        producto.nombre = producto_nombre;
        producto.is_paquete = is_paquete;
        producto.fe_impuesto = fe_impuesto;
        producto.fe_otro_impuesto = fe_otro_impuesto;
        producto.fe_type_item_identification_id = fe_type_item_identification_id;
        producto.count = Venta.countproducto;
        if (isNaN(porcentaje_impuesto)) {
            porcentaje_impuesto = 0;
        }
        producto.porcentaje_impuesto = porcentaje_impuesto;
        producto.porcentaje_otro_impuesto = porcentaje_otro_impuesto;
        producto.tipo_impuesto = tipo_impuesto;
        producto.tipo_otro_impuesto = tipo_otro_impuesto;
        producto.control_inven = control_inven;
        producto.producto_tipo = producto_tipo;
        producto.producto_codigo_interno = producto_codigo_interno;

        var unidades_prod = new Array();


        jQuery.each(Venta.unidades, function (i, value) {
            var unidad = {};
            unidad.id_unidad = value.id_unidad;
            unidad.fe_unidad = value.fe_unidad;
            unidad.abreviatura = value.abreviatura;
            unidad.nombre_unidad = value.nombre_unidad;

            unidad.abreviatura = value.abreviatura;
            unidad.cantidad = $("#item_" + value.id_unidad + "_" + producto_id).val();

            if (detalle_unidad != undefined && detalle_unidad.length > 0) {
                jQuery.each(detalle_unidad, function (di, dv) {
                    if (dv.unidad_id == value.id_unidad && (Venta.devolver == 'true')) {

                        unidad.precio = dv.precio;
                        unidad.precio_sin_iva = dv.precio_sin_iva;


                    }
                })
            }

            unidades_prod.push(unidad);
        });

        producto.unidades = unidades_prod;
        Venta.lst_producto.push(producto);


        Venta.countproducto++;


    }
    ,
    soloNumeros: function (event) {
        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            return true;
        } else if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
            return true;
        } else return false;
    }
    ,

    calculadescuentos: function (event) {

        $("input[id^='desc_']").val(0);

        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {

        } else if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {

        } else return false;


        Venta.TotalesTodoslosProductos();


    }
    ,


    validarCamposVenta: function (dias) {
        $("#realizarventa").addClass('disabled');
        $("#btnRealizarVentaAndView").addClass('disabled');
        var venta_stataus = $("#venta_status").val();


        var importe = parseFloat($('#dineroentregado').val());

        var pagado = 0;
        if ($('#pagado').length > 0) {
            pagado = parseFloat($('#pagado').val());
        }
        var totap = parseFloat($('#totApagar').val());

        if (pagado != 0) {
            totap = totap - pagado;
            pagado = 0;

        }
        // var vuelto = parseFloat($('#vuelto').val());

        var urlRefresh = $('#url_refresh').val();
        var venta_tipo = $("#venta_tipo").val();
        var tipo_devolucion = Venta.getTipoDevolucion();

        var tipo_venta = Venta.getVentaTipo();
        var cliente = Venta.getClienteVenta();


        var miJSON = JSON.stringify(Venta.lst_producto);

        var formaspago = $("#formaspagoform").serialize();


        var totApagar_backup = $("#totApagar_backup").val();
        if ((totap <= 0 && Venta.devolver != 'true' && $("#venta_status").val() == 'COMPLETADO')
            || (Venta.devolver == 'true' && totApagar_backup == totap)) {

            if (Venta.devolver == 'true') {
                if (Venta.notadebito != '1') {
                    var msg = 'No se puede guardar una nota débito sin haber adicionado al menos un produto';
                } else {
                    var msg = 'No se puede guardar una devolución sin haber devuelto al menos un produto';
                }

            } else {
                var msg = ' No puede facturar una venta con monto igual a cero';
            }
            Utilities.alertModal('<h4>Error</h4> <p> ' + msg + ' </p>', 'warning', true);
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;

        } else {

            if (dias < 1) {
                if ((importe >= totap && venta_stataus != 'EN ESPERA') || (venta_stataus == 'EN ESPERA' || Venta.devolver == 'true')) {
                    //no hago nada

                } else {
                    Utilities.alertModal('Por favor ingrese un monto mayor o igual al total', 'warning', true);
                    $("#realizarventa").removeClass('disabled');
                    $("#btnRealizarVentaAndView").removeClass('disabled');
                    return false;
                }

            }
        }
        var deuda = parseFloat($("#deuda").val())
        var totApagar_backup = $("#totApagar_backup").val();

        if (deuda != false && deuda != 0 && deuda > Venta.total && true && Venta.devolver == 'true' && totApagar_backup != deuda) {
            Utilities.alertModal('No se puede devolver, el cliente ya ha pagado parte de la cuota correspondiente a uno o mas de los productos: ' + deuda, 'error')
            $("#btnRealizarVentaAndView").removeClass('disabled');
            $("#realizarventa").removeClass('disabled');
            return false;
        }

        if (Venta.preciocero === true && (Venta.devolver != 'true' || Venta.notadebito == true)) {
            Utilities.alertModal('No se puede vender un producto con precio 0', 'error')
            $("#btnRealizarVentaAndView").removeClass('disabled');
            $("#realizarventa").removeClass('disabled');
            return false;
        }
        if ($("#id_cliente").val() == '' && Venta.admite_datos_cliente == 1 && Venta.devolver != 'true') {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar el cliente</p>', 'warning');
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        }

        if ($("#tipo_documento").val() == '') {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar el tipo de documento</p>', 'warning');
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        }

        if ($("#cboModPag").val() == '') {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar el modo de pago</p>', 'warning');
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        }

        if ($("#venta_status").val() == '') {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar el status de la venta</p>', 'warning');
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        }

        if ($("#tbodyproductos tr[id^='producto']").length == 0) {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar al menos un producto</p>', 'warning');
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            return false;
        }


        return true;


    },

    restoreButtons: function () {
        $("#realizarventa").removeClass('disabled');
        $("#btnRealizarVentaAndView").removeClass('disabled');
    }
    ,
    validarClienteCredito: function (dias, imprimir, facturaelectronica) {
        if (dias > 0) {

            var cliente = {};
            jQuery.each(Venta.clientes, function (i, vaue) {
                if (vaue.id_cliente == $("#id_cliente").val()) {
                    cliente = vaue;
                }
            });


            if (cliente.valida_venta_credito != "1") {

                Utilities.alertModal("Cliente no habilitado para venta a crédito");
                Venta.restoreButtons();
                return false;
            }


            var busqueda = CarteraService.buscarJson('getFacturasCreditoPendienteJson', { cboCliente: cliente.id_cliente });
            busqueda.success(function (data) {
                var salir = false;
                var total_pendinte = 0;
                jQuery.each(data, function (i, value) {
                    var fecha_venta = new Date(value.fecha);
                    var credito_dias = parseInt(value.credito_dias) + 1;
                    var var_credito_estado = value.var_credito_estado;
                    var fecha_actual = new Date();
                    fecha_venta.setDate(fecha_venta.getDate() + credito_dias);
                    if (parseInt(fecha_actual.getTime()) > parseInt(fecha_venta.getTime()) && var_credito_estado == "DEBE") {

                        if (cliente.permitir_deuda_vencida != '1') {
                            salir = true;
                        }
                    }
                    total_pendinte = total_pendinte + parseFloat(value.monto_pendiente);
                });

                if (salir) {
                    var mensaje = "Cliente debe cancelar la deuda pendiente para poder facturar";
                }

                var totap = parseFloat($('#totApagar').val());
                if (cliente.valida_fact_maximo == "1"
                    && parseFloat(cliente.facturacion_maximo) < total_pendinte + totap && (Venta.devolver != 'true' || Venta.notadebito == true)) {
                    salir = true;
                    mensaje = "El cliente ha superado el monto permitido para créditos";
                }

                if (salir) {
                    Utilities.alertModal(mensaje);
                    Venta.restoreButtons();
                    return false;
                } else {
                    if (facturaelectronica) {
                        Venta.facturaVentaNacional(dias);
                    } else {
                        if (Venta.devolver == 'true') {
                            if (Venta.notadebito == '1') {
                                Venta.processNotaDebito(dias, imprimir);
                            } else {
                                Venta.processNotacredito(dias, imprimir);
                            }


                        } else {
                            Venta.procesaVenta(dias, imprimir);
                        }
                    }


                }


            });

            // return true;

        }
    }
    ,

    modalPrint: function (maneja_impresion, idventa, id_devolucion, from_historial = 0) {


        var title = 'La venta se ha guardado';
        if (from_historial == 1) {
            title = 'Imprimir factura';
        }

        if (maneja_impresion == 1) {
            swal({
                title: title,
                text: 'Seleccione el medio de impresión',
                type: "success",
                showCancelButton: true,
                cancelButtonText: 'POS',
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-success",
                confirmButtonText: 'CARTA',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    window.open(baseurl + "Venta/facturaPdf?idventa=" + idventa + "&id_devolucion=" + id_devolucion);
                } else {
                    Venta.cargaData_Impresion(idventa, id_devolucion, from_historial);
                }
            });
        } else {
            Venta.cargaData_Impresion(idventa, id_devolucion, from_historial);
        }
    },


    //facturacion electronica
    async facturaVentaNacional(dias) {


        var test = Venta.FACT_E_habilitacionn == '1' ? true : false;
        var sync = Venta.FACT_E_syncrono == '1' ? true : false;


        if (Venta.cliente.length <= 0 && Venta.devolver != 'true') {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar el cliente</p>', 'warning');
            return false;
        }
        var tipo_devolucion = Venta.getTipoDevolucion();

        var tipo_venta = Venta.getVentaTipo();
        var cliente = await Venta.getClienteVenta();

        var miJSON = JSON.stringify(Venta.lst_producto);
        var formaspago = $("#formaspagoform").serialize();
        var dataventa = $('#frmVenta').serialize() + "&" + formaspago + '&lst_producto=' + miJSON + '&devolver='
            + Venta.devolver + '&tipo_devolucion_obj=' + tipo_devolucion + '&tipo_venta=' + tipo_venta + '&cliente='
            + cliente + '&test=' + test + '&sync=' + sync;

        Utilities.showPreloader();

        $.ajax({
            url: baseurl + 'FacturacionElectronica/facturaElectronica',
            data: dataventa,
            dataType: 'JSON',
            type: 'POST',
            success: function (data) {

                if (data.errors != undefined) {
                    Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                    $("#fact_elect_errors").html('');
                    $("#modal_facturacion_electronica").modal();
                    jQuery.each(data.errors, function (i, value) {
                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                        $("#fact_elect_errors").append(error_div);
                    })
                    Utilities.hiddePreloader();
                } else {
                    Venta.uuid = data.uuid;
                    Venta.fe_numero = data.number;
                    Venta.fe_resolution_id = data.resolution_id;
                    Venta.fe_type_document = data.type_document_id;
                    Venta.fe_prefijo = data.fe_prefijo;
                    Venta.fe_issue_date = data.issue_date;
                    Venta.fe_reponseDian = data.responseDian;
                    Venta.fe_transactionDian = data;

                    /**si el repsoonse dian es null quiere decir que el documeto no ha sido enviado a la dian ,
                     *  caso de ejemplo: facturas de contingencia
                     * en estos casos hay que consumir un segunto y tercer enpoint:
                     * Para factura de contingencia del facturador(03) hay que enviar zip y firmar
                     * Para facturas de continegncia de la dian hay que firmar
                     *  */
                    if (data.responseDian == null) {

                        console.log('data.exception', data.exception);
                        if ((data.exception != '' && data.exception != null)
                            || (data.error != '' && data.error != null)) {

                            Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                            $("#fact_elect_errors").html('');
                            $("#modal_facturacion_electronica").modal();

                            let error= (data.exception != '' && data.exception != null)?data.exception:data.error;
                            var error_div = "  <div class='alert alert-danger'>" + error + "</div>";
                            $("#fact_elect_errors").append(error_div);


                            Utilities.hiddePreloader();

                        } else {
                            Venta.sendZip(dias, data);
                        }




                    } else {



                        /**
                         * 
                         * Servicios síncronos
                            Se consideran a aquellos en los cuales el procesamiento y respuesta del servicio se realizan en la misma
                            conexión de consumo.
                            La llamada (Request) del servidor del cliente a los servicios síncronos es procesado de forma inmediata por
                            el servidor de DIAN y la respuesta (Response) se realiza en la misma conexión.
                         */
                        /**
                         * Servicio asíncrono
                            Son aquellos en los cuales el resultado del procesamiento del servicio requerido no es entregado en la
                            misma conexión de la solicitud de consumo. :
                         */
                        /**IMPORTANTE: EN PRODUCCION, SEGUN SOENAC, EL METODO SINCRONO SE INTERPRETA SIEMPRE COMO PRUEBAS */
                        /**
                         * entro a verificar el zip para ver si la respuesta fue valida, 
                         * en caso de que se haya enviado  asyncrono
                        *en nuestro caso lo usamos Asyncrono para produccion, y syncrono para habilitacion 
                        */
                        if (sync != true) {
                            /**quiere decir que estamos en produccion(Asyncrono) */
                            ZipKey = data.zipKey;
                            Venta.zipkey = ZipKey;

                            /**voy a ahora a consultar el estaus del zip para saber en que etstaus quedo la factura */
                            Venta.statusZip(dias, ZipKey);


                        } else {
                            /**quiere decir que estamos en pruebas(Syncrono) */
                            //sino, hago la verificacion desde el modo de pruebas


                            Venta.fe_status = 'ENVIADO';
                            var is_valid = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.IsValid;

                            //estos son los errores de la dian
                            if (is_valid === "false") {


                                Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                                $("#fact_elect_errors").html('');
                                $("#modal_facturacion_electronica").modal();
                                if (Array.isArray(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string)) {
                                    jQuery.each(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string, function (i, value) {
                                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                                        $("#fact_elect_errors").append(error_div);
                                    });
                                } else {
                                    var error_div = "  <div class='alert alert-danger'>" + data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string + "</div>";
                                    $("#fact_elect_errors").append(error_div);
                                }

                                Utilities.hiddePreloader();
                            } else {
                                $("#modal_facturacion_electronica").modal('hide');
                                Venta.XmlFileName = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.XmlFileName;

                                Utilities.alertModal(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusMessage, 'success');
                                Utilities.alertModal('Por favor espere mientras SID guarda la venta', 'success');
                                Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                            }
                        }
                    }

                }
            },
            error: function (error) {

                $("#modal_facturacion_electronica").modal();
                Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                Utilities.hiddePreloader();

            }
        })


    },

    /**enviar y firmar factura */
    sendZip(dias, data) {
        $.ajax({
            url: baseurl + 'FacturacionElectronica/envioZip',
            type: 'post',
            data: { zipName: data.zipName, zipBase64Bytes: data.zipBase64Bytes },
            dataType: 'json',
            success: function (datazip) {


                if ((datazip.errors != undefined && datazip.errors.length > 0) || datazip == null) {
                    Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                    $("#fact_elect_errors").html('');
                    $("#modal_facturacion_electronica").modal();
                    if (datazip != null) {
                        if (Array.isArray(datazip.errors)) {
                            jQuery.each(datazip.errors, function (i, value) {
                                var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                                $("#fact_elect_errors").append(error_div);
                            });
                        } else {
                            var error_div = "  <div class='alert alert-danger'>" + datazip.errors + "</div>";
                            $("#fact_elect_errors").append(error_div);
                        }
                    } else {
                        var error_div = "  <div class='alert alert-danger'>Ha ocurrido un error</div>";
                        $("#fact_elect_errors").append(error_div);
                    }

                    Utilities.hiddePreloader();
                } else {

                    let zipkey = null;
                    zipkey = datazip.responseDian.Envelope.Body.SendTestSetAsyncResponse.SendTestSetAsyncResult.ZipKey;

                    Venta.zipkey = zipkey;
                    if (Venta.FACT_E_habilitacionn == '1') {
                        /*** Modo habilitacion */

                        $("#modal_facturacion_electronica").modal('hide');

                        Utilities.alertModal('Por favor espere mientras SID guarda la venta', 'success');
                        Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR

                    } else {

                        /*** modo produccion */

                        if (zipkey != null && zipkey != '' && zipkey != undefined) {
                            Venta.statusZip(dias, zipkey);
                        }
                    }

                }






            }

        })
    },

    statusZip(dias, ZipKey) {

        $.ajax({
            url: baseurl + 'FacturacionElectronica/statusZip',
            type: 'POST',
            data: { trackid: ZipKey },
            dataType: 'json',
            success: function (datazip) {
                Venta.fe_status = 'ENVIADO';

                Venta.XmlFileName = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.XmlFileName;
                var is_valid = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.IsValid;
                if (is_valid === "false") {
                    Utilities.alertModal('La consulta de status de la factura electronica enviada ha devuelto error', true);
                    $("#fact_elect_errors").html('');
                    $("#modal_facturacion_electronica").modal();
                    if (Array.isArray(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage)) {
                        jQuery.each(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage, function (i, value) {
                            var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                            $("#fact_elect_errors").append(error_div);
                        });
                    } else {

                        let errMsg = '';
                        if (datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage != undefined) {
                            errMsg = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage;
                        }
                        errMsg = errMsg + " " + datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.StatusDescription;


                        var error_div = "  <div class='alert alert-danger'>" + errMsg + "</div>";
                        $("#fact_elect_errors").append(error_div);
                    }

                    Utilities.hiddePreloader();
                    //si la consulta del stytaus zip retorna error, doy la 
                    //opcion de reintentar la consulta, porque puede que el documento todavia este siendo validado cuando se haga el request
                    //es importante que el usuario reintente hasta que la salga exitoso o rechazado
                    // si llega a salir un mensaje que diga que esta en proceso de validacion se deberia reintenta rautomaticamente

                    //$("#reintentar_bn").attr('onclick', "Venta.statusZip("+dias+", "+ZipKey+")");

                } else {
                    $("#modal_facturacion_electronica").modal('hide');
                    Venta.XmlFileName = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.XmlFileName;

                    Utilities.alertModal(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.StatusMessage, 'success');
                    Utilities.alertModal('Por favor espere mientras SID guarda la venta', 'success');
                    Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                }
            }

        })
    },

    getClienteVenta: function () {
        return JSON.stringify(Venta.cliente);
        /*jQuery.each(Venta.clientes, function (i, value) {
            console.log(value);
            if ($('#id_cliente').val() == value.id_cliente) {
                cliente = JSON.stringify(value);
                return cliente;
            }
        });*/
    },

    getVentaTipo: function () {
        jQuery.each(Venta.tipos_venta, function (i, value) {
            if ($('#tipoventa').val() == value.tipo_venta_id) {
                tipo_venta = JSON.stringify(value);
                return tipo_venta;
            }
        });
    },
    getTipoDevolucion: function () {
        jQuery.each(Venta.tipos_devlucion, function (i, value) {
            if ($('#tipo_devolucion').val() == value.tipo_devolucion_id) {
                tipo_devolucion = JSON.stringify(value);
                return tipo_devolucion;

            }
        });
    },


    procesaVenta: async function (dias, imprimir) {

        var venta_stataus = $("#venta_status").val();


        var importe = parseFloat($('#dineroentregado').val());

        var pagado = 0;
        if ($('#pagado').length > 0) {
            pagado = parseFloat($('#pagado').val());
        }
        var totap = parseFloat($('#totApagar').val());

        if (pagado != 0) {
            totap = totap - pagado;
            pagado = 0;

        }

        var venta_tipo = $("#venta_tipo").val();
        var tipo_devolucion = Venta.getTipoDevolucion();

        var tipo_venta = Venta.getVentaTipo();
        var cliente = Venta.getClienteVenta();


        var miJSON = JSON.stringify(Venta.lst_producto);

        var formaspago = $("#formaspagoform").serialize();
        var dataventa = $('#frmVenta').serialize() + "&" + formaspago + '&lst_producto=' + miJSON + '&devolver=' + Venta.devolver
            + '&notadebito=' + Venta.notadebito
            + '&tipo_devolucion_obj=' + tipo_devolucion + '&tipo_venta=' + tipo_venta + '&cliente='
            + cliente + '&zipkey=' + Venta.zipkey + '&uuid=' + Venta.uuid + '&XmlFileName=' + Venta.XmlFileName + '&fe_numero='
            + Venta.fe_numero + '&fe_resolution_id=' + Venta.fe_resolution_id + '&fe_issue_date=' + Venta.fe_issue_date
            + '&fe_type_document=' + Venta.fe_type_document + '&fe_prefijo=' + Venta.fe_prefijo + '&fe_status=' + Venta.fe_status
            + '&fe_reponseDian=' + JSON.stringify(Venta.fe_transactionDian);


        if (dias < 1) {
            if ((importe >= totap && venta_stataus != 'EN ESPERA') || (venta_stataus == 'EN ESPERA' || Venta.devolver == 'true')) {
                Utilities.showPreloader();

                var ajaxguardar = VentaService.save(dataventa);
                ajaxguardar.success(function (data) {


                    Venta.postProcessVenta(data, imprimir);

                });
                ajaxguardar.error(function (error) {
                    Utilities.hideModal('generarventa');
                    Utilities.hiddePreloader();

                    $("#realizarventa").removeClass('disabled');
                    $("#btnRealizarVentaAndView").removeClass('disabled');
                    Utilities.alertModal('Ha ocurrido un error al guardar la venta', 'error', true);

                });
                return false;

            } else {
                Utilities.alertModal('Por favor ingrese un monto mayor o igual al total', 'warning', true);
                $("#realizarventa").removeClass('disabled');
                $("#btnRealizarVentaAndView").removeClass('disabled');
                return false;
            }

        } else {
            if (venta_tipo == 'ENTREGA' && Venta.devolver == 'true') {

            } else {
                if (importe >= totap) {
                    $("#realizarventa").removeClass('disabled');
                    $("#btnRealizarVentaAndView").removeClass('disabled');
                    Utilities.alertModal('El importe cancelado es igual al total de la venta. La venta se guardará a contado', 'info', true);

                }
            }
            Utilities.showPreloader();
            var ajaxguardar = VentaService.save(dataventa);
            ajaxguardar.success(function (data) {

                Venta.postProcessVenta(data, imprimir);

            });
            ajaxguardar.error(function (error) {
                Utilities.hideModal('generarventa');
                Utilities.hiddePreloader();
                $("#realizarventa").removeClass('disabled');
                $("#btnRealizarVentaAndView").removeClass('disabled');
                Utilities.alertModal('<h4>Error</h4> <p> Ha ocurrido un error al guardar la venta</p>', 'error', true);

            });

            return false;
            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');


        }

    },

    async processNotacredito(dias, imprimir) {

        if (Venta.devolver == 'true' && Venta.uuid != undefined && Venta.uuid != '') {
            Utilities.showPreloader();

            var test = Venta.FACT_E_habilitacionn == '1' ? true : false;
            var sync = Venta.FACT_E_syncrono == '1' ? true : false;

            var idventa = $("#idventa").val();
            var formaspago = $("#formaspagoform").serialize();
            var tipo_venta = Venta.getVentaTipo();
            var cliente = Venta.getClienteVenta();


            var miJSON = JSON.stringify(Venta.lst_producto);

            var data_venta = $('#frmVenta').serialize() + "&" + formaspago + '&lst_producto=' + miJSON + '&devolver='
                + Venta.devolver + '&tipo_devolucion_obj=' + tipo_devolucion + '&tipo_venta=' + tipo_venta + '&cliente='
                + cliente + '&test=' + test + '&sync=' + sync + '&idventa=' + idventa;


            var factured = await VentaAnular.hacerNotaCredito(data_venta);


            if (factured.errors !== undefined || factured.error !== undefined || factured.exception != undefined) {
                $("#modal_facturacion_electronica").modal();

                if (factured.errors != undefined) {
                    jQuery.each(factured.errors, function (i, value) {
                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                        $("#fact_elect_errors").append(error_div);
                    })
                }
                if (factured.message != undefined) {
                    var error_div = "  <div class='alert alert-danger'>" + factured.message + "</div>";
                    $("#fact_elect_errors").append(error_div);
                }
                if (factured.error != undefined) {
                    var error_div = "  <div class='alert alert-danger'>" + factured.error + "</div>";
                    $("#fact_elect_errors").append(error_div);
                }

                Utilities.hiddePreloader();
                $("#realizarventa").removeClass('disabled');
                $("#btnRealizarVentaAndView").removeClass('disabled');


                //TODO SHOW ERRORS
                return false;
            } else {


                var data = factured;

                var ZipKey = '';
                var uuid = data.uuid;
                var number = data.number;
                var prefijo = data.fe_prefijo;
                console.log('data', data);
                var resolution_id = data.resolution_id;
                var issue_date = data.issue_date;



                //entor a verificar el zip para ver si la respuesta fue valida,
                // en caso de que se haya enviado  asyncrono
                if (sync != true) {
                    ZipKey = data.responseDian.Envelope.Body.SendTestSetAsyncResponse.SendTestSetAsyncResult.ZipKey;
                    Venta.zipkey = ZipKey;


                    $.ajax({
                        url: baseurl + 'FacturacionElectronica/statusZip',
                        data: { trackid: ZipKey },
                        type: 'post',
                        dataType: 'json',
                        success: function (datazip) {

                            Utilities.hiddePreloader();
                            $("#realizarventa").removeClass('disabled');
                            $("#btnRealizarVentaAndView").removeClass('disabled');

                            Venta.XmlFileName = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.XmlFileName;
                            var is_valid = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.IsValid;
                            if (is_valid === "false") {
                                $("#modal_facturacion_electronica").modal();
                                Utilities.alertModal('No se ha podido enviar la nota de credito electrónica, intente nuevamente', true);
                                $("#fact_elect_errors").html('');

                                if (Array.isArray(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage)) {
                                    jQuery.each(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage, function (i, value) {
                                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                                        $("#fact_elect_errors").append(error_div);
                                    });
                                } else {

                                    let errMsg = '';
                                    if (datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage != undefined) {
                                        errMsg = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage;
                                    }
                                    errMsg = errMsg + " " + datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.StatusDescription;

                                    var error_div = "  <div class='alert alert-danger'>" + errMsg + "</div>";
                                    $("#fact_elect_errors").append(error_div);
                                }

                                Utilities.hiddePreloader();



                            } else {



                                Utilities.alertModal(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.StatusMessage, 'success');
                                Utilities.alertModal('Por favor espere mientras SID guarda la nota de credito', 'success');
                                Venta.postProcessNotacredito(uuid, number, resolution_id, issue_date, idventa, dias, ZipKey, data, prefijo);


                            }
                        }, error: function () {
                            Utilities.hiddePreloader();
                            $("#realizarventa").removeClass('disabled');
                            $("#btnRealizarVentaAndView").removeClass('disabled');

                            Utilities.alertModal('No se ha podido enviar la nota de credito electrónica, intente nuevamente', true);
                            $("#fact_elect_errors").html('');

                        }

                    })


                } else {
                    //sino, hago la verificacion en modo sincrono

                    var is_valid = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.IsValid;

                    //estos son los errores de la dian
                    if (is_valid === "false") {


                        Utilities.hiddePreloader();
                        $("#realizarventa").removeClass('disabled');
                        $("#btnRealizarVentaAndView").removeClass('disabled');

                        Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                        $("#fact_elect_errors").html('');
                        $("#modal_facturacion_electronica").modal();
                        if (Array.isArray(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string)) {
                            jQuery.each(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string, function (i, value) {

                                var error_div = "  <div class='alert alert-danger'>" + value + "</div>";

                                $("#fact_elect_errors").append(error_div);
                            });
                        } else {
                            var error_div = "  <div class='alert alert-danger'>" + data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string + "</div>";
                            $("#fact_elect_errors").append(error_div);
                        }

                        Utilities.hiddePreloader();
                    } else {
                        $("#fact_elect_errors").html('');
                        Utilities.hideModal('generarventa');


                        Venta.XmlFileName = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.XmlFileName;

                        Utilities.alertModal(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusMessage, 'success');
                        Utilities.alertModal('Por favor espere mientras SID guarda la nota de crédito', 'success');
                        Venta.postProcessNotacredito(uuid, number, resolution_id, issue_date, idventa, dias, ZipKey, data, prefijo);


                    }
                }


            }


        } else {
            Venta.procesaVenta(dias, imprimir);
        }
    },


    //ajax de notadebito
    hacerNotaDebito: function (data_venta) {

        return $.ajax({
            url: baseurl + 'FacturacionElectronica/notaDebito',
            type: 'post',
            data: data_venta,
            dataType: 'json',

        });

    },

    //nota debito
    async processNotaDebito(dias, imprimir) {

        if (Venta.devolver == 'true' && Venta.uuid != undefined && Venta.uuid != '' && Venta.notadebito == '1') {
            Utilities.showPreloader();

            var test = Venta.FACT_E_habilitacionn == '1' ? true : false;
            var sync = Venta.FACT_E_syncrono == '1' ? true : false;

            var idventa = $("#idventa").val();
            var formaspago = $("#formaspagoform").serialize();
            var tipo_venta = Venta.getVentaTipo();
            var cliente = Venta.getClienteVenta();


            var miJSON = JSON.stringify(Venta.lst_producto);

            var data_venta = $('#frmVenta').serialize() + "&" + formaspago + '&lst_producto=' + miJSON + '&devolver='
                + Venta.devolver + '&tipo_devolucion_obj=' + tipo_devolucion + '&tipo_venta=' + tipo_venta + '&cliente='
                + cliente + '&test=true&sync=' + sync + '&idventa=' + idventa;


            var factured = await Venta.hacerNotaDebito(data_venta);


            if (factured.errors !== undefined || factured.error !== undefined || factured.exception !== undefined) {
                Utilities.hiddePreloader();
                $("#realizarventa").removeClass('disabled');
                $("#btnRealizarVentaAndView").removeClass('disabled');

                $("#modal_facturacion_electronica").modal();
                $("#fact_elect_errors").html('');
                if (factured.message != undefined) {
                    var error_div = "  <div class='alert alert-danger'>" + factured.message + "</div>";
                    $("#fact_elect_errors").append(error_div);
                }
                if (factured.errors != undefined) {
                    jQuery.each(factured.errors, function (i, value) {
                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                        $("#fact_elect_errors").append(error_div);
                    })
                }
                if (factured.error != undefined) {
                    var error_div = "  <div class='alert alert-danger'>" + factured.error + "</div>";
                    $("#fact_elect_errors").append(error_div);
                }

                $("#realizarventa").removeClass('disabled');
                $("#btnRealizarVentaAndView").removeClass('disabled');
                //TODO SHOW ERRORS
                return false;
            } else {

                var data = factured;

                var ZipKey = '';
                var uuid = data.uuid;
                var number = data.number;
                var resolution_id = data.resolution_id;
                var issue_date = data.issue_date;
                var fe_reponseDian = data.responseDian;
                var prefijo = data.fe_prefijo;
                console.log('prefijo', prefijo);


                //entor a verificar el zip para ver si la respuesta fue valida, 
                //en caso de que se haya enviado  asyncrono
                if (sync != true) {
                    ZipKey = data.responseDian.Envelope.Body.SendTestSetAsyncResponse.SendTestSetAsyncResult.ZipKey;
                    Venta.zipkey = ZipKey;


                    //TODO VALIDAR SI TENGO QUE ALAMCENAR ESTA FACTURA POR SI ALGO Y PREGUNTAR QUE SIGNIFICA EL STATUS
                    $.ajax({
                        url: baseurl + 'FacturacionElectronica/statusZip',
                        type: 'post',
                        data: { trackid: ZipKey },
                        dataType: 'json',
                        success: function (datazip) {
                            Utilities.hiddePreloader();
                            $("#realizarventa").removeClass('disabled');
                            $("#btnRealizarVentaAndView").removeClass('disabled');


                            Venta.XmlFileName = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.XmlFileName;
                            var is_valid = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.IsValid;
                            if (is_valid === "false") {
                                $("#modal_facturacion_electronica").modal();
                                Utilities.alertModal('No se ha podido enviar la nota de credito electrónica, intente nuevamente', true);
                                $("#fact_elect_errors").html('');

                                if (Array.isArray(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage)) {
                                    jQuery.each(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage, function (i, value) {
                                        var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                                        $("#fact_elect_errors").append(error_div);
                                    });
                                } else {

                                    let errMsg = '';
                                    if (datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage != undefined) {
                                        errMsg = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.ErrorMessage;
                                    } errMsg = errMsg + " " + datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.StatusDescription;

                                    var error_div = "  <div class='alert alert-danger'>" + errMsg + "</div>";
                                    $("#fact_elect_errors").append(error_div);
                                }


                            } else {



                                Utilities.alertModal(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.StatusMessage, 'success');
                                Utilities.alertModal('Por favor espere mientras SID guarda la nota de credito', 'success');
                                Venta.postProcessNotaDebito(uuid, number, resolution_id, issue_date, idventa, dias, ZipKey, data, prefijo);


                            }
                        }

                    })


                } else {
                    //sino, hago la verificacion desde el modo de pruebas


                    var is_valid = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.IsValid;

                    //estos son los errores de la dian
                    if (is_valid === "false") {

                        Utilities.hiddePreloader();
                        $("#realizarventa").removeClass('disabled');
                        $("#btnRealizarVentaAndView").removeClass('disabled');

                        Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                        $("#fact_elect_errors").html('');
                        $("#modal_facturacion_electronica").modal();

                        if (Array.isArray(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string)) {
                            jQuery.each(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string, function (i, value) {

                                var error_div = "  <div class='alert alert-danger'>" + value + "</div>";

                                $("#fact_elect_errors").append(error_div);
                            });
                        } else {
                            var error_div = "  <div class='alert alert-danger'>" + data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string + "</div>";
                            $("#fact_elect_errors").append(error_div);
                        }

                        Utilities.hiddePreloader();
                    } else {
                        Utilities.hiddePreloader();
                        $("#realizarventa").removeClass('disabled');
                        $("#btnRealizarVentaAndView").removeClass('disabled');


                        $("#fact_elect_errors").html('');
                        Utilities.hideModal('generarventa');


                        Venta.XmlFileName = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.XmlFileName;

                        Utilities.alertModal(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusMessage, 'success');
                        Utilities.alertModal('Por favor espere mientras SID guarda la nota de crédito', 'success');
                        Venta.postProcessNotaDebito(uuid, number, resolution_id, issue_date, idventa, dias, ZipKey, data, prefijo);


                    }
                }


            }


        } else {
            Venta.procesaVenta(dias, imprimir);
        }
    },

    postProcessNotacredito(uuid, number, resolution_id, issued_date, venta_id, dias, zipkey, reponseDian, prefijo) {

        $.ajax({
            url: baseurl + 'facturacionElectronica/postProcesNotaCredito',
            type: 'post',
            dataType: 'JSON',
            data: {
                uuid: uuid,
                number: number,
                resolution_id: resolution_id,
                issued_date: issued_date,
                venta_id: venta_id,
                type: 'DEVOLUCION',
                zipkey: zipkey,
                reponseDian: reponseDian,

                status: 'ENVIADO',
                prefijo: prefijo
            },
            success: function (response) {


                if (response.success == true) {
                    $("#modal_facturacion_electronica").modal('hide');
                    Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                } else {
                    Utilities.alertModal('No se pudo almacenar la nota de credito en SID', 'error')
                    Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                }


            },
            error: function () {
                Utilities.alertModal('No se pudo almacenar la nota de credito en local', 'error')
                Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
            }
        })
    },


    postProcessNotaDebito(uuid, number, resolution_id, issued_date, venta_id, dias, zipkey, reponseDian, prefijo) {

        $.ajax({
            url: baseurl + 'facturacionElectronica/postProcesNotaDebito',
            type: 'post',
            dataType: 'JSON',
            data: {
                uuid: uuid,
                number: number,
                resolution_id: resolution_id,
                issued_date: issued_date,
                venta_id: venta_id,
                type: 'DEVOLUCION',
                zipkey: zipkey,
                reponseDian: reponseDian,

                status: 'ENVIADO',
                prefijo: prefijo,
            },
            success: function (response) {

                if (response.success == true) {
                    $("#modal_facturacion_electronica").modal('hide');
                    Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                } else {
                    Utilities.alertModal('No se pudo almacenar la nota de credito en SID', 'error')
                    Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
                }



            },
            error: function () {
                Utilities.alertModal('No se pudo almacenar la nota de credito en local, por favro intente nuevamente', 'error')
                Venta.procesaVenta(dias, 0);// TODO PREGUNTAR AL CLIENTE SI QUIERE IMPRIMIR
            }
        })
    },

    hacerventa: function (imprimir, facturaelectronica) {


        var dias = $('#diascondicionpagoinput').val();
        var retorno = Venta.validarCamposVenta(dias);

        if (retorno == true) {
            if (dias > 0) {
                Venta.validarClienteCredito(dias, imprimir, facturaelectronica);
            } else {

                if (facturaelectronica) {
                    if (Venta.devolver == 'true') {
                        if (Venta.notadebito == '1') {
                            Venta.processNotaDebito(dias, imprimir);
                        } else {
                            Venta.processNotacredito(dias, imprimir);
                        }


                    } else {
                        Venta.facturaVentaNacional(dias)
                    }
                    ;
                } else {
                    if (Venta.devolver == 'true') {
                        if (Venta.notadebito == '1') {
                            Venta.processNotaDebito(dias, imprimir);
                        } else {
                            Venta.processNotacredito(dias, imprimir);
                        }


                    } else {
                        Venta.procesaVenta(dias, imprimir);
                    }


                }

            }
        }


    }
    ,
    postProcessVenta: async function (dataventa, imprimir) {
        var tipo_venta = Venta.getVentaTipo();
        var cliente = Venta.getClienteVenta();
        var urlRefresh = $('#url_refresh').val();
        var formaspago = $("#formaspagoform").serialize();
        data = dataventa;

        var miJSON = JSON.stringify(Venta.lst_producto);
        if (data.result == 'success') {


            Utilities.hideModal('generarventa');
            Utilities.hiddePreloader();

            if (Venta.toastCliSelected != "") {
                Venta.toastCliSelected.reset();
            }

            Utilities.alertModal('Felicidades! La venta se ha guardado', 'success', true);
            if ($("#generarventa").is(":visible")) {
                $("#generarventa").modal('hide');
            }
            Venta.resetFields();
            setTimeout(function () {
                ajaxRefresh(urlRefresh).success(function (datat) {

                    if ($("#ventamodal").length > 0) {
                        $("#ventamodal").on("hidden.bs.modal", function () {
                            $('#page-content').html(datat);
                        });
                        $("#ventamodal").modal('hide');

                        $('#page-content').html(datat);

                    } else {
                        $('#page-content').html(datat);
                    }


                })
            }, 500);



            if (imprimir == 1) {
                console.log('imprimir', imprimir);
                var maneja_impresion = $("#maneja_impresion").val();
                Venta.modalPrint(maneja_impresion, data.idventa, data.id_devolucion);



            } else {


                Venta.abrirCajaRegistradora(data.idventa, data.id_devolucion);
            }


        } else {
            Utilities.hideModal('generarventa');
            Utilities.hiddePreloader();

            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            Utilities.alertModal('' + data.result + '', 'error', true);
            return false;
        }


    }
    ,
    refrescarstock: function () {
        $("#barloadermodal").modal({
            show: true,
            backdrop: 'static'
        });
        $.ajax({
            url: baseurl + 'inventario/getbylocal',
            data: { local: $("#idlocal").val() },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                var newlist = lst_producto;
                lst_producto = new Array();
                var lst_bonos = new Array();
                $("#selectproductos").val('');
                $("#selectproductos").html('<option value="">Seleccione<option>');

                for (var i = 0; i < data.length; i++) {
                    var option = '<option value="' + data[i].producto_id + '">' + data[i].producto_id_cero + ' - ' + data[i].producto_nombre + '</option>';
                    $("#selectproductos").append(option);

                    var stockhidden = $("#stockhidden" + data[i].producto_id);

                    if (stockhidden.length > 0) {
                        stockhidden.val(0);
                        var cantidad_total = (parseFloat(data[i].unidades) * parseFloat(data[i].cantidad));
                        stockhidden.val(cantidad_total);
                    }
                }

                $("#selectproductos").trigger("chosen:updated");
                $("#tbodyproductos").html('');

                countproducto = 0;
                Venta.resetTotals();

                jQuery.each(newlist, function (i, value) {

                    Venta.calculatotales(value.id_producto, value.unidades);
                    Venta.addProductoToArray(value.id_producto, value.nombre, value.porcentaje_impuesto, value.porcentaje_otro_impuesto,
                        value.tipo_impuesto, value.tipo_otro_impuesto,
                        value.is_paquete, value.control_inven, value.producto_tipo, value.producto_codigo_interno,
                        value.fe_type_item_identification_id, value.fe_impuesto, value.fe_otro_impuesto);

                    var stockhidden = $("#stockhidden" + value.id_producto);
                    var cantidad_total = parseFloat((stockhidden.val() - value.unidades * value.cantidad));
                    stockhidden.val(cantidad_total);
                });

                Utilities.hiddePreloader();
            },
            error: function () {
                Utilities.hiddePreloader();
            }
        })
    }
    ,
    addproductototable: function (producto_id, producto_nombre, count, porcentaje_impuesto, detalle_unidad, precio_abierto, descuento,
        desc_porcentaje) {

        if (isNaN(desc_porcentaje)) {
            desc_porcentaje = '';
        }
        if (isNaN(descuento)) {
            descuento = '';
        }

        var cont = parseInt(count) + 1;
        var maneja_descuento = $("#maneja_descuentos").val();
        var unidades_has_prod = UnidadesService.getSoloPreciosByProdNoAsync(producto_id);
        var newrow = {};

        newrow[0] = Venta.pad_with_zeroes(producto_id, 4);
        newrow[1] = producto_nombre;

        var lista_prod = Venta.lst_producto;
        unidades_has_prod.success(function (data) {

            var contador = 2;
            jQuery.each(Venta.unidades, function (i, value) {
                var tienelauniad = false;
                // var precio='';
                jQuery.each(data, function (j, precio) {
                    if (parseFloat(precio.id_unidad) == parseFloat(value.id_unidad)) {
                        tienelauniad = true;
                        //precio=precio.precio;
                    }
                });
                var readonly = '';


                if (!tienelauniad || (Venta.devolver == 'true')) {
                    readonly = 'readonly';
                }

                newrow[contador] = "<input " + readonly + "  type='text' id='item_" + value.id_unidad + "_" + producto_id + "' class='form-control' value=''  onkeydown='return soloNumeros(event);'  onkeyup='Venta.calculatotales(" + producto_id + "," + value.id_unidad + ")'>";
                contador++;

                readonly = '';
                if (!tienelauniad) {
                    readonly = 'readonly';
                }

                if (!tienelauniad) {
                    readonly = 'readonly';
                }

                if (Venta.devolver == 'true') {
                    readonly = 'readonly';

                    if (Venta.notadebito == true && tienelauniad) {
                        readonly = '';
                    }

                    newrow[contador] = "<input " + readonly + " type='text' id='item_dev_" + value.id_unidad + "_" + producto_id + "' class='form-control' value=''  onkeydown='return soloNumeros(event);' onkeyup='Venta.calculatotales(" + producto_id + "," + value.id_unidad + "); '>";
                    contador++;
                }


                newrow[contador] = "<input type='text' id='subtotal_" + value.id_unidad + "_" + producto_id + "' class='form-control' value='0' readonly>";
                contador++;
            });

            var poner_descuento_valor = 0;


            if (desc_porcentaje == '' || desc_porcentaje == 0) {
                poner_descuento_valor = descuento;
            }

            if (maneja_descuento != '1' || Venta.devolver == 'true') {


                newrow[contador] = "<input type='text' id='desc_val_" + producto_id + "' value='" + poner_descuento_valor + "' readonly  class='form-control' onkeydown='return soloDecimal(event);'  onkeyup='Venta.calculatotales(" + producto_id + ",null" + ");Venta.blocktheother(1, " + producto_id + ")'/>";
                contador++;
                newrow[contador] = "<input type='text' id='desc_por_" + producto_id + "' value='" + desc_porcentaje + "' readonly class='form-control' onkeydown='return soloNumeros(event);'  onkeyup='Venta.calculatotales(" + producto_id + ",null" + ");Venta.blocktheother(0," + producto_id + ")'/>";
                contador++

            } else {

                newrow[contador] = "<input type='text' id='desc_val_" + producto_id + "' value='" + poner_descuento_valor + "' class='form-control' onkeydown='return soloNumeros(event);'  onkeyup='Venta.calculatotales(" + producto_id + ",null" + ");Venta.blocktheother(1, " + producto_id + ")'/>";
                contador++;
                newrow[contador] = "<input type='text' id='desc_por_" + producto_id + "' value='" + desc_porcentaje + "' class='form-control' onkeydown='return soloNumeros(event);'  onkeyup='Venta.calculatotales(" + producto_id + ",null" + "); Venta.blocktheother(0," + producto_id + ")'>";
                contador++;

            }
            newrow[contador] = "<input type='text' id='totalprod_" + producto_id + "' class='form-control' readonly value='0'>";
            contador++


            var rowNode = Venta.tablalistaventa.row.add(newrow).draw().node();

            $(rowNode).attr("data-producto_id", producto_id);

            $(rowNode).attr("data-precio-abierto", precio_abierto);

            $(rowNode).attr("id", 'producto_' + producto_id);

            //esto es para poder borrar un tr al presionar la tecla suprimir
            $(rowNode).attr("tabindex", Venta.tablalistaventa.rows().count() - 2);

            //Venta.makeTableKey();

            var rowsCount = Venta.tablalistaventa.data().length;
            var row = Venta.tablalistaventa.row('#trvacio');
            row.remove().draw();


            if (Venta.devolver != 'true' || Venta.notadebito == true) {
                Venta.appendtrvacio();
            }

            if (precio_abierto != '1') {
                Venta.tablalistaventa.cell(Venta.countproducto - 1, 2).focus();
            }


            if (detalle_unidad != undefined && detalle_unidad != null) {

                jQuery.each(detalle_unidad, function (j, value) {



                    $("#item_" + value.id_unidad + "_" + producto_id).val(value.cantidad);
                    if (Venta.devolver == 'true' && Venta.notadebito != true && parseInt(value.cantidad) > 0) {

                        $("#item_dev_" + value.id_unidad + "_" + producto_id).removeAttr('readonly');
                    }

                    Venta.calculatotales(producto_id, value.id_unidad);

                });
            }


            $('tbody tr').off('keydown');
            $('tbody tr').off('click');

            $("input[id^='item_']").click(function () {


                var id = $(this).attr('id');

                id = id.split('_');
                id = id[2];


                var precio_abierto2 = $("#producto_" + id).attr("data-precio-abierto");

                if (precio_abierto2 == '1') {

                    Venta.precioAbierto(id);
                }

            });

            $("tbody tr").on('keydown', function (e) {
                var id = $(this).attr('id');
                id = id.split('_');
                id = id[1];


                if (e.keyCode == 46 && (Venta.devolver != 'true' || Venta.notadebito == true)) {
                    e.preventDefault();

                    Venta.deleteproducto(id);

                }

            });

        });

    }
    ,
    aceptarPrecioAbierto: function () {

        var producto_id = $("#precioabiertoproducto").val();
        var quedo = true;

        jQuery.each(Venta.lst_producto, function (j, value) {


            if (parseInt(producto_id) == parseInt(value.id_producto)) {

                var unidades_prod = new Array();

                for (var i = 0; i < value.unidades.length; i++) {


                    var valorprecio = $("#precio_abierto_" + value.unidades[i].id_unidad).val();

                    if ($("#precio_abierto_" + value.unidades[i].id_unidad).attr('readonly') != 'readonly' && valorprecio == '') {
                        $("#precio_abierto_" + value.unidades[i].id_unidad).focus();
                        quedo = false;
                    }


                    if (parseFloat(valorprecio) > parseFloat($("#maximo_" + value.unidades[i].id_unidad).val()) || parseFloat(valorprecio) < parseFloat($("#minimo_" + value.unidades[i].id_unidad).val())) {
                        Utilities.alertModal('El precio debe estar entre el minimo y el maximo');


                        if ($("#precio_abierto_" + value.unidades[i].id_unidad).attr('readonly') != 'readonly') {

                            $("#precio_abierto_" + value.unidades[i].id_unidad).focus();

                            quedo = false;
                        }
                    }


                    var precio = parseFloat($("#precio_abierto_" + value.unidades[i].id_unidad).val());

                    var unidad = value.unidades[i];
                    if (!isNaN(precio)) {
                        unidad.precio = precio;
                    }

                    unidades_prod.push(unidad);

                }


                var prod = value;
                prod.unidades = unidades_prod;
                Venta.lst_producto.splice(j, 1);

                Venta.lst_producto.push(prod);


                if (quedo) {
                    Venta.TotalesTodoslosProductos();
                    $("#precioabiertomodal").modal('hide');
                    $("#precioabiertoform")[0].reset();
                }

            }
        });


    }
    ,

    makeTableKey: function (init) {
        // Venta.tablalistaventa.destroy();
        Venta.tablalistaventa = $("#tablalistaventa").DataTable({
            keys: true,
            "searching": false,
            "ordering": false,
            "bPaginate": false,
            fixedHeader: {
                header: true,
                footer: true
            },
            scrollY: '30vh',
            "sScrollX": "100%",
            scrollCollapse: true,
            paging: false,
            info: false
        });


        Venta.keyFocusEvent();
        if (init) {
            Venta.tablalistaventa.cell(0, 2).focus();


        }
    }
    ,

    keyFocusEvent: function () {
        // Inline editing on tab focus
        Venta.tablalistaventa.off('key-focus');
        Venta.tablalistaventa.on('key-focus', function (e, datatable, cell) {
            var rowData = datatable.row(cell.index().row).data();

            var colData = cell.data();

            var objectCell = $($.parseHTML(colData));
            var elemento = $("#" + objectCell.attr('id'));


            $("#" + objectCell.attr('id')).focus();
            $("#" + objectCell.attr('id')).select();


            //esto lo hago para que cuandp pase el keyfocus sobre el td busque los datos del producto
            //sobre el cual se esta seleccionado
            var rowNode = datatable.row(cell.index().row).node();

            var id = $(rowNode).attr("data-producto_id");

            Venta.MostrarExistenciaProducto(id);

        });


    }
    ,

    agregarProducto: function () {
        var producto_id = $("#preciostbody tr.ui-selected").attr('id');

        if (producto_id != undefined) {


            var existe = false;
            jQuery.each(Venta.lst_producto, function (i, value) {
                if (producto_id == value.id_producto) {
                    existe = true;
                }
            });

            if (existe == true) {
                Venta.submited = false;
                Utilities.alertModal('<h4>Ya existe un registro para este producto!</h4>', 'warning', true);
                return false;
            } else {

                var producto_id_cero = Venta.pad_with_zeroes(producto_id);
                var producto_nombre = $("#preciostbody tr.ui-selected").attr('data-name');
                producto_nombre = producto_nombre.substring(producto_nombre.indexOf("-") + 1);

                var info_producto = ProductoService.getProducto(producto_id);


                var porcentaje_impuesto = 0;
                var porcentaje_otro_impuesto = 0;
                var tipo_impuesto = "PORCENTAJE";
                var tipo_otro_impuesto = "PORCENTAJE";

                info_producto.success(function (data) {
                    porcentaje_impuesto = data.porcentaje_impuesto;
                    porcentaje_otro_impuesto = data.porcentaje_otro_impuesto;
                    tipo_impuesto = data.tipo_impuesto;

                    tipo_otro_impuesto = data.tipo_otro_impuesto;
                    var control_inven = data.control_inven;
                    var producto_tipo = data.producto_tipo;
                    var producto_codigo_interno = data.producto_codigo_interno;
                    var fe_type_item_identification_id = data.fe_type_item_identification_id;
                    var precio_abierto = data.precio_abierto;
                    var descuento = data.descuento;
                    var desc_porcentaje = data.desc_porcentaje;
                    var fe_impuesto = data.fe_impuesto;
                    var fe_otro_impuesto = data.fe_otro_impuesto;
                    if (data.porcentaje_impuesto == null) {
                        porcentaje_impuesto = 0;
                    }
                    if (data.porcentaje_otro_impuesto == null) {
                        porcentaje_otro_impuesto = 0;
                    }

                    Venta.addproductototable(producto_id, producto_nombre, Venta.countproducto, porcentaje_impuesto, null, precio_abierto, descuento, desc_porcentaje);


                    Venta.addProductoToArray(producto_id, encodeURIComponent(producto_nombre), porcentaje_impuesto,
                        porcentaje_otro_impuesto, tipo_impuesto, tipo_otro_impuesto,
                        data.is_paquete, control_inven, producto_tipo, producto_codigo_interno, fe_type_item_identification_id, fe_impuesto, fe_otro_impuesto);


                    $("#stock_status").val(0);
                    var stockhidden = $("#stockhidden" + producto_id);
                    // Utilities.setfocus("#producto_" + producto_id + " td:eq(2) input");
                    //Utilities.setfocus("#producto_" + producto_id + " td:eq(0) input");

                    $('#seleccionunidades').modal('toggle');

                    Venta.submited = false;
                    if (precio_abierto == '1') {

                        Venta.precioAbierto(producto_id);

                    }

                    if (Venta.devolver != 'true') {
                        document.getElementById('dineroentregado').value = '';
                        document.getElementById('cambiomostrar').value = '';
                        document.getElementById('cambio').value = 0;
                    }


                });

            }
        } else {
            Venta.submited = false;
        }

    }
    ,


    precioAbierto: function (producto_id) {
        $("#precioabiertoproducto").val(producto_id);
        var condicion_pago = null;

        jQuery.each(Venta.tipos_venta, function (i, value) {
            if (parseInt(value.tipo_venta_id) == parseInt($("#tipoventa").val())) {
                condicion_pago = value.condicion_pago;
            }
        });

        var afiliado = $("#afiliado").val();

        var precios = InventarioService.buscarExistenciayPrecios(producto_id, condicion_pago, false);
        precios.success(function (data) {
            $("#precioabiertomodal").modal({ 'show': true, 'keyboard': false, 'backdrop': 'static' });
            jQuery.each(data.precios, function (i, value) {

                if (i == 0) {
                    $("#precio_abierto_" + value.id_unidad).focus();
                }

                $("#permitido_" + value.id_unidad).text(value.precio_minimo + " a " + value.precio_maximo);
                $("#minimo_" + value.id_unidad).val(value.precio_minimo);
                $("#maximo_" + value.id_unidad).val(value.precio_maximo);
                $("#precio_abierto_" + value.id_unidad).removeAttr('readonly');
                $("#precio_abierto_" + value.id_unidad).on('blur', function () {
                    var valorprecio = $("#precio_abierto_" + value.id_unidad).val();
                    if (parseFloat(valorprecio) > parseFloat($("#maximo_" + value.id_unidad).val()) || parseFloat(valorprecio) < parseFloat($("#minimo_" + value.id_unidad).val())) {
                        Utilities.alertModal('El precio debe estar entre el minimo y el maximo');
                        $("#precio_abierto_" + value.id_unidad).focus();
                    }
                });


            });

        });
    }
    ,
    calcular_importe: function (event) {


        if (event != undefined) {
            var key = window.event ? event.keyCode : event.which;


            if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {

            } else if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {

            } else return false;
        }


        document.getElementById('cambio').value = 0;

        document.getElementById('cambiomostrar').value = 0;

        var totalApagar = parseFloat($('#totApagar').val());
        var dineroentregado = parseFloat($('#dineroentregado').val());


        if (isNaN(dineroentregado)) {
            dineroentregado = 0;
        }
        var vuelto = parseFloat((parseFloat(dineroentregado - totalApagar).toFixed(2)));

        if (vuelto > 0) {
            document.getElementById('cambiomostrar').value = vuelto.toLocaleString('de-DE', { maximumFractionDigits: 2 });
            document.getElementById('cambio').value = vuelto;
        }

        //return true;
    }
    ,
    teclado: function () {

        $('body').off('keydown');
        $('body').on('keydown', function (event) {

            console.log('event', event.keyCode);

            if (event.ctrlKey || event.metaKey) {
                switch (String.fromCharCode(event.which).toLowerCase()) {
                    case 's':
                        event.preventDefault();
                        Utilities.setfocus("#dineroentregado", 100);
                        break;
                    case 'o':
                        event.preventDefault();
                        Utilities.setfocus("#descuentoenporcentaje", 100);
                        break;
                    case 'l':
                        event.preventDefault();
                        Utilities.setfocus("#descuentoenvalor", 100);
                        break;
                    case 'q':
                        event.preventDefault();
                        $('#id_vendedor').trigger('chosen:open');

                        $('#id_vendedor_chosen .chosen-search input').focus();
                        setTimeout(function () {
                            $('#id_vendedor').trigger('chosen:activate');

                        }, 250);
                        break;
                    case 'a':
                        event.preventDefault();

                        $('#tipoventa').trigger('chosen:open');

                        $('#tipoventa_chosen .chosen-search input').focus();
                        setTimeout(function () {
                            $('#tipoventa').trigger('chosen:activate');

                        }, 250);
                        break;
                    case 'm':
                        event.preventDefault();


                        setTimeout(function () {
                            $('#id_cliente').select2('open');

                        }, 250);
                        break;
                    case 'g':
                        event.preventDefault();

                        Utilities.setfocus(".inputsearchproduct:last-child", 100);
                        break;

                }
            }

            if (event.keyCode == 116) {
                event.preventDefault();
                event.stopPropagation();
                // $(this).next().focus();  //Use whatever selector necessary to focus the 'next' input
                return false;
            }

            if (event.keyCode == 114) {
                event.preventDefault();
                event.stopPropagation();
                if ($(".modal").is(":visible")) {
                    return false;
                }

                Utilities.showPreloader();

                $.ajax({
                    url: baseurl + 'venta',
                    success: function (data) {
                        if (data.error == undefined) {
                            $('#page-content').html(data);
                        } else {
                            Utilities.alertModal('<h4>' + data.error + '</h4>', 'warning', true);
                        }
                        Utilities.hiddePreloader();
                    },
                    error: function (response) {
                        Utilities.hiddePreloader();
                        Utilities.alertModal('<h4>Ha ocurrido un error al realizar la operacion</h4>', 'warning', true);
                    }
                });
            }


            //F6
            if (event.keyCode == 117) {
                event.preventDefault();
                if ($("#generarventa").is(":visible")) {

                    if (!$("#btnRealizarVentaAndView").hasClass("disabled")) {
                        $("#btnRealizarVentaAndView").addClass("disabled");
                        Venta.hacerventa(1);
                    }
                } else {
                    if (!$("#seleccionunidades").is(":visible") && !$("#ventasabiertas").is(":visible")) {
                        $("#generarventa").modal('show');
                    }
                }
            }

            //F2
            if (event.keyCode == 113) {
                event.preventDefault();
                if ($("#generarventa").is(":visible")) {

                    if (!$("#realizarventa").hasClass("disabled")) {
                        $("#realizarventa").addClass("disabled");
                        Venta.hacerventa(0);
                    }

                }
            }

            //F4
            if (event.keyCode == 115) {
                event.preventDefault();

                Venta.diretPrintCotizar();

            }

            //F7
            if (event.keyCode == 118) {
                event.preventDefault();

                $("#venta_status").val('EN ESPERA');
                Venta.hacerventa(0);

            }
            //F1
            if (event.keyCode == 112) {
                event.preventDefault();
                $("#formasdepagomodal").modal('show');
                $("#formpagototalapagar").html($("#totApagar2").val());

            }


            if (event.keyCode == 13) {
                //pregunto si esta visible el modal se seleccion de productos y que el input de buscar sea vacio,
                //ya que si tiene algo ese input y se presiona enter, debe es buscar mas resultados
                if ($("#seleccionunidades").is(":visible") && $('#seleccionunidades input[type*=search]').val() == "") {

                    event.preventDefault();
                    if (Venta.submited === false) {
                        Venta.submited = true;
                        Venta.agregarProducto();
                    }

                }
            }

            if (event.keyCode == 27) {

                if ($("#seleccionunidades").is(":visible")) {

                    $("#seleccionunidades").modal('hide');
                    Utilities.setfocus(".inputsearchproduct:last-child");

                }
            }


            //FLECHA PARA ABAJO
            if (event.keyCode == 40) {

                if ($("#seleccionunidades").is(":visible")) {

                    $("#tablaproductos_filter input").blur();
                    if ($(".ui-selected").length != 0) {


                        var next = parseInt(Venta.tablaproductos.row('.ui-selected').index());
                        var len = parseInt(Venta.tablaproductos.page.info().end);

                        next = next + 1;


                        if (next == len) {
                            next = 0;
                        }


                        Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(" + next + ")"));


                        return 0;
                    }
                } else {

                }
            }

            //FECLAHA PARA ARRIBA
            if (event.keyCode == 38) {

                if ($("#seleccionunidades").is(":visible")) {
                    $("#tablaproductos_filter input").blur();


                    var next = parseInt(Venta.tablaproductos.row('.ui-selected').index());
                    var len = parseInt(Venta.tablaproductos.page.info().end) - 1;

                    if (next == 0) {
                        next = len;
                    } else {
                        next = next - 1;
                    }

                    if ($(".ui-selected").length != 0) {

                        Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(" + next + ")"));

                        return 0;
                    }
                }
            }

            if (event.keyCode == 9) {

                if ($("#generarventa").is(":visible")) {
                    e.stopPropagation();
                    e.preventDefault();
                    if ($("#importe").is(':focus')) {
                        $("#importe").blur();
                        $("#btnRealizarVentaAndView").focus();
                        return false;
                    }

                    if ($("#btnRealizarVentaAndView").is(':focus')) {
                        $("#btnRealizarVentaAndView").blur();
                        $("#importe").focus();
                        return false;
                    }
                }

                if ($("#seleccionunidades").is(":visible")) {
                    event.stopPropagation();
                    event.preventDefault();


                    setTimeout(function () {
                        $("#agregarproducto").focus();
                    }, 500);
                    return false;

                }
            }

        });

    }
    ,

    processPending() {
        $("#venta_status").val('EN ESPERA');
        Venta.hacerventa(0);
    }
    ,
    events: function () {


        $("#mensajeprodcalert .closed").click(function (event) {
            $(this).parents("#mensajeprodcalert").fadeOut(350);
            return false;
        });

        if (parseInt(Venta.last_factura) >= parseInt($("#resolucion_avisar").val())) {
            Utilities.alertModal('El número de factura ha llegado a ' + Venta.last_factura + '. Su número de facturas autorizadas por la DIAN está apunto de acabarse ', 'warning');
        }

        var diasenquevencec = parseInt($("#resolucion_avisar_vencimiento").val());
        if (diasenquevencec < 16) {
            Utilities.alertModal('La resolución de la dian se vence en ' + diasenquevencec + ' dia(s)', 'warning');
        }


        $("#formadepago").on('click', function () {
            $("#formasdepagomodal").modal('show');
            $("#formpagototalapagar").html($("#totApagar2").val());
        });
        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                Venta.buscarproductos($(this).val());
            }
        });
        $(".closegenerarventa").on('click', function () {
            if ($("#ventamodal").length > 0 || $("#generarventa").length > 0) {
                $("#generarventa, #ventamodal").modal('hide');
            } else {
                $.ajax({
                    url: baseurl + 'venta/pedidos',
                    success: function (data) {
                        $('#page-content').html(data);
                    }
                });
            }
        });
        $(".closemodificarcantidad").on('click', function () {
            $("#modificarcantidad").modal('hide');
        });
        $("#cancelar").on('click', function (data) {
            if ($("#ventamodal").length > 0) {
                $("#ventamodal").modal('hide');
            } else {
                $.ajax({
                    url: baseurl + 'principal',
                    success: function (data) {
                        $('#page-content').html(data);
                    }
                });
            }
        });

        $("#reiniciar").on('click', function (data) {
            if ($("#ventamodal").length > 0) {
                return false;
            } else {
                $.ajax({
                    url: baseurl + 'venta',
                    success: function (data) {
                        $('#page-content').html(data);
                    }
                });
            }
        });

        ajaxRefresh = function (url) {
            return $.ajax({
                url: baseurl + 'venta' + url
            });
        };

        Venta.teclado();
        $("#cantidad").focus(function () {
            $("#cantidad").select();
        })

        $(".chosen").chosen({
            width: "100%",
            search_contains: true,
        })

        Venta.definirselectcliente()

        setTimeout(function () {
            $("#selectproductos").trigger('chosen:open');
        }, 50);


        $("#lstTabla").hide();

        /***
         * Esta funcion se activa al hacer click en el boton guardar y levanta el modal para ingresar el importe y calcular el vuelto
         */
        $("#terminarventa").on('click', function () {
            // $("#venta_status").val('GENERADO');

            if (Venta.devolver == 'true' && Venta.uuid != undefined && Venta.uuid != '') {
                Venta.hacerventa(0, 0);
            } else {
                $("#generarventa").modal('show');
            }


        });
        $("#terminarventapendiente").on('click', function () {

            Venta.processPending();
        });

        $("#facturarElectronicamente").on('click', function () {

            // Venta.facturaVentaNacional(0);
            //  $("#generarventa").modal('show');
            Venta.hacerventa(0, 1);
            // $("#generarventa").modal('show');
        });


        $("#refrescarstock").on('click', function () {
            Venta.refrescarstock();
        });

        $("#abrirventas").on('click', function () {
            if ($("#ventamodal").length > 0) {
                return false;
            }
            Venta.buscarventasabiertas();
        });

        $("#aceptarformasdepago").on('click', function () {
            Venta.guardarFormasDePago();
        });

        $('#generarventa').on('hidden.bs.modal', function (e) {
            $("#importe").val(0, 0);
            // $("#vuelto").val(0, 0);
        });

        $("#seleccionunidades").on('hidden.bs.modal', function () {

            $(".inputsearchproduct").focus();
            $(".inputsearchproduct").select();
        });

        $(".closeseleccionunidades").on('click', function () {
            $("#seleccionunidades").modal('hide');
            Utilities.setfocus(".inputsearchproduct:last-child");
        });


        $('#seleccionunidades').on('shown.bs.modal', function (e) {

            //Venta.tablaproductos = TablesDatatables.init(0, 'tablaproductos', 'asc');

            Venta.selectable();
            //quito el selectable
            $("#tablaproductos_filter input").on("click", function () {

                $(".ui-selected").removeClass("ui-selected");
            });


            $("#tablaproductos_filter input").on('keyup', function () {
                //Venta.tablaproductos.draw();
                //Venta.selectable();
                //Venta.teclado()
                //  $(".ui-selected").removeClass("ui-selected");
            });


        });


        $('#modalcatalogo').on('shown.bs.modal', function (e) {

            // $("#tablaproductos").dataTable();
            TablesDatatables.init(0, 'tabacatalogo');

        });

        $('#modificarcantidad').on('hidden.bs.modal', function (e) {
            $("#bono_show").remove();
            bonos = new Array();

        });


        $('#modalcatalogo, #precioabiertomodal').on('hidden.bs.modal', function (e) {
            //$("#bono_show").remove();
            // Utilities.setfocus(".inputsearchproduct:last-child");
            var count = parseFloat(Venta.tablalistaventa.rows().count()) - 2;

            Venta.tablalistaventa.cell(count, 2).focus();
        });


        $('#mvisualizarVenta').on('hidden.bs.modal', function (e) {


            var urlRefresh = $('#url_refresh').val();

            if (urlRefresh != undefined) {
                $("#generarventa").modal('hide');


                ajaxRefresh(urlRefresh).success(function (data) {
                    Utilities.hiddePreloader();
                    $('#page-content').html(data);
                });
            }
        });


    }
    ,

    definirselectcliente: function () {

        if (Venta.selectcliente != "") {
            Venta.selectcliente.select2("destroy");
            Venta.selectcliente.html("<option value=''>Seleccione<option>");
        }
        Venta.selectcliente = $("#id_cliente").select2(
            {
                //dropdownParent: $("#modal_enviar"),
                allowClear: true,
                language: "es",
                ajax: {
                    url: baseurl + "api/clientes/clientesselect2",
                    dataType: 'json',
                    delay: 250,
                    type: 'get',
                    data: function (params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function (data, params) {

                        return {

                            results: data,
                        };
                    },
                    cache: true
                },
                width: "100%",

                placeholder: {
                    id: '', // or whatever the placeholder value is
                    text: 'Buscar Clientes' // the text to display as the placeholder
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work

                language: {
                    inputTooShort: function () {
                        return 'Ingrese un nombre para buscar';
                    },
                    noResults: function () {
                        return "Sin resultados";
                    }
                }
            });

        Venta.selectcliente.on('select2:select', function (e) {
            /*Entra aqui, cuando se seleccione un cliente*/

            /*esto es para que al seleccionar un cliente, elimine la alerta azul, si es que ya habia seleccionado uno anteriormente
             * */
            if (Venta.toastCliSelected != "") {
                Venta.toastCliSelected.reset();
            }

            var data = e.params.data;
            var html = "<strong>";
            if (data.direccion != undefined && data.direccion != null && data.direccion != "null") {
                html += 'Dirección: ' + data.direccion + '<br>'
            }
            if (data.zona_nombre != undefined && data.zona_nombre != null && data.zona_nombre != "null") {
                html += 'Barrio: ' + data.zona_nombre + '<br>'
            }

            if (data.telefono != undefined && data.telefono != null && data.telefono != "null") {
                html += 'Teléfono: ' + data.telefono + '<br>'
            }
            if (data.celular != undefined && data.celular != null && data.celular != "null") {
                html += 'Celular: ' + data.celular
            }
            html += '</strong>'
            /*genero un mensaje  con los datos anteriores*/
            Venta.toastCliSelected = Utilities.alertModal(html, 'info', false);

        });

    }
    ,

    selectable: function () {
        $("#preciostbody").selectable({

            stop: function () {
                var id_producto = $("#preciostbody tr.ui-selected").attr('id');
                // var id_producto = $('#selectproductos').val();
                //getUnidadPrecio(id_unidad, id_producto);

                //  var id = $("#preciostbody tr.ui-selected").attr('id');

                // $("#agregarproducto").blur();
            }


        });


    }
    ,
    init: function (countproducto, unidades, droguerias, tipos_devlucion, tipos_venta, clientes, last_factura) {
        Venta.resetFields();
        Venta.toastCliSelected = "";
        this.countproducto = countproducto;
        this.unidades = unidades;
        this.last_factura = last_factura;
        this.clientes = clientes;
        Venta.FACT_E_habilitacionn = $("#FACT_E_habilitacionn").val();
        Venta.FACT_E_syncrono = $("#FACT_E_syncrono").val();

        this.droguerias = droguerias;
        this.tipos_devlucion = tipos_devlucion;
        this.tipos_venta = tipos_venta;
        this.devolver = $('#devolver').val();


        this.uuid = $('#uuid').val();
        if (Venta.devolver != 'true') {
            this.total = 0;
            this.lst_producto = new Array();
        }
        /*if (Venta.devolver == 'true' && Venta.uuid != '') {
            Venta.notadebito = true;
        }*/

        if ($("#notadebito").val() === '1') {
            this.notadebito = true;
        } else {
            this.notadebito = false;
        }

        this.lstaeliminar = new Array();
        Venta.selectcliente = ""

        Venta.cliente = new Array();
        this.events();
        Venta.getTipoVenta();
        Venta.changeCliente();
        // Utilities.setfocus(".inputsearchproduct:last-child");
        Venta.makeTableKey(false);
        //Venta.tablalistaventa.cell(':eq(0)').focus();


        $('#id_vendedor').trigger('chosen:open');

        $('#id_vendedor_chosen .chosen-search input').focus();
        setTimeout(function () {
            $('#id_vendedor').trigger('chosen:activate');

        }, 250);

        Venta.prepararCamposFe();

    }
    ,

    /**prepara los campos de factguracion electronica */
    prepararCamposFe() {
        let fact_electronica_contingencia_facturador = $("#fact_electronica_contingencia_facturador");
        let selectFeDocument = $("#selectFeDocument");
        let feContingenciaCheck = $("#feContingenciaCheck");
        let feTypeDocument = $("#feTypeDocument");
        feContingenciaCheck.change(function () {


            if (feContingenciaCheck.is(':checked')) {
                selectFeDocument.removeClass('hidden');
            } else {
                selectFeDocument.addClass('hidden');
            }


        })
        feTypeDocument.change(function () {

            let documentType = feTypeDocument.val();

            if (documentType == '3') {
                fact_electronica_contingencia_facturador.removeClass('hidden');
            } else {
                fact_electronica_contingencia_facturador.addClass('hidden');
            }
        });

    },
    initDomicilios: function (sesion, estatusasignado) {

        Venta.estatusasignado = estatusasignado
        jQuery('#modalAsignarDomicilio').on('hidden.bs.modal', function (e) {
            Venta.get_Domicilios();
        });
        Venta.domicilioselected = "";
        $("select").chosen({
            width: "100%",
            search_contains: true
        });

        Venta.datossesion = sesion;
        Venta.get_Domicilios();
    }
    ,
    formatFecha: function (date) {  //formatea las fechas a dia mes ano
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        var mes = (parseInt(date.getMonth()) + parseInt(1))
        return date.getDate() + "-" + mes + "-" + date.getFullYear() + "  " + strTime;
    }
    ,

    get_Domicilios: function () { //index de domicilios
        Utilities.showPreloader();
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var locales = $("#locales").val();
        var estatus = $("#estatus").val();
        var listar = $("#listar").val();

        var data = {
            'desde': fercha_desde,
            'hasta': fercha_hasta,
            'estatus': estatus,
        }

        var ajaxdomicilios = VentaService.getDomicilios(data); //busco los domicilios
        ajaxdomicilios.success(function (data) {
            Utilities.hiddePreloader();
            Venta.definirTablaDomicilios(); //defino la taba
            if (data.ventas) {
                Venta.armarTablaDomicilios(data.ventas); //armo los datos en la tabla

            } else {
                Utilities.alertModal('' + data.result, 'error', true);
                return false;
            }
        });
        ajaxdomicilios.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
        });

    }
    ,

    armarTablaDomicilios: function (domicilios) {  //armo los datos en la tabla de domicilios

        var fechaventa = "";
        var venta_status = "";
        var newrow = {};
        var count = 0;
        for (var i = 0; i < domicilios.length; i++) {

            var venta_id = domicilios[i].domicilio_id;
            venta_status = domicilios[i].domicilio_estatus;
            fechaventa = "";
            newrow = {};
            count = 1;
            newrow[0] = domicilios[i].domicilio_id

            if (domicilios[i].resolucion_prefijo != undefined) {
                newrow[count] = domicilios[i].resolucion_prefijo + '-' + domicilios[i].documento_Numero;
            } else {
                newrow[count] = domicilios[i].documento_Numero;
            }
            count++;

            newrow[count] = domicilios[i].nombres + ' ' + domicilios[i].apellidos; //cliente
            count++;

            fechaventa = new Date();
            fechaventa = new Date(domicilios[i].fecha_created);
            newrow[count] = Venta.formatFecha(fechaventa)
            count++;

            newrow[count] = venta_status;
            count++;
            newrow[count] = domicilios[i].direccion
            count++;

            newrow[count] = domicilios[i].string_promedio
            count++;

            var usuAsignado = domicilios[i].nombre;
            if (usuAsignado == null || usuAsignado == "null") {
                newrow[count] = newrow[count] = '<label class=" label label-success">NO ASIGNADO</label>';
            } else {
                newrow[count] = newrow[count] = '<label class=" label label-success">' + domicilios[i].nombre + '</label>';
            }

            count++;

            newrow[count] = domicilios[i].total
            count++;

            newrow[count] = "";
            if (venta_status == "EN ESPERA") {
                newrow[count] += '<a onclick="Venta.modalAsignarDom(' + venta_id + ')" ' +
                    ' class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip" title="Asignar"> ' +
                    '<i class="glyphicon glyphicon-eye-open"></i> </a>';
            }
            newrow[count] += '<a onclick="Venta.modalVerDetalleDom(' + venta_id + ')" ' +
                ' class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip" title="Ver Detalle"> ' +
                '<i class="fa fa-search"></i> </a>';


            var arreglo = new Array();
            var tr = new Array();
            tr = Object.assign({}, newrow, arreglo);
            Venta.tablaDomicilios.row.add(tr).draw().node();
        }
    }
    ,

    definirTablaDomicilios: function () {  //defino la tabla de los domicilios

        $("#open_table_domicilios").html('');

        $("#open_table_domicilios").append(' <table class="table table-striped dataTable table-bordered" id="tabladomicilios"> ' +
            '<thead> <tr> <th>ID</th> <th>Factura</th> <th>Cliente</th> <th>Fecha</th> ' +
            '<th>Estatus</th> <th>Direcci&oacute;n</th><th>Tiempo Promedio de Entrega</th>' +
            ' <th>Domiciliario</th> <th>Total</th> <th>Acciones</th> </tr> </thead> ' +
            '<tbody id="tbodyTableDomicilios"> </tbody> </table>');

        Venta.tablaDomicilios = null;

        Venta.tablaDomicilios = $('#tabladomicilios').DataTable({
            autoWidth: false,
            "order": [],
            "columDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            "language": {
                "emptyTable": "No se encontraron registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                "infoEmpty": "Mostrando 0 a 0 de 0 resultados",
                "infoFiltered": "(filtrado de _MAX_ total resultados)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ resultados",
                "loadingRecords": "Cargando...",
                "processing": "Buscando...",
                "search": "Búsqueda:",
                "zeroRecords": false,
                "paginate": {
                    "first": "<<",
                    "last": ">>",
                    "next": "<",
                    "previous": ">"
                },
                "aria": {
                    "sortAscending": ": activar ordenar columnas ascendente",
                    "sortDescending": ": activar ordenar columnas descendente"
                }
            }
        });


    }
    ,
    modalAsignarDom: function (domicilio_id) {

        Venta.domicilioselected = domicilio_id;
        Utilities.showPreloader();
        var ajaxusuarios = UsuarioService.getUsuariosByRolNomb("DOMICILIARIO");
        ajaxusuarios.success(function (data) {
            Utilities.hiddePreloader();
            if (data.usuarios) {

                var usuarios = data.usuarios;

                $("#selectdomiciliarios").html('<option value="">Seleccione<option>');

                var html = "";
                for (var i = 0; i < usuarios.length; i++) {
                    html += '<option value="' + usuarios[i].nUsuCodigo + '">' + usuarios[i].identificacion + ' - ' + usuarios[i].nombre + '</option>';
                }

                $("#selectdomiciliarios").append(html);
                $("#selectdomiciliarios").val('');
                $("#selectdomiciliarios").trigger("chosen:updated");

            }
        });
        ajaxusuarios.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error al buscar los domiciliarios', 'error', true);

        });

        $("#modalAsignarDomicilio").modal('show');
    }
    ,

    asocDomicUsuario: function () {  //cuando se presiona el boton de asociar al domidilio a un domiciliario

        if ($("#selectdomiciliarios").val() == "") {
            Utilities.alertModal('Debe seleccionar al menos un domiciliario');
            return false;
        }

        Utilities.showPreloader();

        var ajaxguardar = VentaService.marcarDomicilioComo($("#selectdomiciliarios").val(), Venta.domicilioselected,
            Venta.datossesion.nUsuCodigo, Venta.estatusasignado);
        ajaxguardar.success(function (data) {
            Utilities.hiddePreloader();
            if (data.success) {
                Utilities.alertModal(data.success, 'success');
                $("#modalAsignarDomicilio").modal('show');
                Venta.get_Domicilios();
            } else {
                Utilities.alertModal(data.error, 'error', true);
            }
        });
        ajaxguardar.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error al asignar el domiciliario', 'error', true);

        });

    }
    ,
    initMapaDomicilio: function () {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // You can set it the plugin
                Venta.mapaDomicilios = new google.maps.Map(document.getElementById('mapadomiciliarios'), {
                    zoom: 14,
                    center: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    gestureHandling: 'greedy'
                });
            });
        } else {

            Venta.mapaDomicilios = new google.maps.Map(document.getElementById('mapadomiciliarios'), {
                zoom: 14,
                center: new google.maps.LatLng(3.4150595, -76.5426338, 13.75),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                gestureHandling: 'greedy'
            });

        }

        //digo que cada 30 segundos busco las posiciones
        setInterval(Venta.buscarMapaDomciliarios, 30 * 1000);
        Venta.buscarMapaDomciliarios();
    }
    ,
    buscarMapaDomciliarios: function () {

        var ajaxdomicilios = UsuarioService.getPosiDomiciliario(); //busco los domicilios
        ajaxdomicilios.success(function (data) {
            Utilities.hiddePreloader();
            if (data.usuarios) {
                //armo los datos en la tabla
                Venta.armarMapa(data.usuarios);
            } else {
                Utilities.alertModal('' + data.error, 'error', true);
                return false;
            }
        });
        ajaxdomicilios.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
        });

    }
    ,
    deleteMarkers: function () { //reinicia los globos rojos del mapa
        //Loop through all the markers and remove
        for (var i = 0; i < Venta.markersMapa.length; i++) {
            Venta.markersMapa[i].setMap(null);
        }
        Venta.markersMapa = [];
    }
    ,
    armarMapa: function (usuarios) {


        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        Venta.deleteMarkers();
        for (i = 0; i < usuarios.length; i++) {

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(usuarios[i]['latitud'], usuarios[i]['longitud']),
                map: Venta.mapaDomicilios
            });

            Venta.markersMapa.push(marker);

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(usuarios[i]['username'] + " - " + usuarios[i]['texto_posicion']);
                    infowindow.open(Venta.mapaDomicilios, marker);
                }
            })(marker, i));
        }

    }
    ,

    viewMapaDomiciliarios: function () {
        //llama a la url que actualiza la configuracion de mostrar el mapa de DOmicilio en SI,
        //para que cuando haga el redirec al index, sepa que debe mostrar el mapa

        var url = baseurl + "venta/callMapaDomicilios"; //antes de mandar a abrir en una nueva ventana la vista del mapa de domiciliarios,
        //pasa por aqui y setea un valor en la bd para que cuando cargue el controlador principal, sepa que va a mandar a llamar
        //la vista del mapa en el javscript
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data) {
                window.open(baseurl)

            }, error: function () {

            }
        });
    }
    ,
    modalVerDetalleDom: function (domicilio_id) {

        Venta.domicilioselected = domicilio_id;
        Utilities.showPreloader();
        var datos = {
            domicilio_id: domicilio_id
        }
        var ajax = VentaService.getHistDomicilio(datos);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            if (data.error == undefined) {

                Venta.definirTablaHistDom();
                Venta.armarTablaHistorial(data.historial, data.infodomicilio);
                $("#modalDetalleDom").modal('show');

            } else {
                Utilities.alertModal(data.error, 'error', true);
            }
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error al buscar el historial', 'error', true);

        });
    }
    ,
    definirTablaHistDom: function () {
        $("#open_table_historial").html('');
        $("#open_table_historial").append(' <table class="table table-striped dataTable table-bordered" id="tablahistorial"> ' +
            '<thead> <tr>  <th>Fecha</th> <th>Usuario</th> <th>Estatus</th> <th>Comentario</th> </tr> </thead> ' +
            '<tbody > </tbody> </table>');

        Venta.tablaHistorial = null;

        Venta.tablaHistorial = $('#tablahistorial').DataTable({
            autoWidth: false,
            "order": [],
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            "language": {
                "emptyTable": "No se encontraron registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                "infoEmpty": "Mostrando 0 a 0 de 0 resultados",
                "infoFiltered": "(filtrado de _MAX_ total resultados)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ resultados",
                "loadingRecords": "Cargando...",
                "processing": "Buscando...",
                "search": "Búsqueda:",
                "zeroRecords": false,
                "paginate": {
                    "first": "<<",
                    "last": ">>",
                    "next": "<",
                    "previous": ">"
                },
                "aria": {
                    "sortAscending": ": activar ordenar columnas ascendente",
                    "sortDescending": ": activar ordenar columnas descendente"
                }
            }
        });
    }
    ,
    armarTablaHistorial: function (historial, infodomicilio) {  //armo los datos en la tabla de historial

        var newrow = {};
        var count = 0;
        for (var i = 0; i < historial.length; i++) {

            if (historial[i].estatus == "ASIGNADO") {
                $("#lblusuarioasigna").html('Usuario que Asigna a Domiciliario: ' + infodomicilio[0].usuasig_nombre);
            }
            count = 0;
            newrow = {};
            fecha = "";
            fecha = new Date();
            fecha = new Date(historial[i].fecha);
            newrow[count] = Venta.formatFecha(fecha)
            count++;

            newrow[count] = historial[i].nombre; //usuario que hizo la acction
            count++;

            newrow[count] = '<label class=" label label-success">' + historial[i].estatus + '</label>'
            count++;

            newrow[count] = historial[i].comentario;
            count++;

            var arreglo = new Array();
            var tr = new Array();
            tr = Object.assign({}, newrow, arreglo);
            Venta.tablaHistorial.row.add(tr).draw().node();
        }
    },
    /**
     * Levanta el modal para marcar o desmarcar, las columnas de los productos, que se mostrarán en el modal de ventas
     */
    modalAddColumnasToProduct: function () {

        var ajax = VentaService.getAllColumnsModalProductos();
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            if (data.error == undefined) {

                Venta.armarColumnasToModalProductos(data);

            } else {
                Utilities.alertModal(data.error, 'error', true);
            }
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error al buscar los datos', 'error', true);

        });

        $('#agregar').modal('show');
    },
    armarColumnasToModalProductos: function (data) {

        $('#tbodycolumnas').html('');
        var html = '';

        if (data.columnas != undefined && Object.keys(data.columnas).length > 0) {
            var checked = '';
            jQuery.each(data.columnas, function (i, value) {

                checked = '';

                if (value.mostrar == 1) {
                    checked = 'checked'
                }

                html += '<tr>';
                html += '<td>' + value.nombre_mostrar + '</td>';
                html += '<td><input type="checkbox" class="columnas_to_products" data-id="' + value.id + '" name="mostrar_' + value.id + '" ' + checked + ' ></td>';
                html += '</tr>';
            })
        }
        $('#tbodycolumnas').html(html);
    },

    /**
     * Cuando le doy click al boton confirmar, para guardar las columnas.
     */
    guardarColumnsProductos: function () {

        var arrayToSend = {
            'columnas': []
        };

        $("#tbodycolumnas tr td input[name^='mostrar_']").each(function (i, fila) {
            arrayToSend.columnas[i] = {};
            arrayToSend.columnas[i].id = $(this).attr('data-id');
            arrayToSend.columnas[i].checked = $(this).is(':checked');
        });

        console.log('arrayToSend', arrayToSend)

        var ajax = VentaService.saveColumnsModalProductos(arrayToSend);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            if (data.error == undefined) {

                var finalizar = VentaService.indexColumnasModalProductos();
                Utilities.alertModal(data.message, 'success');

                finalizar.success(function (data2) {

                    $('#agregar').on('hidden.bs.modal', function () {
                        $('#page-content').html(data2);

                        $("#successspan").html(data.success);

                        $("#success").css('display', 'block');
                    });
                    $('#agregar').modal('hide');
                    Utilities.hiddePreloader()
                });


            } else {
                Utilities.alertModal(data.message, 'warning', true);
            }
        });
        ajax.error(function (response) {
            Utilities.hiddePreloader();

            if (response.error != undefined) {
                Utilities.alertModal(response.message, 'error', true);
            } else {
                Utilities.alertModal('Ha ocurrido un error al guardar los datos', 'error', true);
            }
        });

    }
}