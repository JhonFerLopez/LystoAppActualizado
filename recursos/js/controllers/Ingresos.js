$.fn.scrollTo = function (speed) {
    if (typeof(speed) === 'undefined')
        speed = 1000;

    $('html, body').animate({
        scrollTop: parseInt($(this).offset().top)
    }, speed);
};
var Compra = {
    tablalistacompra: null,
    tablalistaproductos: new Array(), //arreglo que almacena la tabla de la busqueda de productos para agregar a la compra
    lst_producto: new Array(),
    lstProductosPrepack: new Array(), /*guardara los productos que componen el prepack */
    countproducto: 0,
    producto_seleccionadoid: "",
    producto_seleccionadocodigo: '',
    selected_codigo_interno_temp: '',
    costo_unitario: 0, /*costo de la caja en caliente, del producto en la compra*/
    costo_unitario_default: 0, /*costo de la caja que trae el producto, cada vez que se selecciona una fila de producto,
     se setea cuando se busca en buscarExistenciayPrecios*/
    obse_o_prepack_selec: "", /*almacena el codigo_interno del obsequio o prepack seleccionado*/
    monto_prepack: 0, /*almacena el costo del prepack para validar cando se descomponga*/
    restante_prepack: 0, /*almacena el retante del prepack para validar cando se descomponga*/
    productoAgDesde: "normal", //almacena si cuando se crea un producto nuevo desde la compra, se hace desde obsequio o prepack
    caja: 0,
    blister: 0,
    unidad: 0,
    contador_barra: 0,
    colores_credito: new Array(),
    colores_contado: new Array(),
    id_ingreso: '',
    ingresoCompleto: new Array(),
    tipo_calculo: '', /*tipo de calculo, si es matematico o financiero*/
    unidades_productos: new Array(),
    proveedor_nombre: '',
    proveedor_id: '',
    unidades: new Array(),
    condiciones_pago: new Array(),
    tipos_productos: new Array(),
    ubicaciones: new Array(),
    grupos: new Array(),
    detalle_especial: new Array(),
    pasotheadcoopidrogas: false,
    detalles: new Array(),
    tipo_carga: "MANUAL",
    select_bodega: "",
    arrayCantBodega: {}, //es el array temporal donde guardo las cantidades de las bodegas
    ingresoPendiente: '',
    gruposProducto: new Array(),
    id_detalle_ingreso: "",
    codigo_interno_temp: "", //es la mezcla de  codigo interno del producto, con un indice numerico ejemplo: 323232_0 o 323232_1
    //ya que pued ehaber el mismo producto dos veces en la compra, para el caso de coopidrogas

    init: function (ingreso_id, datos_ingreso, calculo_tipo, unidades_medida, condiciones_pago, tipos_de_productos,
                    ubicaciones_data, grupos_data, detalle_compra, detalleespecial, ingresoPendiente) {


        window.onbeforeunload = Compra.preguntarAntesDeSalir;

        this.tablalistacompra = null;
        this.tablalistaproductos = new Array()
        this.lst_producto = new Array();
        this.lstProductosPrepack = new Array();
        this.countproducto = 0;
        this.producto_seleccionadoid = "";
        this.producto_seleccionadocodigo = '';
        this.selected_codigo_interno_temp=''
        this.costo_unitario = 0;
        this.costo_unitario_default=0;
        this.obse_o_prepack_selec = "";
        this.monto_prepack = 0;
        this.restante_prepack = 0;
        this.productoAgDesde = "normal";
        this.caja = 0;
        this.blister = 0;
        this.unidad = 0;
        this.contador_barra = 0;
        this.colores_credito[0] = "#71da71";
        this.colores_credito[1] = "#49d049";
        this.colores_credito[2] = "#2fb62f";
        this.colores_contado[0] = "#9CF3E8";
        this.colores_contado[1] = "#98F0E5";
        this.colores_contado[2] = "#8EEADE";
        this.id_ingreso = ingreso_id;
        this.ingresoCompleto = datos_ingreso;
        this.tipo_calculo = calculo_tipo;
        this.unidades_productos = new Array();
        this.proveedor_nombre = $("#cboProveedor option:selected").text();
        this.proveedor_id = $("#cboProveedor").val();
        this.unidades = unidades_medida;
        this.condiciones_pago = condiciones_pago;
        this.tipos_productos = tipos_de_productos;
        this.ubicaciones = ubicaciones_data;
        this.grupos = grupos_data;
        this.detalle_especial = detalleespecial;
        this.pasotheadcoopidrogas = false;
        this.detalles = new Array();
        this.tipo_carga = "MANUAL";
        this.select_bodega = "";
        this.arrayCantBodega = {};
        this.ingresoPendiente = ingresoPendiente
        this.gruposProducto = new Array();
        this.id_detalle_ingreso='';
        this.codigo_interno_temp = "";

        this.limpiartotales();

        // Compra.declararThead();
        Compra.thead_otros();
        this.events();
        Compra.makeTableKey(false);
        if (this.id_ingreso == 'false') {

        } else {
            //aqui mando a quitar el boton de pendiente si la compra que stoy editando es COMPLETADO
            if (Compra.ingresoCompleto.ingreso_status == "COMPLETADO") {
                $("#divBtnCompraPendiente").remove();
            }
            //es edicion de compra
            Compra.prepararEditar(detalle_compra);
        }
        Utilities.setfocus(".inputsearchproduct:last-child");


    },
    makeTableKey: function (init) {

        Compra.tablalistacompra = $("#tabla_lista_productos").DataTable({
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

        Compra.keyFocusEvent();

        if (init) {
            Compra.tablalistacompra.cell(0, 0).focus();

        }
    },

    keyFocusEvent: function () {
        // Inline editing on tab focus

        Compra.tablalistacompra.off('key-focus');
        Compra.tablalistacompra.on('key-focus', function (e, datatable, cell) {

            if ($("#seleccionunidades").is(":visible")) {
                //si el modal se seleccion de productos esta visible, entonces no entro debajo, ya que cada vez que presiono
                //la flecha de arriba o abajo, tambien se mueve en la tabla de los productos lst_productos
            } else {

                var rowData = datatable.row(cell.index().row).data();
                var colData = cell.data();
                var objectCell = $($.parseHTML(colData));
                var elemento = $("#" + objectCell.attr('id'));


                if ($("#" + objectCell.attr('id')).length != 0) {
                    //esto es para poner el curso al final del input y no al principio
                    var strLength = $("#" + objectCell.attr('id')).val().length * 2;
                    $("#" + objectCell.attr('id')).focus();
                    $("#" + objectCell.attr('id')).select();//esto es para que seleccione todo el valor del campo de texto
                    // $("#" + objectCell.attr('id'))[0].setSelectionRange(strLength, strLength);para que lo pongoal final
                } else if (objectCell[0].lastChild != null) {

                    //aqui entra en el inputsearch para que le permita colocar el focus
                    $("#" + objectCell[0].lastChild.id).focus();
                    $("#" + objectCell[0].lastChild.id).select();
                }

                //esto lo hago para que cuandp pase el keyfocus sobre el td busque los datos del producto
                //sobre el cual se esta seleccionado
                var rowNode = datatable.row(cell.index().row).node();

                var id = $(rowNode).attr("data-producto_id");
                var codigo = $(rowNode).attr("data-producto_codigo_interno");
                var productonuevo = $(rowNode).attr('data-productonuevo');
                Compra.codigo_interno_temp = $(rowNode).attr("data-codigo_interno_temp");

                Compra.id_detalle_ingreso = $(rowNode).attr('data-id_detalle_ingreso');

                if (Compra.validar_mismo_trespecial() == true) {
                    $("#imagenultimocosto").html('')
                    Compra.buscarExistenciaProducto(id, codigo, productonuevo);
                    /*el metodo siguiente, hace el calculo para las flechas rojas, o verdes, del costo unitario*/
                    Compra.calcularflechas();
                }
            }

        });
    },

    teclado: function () {
        $('body').off('keydown');
        $('body').on('keydown', function (e) {
			if(e.keyCode == 34) {
				return false;
			}
            if (e.keyCode == 13) {

                if ($("#seleccionunidades").is(":visible")) {
                    e.preventDefault();

                    $("#confirmTipoProducto").modal('hide');

                    if (Compra.pasotheadcoopidrogas == true) {

                        //ingresa aqui cuando es obsequio
                        Compra.producto_seleccionado_normal();

                    } else {

                        Compra.agregarProducto();
                    }

                }

                //modal de selccionar el archivo .dat
                if ($("#modal_select_ruta").is(":visible")) {

                    Compra.procesar_archivo();
                }


            }

            //esto es para el modal de seleccion de coopidrogas
            //f1
            if (e.keyCode == 112) {

                if ($("#modal_select_ruta").is(":visible")) {
                    e.preventDefault();
                    //para la carga manual
                    Compra.ocultarRow_buscar_ruta();

                }

            }
            //F6  GUARDAR VENTA

            if (e.keyCode == 117) {
                if (Compra.validarguardar() != false) {
                    //actializo las fechas de vencimiento

                    $("#confirmarmodal").modal('show');
                }
            }
            //f2
            if (e.keyCode == 113) {

                if ($("#modal_select_ruta").is(":visible")) {
                    e.preventDefault();
                    //para la carga automatica
                    Compra.mostrarRow_buscar_ruta();

                }
            }

            if (e.keyCode == 27) {
                if ($("#seleccionunidades").is(":visible")) {
                    $("#seleccionunidades").modal('hide');
                    Utilities.setfocus(".inputsearchproduct:last-child");
                }
            }

            //FLECHA PARA ABAJO
            if (e.keyCode == 40) {
                if ($("#seleccionunidades").is(":visible")) {
                    $("#tablaproductos_filter input").blur();
                    if ($(".ui-selected").length != 0) {

                        var next = parseInt(Compra.tablalistaproductos.row('.ui-selected').index());
                        var len = parseInt(Compra.tablalistaproductos.page.info().end);
                        next = next + 1;
                        if (next == len) {
                            next = 0;
                        }
                        Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(" + next + ")"));
                        return 0;
                    }
                }
            }

            //FECLAHA PARA ARRIBA
            if (e.keyCode == 38) {

                if ($("#seleccionunidades").is(":visible")) {
                    $("#tablaproductos_filter input").blur();


                    var next = parseInt(Compra.tablalistaproductos.row('.ui-selected').index());
                    var len = parseInt(Compra.tablalistaproductos.page.info().end) - 1;
                    //console.log(next);
                    if (next == 0) {
                        next = len;
                    } else {
                        next = next - 1;
                    }

                    //console.log(next);
                    //console.log(len);
                    if ($(".ui-selected").length != 0) {

                        Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(" + next + ")"));

                        return 0;
                    }
                }
            }


            if (e.keyCode == 9) {

                if ($("#seleccionunidades").is(":visible")) {
                    e.stopPropagation();
                    e.preventDefault();

                    setTimeout(function () {
                        $("#agregarproducto").focus();
                    }, 500);
                    return false

                }
            }

        });

    },

    mostrarRow_buscar_ruta: function () {
        $("#row_buscar_ruta").css('display', 'block');
        $("#row_ruta_o_manual").css('display', 'none');
        $("#btn_procesar_archivo").css('display', 'block');
        $("#boton_carga_automatica").css('display', 'none');

    },
    ocultarRow_buscar_ruta: function () {

        $("#row_buscar_ruta").css('display', 'none');
        $("#row_ruta_o_manual").css('display', 'block');
        $("#modal_select_ruta").modal('hide');
        $("#btn_procesar_archivo").css('display', 'none');


    },
    events: function () {




		/*las dos siguientes setencias, es para que al cerrar dos modales, no se contraiga la pantalla*/
        $(document.body).on('hide.bs.modal,hidden.bs.modal', function () {
            $('body').css('padding-right', '0');

        });
        $(document.body).on('shown.bs.modal,show.bs.modal', function () {
            $('body').css('padding-right', '0');

        });
        /************/

        /*las 2 siguiente sentencias es para que cuando se despliegue el modal de seleccionar productos, pero cuando
         * antes de eso se le dice que va a ser un prepack, pueda desplegar el scroll lateral*/

        $("#seleccionunidades .modal-body").css('overflow-y', 'auto');
        $("#modal_prepacks .modal-body").css('overflow-y', 'auto');
        $("#modal_prepacks .modal-dialog").css('overflow-y', 'initial !important');
        $("#modal_prepacks .modal-body").css('max-height', 'calc(100vh - 190px)');


        setTimeout(function () {
            $(".cho").css('width', '100%');
            $(".chosen-container").css('width', '100%');
        }, 1);
        //esto es para que cuando la tabla que tiene la clase inner_table se mueva el scroll, tambien se mueva el scroll de la
        //tabla que tiene la clase head
        $(".inner_table").on('scroll', function (e) {
            //$('.head').scrollTop($(this).scrollTop());
            $('.head').scrollLeft($(this).scrollLeft());
        });

        $(".closeseleccionunidades").on('click', function () {
            $("#seleccionunidades").modal('hide');
            Utilities.setfocus(".inputsearchproduct:last-child");
        });


        var select = $('#selectbodegas');
        select
            .data("lastIndex", select[0].selectedIndex)
            .on('change', function (event) {
                var idx = this.selectedIndex; //el index del option actual, ya despues del change
                var prevIdx = $(this).data("lastIndex"); //el index del option anterior al change
                var localactual = $(this).find("option").eq(idx); //busco el value
                var ultimovalor = $(this).find("option").eq(prevIdx); //busco el value
                $(this).data("lastIndex", idx);//actualizo el valor lastIndex al valor actual
                var posicion = Compra.existe_producto();
                if (Compra.validarBodegacantidad(posicion, localactual.val()) == false) { /*verifico si existe el valor que tengo actualmente*/
                    //si pasa aqui es poque no tiene declarado el arrelo canBodegas o porque no tiene las unidades
                    //le paso el ultimo valor que tenia para que lo guarde sobre la bodega anterior
                    if (ultimovalor.val() != "") {
                        Compra.guardarCantidadBodega(posicion, ultimovalor.val());
                        $("#tbody_table_bodegas input[name*=cantidad_bodegas_]").val('')
                    }
                }

            });


        jQuery('#productomodal').on('hidden.bs.modal', function (e) {

            //si se agrego el producto desde prepack se vuelve a recargar el modal donde se descompone el prepack
            if (Compra.productoAgDesde == "prepack") {
                $("#modal_prepacks").modal('show');
            }

            Compra.teclado();
        });


        //esta funcion hace que cuando se presione sobre unatecla sobre un input que tenga la clase inputsearch
        //se busque los productos con las condiciones
        Compra.inputsearch();

        $('#seleccionunidades').on('shown.bs.modal', function (e) {
            $("#preciostbody").selectable({
                stop: function () {
                    var id_producto = $("#preciostbody tr.ui-selected").attr('id');
                    // var id_producto = $('#selectproductos').val();
                    //getUnidadPrecio(id_unidad, id_producto);

                    var id = $("#preciostbody tr.ui-selected").attr('id');

                    $("#agregarproducto").blur();
                }
            });

            $("#tablaproductos_filter input").on("click", function () {
                // console.log('quito');
                $(".ui-selected").removeClass("ui-selected");
            });

        });

        $('#seleccionunidades').on('hidden.bs.modal', function (e) {
            $(".inputsearchproduct").focus();
            $(".inputsearchproduct").select();

        });

        //a abrir el modal de selecionar la ruta o presionar f1 o f2
        $('#modal_select_ruta').on('shown.bs.modal', function (e) {

            $("#btn_procesar_archivo").css('display', 'none');
            $("#boton_carga_automatica").css('display', 'block');
            $("#row_buscar_ruta").css('display', 'none');
            $("#boton_carga_automatica").after('<div class="clearfix"></div>');


        });


        $("#btnGuardarCompra").on('click', function () {

            //hago las validaciones antes de guardar
            if (Compra.validarguardar() != false) {
                //actializo las fechas de vencimiento

                $("#confirmarmodal").modal('show');
            }

        });

        Compra.teclado();

        $("#modal_codigo_barra").on('hidden.bs.modal', function (e) {
            Compra.cerrarmodalCodigoBarra();
        })

        /*esto es para buscar en la tabla cliente tipo campo, por padre solo persona natural */
        $("#agregar_barraCompra").on('click', function (e) {
            e.preventDefault();
            Compra.agregar_barras();
        });
        $("#fecha_ingreso").datepicker({format: 'dd-mm-yyyy'});

        $("select").chosen({width: '100%', search_contains: true});
        $("#fecEmision").datepicker({format: 'dd-mm-yyyy'});


        $("#modal_select_ruta").on('hidden.bs.modal', function (e) {
            $("#row_ruta_o_manual").css('display', 'block');
            $("#row_buscar_ruta").css('display', 'none');
            $("#btn_procesar_archivo").css('display', 'none');
        });


    },
    parseDate: function (input) {
        var parts = input.match(/(\d+)/g);
        // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
        return new Date(parts[0], parts[1] - 1, parts[2]); // months are 0-based
    },
    formatFecha: function (date) {
        var nueva_fecha = Compra.parseDate(date);
        date = new Date(nueva_fecha);
        var year = date.getFullYear();
        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;
        var day = date.getDate().toString();

        day = day.length > 1 ? day : '0' + day;
        return day + '-' + month + '-' + year;

    },

    actDatosTabla: function (producto_id, codigo_interno, posicion) {
        //actualiza los datos de un producto en la tabla, se usa en prepack, si el prepack esta en la tabla

        //Compra.lst_producto[posicion]['codigo_interno_temp'] es la identificacion de todo

        //busco los precios de este producto
        var llamada_precios = UnidadesService.getSoloPreciosByProd(producto_id);
        var precios = new Array();
        llamada_precios.success(function (data) {
            precios = data;
        });

        var tipo_producto = $("#producto_tipo").val();
        var producto_ubicacion_fisica = $("#producto_ubicacion_fisica").val();
        var producto_grupo = $("#produto_grupo").val();

        $("#tipoprod_" + Compra.lst_producto[posicion]['codigo_interno_temp']).val(tipo_producto);
        $("#tipoprod_" + Compra.lst_producto[posicion]['codigo_interno_temp']).trigger("chosen:updated");

        $("#ubicacion_" + Compra.lst_producto[posicion]['codigo_interno_temp']).val(producto_ubicacion_fisica);
        $("#ubicacion_" + Compra.lst_producto[posicion]['codigo_interno_temp']).trigger("chosen:updated");

        $("#grupo_" + Compra.lst_producto[posicion]['codigo_interno_temp']).val(producto_grupo);
        $("#grupo_" + Compra.lst_producto[posicion]['codigo_interno_temp']).trigger("chosen:updated");


        if (Compra.unidades_productos[Compra.datovalido('', codigo_interno)] != undefined &&
            Compra.unidades_productos[Compra.datovalido('', codigo_interno)].length > 0) {
            var contInterno = Compra.unidades_productos[Compra.datovalido('', codigo_interno)];


            var encontro = false;
            var input = "";
            var cuantasunidades = "";
            var habilitarblister = false;
            jQuery.each(Compra.unidades, function (j, value) {

                encontro = false;
                cuantasunidades = "";
                for (var i = 0; i < contInterno.length; i++) {

                    if (value.id_unidad == contInterno[i].id_unidad) {

                        if (contInterno.length > 1) {
                            habilitarblister = true;
                        }

                        encontro = true;
                        cuantasunidades = contInterno[i].unidades;
                    }

                }
                input = $("#contenido_in" + Compra.lst_producto[posicion]['codigo_interno_temp'] + "_" + j);

                if (encontro == true) {
                    if (value.orden < 3) {
                        input.attr("readonly", false);

                    }

                    if (value.orden == "3") {

                        input.attr("readonly", true);

                    }
                    input.val(cuantasunidades);
                }

                if (j == 1 && habilitarblister == true) {

                    input.attr("readonly", false);
                }
            });
        }

        if (precios.length > 0) {
            for (var i = 0; i < precios.length; i++) {
                var input = $("#utilidad_" + Compra.lst_producto[posicion]['codigo_interno_temp'] + "_" + precios[i].id_unidad + "_" + precios[i].id_condiciones_pago);
                input.val(precios[i].utilidad)

                var input = $("#precio_valor_" + Compra.lst_producto[posicion]['codigo_interno_temp'] + "_" + precios[i].id_unidad + "_" + precios[i].id_condiciones_pago);
                input.val(precios[i].precio)
            }
        }

    },

    //guardo en el arreglo unidades_productos los datos del contenido interno del producto que le paso
    addUnidadesProductos: function (producto_id, codigo_interno) {
        Compra.unidades_productos[Compra.datovalido(producto_id, codigo_interno)] = new Array();

        var consulta_unidades = UnidadesService.getSoloUnidadesByProd(producto_id);
        consulta_unidades.success(function (data) {

            Compra.unidades_productos[Compra.datovalido(producto_id, codigo_interno)] = data;
        });

    },

    agregarNuevoProducto: function (producto_id) {
        /*se ejecuta cuando se crea un nuevo producto desde la compra*/

        Compra.teclado();


        var row = {};
        row.producto_id = producto_id;
        row.producto_codigo_interno = $("#producto_codigo_interno").val();
        row.producto_nombre = $("#producto_nombre").val();

        //si el producto fue agregado desde prepack
        if (Compra.productoAgDesde == "prepack") {

            Compra.arrProductosPrepack(producto_id, row.producto_nombre, row.producto_codigo_interno, '', '');

        } else if (Compra.productoAgDesde == "obsequio") {

            //si el producto fue agregado desde obsequio
            Compra.asociar_obsequio(producto_id, row.producto_codigo_interno, row.producto_nombre, '');

            /******guardo en el arreglo unidades_productos los datos del contenido interno*/
            Compra.unidades_productos[Compra.datovalido('', row.producto_codigo_interno)] = new Array();

            var consulta_unidades = UnidadesService.getSoloUnidadesByProd(row.producto_id);
            consulta_unidades.success(function (data) {

                Compra.unidades_productos[Compra.datovalido('', row.producto_codigo_interno)] = data;
            });
            /****************************************************************/

            //busco la posicion del producto dentro de lst_producto si existe
            var existe = Compra.existe_producto_o_codigo(producto_id, Compra.reemplazarcomillas(row.producto_codigo_interno));

            if (existe != 'false') {
                Compra.actDatosTabla(producto_id, row.producto_codigo_interno, existe);
            }

        } else {

            //cuando creo el producto desde la compra pero presionando el boton normal de crear producto
            //aqui solo entra cuando la compra no es de coopidrogas

            row.impuesto_porcentaje = $("#producto_impuesto_costos").val();
            row.producto_ubicacion_fisica = $("#producto_ubicacion_fisica").val();
            row.producto_tipo = $("#producto_tipo").val();

            row.costo_unitario = $("#costo_unitario").val();
            row.produto_grupo = $("#produto_grupo").val();

            row.impuesto = $("#producto_impuesto_costos option:selected").attr('data-porcentaje');
            row.producto_impuesto = $("#producto_impuesto_costos").val();
            row.descuento = "";

            /******guardo en el arreglo unidades_productos los datos del contenido interno*/
            Compra.unidades_productos[Compra.datovalido(row.producto_id, row.producto_codigo_interno)] = new Array();

            var consulta_unidades = UnidadesService.getSoloUnidadesByProd(row.producto_id);
            consulta_unidades.success(function (data) {

                Compra.unidades_productos[Compra.datovalido(row.producto_id, row.producto_codigo_interno)] = data;
            });
            /****************************************************************/

            var nuevo = true;
            row = Compra.armarArregloNormal(row.producto_id, row.producto_codigo_interno, row, nuevo);

            //genero el codigo temporal
            row.codigo_interno_temp = Compra.crearcodigoTemp(row.producto_id,
                Compra.reemplazarcomillas(row.producto_codigo_interno));

            Compra.agregar(row);
            var posicion = Compra.existe_producto(row.producto_id, row.producto_codigo_interno);
            var buscarBarras = ProductoService.getCodigosBarra(row.producto_id);

            buscarBarras.success(function (data) {


                var barras = data.barras;

                if (barras.length > 0) {
                    Compra.crearArregloBarra(posicion);
                }
                var desde = 0;

                var cont = 0;
                for (var i = desde; i < parseInt(barras.length) + parseInt(desde); i++) {

                    Compra.lst_producto[posicion]['codigosBarra'][i] = barras[cont]['codigo_barra'];
                    cont++;
                }

                Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] = true;

            }).error(function () {
                Utilities.alertModal('<h4>Ha ocurrido un error al buscar los c&oacute;digos de barras</h4>', 'warning', true);

            })
            setTimeout(function () {

                $("#ultimo_costo_compra").val($("#costo_unitario").val());
            }, 500)

        }

    },
    crearcodigoTemp: function (producto_id, codigo_interno) {
        //crea el codigo temporale para el producto que llega (codigo_interno)
        var encontro = 0;
        var codigo_interno_temp = codigo_interno;
        for (var i = 0; i < Compra.lst_producto.length; i++) {
            if (Compra.lst_producto[i]['producto_codigo_interno'] == codigo_interno) {
                encontro++;
            }
        }

        return codigo_interno_temp + "_" + encontro;
    },
    armarDetalle: function (fila) {
        /*arma el arreglo del detalle cuando sea un editar compra*/

        var row = {};
        row.producto_id = fila.producto_id;
        row.producto_codigo_interno = fila.producto_codigo_interno;
        Compra.unidades_productos[Compra.datovalido(row.producto_id, row.producto_codigo_interno)] = new Array();
        Compra.unidades_productos[Compra.datovalido(row.producto_id, row.producto_codigo_interno)] = fila.consulta_unidades;

        //el detalle del ingreso, por unidades
        row.detalle_unidad = fila.detalle_unidad;
        row.editar = true;
        var nombre = fila.producto_nombre;
        row.producto_nombre = nombre;
        row.total_impuesto = fila.total_impuesto;
        row.impuesto = fila.impuesto_porcentaje;

        row.producto_ubicacion_fisica = fila.producto_ubicacion_fisica;
        row.producto_tipo = fila.producto_tipo;
        row.costo_unitario = fila.costo_unitario;
        row.produto_grupo = fila.produto_grupo;
        row.producto_impuesto = fila.producto_impuesto;
        row.total_detalle = fila.total_detalle;
        row.id_detalle_ingreso = fila.id_detalle_ingreso;
        row.descuento = fila.porcentaje_descuento;
        row.bonificacion = fila.porcentaje_bonificacion;
        row.precios = fila.precios;
        row.stock = fila.stock;
        Compra.id_detalle_ingreso = row.id_detalle_ingreso;

        return row;

    },
    prepararEditar: function (detalle_compra) {
        /*prepara el detalle cuando se esta editando la compra*/

        Compra.load_editar(detalle_compra);

    },
    verificarProveedor: function () {
        this.proveedor_id = $("#cboProveedor").val();
        this.proveedor_nombre = $("#cboProveedor option:selected").text();

        if ((this.proveedor_nombre == "COOPIDROGAS" || this.proveedor_nombre == "Coopidrogas") && this.pasotheadcoopidrogas == false) {

            $("#modal_select_ruta").modal({show: true});

            if ($("#div_total_bonificado").length == 0) {
                $("#div_total_facturado").after('  <div class="col-md-2" id="div_total_bonificado"> <input type="text"' +
                    ' class="input-square input-small form-control" name="total_bonificado" id="total_bonificado" ' +
                    'readonly value="0.00">');

                $("#th_total_facturado").after('<div class="col-md-2 bold" id="th_total_bonificado">Total Bonificado </div>');
            }

        } else {

            //descuentos es solo para coopidrogas
            $("#th_total_bonificado").remove();
            $("#div_total_bonificado").remove();

        }
    },
    cerrar_modal_catalogo: function () {

        if ($("#modal_prepacks").is(":visible")) {

            $("#modal_prepacks").modal('show');

        } else if ($("#productomodal").is(":visible")) {
            //si esta la ventana de crear producto en la compra

        } else {
            $("#seleccionunidades").modal('show');
        }

    },
    cerrar_modalruta: function () {

        if (this.proveedor_nombre != "COOPIDROGAS" && this.proveedor_nombre != "Coopidrogas") {

            $("#cboProveedor").val($("#cboProveedor option:first").val());
            $("#cboProveedor").trigger("chosen:updated");
        }

        $("#modal_select_ruta").modal('hide');
    },
    cerrarmodalConfirmarCatalogo: function () {

        $("#confirmarSaveCatalogo").prop('disabled', false);
        $("#confirmar_catalogo").modal('hide');
        $("#catalogoIngreso").modal({show: true, keyboard: false, backdrop: 'static'})
    },
    cerrarmodalCodigoBarra: function () {

        $("#codigo_barra_originalCompra").val('');
        $("#abrir_codigos_barra").html('');
        $("#modal_codigo_barra").modal('hide');
    },
    inputsearch: function () {
        //declara que al epresionar enter sobre la clase inputsearch busca los resultados

        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                Compra.modalproductos($(this).val());
            }
        });
    },

    validarguardar: function () {   //hago las validaciones antes de guardar

        var retorno = true;

        if (this.proveedor_id == "" || this.proveedor_id == null) {
            Utilities.alertModal('<h4>Debe seleccionar un proveedor!</h4>', 'warning', true);

            retorno = false;
        }

        if (this.pasotheadcoopidrogas == true && (this.proveedor_nombre != "COOPIDROGAS" && this.proveedor_nombre != "Coopidrogas")) {
            Utilities.alertModal('<h4>Debe seleccionar otro proveedor!</h4>', 'warning', true);
            $("#cboProveedor").trigger("chosen:open");
            $("#cboProveedor").trigger("chosen:updated");
            retorno = false;
        }

        if ($("#doc_numero").val() == "" || $("#doc_numero").val() == false) {
            $("#doc_numero").focus();
            Utilities.alertModal('<h4>Debe ingresar un n&uacute;mero de documento!</h4>', 'warning', true);
            retorno = false;
        }

        if ($("#documento").val() == "" || $("#documento").val() == false) {

            Utilities.alertModal('<h4>Debe ingresar un tipo de Compra!</h4>', 'warning', true);
            retorno = false;
        }

        if ($("#fecha_ingreso").val() == "" || $("#fecha_ingreso").val() == false) {

            Utilities.alertModal('<h4>Debe ingresar una fecha!</h4>', 'warning', true);
            retorno = false;
        }

        if (this.lst_producto.length < 1) {

            Utilities.alertModal('<h4>Debe seleccionar al menos un producto!</h4>', 'warning', true);
            retorno = false;
        }


        //aqui valido que exita al menos un contenido interno y que haya ingresado al menos una cantidad valida
        if (retorno == true) {
            retorno = this.validaContenidoInterno();
        }

        return retorno;

    },
    validaContenidoInterno: function () {
        var retorno = true;
        var unid = new Array();
        var encontrocantidad = false;
        console.log('validaContenidoInterno->Compra.lst_producto',Compra.lst_producto)
        for (var i = 0; i < Compra.lst_producto.length; i++) {

            encontrocantidad = false;
            unid = Compra.lst_producto[i]['unidades'];
            for (var j = 0; j < unid.length; j++) {


                if (unid[j].cantidad > 0) {
                    encontrocantidad = true;

                }
            }


            if (encontrocantidad == false) {
                Utilities.alertModal('<h4>'+decodeURIComponent(Compra.lst_producto[i].producto_nombre)+': Debe ingresar al menos una cantidad v&aacute;lida del producto ' + Compra.lst_producto[i].producto_o_codigo
                    + '!</h4>', 'warning',10000);
                retorno = false;
                return false;
            }

            if ($("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_0").val() == "" ||
                $("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_0").val() == 0) {


                $("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_0").focus();
                Utilities.alertModal('<h4>'+decodeURIComponent(Compra.lst_producto[i].producto_nombre)+': Debe ingresar al menos un Contenido interno!</h4>', 'warning', 10000);
                retorno = false;
                return false;

            }

            if ($("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_0").val() ==
                $("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_1").val()) {


                Utilities.alertModal('<h4>'+decodeURIComponent(Compra.lst_producto[i].producto_nombre)+': El valor en blister no debe ser igual al valor en Caja</h4>', 'warning',10000);
                $("#contenido_in" + Compra.lst_producto[i].codigo_interno_temp + "_1").focus();
                retorno = false;
                return false;

            }
        }
        return retorno;

    },
    //esta funcion pregunta si el codigo interno es 0, en ese caso retorna el id del producto sino, pues muestra el codigo interno
    oproducto_o_codigo: function (producto_id, codigointerno) {

        if (codigointerno == 0) {

            return producto_id;
        }

        return codigointerno;
    },

    //esta funcion pregunta si el codigo interno es 0, en ese caso retorna el id del producto sino, pues muestra el codigo interno
    datovalido: function (producto_id, codigointerno) {
        if (codigointerno == 0) {

            return producto_id;
        }

        return codigointerno;
    },
    reemplazarcomillas: function (variable) {
        //esta funcion quita als comillas que tiene la variable contenido interno, ya que puede ser un string o puede ser int
        //y al momento de pasarlo en una funcion debe ir siempre como string
        // la g indica de forma global
        var regex = new RegExp("\"", "g");

        if (variable != undefined) {
            return variable.replace(regex, "");
        }
    },

    ivaConComilla: function (iva) {
        var ivanuevo = '"' + iva + '"';
        return ivanuevo;
    },

    agregarProducto: function () {
        var producto_id = $("#preciostbody tr.ui-selected").attr('id');
        if (producto_id != undefined) {
            //cuando se presiona el boton agregarproducto para agregar uno nuevo a la compra

            var row = new Array();
            row['producto_id'] = $("#preciostbody tr.ui-selected").attr('id');
            row['producto_codigo_interno'] = $("#preciostbody tr.ui-selected").attr('data-codigo_interno');

            //valido si ya agregue el producto
            var existe = Compra.existe_producto_o_codigo(row['producto_id'], Compra.reemplazarcomillas(row['producto_codigo_interno']));

            if (existe != 'false') {
                Utilities.alertModal('<h4>Ya existe un registro para este producto!</h4>', 'warning', true);
                return false;
            } else {

                var nuevo = false;
                Compra.addUnidadesProductos(row['producto_id'], row['producto_codigo_interno']);
                row = Compra.armarArregloNormal(row['producto_id'], row['producto_codigo_interno'], false, nuevo);
//genero el codigo temporal
                row.codigo_interno_temp = Compra.crearcodigoTemp(row['producto_id'],
                    Compra.reemplazarcomillas(row['producto_codigo_interno']));
                Compra.agregar(row);
            }
        }

    },
    existe_producto: function () {
        //valida si ya esta agregado el producto a la compra con el codigo_interno_temp
        var existe = 'false';
        jQuery.each(Compra.lst_producto, function (i, value) {
            if (value["codigo_interno_temp"] === Compra.codigo_interno_temp) {
                existe = i;
            }
        });

        return existe;
    },

    existe_producto_o_codigo: function (producto_id, codigointerno) {
        //valida si ya esta agregado el producto a la compra con el producto_o_codigo
        //esto se usa cuando en obsequio se crea un producto del catalogo, y vengo a validar si ya el producto existe en a tabla principal
        //si esta el producto dos veces, retornara el ultimo
        var existe = 'false';
        jQuery.each(Compra.lst_producto, function (i, value) {
            if (value["producto_o_codigo"] === Compra.oproducto_o_codigo(producto_id,
                Compra.reemplazarcomillas(codigointerno))) {
                existe = i;
            }
        });

        return existe;
    },

    armarArregloNormal: function (producto_id, codigo_interno, modificando, nuevo) {
        //arma el arreglo a enviar con los datos del producto a la tabla
        var iva = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-producto_impuesto') : modificando.impuesto;
        var iva_id = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-producto_impuesto_id') : modificando.producto_impuesto;
        var producto_nombre = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-name') : modificando.producto_nombre;
        var costo_unitario = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-costo_unitario') : modificando.costo_unitario;
        var produto_grupo = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-produto_grupo') : modificando.produto_grupo;
        var producto_ubicacion_fisica = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-producto_ubicacion_fisica') : modificando.producto_ubicacion_fisica;
        var producto_tipo = modificando == false ? $("#preciostbody tr.ui-selected").attr('data-producto_tipo') : modificando.producto_tipo;
        var descuento = modificando == false ? "" : modificando.descuento;
        /*solo cuando es modificacion, se obtiene el descuento*/


        var row = {};

        row.productonuevo = nuevo;
        row.producto_id = producto_id;
        row.producto_codigo_interno = '"' + codigo_interno + '"';
        row.deshabilitar = "";

        row.producto_nombre = producto_nombre;
        row.costo_unitario = '"' + costo_unitario + '"';
        row.produto_grupo = produto_grupo;
        row.producto_ubicacion_fisica = producto_ubicacion_fisica;
        row.producto_tipo = producto_tipo;
        row.precios = new Array();
        row.contenido_interno = new Array();
        row.descuento = descuento != undefined ? descuento : "";

        //las unidades del producto
        row.contenido_interno = Compra.unidades_productos[Compra.datovalido(producto_id, codigo_interno)];

        if (modificando.precios == undefined) {
            //busco los precios de este producto
            var llamada_precios = UnidadesService.getSoloPreciosByProd(producto_id);
            llamada_precios.success(function (data) {
                row.precios = data;
            });
        } else {
            row.precios = modificando.precios;
        }
        if (iva == "null") {
            iva = "";
            iva_id = "";
        }
        row.iva = '"' + iva + '"';
        row.iva_id = '"' + iva_id + '"';

        return row;

    },
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


    },

    modalproductos: function (valor) {
        //muestra el modal donde listo los productos
        //valor: valor a buscar

        Utilities.showPreloader();
        var table = $('#tablaproductos').DataTable();
        table.destroy();

        $("#productostbody").html('');
        Utilities.hiddePreloader();

        Compra.tablalistaproductos = TablesDatatablesLazzy.init(baseurl + 'api/productos/specialSearchLazzy', 0, 'tablaproductos',
            {constock: false, operacion: 'COMPRA', 'search': valor},
            '',
            Venta.setFocusOnfirstrow,
            false,
            false,
            [],
            false,
            true,
            '50vh');

        $("#seleccionunidades").modal({
            show:true,
            backdrop: 'static',
            keyboard: false
        });

    },

    colocardatepicker: function () {

        $(".fecha").datepicker();
    },


    ver_catalogo: function () {

        $("#seleccionunidades").modal('hide');

        Utilities.showPreloader();
        ///se pregunta asi, porque si no consigue ningun resultado, muestra un tr con un td, por lo tanto
        //asi traiga un solo resultado, que haga la solicitd


        $('#catalogoIngreso').html('');
        var vista = "ingreso";
        var catalogo = ProductoService.catalogoCoopidrogas(vista);

        catalogo.success(function (data) {

            setTimeout(function () {

                $("#confirmarSaveCatalogo").prop('disabled', false);

                $('#catalogoIngreso').html(data);
                $('#catalogoIngreso').modal({show: true, keyboard: false, backdrop: 'static'});
                $("#xcerrarmodalcatalogo").attr('onclick', 'Compra.cerrar_modal_catalogo();')

            }, 10);

        }).error(function () {
            Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
            $("#seleccionunidades").modal('show');

        });


        $("#table_catalogo input[type='radio']").attr('onclick', 'Compra.procesar_catalogo($(this).attr("data-codigo"))')
        Utilities.hiddePreloader();
    },


    validar_check_catalogo: function (check) {
        //el modal que confirma al seleccionar el producto de la lista catalogo, para asociarlo a un obsequio
        $("#confirmarSaveCatalogo").prop('disabled', false);
        $("#confirmar_catalogo").modal({show: true, keyboard: false, backdrop: 'static'});
        $("#textoConfirmarCatalogo").html('');
        $("#textoConfirmarCatalogo").html('Est&aacute; seguro que desea asociar el producto ' + check + ' ?');
        $("#confirmarSaveCatalogo").attr('onclick', 'Compra.guardarCheckCatalogo(' + check + ')');
    },

    guardarCheckCatalogo: function (check) {
        //guardo la confirmacion
        $("#confirmarSaveCatalogo").prop('disabled', true);

        $.ajax({
            url: baseurl + 'producto/producto_catalogo/',
            type: 'post',
            dataType: 'json',
            data: {'producto': check},
            async: false,
            success: function (data) {
                $("#confirmar_catalogo").modal('hide');
                $("#catalogoIngreso").modal('hide');
                var respuesta = data.res[0];
                Compra.asociar_obsequio("", respuesta.producto_codigo_interno, '', respuesta);

            },
            error: function (error) {
                Utilities.hiddePreloader();
                Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
            }

        })
    },


    procesar_catalogo: function (check) {
        //esta fncion es llamada desde ingresos.php

        $("#seleccionunidades").modal('hide');

        Utilities.showPreloader();
        var codigo = ProductoService.validar_codigo_interno(check);
        var existe = false;
        codigo.success(function (data) {
            existe = data.encontro;
            if (existe == true) {
                var datos = data.datos;
                Utilities.alertModal('<h4>El producto ya existe, con el nombre ' + datos.producto_nombre + '</h4>', 'warning', true);
            } else {

                //pregunto si esta visible el modal de prepack para hacer lo que conscierne a prepack
                //sino llamo a la funcion de obsequios
                if ($("#modal_prepacks").is(":visible")) {


                    $("#catalogoIngreso").modal('hide');
                    $("#modal_prepacks").modal('hide');
                    //hago lo que voy a hacer con el producto seleccionado para asociarlo al prepack

                    Producto.agregar(Compra.addCompraEnProducto);

                    setTimeout(function () {
                        Producto.mostrar_datos_catalogo(check);
                        Producto.agregar_barras();
                    }, 4000);


                } else {
                    $("#confirmTipoProducto").modal('hide');
                    $("#catalogoIngreso").modal('hide');
                    //hago lo que voy a hacer con el producto seleccionado para asociarlo al prepack

                    Producto.agregar(Compra.addCompraEnProducto);

                    setTimeout(function () {
                        Producto.mostrar_datos_catalogo(check);
                        Producto.agregar_barras();
                    }, 4000);

                    //Compra.validar_check_catalogo(check);

                }


                Utilities.hiddePreloader();
            }

        }).error(function () {
            Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
        });
        Utilities.hiddePreloader();
        //hago una confirmacion

    },

    producto_seleccionado_normal: function () {

        var producto_id = $("#preciostbody tr.ui-selected").attr('id');
        if (producto_id != undefined) {
            var codigo_interno = $("#preciostbody tr.ui-selected").attr('data-codigo_interno');
            var nombre = $("#preciostbody tr.ui-selected").attr('data-name');
            Compra.asociar_obsequio(producto_id, codigo_interno, nombre, '');
        }
    },

    obsequios: function () {
        //cuando se confirma que es un obsequio el producto seleccionados
//modal-open class is added on body so it has to be removed
        Utilities.showPreloader();
        Compra.modalproductos();
        Compra.productoAgDesde = "obsequio";
        $("#divBotonCatalogoCoopidrogas").remove();
        $("#footer_seleccionunidades").append('<div class="text-left" id="divBotonCatalogoCoopidrogas"><a  href="#" class="btn btn-primary"' +
            ' style="text-align: left" onclick="Compra.ver_catalogo()" >Cat&aacute;logo Principal</a></div>');

        $("#texto_modal_prod").html('');
        $("#texto_modal_prod").html('Seleccione el producto al cual asociar');
    },

    //cuando presiono enter sobre un producto seleccionado, para asociarlo a un obsequio
    asociar_obsequio: function (producto_id, codigo_interno, nombre, catalogo) {
        //esto se ejecuta cuando se presiona "enter" sobre un producto cuando se esta seleccionando un obsequio,
        //para poder asociar un producto obsequio a uno que existe o no

        if (Compra.reemplazarcomillas(Compra.obse_o_prepack_selec) != Compra.reemplazarcomillas(codigo_interno)) {

            $("#texto_modal_prod").html('');
            $("#texto_modal_prod").html('Productos');

            var cantidad_obsequio =
                $("#cantidad_" + Compra.obse_o_prepack_selec + "_" + Compra.unidades[0].id_unidad).val();


            //le actualizo la cantidad a 0
            setTimeout(function () {
                $("#cantidad_" + Compra.obse_o_prepack_selec + "_" + Compra.unidades[0].id_unidad).val('0');

            }, 50)


            var costo =
                $("#total_" + Compra.obse_o_prepack_selec).val();

            //valido si el producto al que voy a asociar, ya esta en el archivo plano o en LST_PRODUCTO
            Compra.validarObsequioAsociado(producto_id, codigo_interno, cantidad_obsequio)

            //coloreo al obsequio para indicarle que ya fue asociado
            $("#producto_" + Compra.codigo_interno_temp)
                .css("color", "#FFA07A  !important");

            //busco la posicion del producto obsequio dentro de lst_producto
            var existe = Compra.existe_producto('', Compra.reemplazarcomillas(Compra.obse_o_prepack_selec), Compra.id_detalle_ingreso);

            //si nombre es vacio es porque el producto esta siendo seleccionado desde catalogo de coopidrogas
            if (nombre == '') {
                nombre = catalogo.producto_nombre;
            }

            //creo el arreglo is_obsequio
            Compra.lst_producto[existe]['is_obsequio'] = new Array();

            //guardo el obsequio
            Compra.guardarObsequio(existe, producto_id, codigo_interno, cantidad_obsequio,
                nombre, costo);
            Utilities.alertModal('<h4>Asociado con &eacute;xito!</h4>', 'success', true);

            $("#seleccionunidades").modal('hide');

        } else {
            Utilities.alertModal('<h4>Seleccione otro producto, el que ha seleccionado es el mismo!</h4>', 'warning', true);
        }
    },

    //guarda en lst_producto la asociacion al producto obsequio
    guardarObsequio: function (existe, producto_id, codigo_interno, cantidad_obsequio,
                               nombre, costo) {
        var arrPrepack = [];
        var datosComponen = {};

        datosComponen.nombre = nombre;
        datosComponen.producto_id = producto_id;
        datosComponen.codigo_interno = Compra.reemplazarcomillas(codigo_interno);
        datosComponen.cantidad_obsequio = cantidad_obsequio;
        datosComponen.unidad = Compra.unidades[0].id_unidad;
        datosComponen.unidad_nombre = Compra.unidades[0].nombre_unidad;
        datosComponen.costo = costo;
        datosComponen.productonuevo = false;

        datosComponen.is_obsequio = true;

        if (producto_id == "") {
            datosComponen.productonuevo = true;
        }

        arrPrepack.push(datosComponen);

        Compra.lst_producto[existe]['is_obsequio'] = arrPrepack;

    },
    //elimina el produto al cual fue asociado un obsequio
    eliminar_obsequio: function (posicion) {

        //coloreo al obsequio a gris para indicarle que le quite la asociacion
        $("#producto_" + Compra.codigo_interno_temp)
            .css("color", "#797979  !important");

        Compra.devolverObsequio(posicion);
        Compra.lst_producto[posicion]['is_obsequio'] = new Array();

        Utilities.alertModal('<h4>Se han guardado los cambios con &eacute;xito!</h4>', 'success', true);
        $("#modal_obsequios").modal('hide');

        $("#producto_" + Compra.lst_producto[posicion]['codigo_interno_temp']).css('background-color', '#eaedf1');

    },

    devolverObsequio: function (posicion) {

        //le actualizo nuevamente la cantidad original al obsequio
        setTimeout(function () {
            $("#cantidad_" + Compra.lst_producto[posicion]['codigo_interno_temp'] + "_" + Compra.unidades[0].id_unidad)
                .val(Compra.lst_producto[posicion]['unidades'][0]['cantidad']);

        }, 0)

        //esta es la cantidad original del obsequio que vino en el archivo, la misma que acabo de acrualizar arriba
        var cantidad_obsequio = Compra.lst_producto[posicion]['unidades'][0]['cantidad']

        //busco la posicion del producto dentro al cual asocie el obsequio, es decir, no al obsequio
        var existe = Compra.existe_producto_o_codigo(Compra.lst_producto[posicion]['is_obsequio'][0]['producto_id'],
            Compra.reemplazarcomillas(Compra.lst_producto[posicion]['is_obsequio'][0]['codigo_interno']));


        if (existe != 'false') {  //quiere decir que si existe en la tabla

            //estos dos, es el producto original al cual asocie el obsequio
            var producto_id = Compra.lst_producto[posicion]['is_obsequio'][0]['producto_id'];
            var codigo_interno = Compra.reemplazarcomillas(Compra.lst_producto[posicion]['is_obsequio'][0]['codigo_interno']);

            //aqui valido, si el producto al cual asocie el obsequio, ya tenia configurada en el modal de distribucion de
            //bodegas, cantidades ya distribuidas, para reiniciarlas, ya que le quito las cantidades puestas por el osbequio

            /* if (Compra.limpiarcantBodegas(Compra.codigo_interno_temp)
             == true) {
             Utilities.alertModal('<h4>Las cantidades distribuidas en las bodegas, fueron reiniciadas para' +
             ' el producto ' + codigo_interno + '!</h4>', 'warning', true);
             }*/

            //busco la cantidad y el total actual que ya fue modificada
            var cantidadactual = $("#cantidad_" + Compra.lst_producto[existe]['codigo_interno_temp'] + "_1").val();
            var totalactual = $("#total_" + Compra.lst_producto[existe]['codigo_interno_temp']).val();

            setTimeout(function () {
                //actulizo la cantidad al input del producto que asocie al obsequio
                $("#cantidad_" + Compra.lst_producto[existe]['codigo_interno_temp'] + "_1")
                    .val(parseInt(cantidadactual) - parseInt(cantidad_obsequio));

                //actualizo el costo unitario
                $("#costo_unitario_" + Compra.lst_producto[existe]['codigo_interno_temp']).val((totalactual / (parseInt(cantidadactual) - parseInt(cantidad_obsequio))).toFixed(2));

                //le actualizo la cantidad al producto pero en lst_producto
                Compra.actualizarCantidad(producto_id, codigo_interno,
                    0);
            }, 50)
            return true;

        } else {
            //quiere decir que el producto no existe dentro del archivo plano, por lo que no hago nada

            return false;
        }

    },
    validarObsequioAsociado: function (producto_id, codigo_interno, cantidad_obsequio) {
        //aqui valido si el producto al cual estroy asociando el obsequio,
        //esta o no esta en lst_producto

        //busco la posicion del producto obsequiado dentro de lst_producto
        var existe = Compra.existe_producto_o_codigo(producto_id, Compra.reemplazarcomillas(codigo_interno), Compra.id_detalle_ingreso);

        if (existe != 'false') {  //quiere decir que si existe

            //actualizo esto para que no guarde el producto dos veces
            Compra.lst_producto[existe]['producto_id'] = producto_id;

            var cantidadactual = $("#cantidad_" + Compra.lst_producto[existe]['codigo_interno_temp'] + "_1").val();

            var costo_unit_actual = $("#costo_unitario_" + Compra.lst_producto[existe]['codigo_interno_temp']).val();

            var totalactual=(costo_unit_actual*cantidadactual);

            //actualizo la cantidad
            $("#cantidad_" + Compra.lst_producto[existe]['codigo_interno_temp'] + "_1").val(parseInt(cantidadactual) + parseInt(cantidad_obsequio));

            //actualizo el costo unitario
            $("#costo_unitario_" + Compra.lst_producto[existe]['codigo_interno_temp'])
                .val((totalactual / (parseInt(cantidadactual) + parseInt(cantidad_obsequio))).toFixed(2));

            //le actualizo la cantidad al producto
            Compra.actualizarCantidad(producto_id, Compra.reemplazarcomillas(codigo_interno), parseInt(cantidad_obsequio));

            return true;

        } else {
            //quiere decir que el producto no existe dentro del archivo plano, por lo que no hago nada

            return false;
        }

    },

    actualizarCantidad: function (producto_id, codigo_interno, cantidadnueva) {
        //actualiza la cantidad del producto
        jQuery.each(Compra.lst_producto, function (i, value) {

            if (value["producto_o_codigo"] === Compra.oproducto_o_codigo(producto_id,
                Compra.reemplazarcomillas(codigo_interno))) {
                Compra.lst_producto[i]['unidades'][0]['cantidad_obsequiada'] = cantidadnueva;
            }

        });
    },


    deleteproducto: function () {
        //borra un producto de lst_producto
        //estas variables se llenan cuando se hace click en algun tr

        var tr = Compra.tablalistacompra.row("#producto_" + Compra.codigo_interno_temp);

        tr.remove().draw();

        var lista_vieja = Compra.lst_producto;
        var lista_nueva = new Array();

        Compra.countproducto--;

        Compra.lst_producto = new Array();

        jQuery.each(lista_vieja, function (i, value) {
            if (value["codigo_interno_temp"] === Compra.codigo_interno_temp) {

            } else {
                //almaceno los que no estoy eliminando
                var retorno = lista_vieja[i];
                lista_nueva.push(retorno);
            }
        });

        Compra.lst_producto = lista_nueva;

    },

    armarArregloCatalogo: function (catalogo) {

        var iva = catalogo.iva * 100;
        var iva_id = "";

        var producto_nombre = catalogo.producto_nombre;
        var costo_unitario = "";
        var produto_grupo = "";
        var producto_ubicacion_fisica = "";
        var producto_tipo = "";

        producto_nombre = producto_nombre.substring(producto_nombre.indexOf("-") + 1);

        var row = new Array();

        row['productonuevo'] = true;
        row['producto_id'] = "";
        row['producto_codigo_interno'] = '"' + catalogo.producto_codigo_interno + '"';
        row['deshabilitar'] = "";

        row['producto_nombre'] = producto_nombre;
        row['costo_unitario'] = '"' + costo_unitario + '"';
        row['produto_grupo'] = produto_grupo;
        row['producto_ubicacion_fisica'] = producto_ubicacion_fisica;
        row['producto_tipo'] = producto_tipo;
        row['precios'] = new Array();
        row['bonificacion'] = catalogo.bonificacion;
        //las unidades del producto
        row['contenido_interno'] = new Array();
        row['precio_corriente'] = catalogo.costo_corriente;
        row['codigo_barra'] = catalogo.producto_codigo_barra;

        //busco los precios de este producto
        var llamada_precios = UnidadesService.getSoloPreciosByProd("");
        llamada_precios.success(function (data) {
            row['precios'] = data;
        });

        if (iva == "null") {
            iva = "";
            iva_id = "";
        }
        row['iva'] = iva;
        row['iva_id'] = '"' + iva_id + '"';

        return row;
    },

    load_editar: function (detalle_compra) {
        //este metodo es invocado cuando se esta editanto una compra
        //el arreglo detalles ya viene lleno desde la vista de ingresos


        Compra.proveedor_id = Compra.ingresoCompleto.id_proveedor;
        Compra.proveedor_nombre = Compra.ingresoCompleto.proveedor_nombre;
        Compra.tipo_carga = Compra.ingresoCompleto.tipo_carga;


        if (Compra.ingresoCompleto.tipo_carga == "AUTOMATICA") {
            Compra.thead_coopidrogas();
        }

        var nuevo = false;
        var row = new Array();
        var arreglo = new Array();

        for (var i = 0; i < detalle_compra.length; i++) {

            row = Compra.armarDetalle(detalle_compra[i]);
            row['editar'] = true;
            //esto es para preguntar en la tabla, para colorearlos
            row.bool_obsequio = detalle_compra[i].is_obsequio;
            row.bool_prepack = detalle_compra[i].is_prepack;

            //aqui pregunto si del detalle viene codigo_interno_temp, que solo viene ya definido cuando fue un archivo de coopidrogas
            if (detalle_compra[i]['codigo_interno_temp'] == undefined) {
                row.codigo_interno_temp = Compra.crearcodigoTemp(detalle_compra[i]['producto_id'],
                    Compra.reemplazarcomillas(detalle_compra[i]['producto_codigo_interno']));
            }
            //le asigno elv alor a la variable para que en existe_producto() pueda validar
            Compra.codigo_interno_temp = row.codigo_interno_temp;

            var modificando = row;
            var existe = Compra.existe_producto();

            if (existe != 'false' && Compra.ingresoCompleto.tipo_carga == "MANUAL") {
                Utilities.alertModal('<h4>Ya existe un registro para este producto!</h4>', 'warning', true);
                return false;
            } else {
                row = new Array();
                arreglo = new Array();
                arreglo = Compra.armarArregloNormal(detalle_compra[i]['producto_id'], detalle_compra[i]['producto_codigo_interno'], modificando, nuevo);

                //uno los dos objetos
                row = Object.assign({}, modificando, arreglo);
                row.codigo_interno_temp = Compra.codigo_interno_temp;
                if (Compra.ingresoCompleto.tipo_carga == "AUTOMATICA") {
                    //entra aqui cuando se esta editando una factura de coopidrogas
                    if (i == 0) {
                        //elimino el que utilizo para seleccionar un producto porque aqui no se podra seleccionar otro
                        $("#tbodyproductos tr:last").remove();
                    }
                    row.producto_id = modificando.producto_id

                    row.precio_corriente = "";
                    row.bonificacion = '0';
                    row.productonuevo = false;
                    row.iva = Compra.reemplazarcomillas(row.iva);
                    row.descuento = "";
                    row.iva_id = Compra.reemplazarcomillas(row.iva_id);
                    row.costo_unitario = Compra.reemplazarcomillas(row.costo_unitario);

                    row.total_iva = modificando.total_impuesto;
                    row.descuento = modificando.descuento;
                    row.bonificacion = modificando.bonificacion;

                    //si fue un producto obsequiado, lo coloco asociado para que el costo sea 0 en el calculo
                    if (row.total == 0.00) {

                        row.productonuevo = "asociado";
                    }
                    Compra.totable_coopidrogas(row, i);
                    Compra.addProductoToArray(row);
                } else {
                    row.id_detalle_ingreso = modificando.id_detalle_ingreso;
                    Compra.agregar(row);
                }
            }
        }

        Compra.colocardatepicker();
        $('.codigo_barra').off('keypress');
        Compra.inputsearch_barra();

        Compra.grabarTrEspecial();

        $("#total_costo").val(Compra.ingresoCompleto.sub_total_ingreso);
        $("#total_iva").val(Compra.ingresoCompleto.impuesto_ingreso);
        $("#total_facturado").val(Compra.ingresoCompleto.total_ingreso);
        $("#total_descuento").val(Compra.ingresoCompleto.total_descuento);
        $("#total_bonificado").val(Compra.ingresoCompleto.total_bonificado);


        Utilities.hiddePreloader();
    },


    nombre_campo_return: function (codigointerno) {
        //esta funcion pregunta si el codigointerno es 0; retorna un string con producto id, es usada en validar la existencia en inventario
        if (codigointerno == 0) {

            return 'producto_id';
        }

        return 'producto_codigo_interno';

    },
    agregar: function (row) {
        //cuando se selecciona un producto en el modal donde se listan.
        //o cuando se crea un nuevo producto desde la compra, tambien pasa por aqui
        // Utilities.setfocus(".inputsearchproduct:last-child");
        Compra.addproductototable(row)

        $('tr').off('keydown');
        $("tr").on('keydown', function (e) {

            if (e.keyCode == 46) {

                e.preventDefault();
                Compra.deleteproducto();
                $("#total_productos").val(Compra.countproducto);
                //hago el calculo de los totales
                Compra.calculatotales('normal');
            }

        });
        //les da datepicker a los input que tengan para pedir fecha de vencimiento
        Compra.colocardatepicker();

        Compra.inputsearch();
        $('.codigo_barra').off('keypress');
        Compra.inputsearch_barra();

        this.producto_seleccionadoid = row['producto_id'];
        this.producto_seleccionadocodigo = Compra.reemplazarcomillas(row['producto_codigo_interno']);

        var stock = new Array();
        var precios = new Array();
        var costo_unitario = "";

        if (row.stock != undefined) {
            stock = row.stock
        }
        if (row.precios != undefined) {
            precios = row.precios
        }
        if (row.costo_unitario != undefined) {
            costo_unitario = row.costo_unitario
        }
        /*si desde el arreglo row, ya viene el costo_unitario y el stock y los precios, los mando de una vez para
         * no voverlos a llamar*/
        Compra.buscarExistenciaProducto(row['producto_id'], Compra.reemplazarcomillas(row['producto_codigo_interno']), false,
            stock, precios, costo_unitario);

        Compra.addProductoToArray(row);
        Compra.grabarTrEspecial();

        $('#seleccionunidades').modal('hide');

        Utilities.hiddePreloader();
    },
    buscarExistenciaProducto: function (producto_id, codigo_interno, productonuevo, stock=new Array(), precios= new Array(),
                                        costo_unitario="") {

        //busca los datos del producto seleccionado en ivnentario
        var codigoaMostrar = $("#td_codigo_" + Compra.codigo_interno_temp).html();

        //esto pasa cuando es coopidrogas, ya que el codigo esta dentro de un <p>,
        //en otros proveedores no, todo esta dentro del <td>
        if (codigoaMostrar == undefined) {
            codigoaMostrar = $("#p_codigo_" + Compra.codigo_interno_temp).html()
        }
        $("#mostrar_nombre").html('');
        //esto es para mostrar el nombre  el codigo del producto sobre el que se esta escribiendo
        $("#mostrar_codigo").val(codigoaMostrar);
        $("#mostrar_nombre").html('<b>Nombre: ' + $("#td_nombre_" + Compra.codigo_interno_temp).html() + '</b>');

        $.each(Compra.unidades, function (key, value) {
            //$("#contenido_" + value.id_unidad).val(0);
            $("#existencia_" + value.id_unidad).text(0);

        });
        $(".ultimosprecios").text(0);
        $("#ultimo_costo_compra").val("")


        /*si desde el arreglo row, ya viene el costo_unitario y el stock y los precios, los mando de una vez para
         * no voverlos a llamar*/

        if (productonuevo == 'false' && stock.length < 1) {
            precios = new Array();
            $.ajax({
                type: 'POST',
                data: '&' + Compra.nombre_campo_return(codigo_interno) + '=' + Compra.datovalido(producto_id,
                    Compra.reemplazarcomillas(codigo_interno))+"&getprecios=soloprecios",
                dataType: "json",
                async: false,
                url: baseurl + 'inventario/buscarExistenciayPrecios',
                success: function (data) {
                    stock = data.stock;
                    precios=data.precios
                    costo_unitario = data.costo_unitario
                }
            });

            //busco los precios de este producto
            /* precios = UnidadesService.getSoloPreciosByProd(producto_id);
             precios.success(function (data) {
                 precios = data;
             }).error(function () {
                 Utilities.alertModal('<h4>Ha ocurrido un error al buscar los &uacute;ltimos precios de venta del producto' +
                     '</h4>', 'warning', true);

             })*/
        }

        Compra.costo_unitario_default = costo_unitario
        /*guardo el cost unitario que tiene el producto*/

        /*recorro siempr eel stock, siemre y cuando el arreglo tenga datos*/
        if (stock.length > 0) {
            $.each(stock, function (key, value) {
                //$("#contenido_" + value.id_unidad).val(value.unidades);
                $("#existencia_" + value.id_unidad).text(value.cantidad);
            });
        }

        if (precios.length > 0) {
            var preci = "";

            $.each(precios, function (key, value) {
                setTimeout(function () {
                    preci = "";
                    if (value.precio != null) {
                        preci = value.precio;
                    }
                    $("#ultimoprecioventa_" + value.id_condiciones_pago + "_" + value.id_unidad).text(preci);

                }, 300)

            });
        }

        setTimeout(function () {

            if (costo_unitario != "" && costo_unitario != null) {
                $("#ultimo_costo_compra").val(costo_unitario)
            } else {
                $("#ultimo_costo_compra").val("")
            }

        }, 500)
    },
    addProductoToArray: function (row) {
        Compra.codigo_interno_temp = row.codigo_interno_temp
        var producto = {};

        if (row.producto_id == '""') {
            row.producto_id = "";
        }
        producto.producto_id = row.producto_id;
        producto.producto_nombre = encodeURIComponent(row.producto_nombre);
        producto.count = this.countproducto;
        producto.producto_codigo_interno = Compra.reemplazarcomillas(row.producto_codigo_interno);
        producto.producto_o_codigo = Compra.oproducto_o_codigo(row.producto_id,
            Compra.reemplazarcomillas(row.producto_codigo_interno));
        producto.codigo_interno_temp = Compra.codigo_interno_temp;
        producto.productonuevo = row.productonuevo;


        var unidades_prod = new Array();
        var cantidad = "0";
        var cantidadCaja = "0";
        jQuery.each(Compra.unidades, function (i, value) {

            var unidad = {};
            unidad.id = value.id_unidad;
            unidad.nombre_unidad = value.nombre_unidad;
            unidad.abreviatura = value.abreviatura;
            unidad.orden = value.orden;
            unidad.id_detalle_unidad = '';

            cantidad = "0";
            if ($("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).val() != ""
                && $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).val() != undefined) {
                cantidad = $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).val();
                if (i == 0) {

                    cantidadCaja = cantidad;
                }
            }

            var costo = 0;
            if (row.editar != undefined) {

                for (var y = 0; y < row.detalle_unidad.length; y++) {

                    if (row.detalle_unidad[y].unidad_id == value.id_unidad) {
                        costo = row.detalle_unidad[y].costo_total;
                        unidad.detalle_ingreso_unidad_id = row.detalle_unidad[y].detalle_ingreso_unidad_id;
                    }

                }
            }

            unidad.cantidad = cantidad;
            unidad.costo = costo;

            unidades_prod.push(unidad);
        });

        //esta variable row.codigo_barra viene del archivo plano, es decir, solo con el proveedor principal
        //ya que la variable que se guarda realmente con los codigos de barra es (codigosBarra)
        if (row.codigo_barra != undefined) {

            var codigos_barra = row.codigo_barra;
            producto.codigosBarra = new Array();

            for (var i = 0; i < Object.keys(codigos_barra).length; i++) {
                producto.codigosBarra[i] = codigos_barra[i];
                //le digo que ya tiene los codigos de barra de la base de datos
                producto.yaActualizoCodigoBarra = true;
            }

        }


        var total_iva = 0;
        var total_producto = 0;
        if (row.editar != undefined) {
            total_iva = row.total_impuesto;
            total_producto = row.total_detalle;
        }

        producto.total_iva = total_iva;
        producto.total_producto = total_producto;

        producto.unidades = unidades_prod;

        if (row.id_detalle_ingreso) {
            producto.id_detalle_ingreso = row.id_detalle_ingreso;
        }

        producto.contenido_interno = {};
        producto.precios = {};

        Compra.lst_producto.push(producto);

        var solouna = false;

        if (row.id_detalle_ingreso) {    //verifico si estoy editando la compra

            var posicion = Compra.existe_producto(row.producto_id, row.producto_codigo_interno);

            if (Compra.detalle_especial.length > 0) {

                for (var i = 0; i < Compra.detalle_especial.length; i++) {

                    //veo si el id esta en detalleingreso_especial
                    if (Compra.detalle_especial[i]['detalle_ingreso_id'] == row.id_detalle_ingreso) {
                        //guardo el obsequio
                        if (solouna == false) {
                            solouna = true;

                            if (Compra.detalle_especial[i]['tipo'] == "OBSEQUIO" &&
                                Compra.lst_producto[posicion]['is_obsequio'] == undefined) {
                                //creo el arreglo is_obsequio
                                Compra.lst_producto[posicion]['is_obsequio'] = new Array();

                                //le digo que esta en 0, porque ya la cantidad del obsequio ya fue asociada a un producto
                                $("#cantidad_" + Compra.lst_producto[posicion]['codigo_interno_temp'] + "_" + Compra.unidades[0].id_unidad).val("0");

                            } else {
                                //creo el arreglo is_prepack
                                if (Compra.lst_producto[posicion]['is_prepack'] == undefined) {
                                    Compra.lst_producto[posicion]['is_prepack'] = new Array();
                                }
                            }
                        }

                        if (Compra.detalle_especial[i]['tipo'] == "OBSEQUIO") {

                            Compra.guardarObsequio(posicion, Compra.detalle_especial[i]['producto_id'],
                                Compra.detalle_especial[i]['producto_codigo_interno'],
                                Compra.detalle_especial[i]['cantidad'],
                                Compra.detalle_especial[i]['producto_nombre'], Compra.detalle_especial[i]['costo_total']);

                        } else {

                            Compra.guardarPrepack(posicion, Compra.detalle_especial[i]['producto_nombre'], Compra.detalle_especial[i]['producto_id'],
                                Compra.detalle_especial[i]['producto_codigo_interno'],
                                Compra.detalle_especial[i]['unidad_id'], Compra.detalle_especial[i]['cantidad'],
                                Compra.detalle_especial[i]['costo_total']);
                        }

                    }

                    //pregunto si es obsequio y si este producto fue asociado a un obsequio
                    //para colocarle la cantidad sumada del obsequio mas la de el normal
                    if (Compra.detalle_especial[i]['tipo'] == "OBSEQUIO" &&
                        row.producto_id == Compra.detalle_especial[i]['producto_id']) {
                        //le digo que esta en 0, porque ya la cantidad del obsequio ya fue asociada a un producto
                        $("#cantidad_" + Compra.codigo_interno_temp + "_" + Compra.unidades[0].id_unidad).val(parseInt(cantidadCaja) + parseInt(Compra.detalle_especial[i]['cantidad']));

                    }

                }

            }

        }

        Compra.countproducto++;
        $("#total_productos").val(Compra.countproducto);


    },
    addproductototable: function (row) {
        //este agrega los productos a la tabla pero de otros proveedores diferentes de coopidrogas

        if (row.id_detalle_ingreso == undefined) {
            row.id_detalle_ingreso = "";
        }

        Compra.id_detalle_ingreso = row.id_detalle_ingreso
        Compra.codigo_interno_temp = row.codigo_interno_temp

        var newrow = {};

        var count = 1;

        newrow[0] = Compra.oproducto_o_codigo(row.producto_id,
            Compra.reemplazarcomillas(row.producto_codigo_interno));

        newrow[count] = "" + row.producto_nombre + "";
        count++;

        var normal = '"normal"';
        //columna de descuentos
        newrow[count] = "<input  onkeydown='return soloDecimal(this, event);' name='descuento_[]' " +
            "onkeyup='return Compra.calculatotales(" + normal + ")' " +
            " type='text'" +
            " id='descuento_" + Compra.codigo_interno_temp + "'  " +
            " value='" + row.descuento + "'   class='form-control' >";

        count++;

        var cantidad = "";
        var costo_total = "";
        var total_iva = "";
        var total = "";
        var readonly = "";

        if (row.detalle_unidad != undefined) {
            total_iva = row.total_impuesto;
            total = row.total_detalle;
        } else {
            row.detalle_unidad = new Array();
        }

        var total_esta_unidad = 0;
        var contenido_interno = row.contenido_interno;
        var costo_con_descuento = "";


        //cantidad y costo respectivamente
        for (var i = 0; i < Compra.unidades.length; i++) {
            cantidad = "";
            costo_total = "";
            readonly = "readonly";

            //esto lo hago para saber si tiene contenido interno para dejar comprar por las unidades o no
            if (contenido_interno != undefined) {
                //busco las unidades de la unidad que esta pasando.
                for (var j = 0; j < contenido_interno.length; j++) {

                    if (contenido_interno[j].id_unidad == Compra.unidades[i].id_unidad) {
                        readonly = "";
                    }
                }
            }

            //si row.detalle_unidad.length es>0 estoy editando
            for (var y = 0; y < row.detalle_unidad.length; y++) {

                if (row.detalle_unidad[y].unidad_id == Compra.unidades[i].id_unidad) {
                    cantidad = row.detalle_unidad[y].cantidad;
                    costo_total = row.detalle_unidad[y].costo_total;
                    costo_con_descuento = row.detalle_unidad[y].costo_con_descuento;

                    total_esta_unidad = $("#total_cantidad_" + Compra.unidades[i].id_unidad).val();
                    $("#total_cantidad_" + Compra.unidades[i].id_unidad).val(parseFloat(total_esta_unidad) + parseFloat(cantidad));
                    readonly = "";
                }

            }

            newrow[count] = "<input type='text'  " + readonly + " name='cantidad_" + Compra.countproducto + "[]' " +
                "onkeydown='return soloDecimal(this, event)' " +
                "onkeyup='return Compra.calculatotales(" + normal + ")'" +
                " id='cantidad_" + Compra.codigo_interno_temp + "_" + Compra.unidades[i].id_unidad + "'  " +
                " value='" + cantidad + "' class='form-control' >";
            count++;
            newrow[count] = "<input " + readonly + "  name='costo_" + Compra.countproducto + "[]' " +
                "onkeydown='return soloDecimal(this, event)' " +
                "onkeyup='return Compra.calculatotales(" + normal + ")'" +
                " type='text' id='costo_" + Compra.codigo_interno_temp + "_" + Compra.unidades[i].id_unidad + "'  " +
                " value='" + costo_total + "' class='form-control' >";
            count++;

        }

        /*columna que mostrara los costos con el % de descuento*/
        newrow[count] = "<input name='costo_con_descuento[]' onkeyup='return Compra.calculadescuento(" + normal + ")'" +
            " onkeydown='return soloDecimal(this, event);' type='text'" +
            " id='costo_con_descuento" + Compra.codigo_interno_temp + "'  value='" + costo_con_descuento + "' readonly class='form-control' >";
        count++;


        //iva, totaliva y total respectivamente
        newrow[count] = "<input name='iva_[]' onkeydown='return soloDecimal(this, event);' " +
            "type='text' id='iva_" + Compra.codigo_interno_temp + "'  value=" + row.iva + " readonly class='form-control' >" +
            "<input name='ivaid_[]'  type='hidden' id='ivaid_" + Compra.codigo_interno_temp + "'  value=" + row.iva_id + " readonly class='form-control' >";
        count++;
        newrow[count] = "<input name='total_iva_[]' onkeydown='return soloDecimal(this, event);' type='text'" +
            " id='total_iva_" + Compra.codigo_interno_temp + "'  value='" + total_iva + "' readonly class='form-control' >";
        count++;

        newrow[count] = "<input name='total_[]' onkeydown='return soloDecimal(this, event);' type='text' " +
            "id='total_" + Compra.codigo_interno_temp + "'  value='" + total + "' readonly class='form-control' >";
        count++;

        newrow[count] = "<input readonly type='text' name='costo_unitario_[" + Compra.countproducto + "]' " +
            "id='costo_unitario_" + Compra.codigo_interno_temp + "'  " +
            "value=" + row.costo_unitario + " class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";
        count++;

        var arreglo = new Array();

        //LLAMO AL TBODY TANTO PARA COOPIDROGAS Y  OTROS PROVEEDORES;
        arreglo = Compra.tbody_todos(row, this.countproducto, count);


        var tr = new Array();
        tr = Object.assign({}, newrow, arreglo);

        var classtrepecial = "trespecial";

        var rowNode = Compra.tablalistacompra.row.add(tr).draw().node();


        $(rowNode).attr("id", 'producto_' + Compra.codigo_interno_temp);
        $(rowNode).attr("data-producto_id", row.producto_id);
        $(rowNode).attr("data-id_detalle_ingreso", row.id_detalle_ingreso);
        $(rowNode).attr("data-codigo_interno_temp", row.codigo_interno_temp);

        $(rowNode).attr("data-productonuevo", row.productonuevo);

        $(rowNode).attr("data-producto_codigo_interno", row.producto_codigo_interno);

        $(rowNode).addClass(classtrepecial);

        //esto es para poder borrar un tr al presionar la tecla suprimir
        $(rowNode).attr("tabindex", Compra.tablalistacompra.rows().count() - 2);

        count = 0;

        //esto es para agregarle los id a los td
        var rr = Compra.tablalistacompra.row('#producto_' + Compra.codigo_interno_temp).nodes().to$().find('td')
            .each(function () {

                if (count == 0) {

                    $(this).attr('id', 'td_codigo_' + Compra.codigo_interno_temp);
                }

                if (count == 1) {

                    $(this).attr('id', 'td_nombre_' + Compra.codigo_interno_temp);

                }


                count++;
            });

        var trvacio = Compra.tablalistacompra.row('#trvacio');
        trvacio.remove().draw();

        Compra.addproductototablevacio();

        //var i = Compra.tablalistacompra.cell(Compra.countproducto, 2).nodes().to$().find('input');
        setTimeout(function () {

            Compra.tablalistacompra.cell(Compra.countproducto - 1, 3).focus();
        }, 500)


        //alert('dd')
        // $("#" + i.attr('id')).focus();

        /*   Compra.tablalistacompra.cell(Compra.countproducto, 2).focus();
         Compra.tablalistacompra.row('#producto_' + Compra.oproducto_o_codigo(row.producto_id,
         Compra.reemplazarcomillas(row.producto_codigo_interno))).nodes().to$().find('td')*/

        /* $("#" + objectCell.attr('id')).focus();*/


        /*
         * //  Compra.tablalistacompra.cell(0, 0).focus();
         console.log(Compra.countproducto);
         Compra.tablalistacompra.cell(Compra.countproducto, 2).focus();
         */

    },
    tbody_todos: function (row, contador, count) {
        //ESTOS SO LOS CAMPOS QUE SON TANTO PARA COOPIDOGAS Y PARA OTROS PROVEEDORES

        if (row.id_detalle_ingreso == undefined) {
            row.id_detalle_ingreso = "";
        }
        var stringtemp = '"' + Compra.codigo_interno_temp + '"' //lo coloco en comillas al cdigo temporal para las funciones
        var newrow = {};

        var disabled = true;
        var readonly = "";
        var value = "";
        var contenido_interno = row.contenido_interno;
        var consiguiocaja = false;

        //contenido interno
        for (var i = 0; i < Compra.unidades.length; i++) {

            disabled = "";
            readonly = "";

            //por defecto declaro vacio
            value = "";
            //si exste contenidointerno quiere decir que tiene datos en la tabla unidades_has_producto
            if (contenido_interno != undefined) {
                //busco las unidades de la unidad que esta pasando.
                for (var j = 0; j < contenido_interno.length; j++) {

                    if (contenido_interno[j].id_unidad == Compra.unidades[i].id_unidad) {
                        value = contenido_interno[j]['unidades'];
                        if (i == 0) {
                            consiguiocaja = true;
                        }
                    }
                }
            }

            if ((i > 0 && value == "") || (i == 2)) {

                readonly = "readonly";
            }


            if (i == 1 && consiguiocaja == true && contenido_interno != undefined &&
                contenido_interno.length > 1) {
                readonly = "";
            }

            newrow[count] = "<input type='text' " + readonly + " " + disabled + "";
            newrow[count] += " onkeydown='return soloNumeros(event), Compra.validar_numeropar(event,this," + i + ")' " +
                " onkeyup='Compra.contenido_interno(this," + i + "," + stringtemp + "," + Compra.unidades[i].id_unidad + ")' " +
                "name='contenido_in" + contador + "[]' " +
                " id='contenido_in" + Compra.codigo_interno_temp + "_" + i + "'  value='" + value + "' class='form-control' " +
                " data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'>";
            count++;
        }

        var precio = "";
        var utilidad = "";
        var precios = "";
        var id_condicion = "";
        var color = "";

        if (row.iva == "") {
            row.iva = Compra.ivaConComilla(row.iva);
        }

        var readonly = "";

        jQuery.each(Compra.condiciones_pago, function (j, valor) {

            jQuery.each(Compra.unidades, function (i, value) {
                readonly = "readonly";

                //por defecto delaro vacio
                precio = "";
                utilidad = "";

                //si exste precios quiere decir que tiene datos en la tabla unidades_has_precio
                if (row.precios.length > 0) {
                    precios = row.precios;

                    //busco los precios de la unidad que esta pasando.
                    for (var t = 0; t < precios.length; t++) {

                        if (precios[t].id_unidad == Compra.unidades[i].id_unidad &&
                            precios[t].id_condiciones_pago == Compra.condiciones_pago[j].id_condiciones) {
                            if (precios[t].precio != null) {
                                precio = precios[t].precio;
                            }

                            if (precios[t].utilidad != null) {
                                utilidad = precios[t].utilidad;
                            }
                        }
                    }
                }

                //si exste contenidointerno quiere decir que tiene datos en la tabla unidades_has_producto
                if (contenido_interno) {
                    //busco las unidades de la unidad que esta pasando.
                    for (var t = 0; t < contenido_interno.length; t++) {

                        if (contenido_interno[t].id_unidad == value.id_unidad) {
                            readonly = "";
                        }
                    }
                }


                if (valor.nombre_condiciones == "CONTADO") {
                    color = Compra.colores_contado[i];
                }

                if (valor.nombre_condiciones == "CREDITO") {
                    color = Compra.colores_credito[i];
                }

                if (row.costo_unitario == "") {
                    row.costo_unitario = '"' + row.costo_unitario + '"';
                }

                id_condicion = valor.id_condiciones;

                var producto_idvacio = '""';
                if (row.producto_id == "") {
                    row.producto_id = producto_idvacio;
                }

                newrow[count] = "<input type='text' " + readonly + " " +
                    "name='utilidad_" + contador + "_" + j + "[]' " +
                    "id='utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones + "' class='form-control' " +
                    "value='" + utilidad + "' " +
                    "onkeyup='Compra.calcular_precio(this,event," + row.producto_id + "," + row.producto_codigo_interno + "," + value.id_unidad + "," + row.costo_unitario + "," + row.iva + "," + valor.id_condiciones + "," + i + ")'  " +
                    " onkeydown='return soloDecimal(this, event)' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'>";

                count++;

                newrow[count] = "<input type='text' " + readonly + " " +
                    "name='precio_valor_" + contador + "_" + j + "[]' " +
                    "id='precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones + "'  " +
                    "class='form-control' value='" + precio + "' " +
                    " onkeyup='Compra.calcular_utilidad(this,event," + row.producto_id + "," + row.producto_codigo_interno + "," + value.id_unidad + "," + row.costo_unitario + "," + row.iva + "," + valor.id_condiciones + "," + i + ")' " +
                    " onkeydown='return soloDecimal(this, event)' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >" +
                    "<input type='hidden' name='precio_id_" + contador + "_" + j + "[]' " +
                    "data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' class='form-control' value='" + id_condicion + "' >";
                count++;
            });
        });


        newrow[count] = "<input type='text' class='form-control codigo_barra' name='codigobarra_[]' " +
            "style='width:150px !important'" +
            "data-producto_id='" + row.producto_id + "' placeholder='Presione Enter' " +
            " data-codigo='" + Compra.reemplazarcomillas(row.producto_codigo_interno) + "' " +
            " id='codigobarra_" + Compra.codigo_interno_temp + "' " +
            " value='' class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";
        count++;
        newrow[count] = "<select name='tipoprod_[]' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'" +
            "id='tipoprod_" + Compra.codigo_interno_temp + "' class='cho form-control' >" +
            "<option value='' selected > Seleccione</option>";

        var selected = "";
        //tipos producto
        for (var j = 0; j < this.tipos_productos.length; j++) {
            selected = "";
            if (row.producto_tipo == this.tipos_productos[j].tipo_prod_id) {
                selected = "selected";
            }
            newrow[count] += "<option value='" + this.tipos_productos[j].tipo_prod_id + "' " + selected + " > " + this.tipos_productos[j].tipo_prod_nombre + "</option>";
        }
        newrow[count] += "</select>";
        count++;
        //ubicaciones fisicas;
        newrow[count] = "<select name='ubicacion_[]' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'" +
            " id='ubicacion_" + Compra.codigo_interno_temp + "' class='cho form-control' >" +
            "<option value='' selected > Seleccione</option>";

        //tipos producto
        for (var j = 0; j < this.ubicaciones.length; j++) {
            selected = "";
            if (row.producto_ubicacion_fisica == this.ubicaciones[j].ubicacion_id) {
                selected = "selected";
            }
            newrow[count] += "<option value='" + this.ubicaciones[j].ubicacion_id + "' " + selected + " > " + this.ubicaciones[j].ubicacion_nombre + "</option>";
        }
        newrow[count] += "</select>";
        count++;

        //grupos;
        newrow[count] = "<select name='grupo_[]' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'" +
            "id='grupo_" + Compra.codigo_interno_temp + "' class='cho form-control' >" +
            "<option value='' selected > Seleccione</option>";

        //tipos producto
        for (var j = 0; j < this.grupos.length; j++) {
            selected = "";
            if (row.produto_grupo == this.grupos[j].id_grupo) {
                selected = "selected";
            }
            newrow[count] += "<option value='" + this.grupos[j].id_grupo + "' " + selected + " > " + this.grupos[j].nombre_grupo + "</option>";
        }
        newrow[count] += "</select>";
        count++;

        return newrow;

    },
    get_codigo_final: function (producto_codigo, codigo_interno_rep) {

        if (codigo_interno_rep != "" && codigo_interno_rep != undefined) {
            return codigo_interno_rep;
        }
        return producto_codigo;

    },
    totable_coopidrogas: function (row, contador) {
        // agrega los productos pero del archivo de coopidrogas
        Compra.id_detalle_ingreso = row.id_detalle_ingreso
        if (row.id_detalle_ingreso == undefined) {
            row.id_detalle_ingreso = "";
        }

        Compra.codigo_interno_temp = row.codigo_interno_temp
        if (row.codigo_interno_temp == undefined) {
            row.codigo_interno_temp = "";
        }
        var newrow = {};

        var count = 1
        newrow[0] = "<input type='radio' name='radio' onclick='Compra.validaObsePrepack(this)' class='' " +
            " value='' data-producto_id='" + row.producto_id + "' data-codigo_interno_temp='" + Compra.codigo_interno_temp + "' " +
            "data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' " +
            " data-codigo='" + Compra.reemplazarcomillas(row.producto_codigo_interno) + "'>" +
            "<p class='todo_p' id='p_codigo_" + Compra.codigo_interno_temp + "'>" + Compra.reemplazarcomillas(row.producto_codigo_interno);

        newrow[count] = row.producto_nombre;

        /*newrow[count] = "<td class='nombre'  id='td_nombre_" + Compra.oproducto_o_codigo(row.producto_id,
         Compra.reemplazarcomillas(row.producto_codigo_interno)) + "' >" + row.producto_nombre + " </td>";*/

        count++;

        newrow[count] = "<input readonly onkeydown='return soloDecimal(this, event);' name='descuento_[]' type='text'" +
            " id='descuento_" + Compra.codigo_interno_temp + "'  " +
            " value='" + row.descuento + "'  readonly class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "'  >";

        count++;

        newrow[count] = "<input readonly onkeydown='return soloDecimal(this, event);' name='bonificacion_[]'  type='text' " +
            " id='bonificacion_" + Compra.codigo_interno_temp + "'  value=" + row.bonificacion + "" +
            " readonly class='form-control'  data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";

        count++;


        if (row.detalle_unidad != undefined) {

            row.cantidad = row.detalle_unidad[0].cantidad;

        }

        var total_esta_unidad = 0;
        //PENDIENTE CON ESTO, SE SUPONE QUE CAJA DEBE SER EL ORDEN 1, YA QUE PARA COOPIDROGAS SIEMPRE SE COMPRA POR CAJAS
        for (var i = 0; i < this.unidades.length; i++) {

            if (this.unidades[i].orden == 1) {

                /*aqui actualizo el total de cajas global*/
                total_esta_unidad = $("#total_cantidad_" + this.unidades[i].id_unidad).val();
                $("#total_cantidad_" + this.unidades[i].id_unidad).val(parseFloat(total_esta_unidad) + parseFloat(row.cantidad));
                /****************************************/

                newrow[count] = "<input type='text' readonly name='cantidad_" + contador + "[" + i + "]' readonly " +
                    "id='cantidad_" + Compra.codigo_interno_temp + "_" + this.unidades[i].id_unidad + "'  " +
                    "value='" + row.cantidad + "' class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";
                count++;
            }
        }

        newrow[count] = " <input readonly type='text' name='precio_corriente_[" + contador + "]'" +
            " id='precio_corriente_" + Compra.codigo_interno_temp + "'  " +
            "value='" + row.precio_corriente + "' class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";
        count++;

        newrow[count] = "<input readonly type='text' name='iva_[" + contador + "]' " +
            "id='iva_" + Compra.codigo_interno_temp + "'  value='" + row.iva + "' class='form-control'" +
            " data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' > " +
            "<input readonly  name='ivaid_[" + contador + "]'  type='hidden' " +
            "id='ivaid_" + Compra.codigo_interno_temp + "'  value=''  class='form-control' " +
            "data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";

        count++;
        newrow[count] = "<input readonly type='text' name='total_iva_[" + contador + "]'" +
            " id='total_iva_" + Compra.codigo_interno_temp + "'  value='" + row.total_iva + "'" +
            " class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' > ";
        count++;


        //para coopidrogas en lac arga del archivo, este total viene vacio
        //se calcula es al final de agregar todos los productos en la tabla, con al funcion calculatotales()
        if (row.total_detalle == undefined) {
            row.total_detalle = "";
        }

        newrow[count] = " <input readonly type='text'  name='total_[" + contador + "]' " +
            "id='total_" + Compra.codigo_interno_temp + "'  " +
            "value='" + row.total_detalle + "' class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";
        count++;

        newrow[count] = "<input readonly type='text' name='costo_unitario_[" + contador + "]' " +
            "id='costo_unitario_" + Compra.codigo_interno_temp + "'  " +
            "value='" + row.costo_unitario + "' class='form-control' data-id_detalle_ingreso='" + row.id_detalle_ingreso + "' >";

        count++;

        var arreglo = new Array();
        arreglo = Compra.tbody_todos(row, contador, count);

        var tr = new Array();
        tr = Object.assign({}, newrow, arreglo);


        var rowNode = Compra.tablalistacompra.row.add(tr).draw().node();

        $(rowNode).attr("id", 'producto_' + Compra.codigo_interno_temp);

        $(rowNode).attr("data-producto_id", row.producto_id);

        $(rowNode).attr("data-id_detalle_ingreso", row.id_detalle_ingreso);

        $(rowNode).attr("data-codigo_interno_temp", row.codigo_interno_temp);

        $(rowNode).attr("data-productonuevo", row.productonuevo);

        $(rowNode).attr("data-producto_codigo_interno", row.producto_codigo_interno);

        if (row.productonuevo == true) {
            $(rowNode).css('color', '#d84545');
        }

        if (row.bool_prepack == 1) {
            $(rowNode).css('color', '#71da71');
        }

        if (row.bool_obsequio == 1) {
            $(rowNode).css('color', '#FFA07A');
        }


        if (row.asociadoACodigo != undefined) {
            $(rowNode).attr("data-asociadoACodigo", row.asociadoACodigo);
        }

        count = 0;
        //esto es para agregarle los id a los td
        var rr = Compra.tablalistacompra.row('#producto_' + Compra.codigo_interno_temp).nodes().to$().find('td')
            .each(function () {
                if (count == 1) {
                    $(this).attr('id', 'td_nombre_' + Compra.codigo_interno_temp);
                }
                count++;
            });

        // Compra.tablalistacompra.cell(0, 0).focus();

    },
    limpiartotales: function () {
        //limpia los totales
        $("#total_productos").val(0);
        $("#total_costo").val('0.00');
        $("#total_iva").val('0.00');
        $("#total_facturado").val('0.00');
        $("#total_bonificado").val('0.00');
        $("#total_descuento").val('0.00');
        Compra.countproducto = 0;
        Compra.lst_producto = new Array();
        Compra.detalles = new Array();

        for (var i = 0; i < this.unidades.length; i++) {
            $("#total_cantidad_" + this.unidades[i].id_unidad).val(0);
        }
    },
    calculadescuento: function () {
        /*cada vez que se escribe sobre algun input de costo_con_descuento de cada producto*/

    },
    calculatotales: function (tipo) {
        //van a haber total_cantidades por cada unidad
        //tipo es para saber si estoy con el archivo de coopidogas o con otro proveedor
        //ya que depende del proveedor, voy a multiplicar el costo por la cantidad o no.

        var bonificacion = 0;
        var descuento = 0;
        var total_costo = 0.00;
        var total_iva = 0.00;
        var total_facturado = 0.00
        var total_bonificacion = 0.00
        var total_descuento = 0.00
        if (this.lst_producto.length < 1) {
            Compra.limpiartotales();
        }

        var total_facturado_producto = 0.00;
        var total_costo_producto = 0.00;
        var total_costo_producto_con_des = 0.00;
        var total_iva_producto = 0.00;
        var total_bonifi_producto = 0.00;
        var total_descuento_producto = 0.00;
        var precio_corriente = 0.00;
        var costo_con_descuento = "";
        var costo_unitario_producto = 0;
        var costo_caja = 0.00;
        $("#imagenultimocosto").html('')

        jQuery.each(Compra.lst_producto, function (j, producto) {
            costo_unitario_producto = 0.00
            costo_con_descuento = 0.00;
            total_costo_producto = 0.00;
            total_costo_producto_con_des = 0.00;
            total_iva_producto = 0.00;
            total_bonifi_producto = 0.00;
            total_descuento_producto = 0.00;
            costo_caja = 0.00;

            bonificacion = parseFloat($("#bonificacion_" + producto.codigo_interno_temp).val());
            descuento = parseFloat($("#descuento_" + producto.codigo_interno_temp).val());
            precio_corriente = parseFloat($("#precio_corriente_" + producto.codigo_interno_temp).val());
            if (isNaN(bonificacion)) {
                bonificacion = 0;
            }

            if (isNaN(descuento)) {
                descuento = 0;
            }

            if (isNaN(precio_corriente)) {
                precio_corriente = 0;
            }

            var costo = 0;
            var total_cantidadesta_unidad = 0;
            var cantidad = 0;

            jQuery.each(Compra.unidades, function (i, value) {

                if ((tipo == "coopidrogas" && i < 1) || tipo == "normal") {

                    cantidad = parseFloat($("#cantidad_" + producto.codigo_interno_temp + "_" + value.id_unidad).val());

                    if (isNaN(cantidad)) {
                        cantidad = 0;
                    }


                    if ($("#costo_" + producto.codigo_interno_temp + "_" + value.id_unidad).val() != undefined) {
                        //estamos en otro proveedor distinto de coopidrogas
                        costo = parseFloat($("#costo_" + producto.codigo_interno_temp + "_" + value.id_unidad).val());

                    } else {
                        ///estamos con coopidrogas, aqui se toma el costo unitario.  Deberia venir al menos con 0
                        costo = parseFloat($("#costo_unitario_" + producto.codigo_interno_temp).val());

                        //si el producto es uno nuevo que estoy creando para asociarlo, siempre el costo es 0
                        if ($("#producto_" + producto.codigo_interno_temp).attr('data-productonuevo') == "asociado") {
                            costo = 0;
                        }
                    }

                    if (isNaN(costo)) {
                        costo = 0;
                    }

                    total_cantidadesta_unidad = 0;

                    //porque siempre j=0 o va a ser 0.00 y debajo va a sumar 0.00 + cada cantidad del arreglo lstproducto
                    if (j != 0) {

                        total_cantidadesta_unidad = $("#total_cantidad_" + value.id_unidad).val();
                    }

                    Compra.lst_producto[j]['unidades'][i]['cantidad'] = cantidad;

                    //con coopidrogas tengo que multiplicar la cantidad por el costo.
                    if (tipo == "coopidrogas") {
                        Compra.lst_producto[j]['unidades'][i]['costo'] = costo * cantidad;
                    } else {
                        Compra.lst_producto[j]['unidades'][i]['costo'] = costo;
                    }

                    var cantidad_a_dividir = 1
                    if (cantidad > 0) {
                        cantidad_a_dividir = cantidad
                    }


                    /*calculo el costo unitario, para cada unidad, pero sirve mas abajo para la caja*/
                    costo_unitario_producto = (Compra.lst_producto[j]['unidades'][i]['costo'] / cantidad_a_dividir)
                    costo_unitario_producto= costo_unitario_producto-((parseFloat(descuento) * costo_unitario_producto) / 100);

                    /*es el total de los costos del producto, con el descuento, si es que tiene*/
                    total_costo_producto_con_des = (parseFloat(total_costo_producto_con_des) +
                        parseFloat(Compra.lst_producto[j]['unidades'][i]['costo'])) - ((parseFloat(descuento) * costo) / 100);



                    /*si la fila del producto sobre la cual estoy escribiendo, es igual al producto que tengo seleccionado,
                     * y estoy pasando sobre la caja, entonces hago el calculo de las flechas*/

                    if (producto.codigo_interno_temp == Compra.selected_codigo_interno_temp && i < 1) {

                        if ($("#iva_" + producto.codigo_interno_temp).val() != "") {

                            costo_unitario_producto = parseFloat(costo_unitario_producto) + parseFloat(Math.round(costo_unitario_producto *
                                ($("#iva_" + producto.codigo_interno_temp).val() / 100)))

                        }

                        /*le coloco el costo unitario visualmente*/

                        $('#costo_unitario_' + producto.codigo_interno_temp).val(parseFloat(costo_unitario_producto).toFixed(2))


                        /*aqui hago lo de las imagenes, comparo el costo unitario que acabo de calcular, contra el que ya tiene en la BD*/
                        if (parseFloat(costo_unitario_producto)
                            > parseFloat(Compra.costo_unitario_default)) {
                            $("#imagenultimocosto").html('<img width="25px" height="25px" src="' + baseurl + 'recursos/img/subida.jpg" >');
                        } else if (parseFloat(costo_unitario_producto)
                            == parseFloat(Compra.costo_unitario_default)) {
                            $("#imagenultimocosto").html('<img width="30px" height="25px" src="' + baseurl + 'recursos/img/igualamarillo.png" >');
                        } else {
                            $("#imagenultimocosto").html('<img width="25px" height="25px" src="' + baseurl + 'recursos/img/bajada.png" >');
                        }
                    }


                    /*este total costo, es el total de los costos del producto sin descuento y sin iva*/
                    total_costo_producto = (parseFloat(total_costo_producto) +
                        parseFloat(Compra.lst_producto[j]['unidades'][i]['costo']));

                    total_costo = parseFloat(total_costo) + parseFloat(Compra.lst_producto[j]['unidades'][i]['costo']);

                    total_cantidadesta_unidad = parseFloat(total_cantidadesta_unidad) + parseFloat(Compra.lst_producto[j]['unidades'][i]['cantidad']);

                    $("#total_cantidad_" + value.id_unidad).val(total_cantidadesta_unidad);

                }
            });

            /*voy sumando los totales descuento + el costo total del producto x con todas sus unidades, y
             el costo total del prducto con descuento, de todas sus unidades*/
            total_descuento = parseFloat(total_descuento) + (parseFloat(total_costo_producto) - parseFloat(total_costo_producto_con_des))

            if ($("#iva_" + producto.codigo_interno_temp).val() != "") {

                total_iva_producto = parseFloat(total_iva_producto) + parseFloat(Math.round(total_costo_producto_con_des *
                    ($("#iva_" + producto.codigo_interno_temp).val() / 100)))

            }

            $("#costo_con_descuento" + producto.codigo_interno_temp).val(total_costo_producto_con_des.toFixed(2));


            total_facturado_producto = parseFloat(total_iva_producto) + parseFloat(total_costo_producto_con_des);

            //seteo el total por producto
            $("#total_" + producto.codigo_interno_temp).val(total_facturado_producto.toFixed(2));

            $("#total_iva_" + producto.codigo_interno_temp).val(total_iva_producto.toFixed(2));

            total_facturado = parseFloat(total_facturado) + parseFloat(total_facturado_producto);

            total_iva = parseFloat(total_iva) + parseFloat(total_iva_producto);

            //por defecto las bonificaciones vienen en 0 si no tiene bonificacion
            if (bonificacion != '0') {
                total_bonificacion = parseFloat(total_bonificacion) + parseFloat((precio_corriente * (bonificacion / 100)) * cantidad);
            }

            Compra.lst_producto[j]['total_iva'] = total_iva_producto;
            Compra.lst_producto[j]['total_producto'] = total_facturado_producto;


        });

        document.getElementById('total_costo').value = parseFloat(total_costo.toFixed(2));
        document.getElementById('total_iva').value = parseFloat(total_iva.toFixed(2));
        document.getElementById('total_facturado').value = parseFloat(total_facturado.toFixed(2));
        document.getElementById('total_descuento').value = parseFloat(total_descuento.toFixed(2));
        console.log('total_descuento', total_descuento)

        if ($("#total_bonificado").val() != undefined) {
            document.getElementById('total_bonificado').value = parseFloat(total_bonificacion.toFixed(2));
        }


    },

    //funcion cada vez ue se hace click sobre el checkbox para colocar los mismos precios de cntado en credito
    contadoacredito: function () {
        if ($("#creditosmismo").is(':checked')) {
            var preciocontado = "";
            var utilidadcontado = "";
            var preciocredito = "";
            var utilidadcredito = "";
            var encontrocontado = false;
            var encontrocredito = false;

            //recorro los  productos
            for (var i = 0; i < Compra.lst_producto.length; i++) {

                preciocontado = "";
                utilidadcontado = "";
                preciocredito = "";
                utilidadcontado = "";
                var idocodigo = Compra.codigo_interno_temp


                //recorro todas las unidades
                jQuery.each(Compra.unidades, function (k, value) {
                    encontrocontado = false;
                    encontrocredito = false;
                    //recorro todas las condiciones de pago
                    jQuery.each(Compra.condiciones_pago, function (j, valor) {


                        if (valor.nombre_condiciones == "CONTADO" || valor.nombre_condiciones == "Contado") {

                            utilidadcontado = $("#utilidad_" + idocodigo + "_" + value.id_unidad + "_" + valor.id_condiciones);
                            preciocontado = $("#precio_valor_" + idocodigo + "_" + value.id_unidad + "_" + valor.id_condiciones);
                            encontrocontado = true;
                            //esto lo hago por si vino CREDITO de primero, guardo los valores de una vez
                            if (encontrocredito == true) {
                                preciocredito.val(preciocontado.val())
                                utilidadcredito.val(utilidadcontado.val())
                            }

                        }

                        if (valor.nombre_condiciones == "CREDITO" || valor.nombre_condiciones == "Credito") {
                            utilidadcredito = $("#utilidad_" + idocodigo + "_" + value.id_unidad + "_" + valor.id_condiciones);
                            preciocredito = $("#precio_valor_" + idocodigo + "_" + value.id_unidad + "_" + valor.id_condiciones);
                            encontrocredito = true;

                            //si ya encontro CONTADO DE PRIMERO,  guardo los valores de una vez
                            if (encontrocontado == true) {

                                preciocredito.val(preciocontado.val())
                                utilidadcredito.val(utilidadcontado.val())

                            }
                        }

                    });
                });
            }
        }

    },
    calcular_precio: function (esto, event, producto_id, codigo, unidad, costounitario, iva, idcondicion, contadorfila) {
        /*esta funcion calcula el precio cuando se escribe sobre cualquier input de "UTILIDAD" */

        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode != 37 && event.keyCode != 38 && event.keyCode != 39 && event.keyCode != 40) {

            if ($(esto).val() != "") {


                if ($("#contenido_in" + Compra.codigo_interno_temp + "_0").val() != "" && $("#contenido_in" + Compra.codigo_interno_temp + "_0").val() != false) {
                    Compra.costo_unitario = $("#costo_unitario_" + Compra.codigo_interno_temp).val();


                    /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el calculo*/
                    if (this.tipo_calculo == "FINANCIERO") {
                        Compra.calcular_financiero("PRECIO", producto_id, codigo, unidad, iva, idcondicion, contadorfila);
                    }
                    if (this.tipo_calculo == "MATEMATICO") {
                        Compra.calcular_matematico("PRECIO", producto_id, codigo, unidad, iva, idcondicion, contadorfila);
                    }
                } else {
                    $(esto).val('');
                    $("#contenido_in" + Compra.codigo_interno_temp + "_0").focus();

                    Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning', true);
                }


            }
        }
    }
    ,
    calcular_utilidad: function (esto, event, producto_id, codigo, unidad, costounitario, iva, idcondicion, contadorfila) {

        //esto es ara que al pasar con las flechas del teclado, no calcule
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode != 37 && event.keyCode != 38 && event.keyCode != 39 && event.keyCode != 40) {

            if ($(esto).val() != "") {
                //idcondicion es si es credito o contado
                /*esta funcion calcula el precio cuando se escribe sobre cualquier input de los "PRECIOS"  (CONTADO,CREDITO,...)*/


                /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el cal            culo*/
                if ($("#contenido_in" + Compra.codigo_interno_temp + "_0").val() != "" && $("#contenido_in" + Compra.codigo_interno_temp + "_0").val() != false) {
                    Compra.costo_unitario = $("#costo_unitario_" + Compra.codigo_interno_temp).val();

                    if (this.tipo_calculo == "FINANCIERO") {
                        Compra.calcular_financiero("UTILIDAD", producto_id, codigo, unidad, iva, idcondicion, contadorfila);
                    }
                    if (this.tipo_calculo == "MATEMATICO") {

                        Compra.calcular_matematico("UTILIDAD", producto_id, codigo, unidad, iva, idcondicion, contadorfila);
                    }
                } else {
                    $(esto).val('');
                    $("#contenido_in" + Compra.codigo_interno_temp + "_0").focus();

                    Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning', true);
                }


            }
        }
    }
    ,
