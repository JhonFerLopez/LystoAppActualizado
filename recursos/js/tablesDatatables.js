/*
 *  Document   : tablesDatatables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables Datatables page
 */

//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//


// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register('clearPipeline()', function () {
    return this.iterator('table', function (settings) {
        settings.clearCache = true;
    });
});

var DatatablesSettings = {

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
        "zeroRecords": "No se encontraron resultados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "aria": {
            "sortAscending": ": activar ordenar columnas ascendente",
            "sortDescending": ": activar ordenar columnas descendente"
        }


    },
    "buttons": [
        {
            extend: 'pdfHtml5',
            extension: '.pdf',
            filename: 'ReporteSid',
            title: 'SID - Sistema Integral de Droguerias - ' + empresa_nombre + ' NIT ' + nit,
            footer: true,
            exportOptions: {
                stripNewlines: false,
                format: {
                    body: function (data, column, row, node) {

                        // aqui se pregunta si el primer elemento quele sigue despues del TD es un select
                        //entonces tomo la primera opcion seleccionada, si es que es distinta de "seleccione",
                        //esto se hace porque tomaba todo el texto de todos los options del select
                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="select" || $(node)[0].firstChild.localName=="SELECT"))
                        {
                            if($(data).find("option:selected").text()=="Seleccione" ||
                                $(data).find("option:selected").text()=="SELECCIONE"){
                                return "";
                            }
                            return $(data).find("option:selected").text();
                        }

                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="input" || $(node)[0].firstChild.localName=="INPUT"))
                        {

                            if($(node)[0].firstChild.type=="text"){
                                return $(node)[0].firstChild.value;
                            }else if ($(node)[0].firstChild.type=="checkbox"){

                                if($(node)[0].firstChild.checked==true){
                                    return 'SI';
                                }else{
                                    return 'NO';
                                }
                            }
                        }

                        //aparentemente esto es cuando hay input type text en la tabla, y retorna el valor
                        if($(node)[0].firstChild.nodeType!=undefined &&
                            $(node)[0].firstChild.nodeType==3 ){
                            if( $(node)[0].firstChild.nextSibling!=undefined){
                                return $(node)[0].firstChild.nextSibling.defaultValue;
                            }

                        }

                        data = $('<p>' + data + '</p>').text();


                        return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                    }
                }
            },
            orientation: 'landscape',
            pageSize: 'LEGAL',
            /*customize: function (doc) {
                doc.styles.title = {

                    alignment: 'center'
                }
            }*/

        },
        {
            extend: 'csv',
            extension: '.csv',
            filename: 'Reporte',
            footer: true,
            exportOptions: {
                format: {
                    body: function (data, column, row, node) {
                        // aqui se pregunta si el primer elemento quele sigue despues del TD es un select
                        //entonces tomo la primera opcion seleccionada, si es que es distinta de "seleccione",
                        //esto se hace porque tomaba todo el texto de todos los options del select
                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="select" || $(node)[0].firstChild.localName=="SELECT"))
                        {
                            if($(data).find("option:selected").text()=="Seleccione" ||
                                $(data).find("option:selected").text()=="SELECCIONE"){
                                return "";
                            }
                            return $(data).find("option:selected").text();
                        }

                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="input" || $(node)[0].firstChild.localName=="INPUT"))
                        {
                            if($(node)[0].firstChild.type=="text"){
                                return $(node)[0].firstChild.value;
                            }else if ($(node)[0].firstChild.type=="checkbox"){

                                if($(node)[0].firstChild.checked==true){
                                    return 'SI';
                                }else{
                                    return 'NO';
                                }
                            }
                        }
                        data = $('<p>' + data + '</p>').text();
                        return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                    }
                }
            }

        },
        {
            extend: 'excelHtml5',
            extension: '.xlsx',
            filename: 'Reporte',
            footer: true,
            customize: function (xlsx) {

                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                var downrows = 3;
                var clRow = $('row', sheet);
                //update Row
                clRow.each(function () {
                    var attr = $(this).attr('r');
                    var ind = parseInt(attr);
                    ind = ind + downrows;
                    $(this).attr("r", ind);
                });

                // Update  row > c
                $('row c ', sheet).each(function () {
                    var attr = $(this).attr('r');
                    var pre = attr.substring(0, 1);
                    var ind = parseInt(attr.substring(1, attr.length));
                    ind = ind + downrows;
                    $(this).attr("r", pre + ind);
                });

                function Addrow(index, data) {
                    msg = '<row r="' + index + '">'
                    for (i = 0; i < data.length; i++) {
                        var key = data[i].k;
                        var value = data[i].v;
                        msg += '<c t="inlineStr" r="' + key + index + '" s="42">';
                        msg += '<is>';
                        msg += '<t>' + value + '</t>';
                        msg += '</is>';
                        msg += '</c>';
                    }
                    msg += '</row>';
                    return msg;
                }

                //insert
                var r1 = Addrow(1, [{k: 'A', v: empresa_nombre}, {k: 'B', v: ''}, {k: 'C', v: ''}]);
                var r2 = Addrow(2, [{k: 'A', v: 'NIT ' + nit}, {k: 'B', v: 'ColB'}, {k: 'C', v: ''}]);


                sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + sheet.childNodes[0].childNodes[1].innerHTML;
            },
            exportOptions: {
                stripNewlines: true,
                columns: ':visible',
                format: {
                    body: function (data, row, column, node) {

                        // aqui se pregunta si el primer elemento quele sigue despues del TD es un select
                        //entonces tomo la primera opcion seleccionada, si es que es distinta de "seleccione",
                        //esto se hace porque tomaba todo el texto de todos los options del select
                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="select" || $(node)[0].firstChild.localName=="SELECT"))
                        {
                            if($(data).find("option:selected").text()=="Seleccione" ||
                                $(data).find("option:selected").text()=="SELECCIONE"){
                                return "";
                            }
                            return $(data).find("option:selected").text();
                        }

                        if ($(node)[0].firstChild && $(node)[0].firstChild.localName &&
                            ($(node)[0].firstChild.localName=="input" || $(node)[0].firstChild.localName=="INPUT"))
                        {
                            if($(node)[0].firstChild.type=="text"){
                                return $(node)[0].firstChild.value;
                            }else if ($(node)[0].firstChild.type=="checkbox"){

                                if($(node)[0].firstChild.checked==true){
                                    return 'SI';
                                }else{
                                    return 'NO';
                                }
                            }
                        }
                        data = $('<p>' + data + '</p>').text();
                        return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                    },
                    footer: function (data, row, column, node) {
                        // Strip $ from salary column to make it numeric
                        data = $('<p>' + data + '</p>').text();
                        return $.isNumeric(data.replace(',', '.')) ? data.replace(',', '.') : data;
                    }
                }
            }
        },


        'print',
        'copyHtml5'
    ],
    "dom": '<"row"<"pull-left"f ><"pull-right"l>>rt<"row"<"pull-left"i><"pull-right"p>><"row"<"pull-left"B>>',
};

