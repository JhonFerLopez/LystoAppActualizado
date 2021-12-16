<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Comparativas Vendedor</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>

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

                <div class="col-md-2">
                    Desglosado por fecha <input type="radio" onclick="changeRadio(this);" checked name="radiofecha"
                                           value="fecharango">
                </div>


                <div class="col-md-2">
                    Sumatoria <input type="radio" onclick="changeRadio(this);" name="radiofecha" value="fechaunica">
                </div>


                <div class="col-md-2 hasta">
                    ¿Que quieres Comparar?
                </div>

                <div class="col-md-3 hasta">
                    <select name="comparar" id="comparar" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="COMISION POR VENTAS">COMISION POR VENTAS</option>
                        <?php
                        if(
                        $this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN"
                        ||
                        $this->usuarios_grupos_model->user_has_perm(
                            $this->session->userdata('nUsuCodigo'),
                            'rep_comp_vend_mostrar_filtro_total_vendido')
                        ) { ?>
                        <option value="TOTAL VENDIDO">TOTAL VENDIDO</option>
                        <?php } ?>
                        <option value="VENTA COMISIONADA">VENTA COMISIONADA</option>
                        <!--<option value="PARTICIPACION VENTAS">PARTICIPACION VENTAS</option>-->

                    </select>
                </div>


            </div>

            <br>
            <div class="row">


                <div class="col-md-1">
                    Fecha
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>


                <div class="col-md-3">
                    <button type="button" class="btn btn-info" onclick="get_ventas()"><i class="fa fa-bar-search"></i>Generar
                        reporte
                    </button>
                </div>

                <div class="col-md-3">
                    <button type="button" id="" class="btn btn-success" onclick="verGrafica()"><i
                                class="fa fa-bar-chart"></i>Ver
                        gráfica
                    </button>
                </div>
            </div>
            <br>


            <div class="row" id="tablarango">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <?php foreach ($vendedores as $vendedor) {
                                    ?>
                                    <th><?= $vendedor['nombre'] ?></th>
                                    <?php
                                } ?>


                            </tr>

                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <?php foreach ($vendedores as $vendedor) {
                                    ?>
                                    <th></th>
                                    <?php
                                } ?>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <div class="row hidden" id="tablaunicafecha">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla2">

                            <thead>
                            <tr>
                                <th>Vendedor</th>
                             <!--   <td>PORCENTAJE PARTICIPACION</td>-->
                                <td>VENTAS VENDEDOR</td>
                                <td>COMISION POR VENTAS</td>
                                <td>VENTA COMISIONADA</td>


                            </tr>

                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                               <!--<td>PORCENTAJE PARTICIPACION</td>-->
                                <td></td>
                                <td></td>
                                <td></td>
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
                <h4 class="modal-title">Gráfica comparativa de vendedores por: <span id="compararbox"></span></h4>
            </div>

            <div class="modal-body">


                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="white-box">

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

</div>


<div class="modal fade " id="graficamodal2" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Gráfica comparativa de vendedores </h4>
            </div>

            <div class="modal-body">
                <div class="row text-right">
                    <!--<div class="col-md-3 text-info"> Mostrar porcentaje
                        <input type="radio" name="radiomostrar" id="mostrarporcentaje" checked
                               onchange="hacerGraficoPorVendedor(datagrafico2)">
                    </div>-->
                    <div class="col-md-2 text-info">
                        Ventas
                        <input type="radio" name="radiomostrar" checked id="mostrartotalvendedor"
                               onchange="hacerGraficoPorVendedor(datagrafico2)">
                    </div>
                    <!--  <div class="col-md-3 text-info">
                          Total del dia
                          <input type="radio" name="radiomostrar"  id="mostrartotaldia" onchange="hacerGraficoPorVendedor(datagrafico2)">
                      </div>-->
                    <div class="col-md-2 text-info">
                        Comisionado
                        <input type="radio" name="radiomostrar" id="mostrarcomicionado"
                               onchange="hacerGraficoPorVendedor(datagrafico2)">
                    </div>
                </div>

                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="white-box">

                            <div id="legends2" style="width:600px;"></div>
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="grafico2"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /.modal-content -->
    </div>

</div>


<!-- /.row -->