//calcula el costo unitario de cada unidad dependiendo de lo que se haya comprado
    calcularCostoUnitario: function (producto_id, codigo, unidad, contadorfila) {

        var encontroCantidad = true;
        var encontroCosto = true;

        var descuento = parseFloat($("#descuento_" + Compra.codigo_interno_temp).val());

        if ($("#cantidad_" + Compra.codigo_interno_temp + "_" + unidad).val() == undefined
            || $("#cantidad_" + Compra.codigo_interno_temp + "_" + unidad).val() == ""
            || $("#cantidad_" + Compra.codigo_interno_temp + "_" + unidad).val() == false) {
            encontroCantidad = false;
        }
        var costo = 0;
        var cantidad = 0;

        if ($("#costo_" + Compra.codigo_interno_temp + "_" + unidad).val() != undefined) {
            //estamos en otro proveedor distinto de coopidrogas

            if ($("#costo_" + Compra.codigo_interno_temp + "_" + unidad).val() == ""
                || $("#costo_" + Compra.codigo_interno_temp + "_" + unidad).val() == false) {
                encontroCosto = false;
            }
            costo = $("#costo_" + Compra.codigo_interno_temp + "_" + unidad).val()

            cantidad = $("#cantidad_" + Compra.codigo_interno_temp + "_" + unidad).val();
        } else {
            ///estamos con coopidrogas, aqui se toma el costo unitario.  Deberia venir al menos con 0
            costo = parseFloat($("#costo_unitario_" + Compra.codigo_interno_temp).val());
            cantidad = 1; //notese que aqui la cantidad es 1, porque abajo se va a dividir entre la cantidad, si embargo,
            //ya con el costo, se tiene el costo unitario calculado

            if ($("#costo_unitario_" + Compra.codigo_interno_temp).val() == ""
                || $("#costo_unitario_" + Compra.codigo_interno_temp).val() == false) {
                encontroCosto = false;
            }
            //si el producto es uno nuevo que estoy creando para asociarlo, siempre el costo es 0
            if ($("#producto_" + Compra.codigo_interno_temp).attr('data-productonuevo') == "asociado") {
                costo = 0;
            }

        }

        if (isNaN(descuento)) {
            descuento = 0;
        }

        if (encontroCosto != false && encontroCantidad != false) {
            return (costo / cantidad) - ((parseFloat(descuento) * (costo / cantidad)) / 100);
        }

        //si llego aqui es porque, en el precio o utilidad que estoy calculando, no tengo nada en costo y cantidad,
        //es decir no estoy comprando esta unidad, pero le quuero configurar el precio y la utilidad

        var contIntCaja = 0;
        var contIntBlister = 0;
        var contIntUnidad = 0;
        var unidadEscribe = new Array();
        //guardo los contenidos internos de cada unidad

        jQuery.each(Compra.unidades, function (i, value) {

            if (i == 0) {
                contIntCaja = $("#contenido_in" + Compra.codigo_interno_temp + "_" + i).val();
            }

            if (i == 1) {
                contIntBlister = $("#contenido_in" + Compra.codigo_interno_temp + "_" + i).val();
            }

            if (i == 2) {
                contIntUnidad = $("#contenido_in" + Compra.codigo_interno_temp + "_" + i).val();
            }

            if (value.id_unidad == unidad) {
                unidadEscribe = value;
            }

        });


        var caja = 0;
        var blister = 0;
        unidad = 0;

        var arrcorto = new Array();
        var posicion = Compra.existe_producto();
        var cantidad = 0;

        jQuery.each(Compra.lst_producto[posicion]['unidades'], function (i, value) {

            arrcorto = Compra.lst_producto[posicion]['unidades'][i];
            //si consigo algun costo
            if (arrcorto.costo > 0) {

                cantidad = arrcorto.cantidad;
                //pregunto si el costo y la cantidad sobre la que estoy pasando es igual a caja
                if (arrcorto.orden == "1") {

                    //ME QUEDE VALIDANDO PORQUE NO HACIA EL CALCULO DE FORMA CORRECTA CON BLISTER, CUANDO SE ASOCIA A OBSEQUIO
                    if (arrcorto.cantidad_obsequiada != undefined && arrcorto.cantidad_obsequiada > 0) {
                        cantidad = parseInt(cantidad) + parseInt(arrcorto.cantidad_obsequiada)
                    }

                    caja = arrcorto.costo / cantidad;
                    contIntBlister != "" && contIntBlister != 0 ? blister = caja / contIntBlister : blister = 0;
                    contIntUnidad != "" && contIntUnidad != 0 ? unidad = caja / contIntCaja : 0;
                }

                //pregunto si el costo y la cantidad sobre la que estoy pasando es igual a blister
                if (arrcorto.orden == "2") {

                    blister = arrcorto.costo / cantidad;
                    caja = blister * contIntBlister;
                    unidad = blister / contIntUnidad;
                }

                //pregunto si el costo y la cantidad sobre la que estoy pasando es igual a unidad
                if (arrcorto.orden == "3") {

                    unidad = arrcorto.costo / cantidad;
                    blister = contIntUnidad * unidad;
                    caja = unidad * contIntCaja;
                }

            }

        });

        if (contadorfila == 0) {
            return caja - ((parseFloat(descuento) * caja) / 100);

        }

        if (contadorfila == 1) {
            return blister - ((parseFloat(descuento) * blister) / 100);

        }

        if (contadorfila == 2) {
            return unidad - ((parseFloat(descuento) * unidad) / 100);

        }


    }
    ,
    calcular_financiero: function (quecalcular, producto_id, codigo, unidad, iva, idcondicion, contadorfila) {
        //hace el calculo financiero
        //producto_id ya viene calculado si es codigo interno o es el id del producto

        var utilidad = $("#utilidad_" + Compra.codigo_interno_temp + "_" + unidad + "_" + idcondicion);
        var precio = $("#precio_valor_" + Compra.codigo_interno_temp + "_" + unidad + "_" + idcondicion);


        //valido que haya configurado un contenido interno
        if ($("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).val() == ""
            || $("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).val() == false) {
            utilidad.val('');
            precio.val('');
            $("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).focus();
            Utilities.alertModal('<h4>Error</h4> <p>Debe introducir un contenido interno v&aacute;lido</p>', 'warning', true);
            return false;
        }

        //esto es lo que esta costando por unidad (caja o blister o unidad)
        var calculo = Compra.calcularCostoUnitario(producto_id, codigo, unidad, contadorfila);

        /*verifico si hay algun impuesto seleccionado*/
        if (iva != "") {
            calculo = Compra.calcular_impuesto(calculo, iva);
        }

        /*se hacen los calculos dependiendo de lo que se quiera calcular*/
        if (quecalcular == "PRECIO") {

            if (isNaN(utilidad.val())) {

                precio.val('');

                return false;
            }

            if (utilidad.val() != "") {

                var calculo_utilidad = utilidad.val() - 100;
                calculo_utilidad = Math.abs(calculo_utilidad);
                calculo_utilidad = calculo_utilidad / 100;
                calculo = parseFloat(calculo) / parseFloat(calculo_utilidad);
                // calculo = Math.round(calculo);
                if (!isNaN(((calculo / 100) * 100).toFixed(2))) {
                    precio.val(((calculo / 100) * 100).toFixed(2));

                } else {
                    precio.val('')

                }
            } else {
                precio.val('');

            }
        }
        var costo_original = calculo;

        if (quecalcular == "UTILIDAD") {
            /*se hacen los calculos dependiendo de lo que se quiera calcular*/

            if (isNaN(precio.val())) {
                utilidad.val('');
                return false;
            }

            if (precio.val() != "") {
                if (calculo != 0 && calculo != "Infinity") {
                    calculo = (calculo / precio.val()) - 1;
                    calculo = Math.abs(calculo);
                    calculo = (calculo * 100).toFixed(2);

                    if (parseFloat(precio.val()) < parseFloat(costo_original)) {
                        var calculonegativo = "-" + calculo;
                        if (calculonegativo < 0) {
                            calculo = "-" + calculo;
                        }
                    }

                    calculo = parseFloat(calculo).toFixed(2);

                    utilidad.val(calculo);

                } else {
                    utilidad.val('');
                }

            } else {
                utilidad.val('');
            }

        }


        //esto es para saber si esta marcado el check de colocar los mismos precios de contado en el campo de credito
        var credito_id = "";
        var utilidadcredito = "";
        var preciocredito = "";
        if ($("#creditosmismo").is(':checked')) {

            jQuery.each(Compra.condiciones_pago, function (j, valor) {

                if (valor.nombre_condiciones == "CREDITO" || valor.nombre_condiciones == "Credito") {
                    credito_id = valor.id_condiciones;
                }
            })

            if (credito_id != "") {
                utilidadcredito = $("#utilidad_" + Compra.codigo_interno_temp + "_" + unidad + "_" + credito_id);
                preciocredito = $("#precio_valor_" + Compra.codigo_interno_temp + "_" + unidad + "_" + credito_id);

                utilidadcredito.val(utilidad.val())
                preciocredito.val(precio.val())
            }
        }

    }
    ,
    calcular_matematico: function (quecalcular, producto_id, codigo, unidad, iva, idcondicion, contadorfila) {

        var utilidad = $("#utilidad_" + Compra.codigo_interno_temp + "_" + unidad + "_" + idcondicion);
        var precio = $("#precio_valor_" + Compra.codigo_interno_temp + "_" + unidad + "_" + idcondicion);

        //valido que haya configurado un contenido interno
        if ($("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).val() == ""
            || $("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).val() == false) {
            utilidad.val('');
            precio.val('');
            $("#contenido_in" + Compra.codigo_interno_temp + "_" + contadorfila).focus();
            Utilities.alertModal('<h4>Error</h4> <p>Debe introducir un contenido interno v&aacute;lido</p>', 'warning', true);
            return false;
        }

        //esto es lo que esta costando por unidad (caja o blister o unidad)
        var calculo = Compra.calcularCostoUnitario(producto_id, codigo, unidad, contadorfila);

        /*verifico si hay algun impuesto seleccionado*/
        if (iva != "") {
            calculo = Compra.calcular_impuesto(calculo, iva);
        }

        /*se hacen los calculos dependiendo de lo que se quiera calcular*/
        if (quecalcular == "PRECIO") {

            if (isNaN(utilidad.val())) {
                precio.val('');
                return false;
            }
            if (utilidad.val() != "") {

                var calculo_utilidad = (utilidad.val() / 100);

                calculo_utilidad = calculo * calculo_utilidad;
                calculo = parseFloat(calculo) + parseFloat(calculo_utilidad);
                // calculo = Math.round(parseFloat(calculo));
                precio.val(calculo.toFixed(2));

            } else {
                precio.val('');

            }
        }
        /*se hacen los calculos dependiendo de lo que se quiera calcular*/
        if (quecalcular == "UTILIDAD") {

            if (isNaN(precio.val())) {
                utilidad.val('');
                return false;
            }
            if (precio.val() != "") {

                if (calculo != 0 && calculo != "Infinity") {
                    calculo = ((precio.val() * 100) / calculo) - 100;

                    calculo = parseFloat(calculo).toFixed(2);

                    utilidad.val(calculo);

                } else {
                    utilidad.val('');
                }
            } else {
                utilidad.val('');
            }
        }

        //esto es para saber si esta marcado el check de colocar los mismos precios de contado en el campo de credito
        var credito_id = "";
        var utilidadcredito = "";
        var preciocredito = "";
        if ($("#creditosmismo").is(':checked')) {

            jQuery.each(Compra.condiciones_pago, function (j, valor) {

                if (valor.nombre_condiciones == "CREDITO" || valor.nombre_condiciones == "Credito") {
                    credito_id = valor.id_condiciones;
                }
            })

            if (credito_id != "") {
                utilidadcredito = $("#utilidad_" + Compra.codigo_interno_temp + "_" + unidad + "_" + credito_id);
                preciocredito = $("#precio_valor_" + Compra.codigo_interno_temp + "_" + unidad + "_" + credito_id);

                utilidadcredito.val(utilidad.val())
                preciocredito.val(precio.val())
            }
        }

    }
    ,
    contenido_interno: function (esto, contador, producto_id, id_unidad) {

        //unidad
        $("#contenido_in" + Compra.codigo_interno_temp + "_2").attr('readonly', true);

        //es caja
        if (contador == 0) {

            //si es vacio coloco los demas vacio y los coloco readonly
            if ($(esto).val() == "" || $(esto).val() == false) {
                $("#contenido_in" + Compra.codigo_interno_temp + "_1").val('');
                $("#contenido_in" + Compra.codigo_interno_temp + "_2").val('');

                $("#contenido_in" + Compra.codigo_interno_temp + "_1").attr("readonly", true);
                $("#contenido_in" + Compra.codigo_interno_temp + "_2").attr("readonly", true);

                //deshabilito todos los precios y utilidades que no sean caja
                jQuery.each(Compra.condiciones_pago, function (j, valor) {
                    jQuery.each(Compra.unidades, function (i, value) {

                        $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                        $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                        $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);
                        $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);

                    });
                });


            } else if ($(esto).val() < 2) {
                /*si el valor es 1*/

                $("#contenido_in" + Compra.codigo_interno_temp + "_1").val('');
                $("#contenido_in" + Compra.codigo_interno_temp + "_2").val('');

                $("#contenido_in" + Compra.codigo_interno_temp + "_1").attr("readonly", true);

                //deshabilito todos los precios y utilidades que no sean caja
                jQuery.each(Compra.condiciones_pago, function (j, valor) {
                    jQuery.each(Compra.unidades, function (i, value) {

                        if (contador != i) {

                            $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                            $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                            $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);
                            $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);

                            if (Compra.pasotheadcoopidrogas == false) {
                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);

                            }

                        } else {
                            $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", false);
                            $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", false);
                            if (Compra.pasotheadcoopidrogas == false) {
                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", false);
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", false);
                            }
                        }
                    });
                });


            } else {


                $("#contenido_in" + Compra.codigo_interno_temp + "_1").attr("readonly", false);
                //les quito el readonly

                jQuery.each(Compra.condiciones_pago, function (j, valor) {
                    jQuery.each(Compra.unidades, function (i, value) {


                        setTimeout(function () {
                            $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);
                            $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);

                        }, 10);

                        if (Compra.pasotheadcoopidrogas == false) {

                            if (i == 1 && $("#contenido_in" + Compra.codigo_interno_temp + "_1").val() == "" && $("#contenido_in" + Compra.codigo_interno_temp + "_1").val() == false) {
                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);

                            }

                            if (i == 2) {
                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", false);
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", false);
                            }
                        }


                    });
                });


                //pregunto si blister es distinto de vacio para saber si ponerle 1 a la unidad o hacer el calculo de blister
                if ($("#contenido_in" + Compra.codigo_interno_temp + "_1").val() != "" && $("#contenido_in" + Compra.codigo_interno_temp + "_1").val() != false) {


                    //aqui verifico si el monto da decimal, para que no lo muestre
                    if ((($("#contenido_in" + Compra.codigo_interno_temp + "_0").val() / $("#contenido_in" + Compra.codigo_interno_temp + "_1").val()) % 1) != 0) {
                        //si la division entre la caja y el blister da decimal, coloco vacio el blister
                        Utilities.alertModal('<p>La divisi&oacute;n da como resultado un n&uacute;mero decimal</p>', 'warning', true);
                        $("#contenido_in" + Compra.codigo_interno_temp + "_1").val('');
                        $("#contenido_in" + Compra.codigo_interno_temp + "_2").val('1');
                    } else {
                        //sino hago la division
                        $("#contenido_in" + Compra.codigo_interno_temp + "_2").val(parseInt($("#contenido_in" + Compra.codigo_interno_temp + "_0").val()) / parseInt($("#contenido_in" + Compra.codigo_interno_temp + "_1").val()));

                    }

                } else {

                    $("#contenido_in" + Compra.codigo_interno_temp + "_2").val('1');
                }

            }
        }
        //es blister
        if (contador == 1) {


            if ($(esto).val() == "" || $(esto).val() == false || $(esto).val() < 1) {

                /*coloco la unidad en 1 solo si caja tiene algun calor mayor que 1*/
                if ($("#contenido_in" + Compra.codigo_interno_temp + "_0").val() > 1) {
                    $("#contenido_in" + Compra.codigo_interno_temp + "_2").val('1');
                }


                /*limpio todos los precios y utlidades solo de blister*/
                jQuery.each(Compra.condiciones_pago, function (j, valor) {
                    jQuery.each(Compra.unidades, function (i, value) {

                        if (contador == i) {

                            setTimeout(function () {
                                $("#utilidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val('');
                                $("#precio_valor_" + Compra.codigo_interno_temp + "_" + value.id_unidad + "_" + valor.id_condiciones).val('');

                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).val("");
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).val("");
                                $("#cantidad_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);
                                $("#costo_" + Compra.codigo_interno_temp + "_" + value.id_unidad).attr("readonly", true);
                            }, 10);

                        }
                    });
                });


            } else if ($(esto).val() > 0) {

                //aqui valido que el valor en blister no sea igual al valor en caja
                if ($(esto).val() == $("#contenido_in" + Compra.codigo_interno_temp + "_0").val()) {
                    Utilities.alertModal('<h4>No debe ser igual al valor en Caja</h4>', 'warning');
                    $(esto).val(Compra.blister);
                    return false;
                }

                //aqui verifico si el monto da decimal, para que no lo muestre
                if ((($("#contenido_in" + Compra.codigo_interno_temp + "_0").val() / $(esto).val()) % 1) != 0) {

                    $(esto).val(Compra.blister);
                    return false;
                }
                $("#cantidad_" + Compra.codigo_interno_temp + "_" + id_unidad).attr("readonly", false);
                $("#costo_" + Compra.codigo_interno_temp + "_" + id_unidad).attr("readonly", false);

                $("#contenido_in" + Compra.codigo_interno_temp + "_2").attr('readonly', true);
                $("#contenido_in" + Compra.codigo_interno_temp + "_2").val(parseInt($("#contenido_in" + Compra.codigo_interno_temp + "_0").val()) / parseInt($("#contenido_in" + Compra.codigo_interno_temp + "_1").val()));

            }
        }
    }
    ,
    validar_numeropar: function (event, esto, contador) {

        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            if (contador == 0) {
                Compra.caja = $(esto).val();
            }

            if (contador == 1) {
                Compra.blister = $(esto).val();
            }

            if (contador == 1) {
                Compra.unidad = $(esto).val();
            }

            return true;
        }
        if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
            if (contador == 0) {
                Compra.caja = $(esto).val();
            }

            if (contador == 1) {
                Compra.blister = $(esto).val();
            }

            if (contador == 1) {
                Compra.unidad = $(esto).val();
            }

            return true
        }
        else return false;

    }
    ,

    getunidades_has_producto: function (arreglo, unidad, orden) {
        //esta funcion busca una unidad especifica dentro del arreglo de las unidades del producto
        if (arreglo.length > 0) {

            if (arreglo.length > 1) {
                var encontro = false;

                for (var i = 0; i < arreglo.length; i++) {

                    if (orden == false) {
                        if (arreglo[i].id_unidad == unidad) {

                            encontro = arreglo[i];
                        }
                    } else {
                        if (arreglo[i].orden == orden) {

                            encontro = arreglo[i];
                        }
                    }
                }

                return encontro;
            }

            return arreglo[0];
        }

        return false;

    }
    ,
    calcular_impuesto: function (calculo, iva) {
        /**esta funcion retorna el calculo ya realizado de un monto en base al porcentaje del impuesto*/
        var calculo_impuesto = (iva / 100);
        calculo_impuesto = parseFloat(calculo) * parseFloat(calculo_impuesto);
        calculo = parseFloat(calculo) + parseFloat(calculo_impuesto);
        return calculo;
    }
    ,
    pad_with_zeroes: function (number, length) {
        //agregar ceros a la izquierda
        var my_string = '' + number;
        while (my_string.length < length) {
            my_string = '0' + my_string;
        }

        return my_string;

    }
    ,
    procesar_archivo: function () {
        //proceso el archivo coopidrogas
        var formData = new FormData($("#formselect_ruta")[0]);
        var contentType = false;
        Utilities.showPreloader();
        $.ajax({
            url: baseurl + 'ingresos/procesar_arcoopidrogas',
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: contentType,
            processData: false,
            success: function (data) {

                if (data.error) {

                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'warning', true);
                    Utilities.hiddePreloader();
                } else {

                    $("#abrirNuevoProducto").remove();

                    Compra.tipo_carga = "AUTOMATICA";
                    Compra.countproducto = 0;
                    Compra.lst_producto = new Array();
                    //refreshProductos();
                    Compra.limpiartotales();
                    //abro el thead de coopidrogas
                    Compra.thead_coopidrogas();

                    //agrego los datos a la tabla de coopidrogas
                    var coopidrogas = data.coopidrogas;

                    $("#doc_numero").val(data.numero_documento);


                    //recorro el arreglo de productos
                    for (var i = 0; i < coopidrogas.length; i++) {
                        var row = coopidrogas[i];
                        Compra.totable_coopidrogas(row, i);

                        Compra.addProductoToArray(row);
                    }
                    Compra.tablalistacompra.cell(Compra.countproducto, 2).focus();

                    // Compra.tablalistacompra.cell(0, 0).focus();
                    Compra.tablalistacompra.cell(Compra.countproducto, 2).focus();

                    $('.codigo_barra').off('keypress');
                    Compra.inputsearch_barra();


                    Compra.colocardatepicker();

                    Compra.grabarTrEspecial();

                    Compra.calculatotales('coopidrogas');
                    Utilities.hiddePreloader();
                    $("#modal_select_ruta").modal('hide');

                    // $("#modal_proveedor").modal('hide');
                }


            },
            error: function () {

                Utilities.alertModal('<h4>' + data.error + '</h4>', 'warning', true);
            }

        })

    }
    ,
    grabarTrEspecial: function () {
        //declara a los tr que tienenla clase trespecial para buscar la informacion del producto al cual
        //se le hace click
        //data-producto_id aqui siemore va a venir con algo


        $("#tbodyproductos tr").on('click', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-producto_id');
            var codigo = $(this).attr('data-producto_codigo_interno');
            if (codigo == undefined) {
                codigo = $(this).attr('data-producto_codigo_interno');

            }
            var productonuevo = $(this).attr('data-productonuevo');

            Compra.codigo_interno_temp = $(this).attr('data-codigo_interno_temp');
            if (Compra.validar_mismo_trespecial() == true) {
                $("#imagenultimocosto").html('')
                Compra.buscarExistenciaProducto(id, codigo, productonuevo);
                /*el metodo siguiente, hace el calculo para las flechas rojas, o verdes, del costo unitario*/
                Compra.calcularflechas();
            }
        });
    }
    ,
    validar_mismo_trespecial: function () {
        //esta funcion valida si el tr sobre esta el click, es el mismo,
        //para no tener que estar consultando los datos del producto cada vez que se hace click sobre el mismo tr

        if (Compra.selected_codigo_interno_temp == Compra.codigo_interno_temp) {
            return false;
        }
        Compra.selected_codigo_interno_temp = Compra.codigo_interno_temp
        return true;

    }
    ,
    thead_todos: function () {
        //thead de la tabla de productos que tienen en comun los dos tipos de proveedores
        var thead = '';

        //contenido interno dinamico
        for (var i = 0; i < this.unidades.length; i++) {

            thead += '<th style="padding-top: 0px; padding-bottom: 0px">Contenido Interno ' + this.unidades[i]['nombre_unidad'] + '</th>';

        }

        var color = "";
        //utilidad y precio dinamico
        for (var j = 0; j < Compra.condiciones_pago.length; j++) {

            for (var i = 0; i < Compra.unidades.length; i++) {

                if (Compra.condiciones_pago[j].nombre_condiciones == "CONTADO") {
                    color = Compra.colores_contado[i];
                }

                if (Compra.condiciones_pago[j].nombre_condiciones == "CREDITO") {
                    color = Compra.colores_credito[i];
                }

                thead += '<th style="color:white; background-color : ' + color + '" >% UTILIDAD ' + Compra.condiciones_pago[j]['nombre_condiciones'] + ' ' + Compra.unidades[i]['nombre_unidad'] + '</th>';
                thead += '<th style="color:white; background-color : ' + color + '" >PRECIO ' + Compra.condiciones_pago[j]['nombre_condiciones'] + ' ' + Compra.unidades[i]['nombre_unidad'] + '</th>';

            }
        }

        thead += ' <th style="padding-top: 0px; padding-bottom: 0px">C&oacute;digo de barras</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Tipo Producto</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Ubicaci&oacute;n</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Grupo o Nivel</th>' +
            '</tr>';
        return thead;

    }
    ,
    thead_coopidrogas: function () {

        //thead de productos solo para el proveedor coopidrogas
        this.pasotheadcoopidrogas = true;

        $("#open_table_compra").html('');

        $("#open_table_compra").append('<table id="tabla_lista_productos" ' +
            'class="table table-striped dataTable table-bordered table-hover table-featured">' +
            '<thead class="" id="theadtabla_ingresos"> </thead> <tbody class="" id="tbodyproductos"></tbody>' +
            '</table>');

        //Compra.tablalistacompra.destroy();


        var thead = ' <tr>';
        thead += '<th>C&oacute;digo</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px " class="nombre" >Descripci&oacute;n</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">%Descuento</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">%Bonificaci&oacute;n</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Cantidad</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Precio Corriente</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Iva</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Total Iva</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Total</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Costo Unitario</th>';

        var theadtodos = Compra.thead_todos();
        thead += theadtodos;

        $("#theadtabla_ingresos").append(thead);

        Compra.makeTableKey(false);

    }
    ,
    thead_otros: function () {
        this.pasotheadcoopidrogas = false;
        var thead = ' <tr>';
        thead += '<th>Producto</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px;"  class="nombre">Descripci&oacute;n</th>';

        thead += '<th style="padding-top: 0px; padding-bottom: 0px;"  class="nombre">%Descuento</th>';

        //campo cantidad y costo por unidad
        for (var i = 0; i < this.unidades.length; i++) {


            thead += '<th style="padding-top: 0px; padding-bottom: 0px;">Cantidad ' + this.unidades[i]['nombre_unidad'] + '</th>';
            thead += '<th style="padding-top: 0px; padding-bottom: 0px">Costo Total ' + this.unidades[i]['nombre_unidad'] + '</th>';

        }

        thead += '<th style="padding-top: 0px; padding-bottom: 0px">Total con Descuento</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Iva</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Total Iva</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Total</th>' +
            '<th style="padding-top: 0px; padding-bottom: 0px">Costo Unitario</th>';

        var theadtodos = Compra.thead_todos();
        thead += theadtodos;
        $("#theadtabla_ingresos").html('');
        $("#theadtabla_ingresos").append(thead);
    }
    ,
    addproductototablevacio: function () {

        var newrow = {};
        var count = 0;

        newrow[count] = "<td  ><input name='' id='inputsearchproduct' type='text' class='form-control inputsearchproduct' ></td>";
        count++;

        newrow[count] = "<td class='nombre' ><input type='text' readonly class='form-control ' style='width:250px !important'></td>";
        count++;

        newrow[count] = "<td   class='nombre'>" +
            "<input type='text' readonly class='form-control ' ></td>";
        count++;


        for (var i = 0; i < Compra.unidades.length; i++) {

            newrow[count] = "<td ><input onkeydown='' onkeyup='' type='text'   disabled value='0' class='form-control' ></td>";
            count++;

            newrow[count] = "<td  ><input onkeydown=''  onkeyup='' type='text'  disabled value='0' class='form-control' ></td>";
            count++;
        }

        newrow[count] = "<td><input  type='text'  disabled value='' disabled class='form-control' ></td>";
        count++;

        newrow[count] = "<td><input  type='text'  disabled value='0' disabled class='form-control' ></td>";
        count++;
        newrow[count] = "<td><input  type='text'  disabled value='0' disabled class='form-control' ></td>";
        count++;

        newrow[count] = "<td><input  type='text'  disabled value='0' disabled class='form-control' ></td>";
        count++;

        newrow[count] = "<td><input  type='text'  disabled value='0' disabled class='form-control' ></td>";
        count++;

        //LLAMO AL TBODY TANTO PARA COOPIDROGAS Y  OTROS PROVEEDORES;
        var arreglo = new Array();
        arreglo = Compra.tbody_todosvacio(count);

        var tr = new Array();
        tr = Object.assign({}, newrow, arreglo);

        var rowNode = Compra.tablalistacompra.row.add(tr).draw().node();

        $(rowNode).attr("id", 'trvacio');

    }
    ,
    tbody_todosvacio: function (count) {
        var newrow = {};

        //contenido interno
        for (var i = 0; i < this.unidades.length; i++) {


            newrow[count] = "<td ><input type='text'  disabled value='0' class='form-control'></td>";
            count++;
        }

        jQuery.each(Compra.condiciones_pago, function (j, valor) {
            jQuery.each(Compra.unidades, function (i, value) {

                newrow[count] = "<td><input type='text'  disabled class='form-control'  value='0' ></td>";
                count++;

                newrow[count] = "<td><input type='text'  disabled class='form-control' value='0' ></td>";
                count++;
            });
        });

        newrow[count] = "<td><input type='text'  disabled value='0' class='form-control' style='width:150 px !important' ></td>";
        count++;
        newrow[count] = "<td><input type='text'  disabled value='0' class='form-control' ></td>";
        count++;
        newrow[count] = "<td><input type='text'  disabled value='0' class='form-control' ></td>";
        count++;
        newrow[count] = "<td><input type='text'  disabled value='0' class='form-control' ></td>";

        return newrow;

    }
    ,
    guardaringreso: function () {

        var status = "COMPLETADO";
        Compra.accionGuardar(status);
        // Utilities.showPreloader();
        $("#botonconfirmar").prop('disabled', false);
    }
    ,
    ordenarLstParaPrepack: function (in_primero, in_segundo) {
        var temp = new Array();
        //cuando el que viene NO es prepack
        //guardo el siguiente
        temp = Compra.lst_producto[in_segundo];

        //en el siguiente guardo el prepack
        Compra.lst_producto[in_segundo] = Compra.lst_producto[in_primero];

        ///en el actual que es sprepack, guardo el siguiente que no era prepack
        Compra.lst_producto[in_primero] = temp;

    }
    ,
    ordenarListaProducto: function () {
        //ordena los prepack de ultimo, para en el modelo poder consultar a la BD los prductos
        //ya agregads

        var conten = "";

        //esto es para guardar los contenidos internos
        for (var i = 0; i < Compra.lst_producto.length; i++) {

            for (var j = 0; j < Compra.unidades.length; j++) {

                Compra.lst_producto[i]['contenido_interno'][Compra.unidades[j].id_unidad] = "";

                Compra.lst_producto[i]['precios'][Compra.unidades[j].id_unidad] = {};

                conten = $("#contenido_in" + Compra.lst_producto[i]['codigo_interno_temp'] + "_" + j).val();

                //guardo los precios y % de utilidades
                for (var t = 0; t < Compra.condiciones_pago.length; t++) {
                    Compra.lst_producto[i]['precios'][Compra.unidades[j].id_unidad][Compra.condiciones_pago[t].id_condiciones] = {};


                    Compra.lst_producto[i]['precios'][Compra.unidades[j].id_unidad][Compra.condiciones_pago[t].id_condiciones]['utilidad'] =
                        $("#utilidad_" + Compra.lst_producto[i]['codigo_interno_temp'] + "_" + Compra.unidades[j].id_unidad + "_" +
                            Compra.condiciones_pago[t].id_condiciones).val();

                    Compra.lst_producto[i]['precios'][Compra.unidades[j].id_unidad][Compra.condiciones_pago[t].id_condiciones]['precio'] =
                        $("#precio_valor_" + Compra.lst_producto[i]['codigo_interno_temp'] + "_" + Compra.unidades[j].id_unidad + "_" +
                            Compra.condiciones_pago[t].id_condiciones).val();

                }


                if (conten == undefined || conten == "" || conten == 0) {

                    conten = "";
                }

                Compra.lst_producto[i]['contenido_interno'][Compra.unidades[j].id_unidad] = conten;

            }
        }

        var indice = 0;
        for (var i = 0; i < Compra.lst_producto.length; i++) {

            if (Compra.lst_producto[i]['is_prepack'] != undefined && Compra.lst_producto[i]['is_prepack'].length > 0) {

                if (Compra.lst_producto[parseInt(i) + 1] != undefined) {


                    if (Compra.lst_producto[parseInt(i) + 1]['is_prepack'] == undefined ||
                        Compra.lst_producto[parseInt(i) + 1]['is_prepack'].length < 1) {

                        Compra.ordenarLstParaPrepack(i, parseInt(i) + 1);

                    } else {
                        //cuando el que viene SI es prepack
                        indice = i;
                        while (Compra.lst_producto[parseInt(i) + 1] != undefined &&
                        Compra.lst_producto[parseInt(i) + 1]['is_prepack'] != undefined &&
                        Compra.lst_producto[i]['is_prepack'].length > 0) {

                            i++;
                        }

                        if (Compra.lst_producto[parseInt(i) + 1] != undefined) {
                            Compra.ordenarLstParaPrepack(indice, parseInt(i) + 1);
                        }
                        i = indice;

                    }


                } else {

                    return false;

                }


            }

        }


    }
    ,
    accionGuardar: function (status) {
        Utilities.showPreloader();
        Compra.ordenarListaProducto();

        var miJSON = JSON.stringify(Compra.lst_producto);

        console.log('Compra.lst_producto', Compra.lst_producto)
        $.ajax({
            type: 'POST',
            //data: {'lista':miJSON,  'proveedor':proveedor_id, 'form':$('#frmCompra').serialize() },
            data: $('#frmCompra').serialize() + '&lst_producto=' + miJSON + '&proveedor=' + this.proveedor_id +
                '&tipo_carga=' + Compra.tipo_carga + '&status=' + status,
            url: baseurl + 'ingresos/registrar_ingreso',
            dataType: 'json',
            success: function (data) {

                Utilities.hiddePreloader();
                if (data.success) {

                    $("#confirmarmodal").modal('hide');

                    if (data.estatus_devuelto == "PENDIENTE") {
                        Utilities.alertModal('La compra fué guardada con estatus PENDIENTE ' +
                            'puede reanudarla en la opción Consultar Compras con el id ' + data.id, 'warning', 8000);
                    } else {
                        Utilities.alertModal('<h4>Se ha registrado el ingreso</h4> Número de ingreso: ' + data.id, 'success', true);
                    }

                    setTimeout(function () {
                        $.ajax({
                            url: baseurl + 'ingresos',
                            success: function (data2) {

                                $('#page-content').html(data2);
                            }
                        });
                    }, 500);
                }
                else {
                    $("#botonconfirmar").removeClass('disabled');
                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'warning', true);
                }


            },
            error: function (data) {
                Utilities.hiddePreloader();

                Utilities.alertModal('<h4> Ha ocurrido un error al registrar el ingreso</h4>', 'warning', true);


            }
        });
    }
    ,
    validar_codigos_barra: function (guardar, posicion) {
        //este metodo hace las validaciones de los codigos de barra
        //si guardar es false, no esta guardando,
        //recibe la posicion y una variable en tal caso para guardar
        var seguir = true;
        var codigo_de_barra = $("#codigo_barra_originalCompra").val();
        var cont = 0;

        if (guardar == false && codigo_de_barra == "" && codigo_de_barra == false) {
            Utilities.alertModal('<h4>Error</h4><p>Debe ingresar un c&oacute;digo v&aacute;lido</p>', 'warning', true);
            $("#codigo_barra_originalCompra").focus();
            seguir = false;
            return false;
        }

        if (codigo_de_barra != false && codigo_de_barra != "") {
            /*verifico si existe este codigo de barra*/
            var verificar_serv = Compra.validar_existencia_barra(Compra.producto_seleccionadoid, codigo_de_barra)

            if (verificar_serv == true) {
                Utilities.alertModal('<h4>Error</h4><p>El c&oacute;digo de barra ingresado ya existe</p>', 'warning', true);
                $("#codigo_barra_originalCompra").focus();
                seguir = false;
                return false;

            }
        }

        if (posicion != "false") {

            Compra.crearArregloBarra(posicion);
        }

        //si es 1 es que solo esta el input original
        if ($("input[name*=codigos_barracompra]").length < 2 && guardar == true) {

            return true;
        }
        var primera = false;

        $("input[name*=codigos_barracompra]").each(function (i, valor) {
            var valor = $(this);

            /*el 0 es el primer input, que esta al lado del boton anadir, seria la variable codigo_de_barra*/
            if (i != 0) {

                if (codigo_de_barra == valor.val()) {
                    Utilities.alertModal('<h4>Error</h4><p>Ya ha ingresado este c&oacute;digo de barra</p>', 'warning', true);
                    seguir = false;
                    return false;
                }

                //aqui le digo si lo guardo en el arreglo o no
                if (guardar == true) {

                    if (primera == false) {
                        primera = true;
                        Compra.crearArregloBarra(posicion);
                    }
                    Compra.lst_producto[posicion]['codigosBarra'][cont] = valor.val();
                }
                cont++;

            }
        });

        return seguir;
    }
    ,
    guardarCodigosBarra: function () {
        //guarda los codigos de barra para el producto;
        var posicion = Compra.existe_producto(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);
        Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] = true;

        var seguir = true;
        seguir = Compra.validar_codigos_barra(true, posicion);


        if (seguir == true) {
            $("#modal_codigo_barra").modal('hide');
        }
    }
    ,
    agregar_barras: function () {
        /*este metodo es usado al presionar sobre el boton anadir para agregar codigos de barra*/
        if (Compra.producto_seleccionadoid == "") return false;
        var seguir = true;
        var codigo_de_barra = $("#codigo_barra_originalCompra").val();
        /*valido si ya ingrese algun codigo de barra que ya exista, y le indico si debe guardarlos o no*/
        seguir = Compra.validar_codigos_barra(false, "false");

        /*si en ningun momento encontro algun error, relleno los input*/
        if (seguir == true) {
            Compra.rellenar_barras(codigo_de_barra)
        }
    }
    ,
    validar_existencia_barra: function (producto_id, barra) {
        /*este metodo, busca en a BD si existe el codigo de barra enviado*/
        var existe = false;
        $.ajax({
            url: baseurl + 'producto/validar_existencia_barra',
            type: "post",
            dataType: "json",
            async: false,
            data: {'producto_id': producto_id, 'codigo_barra': barra},
            success: function (data) {

                if (data.error == undefined) {

                } else {
                    existe = true;
                }
            },
            error: function () {
                Utilities.alertModal('<h4>Por favor comun&iacute;quese con soporte</h4>', 'warning', true);
            }
        });

        return existe;
    }
    ,
    inputsearch_barra: function () {
        //declara que al epresionar enter sobre la clase codigo_barra,
        //levanta el modal para escribir los codigos de barra
        $(".codigo_barra").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();

                Compra.modalCogidoBarra($(this).attr('data-producto_id'), $(this).attr('data-codigo'));
            }
        });
    }
    ,
    crearArregloBarra: function (posicion) {
        //declara el arreglo para los codigos de barra
        Compra.lst_producto[posicion]['codigosBarra'] = new Array();
    }
    ,
    modalCogidoBarra: function (producto_id, codigo) {
        //levanta el modal donde listo los codigos de barra

        $("#nombreproduto_codigo").html('');
        var posicion = Compra.existe_producto(producto_id, codigo);
        $("#nombreproduto_codigo").html(decodeURIComponent(Compra.lst_producto[posicion]['producto_nombre']));

        var codigocero = "";
        //pregunto si no esta definido para declarar el arreglo en lst_producto
        if (Compra.lst_producto[posicion]['codigosBarra'] == undefined ||
            (Compra.lst_producto[posicion]['codigosBarra'] != undefined && Compra.lst_producto[posicion]['codigosBarra'].length < 1)) {

            Compra.crearArregloBarra(posicion);

        } else {
            codigocero = Compra.lst_producto[posicion]['codigosBarra'][0];
        }
        //codigocero codigo cero seria el primer codigo de barra en el arreglo


        //es porque es un producto ya guardado en la bd, le busco sus codigos de barra
        if (producto_id != "" &&
            Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] == undefined) {
            //Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] es un booleano que se crea al guardar los codigos de barra
            //para saber si busco en la db o no
            var buscarBarras = ProductoService.getCodigosBarra(producto_id);

            buscarBarras.success(function (data) {

                var barras = data.barras;
                var desde = 0;
                //esto es para saber desde donde empezar a llenar el arreglo, para los productos que traen codigos
                //desde el archivo
                if (codigocero != "") {
                    desde = 1;
                }

                var cont = 0;
                for (var i = desde; i < parseInt(barras.length) + parseInt(desde); i++) {

                    Compra.lst_producto[posicion]['codigosBarra'][i] = barras[cont]['codigo_barra'];
                    cont++;
                }

                Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] = true;

            }).error(function () {
                Utilities.alertModal('<h4>Ha ocurrido un error al buscar los c&oacute;digos de barras</h4>', 'warning', true);

            })
        }

        for (var i = 0; i < Compra.lst_producto[posicion]['codigosBarra'].length; i++) {
            /*este metodo rellena los input con los codigos de barra*/
            Compra.rellenar_barras(Compra.lst_producto[posicion]['codigosBarra'][i]);
        }

        $("#modal_codigo_barra").modal('show');
        $("#codigo_barra_originalCompra").focus();

    }
    ,
    rellenar_barras: function (codigo_de_barra) {
        /*este metodo rellena los input con los codigos de barra*/
        var div = '  <div class="form-group " id="div_barra' + Compra.contador_barra + '" ><div class="col-md-3 "></div><div class="col-md-7">' +
            '<input type="text" name="codigos_barracompra[]"  readonly class="form-control" placeholder=""  value="' + codigo_de_barra + '"/> </div>' +
            '<div class="col-md-2">' +
            '<button class="fa fa-trash-o btn-default delete_barra" title="Eliminar" onclick="Compra.delete_barra(' + Compra.contador_barra + ')" aria-hidden="true"' +
            ' value="' + Compra.contador_barra + '" ></button>' +
            '</div>' +
            ' </div>'
        //col-md-offset-9
        $("#abrir_codigos_barra").append(div);
        $("#codigo_barra_originalCompra").val('');
        $("#codigo_barra_originalCompra").focus();
        Compra.contador_barra++;
    }
    ,
    delete_barra: function (contador) {
        /*esto es para eliminarlos dinamicamente*/
        $("#div_barra" + contador).remove();
    }
    ,
    generar_reporte_excel: function () {
        document.getElementById("frmExcel").submit();
    }
    ,

    generar_reporte_pdf: function () {
        document.getElementById("frmPDF").submit();
    }
    ,
    refreshProductos: function () {

        $("#frmCompra")[0].reset();
    }
    ,

    addCompraEnProducto: function () {
        //creo un input para saber que voy a guardar el producto desde la compra
        $("#id").after('<input type="hidden" name="estasencompra" id="estasencompra" value="1">');
        $("#btnConsulProdCoo").removeAttr('onclick');
        $("#btnConsulProdCoo").attr('onclick', 'Compra.ver_catalogo()');
        //$("#btnConsulProdCoo").remove();
    }
    ,
    armarSelecPrepack: function () {
        //arma el select donde se lista todos los productos, para descomponen el prepack

        $("#selectProductosPrepacks").html('');
        $("#selectProductosPrepacks").trigger("chosen:updated");
        var buscar = ProductoService.getSoloProductos();
        buscar.success(function (data) {

            var html = '<option value="">Seleccione</option>';
            jQuery.each(data.productos, function (i, value) {
                html += '<option value="' + value.producto_id + '"' +
                    'data-producto_id="' + value.producto_id + '" ' +
                    'data-codigo="' + value.producto_codigo_interno + '"> ' +
                    '' + value.producto_codigo_interno + ' - ' + value.producto_nombre + '</option>';
            });

            $("#selectProductosPrepacks").html(html);
            $("#selectProductosPrepacks").trigger("chosen:open");
            $("#selectProductosPrepacks").trigger("chosen:updated");

        }).error(function () {
            Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
        });
    }
    ,
