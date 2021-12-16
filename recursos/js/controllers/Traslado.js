var Traslado = {
    tablalista: null,
    lst_producto: new Array(),
    local1: '',
    local_anterior: "", /*esta variable es para colocarla cuando se cancele el reiniciar el formulario de traslado*/
    local_actual: "",
    unidades: new Array(), // son las todas las unidades
    unidadesEsteProducto: new Array(), // son las unidades disponibles para la tabla, de un productod seleccionado
    productosDetalle: new Array(), // son los propductos que fueron trasladados, solo los datos
    producto_seleccionadoid: '',
    total_minima: 0,
    cache: {},
    detalleTraslado: new Array(),
    inizializeDomCache: function () {
        this.cache.pagecontent = $('#page-content');
        this.cache.select_chosen = $('.select-chosen');
    },
    init: function (unidades, detalle,productosDetalle) {

        this.inizializeDomCache();
        this.unidades = unidades;
        this.thead();
        Traslado.tablalista = null;
        this.lst_producto = new Array();
        this.total_minima = 0;
        this.producto_seleccionadoid = '';
        this.detalleTraslado = new Array();
        this.unidadesEsteProducto = new Array();
        this.productosDetalle= new Array();
        this.events();
        this.teclado();
        this.getSecondLocal();
        Traslado.local_anterior = $("#localform1").val();
        Traslado.local_actual = $("#localform1").val();


        if (detalle!=undefined && detalle.length > 0) {
            Traslado.detalleTraslado = detalle;
            Traslado.productosDetalle=productosDetalle;
            Traslado.prepararDetalle();
        }else{
            Traslado.makeTableKey(true);
        }

    },
    thead: function (){

        $("#thead_tablaresult").html('');

        var tr='<tr>';
        tr+='<th>C&oacute;digo</th>';
        tr+='<th>Nombre</th>';

        $.each(Traslado.unidades, function (key, value) {
            tr+='<th>'+value.nombre_unidad+'</th>';
        });

        tr+='</tr>';
        $("#thead_tablaresult").html(tr);

    },
    prepararDetalle: function () {

        var arr=new Array();
        var cont=0;
        for (var i = 0; i < Traslado.productosDetalle.length; i++) {

            cont=0;
            arr=new Array();

            for (var j = 0; j < Traslado.detalleTraslado.length; j++) {

                if(Traslado.detalleTraslado[j].id_producto==Traslado.productosDetalle[i].producto_id){

                    arr[cont]=  Traslado.detalleTraslado[j];
                    cont++;
                }
            }

            Traslado.unidadesEsteProducto = new Array();
            Traslado.unidadesEsteProducto=arr;
            Traslado.addproductoTotablenormal(Traslado.productosDetalle[i]);
            Traslado.addProductoToArray(Traslado.productosDetalle[i]);

        }

    },
    teclado: function () {

        $('body').off('keydown');
        $('body').on('keydown', function (e) {
            if (e.keyCode == 13) {

                if ($("#seleccionunidades").is(":visible")) {
                    e.preventDefault();
                    Traslado.agregar();
                }
            }

            if (e.keyCode == 46) {

                e.preventDefault();
                Traslado.deleteproducto();
            }

            if (e.keyCode == 40) {
                if ($("#seleccionunidades").is(":visible")) {

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
    events: function () {

        $("select").chosen({'width': '100%'})


        Traslado.cache.select_chosen.chosen({
            width: "100%"
        });

        //  $("#fecha_ingreso").datepicker();
        $('.input_datepicker').datepicker({weekStart: 1, format: 'dd-mm-yyyy'});

        $(".buscar").on("change", function () {
            Traslado.cambiarlocal();
            Traslado.buscarTraslados();
        });

        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                Traslado.buscarproductos($(this).val());
            }
        });
        $('#seleccionunidades').on('shown.bs.modal', function (e) {
            TablesDatatables.init(0, 'tablaproductos', 'asc');
            $("#preciostbody").selectable({
                stop: function () {
                    var id = $("#preciostbody tr.ui-selected").attr('id');
                }
            });
            setTimeout(function () {
                Utilities.selectSelectableElement(jQuery("#preciostbody"), jQuery("#preciostbody").children(":eq(0)"));
            }, 1000);
        });

    },
    makeTableKey: function (init) {

        Traslado.tablalista = $("#tablaresult").DataTable({
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

        Traslado.keyFocusEvent();

        if (init) {
            Traslado.tablalista.cell(0, 0).focus();

        }
    },
    keyFocusEvent: function () {
        // Inline editing on tab focus
        Traslado.tablalista.off('key-focus');
        Traslado.tablalista.on('key-focus', function (e, datatable, cell) {
            var rowData = datatable.row(cell.index().row).data();
            var colData = cell.data();
            var objectCell = $($.parseHTML(colData));
            var elemento = $("#" + objectCell.attr('id'));

            if ($("#" + objectCell.attr('id')).length != 0) {
                //var offset = elemento.offset()
                //$(".inner_table").animate({scrollLeft: offset.left}, 0);
                $("#" + objectCell.attr('id')).focus();
            }

            var rowNode = datatable.row(cell.index().row).node();

            var id = $(rowNode).attr("data-producto_id");

            if (Traslado.validar_mismo_trespecial(id) == true) {
                InventarioService.buscarExistenciaProducto(Traslado.unidades,id,null,null,$("#localform1").val());
            }
        });
    },

    deleteproducto: function () {
        //borra un producto de lst_producto
        //estas variables se llenan cuando se hace click en algun tr
        var tr = Traslado.tablalista.row('#lista_' + Traslado.producto_seleccionadoid)

        tr.remove().draw();

        var lista_vieja = Traslado.lst_producto;
        var lista_nueva = new Array();

        Traslado.lst_producto = new Array();

        jQuery.each(lista_vieja, function (i, value) {

            if (value["producto_id"] === Traslado.producto_seleccionadoid) {

            } else {
                //almaceno los que no estoy eliminando
                var retorno = lista_vieja[i];
                lista_nueva.push(retorno);
            }
        });

        Traslado.lst_producto = lista_nueva;
        Traslado.tablalista.cell(0, 0).focus();

    },

    grabarTrEspecial: function () {
        //declara a los tr que tienenla clase trespecial para buscar la informacion del producto al cual
        //se le hace click
        //data-producto_id aqui siemore va a venir con algo

        $("#columnas tr").on('click', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-producto_id');

            if (Traslado.validar_mismo_trespecial(id) == true) {
                InventarioService.buscarExistenciaProducto(Traslado.unidades,id,null,null,$("#localform1").val());
            }
        });
    },
    validar_mismo_trespecial: function (id) {
        //esta funcion valida si el tr sobre esta el click, es el mismo,
        //para no tener que estar consultando los datos del producto cada vez que se hace click sobre el mismo tr

        if (Traslado.producto_seleccionadoid == id) {
            return false;
        }
        this.producto_seleccionadoid = id;

        return true;
    },

    mostrar_advertencia: function () {

        $('#advertencia').modal('show');

    },
    cambiarlocal: function () {

        if (Traslado.lst_producto.length > 0) {

            this.mostrar_advertencia();
        } else {
            Traslado.local_actual = $("#localform1").val();
            this.getSecondLocal();
        }
    }
    ,
    getSecondLocal: function () { //verifica que no este en el segundo local, el primer local, seleccionado
        Traslado.local1 = $("#localform1");
        var local2 = $("#localform2").val();
        $("#localform2").html(Traslado.local1.html());
        $("#localform2").val(local2);
        //para que pueda quedar TODOS en ambos select
        if ($("#localform1").val() != "TODOS") {

            $("#localform2 option[value='" + Traslado.local1.val() + "']").remove();
        }

        $("#localform2").trigger("chosen:updated");

    },
    form: function (traslado_id) {
        $("#advertencia").modal('hide');

        if (traslado_id == false || traslado_id == undefined) {

            traslado_id = false;

        }

        Utilities.showPreloader();
        var ajax = TrasladoService.form(traslado_id, $("#localform1").val());
        ajax.success(function (data) {
            Utilities.hiddePreloader();

            if (traslado_id != false) {

                $("#modal_body_traslado").html('');
                $("#modal_body_traslado").html(data);
                $("#show_id_traslado").html('');
                $("#show_id_traslado").html('Detalle del Traslado '+traslado_id);

                $('#traslado_modal').modal('show');
            } else {
                Traslado.cache.pagecontent.html(data);

            }

        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });

    }
    ,
    buscarproductos: function (valor) {
        Utilities.showPreloader();

        //busco los productos que tengan stock
        $("#preciostbody").html('');
        var buscar = {};
        buscar.value = valor;
        var ajaxbuscar = ProductoService.specialSearch({
            'search': buscar,
            'local': $("#localform1").val(),
            'is_paquete': 0,
            'is_obsequio': 0,
            'is_prepack': 0,
            'constock':true
        }, baseurl, sessionStorage.api_key);
        ajaxbuscar.success(function (data) {

            var html = '';
            jQuery.each(data.productos, function (i, value) {

                    var comisiona = '';
                    if (value['producto_comision'] != null && value['producto_comision'] != '0') {
                        comisiona = 'comisiona';
                    }
                    html += '<tr id="' + value['producto_id'] + '" tabindex="' + i + '" ' +
                        'data-name="' + value['producto_nombre'] + '"' +
                        ' data-producto_codigo_interno="' + value['producto_codigo_interno'] + '"  class="' + comisiona + '">';
                    html += '<td>' + value['producto_id'] + '</td>';
                    html += '<td>' + value['producto_codigo_interno'] + '</td>';
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
                    jQuery.each(Traslado.unidades, function (j, unidad) {
                        var canuni = 0;
                        jQuery.each(value.existencia, function (h, unidad2) {
                            if (unidad2.id_unidad == unidad.id_unidad) {
                                canuni = unidad2['cantidad'];
                            }
                        });
                        html += '<td>' + canuni + '</td>';
                    });
                    html += '</tr>';


            });
            $("#preciostbody").html(html);
            Utilities.hiddePreloader();
            $("#seleccionunidades").modal('show');
        });
    },
    agregar: function () {
        var id_producto = $("#preciostbody tr.ui-selected").attr('id');
        var codigo_interno = $("#preciostbody tr.ui-selected").attr('data-producto_codigo_interno');
        var nombre = $("#preciostbody tr.ui-selected").attr('data-name');

        var existe = Traslado.existe_producto(id_producto);

        if (existe == 'false') {

            Traslado.producto_seleccionadoid = id_producto;

            var producto_selected = {};
            producto_selected.producto_id = id_producto;
            producto_selected.producto_nombre = nombre;
            producto_selected.producto_codigo_interno = codigo_interno;

            Utilities.showPreloader();

            var ajax = UnidadesService.getUnidadesByProd(id_producto);
            ajax.success(function (data) {

                //le asigno las unidades de este producto a esta variable
                Traslado.unidadesEsteProducto = new Array();
                Traslado.unidadesEsteProducto = data;

                Traslado.addproductoTotable(producto_selected);
                Traslado.addProductoToArray(producto_selected);
                Traslado.appendtrvacio();
                Traslado.tablalista.cell(Traslado.tablalista.rows().count() - 2, 2).focus();
                $('#seleccionunidades').modal('toggle');
                Traslado.grabarTrEspecial();
                InventarioService.buscarExistenciaProducto(Traslado.unidades,id_producto,null,null,$("#localform1").val());
                Utilities.hiddePreloader();
            });
            ajax.error(function () {
                Utilities.alertModal('Ocurrió un error', 'warning');
                Utilities.hiddePreloader();
            })


        } else {
            Utilities.alertModal('El producto selecionado ya fué agregado', 'warning');
            return false;
        }


    },
    addProductoToArray: function (existe_producto) {

        Traslado.lst_producto.push(existe_producto);

    },
    verifiTieneUnidad: function (unidadenviada) {  //verifica si son iguales las unidades


        var unidadComparar = '';
        var cantidad = '';
        var tienelaunidad=false;
        var arreglo = {
            tiene: tienelaunidad,
            cantidad: cantidad
        };

        var isDetalle=false;
        jQuery.each(Traslado.unidadesEsteProducto, function (j, value) {

            unidadComparar = '';

            //si pasa en la primera condicion, quiere decir que estoy pasando el detalle del traslado,
            //ya que en la tabla, el campo de las unidades, se llama unidad_id,
            //si pasa en la 2da quiere decir que estoy en un nuevo traslado, y que unidadesEsteProducto viene de unidades_has_producto

            if (value.unidad_id != undefined) {

                unidadComparar = value.unidad_id;
                isDetalle=true;
            } else {
                unidadComparar = value.id_unidad;
            }

            if (unidadenviada == unidadComparar) {
                arreglo.tiene = true;

                //si es detalle de traslado, guardo la cantidad
                if(isDetalle==true){
                    arreglo.cantidad = value.cantidad;
                }
            }
        });

        return arreglo;

    },
    addproductoTotablenormal: function (producto_selected,cont){

        var  tr = '<tr id="lista_' + producto_selected.producto_id+'" tabindex="'+cont+'" ' +
            'data-producto_id="'+producto_selected.producto_id+'" >';

       var  nombre = producto_selected.producto_nombre;

        tr+='<td>'+producto_selected.producto_codigo_interno + '<input type="hidden" name="id_producto[]" ' +
            'value="' + producto_selected.producto_id + '"></td>';

        tr+='<td>'+nombre + '<input type="hidden" name="nombre_producto[' + producto_selected.producto_id + ']" ' +
            'value="' + nombre + '"></td>';

        var cantidad = '';
        var tienelaunidad = {
            tiene: false,
            cantidad: cantidad
        };

        var readonly = '';
        var disabled = 'disabled';
        jQuery.each(Traslado.unidades, function (i, value) {
            tienelaunidad = {
                tiene: false,
                cantidad: ''
            };

            //verifico si tiene la unidad disponible. Es un objeto porque si se esta mostrando el detalle, entonces retorna la cantidad
            tienelaunidad = Traslado.verifiTieneUnidad(value.id_unidad);

            readonly = '';

            if (!tienelaunidad.tiene) {
                readonly = 'readonly';

            }

            tr+='<td><input ' + readonly;

            if(Traslado.detalleTraslado.length>0){
                tr+=' disabled ';
            }
            tr+='  type="text" class="form-control" onkeydown="return soloNumeros(event);" ';
            tr+=' onkeyup="Traslado.validaStock(this,event,' + producto_selected.producto_id + ',' + value.id_unidad + ')" ';
            tr+=' id="cantidad_' + producto_selected.producto_id + '_' + value.id_unidad + '"' +
                ' name="cantidad_' + producto_selected.producto_id + '[' + value.id_unidad + ']" ' +
                'value="'+tienelaunidad.cantidad+'" >';
            tr+='<input ' + readonly + '  type="hidden" class="form-control" name="unidad_' + producto_selected.producto_id + '[]"' +
                ' value="' + value.id_unidad + '"></td>';

        });

        $('#trvacio').remove();

        $("#columnas").append(tr);

    },
    addproductoTotable: function (producto_selected) {

        var newrow = {};

        var count = 1;

        var nombre = producto_selected.producto_nombre;

        newrow[0] = producto_selected.producto_codigo_interno + '<input type="hidden" name="id_producto[]" value="' + producto_selected.producto_id + '">';
        //class="center" width="10%"

        newrow[count] = nombre + '<input type="hidden" name="nombre_producto[' + producto_selected.producto_id + ']" value="' + nombre + '">';
        count++;

        var cantidad = '';
        var tienelaunidad = {
            tiene: false,
            cantidad: cantidad
        };

        var readonly = '';
        var disabled = 'disabled';
        jQuery.each(Traslado.unidades, function (i, value) {
            tienelaunidad = {
                tiene: false,
                cantidad: ''
            };

            //verifico si tiene la unidad disponible. Es un objeto porque si se esta mostrando el detalle, entonces retorna la cantidad
            tienelaunidad = Traslado.verifiTieneUnidad(value.id_unidad);

            readonly = '';

            if (!tienelaunidad.tiene) {
                readonly = 'readonly';

            }

            newrow[count] = '<input ' + readonly;

            if(Traslado.detalleTraslado.length>0){
                newrow[count] += ' disabled ';
            }
            newrow[count] += '  type="text" class="form-control" onkeydown="return soloNumeros(event);" ';
            newrow[count] += ' onkeyup="Traslado.validaStock(this,event,' + producto_selected.producto_id + ',' + value.id_unidad + ')" ';
            newrow[count] += ' id="cantidad_' + producto_selected.producto_id + '_' + value.id_unidad + '"' +
                ' name="cantidad_' + producto_selected.producto_id + '[' + value.id_unidad + ']" value="'+tienelaunidad.cantidad+'" >';
            //'onblur="Traslado.validaStock(' + producto_selected.producto_id + ',' + value.id_unidad + ',this);">';
            newrow[count] += '<input ' + readonly + '  type="hidden" class="form-control" name="unidad_' + producto_selected.producto_id + '[]" value="' + value.id_unidad + '">';
            count++;

        });


        var trvacio = Traslado.tablalista.row('#trvacio');
        trvacio.remove().draw();


        var rowNode = Traslado.tablalista.row.add(newrow).draw().node();
        $(rowNode).attr("id", 'lista_' + producto_selected.producto_id);
        $(rowNode).attr("tabindex", Traslado.tablalista.rows().count());
        $(rowNode).attr("data-producto_id", producto_selected.producto_id);


    },
    appendtrvacio: function () {
        var newrow = {};
        var count = 0;

        newrow[count] = '<input type="text" class="form-control inputsearchproduct" id="inputsearchproduct">';
        count++;
        newrow[count] = '';
        count++;

        jQuery.each(Traslado.unidades, function (i, value) {
            newrow[count] = '<input readonly type="text" id="" class="form-control" value="0" >';
            count++;

        });
        newrow[count] = '';

        var rowNode = Traslado.tablalista.row.add(newrow).draw().node();
        $(rowNode).attr("id", 'trvacio');
        $(rowNode).attr("tabindex", Traslado.tablalista.rows().count());
        $(".inputsearchproduct").on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                Traslado.buscarproductos($(this).val());
            }
        });
    },
    existe_producto: function (producto_id) {
        //valida si ya esta agregado el producto a la compra
        var existe = 'false';

        jQuery.each(Traslado.lst_producto, function (i, value) {

            if (value["producto_id"] === producto_id) {
                existe = i;
            }
        });

        return existe;
    },

    validaStock: function (esto,event, producto_id) { //valida el stock del producto

        if (Traslado.validaTecla(event)) {

            var precios = ProductoService.getProducto(producto_id); //valido si el producto existe

            precios.success(function (data) {

                    if (data.control_inven == '1') {

                        //declaro el total en 0
                        Traslado.total_minima = 0;
                        var Ajaxexistecia = InventarioService.soloStock(producto_id, $("#localform1").val());
                        var unidades_producto = new Array();

                        //busco el stock de este producto y sus unidades
                        Ajaxexistecia.success(function (datastock) {
                            Traslado.total_minima = datastock.stockMinimas;  // el total en stock en minimas
                            unidades_producto = datastock.unidades_producto;  //las unidades de este producto
                        });


                       var totalentabla= Traslado.totalMinimaEnTabla(unidades_producto,producto_id,Traslado.unidades);

                        //pregunto si el total que tengo ingresado en los campos, es mayor al total en stock del producto
                        if (totalentabla > Traslado.total_minima) {

                            Utilities.alertModal('<h4>El total de unidades ingresado es mayor al stock actual!</h4>', 'warning');
                            $("#guardar").prop('disabled', true);
                            //lo coloreo de rojo
                            Traslado.tablalista.row('#lista_' + Traslado.producto_seleccionadoid).nodes().to$().find('td')
                                .each(function () {

                                    $(this).css('color', '#d84545')

                                });
                            Traslado.irRestando(esto,totalentabla,unidades_producto,producto_id);


                        } else {

                            $("#guardar").prop('disabled', false);
                            //le quito el color rojo
                            Traslado.tablalista.row('#lista_' + Traslado.producto_seleccionadoid).nodes().to$().find('td')
                                .each(function () {

                                    $(this).css('color', '#797979  !important')

                                });
                        }
                    }
                }
            )
        }

    },
    totalMinimaEnTabla: function (unidades_producto,producto_id,unidadesensistemas){
        //calcula el total de unidades minimas que hay en la tabla

        var totalentabla = 0;
        var existenciaminima = 0;
        var elemento = '';

        if (unidades_producto.length > 0) {  //valido si tiene unidades configuradas

            //recorro todas las unidades ingresadas en la tabla, para este producto
            $.each(unidadesensistemas, function (key, value) {
                elemento = $("#cantidad_" + producto_id + "_" + value.id_unidad);

                if (elemento.val() != "" && elemento.val() != 0 && elemento.val() > 0) {
                    existenciaminima = 0;

                    //aqui convierto las cantidades colocadas en los campos, a unidades minimas
                    existenciaminima = InventarioService.converUnidadesMinimas(unidades_producto, value.id_unidad,
                        elemento.val());

                    //los voy sumando, para luego comparar contra el total del stock
                    totalentabla = parseInt(totalentabla) + parseInt(existenciaminima);

                }

            });
        }

        return totalentabla;
    },
    irRestando: function (esto, totalentabla,unidades_producto,producto_id) {


        //"esto" es el input que estoy escribiendo,
        // totalentabla es el total de unidades minimas que hay hasta los momentos en todos los input
        //aqui valido para que no deje escrita una cantidad mayor al stock en el input
        var talvalor = "";
        while (totalentabla> Traslado.total_minima) {
            talvalor = $(esto).val();
            if (parseFloat(totalentabla) > parseFloat(Traslado.total_minima)) {
                $(esto).val(talvalor.substr(0, talvalor.length - 1));
                totalentabla= Traslado.totalMinimaEnTabla(unidades_producto,producto_id,Traslado.unidades);

            }else{
                break;
            }
        }

        $("#guardar").prop('disabled', false);
        //le quito el color rojo
        Traslado.tablalista.row('#lista_' + Traslado.producto_seleccionadoid).nodes().to$().find('td')
            .each(function () {

                $(this).css('color', '#797979  !important')

            });

        $(esto).focus();
    },
    validaTecla: function (event) { //esto es para que al moverse sobre la tabla no busque calcule el total de
        //unidades minimas del producto sobre el cual se esta seleccionado

        var key = window.event ? event.keyCode : event.which;

        //|| event.keyCode == 38 || event.keyCode == 40 on flecha arriba y flecha abajo
        if (event.keyCode == 8 || event.keyCode == 46) {

        } else if ((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {

        } else return false;

        return true;
    },
    guardar: function () {

        Utilities.showPreloader();
        var miJSON = JSON.stringify(Traslado.lst_producto);
        console.log('Traslado.lst_producto', Traslado.lst_producto);

        var ajaxguardar = TrasladoService.guardar(miJSON);

        ajaxguardar.success(function (data) {
            if (data.success && data.error == undefined) {

                Utilities.alertModal('<h4>Se ha registrado el traslado</h4> Número : ' + data.id, 'success', true);
                Traslado.form(false);
                Utilities.hiddePreloader();
            }
            else {
                Utilities.hiddePreloader();
                Utilities.alertModal('<h4>Ha ocurrido un error </h4><p>' + data.error + '</p>', 'danger', true)

            }
        });
        ajaxguardar.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4>Ha ocurrido un error </h4> <p>Intente nuevamente</p>', 'danger', true);
            return false;
        });
    },
    guardarLocal: function () {
        Traslado.local_actual = $("#localform1").val();
    },
    cerrartransferir_advertencia: function () {
        Traslado.local_anterior = Traslado.local_actual;

        $("#localform1").val(Traslado.local_anterior);
        $("#localform1").trigger("chosen:updated");
        $('#advertencia').modal('hide');
    },

    buscarTraslados: function () {  //busca los traslados, segun los parametros que se le pasen
        Utilities.showPreloader();
        var ajax = TrasladoService.buscarTraslados();

        ajax.success(function (data) {
            $("#tbodyproductos").html('');
            if (data.traslados.length > 0) {

                var traslados = data.traslados;

                for (var i = 0; i < traslados.length; i++) {

                    Traslado.mostrarSoloTraslados(traslados[i]);

                }
            }

            Utilities.hiddePreloader();
        });
        ajax.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4>Ha ocurrido un error </h4> <p>Intente nuevamente</p>', 'danger', true);
            return false;
        });


    },

    mostrarSoloTraslados: function (traslados) {  //lista solo los traslados en en el index

        var tr = '<tr>';
        tr += '<td>' + traslados.id_traslado + '</td>';
        tr += '<td>' + traslados.fecha_formateada + '</td>'
        tr += '<td>' + traslados.username + '</td>'
        tr += '<td>' + traslados.cant_productos + '</td>';
        tr += '<td> <div class="btn-group"> <a class="btn btn-default btn-default btn-default" data-toggle="tooltip" ' +
            'title="Ver Detalle" data-original-title="Ver Detalle" ' +
            'href="#" onclick="Traslado.ver(' + traslados.id_traslado + ');">  ' +
            '<i class="fa fa-search"></i> </a> ' +
            ' </div></td>' +
            '</tr>';

        $("#tbodyproductos").append(tr);

    },

    ver: function (traslado_id) {

        Traslado.form(traslado_id);
    },

    cerrarMostrarDetalle: function () {

        $('#traslado_modal').modal('hide');
        $("#modal_body_traslado").html('');

    },

}