<script type="text/javascript">


    var datagrafico = false;
    var datagrafico2 = false;
    var ticks = new Array();
    <?php foreach ($vendedores as $vendedor){
    ?>
    //ticks.push(new Array(<?= $vendedor['nUsuCodigo']?>))
    <?php
    }?>

    function changeRadio(element) {
        console.log(element.value);
        if (element.value == 'fecharango') {
            $(".hasta").removeClass('hidden');
            $("#tablaunicafecha").addClass('hidden');
            $("#tablarango").removeClass('hidden');


        } else {

            $(".hasta").addClass('hidden');
            $("#tablarango").addClass('hidden');
            $("#tablaunicafecha").removeClass('hidden');
            //$("#fecha_hasta").val('');
        }
    }
    function verGrafica() {
        var radiofecha = $("input[name='radiofecha']:checked").val();
        if (radiofecha === 'fecharango') {
            $("#graficamodal").modal('show');
        } else {
            $("#graficamodal2").modal('show');
        }
    }
    function get_ventas() {


        var hoy = new Date(Date.now()).toLocaleString();
        var usuario = '<?= $this->session->userdata("username")?>';
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var comparar = $("#comparar").val();
        var radiofecha = $("input[name='radiofecha']:checked").val();

        console.log(radiofecha);
        if (comparar == '' && radiofecha === 'fecharango') {
            Utilities.alertModal('Debe seleccionar el item a comparar');
            return false;
        }

        if (fercha_desde == '') {
            Utilities.alertModal('Debe seleccionar la fecha');
            return false;
        }
        if (fercha_hasta == '') {
            Utilities.alertModal('Debe seleccionar la fecha fin');
            return false;
        }
        $("#compararbox").html(comparar);
        var mensajetop = 'Desde: ' + fercha_desde + '<br>&#013;Hasta: ' + fercha_hasta + '<br>&#013;Fecha-Hora: ' + hoy + '<br>&#013;Comparar: ' + comparar + '<br>&#013;Usuario: ' + usuario;
        $('#tabla').append('<caption style="caption-side: top">' + mensajetop + '</caption>');

        if (radiofecha === 'fecharango') {
            TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/comparativaVendedoresPorFecha', 0, 'tabla', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
                comparar: comparar,
                reporte: true,
            }, 'Reporte comparativo Vendedores', hacerGrafico);
        } else {
            TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/comparativaVendedoresPorVendedores', 0, 'tabla2', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
                reporte: true,
            }, 'Reporte comparativo Vendedores', hacerGraficoPorVendedor);
        }


    }

    function hacerGrafico(data) {


        if (datagrafico === false) {
            datagrafico = data.graficoarray;
        }
        var barData = new Array();
        var count = 1;

        jQuery.each(data.graficoarray, function (i, value) {
            console.log(value);
            barData.push({
                label: value.label
                , data: value.data
                , bars: {
                    order: count
                }
            });
            count++;
        })


        var stack = 0
            , bars = true
            , lines = true
            , steps = true;

        var barOptions = {
            bars: {
                show: true
                , barWidth: 43200000
                , fill: 1
            },


            grid: {
                show: true
                , aboveData: false
                , labelMargin: 5
                , axisMargin: 0
                , borderWidth: 1
                , minBorderMargin: 5
                , clickable: true
                , hoverable: true
                , autoHighlight: false
                , mouseActiveRadius: 20
                , borderColor: '#f5f5f5'
            }
            , series: {
                stack: stack
            }
            , legend: {
                position: "ne"
                , margin: [0, 0]
                , noColumns: 0
                , labelBoxBorderColor: null
                , labelFormatter: function (label, series) {
                    // just add some space to labes
                    return '' + label + '&nbsp;&nbsp;';
                }
                , width: 30
                , height: 5,
                container: "#legends"
            }
            , yaxis: {
                tickColor: '#f5f5f5'
                , font: {
                    color: '#bdbdbd'
                },
                min: 0,
                tickFormatter: function (v, axis) {
                    return v + "$";
                }
            }
            , xaxis: {
                mode: "time",
                timeformat: "%d/%m/%Y",
                minTickSize: [1, "day"],
                tickColor: '#f5f5f5'
                , font: {
                    color: '#bdbdbd'
                }
            }
            , colors: ["#4F5467", "#01c0c8", "#fb9678"]
            , tooltip: true, //activate tooltip
            tooltipOpts: {
                content: "%s : %y"
                , shifts: {
                    x: -30
                    , y: -50
                }
            }
        };

        console.log(barData);
        var somePlot = Utilities.FlotChart($("#flot-bar-chart"), barData, barOptions, false);


    }


    function hacerGraficoPorVendedor(data) {


        var mostrarporcentaje = $("#mostrarporcentaje");
        var mostrartotalvendedor = $("#mostrartotalvendedor");
        var mostrartotaldia = $("#mostrartotaldia");
        var mostrarcomicionado = $("#mostrarcomicionado");

        if (datagrafico2 === false) {
            datagrafico2 = data;
        }
        var barData = [];

        var count = 0;
        var barOptions = {

            series: {
                pie: {
                    innerRadius: 0.5
                    , show: true
                }
            }
            , grid: {
                hoverable: true
            }
            , color: null
            , tooltip: true
            , tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20
                    , y: 0
                }
                , defaultTheme: false
            }

        };
        console.log(data);
        /*  if (mostrarporcentaje.is(':checked')) {
         jQuery.each(data.grafcoporcentaje, function (i, value) {
         barData[i] = {
         label: value[0],
         data: value[1],

         };
         })

         }*/
        /*   if (mostrartotaldia.is(':checked')) {


         jQuery.each(data.graficototal, function (i, value) {
         barData[i] = {
         label: value[0],
         data: value[1],

         };
         })

         }*/
        if (mostrartotalvendedor.is(':checked')) {

            jQuery.each(data.grafcototalvendedor, function (i, value) {
                barData[i] = {
                    label: value[0],
                    data: value[1],

                };
            })
        }
        if (mostrarcomicionado.is(':checked')) {


            jQuery.each(data.grafcocomision, function (i, value) {
                barData[i] = {
                    label: value[0],
                    data: value[1],

                };
            })
        }


        var somePlot = Utilities.FlotChart($("#grafico2"), barData, barOptions, false);


    }
</script>
