var AjusteInventario = {
    metodos_pago: new Array(),
    facturas: new Array(),
    tipo: '',
    soloajuste: false,
    totalCosto: 0,
    unidades: new Array(),
    INVENTARIO_UBICACION_REQUERIDO: false,
    documentos: new Array(),
    productos: new Array(),
    ubicaciones: new Array(),
    info:new Array(),
    cache: {},
    tablalista: null,
    producto_seleccionadoid: '',
    total_minima: 0,
    inizializeDomCache: function () {
        this.cache.borrar = $('#borrar');
        this.cache.id_borrar = $('#id_borrar');
        this.cache.nom_borrar = $('#nom_borrar');
        this.cache.globalModal = $('#globalModal');
        this.cache.pagecontent = $('#page-content');
        this.cache.guardar = $('#guardar');
        this.cache.select_producto = $('#select_producto');
        this.cache.locales_in = $('#locales_in');
        this.cache.input_datepicker = $('.input-datepicker');
        this.cache.select_chosen = $('.select-chosen');
        this.cache.tipo = $('#tipo');
        this.cache.tipoajuste = $('#tipoajuste');

    },
    init: function (unidades, tipo, documentos, ubicaciones, productos, soloajuste, INVENTARIO_UBICACION_REQUERIDO) {

        console.log(INVENTARIO_UBICACION_REQUERIDO);
        this.inizializeDomCache();
        this.unidades = unidades;
        this.tipo = tipo;
        this.ubicaciones = ubicaciones;
        this.INVENTARIO_UBICACION_REQUERIDO = INVENTARIO_UBICACION_REQUERIDO;

        this.soloajuste = soloajuste;
        this.tablalista = null;
        this.producto_seleccionadoid = '';
        this.documentos = documentos;
        this.productos = productos;
        this.events();
        this.changelocal();

        //si el tipo es todos
        if (tipo == "todos" || tipo == "byGroup") {
            AjusteInventario.organizarTodosProductos();

        } else {
            AjusteInventario.makeTableKey(false);
            AjusteInventario.tablalista.cell(0, 2).focus();
        }

        Utilities.setfocus(".inputsearchproduct:last-child");
        // AjusteInventario.makeTableKey();
    },
    organizarTodosProductos: function () {

        Utilities.showPreloader();
        $("#columnas").html('');
        var buscar = {};
        buscar.value = "";
        var html = "";
        TablesDatatablesLazzy.init(baseurl + ProductoService.urlApi + '/productosUnidades', 0, 'tablaresult', {
            local: $('#locales_in').val(),
            tipo: AjusteInventario.tipo,
            grupo: $('#grupo').val(),
            'is_paquete': 0,
            'is_prepack': 0,
            'is_obsequio': 0
        }, false, false, AjusteInventario.validar_mismo_trespecial, AjusteInventario.beforeExistenciaProducto, false, true);
        Utilities.hiddePreloader();

    },
    makeTableKey: function (init) {

        AjusteInventario.tablalista = $("#tablaresult").DataTable({
            keys: true,
            "searching": false,
            "ordering": false,
            "bPaginate": false,
            fixedHeader: {
                header: true,
                footer: true
            },
            scrollY: '30vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            "sScrollX": "100%",
        });

        AjusteInventario.keyFocusEvent();

        if (init) {
            AjusteInventario.tablalista.cell(0, 2).focus();

        }
    },

    keyFocusEvent: function () {
        // Inline editing on tab focus

        AjusteInventario.tablalista.off('key-focus');
        AjusteInventario.tablalista.on('key-focus', function (e, datatable, cell) {
            var rowData = datatable.row(cell.index().row).data();
            var colData = cell.data();
            var objectCell = $($.parseHTML(colData));

            if ($("#" + objectCell.attr('id')).length != 0) {
                //var offset = elemento.offset()
                //$(".inner_table").animate({scrollLeft: offset.left}, 0);
                $("#" + objectCell.attr('id')).focus();
            }

            var rowNode = datatable.row(cell.index().row).node();

            var id = $(rowNode).attr("data-producto_id");

            if (AjusteInventario.validar_mismo_trespecial(id) == true) {
                InventarioService.buscarExistenciaProducto(AjusteInventario.unidades, id, null, null, $("#locales_in").val());
            }

        });
    },

    validar_mismo_trespecial: function (id) {
        //esta funcion valida si el tr sobre esta el click, es el mismo,
        //para no tener que estar consultando los datos del producto cada vez que se hace click sobre el mismo tr

        if (AjusteInventario.producto_seleccionadoid == id) {
            return false;
        }
        AjusteInventario.producto_seleccionadoid = id;

        return true;
    },

    events: function () {
        $("select").chosen({'width': '100%'})


        AjusteInventario.cache.select_chosen.chosen({
            width: "100%"
        });
        AjusteInventario.cache.input_datepicker.datepicker({weekStart: 1, format: 'dd-mm-yyyy'});

        $("#locales, #tipo, #tipoajuste").on("change", function () {
            AjusteInventario.changelocal();
        });

        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                AjusteInventario.buscarproductos($(this).val());
            }
        });
        $('#seleccionunidades').on('shown.bs.modal', function (e) {
            //  TablesDatatables.init(0, 'tablaproductos', 'asc');

            $("#preciostbody").selectable({
                stop: function () {
                    var id = $("#preciostbody tr.ui-selected").attr('id');
                }
            });

            $("#tablaproductos_filter input").on("focus", function () {
                // console.log('quito');
                $(".ui-selected").removeClass("ui-selected");
            });
            $("#tablaproductos_filter input").on('keyup', function () {

                $(".ui-selected").removeClass("ui-selected");
            });

            setTimeout(function () {
                Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(0)"));
            }, 1000);
        });

        $('body').off('keydown');
        $('body').on('keydown', function (e) {
            if (e.keyCode == 13) {

                if ($("#seleccionunidades").is(":visible")) {
                    e.preventDefault();
                    AjusteInventario.addProduct();
                }
            }

            if (e.keyCode == 40) {
                if ($("#seleccionunidades").is(":visible")) {

                    $("#tablaproductos_filter input").blur();

                    if ($(".ui-selected").length != 0) {

                        var next = parseInt($(".ui-selected").attr('tabindex'));
                        var len = jQuery("#preciostbody tr").length;

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

            if (e.keyCode == 38) {

                if ($("#seleccionunidades").is(":visible")) {
                    $("#tablaproductos_filter input").blur();
                    var next = parseInt($(".ui-selected").attr('tabindex'));
                    var len = parseInt(jQuery("#preciostbody tr").length);
                    if (next == 0) {
                        next = len - 1;
                    } else {
                        next = next - 1;
                    }

                    if ($(".ui-selected").length != 0) {

                        Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(" + next + ")"));

                        return 0;
                    }
                }
            }


        });
    },
    buscarproductos: function (valor) {
        Utilities.showPreloader();
        // var table = $('#tablaproductos').DataTable();
        //table.destroy();
        $("#preciostbody").html('');


        var constock = false;

        if ($("#tipo").val() == "SALIDA") {
            constock = true;
        }


        TablesDatatablesLazzy.init(baseurl + 'api/productos/specialSearchLazzy', 0, 'tablaproductos',
            {
                local: $("#locales_in").val(),
                constock: constock,
                operacion: 'INVENTARIO',
                is_paquete: 0,
                'search': valor
            });


        Utilities.hiddePreloader();
        $("#seleccionunidades").modal('show');
    },
    changelocal: function () {
        $.ajax({
            url: baseurl + 'inventario/ajusteinventario_by_local',
            data: {
                'id_local': $("#locales").val(),
                'tipo': $("#tipo").val(),
                'tipoajuste': $("#tipoajuste").val(),
                'soloajuste': AjusteInventario.soloajuste
            },
            type: 'POST',
            success: function (data) {
                $("#tabla").html(data);
                TablesDatatables.init(0, 'tablaresultado');
            }
        });
    },

    ver: function (id) {
        AjusteInventario.cache.globalModal.html('');
        AjusteInventario.cache.globalModal.load(baseurl + 'inventario/verajuste/' + id);
        AjusteInventario.cache.globalModal.modal({show: true});
    },
    vistaPrevia: function (id) {
        AjusteInventario.cache.globalModal.html('');
        AjusteInventario.cache.globalModal.load(baseurl + 'inventario/vistapreviaAjuste/' + id);
        AjusteInventario.cache.globalModal.modal({show: true});
    },
    print: function (id) {
        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;


        if (is_nube) {
            console.log(id);

            $.ajax({
                url: baseurl + 'api/Inventario/data_print_movimiento',
                type: 'GET',
                data: {
                    id: id
                },
                success: function (data) {
                    var urltickera = TICKERA_URL;

                    var url = urltickera + '/directPrintAjuste/';

                    console.log(url);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            data: data,
                            id: id,
                            impresora: IMPRESORA

                        },
                        success: function (data2) {
                            Utilities.hiddePreloader();
                            TablesDatatables.init(0, 'history', 'desc');
                            console.log(data2);
                            if (data2.result == 'success') {
                                Utilities.alertModal('Se ha enviado el documento a la impresora', 'success');


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
            AjusteInventario.cache.globalModal.load(baseurl + 'inventario/directPrintAjuste/' + id);
            Utilities.alertModal('Se ha enviado el documento a la impresora', 'success');
        }

    },
    directPrintInformeDiferencia: function (id) {
        AjusteInventario.cache.globalModal.load(baseurl + 'inventario/directPrintInformeDiferencia/' + id);
        Utilities.alertModal('Se ha enviado el documento a la impresora', 'success');
    },
    informeDiferencia: function (id) {
        AjusteInventario.cache.globalModal.html('');
        AjusteInventario.cache.globalModal.load(baseurl + 'inventario/informeDiferencia/' + id);
        AjusteInventario.cache.globalModal.modal({show: true});
    },
    guardar: function () {
        $("#btn_ajusteInventario_guardar").prop('disabled',true)


        if ($('#locales_in').val() == 'seleccione') {
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            Utilities.alertModal('<h4>Debe Seleccionar la bodega</h4>', 'warning');
            return false;
        }
        if ($('#fecha').val() == '') {
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            Utilities.alertModal('<h4>Debe Ingresar la fecha</h4>', 'warning');
            return false;
        }
        if ($("#columnas tr").length == 0) {
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            Utilities.alertModal('<h4>Debe Seleccionar al menos un producto</h4>', 'warning');
            return false;
        }


        if (AjusteInventario.tipo == 'ajuste') {
            if ($('#tipoajuste').val() == '') {
                $("#btn_ajusteInventario_guardar").prop('disabled',false)
                Utilities.alertModal('<h4>Debe Seleccionar el movimiento</h4>', 'warning');
                return false;
            }
        }
        if (AjusteInventario.tipo == 'byGroup') {
            if ($('#grupo').val() == '') {
                $("#btn_ajusteInventario_guardar").prop('disabled',false)
                Utilities.alertModal('<h4>Debe Seleccionar el grupo</h4>', 'warning');
                return false;
            }
        }



        AjusteInventario.info= Utilities.alertModal('<h4>Por favor espere</h4>', 'info',false);
        setTimeout( function (){
            AjusteInventario.saveaftertwosecons();
        },1000)

    },

    saveaftertwosecons: function (){

        var miJSON = '';
        if(AjusteInventario.tipo=="todos"){
            App.generarBackup(0);
        }
        Utilities.showPreloader();
        jQuery.each(AjusteInventario.documentos, function (i, value) {

            if (value.documento_id == AjusteInventario.cache.tipoajuste.val()) {
                miJSON = JSON.stringify(value);
            }
        });


        var ajaxguardar = AjusteInventarioService.guardar($('#formagregar').serialize() + '&tipodocumento=' + miJSON);
        ajaxguardar.success(function (data) {
            AjusteInventario.info.reset();
            Utilities.hiddePreloader();
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            if (data.error != undefined) {
                Utilities.alertModal(data.error, 'danger');
            } else {

                Utilities.alertModal('La operación se ha ralizado con éxito', 'success');
                if (AjusteInventario.tipo == 'ajuste') {

                    var callback = AjusteInventarioService.index();

                } else {
                    var callback = AjusteInventarioService.add(AjusteInventario.tipo);
                }

                AjusteInventario.print(data.id);


                callback.success(function (datacallback) {

                    AjusteInventario.cache.pagecontent.html(datacallback);
                });

            }
        });
        ajaxguardar.error(function () {
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            AjusteInventario.info.reset();
            Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'danger');
            Utilities.hiddePreloader();
        })

    },

    add: function (tipo) {
        Utilities.showPreloader();
        var ajax = AjusteInventarioService.add(tipo);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            AjusteInventario.cache.pagecontent.html(data);
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    },

    remover: function (id) {
        var tr = AjusteInventario.tablalista.row('#lista_' + id)

        tr.remove().draw();

        AjusteInventario.tablalista.cell(AjusteInventario.tablalista.rows().count() - 2, 0).focus();


    },
    addProduct: function () {
        var id_producto = $("#preciostbody tr.ui-selected").attr('id');


        if (id_producto != undefined) {

            if ($("#lista_" + id_producto).length > 0) {
                Utilities.alertModal('El producto seleccionado ya fué agregado', 'warning');

            } else {

                if (id_producto != undefined) {

                    Utilities.showPreloader();

                    AjusteInventario.producto_seleccionadoid = id_producto;
                    var ajax = UnidadesService.getUnidadesByProd(id_producto);
                    ajax.success(function (data) {
                        var producto_selected = {};

                        producto_selected.producto_codigo_interno = data[0].producto_codigo_interno != undefined ? data[0].producto_codigo_interno : '';
                        producto_selected.producto_nombre = data[0].producto_nombre;
                        producto_selected.producto_id = data[0].producto_id;
                        producto_selected.producto_ubicacion_fisica = data[0].producto_ubicacion_fisica;


                        AjusteInventario.addproductoTotable(data, producto_selected);
                        AjusteInventario.appendtrvacio();

                        setTimeout(function () {
                            AjusteInventario.tablalista.cell(AjusteInventario.tablalista.rows().count() - 2, 2).focus();
                        }, 500);
                        InventarioService.buscarExistenciaProducto(Traslado.unidades, id_producto, null, null, $("#locales_in").val());
                        $('#seleccionunidades').modal('toggle');
                        Utilities.hiddePreloader();
                    });
                    ajax.error(function () {
                        Utilities.alertModal('Ocurrió un error', 'warning');
                        Utilities.showPreloader();
                    })
                } else {
                    Utilities.alertModal('Debe seleccionar un producto', 'warning');
                }
            }
        }
    },

    beforeExistenciaProducto: function (id_producto) {

        InventarioService.buscarExistenciaProducto(AjusteInventario.unidades, id_producto, null, null, $("#locales_in").val());

    },
    addproductoTotable: function (unidades, producto_selected) {

        var newrow = {};

        var count = 1;

        if (AjusteInventario.tipo != 'todos') {
            $("#columnas tr:last").remove();
        }
        var nombre = producto_selected.producto_nombre;

        newrow[0] = producto_selected.producto_codigo_interno + '<input type="hidden" name="id_producto[]" value="' + producto_selected.producto_id + '">';
        //class="center" width="10%"

        newrow[count] = nombre + '<input type="hidden" name="nombre_producto[' + producto_selected.producto_id + ']" value="' + nombre + '">';
        count++;
        var select_ubic = '<select name="ubicacion_producto_' + producto_selected.producto_id + '"><option selected value="">-Seleccione-</option>';

        jQuery.each(AjusteInventario.ubicaciones, function (i, val) {
            select_ubic += '<option value="' + val.ubicacion_id + '"'

            console.log(producto_selected);
            if (val.ubicacion_id === producto_selected.producto_ubicacion_fisica) {
                select_ubic += ' selected ';
            }
            select_ubic += '>' + val.ubicacion_nombre + '</option>';
        });
        select_ubic += '</select>';
        newrow[count] = select_ubic;
        count++;

        var tienelauniad = false;
        var readonly = '';
        var disabled = 'disabled';
        jQuery.each(AjusteInventario.unidades, function (i, value) {
            tienelauniad = false;

            var costo = 0;
            jQuery.each(unidades, function (j, unidad) {
                if (parseFloat(value.id_unidad) == parseFloat(unidad.id_unidad)) {
                    tienelauniad = true;
                    costo = unidad.costo;
                    if (isNaN(costo) || costo == null) {
                        costo = 0;
                    }
                }
            });

            readonly = '';

            if (!tienelauniad) {
                readonly = 'readonly';

            }
            disabled = '';
            if (AjusteInventario.tipo == 'todos' && !tienelauniad) {
                disabled = 'disabled';
            }

            newrow[count] = '<input ' + readonly + '  ' + disabled + ' type="text" class="form-control" onkeydown="return soloNumeros(event);" ';

            if (AjusteInventario.tipo != 'todos') {

                newrow[count] += ' ' +
                    ' onkeyup="AjusteInventario.validaStock(this,event,' + producto_selected.producto_id + ',' + value.id_unidad + '); ' +
                    '' +
                    'AjusteInventario.multiplicaCosto(' + producto_selected.producto_id + ',' + value.id_unidad + ',\'SUMA\', event);" ';
            }
            newrow[count] += ' id="cantidad_' + producto_selected.producto_id + '_' + value.id_unidad + '"' +

                ' name="cantidad_' + producto_selected.producto_id + '[]" value="" >';
            newrow[count] += '<input ' + readonly + '  type="hidden" class="form-control" name="unidad_' + producto_selected.producto_id + '[]" value="' + value.id_unidad + '">';
            count++;


            if (AjusteInventario.tipo == 'ajuste') {
                newrow[count] = '<input readonly  class="form-control" onkeydown="return soloNumeros(event);" type="text"' +
                    ' name="costo_unitario' + producto_selected.producto_id + '[]" value="' + costo + '" ' +
                    '  id="costo_unitario_'
                    + producto_selected.producto_id + '_' + value.id_unidad + '">';
                count++;
                newrow[count] = '<input ' + readonly + '  class="form-control costototal" onkeydown="return soloNumeros(event);" type="text"' +
                    ' name="costo_' + producto_selected.producto_id + '[]" value="" ' +
                    ' onkeyup="AjusteInventario.divideCosto(' + producto_selected.producto_id + ',' + value.id_unidad + ',\'SUMA\', event);"' +
                    ' id="costo_' + producto_selected.producto_id + '_' + value.id_unidad + '">';
                count++;
            }
        });

        if (AjusteInventario.tipo != 'todos') {
            newrow[count] = '<div class="btn-group"><a class="btn btn-default btn-default btn-default" data-toggle="tooltip" title="Remover" data-original-title="Remover"' +
                'onclick="AjusteInventario.remover(' + producto_selected.producto_id + ')"> <i class="fa fa-trash-o"></i> </a></div>';
            count++;
        }

        if (AjusteInventario.tipo != 'todos') {
            var trvacio = AjusteInventario.tablalista.row('#trvacio');
            trvacio.remove().draw();
        }

        var rowNode = AjusteInventario.tablalista.row.add(newrow).draw().node();
        $(rowNode).attr("id", 'lista_' + producto_selected.producto_id);
        $(rowNode).attr("tabindex", AjusteInventario.tablalista.rows().count());
        $(rowNode).attr("data-producto_id", producto_selected.producto_id);

    },

    divideCosto: function (producto_id, unidad, tipo, event) {

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            return true;
        } else {

            var costo_total = $("#costo_" + producto_id + "_" + unidad).val();

            var cantidad = $("#cantidad_" + producto_id + "_" + unidad).val();

            if (isNaN(costo_total)) {
                costo_total = 0;
            }
            if (isNaN(cantidad)) {
                cantidad = 0;
            }

            var costo_unitario = parseFloat(costo_total) / parseFloat(cantidad);
            if (!isNaN(costo_unitario)) {
                $("#costo_unitario_" + producto_id + "_" + unidad).val(costo_unitario.toFixed(2));
                AjusteInventario.actualizaTotalMovim();
            }

        }

    },

    multiplicaCosto: function (producto_id, unidad, tipo, event) {

        if (event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            return true;
        } else {


            var costo_unitario = $("#costo_unitario_" + producto_id + "_" + unidad).val();
            var cantidad = $("#cantidad_" + producto_id + "_" + unidad).val();


            if (isNaN(costo_total)) {
                costo_total = 0;
            }
            if (isNaN(cantidad)) {
                cantidad = 0;
            }

            var costo_total = parseFloat(costo_unitario) * parseFloat(cantidad);

            if (!isNaN(costo_total)) {
                $("#costo_" + producto_id + "_" + unidad).val(costo_total.toFixed(2));

                AjusteInventario.actualizaTotalMovim();

            }
        }
    },

    actualizaTotalMovim: function () {

        var total = 0.00;
        $("input[class*=costototal]").each(function (i, valor) {
            var valor = $(this);

            if (valor.val() != "") {

                total = parseFloat(total) + parseFloat(valor.val());
            }

        });
        AjusteInventario.totalCosto = total.toFixed(2);
        $("#totalCosto").html('');
        $("#totalCosto").html(total.toFixed(2));

    },
    appendtrvacio: function () {
        var newrow = {};
        var count = 0;

        newrow[count] = '<input type="text" class="form-control inputsearchproduct" id="inputsearchproduct">';
        count++;
        newrow[count] = '';
        count++;
        newrow[count] = '';
        count++;

        jQuery.each(AjusteInventario.unidades, function (i, value) {
            newrow[count] = '<input readonly type="text" id="" class="form-control" value="0" >';
            count++;

            if (AjusteInventario.tipo == 'ajuste') {
                newrow[count] = '<input type="text" id="" class="form-control" value="0" readonly>';
                count++;
                newrow[count] = '<input type="text" id="" class="form-control" value="0" readonly>';
                count++;
            }
        });
        newrow[count] = '';

        var rowNode = AjusteInventario.tablalista.row.add(newrow).draw().node();
        $(rowNode).attr("id", 'trvacio');
        $(rowNode).attr("tabindex", AjusteInventario.tablalista.rows().count());
        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                AjusteInventario.buscarproductos($(this).val());
            }
        });
    }
    ,

    changeTipo: function () {
        var ajax = DocumentoInventarioService.getByTipo(AjusteInventario.cache.tipo.val());
        ajax.success(function (data) {
            var html = '<option value="">DOCUMENTO</option>';
            jQuery.each(data.documentos, function (i, value) {
                html += '<option value="' + value.documento_id + '">' + value.documento_nombre + '</option>';
            });
            AjusteInventario.cache.tipoajuste.html(html);
            AjusteInventario.cache.tipoajuste.trigger("chosen:updated");
        });
    },

    changeGrupo: function () {
        AjusteInventario.organizarTodosProductos();
    },
    validaStock: function (esto, evento, producto_id, unidad_id) {

        if (Traslado.validaTecla(evento)) {

            if (AjusteInventario.cache.tipo.val() == 'SALIDA') {

                var precios = ProductoService.getProducto(producto_id);
                var selector = $("#cantidad_" + producto_id + "_" + unidad_id);
                precios.success(function (data) {
                    var cantidad = parseFloat(selector.val());

                    if (data.control_inven == '1') {


                        //declaro el total en 0
                        AjusteInventario.total_minima = 0;
                        var Ajaxexistecia = InventarioService.soloStock(producto_id, $("#locales_in").val());
                        var unidades_producto = new Array();

                        //busco el stock de este producto y sus unidades
                        Ajaxexistecia.success(function (datastock) {
                            AjusteInventario.total_minima = datastock.stockMinimas;  // el total en stock en minimas
                            unidades_producto = datastock.unidades_producto;  //las unidades de este producto
                        });


                        var totalentabla = Traslado.totalMinimaEnTabla(unidades_producto, producto_id, AjusteInventario.unidades);
                        //pregunto si el total que tengo ingresado en los campos, es mayor al total en stock del producto

                        if (totalentabla > AjusteInventario.total_minima) {

                            Utilities.alertModal('<h4>El total de unidades ingresado es mayor al stock actual!</h4>', 'warning');
                            $("#guardar").prop('disabled', true);
                            //lo coloreo de rojo
                            AjusteInventario.tablalista.row('#lista_' + AjusteInventario.producto_seleccionadoid).nodes().to$().find('td')
                                .each(function () {

                                    $(this).css('color', '#d84545')

                                });
                            AjusteInventario.irRestando(esto, totalentabla, unidades_producto, producto_id);


                        } else {

                            $("#guardar").prop('disabled', false);
                            //le quito el color rojo
                            AjusteInventario.tablalista.row('#lista_' + AjusteInventario.producto_seleccionadoid).nodes().to$().find('td')
                                .each(function () {

                                    $(this).css('color', '#797979  !important')

                                });
                        }

                    } else {

                        Utilities.alertModal('OJO ESTE PRODUCTO NO MANEJA CONTROL DE INVENTARIO', 'warning');
                    }

                });
            }
        }
    },
    irRestando: function (esto, totalentabla, unidades_producto, producto_id) {
        //aqui valido para que no deje escrita una cantidad mayor al stock en el input
        /* var talvalor = $(esto).val();
         while (talvalor > stockactual) {

         if (parseFloat(talvalor) > parseFloat(stockactual)) {
         $(esto).val(talvalor.substr(0, talvalor.length - 1));
         talvalor = talvalor.substr(0, talvalor.length - 1)
         } else {
         break;
         }
         }*/

        //"esto" es el input que estoy escribiendo,
        // totalentabla es el total de unidades minimas que hay hasta los momentos en todos los input
        //aqui valido para que no deje escrita una cantidad mayor al stock en el input
        var talvalor = "";
        while (totalentabla > AjusteInventario.total_minima) {
            talvalor = $(esto).val();
            if (parseFloat(totalentabla) > parseFloat(AjusteInventario.total_minima)) {
                $(esto).val(talvalor.substr(0, talvalor.length - 1));
                totalentabla = Traslado.totalMinimaEnTabla(unidades_producto, producto_id);

            } else {
                break;
            }
        }

        $("#guardar").prop('disabled', false);
        //le quito el color rojo
        AjusteInventario.tablalista.row('#lista_' + AjusteInventario.producto_seleccionadoid).nodes().to$().find('td')
            .each(function () {

                $(this).css('color', '#797979  !important')

            });

        $(esto).focus();
    },
    anularMovimiento: function (id_ajusteinventario){
        Utilities.showPreloader();
        AjusteInventario.info= Utilities.alertModal('<h4>Por favor espere</h4>', 'info',false);
        var ajaxguardar = AjusteInventarioService.anularMovim(id_ajusteinventario);
        ajaxguardar.success(function (data) {
            AjusteInventario.info.reset();
            Utilities.hiddePreloader();
            $("#btn_ajusteInventario_guardar").prop('disabled',false)
            if (data.error != undefined) {
                Utilities.alertModal(data.error, 'danger');
            } else {

                Utilities.alertModal('La operación se ha ralizado con éxito', 'success');
                var callback = AjusteInventarioService.index();
                callback.success(function (datacallback) {

                    AjusteInventario.cache.pagecontent.html(datacallback);
                });

            }
        });
        ajaxguardar.error(function () {
            Utilities.alertModal('Ha ocurrido un error al realizar la operación', 'danger');
            Utilities.hiddePreloader();
            AjusteInventario.info.reset();
        })

    }
}