//arma el tr donde se abre el obsequio
    armarObsequio: function (posicion) {

        var row = Compra.lst_producto[posicion]['is_obsequio'][0];
        var tr = '<tr>';

        tr += '<td>' + row.codigo_interno + '</td>' +
            '<td>' + row.nombre + '</td>' +
            '<td>' + row.cantidad_obsequio + '</td>' +
            '<td>' + row.costo + '</td>' +
            '<td><a href="#" onclick="Compra.eliminar_obsequio(' + posicion + ')" ' + 'style="width: 200px; margin: 0;" class="btn btn-danger"><i ' +
            'class="fa fa-trash-o"></i> Eliminar</a><td/>' +
            '</tr>';

        return tr;
    }
    ,
//cuando se presiona sobre el boton radio button en la lista de productos
    validaObsePrepack: function (esto) {

        //verifica si el producto que se selecciona el radio buton, va a ser asociado como un obsequio
        //o como un prepack

        Compra.codigo_interno_temp = $(esto).attr('data-codigo_interno_temp');
        var producto_id = $(esto).attr('data-producto_id');
        var codigo = $(esto).attr('data-codigo');
        //busco la posicion del producto prepack en lst_producto
        var posicion = Compra.existe_producto();

        if (posicion != 'false') {

            Compra.obse_o_prepack_selec = Compra.codigo_interno_temp;

            //valido si el producto ya fue asociado a un obsequio, para no volver a mostrar el modal de seleccion
            //pregunto si ya tiene un producto asociado como obsequio
            if (Compra.lst_producto[posicion]['is_obsequio'] != undefined &&
                Compra.lst_producto[posicion]['is_obsequio'].length > 0) {

                $("#tbody_productos_obsequios").html('');
                $("#tbody_productos_obsequios").append(Compra.armarObsequio(posicion));
                $("#modal_obsequios").modal('show');
                Utilities.alertModal('<h4>Este Producto es un obsequio!</h4>', 'warning', true);
                return false;
            }
            //si es prepack
            if (Compra.lst_producto[posicion]['is_prepack'] != undefined &&
                Compra.lst_producto[posicion]['is_prepack'].length > 0) {

                Utilities.alertModal('<h4>Este Producto es un Prepack!</h4>', 'warning', true);
                Compra.prepack();
                return false;
            }

            //si no es ni prepack ni obsequio muestro el modal para seleccionar
            $("#confirmTipoProducto").modal('show');
        }
    }
    ,
    prepack: function () {
        //cuando se confirma que el producto es un prepack
        Compra.monto_prepack = $("#total_" + Compra.obse_o_prepack_selec).val();

        Compra.restante_prepack = 0;
        Compra.productoAgDesde = "prepack";
        /*digo que no se esta agregando el producto por obsequio sino por prepack  */
        Compra.lstProductosPrepack = new Array();
        /*limpio el arreglo que tendra los productos que lo componen*/

        $("#confirmTipoProducto").modal('hide');
        $("#tbody_productos_prepack").html('');
        $("#monto_prepack").html('');
        $("#monto_prepack").html(Compra.monto_prepack);

        //busco la posicion del producto prepack en lst_producto
        var posicion = Compra.existe_producto();

        //pregunto si ya el producto esta definido como un prepack
        if (Compra.lst_producto[posicion]['is_prepack'] != undefined && Compra.lst_producto[posicion]['is_prepack'].length > 0) {

            for (var i = 0; i < Compra.lst_producto[posicion]['is_prepack'].length; i++) {

                Compra.arrProductosPrepack(Compra.lst_producto[posicion]['is_prepack'][i].producto_id,
                    Compra.lst_producto[posicion]['is_prepack'][i].producto_nombre,
                    Compra.lst_producto[posicion]['is_prepack'][i].producto_codigo,
                    Compra.lst_producto[posicion]['is_prepack'][i].cantidad,
                    Compra.lst_producto[posicion]['is_prepack'][i].costo);

                Compra.restante_prepack = parseFloat(Compra.lst_producto[posicion]['is_prepack'][i].costo)
                    + parseFloat(Compra.restante_prepack);

            }
        }

        Utilities.showPreloader();
        Compra.armarSelecPrepack();
        $("#restante_prepack").html(Compra.restante_prepack);
        $("#ver_catalogo_from_prepack").remove();

        $("#footer_modal_prepack").append('<div class="text-left" id="ver_catalogo_from_prepack"><a  href="#" class="btn btn-primary"' +
            ' style="text-align: left" onclick="Compra.ver_catalogo()" >Cat&aacute;logo Principal</a></div>');

        Utilities.hiddePreloader();
        $("#modal_prepacks").modal('show');
    }
    ,
    enviarProdToPrepack: function () {
        /*cuando se presiona el boton agregar, para agregarlo a la tabla de productos que componen  el prepack*/
        var producto_id = $("#selectProductosPrepacks").val();
        var producto_nombre = $("#selectProductosPrepacks option:selected").html();
        var producto_codigo = $("#selectProductosPrepacks option:selected").attr('data-codigo');

        //cada vez que agrego un producto
        Compra.arrProductosPrepack(producto_id, producto_nombre, producto_codigo, '', '');
    }
    ,
    arrProductosPrepack: function (producto_id, producto_nombre, producto_codigo, cantidad, costo) {

        //crea el arreglo para el producto seleccionado en el modal
        Compra.lstProductosPrepack[Compra.oproducto_o_codigo(producto_id, producto_codigo)] = new Array();
        Compra.lstProductosPrepack[Compra.oproducto_o_codigo(producto_id, producto_codigo)]['producto_id'] = producto_id;
        Compra.lstProductosPrepack[Compra.oproducto_o_codigo(producto_id, producto_codigo)]['producto_codigo'] = producto_codigo;
        Compra.addPrepack(producto_id, producto_nombre, producto_codigo, cantidad, costo);
    }
    ,
    addPrepack: function (producto_id, producto_nombre, producto_codigo, cantidad, costo) {
        //anade los productos que componen el prepack al tbody donde se listan

        if (producto_id != '') {

            if ($("#tr_prepack" + producto_id).length == 0) {
                var unidades_has_prod = UnidadesService.getSoloUnidadesByProd(producto_id);

                unidades_has_prod.success(function (data) {

                    //si consiguio unidades asociadas
                    if (data.length > 0) {

                        //guardo las unidades de este producto para usarlo cuando se vaya a guardar, y valido
                        Compra.unidades_productos[Compra.oproducto_o_codigo(producto_id, producto_codigo)] = data;

                        var tr = '<tr id="tr_prepack' + producto_id + '">' +
                            '<td>' + Compra.datovalido(producto_id, producto_codigo) + '</td>' +
                            '<td id="tbodyNombrePrepDesc_' + Compra.oproducto_o_codigo(producto_id, producto_codigo) + '">' + producto_nombre + '</td>';
                        var readonly = '';


                        jQuery.each(Compra.unidades, function (i, value) {

                            //solo cajas
                            if (i == 0) {

                                var tienelauniad = false;
                                jQuery.each(data, function (j, unidad) {
                                    if (parseFloat(unidad.id_unidad) == parseFloat(value.id_unidad)) {
                                        tienelauniad = true;
                                    }
                                });
                                readonly = '';

                                if (tienelauniad == false) {
                                    readonly = 'readonly';
                                }

                                tr += '<td><input ' + readonly + ' onkeydown="return soloNumeros(event)"' +
                                    'name="cantidad_pre_' + producto_id + '[]" ' +
                                    'class="form-control" data-unidad="' + value.id_unidad + '"' +
                                    'data-producto_id="' + producto_id + '" ' +
                                    'data-producto_codigo="' + producto_codigo + '" ' +
                                    'value="' + cantidad + '" ' +
                                    '></td>';

                                tr += '<td><input ' + readonly + ' ' +
                                    ' value="' + costo + '" ' +
                                    'onkeydown="return soloDecimal(this, event)" ' +
                                    ' onkeyup="return Compra.validarCostoPrepack()" ' +
                                    ' name="costo_pre_' + producto_id + '[]" ' +
                                    ' class="form-control" data-unidad="' + value.id_unidad + '" ' +
                                    ' data-producto_id="' + producto_id + '" ' +
                                    ' id="costo_pre_' + producto_id + '_' + value.id_unidad + '" ' +
                                    ' data-producto_codigo="' + producto_codigo + '" ></td>';

                                tr += '<td><button class="btn btn-success" onclick="Compra.deleteProdPrepack(' + producto_id + ')"><i class="fa fa-trash"></i></button></td>';
                                tr += '</tr>';
                            }
                        });

                        $("#tbody_productos_prepack").append(tr);

                    } else {
                        Utilities.alertModal('<h4>Error</h4> <p>Este producto no tiene configurado ninguna unidad de medida</p>', 'warning', true);
                    }
                });

            } else {
                Utilities.alertModal('<h4>Error</h4> <p>Este producto ya ha sido agregado</p>', 'warning', true);
            }
        } else {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar un producto</p>', 'warning', true);
        }
    }
    ,
    validarDescomPrepack: function () {
        //valido cuando presiono el boton de confirmar al descomponer prepack

        if ($("#tbody_productos_prepack tr").length < 1) {

            //le digo que ya no es un prepack
            //busco la posicion del producto prepack en lst_producto
            var posicion = Compra.existe_producto();
            Compra.lst_producto[posicion]['is_prepack'] = new Array();

            $("#producto_" + Compra.obse_o_prepack_selec).removeAttr("data-productoPrepack");


            if ($("#producto_" + Compra.obse_o_prepack_selec).attr("data-productonuevo") == true
                || $("#producto_" + Compra.obse_o_prepack_selec).attr("data-productonuevo") == 'true') {
                setTimeout(function () {

                    $("#producto_" + Compra.obse_o_prepack_selec).css('color', 'rgb(216, 69, 69) !important')
                }, 200)
            }

            if ($("#producto_" + Compra.obse_o_prepack_selec).attr("data-productonuevo") == false
                || $("#producto_" + Compra.obse_o_prepack_selec).attr("data-productonuevo") == 'false') {
                setTimeout(function () {
                    $("#producto_" + Compra.obse_o_prepack_selec).css('color', '#797979  !important');
                }, 200)
            }

            $("#modal_prepacks").modal('hide');
            return false;
        }

        Compra.validarCostoPrepack();
        if ($("#confirmarGuardarPrepack").is('[disabled=disabled]')) {
            return false;
        }
        Compra.guardarTablaPrepack();
    },

    validarCostoPrepack: function () {
        //aqui entra cuando se presiona guardar, y cada vez que se escribe sobre el input de costos,
        //en el modal del prepack, para saber si es mayor o mrnor o igual, la suma de los costos

        var valor = 0.00;
        $("#restante_prepack").css("color", "black");

        $("#tbody_productos_prepack tr td input[name^='costo_pre_']").each(function (i, fila) {
            var esto = $(this)
            if (esto.val() != "") {
                valor = parseFloat(valor) + parseFloat(esto.val());
                if (parseFloat(valor) > parseFloat(Compra.monto_prepack)) {

                    $("#restante_prepack").css("color", "red");
                    $("#confirmarGuardarPrepack").prop('disabled', true);

                } else if (parseFloat(valor) < parseFloat(Compra.monto_prepack)) {
                    $("#restante_prepack").css("color", "red");
                    $("#confirmarGuardarPrepack").prop('disabled', true);

                } else {
                    $("#confirmarGuardarPrepack").prop('disabled', false);
                }

            }

        });

        $("#restante_prepack").html(valor);
    }
    ,
    deleteProdPrepack: function (id) {

        $("#tr_prepack" + id).remove();
        Compra.validarCostoPrepack();
        return false;
    }
    ,

    guardarPrepack: function (posicion, producto_nombre, producto_id, producto_codigo, unidad_id, cantidad, costo) {

        //arma los datos del prepack al arreglo lst_producto
        var datosComponen = {};
        datosComponen.producto_nombre = producto_nombre;
        datosComponen.producto_id = producto_id;
        datosComponen.producto_codigo = producto_codigo;
        datosComponen.unidad_id = unidad_id;
        datosComponen.cantidad = cantidad;
        datosComponen.costo = costo;
        datosComponen.is_prepack = true;

        if (posicion != 'false') {
            Compra.lst_producto[posicion]['is_prepack'].push(datosComponen);
        }
        return datosComponen;
    }
    ,