var TablesDatatables = function () {

    return {
        //columnDefs define el ancho de las columnas, por defecto, se le dice que la 0 tiene 2 %
        init: function (orderby, datatable, order, title, columnDefs=[{width: '2%', targets: 0}],focusoncomplete=true ) {

            var botones_mstrar = DatatablesSettings.buttons;

            var oder_col = orderby || 0;
            var order_order = order || 'desc';


            if (title != undefined) {
                botones_mstrar[0].title = title;
                botones_mstrar[0].filename = title;
            }
            /* Initialize Datatables */

            if (datatable) {
                // console.log(datatable);
                datatable_elemnt = $('#' + datatable);
                //console.log(datatable_elemnt);
            }
            else {
                datatable_elemnt = $('.dataTable');
            }
            var table = datatable_elemnt.DataTable({
                    retrieve: true,

                    columnDefs: columnDefs,
                    "iDisplayLength": 20,
                    fixedColumns: false,
                    fixedHeader: {
                        header: true,
                        footer: false
                    },
                    "bStateSave":false,
                    "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],
                    "order": [[oder_col, order_order]],
                    "scrollY": "300px",

                    "scrollX": true,
                    "scrollCollapse": true,
                    "dom": DatatablesSettings.dom,
                    buttons: botones_mstrar,
                    "language": DatatablesSettings.language,
                    "fnInitComplete": function (data,json) {
                    	if(focusoncomplete==true){
							setTimeout(function () {
								$('div.dataTables_filter input').focus();
							}, 5);
						}
                    },

                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();

                    var i = 0;
                    var sumar=false;
                    api.columns().every(function (index) {
                       // console.log('index',index)

                        decimales=2;
                        /*si tiene el atributo sumcuantosdecimales, se le dice cuantos decimales va a sumar, por defecto esta en 2,
                         * puede venir en 0 para que no los sume */
                        sumar=false;
                        /*en lista de ventas, y me imagino que en algunas partes mas, no hace falta sumar algunas filas, y menos
                         * si son textos, por lo tanto, en el <td> de cada registro, se debe indicar con un atributo data:
                         * data-sumar="true" para saber si aqui se va a sumar o no*/

                        api.column(index).nodes().toArray().map(function(node) {
                            if($(node).attr('data-sumar')!=undefined && $(node).attr('data-sumar')=="true"){
                                sumar=true;
                            }

                            if($(node).attr('data-sumcuantosdecimales')!=undefined){
                                decimales=$(node).attr('data-sumcuantosdecimales');
                            }
                        });

                        if (i > 0 && sumar==true) {

                            var sum = this
                                .data()
                                .reduce(function (a, b) {

                                    var x = parseFloat(a) || 0;

                                    if (isNaN(x)) {
                                        x = 0;
                                    }
                                    //   console.log(x);
                                    // console.log(b);
                                    if (b != null) {
                                        if (isNaN(b)) {
                                            b = b.replace(/[,.]/g, function (m) {
                                                // m is the match found in the string
                                                // If `,` is matched return `.`, if `.` matched return `,`
                                                return m === ',' ? '.' : '';
                                            });
                                        }

                                        // console.log(b);
                                        // b=parseFloat(b.replace('.','').replace(',','.'));
                                        var y = parseFloat(b) || 0;


                                        //  console.log(x + y);
                                        return x + y;
                                    }
                                }, 0);

                           // console.log(sum);
                            if (sum != undefined) {

                                $(this.footer()).html(sum.toLocaleString('de-DE', {
                                    maximumFractionDigits: decimales,
                                    minimumFractionDigits: decimales,
                                }));
                            }

                        }
                        i++;


                    });


                }

                })
            ;
            /* Add placeholder attribute to the search input */
            // $('.dataTables_filter input').attr('placeholder', 'Buscar');

            return table;
        }
    };
}();


