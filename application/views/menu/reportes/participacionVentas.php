<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Participación ventas</h4></div>
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
                    Vendededor
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
                    <button type="button" class="btn btn-info" onclick="get_ventas()"><i class="fa fa-bar-search"></i>Generar
                        reporte
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" id="" class="btn btn-success" onclick="verGrafica()"><i
                                class="fa fa-bar-chart"></i>Ver
                        gráfica
                    </button>
                </div>

            </div>

            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Vendedor</th>
<?php
/**
             * Con esto verifico si le muestro los totales o no
             */
            if(
                $this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN"
                ||
                $this->usuarios_grupos_model->user_has_perm(
                    $this->session->userdata('nUsuCodigo'),
                    'rep_part_ventas_vendedor_vertotalesventas')
            ) { ?>
                                <th>% Participacion</th>
                                <th>Total Vendedor</th>
                                <th>Total Vendedor Gravado</th>
                                <th>Total Vendedor Excluido</th>
                                <th>Total Vendedor Iva</th>
                                <th>Total Vendedor Sin Iva</th>
                                <th>Venta comisionada vendedor</th>

                               <?php } ?>
                                <th>Total Comisionado</th>

                            </tr>

                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <?php
                                /**
                                 * Con esto verifico si le muestro los totales o no
                                 */
                                if(
                                $this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN"
                                ||
                                $this->usuarios_grupos_model->user_has_perm(
                                    $this->session->userdata('nUsuCodigo'),
                                    'rep_part_ventas_vendedor_vertotalesventas')
                                ) { ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php } ?>
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
</div>

    <!-- /.row -->


    <script type="text/javascript">


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

            /*if (vendedor == '') {
                Utilities.alertModal('Debe seleccionar el vendedor');
                return false;
            }*/

            var mensajetop = 'Desde: ' + fercha_desde + '<br>&#013;Hasta: ' + fercha_hasta + '<br>&#013;Fecha-Hora: ' + hoy + '<br>&#013;Vendedor: ' + vendedor + '<br>&#013;Usuario: ' + usuario;
            $('#tabla').append('<caption style="caption-side: top">' + mensajetop + '</caption>');

            TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/participacion_vendedores', 0, 'tabla', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
                vendedor: vendedor,

                reporte: true,
            }, 'Reporte de participación en ventas', hacerGrafico);


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
                    label: "Porcentaje participacion",
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
    </script>