//recorre los tr en donde cree los productos que componen el prepack
//para guardarlos
    guardarTablaPrepack: function () {

        var misma_unidad = false;

        //busco la posicion del producto prepack en lst_producto
        //ya en memoria se tiene el prepack seleccionado en la tabla principal
        var posicion = Compra.existe_producto();

        //defino que es un prepack
        Compra.lst_producto[posicion]['is_prepack'] = new Array();
        var arrPrepack = [];

        var error = false;
        var cantidad = "";
        var costo = "";
        var valor = 0;
        //recorro los input de cantidad
        jQuery.each($("#tbody_productos_prepack tr td input[name^='cantidad_pre_']"), function () {

            cantidad = $(this).val()
            costo = $("#costo_pre_" + $(this).attr('data-producto_id') + "_" + $(this).attr('data-unidad')).val();
            if (cantidad != "" && cantidad > 0) {

                if (costo != "" && costo > 0) {
                    //////////
                    $("#restante_prepack").css("color", "black");

                    valor = parseFloat(valor) + parseFloat(costo);

                    $("#confirmarGuardarPrepack").prop('disabled', false);

                    //valido si existe este producto en la tabla de los productos que componen el prepack
                    if (Compra.lstProductosPrepack[Compra.oproducto_o_codigo($(this).attr('data-producto_id'),
                        $(this).attr('data-producto_codigo'))] != undefined) {

                        //almaceno en una variable mas pequena, las unidades de este producto
                        var mis_unidades = Compra.unidades_productos[Compra.oproducto_o_codigo($(this).attr('data-producto_id'),
                            $(this).attr('data-producto_codigo'))];


                        misma_unidad = false;

                        //busco las unidades de este producto que fue agregado a la tabla
                        if (mis_unidades != undefined) {

                            for (var i = 0; i < mis_unidades.length; i++) {

                                //valido si la data-unidad que viene del input son los mismoa valores de la unidad que tiene creada
                                //el producto
                                if ($(this).attr('data-unidad') == mis_unidades[i]['id_unidad']) {

                                    misma_unidad = true;

                                }
                            }
                        }

                        if (misma_unidad == true) {
                            var datosComponen = {};

                            datosComponen = Compra.guardarPrepack('false', $("#tbodyNombrePrepDesc_" + Compra.oproducto_o_codigo($(this).attr('data-producto_id'),
                                $(this).attr('data-producto_codigo'))).html(), $(this).attr('data-producto_id'),
                                $(this).attr('data-producto_codigo'), $(this).attr('data-unidad'), cantidad,
                                $("#costo_pre_" + $(this).attr('data-producto_id') + "_" + $(this).attr('data-unidad')).val()
                            );

                            arrPrepack.push(datosComponen);
                        }
                    }
                } else {

                    Utilities.alertModal('<h4>Debe ingresar al menos un costo!</h4>', 'warning', true);
                    $(this).focus();
                    error = true;
                    return false;
                }
            } else {

                Utilities.alertModal('<h4>Debe ingresar al menos una cantidad!</h4>', 'warning', true);
                $(this).focus();
                error = true;
                return false;
            }

        });

        if (valor > Compra.monto_prepack) {

            Utilities.alertModal('<h4>La suma de todos los costos no debe ser mayor al costo del Prepack!</h4>', 'warning', true);

            $("#confirmarGuardarPrepack").prop('disabled', true);
            error = true;

        } else if (valor < Compra.monto_prepack) {
            Utilities.alertModal('<h4>La suma de todos los costos no debe ser menor al costo del Prepack!</h4>', 'warning', true);

            $("#confirmarGuardarPrepack").prop('disabled', true);
            error = true;
        }

        if (error == false) {

            $("#producto_" + Compra.obse_o_prepack_selec).attr("data-productoPrepack", '1');

            Compra.lst_producto[posicion]['is_prepack'] = arrPrepack;

            $("#producto_" + Compra.obse_o_prepack_selec).css('color', '#71da71')
            $("#modal_prepacks").modal('hide');

        }


    }
    ,
    guardarCanBodega: function (esto, unidad_id) {

        if ($("#selectbodegas option:selected").val() != "") {
            Compra.guardarCantidadBodega(
                Compra.existe_producto(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo),
                $("#selectbodegas option:selected").val(), unidad_id);
        }

    }
    ,
    modalBodegas: function () {

        var posicion = Compra.existe_producto(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);


        $("#nombreproducto_bodega").html('');

        if (posicion != "false") {

            if (Compra.lst_producto[posicion]['is_obsequio']) {
                Utilities.alertModal('<h4>Debe seleccionar el producto al cual fu&eacute; asociado!</h4>', 'warning', true);
                return false;
            }

            $("#nombreproducto_bodega").html(decodeURIComponent(Compra.lst_producto[posicion]['producto_nombre']));

            $("#thead_table_bodegas").html('');
            $("#tbody_table_bodegas").html('');
            var thead = '<thead><tr>';
            var tbody = '<tbody><tr>';
            jQuery.each(Compra.unidades, function (j, value) {

                if ((Compra.pasotheadcoopidrogas == false) || (Compra.pasotheadcoopidrogas == true && j < 1)) {

                    thead += '<th>' + value.nombre_unidad + '</th>';
                    tbody += '<td ><input class="form-control" name="cantidad_bodegas_' + value.id_unidad + '[]" ' +
                        'onkeydown="return soloNumeros(event)" ' +
                        'onkeyup="Compra.guardarCanBodega(this,' + value.id_unidad + ')" ' +
                        ' data-unidad_id="' + value.id_unidad + '" ' +
                        'id="cantidad_bodegas_' + value.id_unidad + '" type="text" /></td>';
                }
            });
            thead += '</tr></thead>';
            tbody += '</tr></tbody>';

            $("#modal_compra_bodegas").modal('show');
            $("#thead_table_bodegas").append(thead);
            $("#tbody_table_bodegas").append(tbody);
            $("#selectbodegas").val($("#selectbodegas option:first").val());
            $("#selectbodegas").trigger("chosen:updated");
            setTimeout(function () {
                $("#cantidad_bodegas_" + Compra.unidades[0].id).focus();
            }, 500)
            Compra.validarBodegacantidad(posicion, $("#selectbodegas option:first").val());
        } else {
            Utilities.alertModal('<h4>Debe seleccionar un producto!</h4>', 'warning', true);
            return false;
        }

    }
    ,
    validarBodegacantidad: function (posicion, local) {
        //NO SE USA
        //valido si este producto ya se le creo el array temporal
        // y si tiene guardada alguna unidad con su cantidad, para rellenar los input
        var tienecantidad = false;
        var producto = Compra.oproducto_o_codigo(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);
        if (Compra.arrayCantBodega[producto] != undefined) {

            if (Compra.arrayCantBodega[producto][local] != undefined) {
                jQuery.each(Compra.arrayCantBodega[producto][local], function (i, unidad) {

                    if (unidad.cantidad != 0 && unidad.cantidad != "") {
                        tienecantidad = true;
                        $("#cantidad_bodegas_" + i).val(unidad.cantidad)
                    }
                });
            }
        }
        return tienecantidad;
    }
    ,
    guardarCantidadBodega: function (posicion, local, unidadenviada) { //guarda las cantidades del modal de bodegas, en el array temporal
        //NO SE USA
        var existeunidad = false;
        var valor = null;
        var total_esta_unidad = 0;
        if (unidadenviada == undefined) {
            unidadenviada = "";
        }
        //unidadenviada es !=undefined cuando se cambia el select de bodegas,
        //solo viene con valor cuando se escribe sobre el input, para solo recorrer ese input

        var producto = Compra.oproducto_o_codigo(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);
        var seguir = true;
        var cantNormal = "";
        $("#tbody_table_bodegas input[name*=cantidad_bodegas_" + unidadenviada + "]").each(function (i, tal) {
            existeunidad = false;
            valor = $(this);
            total_esta_unidad = 0;
            if (valor.val() != 0 && valor.val() != "") {

                //declaro el array temporal para este producto
                if (Compra.arrayCantBodega[producto] == undefined) {
                    Compra.arrayCantBodega[producto] = {};
                }

                if (Compra.arrayCantBodega[producto][local] == undefined) {
                    Compra.arrayCantBodega[producto][local] = {};
                }


                //busco la cantidad de unidades que he colocado en este modal, para esta unidad
                jQuery.each(Compra.arrayCantBodega[producto], function (id_local, localarray) {

                    jQuery.each(localarray, function (unidad_id, unidad) {

                        //busco solo la cantidad para esta unidad, en este local, para este producto, que sea distinto del local actual
                        if (unidad.cantidad != 0 && unidad.cantidad != "" && valor.attr('data-unidad_id') == unidad_id
                            && id_local != local) {
                            total_esta_unidad = parseInt(total_esta_unidad) + parseInt(unidad.cantidad)
                        }
                    });
                });


                seguir = true;

                if (unidadenviada != "") {

                    //busco la cantidad que esta en la tabla principal para esta unidad
                    for (var m = 0; m < Compra.lst_producto[posicion]['unidades'].length; m++) {

                        if (Compra.lst_producto[posicion]['unidades'][m]['id'] == valor.attr('data-unidad_id')) {
                            cantNormal = 0;

                            if (Compra.lst_producto[posicion]['unidades'][m]['cantidad_obsequiada'] != undefined &&
                                Compra.lst_producto[posicion]['unidades'][m]['cantidad_obsequiada'] != undefined != "" &&
                                Compra.lst_producto[posicion]['unidades'][m]['cantidad_obsequiada'] != 0) {
                                cantNormal = Compra.lst_producto[posicion]['unidades'][m]['cantidad_obsequiada']
                            }

                            //pregunto si todo de este modal es mayor al valor que esta en la tabla principal
                            if ((parseInt(valor.val()) + parseInt(total_esta_unidad)) >
                                (parseInt(Compra.lst_producto[posicion]['unidades'][m]['cantidad']) + parseInt(cantNormal))) {
                                Utilities.alertModal('<h4>Han ingresado una cantidad mayor a la configurada !</h4>', 'warning', true);
                                seguir = false;
                                break;
                            }
                        }
                    }
                }

                //si es menor, entonces lo guardo en el arreglo temporal
                if (seguir == true) {

                    //valido que exista la unidad
                    jQuery.each(Compra.unidades, function (j, value) {

                        if (value.id_unidad == valor.attr('data-unidad_id')) {
                            existeunidad = true;
                        }
                    });

                    if (existeunidad == true) {

                        Compra.arrayCantBodega[producto][local][valor.attr('data-unidad_id')] = {};
                        Compra.arrayCantBodega[producto][local][valor.attr('data-unidad_id')]['cantidad'] = valor.val();

                    } else {
                        Utilities.alertModal('<h4>Esta unidad no existe !</h4>', 'warning', true);
                        return false;
                    }
                } else {
                    //si es mayor, entonces le quito el ultimo numero que escribio
                    var talvalor = valor.val();
                    return valor.val(talvalor.substr(0, talvalor.length - 1));
                }
            } else {

                if (unidadenviada != "") {
                    delete Compra.arrayCantBodega[producto][local][unidadenviada];
                }

                if (Compra.tamanoArray(Compra.arrayCantBodega[producto][local]) < 1) {
                    delete Compra.arrayCantBodega[producto][local];
                }

                if (Compra.tamanoArray(Compra.arrayCantBodega[producto]) < 1) {
                    delete Compra.arrayCantBodega[producto];
                }


            }
        });
    }
    ,

    tamanoArray: function (obj) {  //retorna el tamano del arreglo o objeto
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    }
    ,
    guardarArrBodegas: function () {  //guarda las cantidades del modal de bodegas
        // NO SE USA
        var posicion = Compra.existe_producto(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);
        if (Compra.lst_producto[posicion]['canBodegas'] == undefined) {
            Compra.lst_producto[posicion]['canBodegas'] = {};
        }
        var producto = Compra.oproducto_o_codigo(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo);

        if (Compra.arrayCantBodega[producto] == undefined) {
            Utilities.alertModal('<h4>Debe ingresar al menos una cantidad !</h4>', 'warning', true);
            return false;
        }
        //Compra.arrayCantBodega es el array temporal donde guardo las cantidades
        Compra.lst_producto[posicion]['canBodegas'] = Compra.arrayCantBodega[producto];

        $("#modal_compra_bodegas").modal('hide');
    }
    ,

    limpiarcantBodegas: function (producto) {  //resetea los valores de las cantidades para este producto

        $("#tbody_table_bodegas input[name*=cantidad_bodegas_]").val('');
        setTimeout(function () {

            $("#cantidad_bodegas_" + Compra.unidades[0].id).focus();
        }, 500)
        var existe = false;
        if (Compra.arrayCantBodega[producto] != undefined) {
            existe = true;
        }

        delete Compra.arrayCantBodega[producto];
        return existe;
    }
    ,

    preguntarAntesDeSalir: function () {

        var retorno = this.validaContenidoInterno();
        if (retorno == true) {
            var status = Compra.ingresoPendiente;
            if (Compra.lst_producto.length > 0 &&
                (Object.keys(Compra.ingresoCompleto).length < 1 ||
                    (Object.keys(Compra.ingresoCompleto).length > 0 && Compra.ingresoCompleto.ingreso_status == Compra.ingresoPendiente))) {
                Compra.accionGuardar(status);
            } else {
                Utilities.alertModal('<h4>Debe ingresar al menos un producto &oacute; esta compra no puede cambiarse a Pendiente!</h4>', 'warning', true);
                return false;
            }
        }
    },

    calcularflechas: function () {
        /*cada vez que se le da click a una fila d eun producto, se busca el costo unitario y se compara contra lo que esta comprando
         * para saber si le esta ganando o perdiendo*/
        var costo_unitario_producto = 0;
        jQuery.each(Compra.lst_producto, function (j, producto) {
            if (producto.codigo_interno_temp == Compra.selected_codigo_interno_temp) {

                var descuento = parseFloat($("#descuento_" + producto.codigo_interno_temp).val());
                if (isNaN(descuento)) {
                    descuento = 0;
                }
                var costo = 0;
                var cantidad = 0;
                costo_unitario_producto = 0;
                jQuery.each(Compra.unidades, function (i, value) {

                    if (i < 1) {

                        cantidad = parseFloat($("#cantidad_" + producto.codigo_interno_temp + "_" + value.id_unidad).val());

                        if (isNaN(cantidad)) {
                            cantidad = 0;
                        }


                        if ($("#costo_" + producto.codigo_interno_temp + "_" + value.id_unidad).val() != undefined) {
                            //estamos en otro proveedor distinto de coopidrogas
                            costo = parseFloat($("#costo_" + producto.codigo_interno_temp + "_" + value.id_unidad).val());

                        } else {
                            ///estamos con coopidrogas, aqui se toma el costo unitario.  Deberia venir al menos con 0
                            costo = parseFloat($("#costo_unitario_" + producto.codigo_interno_temp).val());

                            //si el producto es uno nuevo que estoy creando para asociarlo, siempre el costo es 0
                            if ($("#producto_" + producto.codigo_interno_temp).attr('data-productonuevo') == "asociado") {
                                costo = 0;
                            }
                        }

                        if (isNaN(costo)) {
                            costo = 0;
                        }

                        var cantidad_a_dividir = 1
                        if (cantidad > 0) {
                            cantidad_a_dividir = cantidad
                        }

                        /*calculo el cost unitario del producto*/
                        costo_unitario_producto = (Compra.lst_producto[j]['unidades'][i]['costo'] / cantidad_a_dividir)
                        costo_unitario_producto= costo_unitario_producto-((parseFloat(descuento) * costo_unitario_producto) / 100);

                        if (producto.codigo_interno_temp == Compra.selected_codigo_interno_temp && i < 1) {
                            /*solo entra para la caja*/
                            if (parseFloat(costo_unitario_producto)
                                > parseFloat(Compra.costo_unitario_default)) {

                                $("#imagenultimocosto").html('<img width="25px" height="25px" src="' + baseurl + 'recursos/img/subida.jpg" >');
                            } else if (parseFloat(costo_unitario_producto)
                                == parseFloat(Compra.costo_unitario_default)) {
                                $("#imagenultimocosto").html('<img width="30px" height="25px" src="' + baseurl + 'recursos/img/igualamarillo.png" >');
                            } else {

                                $("#imagenultimocosto").html('<img width="25px" height="25px" src="' + baseurl + 'recursos/img/bajada.png" >');
                            }
                        }
                    }
                });
            }
        })
    },
    producto_separata: function (){

    }


}