var NoScrollTable = function () {

    return {
        init: function (orderby, datatable, order) {
            var oder_col = orderby || 0;
            var order_order = order || 'desc';


            /* Initialize Datatables */

            if (datatable) {
                // console.log(datatable);
                datatable_elemnt = $('#' + datatable);
                //console.log(datatable_elemnt);
            }
            else {
                datatable_elemnt = $('.dataTable');
            }
            var table = datatable_elemnt.DataTable({
                    retrieve: true,

                    columnDefs: [
                        {width: '2%', targets: 0}
                    ],
                    "iDisplayLength": 20,
                    fixedColumns: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    "bStateSave":false,
                    "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],
                    "order": [[oder_col, order_order]],
                    //"scrollY": "300px",
                    //"scrollX": true,
                    // "scrollCollapse": true,
                    "dom": DatatablesSettings.dom,
                    buttons: DatatablesSettings.buttons,
                    "language": DatatablesSettings.language,
                    "fnInitComplete": function () {

                        setTimeout(function () {
                            $('div.dataTables_filter input').focus();
                        }, 5);


                    },
                })
            ;
            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Presiones ENTER para buscar..');


        }
    };
}();

var TablesDatatablesLazzy = function () {

    return {
        init: function (url, order, datatable, data, title, fnInitComplete, onkeyfocusCall,
                        onkeyfocusAfter, buttons, disableBasicfunctions, enableSearch, scrollY,focusoncomplete=true,
						columnDefs=[{
							"defaultContent": "-",
							"targets": "_all"
						}]) {


            var botones_mstrar = DatatablesSettings.buttons;

            if (title != undefined && title != false) {
                botones_mstrar[0].title = title;
                botones_mstrar[0].filename = title;
            }

            if (enableSearch == undefined || enableSearch===true) {
                enableSearch = true;
            }


            if (buttons != undefined && buttons!==false) {
                botones_mstrar = buttons;

            }



            /*onkeyfocusCall para cuando se haga el keyfocus valide si es el mismo producto sobre el cual estaba anteriormente,
             * para no hacer que llamar al mismo producto cada vez que se pase de td*/

            /*onkeyfocusAfter si pasa la validacion del keyfocus, envio a esta funcion el producto_id para  hacer lo que se quiera,
             * ejemplo, colocar la existencia del producto en pantalla etc. (vease AjusteInventario.organizarTodosProductos)*/

            var oder_col = order || 0;
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();
            /* Initialize Datatables */
            if (datatable) {
                datatable_elemnt = $('#' + datatable);
            }
            else {
                datatable_elemnt = $('.dataTable');
            }
            var tableoptions = {
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "data": function (d) {
                        d.data = data
                    }
                },
                rowId: 'DT_RowId',
                "bStateSave":false,

                "columnDefs":columnDefs,
                retrieve: false,
                "aLengthMenu": disableBasicfunctions===true ? [[-1], ["Todos"]] : [[10, 20, 30, -1], [10, 20, 30, "Todos"]],

                "order": [[oder_col, "desc"]],
                "scrollY": disableBasicfunctions===true ? false : "300px",
                "scrollX": true,
                "scrollCollapse": true,
                scrollY: scrollY,
                "dom": DatatablesSettings.dom,
                buttons: botones_mstrar,
                "language": DatatablesSettings.language,
                /********para poder mover con las flechas*********/
                keys: true,
                "searching": disableBasicfunctions  === true ? false : true,
                "searching": enableSearch ? true : false,
                "ordering": disableBasicfunctions  === true? false : true,
                "bPaginate": disableBasicfunctions === true? false : true,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                paging: true,
                info: true,
                'createdRow': function (row, data, dataIndex) {

                    $(row).attr('tabindex', dataIndex);
                    $(row).attr('class', data['class']);
                    $(row).attr('data-name', data['producto_nombre']);
                    $(row).attr('data-codigo_interno', data['producto_codigo_interno']);
                    $(row).attr('data-producto_impuesto', data['producto_impuesto']);
                    $(row).attr('data-producto_impuesto_id', data['producto_impuesto_id']);
                    $(row).attr('data-costo_unitario', data['costo_unitario']);
                    $(row).attr('data-producto_ubicacion_fisica', data['producto_ubicacion_fisica']);
                    $(row).attr('data-producto_tipo', data['producto_tipo']);
                    $(row).attr('data-produto_grupo', data['produto_grupo']);
                    $(row).attr('data-porcentaje_descuento', data['porcentaje_descuento']);

                    /*aqui, si se quiere que en algunas columnas se sume, debe llegar un arreglo llamado: campos_sumar,
                     donde se le indica cuales van a ser las columnas que se van a sumar en el footerCallback, esto en este caso
                     que es lazzy debe venir desde el servidor, vease api/venta/ventas_devolver_get*/
                    if(data.campos_sumar!=undefined){
                        var indexsuma=""
                        for(var i=0; i<data.campos_sumar.length;i++){
                            if(data.campos_sumar[i]!=""){
                                indexsuma=parseInt(data.campos_sumar[i])+parseInt(0)
                                $( row ).find('td:eq('+indexsuma+')').attr('data-sumar', 'true');
                            }

                        }
                    }
                },
                "sScrollX": "100%",
                /************************************/
                "fnInitComplete": function (settings, json) {
					if(focusoncomplete==true) {
						setTimeout(function () {
							$('div.dataTables_filter input').focus();
						}, 1);
					}
                    if (fnInitComplete) {
                        fnInitComplete(json);
                    }

                },

                "fnDrawCallback": function (datos) {
                    //esto funciona cuando se hace el paginate, ya que con fnInitComplete solo lo hace una vez
					if(focusoncomplete==true) {
						setTimeout(function () {
							$('div.dataTables_filter input').focus();
						}, 1);
					}
                    if (fnInitComplete) {
                        fnInitComplete(datos.json);
                    }
                },

                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();

                    var i = 0;
                    var sumar=false
                    api.columns().every(function (index) {

                        sumar=false;
                        /*en algunos reportes, no hace falta sumar algunas filas, y menos
                         * si son textos, por lo tanto, en el <td> de cada registro, se debe indicar con un atributo data:
                         * data-sumar="true" si se quiere que sume, para saber si aqui se va a sumar o no.
                         * Vease arriba: createdRow, alli, llega un arreglo llamado: campos_sumar, donde se le indica cuales van a
                         * ser las columnas que se van a sumar, esto en este caso que es lazzy debe venir desde el servidor*/
                        api.column(index).nodes().toArray().map(function(node) {
                            if($(node).attr('data-sumar')!=undefined && $(node).attr('data-sumar')=="true"){
                                sumar=true;
                            }
                        });

                        if (i > 0 && sumar==true) {
                            var sum = this
                                .data()
                                .reduce(function (a, b) {

                                    var x = parseFloat(a) || 0;

                                    if (isNaN(x)) {
                                        x = 0;
                                    }
                                    //   console.log(x);
                                    // console.log(b);
                                    if (b != null) {
                                        if (isNaN(b)) {
                                            b = b.replace(/[,.]/g, function (m) {
                                                // m is the match found in the string
                                                // If `,` is matched return `.`, if `.` matched return `,`
                                                return m === ',' ? '.' : '';
                                            });
                                        }

                                        // console.log(b);
                                        // b=parseFloat(b.replace('.','').replace(',','.'));
                                        var y = parseFloat(b) || 0;


                                        //  console.log(x + y);
                                        return x + y;
                                    }
                                }, 0);


                            if (sum != undefined) {

                                $(this.footer()).html(sum.toLocaleString('de-DE', {
                                    maximumFractionDigits: 2,
                                    minimumFractionDigits: 2,
                                }));
                            }

                        }
                        i++;


                    });


                }


            };

            if (data != undefined) {
                if (data.reporte) {
                    tableoptions.aLengthMenu = [[-1, 10, 20, 30], ["Todos", 10, 20, 30]];
                }
            }

            var table = datatable_elemnt.DataTable(tableoptions);  //pendiente a ver si afecta dataTable a DataTable
            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Presiones ENTER para buscar..');


            /*para poder moverse con las flechas*/
            table.off('key-focus');
            table.on('key-focus', function (e, datatable, cell) {

                var rowData = datatable.row(cell.index().row).data();
                var colData = cell.data();
                var objectCell = $($.parseHTML(colData));

                if ($("#" + objectCell.attr('id')).length != 0) {
                    $("#" + objectCell.attr('id')).focus();
                }
                var input = $.parseHTML(rowData[0]);
                /*input[1].defaultValue es el value por defecto del input tpe hiden
                 que tiene la primera columna, donde debe ir el id del producto*/

                if (onkeyfocusCall!=undefined && onkeyfocusCall!=false ) {
                    if (onkeyfocusCall(input[1].defaultValue)) {
                        onkeyfocusAfter(input[1].defaultValue);
                    }
                }
            });

            /*esto lo que hace es mandar a buscar lo introducido cuando, solo cuando se presiona ENTER
             var tableapi=table.api();  <-- la tabla de referencia fuese esta, si  el objeto de la tabla hubiese sido
             con minuscula  (dataTable) en vez de (DataTable)*/
            $(".dataTables_filter input")
                .unbind()
                .bind('keyup change', function (e) {
                    if (e.keyCode == 13 || this.value == "") {
                        table
                            .search(this.value)
                            .draw();

                        setTimeout(function () {
                            $('.dataTables_filter input').val('');
                        }, 500);

                    }
                });


            return table;
        }
    };
}();


