<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Participación ventas (Factura/Producto)</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>


<div class="row">


    <div class="col-md-12">

        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success alert-dismissable" id="success"
                     style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                    <span id="successspan"><?php echo isset($success) ? $success : '' ?></div>
                </span>
            </div>
        </div>

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">

                <div class="col-md-1">
                    Categor&iacute;a
                </div>
                <div class="col-md-3">
                    <select id="categoria" class="form-control campos" name="categoria">
                        <option value="" selected>SELECCIONE</option>
                        <option value="CLASIFICACION">CLASIFICACIÓN</option>
                        <option value="TIPO">TIPO</option>
                        <!--<option value="COMPONENTE">COMPONENTE</option>-->
                        <option value="GRUPO">GRUPO</option>
                        <option value="UBICACION_FISICA">UBICACIÓN FÍSICA</option>
                        <option value="IMPUESTO">IMPUESTO</option>
                    </select>
                </div>
                <div class="col-md-2">
                    Sub Categor&iacute;a
                </div>
                <div class="col-md-3">
                    <select id="subcategoria" class="form-control campos" name="subcategoria">

                    </select>
                </div>

            </div>

            <div class="row">
                <input type="hidden" name="listar" id="listar" value="ventas">


                <div class="col-md-2">
                    Desde
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>

                <div class="col-md-2">
                    Hasta
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>


                <div class="col-md-2">
                    Vendedor
                    <select name="vendedor" id="vendedor" class="form-control">
                        <option value="">Todos</option>
                        <?php

                        foreach ($vendedores as $vendedor) {
                            ?>
                            <option value="<?= $vendedor['nUsuCodigo'] ?>"><?= $vendedor['nombre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2">
                    Productos Comision
                    <select id="comisionados" name="comisionados" class="form-control">
                        <option value="">TODOS</option>
                        <option value="SI">COMISIONADOS</option>
                        <option value="NO">NO COMISIONADOS</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <br>
                    <button type="button" class="btn btn-info" onclick="get_ventas()"><i class="fa fa-bar-search"></i>Generar
                        reporte
                    </button>
                </div>
                <!--  <div class="col-md-2">
                      <button type="button" id="" class="btn btn-success" onclick="verGrafica()"><i
                                  class="fa fa-bar-chart"></i>Ver
                          gráfica
                      </button>
                  </div>--->

            </div>

            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead>
                            <tr>
                                <th rowspan="1">Fecha</th>
                                <th rowspan="1">Factura</th>
                                <th rowspan="1">Vendedor</th>
                                <th rowspan="1">Producto</th>
                                <th rowspan="1">Nombre Producto</th>
                                <?php foreach ($unidades as $unidad): ?>
                                    <th rowspan="1"><?= $unidad['abreviatura']?></th>

                                <?php endforeach; ?>
                                <th rowspan="1">Valor</th>
                                <th colspan="1">Valor Base Excluida</th>
                                <th colspan="1">Base Gravada</th>
                                <?php foreach ($impuestos as $impuesto) {
                                    ?>

                                    <th rowspan="1"><?= $impuesto['nombre_impuesto'] ?></th>

                                    <?php if ($impuesto['tipo_calculo'] != 'FIJO') { ?>
                                        <th rowspan="1">Gravado <?= $impuesto['nombre_impuesto'] ?></th>
                                    <?php } ?>
                                    <?php
                                } ?>
                                <th rowspan="1">Total Iva</th>

                                <th rowspan="1">Comision</th>
                                <th rowspan="1">% Comision</th>

                            </tr>


                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php foreach ($unidades as $unidad): ?>
                                    <th data-sumar="false"></th>

                                <?php endforeach; ?>

                                <th></th>
                                <?php foreach ($impuestos as $impuesto) {
                                    ?>
                                    <th></th>

                                    <?php if ($impuesto['tipo_calculo'] != 'FIJO') { ?>
                                        <th></th>
                                    <?php } ?>
                                    <?php
                                } ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="graficamodal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Gráfica de participaci&oacute;n ventas</h4>
            </div>

            <div class="modal-body">

                <div class="row text-right">
                    <div class="col-md-3 text-info"> Mostrar porcentaje
                        <input type="checkbox" id="mostrarporcentaje" checked
                               onchange="hacerGrafico(datagrafico)">
                    </div>
                    <div class="col-md-3 text-info">
                        Mostrar total vendedor
                        <input type="checkbox" id="mostrartotalvendedor" onchange="hacerGrafico(datagrafico)">
                    </div>
                    <div class="col-md-3 text-info">
                        Mostrar total del dia
                        <input type="checkbox" id="mostrartotaldia" onchange="hacerGrafico(datagrafico)">
                    </div>
                    <div class="col-md-3 text-info">
                        Mostrar total comisionado
                        <input type="checkbox" id="mostrarcomicionado" onchange="hacerGrafico(datagrafico)">
                    </div>
                </div>
                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Reporte Gráfico</h3>
                            <div id="legends" style="width:600px;"></div>
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /.modal-content -->

    </div>


    <!-- /.row -->


    <script type="text/javascript">


        var subcategoria = "";
        var subcatSelected = '';

        function definirCategoria() {

            if (subcategoria != "") {
                subcategoria.select2("destroy");
                subcategoria.html("<option value=''>Seleccione<option>");
            }
            subcategoria = $("#subcategoria").select2(
                {
                    //dropdownParent: $("#modal_enviar"),
                    allowClear: true,
                    language: "es",
                    width: "100%",
                    placeholder: 'Buscar Sub Categoria',
                    escapeMarkup: function (markup) {
                        return markup;
                    }, // let our custom formatter work

                    language: {
                        inputTooShort: function () {
                            return 'Ingrese un texto para buscar';
                        },
                        noResults: function () {
                            return "Sin resultados";
                        }
                    }
                });


            subcategoria.on('select2:select', function (e) {
                /*Entra aqui, cuando se seleccione un cliente*/
                var data = e.params.data;
                subcatSelected = data;
                console.log('data', data)
                /*genero un mensaje  con los datos anteriores*/
                traerProductos(data.id);

            });
        }


        var datagrafico = false;

        function verGrafica() {
            $("#graficamodal").modal('show');
        }
        function get_ventas() {

            var hoy = new Date(Date.now()).toLocaleString();
            var usuario = '<?= $this->session->userdata("username")?>';
            var fercha_desde = $("#fecha_desde").val();
            var fercha_hasta = $("#fecha_hasta").val();
            var vendedor = $("#vendedor").val();
            var categoria = $("#categoria").val();
            var subcategoria = $("#subcategoria").val();
            var comisionados = $("#comisionados").val();


            var mensajetop = 'Desde: ' + fercha_desde + '<br>&#013;Hasta: ' + fercha_hasta + '<br>&#013;Fecha-Hora: ' + hoy + '<br>&#013;Vendedor: ' + vendedor + '<br>&#013;Usuario: ' + usuario;
            $('#tabla').append('<caption style="caption-side: top">' + mensajetop + '</caption>');

            TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/participacion_vendedoresProducto', 0, 'tabla', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
                vendedor: vendedor,
                categoria: categoria,
                subcategoria: subcategoria,
                comisionados: comisionados,

                reporte: true,
            }, 'Reporte de participación en ventas', false, false, false, false, false, false, '300px');


        }

        function hacerGrafico(data) {

            var mostrarporcentaje = $("#mostrarporcentaje");
            var mostrartotalvendedor = $("#mostrartotalvendedor");
            var mostrartotaldia = $("#mostrartotaldia");
            var mostrarcomicionado = $("#mostrarcomicionado");

            if (datagrafico === false) {
                datagrafico = data;
            }
            var barData = [];

            var count = 0;
            var barOptions = {


                legend: {
                    show: true,
                    position: "ne",
                    backgroundColor: '#faff27',
                    backgroundOpacity: 0.5,
                    container: "#legends",
                    noColumns: 4
                }, grid: {
                    color: "#AFAFAF",
                    hoverable: true,
                    aboveData: true,
                    borderWidth: 0,
                    backgroundColor: '#FFF',
                    clickable: true
                },


                yaxes: [{
                    max: 120, min: 0, position: "left", tickFormatter: function (v, axis) {
                        return v + "%";
                    }
                }, {
                    min: 0,
                    // align if we are to the right
                    alignTicksWithAxis: null,
                    position: "right",
                    tickFormatter: function (v, axis) {
                        // return v.toFixed(axis.tickDecimals) + "$";
                        return v + "$";
                    }
                }],
                xaxis: {
                    mode: "time",
                    timeformat: "%d/%m/%Y",
                    minTickSize: [1, "day"]
                },

                tooltip: {
                    show: true,
                    content: "%s %x  %y",
                    xDateFormat: "%d-%m-%y",

                    onHover: function (flotItem, $tooltipEl) {
                        console.log(flotItem, $tooltipEl);
                    },

                }
                //  animator: { start: 0, steps: 135, duration: 3000, direction: "right" }

            };

            if (mostrarporcentaje.is(':checked')) {
                barData[count] = {
                    label: "Porcentaje participación",
                    color: "#00fb86",
                    data: data.grafcoporcentaje,
                    stack: true,
                    stackpercent: false,
                    yaxis: 1,
                    bars: {
                        show: true,
                        barWidth: 43200000,
                        // fill: 1,
                        align: 'center',
                        fillColor: {
                            colors: [{opacity: 1}, {opacity: 1}]
                        },


                    },

                    valueLabels: {
                        show: true,

                        horizAlign: 'insideMax',
                        font: "9pt 'Trebuchet MS'",
                        useBackground: true,

                        showAsHtml: true,
                        align: "center",
                        labelFormatter: function (v) {
                            return v + '%';
                        },
                        // useDecimalComma: true
                    },
                };

                count++;
            }
            if (mostrartotaldia.is(':checked')) {
                barData[count] = {
                    label: "Total ventas",
                    color: "#3700fb",
                    data: data.graficototal,

                    stack: false,
                    yaxis: 2,
                    lines: {show: true},
                    bars: false,
                    points: {show: true, symbol: "circle"}


                };
                count++;
            }
            if (mostrartotalvendedor.is(':checked')) {
                barData[count] = {
                    label: "Total vendedor",
                    color: "#fb6054",
                    data: data.grafcototalvendedor,
                    stack: false,
                    yaxis: 2,
                    lines: {show: true},
                    bars: false,
                    points: {show: true, symbol: "circle"},


                };
                count++;
            }
            if (mostrarcomicionado.is(':checked')) {
                barData[count] = {
                    label: "Total comisionado",
                    color: "#fb2fc7",
                    data: data.grafcocomision,
                    stack: false,
                    yaxis: 2,
                    lines: {show: true},
                    bars: false,
                    points: {show: true, symbol: "circle"},

                };
                count++;
            }
            console.log(count);
            console.log(barData);
            var somePlot = Utilities.FlotChart($("#flot-bar-chart"), barData, barOptions, false);


        }

        $(document).ready(function () {
            definirCategoria();

            $("#categoria").on("change", function () {

                if ($(this).val() == "") {
                    return false;
                }

                Utilities.showPreloader();
                url = baseurl;
                if ($(this).val() == "GRUPO") {
                    url += 'grupo/getGruposJson'
                }

                if ($(this).val() == "CLASIFICACION") {
                    url += 'clasificacion/getClasificacionJson'
                }
                if ($(this).val() == "TIPO") {
                    url += 'tipo_producto/getTiposJson'
                }

                if ($(this).val() == "COMPONENTE") {
                    url += 'componentes/getComponentesJson'
                }

                if ($(this).val() == "UBICACION_FISICA") {
                    url += 'ubicacion_fisica/getUbicacionJson'
                }

                if ($(this).val() == "IMPUESTO") {
                    url += 'impuesto/getImpuestosJson'
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        var newOption = "";
                        definirCategoria();
                        for (var i = 0; i < data.length; i++) {
                            newOption = new Option(data[i].text, data[i].id, false, false);
                            subcategoria.append(newOption);
                        }
                        subcategoria.trigger('change');
                        Utilities.hiddePreloader();
                    },
                    error: function () {
                        Utilities.hiddePreloader();
                        Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
                    }
                })

            });

        })
    </script>
