<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Informe detallado de compras por fecha</h4></div>
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

                <div class="col-md-1">
                    Desde
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos">
                </div>
                <div class="col-md-1">
                   Proveedor
                </div>
                <div class="col-md-3">
                    <select id="tipos_venta_select" class="form-control">
                        <option value="TODOS"  selected>TODOS</option>
                        <?php
                        if(count($proveedores)>0){
                            foreach($proveedores as $row){ ?>
                                <option value="<?= $row['id_proveedor'] ?>"><?= $row['proveedor_nombre'] ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
               <!-- <div class="col-md-2">
                    <button type="button" class="btn btn-success" onclick="verGrafica()"><i class="fa fa-bar-chart"></i>Ver
                        gráfica
                    </button>
                </div>-->

            </div>
            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead id="theadtabla">
                            <tr>
                                <th >Fecha</th>

                                <?php foreach ($impuestos as $impuesto) {
                                    ?>

                                    <th><?= $impuesto['nombre_impuesto'] ?></th>

                                    <?php if ($impuesto['tipo_calculo'] != 'FIJO') { ?>
                                        <th>Gravado <?= $impuesto['nombre_impuesto'] ?></th>
                                    <?php } ?>
                                    <?php
                                } ?>
                                <th >Total Iva</th>
                                <th >Total gravado</th>
                                <th >Total Excluido</th>
                              <!--  <th rowspan="2">Anulaciones</th>
                                <th rowspan="2">Devoluciones</th>-->
                                <th >Gravado + Excluido</th>
                                <th>Total</th>

                            </tr>

                            </thead>
                            <tbody id="tbody">


                            </tbody>

                            <tfoot id="tfoot">
                            <tr>
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
                              <!--  <th></th>
                                <th></th>-->

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
                <h4 class="modal-title">Gráfica de ventas por fecha</h4>
            </div>

            <div class="modal-body">

                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title">Reporte Gráfico</h3>
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
    var gImpuestos=new Array();
    function definirDatos(impuestos){
        gImpuestos=impuestos
    }

    function definirThead(){


        var html='<tr>' +
            ' <th >Fecha</th>';
        if($("#tipos_venta_select").val()!="TODOS"){
            html+='<th >Tipo de Venta</th>'+$("#tipos_venta_select option:selected").text()+'</th>';
        }


        for(var i=0; i<Object.keys(gImpuestos).length;i++){
            html+='<th>'+ gImpuestos[i]['nombre_impuesto']+'</th>'
            if(gImpuestos[i]['tipo_calculo']!="FIJO"){
                html+='<th >Gravado '+ gImpuestos[i]['nombre_impuesto']+'</th>';
            }
        }
        html+='<th >Total Iva</th>'+

            '                                <th>Gravado + Excluido</th>'+
            '                                <th >Venta Total</th>'+
            '                            </tr>'+
            '                            <tr>';

        html+='                            </tr>';

        var tfoot=" <tr>" +
            "<th></th>";
        if($("#tipos_venta_select").val()!="TODOS"){
            tfoot+='<th></th>';
        }
        tfoot+="<th></th>";
        for(var i=0; i<Object.keys(gImpuestos).length;i++){
            tfoot+='<th></th>'
            if(gImpuestos[i]['tipo_calculo']!="FIJO"){
                tfoot+='<th></th>';
            }
        }
        tfoot+=" <th></th><th></th><th></th></tr>";
        $("#tbody").html('');
        $("#theadtabla").html('')

        $("#tfoot").html('');
        $("#tfoot").html(tfoot);

        $("#theadtabla").html(html)
    }

    $(function () {

        definirDatos(<?php echo json_encode($impuestos); ?>);

        $(".fecha").datepicker({format: 'dd-mm-yyyy'});

        $(".campos").on("change", function () {
            get_ventas();
        });
        $("#tipos_venta_select").on("change", function () {
            get_ventas();
        });

        get_ventas();


    });

    function verGrafica() {
        $("#graficamodal").modal('show');
    }

    function get_ventas() {

        var table = $('#tabla').DataTable();
        table.destroy();
        //definirThead();

        var hoy = new Date(Date.now()).toLocaleString();
        var usuario = '<?= $this->session->userdata("username")?>';
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var mensajetop = '  Desde: ' + fercha_desde + '<p>Hasta: ' + fercha_hasta + 'Fecha-Hora: ' + hoy + 'Usuario: ' + usuario + '</p>' +
            '<p>';


        $('#tabla').append('<caption style="caption-side: top">' + mensajetop + '</caption>');

        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Ingreso/compras_por_fecha', 0, 'tabla', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
                tipos_venta_select: $("#tipos_venta_select").val(),
                tipos_venta_text:$("#tipos_venta_select option:selected").text(),
                reporte: true,
            }, false, graficoVentasdiaras, false, false,
            [
                {
                    text: 'EXCEL',

                    action: function (e, dt, node, config) {

                        window.location.href = baseurl + 'reportesExcel/excelInformeCompras?fecha_desde=' + fercha_desde + '&fecha_hasta=' + fercha_hasta+'&tipos_venta_select='+$("#tipos_venta_select").val()+'&tipos_venta_text='+$("#tipos_venta_select option:selected").text();

                    }
                },

                {
                    text: 'PDF',

                    action: function (e, dt, node, config) {

                        window.location.href = baseurl + 'reportesPdf/pdfInformeCompra?fecha_desde=' + fercha_desde + '&fecha_hasta=' + fercha_hasta+'&tipos_venta_select='+$("#tipos_venta_select").val()+'&tipos_venta_text='+$("#tipos_venta_select option:selected").text();

                    }

                },
                {
                    extend: 'csv',
                    extension: '.csv',
                    filename: 'Reporte',
                    footer: true

                },


                'print',
                'copyHtml5'
            ]
        );


    }

    function graficoVentasdiaras(data) {

        var barOptions = {

            xaxis: {
                mode: "time",
                timeformat: "%d/%m/%Y",
                minTickSize: [1, "day"]
            }
            , legend: {
                show: true
            }, grid: {
                color: "#AFAFAF",
                hoverable: true,
                aboveData: true,
                borderWidth: 0,
                backgroundColor: '#FFF',
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "Total: %y"
            },
            yaxis: 1
        };

        var barData = [{
            label: "Total Venta",
            color: "#fb9678",
            data: data.graficototal,
            bars: {
                show: true,
                barWidth: 43200000,
                // fill: 1,
                align: 'center',


            },
            yaxis: 1

        }, {
            label: "Base excluida",
            color: "#64fb2e",
            data: data.grafcoexluido,
            lines: {
                show: true
            },
            points: {
                show: true
            }
        }, {
            label: "Base gravada",
            color: "#2a2ffb",
            data: data.grafcogravado,
            lines: {
                show: true
            },
            points: {
                show: true
            },
            yaxis: 1
        },

        ];

        var somePlot = Utilities.FlotChart($("#flot-bar-chart"), barData, barOptions);


    }
</script>



