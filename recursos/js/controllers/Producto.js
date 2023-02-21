var Producto = {
    lst_producto: new Array(),
    droguerias: new Array(),
    stock: false,
    cache: {},
    contador_condicion: 0,
    contador_barra: 0,
    contador_img: 0,
    identificador: 0,
    costo_unitario: 0, /*esta variable es el costo unitario, que ahora es CAJA ya que los proveedores venden solo Cajas*/
    tipo_calculo: '', /*esta variable es para tener en cuenta el calculo que se va a hacer cuando se escriba sobre utilidad o precios*/
    caja: 0,
    blister: 0,
    unidad: 0,
    unidades: new Array(),
    impuestos: new Array(),
    condiciones: new Array(),
    preciosProducto: new Array(),
    datosProducto: new Array(),
    gruposProducto: new Array(),//los grupos asociaos al producto
    contenidos_internos: {},
    opcionesParamRap: {}, //para almacenar las opciones que son enviadas al servidor
    producto_seleccionadoid: '', //para saber sobre el producto que hice click o que seleccione el codigo de barra
    inizializeDomCache: function () {
        this.cache.drogueria = $("#drogueria");
        this.cache.drogueria_domain = $("#drogueria_domain");
        this.cache.locales = $("#locales");
        this.cache.pagecontent = $('#page-content');

    },
    init: function (droguerias, stock, todasunidades, arrcondiciones, tipocalculo) {
        this.inizializeDomCache();
        this.costo_unitario = 0;

        this.contenidos_internos = {};
        this.caja = 0;
        this.blister = 0;
        this.unidad = 0;

        this.events();
        this.stock = stock;
        this.droguerias = droguerias;
        this.unidades = todasunidades;
        this.condiciones = arrcondiciones;
        this.tipo_calculo = tipocalculo;
        this.lst_producto = new Array();
        this.opcionesParamRap = {};
        this.contador_barra = 0;
        this.producto_seleccionadoid = "";
    },
    events: function () {

        $('#info_is_paquete').css('display','block');

        $("select").chosen({search_contains: true, width: '100%'});

        $("#modal_codigo_barra").on('hidden.bs.modal', function (e) {
            Compra.cerrarmodalCodigoBarra();
        })

        /*esto es para buscar en la tabla cliente tipo campo, por padre solo persona natural */
        $("#agregar_barra").on('click', function (e) {
            e.preventDefault();
            Producto.agregar_barras();
        });

        $("#modal_codigo_barra").on('hidden.bs.modal', function (e) {
            Producto.cerrarmodalCodigoBarra();
        })

        $("#tbody").selectable({
            stop: function () {
                var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();
                //  console.log(id);
            }
        });

        $('#imagen_model').on('show.bs.modal', function (e) {

            jQuery.removeData(jQuery('#img_01'), 'elevateZoom');//remove zoom instance from image
            jQuery('.zoomContainer').remove()

        });

        jQuery('#imagen_model').on('hidden.bs.modal', function (e) {

            jQuery.removeData(jQuery('#img_01'), 'elevateZoom');//remove zoom instance from image
            jQuery('.zoomContainer').remove();// remove zoom container from DOM
        });
        Utilities.hiddePreloader();

        jQuery('#catalogo').on('hidden.bs.modal', function (e) {
            $('#productomodal').modal({show: true, keyboard: false, backdrop: 'static'});
        });

        jQuery('#productomodal').on('hidden.bs.modal', function (e) {
            Producto.filterProducts();
        });

    },

    armarGrupos: function(gruposProducto){
        for(var j = 0; j < gruposProducto.length; j++) {
            Producto.gruposProducto[j] = new Array();
            Producto.gruposProducto[j]['id_grupo'] = "";
            Producto.gruposProducto[j]['id_nivel'] = "";
            Producto.gruposProducto[j]['id_grupo'] = gruposProducto[j].grupo_id;
            Producto.gruposProducto[j]['id_nivel'] = gruposProducto[j].nivel_id;
        }
    },
    initAgregar: function (contador_unidad, unidadesArray, calculo_tipo, condicion_contador, impuesto, arrcondiciones,
                           arrPreciosProducto, dtProducto) {

        this.unidades = unidadesArray;
        this.contador_condicion = condicion_contador;
        this.tipo_calculo = calculo_tipo;
        this.impuestos = impuesto;
        this.condiciones = arrcondiciones;
        this.preciosProducto = arrPreciosProducto;
        this.datosProducto = dtProducto;

        if ($("#precio_abierto").is(':checked')) {
            Producto.armarPrecioMM();
        }

        setTimeout(function () {

            $("#producto_codigo_interno").focus();
        }, 500);


        this.eventAgregar();

    },
    eventAgregar: function () {

        $("#is_paquete").on('click', function () {
            Producto.tooglepaquete();
        });
        setTimeout(function () {
            $(".cho").css('width', '100%');
            $(".chosen-container").css('width', '100%');
        }, 1);
        /*esto es para buscar en la tabla cliente tipo campo, por padre solo persona natural */
        $("#agregar_barra").on('click', function (e) {
            e.preventDefault();
            Producto.agregar_barras();
        });
        /*se hace esto porque los campos texarea vienen con unos espacios en blanco*/
        $("#producto_mensaje").val();
        if ($("#id").val() != "") {
            $("#producto_mensaje").val($("#producto_mensaje").attr('data-texto'));

            /*aprovecho y busco los codigos de barra si hay*/
            Producto.traer_codigos_barra();
        }

        if ($('input[name="check_mensaje_producto"]:checked').val() == 0) {
            $("#producto_mensaje").prop('disabled', 'disabled')
        }

        $("input[name=check_mensaje_producto]").on('change', function () {

            if ($(this).val() == "0") {
                $("#producto_mensaje").val('')
                $("#producto_mensaje").prop('disabled', 'disabled')
            } else {
                $("#producto_mensaje").prop('disabled', false)
            }

        });


        /*$('.textarea-editor').wysihtml5({
         "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
         "emphasis": true, //Italics, bold, etc. Default true
         "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
         "html": false, //Button which allows you to edit the generated HTML. Default false
         "link": false, //Button to insert a link. Default true
         "image": false, //Button to insert an image. Default true,
         "color": false //Button to change color of font
         });*/

        $("#agregar_img").on('click', function (e) {

            Producto.contador_img++;
            $("#row1").append('<div class="row"><div class="col-md-6"><div class="input-prepend input-append input-group">' +
                '<span class="input-group-addon"><i class="fa fa-folder"></i> </span>' +
                '<input type="file" onchange="Producto.asignar_imagen(' + Producto.contador_img + ')" ' +
                'class="form-control input_imagen" data-count="1" name="userfile[]" accept="image/*"' +
                ' id="input_imagen' + Producto.contador_img + '"></div>' +
                '</div> <div class="col-md-2"><img id="imgSalida' + Producto.contador_img +
                '" src="' + baseurl + '/recursos/img/default_img.png" height="100" width="100"></div> </div>');
            e.preventDefault()
        })

        UiDraggable.init();


        //$("select[id^='medida']").chosen({ allow_single_deselect: true, disable_search_threshold: 5, width:"100%" });

        $('body').off('keydown');
        $('body').on('keydown', function (event) {

            if (event.keyCode === 115) {
                event.preventDefault();
                Producto.agregarprecio();
            }
        });

        $("#producto_clasificacion").chosen({search_contains: true});
        $("#producto_tipo").chosen({search_contains: true});
        $("#producto_componente").chosen({search_contains: true});
        $("#producto_ubicacion_fisica").chosen({search_contains: true});
        $("#productos_paquete").chosen({search_contains: true});

        $("#producto_marca").chosen({search_contains: true});
        $("#producto_linea").chosen({search_contains: true});
        $("#producto_familia").chosen({search_contains: true});
        $("#produto_grupo").chosen({search_contains: true});
        $("#nivel_grupo").chosen({search_contains: true});
        $("#producto_proveedor").chosen({search_contains: true});


        $("#producto_codigo_interno").focus();
        // Producto.handleF();

        $("#nivel_grupo").on('change', function () {

            Utilities.showPreloader();
            var ajaxresult = GrupoService.getGruposByNivel($("#nivel_grupo").val()); //busco los grupos por el nivel
            ajaxresult.success(function (data) {
                Utilities.hiddePreloader();

                $("#produto_grupo").html("");
                if (data.grupos.length > 0) {
                    var html = '<option value="">Seleccione</option>';
                    jQuery.each(data.grupos, function (u, value) {

                        html += '<option value="' + value['id_grupo'] + '">' + value['nombre_grupo'] + '</option>';

                    });

                    $("#produto_grupo").html(html);

                } else {
                    Utilities.alertModal(Messages.GLOBAL_ERROR);
                }
                $("#produto_grupo").trigger("chosen:open");
                $("#produto_grupo").trigger("chosen:updated");

            });
            ajaxresult.error(function () {
                Utilities.hiddePreloader();
                Utilities.alertModal(Messages.GLOBAL_ERROR);

            });
        });


    },


    contenido_interno: function (esto, contador) {
//esta funcion es la que hace los calculos con el contenido interno

        $("#unidad" + 2).attr('readonly', true);

        //es caja
        if (contador == 0) {

            //si es vacio coloco los demas vacio y los coloco disabled
            if ($(esto).val() == "" || $(esto).val() == false || $(esto).val() < 2) {
                $("#unidad" + 1).val('');
                $("#unidad" + 2).val('');
                $("#trunidad" + 1).find("input").attr("disabled", "disabled");
                $("#trunidad" + 2).find("input").attr("disabled", "disabled");

                if ($(esto).val() == "1") {

                    if ($("#tr_stock" + Producto.unidades[0]['id_unidad']).length == 0) {

                        Producto.actualizar_tablastock("" + Producto.unidades[0]['nombre_unidad'] + "", Producto.unidades[0]['id_unidad'])

                    }

                } else {
                    $("#tr_stock" + Producto.unidades[0]['id_unidad']).remove();
                }

                $("#tr_stock" + Producto.unidades[1]['id_unidad']).remove();

                $("#tr_stock" + Producto.unidades[2]['id_unidad']).remove();


            } else {

                if ($("#is_paquete").is(':checked')) {

                } else {

                    if ($("#tr_stock" + Producto.unidades[0]['id_unidad']).length == 0) {
                        Producto.actualizar_tablastock("" + Producto.unidades[0]['nombre_unidad'] + "", Producto.unidades[0]['id_unidad'])
                    }

                    if ($("#tr_stock" + Producto.unidades[2]['id_unidad']).length == 0) {
                        Producto.actualizar_tablastock("" + Producto.unidades[2]['nombre_unidad'] + "", Producto.unidades[2]['id_unidad'])
                    }
                    $("#trunidad" + 1).find("input").attr("disabled", false);
                    $("#trunidad" + 2).find("input").attr("disabled", false);

                    //pregunto si blister es distinto de vacio para saber si ponerle 1 a la unidad o hacer el calculo de blister
                    if ($("#unidad" + 1).val() != "" && $("#unidad" + 1).val() != false) {

                        //aqui verifico si el monto da decimal, para que no lo muestre
                        if ((($("#unidad" + 0).val() / $("#unidad" + 1).val()) % 1) != 0) {
                            //si la division entre la caja y el blister da decimal, coloco vacio el blister
                            Utilities.alertModal('<p>La divisi&oacute;n da como resultado un n&uacute;mero decimal</p>', 'warning');
                            $("#unidad" + 1).val('')
                            $("#unidad" + 2).val('1');
                        } else {
                            //sino hago la division
                            $("#unidad" + 2).val(parseInt($("#unidad" + 0).val()) / parseInt($("#unidad" + 1).val()));
                        }


                    } else {

                        $("#unidad" + 2).val('1');
                    }
                }

            }

        }

        //es blister
        if (contador == 1) {

            if ($(esto).val() == "" || $(esto).val() == false || $(esto).val() < 1) {
                $("#tr_stock" + Producto.unidades[1]['id_unidad']).remove();
                $("#unidad" + 2).val('1');

            } else if ($(esto).val() > 0) {
                //aqui valido que el valor en blister no sea igual al valor en caja
                if ($(esto).val() == $("#unidad" + 0).val()) {
                    Utilities.alertModal('<h4>No debe ser igual al valor en Caja</h4>', 'warning');
                    $(esto).val(Producto.blister);
                    return false;
                }
                //aqui verifico si el monto da decimal, para que no lo muestre
                if ((($("#unidad" + 0).val() / $(esto).val()) % 1) != 0) {

                    $(esto).val(Producto.blister);
                    return false;
                }

                if ($("#tr_stock" + Producto.unidades[1]['id_unidad']).length == 0) {
                    Producto.actualizar_tablastock("" + Producto.unidades[1]['nombre_unidad'] + "", Producto.unidades[1]['id_unidad'])
                }


                $("#unidad" + 2).attr('readonly', true);
                $("#unidad" + 2).val(parseInt($("#unidad" + 0).val()) / parseInt($("#unidad" + 1).val()));

            }

        }
    },

    guardarproducto: function () {

        $("#btnGuardar").prop('disabled', 'disabled');

        if ($("#producto_codigo_interno").val() == '' || $("#producto_codigo_interno").val() == 0) {

            $("#btnGuardar").prop('disabled', false);
            Utilities.alertModal('<h4>Debe ingresar un c&oacute;digo de producto v&aacute;lido</h4>', 'warning');
            $("#producto_codigo_interno").focus()

            return false;
        }

        //hago las validaciones del contenido interno
        var validacionCont = Producto.validarContenidoInt();

        if (validacionCont == false) {
            return false;
        }

        var validar_codigo = Producto.validar_codigo_interno($("#producto_codigo_interno"));
        if (validar_codigo == true) {
            $("#btnGuardar").prop('disabled', false);
            Utilities.alertModal('<h4>El c&oacute;digo ingresado ya existe</h4>', 'warning');
            $("#producto_codigo_interno").focus()

            return false;
        }


        var nombre = $("#producto_nombre");
        // var producto_impuesto = $("#producto_impuesto");
        if (nombre.val() == '') {

            $("#btnGuardar").prop('disabled', false);
            Utilities.alertModal('<h4>Debe ingresar el nombre del producto</h4>', 'warning');
            $("#producto_nombre").focus();
            return false;
        }


        if ($("#unidadescontainer tr").length == 0) {

            $("#btnGuardar").prop('disabled', false);
            Utilities.alertModal('<h4>Debe Seleccionar al menos una unidad de medida</h4>', 'info');
            return false;
        }

        //esto es para chequear que tenga seleccionado si o no el control d einventario
        if ($("#formguardar_productos input[name='control_inventario']:radio").is(':checked')) {

        } else {
            Utilities.alertModal('<h4>Debe Seleccionar una opci&oacute;n para Control de inventarios</h4>', 'warning');
            return false;
        }


        /*********aqui valido los precios minimo, para qu no coloquen uno mayor a otro*********/
        if ($("#precio_abierto").is(':checked')) {
            var minimo = '';
            var maximo = '';

            for (var j = 0; j < Producto.unidades.length; j++) {

                for (var i = 0; i < Producto.condiciones.length; i++) {


                    minimo = $("#precio_minimo_" + Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones);
                    maximo = $("#precio_maximo_" + Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones);

                    if (minimo.val() != "" && parseFloat(minimo.val()) > parseFloat(maximo.val())) {

                        minimo.focus();
                        $("#btnGuardar").prop('disabled', false);
                        Utilities.alertModal('<h4>El precio m&iacute;nimo debe ser menor al precio m&aacute;ximo</h4>', 'warning', true);
                        return false;
                        break;
                    }

                    if (maximo.val() != "" && parseFloat(maximo.val()) < parseFloat(minimo.val())) {
                        maximo.focus();
                        $("#btnGuardar").prop('disabled', false);
                        Utilities.alertModal('<h4>El precio m&aacute;ximo debe ser mayor al precio m&iacute;nimo</h4>', 'warning', true);
                        return false;
                        break;
                    }

                }
            }
        }
        /************************************************************************************************/

        $('#productomodal').modal('show');
        var validacion_barra = true;

        $("#div_inicial_barra_formProducto input[name*=codigos_barra]").each(function (i, valor) {
            var valor = $(this)

            if (i != 0) {

                if (valor.val() != "" && valor.val() != false) {

                    /*verifico si existe este codigo de barra*/
                    var verificar_serv = Producto.validar_existencia_barra(valor.val())
                    if (verificar_serv == true) {
                        $("#btnGuardar").prop('disabled', false);
                        Utilities.alertModal('<h4>Alerta</h4><p>El c&oacute;digo de barra ingresado ya existe</p>', 'warning');
                        valor.focus();
                        $('#productomodal').modal('hide');
                        i.stopPropagation();


                    }
                } else {
                    $("#btnGuardar").prop('disabled', false);
                    Utilities.alertModal('<h4>Alerta</h4><p>El c&oacute;digo de barra no debe ir vac&iacute;o</p>', 'warning');
                    valor.focus();

                    i.stopPropagation();
                }
            }


        });


        App.formSubmitWithFile($("#formguardar_productos").attr('action'), Producto.filterProducts, 'productomodal', 'formguardar_productos', 'btnGuardar');

    },
    //hago las validaciones del contenido interno
    validarContenidoInt: function () {

        var exito = true;
        if ($("#unidad0").val() == '' || $("#unidad0").val() == 0) {

            $("#btnGuardar").prop('disabled', false);
            $("#unidad0").focus()
            Utilities.alertModal('<h4>Debe ingresar al menos un contenido interno</h4>', 'warning');
            exito = false;
            return exito;
        } else {
            if ($("#unidad0").val() == $("#unidad2").val()) {
                $("#unidad0").focus();
                Utilities.alertModal('<h4>El valor en Caja no puede ser igual que el valor de Unidad</h4>', 'warning');
                exito = false;
                return exito;
            }
        }

        if ($("#unidad" + 1).val() == $("#unidad" + 0).val()) {
            exito = false;
            $("#unidad0").focus();
            Utilities.alertModal('<h4>El valor en blister no debe ser igual al valor en Caja</h4>', 'warning');
            return exito;
        }

        return exito;

    },

    rellenar_barras: function (codigo_de_barra) {
        /*este metodo rellena los input con los codigos de barra*/
        var div = '  <div class="form-group " id="div_barra' + Producto.contador_barra + '" ><div class="col-md-3 "></div><div class="col-md-7">' +
            '<input type="text" name="codigos_barra[]"  class="form-control" placeholder=""  value="' + codigo_de_barra + '"/> </div>' +
            '<div class="col-md-2">' +
            '<button class="fa fa-trash-o btn-default delete_barra" title="Eliminar" onclick="Producto.delete_barra(' + Producto.contador_barra + ')" ' +
            'aria-hidden="true" value="' + Producto.contador_barra + '" ></button>' +
            '</div>' +
            ' </div>'
        //col-md-offset-9
        $("#div_inicial_barra_formProducto").after(div);
        $("#codigo_barra_original").val('');
        $("#codigo_barra_original").focus();
        Producto.contador_barra++;
    },

    rellenar_barrasRap: function (codigo_de_barra) {

        /*este metodo rellena los input con los codigos de barra*/
        var div = '  <div class="form-group " id="div_barra' + Producto.contador_barra + '" ><div class="col-md-3 "></div><div class="col-md-7">' +
            '<input type="text" name="codigos_barra[]" id="" readonly class="form-control" placeholder=""  value="' + codigo_de_barra + '"/> </div>' +
            '<div class="col-md-2">' +
            '<button class="fa fa-trash-o btn-default delete_barra" title="Eliminar" onclick="Producto.delete_barra(' + Producto.contador_barra + ')" aria-hidden="true"' +
            ' value="' + Producto.contador_barra + '" ></button>' +
            '</div>' +
            ' </div>';

        //col-md-offset-9
        $("#abrir_codigos_barra").append(div);
        $("#codigo_barra_original").val('');
        $("#codigo_barra_original").focus();
        Producto.contador_barra++;
    },

    traer_codigos_barra: function () {

        /*este metodo busca los codigos de barras en el caso que vayas a editar un producto*/

        var buscarBarras = ProductoService.getCodigosBarra($("#id").val());
        buscarBarras.success(function (data) {
            if (data.error == undefined) {
                var barras = data.barras

                for (var i = 0; i < barras.length; i++) {
                    /*este metodo rellena los input con los codigos de barra*/
                    Producto.rellenar_barras(barras[i]['codigo_barra']);
                }
            }
        }).error(function () {
            Utilities.alertModal('<h4>Por favor comuniquese con soporte</h4>', 'warning');

        })
    }
    ,

    delete_barra: function (contador) {
        /*esto es para eliminarlos dinamicamente*/
        $("#div_barra" + contador).remove();
    }
    ,

    calcularcostos_global: function () {
        /*esta funcion se ejecuta cada vez que se escribe sobre el costo del producto o se cambia el impuesto*/
        if ($("#costo_unitario").val() != "" && $("#costo_unitario").val() != false) {
            /*se recorre las filas*/
            $("#unidadescontainer input[id^='unidad']").each(function (index) {
                /*se recorre segun el contador de condiciones de pago*/
                for (var i = 0; i < Producto.contador_condicion; i++) {
                    var utilidad = $("#utilidad_" + index + "_" + i);
                    var precio = $("#precio_valor_" + index + "_" + i);
                    /*primero hago el calculo en base a la utilidad si no esta vacio, sino, hago el calculo en base al precio*/
                    if ($("#utilidad_" + index + "_" + i).val() != "" && $("#utilidad_" + index + "_" + i).val() != false) {
                        Producto.calcular_precio(index, i)
                    } else if ($("#precio_valor_" + index + "_" + i).val() != "" && $("#precio_valor_" + index + "_" + i).val() != false) {
                        //calcular_utilidad(index, i)
                    }
                }
            });
        }
    }
    ,
    validar_codigo_interno: function (elemento) {

        var existe = false;
        if (elemento.val() != "" && elemento.val() != false) {
            $.ajax({
                url: baseurl + 'producto/validar_codigo_interno',
                type: "post",
                dataType: "json",
                async: false,
                data: {'codigo_interno': elemento.val(), 'id': $("#id").val()},
                success: function (data) {
                    existe = data.encontro;

                },
                error: function () {
                    Utilities.alertModal('<h4>Por favor comuniquese con soporte</h4>', 'warning');
                }
            });


            return existe;

        }
    }
    ,

    validar_numeropar: function (event, esto, contador) {

        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
            if (contador == 0) {
                Producto.caja = $(esto).val();
            }

            if (contador == 1) {
                Producto.blister = $(esto).val();
            }

            if (contador == 1) {
                Producto.unidad = $(esto).val();
            }

            return true;
        }
        if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
            if (contador == 0) {
                Producto.caja = $(esto).val();
            }

            if (contador == 1) {
                Producto.blister = $(esto).val();
            }

            if (contador == 1) {
                Producto.unidad = $(esto).val();
            }

            return true
        }
        else return false;

    },

    actualizar_tablastock: function (nombre, unidad_id) {

        /*esto es para abrir en la tabla de inventario stock*/
        var tabla_stock = "";
        tabla_stock += "<tr id='tr_stock" + unidad_id + "'>" +
            "<td id='td_stock_minimo" + unidad_id + "'>Stock m&iacute;nimo " + nombre + "" +
            "</td>" +
            "<td>" +
            "<input type='hidden' id='control_stock_unidad" + unidad_id + "' class='form-control' " +
            "value='" + unidad_id + "' min='0' onkeydown='return soloDecimal(this, event);' " +
            " name='control_stock_unidad[" + unidad_id + "]' >" +
            "<input type='text' class='form-control' " +
            "value='' min='0' onkeydown='return soloDecimal(this, event);' " +
            "name='stock_minimo[" + unidad_id + "]' id='stock_minimo" + unidad_id + "'></td>" +
            "<td id='td_stock_maximo" + unidad_id + "'>Stock m&aacute;ximo " + nombre + "</td>" +
            "<td><input type='text' class='form-control' " +
            "value='' min='0' " +
            "onkeydown='return soloDecimal(this, event);' " +
            "name='stock_maximo[" + unidad_id + "]' " +
            "id='stock_maximo" + unidad_id + "'></td> " +
            "</tr>";

        $("#tbody_stock").append(tabla_stock);
    },
    buscarParaPaquete: function () { //busco todos los productos

        $("#productos_paquete").html('');
        $("#productos_paquete").trigger("chosen:updated");
        Utilities.showPreloader();
        var buscar = ProductoService.getSoloProductos();
        var cantidad = "";
        var i = 1;
        buscar.success(function (data) {
            var html = '<option value="">Seleccione</option>';
            jQuery.each(data.productos, function (u, value) {

                if ((Producto.datosProducto['producto_id'] != undefined && value.producto_id != Producto.datosProducto['producto_id'])
                    || Producto.datosProducto['producto_id'] == undefined) {

                    html += '<option value="' + value['producto_id'] + '">' + value['producto_codigo_interno'] + ' ' +
                        '- ' + value['producto_nombre'] + '</option>';
                }

            });

            $("#productos_paquete").html(html);
            $("#productos_paquete").trigger("chosen:open");
            $("#productos_paquete").trigger("chosen:updated");

            Utilities.hiddePreloader();

        }).error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4>Ha ocurrido un error al buscar los productos!</h4>', 'warning', true);
        });
    },
    tooglepaquete: function () {

        //si es paquete solo se habilite vender por caja
        if ($("#is_paquete").is(':checked')) {
            $('#info_is_paquete').css('display','block');

            var select = $("#productos_paquete");

            var options = select[0].options;
            //si no tiene elementos, busco los productos para empezar a armar el paquete
            if (options.length < 1) {
                Producto.buscarParaPaquete();
            }
            $("#div_productos_paquete").css('display', 'block');
            $("#div_tab_productos_paquete").css('display', 'block');
            $("#productos_paquete").removeAttr('disabled');
            $("#productos_paquete").trigger("chosen:updated");
            $("#unidad1").attr('disabled', 'true');
            $("#unidad2").attr('disabled', 'true');
            $("#unidad1").val('');
            $("#unidad2").val('');

            //coloco value 1 a la caja y lo coloco readonly ya que cuando es paquete solo se vende por caja
            $("#unidad0").val('1');
            $("#unidad0").attr('readonly', true);

            //si esta checkeado, se deshabilita blister

        } else {
            $('#info_is_paquete').css('display','none');
            $("#div_productos_paquete").css('display', 'none');
            $("#div_tab_productos_paquete").css('display', 'none');
            $("#productos_paquete").attr('disabled', 'true');
            $("#productos_paquete").trigger("chosen:updated");
            //remuevo el readonly a la caja
            $("#unidad0").removeAttr('readonly');

            //pregunto si la caja es distinto de vacio o mayor que  para habilitar blister
            if ($("#unidad0").val() != "" && $("#unidad1").val() != false && $("#unidad1").val() > 1) {
                $("#unidad1").removeAttr('disabled');
            }

        }
    }
    ,

    deleteProd: function (id) {

        $("#tr_" + id).remove();
        return false;
    }
    ,

    addprod: function () {
        var producto_id = $("#productos_paquete").val();
        if (producto_id != '') {
            //console.log($("#tr_" + producto_id).length);
            if ($("#tr_" + producto_id).length == 0) {
                var unidades_has_prod = UnidadesService.getUnidadesByProd(producto_id);
                unidades_has_prod.success(function (data) {
                    var producto_nombre = $("#productos_paquete option:selected").html();
                    var tr = '<tr id="tr_' + producto_id + '"><td><input type="hidden" name="productos_paquete[]"  onkeydown="return soloNumeros(event);" value="' + producto_id + '">' + producto_nombre + '</td>';

                    jQuery.each(Producto.unidades, function (i, value) {
                        var tienelauniad = false;
                        jQuery.each(data, function (j, unidad) {
                            if (parseFloat(unidad.id_unidad) == parseFloat(value.id_unidad)) {
                                tienelauniad = true;
                            }
                        });
                        var readonly = '';
                        if (!tienelauniad) {
                            readonly = 'readonly';
                        }
                        tr += '<td><input ' + readonly + ' name="cantidad_' + producto_id + '[]" class="form-control" ><input type="hidden" name="unidad_' + producto_id + '[]" class="form-control" value="' + value.id_unidad + '" ></td>';
                    });
                    tr += '<th><button class="btn btn-success" onclick="Producto.deleteProd(' + producto_id + ')"><i class="fa fa-trash"></i></button></th>';
                    tr += '</tr>';
                    $("#tabla_productos tbody").append(tr);
                });
            } else {
                Utilities.alertModal('<h4>Alerta</h4> <p>Este producto ya ha sido agregado</p>', 'warning');
            }
        }
        else {
            Utilities.alertModal('<h4>Datos incompletos</h4> <p>Debe seleccionar un producto</p>', 'warning');
        }
    }
    ,

    procesar_catalogo: function (check) {
        Producto.procesar_catalogo(check);
    },
    verificar_catalogoProducto: function (check) {

        /*esta funcion valida si se esta editando un producto o es uno nuevo
         * para arrojar una advetencia de que se perderan los datos*/
        if ($("#id").val() != "") {
            $("#id_producto_catalogo").val(check)
            $("#confirmar_selec_catalogo").modal('show');
        } else {
            Producto.mostrar_datos_catalogo(check)
        }

    },
    /**esta funcion retorna el calculo ya realizado de un monto en base al porcentaje del impuesto*/
    calcular_impuesto: function (calculo, iva) {
        /**esta funcion retorna el calculo ya realizado de un monto en base al porcentaje del impuesto*/
        var calculo_impuesto = (iva / 100);
        calculo_impuesto = parseFloat(calculo) * parseFloat(calculo_impuesto);
        calculo = parseFloat(calculo) + parseFloat(calculo_impuesto);
        return calculo;
    },
    calcular_precio: function (contadorfila, contador2) {
        /*esta funcion calcula el precio cuando se escribe sobre cualquier input de "UTILIDAD" */
        if ($("#costo_unitario").val() != "" || $("#costo_unitario").val() != false) {

            /*calculo el costo de la unidad minima*/
            if ($("#unidad0").val() != "" && $("#unidad0").val() != false) {
                Producto.costo_unitario = $("#costo_unitario").val();

                /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el calculo*/
                if (Producto.tipo_calculo == "FINANCIERO") {
                    Producto.calcular_financiero("PRECIO", contadorfila, contador2);
                }
                if (Producto.tipo_calculo == "MATEMATICO") {
                    Producto.calcular_matematico("PRECIO", contadorfila, contador2);
                }
            } else {
                $("#unidad0").focus();
                Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning');
            }
        }
    }
    ,
    calcular_utilidad: function (contadorfila, contador2) {
        /*esta funcion calcula el precio cuando se escribe sobre cualquier input de los "PRECIOS"  (CONTADO,CREDITO,...)*/

        if ($("#costo_unitario").val() != "" || $("#costo_unitario").val() != false) {
            /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el calculo*/

            /*calculo el costo de la unidad minima*/
            if ($("#unidad0").val() != "" && $("#unidad0").val() != false) {
                Producto.costo_unitario = $("#costo_unitario").val();

                //$("#costo_unitario").val();

                if (Producto.tipo_calculo == "FINANCIERO") {
                    Producto.calcular_financiero("UTILIDAD", contadorfila, contador2);
                }
                if (Producto.tipo_calculo == "MATEMATICO") {

                    Producto.calcular_matematico("UTILIDAD", contadorfila, contador2);
                }
            } else {
                $("#unidad0").focus()
                Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning', true);
            }
        }
    }
    ,
    calcular_financiero: function (quecalcular, contadorfila, contador2) {

        var utilidad = $("#utilidad_" + contadorfila + "_" + contador2);
        var precio = $("#precio_valor_" + contadorfila + "_" + contador2);

        /*hago el calculo de cuanto deberia costar*/
        var calculo = Producto.costo_unitario;

        //si es blister
        if (contadorfila == 1) {

            var valor_blister = $("#unidad1").val();

            //si blister es vacio o falso le quito el foco y lo mando a colocar un contenido interno para blister

            if (valor_blister == "" || valor_blister == false) {
                utilidad.val('');
                precio.val('');
                $("#unidad1").focus();
                Utilities.alertModal('<h4>Alerta</h4> <p>Debe introducir contenido interno en blister</p>', 'warning', true);
                return false;

            }
            calculo = calculo / valor_blister;

        }

        //si es unidad
        if (contadorfila == 2) {

            calculo = calculo / $("#unidad0").val();
        }

        var impuesto = $("#producto_impuesto_costos option:selected");
        /*verifico si hay algun impuesto seleccionado*/
        if (impuesto.val() != "") {
            calculo = Producto.calcular_impuesto(calculo, impuesto.attr('data-porcentaje'));
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
                precio.val(((calculo / 100) * 100).toFixed(2));
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
        }
    }
    ,
    calcular_matematico: function (quecalcular, contadorfila, contador2) {

        var utilidad = $("#utilidad_" + contadorfila + "_" + contador2);
        var precio = $("#precio_valor_" + contadorfila + "_" + contador2);

        if ($("#unidad" + contadorfila).val() != "" && $("#unidad" + contadorfila).val() != 0) {

            /*hago el calculo de cuanto deberia costar*/
            var calculo = Producto.costo_unitario;

            //si es blister
            if (contadorfila == 1) {

                var valor_blister = $("#unidad1").val();

                //si blister es vacio o falso le quito el foco y lo mando a colocar un contenido interno para blister

                if (valor_blister == "" || valor_blister == false) {
                    utilidad.val('');
                    precio.val('');
                    $("#unidad1").focus();
                    Utilities.alertModal('<h4>Alerta</h4> <p>Debe introducir contenido interno en blister</p>', 'warning', true);
                    return false;

                }
                calculo = calculo / valor_blister;

            }

            //si es unidad
            if (contadorfila == 2) {

                calculo = calculo / $("#unidad0").val();
            }

            var impuesto = $("#producto_impuesto_costos option:selected");
            /*verifico si hay algun impuesto seleccionado*/
            if (impuesto.val() != "") {
                calculo = Producto.calcular_impuesto(calculo, impuesto.attr('data-porcentaje'));
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
                    calculo = ((precio.val() * 100) / calculo) - 100;
                    calculo = parseFloat(calculo).toFixed(2);
                    utilidad.val(calculo);
                } else {
                    utilidad.val('');
                }
            }
        }
    },

    validar_existencia_barra: function (barra) {
        /*este metodo, busca en a BD si existe el codigo de barra enviado*/
        var existe = false;
        $.ajax({
            url: baseurl + 'producto/validar_existencia_barra',
            type: "post",
            dataType: "json",
            async: false,
            data: {'producto_id': $("#id").val(), 'codigo_barra': barra},
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
    },
    addBarraRap: function () {
        /*este metodo es usado al presionar sobre el boton anadir para agregar codigos de barra, en parametrizacion rapida*/
        var seguir = true;
        var codigo_de_barra = $("#codigo_barra_original").val();
        /*valido si ya ingrese algun codigo de barra que ya exista, y le indico si debe guardarlos o no*/
        seguir = Producto.validar_codigos_barra(false);

        /*si en ningun momento encontro algun error, relleno los input*/
        if (seguir == true) {
            Producto.rellenar_barrasRap(codigo_de_barra)
        }
    },

    validar_codigos_barra: function (guardar) {
        //este metodo hace las validaciones de los codigos de barra
        //si guardar es false, no esta guardando,
        var seguir = true;
        var codigo_de_barra = $("#codigo_barra_original").val();
        var cont = 0;

        if (guardar == false && codigo_de_barra == "" && codigo_de_barra == false) {
            Utilities.alertModal('<h4>Error</h4><p>Debe ingresar un c&oacute;digo v&aacute;lido</p>', 'warning', true);
            $("#codigo_barra_original").focus();
            seguir = false;
            return false;
        }

        if (guardar == false && codigo_de_barra == "" && codigo_de_barra == false) {
            Utilities.alertModal('<h4>Error</h4><p>Debe ingresar un c&oacute;digo v&aacute;lido</p>', 'warning', true);
            $("#codigo_barra_original").focus();
            seguir = false;
            return false;
        }


        if (codigo_de_barra != false && codigo_de_barra != "") {
            /*verifico si existe este codigo de barra*/
            var verificar_serv = Compra.validar_existencia_barra(Producto.producto_seleccionadoid, codigo_de_barra)

            if (verificar_serv == true) {
                Utilities.alertModal('<h4>Error</h4><p>El c&oacute;digo de barra ingresado ya existe</p>', 'warning', true);
                $("#codigo_barra_original").focus();
                seguir = false;
                return false;

            }
        }
        //aqui es <1 porque estoy buscando los input dentro de #abrir_codigos_barra
        if ($("#abrir_codigos_barra input[name*=codigos_barra]").length < 1 && guardar == true) {

            return true;
        }
        Producto.lst_producto[Producto.producto_seleccionadoid]['codigos_barra'] = {};

        $("#abrir_codigos_barra input[name*=codigos_barra]").each(function (i, ar) {
            var valor = $(this);

            if (codigo_de_barra == valor.val()) {
                Utilities.alertModal('<h4>Error</h4><p>Ya ha ingresado este c&oacute;digo de barra</p>', 'warning', true);
                seguir = false;
                return false;
            }

            if (guardar == true) {

                Producto.lst_producto[Producto.producto_seleccionadoid]['codigos_barra'][cont] = valor.val();
            }
            cont++;


        });


        return seguir;
    },

    agregar_barras: function () {
        /*este metodo es usado al presionar sobre el boton anadir para agregar codigos de barra*/
        var seguir = true;
        var codigo_de_barra = $("#codigo_barra_original").val();

        /*valido si ya ingrese algun codigo de barra que ya exista*/
        $("#div_inicial_barra_formProducto input[name*=codigos_barra]").each(function (i, valor) {
            var valor = $(this)

            if (valor.val() != "" && valor.val() != false) {
                /*el 0 es el primer input, que esta al lado del boton anadir, seria la variable codigo_de_barra*/
                if (i != 0) {

                    if (codigo_de_barra == valor.val()) {

                        $("#codigo_barra_original").focus()
                        Utilities.alertModal('<h4>Alerta</h4><p>Ya ha ingresado este c&oacute;digo de barra</p>', 'warning', true);
                        seguir = false;
                        return false;
                    }
                }

                /*verifico si existe este codigo de barra*/
                var verificar_serv = Producto.validar_existencia_barra(valor.val())
                if (verificar_serv == true) {

                    Utilities.alertModal('<h4>Alerta</h4><p>El c&oacute;digo de barra ingresado ya existe</p>', 'warning', true);
                    valor.focus();
                    seguir = false;
                    return false;

                }
            } else {

                Utilities.alertModal('<h4>Alerta</h4><p>El c&oacute;digo de barra no debe estar vacío</p>', 'warning', true);
                valor.focus();
                seguir = false;
                return false;
            }
        });

        /*si en ningun momento encontro algun error, relleno los input*/
        if (seguir == true) {
            Producto.rellenar_barras(codigo_de_barra)
        }
    },

    asignar_identificador: function (identif) {
        Producto.identificador = identif;
    }
    ,

    fileOnload: function (e) {
        var result = e.target.result;
        $('#imgSalida' + Producto.identificador).attr("src", result);

    }
    ,
    asignar_imagen: function (con) {
        var input = $("#input_imagen" + con)
        if (input[0].files[0] && input[0].files[0]) {

            Producto.asignar_identificador(con)
            var reader = new FileReader();
            reader.onload = Producto.fileOnload;

            reader.readAsDataURL(input[0].files[0]);
        }

    }
    ,
    /*este borra la imagen de los productos, pero de la pestana IMAGEN*/
    borrar_img: function (producto_id, nombre, id_div) {

        $.ajax({
            url: baseurl + 'producto/eliminarimg',
            type: "post",
            dataType: "json",
            data: {'producto_id': producto_id, 'nombre': nombre},
            success: function (data) {

                if (data.error == undefined) {

                    $("#div_imagen_producto" + id_div).remove()

                    var growlType = 'success';
                    Utilities.alertModal('<h4>' + data.success + '</h4>', 'success', true);
                } else {
                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'warning', true);
                }

            },
            error: function () {

                Utilities.alertModal('<h4>Por favor comuniquese con soporte</h4>', 'warning', true);
            }
        })

    },
    filterProducts: function () {

        if ($("#control_inventario").val() != '') {
            var control_inventario = $("#control_inventario").val();  // checked
            if (control_inventario == "null") {
                Utilities.alertModal('Estos productos no tienen seleccionada ninguna opci&oacute;n para el Control de Inventarios', 'info', 5000);
            }
        } else {
            var control_inventario = '';  // check
        }

        if ($("#control_inventario_diario").val() != '') {
            var control_inventario_diario = $("#control_inventario_diario").val();  // checked
            if (control_inventario_diario == "null") {
                Utilities.alertModal('Estos productos no tienen seleccionada ninguna opci&oacute;n para el Control de Inventario diario', 'info', 5000);
            }
        } else {
            var control_inventario_diario = '';  // check
        }


        $("#drogueria").val('').trigger("chosen:updated");
        TablesDatatablesLazzy.init(baseurl + ProductoService.urlApi + '/dataTables', 0, 'table', {
            local: $('#locales').val(),
            control_inventario: control_inventario,
            control_inventario_diario: control_inventario_diario,
            produto_grupo: $("#produto_grupo").val(),
            producto_activo: $("#producto_activo").val(),
            stock: Producto.stock
        }, false, Producto.checkContInMal);

    },

    unidadesycostos: function () {
        var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();

        if (id != undefined) {
            $("#productomodal").load(baseurl + 'producto/verunidades/' + id);
            $('#productomodal').modal('show');
        }
    },
    getproductosbyDrogueria: function () {
        var domain = '';
        var drogueria_id = '';
        Producto.cache.locales.attr('disabled', true).trigger("chosen:updated");
        if (Producto.cache.drogueria.val() == '') {
            Producto.cache.locales.removeAttr('disabled').trigger("chosen:updated");
            Producto.filterProducts();
        } else {
            jQuery.each(Producto.droguerias, function (i, value) {
                if (value.drogueria_id == Producto.cache.drogueria.val()) {
                    domain = value.drogueria_domain;
                    drogueria_id = value.drogueria_id;
                }
            });

            if (typeof(Storage) !== undefined) {
                if (sessionStorage["drogueria_" + drogueria_id] === undefined) {
                    var authajax = SecurityService.login('PROSODE', 'SysCalVE87901.-', domain);
                    //  console.log(authajax);
                    //TODO HAY QUE HACER EL MODULO DE SEGURIDAD COMPLETO DONDE SE AUTORICE EL USO DEL API Y LE RETORNE UN USUARIO Y PASSWORD EL CUAL SE ALAMACENARA EN BD Y SE USARA AQUI
                    authajax.success(function (data) {
                        sessionStorage["api_drogueria_" + drogueria_id] = data.api_key;
                        TablesDatatablesLazzy.init(domain + ProductoService.urlApi + '/dataTables', 0, 'table', {
                            local: $('#locales').val(),
                            drogueria_id: $('#drogueria').val(),
                            stock: Producto.stock,
                            'x-api-key': data.api_key
                        });
                    });
                    authajax.error(function (request, status, error) {
                        console.log(request.responseText);
                        console.log(status);
                        console.log(error);
                    });
                } else {
                    TablesDatatablesLazzy.init(domain + ProductoService.urlApi + '/dataTables', 0, 'table', {
                        local: $('#locales').val(),
                        drogueria_id: $('#drogueria').val(),
                        stock: Producto.stock,
                        'x-api-key': sessionStorage["api_drogueria_" + drogueria_id]
                    });
                }

            } else {
                Utilities.alertModal('El navegador no soporta sessionStorage');

            }

        }
    }
    ,

    cerrar_confir_catalogo: function () {

        $("#confirmar_selec_catalogo").modal('hide');
    }
    ,

    selectProductError: function () {
        Utilities.alertModal('Debe seleccionar un producto', 'info');
    }
    ,

    ver_imagen: function () {

        var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();
        if (id != undefined) {
            $('#imagen_model').html($("#load_div").html());
            $("#imagen_model").load(baseurl + 'producto/ver_imagen/' + id);
            $('#imagen_model').modal({show: true, keyboard: false, backdrop: 'static'});

        }
        else Producto.selectProductError();

    },
    //functionBefore es una funcion a llamar despues de hacer la carga del modal, se hizo en compras
    agregar: function (functionBefore) {

        Utilities.showPreloader();
        $.ajax({
            url: baseurl + 'producto/agregar',
            type: 'post',
            asyn:false,
            success: function (data) {
                Utilities.hiddePreloader();
                $('#productomodal').html(data);
                $('#productomodal').modal({show: true, keyboard: false, backdrop: 'static'});

                if (functionBefore != false) {
                    functionBefore();
                }

            },
            error: function (error) {

                Utilities.hiddePreloader();
                Utilities.alertModal('Ha ocurrido un error', 'error');
            }

        })
    }
    ,


    editarProducto: function () {
        var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();

        if (id != undefined) {

            Utilities.showPreloader();

            $.ajax({
                url: baseurl + 'producto/agregar/' + id,
                type: 'post',
                success: function (data) {

                    Utilities.hiddePreloader();

                    setTimeout(function () {

                        $('#productomodal').html(data);
                        $('#productomodal').modal({show: true, keyboard: false, backdrop: 'static'});
                    }, 10)

                },
                error: function (error) {

                    Utilities.hiddePreloader();
                    Utilities.alertModal('Ha ocurrido un error', 'error');
                }

            })
        } else {

            Utilities.alertModal('Debe seleccionar un producto', 'warning');
            return false;
        }

    }
    ,


    duplicar: function () {
        var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();
        if (id != undefined) {
            $("#productomodal").load(baseurl + 'producto/agregar/' + id, {'duplicar': 1});
            $('#productomodal').modal({show: true, keyboard: false, backdrop: 'static'});
        }
    }
    ,

    columnas: function () {
        $("#columnas").load(baseurl + 'producto/editcolumnas');
        $('#columnas').modal({show: true, keyboard: false, backdrop: 'static'});
    }
    ,

    updateCostos: function () {
        Utilities.showPreloader();
        var ajaxresult = ProductoService.updateCostos();
        ajaxresult.success(function (data) {
            Utilities.hiddePreloader();
            if (data.result == Responses.OK) {
                Utilities.alertModal(Messages.GLOBAL_SUCCESS, 'success');
            } else {
                Utilities.alertModal(Messages.GLOBAL_ERROR);
            }

        });
        ajaxresult.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal(Messages.GLOBAL_ERROR);

        });
    },

    updateCostosPromedio: function () {
        Utilities.showPreloader();
        var ajaxresult = ProductoService.updateCostosPromedio();
        ajaxresult.success(function (data) {
            Utilities.hiddePreloader();
            if (data.result == Responses.OK) {
                Utilities.alertModal(Messages.GLOBAL_SUCCESS, 'success');
            } else {
                Utilities.alertModal(Messages.GLOBAL_ERROR);
            }

        });
        ajaxresult.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal(Messages.GLOBAL_ERROR);

        });

    },

    updatePreciosLote: function () {
        if ($("#porcentaje").val() == '') {
            Utilities.alertModal('Debe ingresar el porcentaje de utilidad');
            return false;
        }
        //  console.log($("#tipo_producto").val());
        if ($("#tipo_producto").val() == '' || $("#tipo_producto").val() == null) {
            Utilities.alertModal('Debe seleccionar al menos un grupo');
            return false;
        }

        Utilities.showPreloader();
        var ajaxresult = ProductoService.updatePrecios($("#formprecioslote").serialize());
        ajaxresult.success(function (data) {
            Utilities.hiddePreloader();
            if (data.result == Responses.OK) {
                Utilities.alertModal(Messages.GLOBAL_SUCCESS, 'success');
                $("#precioslote").modal('hide');
            } else {
                Utilities.alertModal(data.mesagge);
            }

        });
        ajaxresult.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal(Messages.GLOBAL_ERROR);

        });
    },


    preciosEnLoteModal: function () {

        $("#precioslote").modal();
    },

    confirmar: function () {
        var id = $("#tbody tr.ui-selected td:first input[name='producto_id_columna']").val();
        if (id != undefined) {
            $('#borrar').modal('show');
            $("#id_borrar").attr('value', id);
        }

    }
    ,

    eliminar: function () {

        App.formSubmitAjax($("#formeliminarProducto").attr('action'), Producto.filterProducts, 'borrar', 'formeliminarProducto');

    },

    mostrar_datos_catalogo: function (producto) {
        /*esta funcion pasa los datos del catalogo al modal de producto*/

        $("#confirmar_selec_catalogo").modal('hide');

        Utilities.showPreloader();
        $.ajax({
            url: baseurl + 'producto/producto_catalogo/',
            type: 'post',
            dataType: 'json',
            data: {'producto': producto},
            async: false,
            success: function (data) {
                $("#formguardar_productos").find("input[type=text],textarea").val("");
                var respuesta = data.res[0];

                $("#producto_codigo_interno").val(respuesta.producto_codigo_interno);
                $("#codigo_barra_original").val(respuesta.producto_codigo_barra);
                $("#producto_nombre").val(respuesta.producto_nombre);
                $("#costo_unitario").val(respuesta.costo_real);

                var encontro = false;

                $("#producto_impuesto_costos > option").each(function () {

                    if ((respuesta.iva * 100) == $(this).attr('data-porcentaje')) {
                        $("#producto_impuesto_costos").val(this.value);
                        encontro = true;
                    }

                });

                if (encontro == false) {

                    Utilities.alertModal('<h4>El impuesto que posee este producto no ha sido agregado!</h4>', 'warning', true);
                }

                Utilities.hiddePreloader();
                $("#catalogo").modal('hide');
            },
            error: function (error) {


                Utilities.hiddePreloader();
                Utilities.alertModal('Ha ocurrido un error', 'error');
            }

        })

        $('#productomodal').modal({show: true, keyboard: false, backdrop: 'static'});

    }
    ,

    ver_catalogo: function () {
        Utilities.showPreloader();
        var vista = "producto";

        var catalogo = ProductoService.catalogoCoopidrogas(vista);
        catalogo.success(function (data) {
            setTimeout(function () {

                $('#catalogo').html(data);
                $('#catalogo').modal({show: true, keyboard: false, backdrop: 'static'});
                Utilities.hiddePreloader();
            }, 10);

        }).error(function () {
            Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
            Utilities.hiddePreloader();
        });


    },

    armarPrecioMM: function () {  //arma los datos de precio minimo y maximo en la tabla

        var indiceCondiciones = new Array();
        for (var i = 0; i < Producto.condiciones.length; i++) {
            indiceCondiciones = Producto.condiciones[i];
            $("#thappend_" + indiceCondiciones.id_condiciones).after('<th class="precios_mm"> Precio M&iacute;nimo' +
                ' ' + indiceCondiciones.nombre_condiciones + '</th>' +
                '<th class="precios_mm"  > Precio M&aacute;ximo ' + indiceCondiciones.nombre_condiciones + '</th>');
        }
        indiceCondiciones = new Array();
        var indicePrecios = new Array();

        var readonly = '';
        var minimo = "";
        var maximo = "";
        for (var j = 0; j < Producto.unidades.length; j++) {

            readonly = '';
            if ($("#unidad" + j).is('[readonly]') || $("#unidad" + j).is(':disabled')) {
                readonly = 'readonly';
            }
            if (j == 2 && $("#unidad" + j).val() != "") {

                readonly = '';
            }

            for (var i = 0; i < Producto.condiciones.length; i++) {
                indiceCondiciones = Producto.condiciones[i];

                minimo = "";
                maximo = "";
                //busco los valores de los precios minimo y maximo
                for (var t = 0; t < Producto.preciosProducto.length; t++) {
                    indicePrecios = Producto.preciosProducto[t];

                    if (indicePrecios.id_condiciones_pago == indiceCondiciones.id_condiciones &&
                        indicePrecios.id_unidad == Producto.unidades[j].id_unidad) {

                        minimo = indicePrecios.precio_minimo;
                        maximo = indicePrecios.precio_maximo;

                        if (indicePrecios.precio_minimo == null) {
                            minimo = '';
                        }

                        if (indicePrecios.precio_maximo == null) {
                            maximo = '';
                        }

                    }
                }


                $("#tdappend_" + j + "_" + indiceCondiciones.id_condiciones).after(' <td class="precios_mm">' +
                    '<input min="0" type="text" ' + readonly + ' maxlength="18" ' +
                    'class="form-control precios_mm" ' +
                    'id="precio_minimo_' + Producto.unidades[j].id_unidad + '_' + indiceCondiciones.id_condiciones + '" ' +
                    'onkeydown="return soloDecimal3(this, event);" value="' + minimo + '" ' +
                    'name="precio_minimo_' + Producto.unidades[j].id_unidad + '[' + indiceCondiciones.id_condiciones + ']"> </td>' +
                    '<td class="precios_mm">' +
                    '<input min="0" type="text" maxlength="18" ' + readonly + ' ' +
                    'class="form-control precios_mm" value="' + maximo + '" ' +
                    'id="precio_maximo_' + Producto.unidades[j].id_unidad + '_' + indiceCondiciones.id_condiciones + '" ' +
                    'onkeydown="return soloDecimal3(this, event);" ' + 'value="' + maximo + '" ' +
                    'name="precio_maximo_' + Producto.unidades[j].id_unidad + '[' + indiceCondiciones.id_condiciones + ']"> </td>');

            }

        }
    },
    precioAbierto: function (esto) {

        if ($(esto).is(':checked')) {

            //agrego los campos en la tabla
            Producto.armarPrecioMM()

        } else {

            $(".precios_mm").remove();

        }
    },
    changeBtn: function (esto) {
        var esto = $(esto);

        if (esto.hasClass('btn-info')) {
            $(esto).removeClass('btn-info');
            $(esto).addClass('btn-default');
        } else {
            $(esto).removeClass('btn-default');
            $(esto).addClass('btn-info');
        }
        Producto.getproductosParamrap();

    },
    getproductosParamrap: function () {

        Producto.opcionesParamRap = {};
        var opciones = {};
        var attropcion = "";
        var thead = '<tr><th>ID</th><th>C&oacute;digo</th> <th>Nombre</th>';

        $(".opciones").each(function () {
            var valor = $(this);
            attropcion = valor.attr('data-opcion');
            if (valor.hasClass('btn-info')) {

                if (attropcion == "contenido_interno") {
                    opciones.contenido_interno = attropcion;
                    for (var i = 0; i < Producto.unidades.length; i++) {
                        thead += "<th>Contenido Interno " + Producto.unidades[i].nombre_unidad + "</th>";

                    }
                }
                if (attropcion == "precios") {
                    opciones.precios = attropcion;
                    for (var j = 0; j < Producto.condiciones.length; j++) {

                        for (var i = 0; i < Producto.unidades.length; i++) {

                            thead += '<th >% UTILIDAD ' + Producto.condiciones[j]['nombre_condiciones'] + ' ' + Producto.unidades[i]['nombre_unidad'] + '</th>';
                            thead += '<th >PRECIO ' + Producto.condiciones[j]['nombre_condiciones'] + ' ' + Producto.unidades[i]['nombre_unidad'] + '</th>';

                        }
                    }
                }
                if (attropcion == "codigos_barra") {
                    opciones.codigos_barra = attropcion;
                    thead += "<th>C&oacute;digos de barra</th>";
                }
                if (attropcion == "comision") {
                    opciones.comision = attropcion;
                    thead += "<th>Comisi&oacute;n</th>";
                }
                if (attropcion == "precio_abierto") {
                    opciones.precio_abierto = attropcion;
                    thead += "<th>Precio abierto</th>";

                    for (var j = 0; j < Producto.condiciones.length; j++) {

                        for (var i = 0; i < Producto.unidades.length; i++) {

                            thead += '<th >PRECIO MINIMO ' + Producto.condiciones[j]['nombre_condiciones'] + ' ' + Producto.unidades[i]['nombre_unidad'] + '</th>';
                            thead += '<th >PRECIO MAXIMO ' + Producto.condiciones[j]['nombre_condiciones'] + ' ' + Producto.unidades[i]['nombre_unidad'] + '</th>';

                        }
                    }
                }
                if (attropcion == "grupo") {
                    opciones.grupo = attropcion;
                    thead += "<th>Grupo</th>";
                }
                if (attropcion == "tipo") {
                    opciones.tipo = attropcion;
                    thead += "<th>Tipo</th>";
                }
                if (attropcion == "ubicacion_fisica") {
                    opciones.ubicacion_fisica = attropcion;
                    thead += "<th>Ubicaci&oacute;n f&iacute;sica</th>";
                }

                if (attropcion == "impuestos") {
                    opciones.impuestos = attropcion;
                    thead += "<th>Impuestos</th>";
                }

                if(attropcion=='tipo_item_dian'){
                    opciones.tipo_item_dian = attropcion;
                    thead += "<th>Tipo de Item (Dian)</th>";
                }

            }

        });
        opciones.local = $('#locales').val();
        Producto.opcionesParamRap = opciones;
        thead += '</tr>';
        var table = $('#table').DataTable();
        table.destroy();

        $('#table').remove();
        this.apendthead();
        $("#thead").html('');
        $("#thead").html(thead);
        $("#drogueria").val('').trigger("chosen:updated");
        TablesDatatablesLazzy.init(baseurl + ProductoService.urlApi + '/paramRap', 0, 'table', opciones, false, Producto.despuesLlamada);

    },
    apendthead: function () {

        $("#productostable").append(' <table class="table table-striped dataTable table-bordered" id="table"> ' +
            '<thead id="thead"></thead> <tbody id="tbody"> </tbody> </table>');

    },
    despuesLlamada: function (data) {
        //despues que se hace cada llamada al ajax de la parametrizacion rapida
        $("select").chosen({search_contains: true});
        if (data.productos) {

            Producto.colocarDatos(data.productos);

        }

        if (data.contenidos_internos) {

            jQuery.each(data.contenidos_internos, function (i, value) {

                if (Producto.contenidos_internos[i] == undefined) {
                    Producto.contenidos_internos[i] = {};
                }
                Producto.contenidos_internos[i] = value;
            })
        }

    },

    colocarDatos: function (productosPaginacion) {
        //para mostrar los datos almacenados, si ya se paso la pagina.
        //productosPaginacion son los productos que se estan mostrando actualmente en la pagina, ejemplo: los 10 que estan
        var producto_id = "";
        var miarreglo = new Array();
        for (var i = 0; i < productosPaginacion.length; i++) {

            producto_id = productosPaginacion[i];
            miarreglo = new Array();
            //busco los datos de este producto en lst_producto si existen.
            miarreglo = Producto.lst_producto[producto_id];

            if (miarreglo != undefined) {

                //si se le configuro un contenido interno aqui y si esta marcada la opcion
                if (miarreglo['contenido_interno'] != undefined && Producto.opcionesParamRap.contenido_interno) {

                    var conte = Producto.lst_producto[producto_id]['contenido_interno'];

                    for (var y = 0; y < conte.length; y++) {

                        $("#contenido_in" + producto_id + "_" + y).val(conte[y].cantidad);

                        if ((y > 0 && conte[y].cantidad == "") || (y == 2)) {

                            $("#contenido_in" + producto_id + "_" + y).attr('readonly', true)
                        } else {
                            $("#contenido_in" + producto_id + "_" + y).removeAttr('readonly')
                            $("#contenido_in" + producto_id + "_" + y).attr('readonly', false)
                        }
                    }
                }

                //si se le configuro precios
                if (miarreglo['precios'] != undefined && Producto.opcionesParamRap.precios) {
                    for (var j = 0; j < Producto.condiciones.length; j++) {
                        if (miarreglo['precios'][Producto.condiciones[j].id_condiciones] != undefined) {
                            for (var t = 0; t < Producto.unidades.length; t++) {
                                if (miarreglo['precios'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad] != undefined) {

                                    if (miarreglo['precios'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad]['utilidad'] != undefined) {
                                        $("#utilidad_" + producto_id + "_" + Producto.unidades[t].id_unidad + "_" + Producto.condiciones[j].id_condiciones)
                                            .val(miarreglo['precios'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad].utilidad);
                                    }
                                    if (miarreglo['precios'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad]['precio'] != undefined) {
                                        $("#precio_valor_" + producto_id + "_" + Producto.unidades[t].id_unidad + "_" + Producto.condiciones[j].id_condiciones)
                                            .val(miarreglo['precios'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad].precio);
                                    }
                                }
                            }
                        }
                    }
                }
                if (miarreglo['codigos_barra'] && Producto.opcionesParamRap.codigos) {

                }
                if (miarreglo['comision'] && Producto.opcionesParamRap.comision) {
                    $("#comision_" + producto_id).val(Producto.lst_producto[producto_id]['comision']);
                }


                if (miarreglo['precio_abierto'] && Producto.opcionesParamRap.precio_abierto) {
                    if (miarreglo['precio_abierto']['is_enabled'] == true) {
                        $("#precio_abierto_" + producto_id).attr('checked', true);
                    } else {
                        $("#precio_abierto_" + producto_id).attr('checked', false);
                    }
                    // $("#precio_abierto_" + producto_id).val(Producto.lst_producto[producto_id]['precio_abierto']);
                    for (var j = 0; j < Producto.condiciones.length; j++) {
                        if (miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones] != undefined) {
                            for (var t = 0; t < Producto.unidades.length; t++) {
                                if (miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad] != undefined) {

                                    if (miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad]['precio_minimo'] != undefined) {
                                        $("#precio_minimo_" + producto_id + "_" + Producto.unidades[t].id_unidad + "_" + Producto.condiciones[j].id_condiciones)
                                            .val(miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad].precio_minimo);
                                    }
                                    if (miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad]['precio_maximo'] != undefined) {
                                        $("#precio_maximo_" + producto_id + "_" + Producto.unidades[t].id_unidad + "_" + Producto.condiciones[j].id_condiciones)
                                            .val(miarreglo['precio_abierto'][Producto.condiciones[j].id_condiciones][Producto.unidades[t].id_unidad].precio_maximo);
                                    }
                                }
                            }
                        }
                    }

                    Producto.checkPrecioAbParamRap($("#precio_abierto_" + producto_id));

                }
                if (miarreglo['grupo'] && Producto.opcionesParamRap.grupo) {
                    $("#grupo_" + producto_id).val(Producto.lst_producto[producto_id]['grupo']);
                    $("#grupo_" + producto_id).trigger("chosen:updated");
                }


                if (miarreglo['tipo'] && Producto.opcionesParamRap.tipo) {
                    $("#tipo_" + producto_id).val(Producto.lst_producto[producto_id]['tipo']);
                    $("#tipo_" + producto_id).trigger("chosen:updated");
                }
                if (miarreglo['ubicacion_fisica'] && Producto.opcionesParamRap.ubicacion_fisica) {
                    $("#ubicacion_" + producto_id).val(Producto.lst_producto[producto_id]['ubicacion_fisica']);
                    $("#ubicacion_" + producto_id).trigger("chosen:updated");
                }
            }
        }
    },
    inputsearch_barra: function (evento, producto_id) {
        //declara que al epresionar enter sobre el input de codigos de barra,
        //levanta el modal para agregarlos

        if (evento.keyCode == 13) {
            evento.preventDefault();
            Producto.producto_seleccionadoid = producto_id;
            Producto.modalCogidoBarra(producto_id);

        }
    },
    modalCogidoBarra: function (producto_id) {
        //levanta el modal donde listo los codigos de barra

        $("#nombreproduto_codigo").html('');
        $("#nombreproduto_codigo").html($("#nombre_producto_" + producto_id).val());

        //pregunto si no esta definido para declarar el arreglo en lst_producto
        if (Producto.lst_producto[producto_id] == undefined) {
            Producto.lst_producto[producto_id] = {};
        }

        if (Producto.lst_producto[producto_id]['codigos_barra'] == undefined) {
            Producto.lst_producto[producto_id]['codigos_barra'] = {};
        }
        //pregunto si ya guardo en esta pagina algun codigo de barra, sino le busco sus codigos de barra
        if (Producto.lst_producto[producto_id]['yaActualizoCodigoBarra'] == undefined) {
            //Compra.lst_producto[posicion]['yaActualizoCodigoBarra'] es un booleano que se crea al guardar los codigos de barra
            //para saber si busco en la db o no
            var buscarBarras = ProductoService.getCodigosBarra(producto_id);

            buscarBarras.success(function (data) {
                var barras = data.barras;
                var cont = 0;
                for (var i = 0; i < parseInt(barras.length); i++) {

                    Producto.lst_producto[producto_id]['codigos_barra'][i] = barras[cont]['codigo_barra'];
                    cont++;
                }

            }).error(function () {
                Utilities.alertModal('<h4>Ha ocurrido un error al buscar los c&oacute;digos de barras</h4>', 'warning', true);

            })
        }

        for (var i = 0; i < Object.keys(Producto.lst_producto[producto_id]['codigos_barra']).length; i++) {
            /*este metodo rellena los input con los codigos de barra*/
            Producto.rellenar_barrasRap(Producto.lst_producto[producto_id]['codigos_barra'][i]);
        }

        $("#modal_codigo_barra").modal('show');
        $("#codigo_barra_original").focus();

    },
    cerrarmodalCodigoBarra: function () {

        $("#codigo_barra_original").val('');
        $("#abrir_codigos_barra").html('');
        $("#modal_codigo_barra").modal('hide');
    },
    guardarCodigosBarra: function () {
        //guarda los codigos de barra para el producto;
        Producto.lst_producto[Producto.producto_seleccionadoid]['yaActualizoCodigoBarra'] = true;

        var seguir = true;
        seguir = Producto.validar_codigos_barra(true);

        if (seguir == true) {
            Producto.lst_producto[Producto.producto_seleccionadoid]['producto_id']="";
            Producto.lst_producto[Producto.producto_seleccionadoid]['producto_id']=Producto.producto_seleccionadoid;
            $("#modal_codigo_barra").modal('hide');
        }
    },
    otrosDatosRap: function (esto, producto_id) {
        //guarda em lst_producto los demas datos, que no manejan unidades de medida, para ordenarlos

        if ($(esto).attr('data-tipo') == "comision") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['comision'] = $(esto).val();
        }

        if ($(esto).attr('data-tipo') == "grupo") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['grupo'] = $("#grupo_" + producto_id + " option:selected").val();
        }


        if ($(esto).attr('data-tipo') == "tipo") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['tipo'] = $("#tipo_" + producto_id + " option:selected").val();
        }

        if ($(esto).attr('data-tipo') == "ubicacion") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['ubicacion_fisica'] = $("#ubicacion_" + producto_id + " option:selected").val();
        }

        if ($(esto).attr('data-tipo') == "impuestos") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['impuestos'] = $("#impuestos_" + producto_id + " option:selected").val();
            Producto.lst_producto[producto_id]['impuestos_porcentaje'] = $("#impuestos_" + producto_id + " option:selected").attr("data-porcentaje_impuesto");

        }

        if ($(esto).attr('data-tipo') == "tipo_item_dian") {
            if (Producto.lst_producto[producto_id] == undefined) {
                Producto.lst_producto[producto_id] = {};
                Producto.lst_producto[producto_id]['producto_id'] = {};
                Producto.lst_producto[producto_id]['producto_id'] = producto_id;
            }
            Producto.lst_producto[producto_id]['tipo_item_dian'] = $("#tipo_item_dian_" + producto_id + " option:selected").val();

        }

    },
    habilitarPrecAbiParRam: function (producto_id) {
        if (Producto.lst_producto[producto_id] == undefined) {
            Producto.lst_producto[producto_id] = {};
            Producto.lst_producto[producto_id]['producto_id'] = {};
            Producto.lst_producto[producto_id]['producto_id'] = producto_id;
        }

        if (Producto.lst_producto[producto_id]['precio_abierto'] == undefined) {
            Producto.lst_producto[producto_id]['precio_abierto'] = {};
        }

        if (Producto.lst_producto[producto_id]['precio_abierto']['is_enabled'] == undefined) {
            Producto.lst_producto[producto_id]['precio_abierto']['is_enabled'] = {};
        }
    },
    preciAbiertosParamrap: function (esto, producto_id, id_unidad, id_condiciones) {
        //guarda los precios minimo y maximo segun el check de precio abierto

        //como esto se repite al presionar el check, entonces lo meto en un solo metodo
        Producto.habilitarPrecAbiParRam(producto_id);

        if ($("#precio_abierto_" + producto_id).is(":checked")) {

            Producto.lst_producto[producto_id]['precio_abierto']['is_enabled'] = true;

            if (Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones] == undefined) {
                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones] = {};
            }

            if (Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad] == undefined) {
                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad] = {};
            }

            if ($(esto).attr('data-tipo') == "precio_minimo") {

                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad]["precio_minimo"] = {}
                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad]["precio_minimo"] = $(esto).val();
            }

            if ($(esto).attr('data-tipo') == "precio_maximo") {

                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad]["precio_maximo"] = {}
                Producto.lst_producto[producto_id]['precio_abierto'][id_condiciones][id_unidad]["precio_maximo"] = $(esto).val();
            }
        } else {
            Producto.lst_producto[producto_id]['precio_abierto']['is_enabled'] = false;
            return false;
        }

    },
    checkPrecioAbParamRap: function (esto) {
        //cada vez que se hace click al check de precio abierto

        if ($(esto).is(':checked')) {

            Producto.habilitarPrecAbiParRam($(esto).val());
            Producto.lst_producto[$(esto).val()]['precio_abierto']['is_enabled'] = true;

            var costounitarioCaja = $(esto).attr('data-costo_unitario');
            var costoEstaunidad = "";

            for (var j = 0; j < Producto.unidades.length; j++) {

                for (var i = 0; i < Producto.condiciones.length; i++) {


                    costoEstaunidad = "";
                    costoEstaunidad = Producto.calcularCostoUnitario($("#contenido_in" + $(esto).val() + "_" + j).val(),
                        $(esto).val(), Producto.unidades[j].id_unidad, costounitarioCaja)

                    if (costoEstaunidad == "") {
                        //si no tengo
                        $("#precio_minimo_" + $(esto).val() + "_" +
                            Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones).css("display", "none");
                        $("#precio_maximo_" + $(esto).val() + "_" +
                            Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones).css("display", "none");

                    } else {
                        //si tengo la unidad configurada
                        $("#precio_minimo_" + $(esto).val() + "_" +
                            Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones).css("display", "block");
                        $("#precio_maximo_" + $(esto).val() + "_" +
                            Producto.unidades[j].id_unidad + "_" + Producto.condiciones[i].id_condiciones).css("display", "block");

                    }

                }
            }

        } else {

            $("input[name*=precio_minimo_" + $(esto).val() + "]").css("display", "none");
            $("input[name*=precio_maximo_" + $(esto).val() + "]").css("display", "none");
            Producto.habilitarPrecAbiParRam($(esto).val());
            Producto.lst_producto[$(esto).val()]['precio_abierto']['is_enabled'] = false;

        }

    },
    rellenarContenido: function (producto_id) {
        //guarda el contenido interno de los productos, sobre los cuales se escriban nada mas.

        if (Producto.lst_producto[producto_id] == undefined) {
            Producto.lst_producto[producto_id] = {};
            Producto.lst_producto[producto_id]['producto_id'] = {};
            Producto.lst_producto[producto_id]['producto_id'] = producto_id;
        }

        if (Producto.lst_producto[producto_id]['contenido_interno'] == undefined) {
            Producto.lst_producto[producto_id]['contenido_interno'] = {};
        }


        var producto = {};
        var unidades_prod = new Array();
        var cantidad = "0";
        jQuery.each(Producto.unidades, function (i, value) {

            var unidad = {};
            unidad.id = value.id_unidad;
            unidad.nombre_unidad = value.nombre_unidad;
            unidad.abreviatura = value.abreviatura;
            unidad.orden = value.orden;

            cantidad = "";
            if ($("#contenido_in" + producto_id + "_" + i).val() != ""
                && $("#contenido_in" + producto_id + "_" + i).val() != undefined) {
                cantidad = $("#contenido_in" + producto_id + "_" + i).val();
            }
            unidad.cantidad = cantidad;
            unidades_prod.push(unidad);
        });
        Producto.lst_producto[producto_id]['contenido_interno'] = unidades_prod;

    },
    rellenarPrecios: function (producto_id, unidad, idcondicion, precio, utilidad) {
        //guarda los precios de los prpductos, cada vez que se escriba sobre uno

        if (Producto.lst_producto[producto_id] == undefined) {
            Producto.lst_producto[producto_id] = {};
            Producto.lst_producto[producto_id]['producto_id'] = {};
            Producto.lst_producto[producto_id]['producto_id'] = producto_id;
        }

        if (Producto.lst_producto[producto_id]['precios'] == undefined) {
            Producto.lst_producto[producto_id]['precios'] = {};
        }

        if (Producto.lst_producto[producto_id]['precios'][idcondicion] == undefined) {
            Producto.lst_producto[producto_id]['precios'][idcondicion] = {};
        }

        if (Producto.lst_producto[producto_id]['precios'][idcondicion][unidad] == undefined) {
            Producto.lst_producto[producto_id]['precios'][idcondicion][unidad] = {};
        }

        Producto.lst_producto[producto_id]['precios'][idcondicion][unidad]['precio'] = {};
        Producto.lst_producto[producto_id]['precios'][idcondicion][unidad]['utilidad'] = {};
        Producto.lst_producto[producto_id]['precios'][idcondicion][unidad]['precio'] = precio;
        Producto.lst_producto[producto_id]['precios'][idcondicion][unidad]['utilidad'] = utilidad;

    }
    ,
    cont_paramrap: function (esto, contador, producto_id, id_unidad) {
        //unidad
        $("#contenido_in" + producto_id + "_2").attr('readonly', true);

        //es caja
        if (contador == 0) {

            //si es vacio coloco los demas vacio y los coloco readonly
            if ($(esto).val() == "" || $(esto).val() == false) {
                $("#contenido_in" + producto_id + "_1").val('');
                $("#contenido_in" + producto_id + "_2").val('');

                $("#contenido_in" + producto_id + "_1").attr("readonly", true);
                $("#contenido_in" + producto_id + "_2").attr("readonly", true);

                //deshabilito todos los precios y utilidades que no sean caja
                jQuery.each(Producto.condiciones, function (j, valor) {
                    jQuery.each(Producto.unidades, function (i, value) {

                        $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                        $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                        $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);
                        $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);

                        $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");
                        $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");

                    });
                });

            } else if ($(esto).val() < 2) {
                /*si el valor es 1*/

                $("#contenido_in" + producto_id + "_1").val('');
                $("#contenido_in" + producto_id + "_2").val('');

                $("#contenido_in" + producto_id + "_1").attr("readonly", true);

                //deshabilito todos los precios y utilidades que no sean caja
                jQuery.each(Producto.condiciones, function (j, valor) {
                    jQuery.each(Producto.unidades, function (i, value) {

                        if (contador != i) {

                            $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                            $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val("");
                            $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);
                            $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", true);


                            $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");
                            $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");
                        } else {


                            $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                            $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                            $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", false);
                            $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr("readonly", false);
                        }
                    });
                });


            } else {

                $("#contenido_in" + producto_id + "_1").attr("readonly", false);
                //les quito el readonly

                jQuery.each(Producto.condiciones, function (j, valor) {
                    jQuery.each(Producto.unidades, function (i, value) {

                        setTimeout(function () {
                            $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);
                            $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);
                            $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                            $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                        }, 10);
                    });
                });

                //pregunto si blister es distinto de vacio para saber si ponerle 1 a la unidad o hacer el calculo de blister
                if ($("#contenido_in" + producto_id + "_1").val() != "" && $("#contenido_in" + producto_id + "_1").val() != false) {
                    //aqui verifico si el monto da decimal, para que no lo muestre
                    if ((($("#contenido_in" + producto_id + "_0").val() / $("#contenido_in" + producto_id + "_1").val()) % 1) != 0) {
                        //si la division entre la caja y el blister da decimal, coloco vacio el blister
                        Utilities.alertModal('<p>La divisi&oacute;n da como resultado un n&uacute;mero decimal</p>', 'warning', true);
                        $("#contenido_in" + producto_id + "_1").val('');
                        $("#contenido_in" + producto_id + "_2").val('1');
                    } else {
                        //sino hago la division
                        $("#contenido_in" + producto_id + "_2").val(parseInt($("#contenido_in" + producto_id + "_0").val()) / parseInt($("#contenido_in" + producto_id + "_1").val()));

                    }

                } else {

                    $("#contenido_in" + producto_id + "_2").val('1');
                }
            }
        }
        //es blister
        if (contador == 1) {

            if ($(esto).val() == "" || $(esto).val() == false || $(esto).val() < 1) {

                /*coloco la unidad en 1*/
                $("#contenido_in" + producto_id + "_2").val('1');

                /*limpio todos los precios y utlidades solo de blister*/
                jQuery.each(Producto.condiciones, function (j, valor) {
                    jQuery.each(Producto.unidades, function (i, value) {

                        if (contador == i) {

                            setTimeout(function () {
                                $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val('');
                                $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).val('');

                                $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");
                                $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "none");

                            }, 10);

                        }
                    });
                });
            } else if ($(esto).val() > 0) {
                //aqui verifico si el monto da decimal, para que no lo muestre
                if ((($("#contenido_in" + producto_id + "_0").val() / $(esto).val()) % 1) != 0) {

                    $(esto).val(Producto.blister);
                    return false;
                }
                $("#contenido_in" + producto_id + "_2").attr('readonly', true);
                $("#contenido_in" + producto_id + "_2").val(parseInt($("#contenido_in" + producto_id + "_0").val()) / parseInt($("#contenido_in" + producto_id + "_1").val()));

                jQuery.each(Producto.condiciones, function (j, valor) {
                    jQuery.each(Producto.unidades, function (i, value) {

                        if (contador == i) {

                            setTimeout(function () {
                                if ($("#precio_abierto_" + producto_id).is(":checked")) {
                                    $("#precio_minimo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                                    $("#precio_maximo_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).css("display", "block");
                                }

                                $("#utilidad_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);
                                $("#precio_valor_" + producto_id + "_" + value.id_unidad + "_" + valor.id_condiciones).attr('readonly', false);
                            }, 10);

                        }
                    });
                });


            }
        }

        Producto.rellenarContenido(producto_id);

    },
    calcular_precio_rap: function (evento, esto, producto_id, unidad, costounitario, iva, idcondicion, contadorfila) {
        /*esta funcion calcula el precio cuando se escribe sobre cualquier input de "UTILIDAD" */


        if (($(esto).val() > 0) || ($(esto).val() != "0" && $(esto).val() == "")) {

            if (costounitario == "" || costounitario == false) {
                Utilities.alertModal('<h4>Este producto no tiene un costo unitario v&aacute;lido</h4>', 'warning', true);
                return false;
            }

            if ($("#contenido_in" + producto_id + "_0").val() != "" && $("#contenido_in" + producto_id + "_0").val() != false) {
                Producto.costo_unitario = costounitario;

                /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el calculo*/
                if (Producto.tipo_calculo == "FINANCIERO") {
                    Producto.calcular_financiero_rap("PRECIO", producto_id, unidad, iva, idcondicion, contadorfila, evento);
                }
                if (Producto.tipo_calculo == "MATEMATICO") {
                    Producto.calcular_matematico_rap("PRECIO", producto_id, unidad, iva, idcondicion, contadorfila, evento);
                }
            } else {
                $(esto).val('');
                $("#contenido_in" + producto_id + "_0").focus();

                Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning', true);
            }


        } else {
            Utilities.alertModal('<h4>Debe ingresar una cantidad mayor a 0</h4>', 'warning', true);
            var precio = $("#precio_valor_" + producto_id + "_" + unidad + "_" + idcondicion);
            Producto.rellenarPrecios(producto_id, unidad, idcondicion, precio.val(), $(esto).val(""));
        }
    }
    ,
    calcular_utilidad_rap: function (evento, esto, producto_id, unidad, costounitario, iva, idcondicion, contadorfila) {

        if (($(esto).val() > 0) || ($(esto).val() != "0" && $(esto).val() == "")) {

            //idcondicion es si es credito o contado
            /*esta funcion calcula el precio cuando se escribe sobre cualquier input de los "PRECIOS"  (CONTADO,CREDITO,...)*/

            if (costounitario == "" || costounitario == false) {
                Utilities.alertModal('<h4>Este producto no tiene un costo unitario v&aacute;lido</h4>', 'warning', true);
                return false;
            }
            /*dependiendo del tipo de calculo que se haya guardado en el sistema se hace el cal            culo*/
            if ($("#contenido_in" + producto_id + "_0").val() != "" && $("#contenido_in" + producto_id + "_0").val() != false) {

                Producto.costo_unitario = costounitario;
                if (Producto.tipo_calculo == "FINANCIERO") {

                    Producto.calcular_financiero_rap("UTILIDAD", producto_id, unidad, iva, idcondicion,
                        contadorfila, evento);
                }
                if (Producto.tipo_calculo == "MATEMATICO") {

                    Producto.calcular_matematico_rap("UTILIDAD", producto_id, unidad, iva, idcondicion,
                        contadorfila, evento);
                }
            } else {
                $(esto).val('');
                $("#contenido_in" + producto_id + "_0").focus();

                Utilities.alertModal('<h4>Debe ingresar una cantidad en Contenido Interno</h4>', 'warning', true);
            }


        } else {
            Utilities.alertModal('<h4>Debe ingresar una cantidad mayor a 0</h4>', 'warning', true);
            var utilidad = $("#utilidad_" + producto_id + "_" + unidad + "_" + idcondicion);
            Producto.rellenarPrecios(producto_id, unidad, idcondicion, $(esto).val(""), utilidad.val());
        }
    }
    ,
    calcular_financiero_rap: function (quecalcular, producto_id, unidad, iva, idcondicion, contadorfila, evento) {
        //hace el calculo financiero

        var utilidad = $("#utilidad_" + producto_id + "_" + unidad + "_" + idcondicion);
        var precio = $("#precio_valor_" + producto_id + "_" + unidad + "_" + idcondicion);
        var costoEstaunidad = "";
        costoEstaunidad = Producto.calcularCostoUnitario($("#contenido_in" + producto_id + "_" + contadorfila).val(),
            producto_id, unidad, Producto.costo_unitario)


        if (costoEstaunidad == "" && Traslado.validaTecla(evento)) {
            Utilities.alertModal('<h4>Error</h4> <p>Debe configurar un contenido interno para esta unidad</p>', 'warning', true);
            utilidad.val('');
            precio.val('');
            return false;

        }

        //calculo cuanto cuesta esta unidad
        var calculo = costoEstaunidad;

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
                precio.val(((calculo / 100) * 100).toFixed(2));
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
        }
        Producto.rellenarPrecios(producto_id, unidad, idcondicion, precio.val(), utilidad.val());

    },
    calcular_matematico_rap: function (quecalcular, producto_id, unidad, iva, idcondicion, contadorfila, costounitario) {

        var utilidad = $("#utilidad_" + producto_id + "_" + unidad + "_" + idcondicion);
        var precio = $("#precio_valor_" + producto_id + "_" + unidad + "_" + idcondicion);
        var costoEstaunidad = "";
        costoEstaunidad = Producto.calcularCostoUnitario($("#contenido_in" + producto_id + "_" + contadorfila).val(),
            producto_id, unidad, Producto.costo_unitario)

        if (costoEstaunidad == "" && Traslado.validaTecla(evento)) {
            Utilities.alertModal('<h4>Error</h4> <p>Debe configurar un contenido interno para esta unidad</p>', 'warning', true);
            utilidad.val('');
            precio.val('');
            return false;

        }
        //esto es lo que esta costando por unidad (caja o blister o unidad)
        var calculo = costoEstaunidad;

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
                calculo = ((precio.val() * 100) / calculo) - 100;
                calculo = parseFloat(calculo).toFixed(2);
                utilidad.val(calculo);
            } else {
                utilidad.val('');
            }
        }
        Producto.rellenarPrecios(producto_id, unidad, idcondicion, precio.val(), utilidad.val());

    },
    calcularCostoUnitario: function (input, producto_id, unidad, costounitariocaja) {

        //el input es el value del input que paso,
        //aqui valido si el input existe, si es asi, pregunto si es "" y retorno para decirle que debe configurar un contenido interno
        //si asi se requiere
        if (input != undefined) {

            if (input == "") {
                return "";
            }
        }

        var encontro = false;
        var costoEstaunidad = "";
        var contenido_estaunidad = "";
        var contIntCaja = "";
        var unidad_arr = "";

        //primero pregunto por lst_producto[producto_id] y Producto.lst_producto[producto_id]['contenido_interno']
        //ya que en este arreglo van a estar lo ultimo que se tiene en memoria de los contenidos internos
        if (Producto.lst_producto[producto_id] != undefined) {
            if (Producto.lst_producto[producto_id]['contenido_interno'] != undefined) {

                jQuery.each(Producto.lst_producto[producto_id]['contenido_interno'], function (i, value) {

                    contenido_estaunidad = value.cantidad;

                    if (value.orden == 1) {
                        //guardo el contenido interno de caja
                        contIntCaja = contenido_estaunidad;
                    }

                    unidad_arr = value['id']
                    if (unidad_arr == unidad) {
                        encontro = true;

                        if (value.orden == 1) {
                            costoEstaunidad = costounitariocaja;
                        }

                        if (value.orden == 2) {

                            costoEstaunidad = costounitariocaja / contenido_estaunidad;
                        }

                        if (value.orden == 3) {
                            costoEstaunidad = costounitariocaja / contIntCaja;
                        }
                    }
                });
            }
        }

        //si encontro algun contenido interno en lst_producto, retorno cuanto cuesta la unidad
        if (encontro == true) {
            return costoEstaunidad
        }

        var cantidad = "";
        costoEstaunidad = "";
        contenido_estaunidad = "";
        contIntCaja = "";

        //si no consiguio nada en lst_producto, pregunto en Producto.contenidos_internos
        //estos son almacenados luego de hacer algun llamado ajax en la tabla, ejemplo pasar de pagina
        //si no consigue nada aqui, retorno false y ya con eso se sabe que no tiene configurado contenido interno para esta unidad
        jQuery.each(Producto.contenidos_internos[producto_id], function (i, value) {


            if (value.orden == 1) {
                //guardo el contenido interno de caja
                contIntCaja = value.unidades;
            }

            if (value['id_unidad'] == unidad) {

                cantidad = value['unidades'];
                encontro = true;
                contenido_estaunidad = value.unidades;
                if (value.orden == 1) {
                    costoEstaunidad = costounitariocaja;
                }

                if (value.orden == 2) {
                    costoEstaunidad = costounitariocaja / contenido_estaunidad;
                }

                if (value.orden == 3) {
                    costoEstaunidad = costounitariocaja / contIntCaja;
                }
            }
        });

        //si no encontro la unidad
        if (encontro == false) {
            return false;
        }

        //si llega aqui es porque encontro=true y tiene el costo de esta unidad
        return costoEstaunidad;
    },
    guardarParamRap: function () {

        Utilities.showPreloader();


        var lst = JSON.stringify(Producto.lst_producto);

        var ajaxguardar = ProductoService.guardarParamRap('&lst_producto=' + lst + '&guardar=1');
        ajaxguardar.success(function (data) {
            Utilities.hiddePreloader();
            if (data.error != undefined) {
                Utilities.alertModal(data.error, 'danger');
            } else {
                Utilities.alertModal(data.success, 'success');
                var callback = ProductoService.paramRapIndex();

                Producto.actualizaCalcularPrecioRap();

            }
            Utilities.hiddePreloader();
        });
        ajaxguardar.error(function () {
            Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'danger');
            Utilities.hiddePreloader();
        })


    },

    /**
     * Despues que se guardan los cambios en parametrizacion rapida,
     * se manda a actualizar en el input de los % de utilidad y precio, el iva, ya que cuando se guarda,
     * no se refresca el valor en el keyup (precio_valor_ , utilidad_)
     */
    actualizaCalcularPrecioRap: function(){

        var  utilidad_producto_id='';
        var precio_valor_producto_id='';
        var onkeyup='';
        var split='';
        var newkeyup='';
        $.each( Producto.lst_producto, function( key, value ) {

            /**
             * recorro el arreglo de productos
             * y verifico que se haya seteado el valor en impuestos
             */
            if(value!=undefined && value['producto_id']!=undefined && value['impuestos']!=undefined){

                /**
                 * obtengo el id del select de utilidades, lo recorro.
                 */
                utilidad_producto_id='utilidad_'+ value['producto_id']+'_';
                $("#table input[id^="+utilidad_producto_id+"]").each(function () {
                    /**
                     * obtengo su keyup actual, lo recorro, y lo vuelvo a armar, en base a la variable
                     * impuestos_porcentaje, que es seteada cuando se hace el change del select de impuestos
                     */
                    onkeyup=$(this).attr('onkeyup')
                    split= onkeyup.split(",");
                    newkeyup='';
                    for (var i = 0; i < Object.keys(split).length; i++) {

                        if(i==5){
                            newkeyup+=value['impuestos_porcentaje']+',';
                        }else{
                            newkeyup+=split[i];
                            if(i<7){
                                newkeyup+=',';
                            }
                        }
                    }
                    $(this).attr('onkeyup',newkeyup)
                });

                precio_valor_producto_id='precio_valor_'+ value['producto_id']+'_';
                $("#table input[id^="+precio_valor_producto_id+"]").each(function () {
                    /**
                     * obtengo su keyup actual, lo recorro, y lo vuelvo a armar, en base a la variable
                     * impuestos_porcentaje, que es seteada cuando se hace el change del select de impuestos
                     */
                    onkeyup=$(this).attr('onkeyup')
                    split= onkeyup.split(",");
                    newkeyup='';
                    for (var i = 0; i < Object.keys(split).length; i++) {

                        if(i==5){
                            newkeyup+=value['impuestos_porcentaje']+',';
                        }else{
                            newkeyup+=split[i];
                            if(i<7){
                                newkeyup+=',';
                            }
                        }
                    }
                    $(this).attr('onkeyup',newkeyup)
                });
            }

        });


    },
    //verifica si los productos traidos tienen condigurado mal el contenido interno
    checkContInMal: function (data) {

        var encontro = false;
        if (data.contInternos.length > 0) {

            var continternos = data.contInternos;


            for (var i = 0; i < continternos.length; i++) {

                if (continternos[i].length == 1) {

                    if (continternos[i][0].unidades > 1) {
                        encontro = true;
                        $("#table")[0].tBodies[0].rows[i].style.color = "red";
                    }
                }

                if (continternos[i].length == 2) {

                    //pregunto si la caja tiene<2
                    if (continternos[i][0].unidades < 2) {
                        encontro = true;
                        $("#table")[0].tBodies[0].rows[i].style.color = "red";
                    }

                    //pregunto si la Unidad tiene >1
                    if (continternos[i][1].unidades > 1) {
                        encontro = true;
                        $("#table")[0].tBodies[0].rows[i].style.color = "red";
                    }
                }

                if (continternos[i].length == 3) {
                    //pregunto si la caja tiene lo mismo que blister
                    if (continternos[i][0].unidades == continternos[i][1].unidades) {
                        encontro = true;
                        $("#table")[0].tBodies[0].rows[i].style.color = "red";
                    }

                }

            }

            if (encontro == true) {
                Utilities.alertModal('Los productos en color rojo se les debe corregir el contenido interno', 'warning', 5000);
            }

        }

    },

    addproductogrupo: function () {  //el boton deagregar grupo al producto

        if ($("#nivel_grupo").val() == "") {
            Utilities.alertModal('<p>Debe ingresar al menos un nivel</p>', 'warning');
            return false;
        }

        if ($("#produto_grupo").val() == "") {
            Utilities.alertModal('<p>Debe ingresar al menos un grupo</p>', 'warning');
            return false;
        }

        var yaesta = false;
        for (var i = 0; i < Producto.gruposProducto.length; i++) {

            if (Producto.gruposProducto[i].id_grupo == $("#produto_grupo").val()
                && Producto.gruposProducto[i].id_nivel == $("#nivel_grupo").val()) {
                yaesta = true;
            }
        }

        if (yaesta == false) {
            var tamano = Producto.gruposProducto.length;
            Producto.gruposProducto[tamano] = new Array();
            Producto.gruposProducto[tamano]['id_grupo'] = "";
            Producto.gruposProducto[tamano]['id_nivel'] = "";
            Producto.gruposProducto[tamano]['id_grupo'] = $("#produto_grupo").val();
            Producto.gruposProducto[tamano]['id_nivel'] = $("#nivel_grupo").val();

            var html = '<tr id="trproductogrupo_'+$("#produto_grupo").val()+'_'+$("#nivel_grupo").val()+'">' +
                '<td>' + $("#produto_grupo option:selected").text() + ' <input type="hidden" name="input_grupo[]" ' +
                'value="'+$("#produto_grupo").val()+'" ></td>' +
                '<td>' + $("#nivel_grupo option:selected").text() + '<input type="hidden" name="input_nivelgrupo[]" ' +
                'value="'+$("#produto_grupo").val()+'" ></td>' +
                '<td><a href="#" ' +
                'onclick="Compra..borrarProductoGrupo(' + $("#produto_grupo").val() + ',' + $("#nivel_grupo").val() + ')" ' +
                ' style="width: 200px; margin: 0;" class="btn btn-danger"><i class="fa fa-trash-o"></i> Eliminar</a></td>' +
                '</tr>';
            $("#tbodytablegrupos").append(html);

        } else {
            Utilities.alertModal('<p>Ya ha seleccionado este grupo</p>', 'warning');
            return false;
        }
    },
    borrarProductoGrupo: function (grupoid,nivelid) { //cuandos e presiona el boton de eliminar el grupo asociado al producto

        var esta = false;
        for (var i = 0; i < Producto.gruposProducto.length; i++) {

            if (Producto.gruposProducto[i].id_grupo == grupoid
                && Producto.gruposProducto[i].id_nivel == nivelid) {
                esta = true;
                Producto.gruposProducto.splice(i, 1);
            }
        }

        if(esta==true){
            $("#trproductogrupo_"+grupoid+"_"+nivelid).remove();
        }

    }

}



