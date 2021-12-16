<?php $ruta = base_url(); ?>
<style>
	caption {
		display: none
	}
</style>
<div class="row bg-title">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
		<h4 class="page-title">Reporte de Ventas por hora</h4></div>
	<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>">SID</a></li>

		</ol>
	</div>
	<!-- /.col-lg-12 -->
</div>


<div class="row">


	<div class="col-md-12">

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


			</div>
			<br>


			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive" id="">
						<table class="table table-striped dataTable table-bordered" id="tabla">

							<thead id="theadtabla">
							<tr>
								<th rowspan="2"></th>
								<th colspan="2">Domingo</th>
								<th colspan="2">Lunes</th>
								<th colspan="2">Martes</th>
								<th colspan="2">Miércoles</th>
								<th colspan="2">Jueves</th>
								<th colspan="2">Viernes
								<th colspan="2">Sábado</th>
								<th rowspan="2">Promedio</th>
							</tr>
							<tr>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>

								<th>Cantidad</th>
								<th>Valor</th>
							</tr>
							</thead>
							<tbody id="tbody">
							</tbody>
							<tfoot id="tfoot"></tfoot>
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
            ' <th rowspan="2">Fecha</th>';
        if($("#tipos_venta_select").val()!="TODOS"){
            html+='<th rowspan="2">Tipo de Venta</th>'+$("#tipos_venta_select option:selected").text()+'</th>';
        }

        html+='<th colspan="2">Base Excluida</th>' +
            '<th colspan="2">Base Gravada</th>' +
            '<th rowspan="2">Descuento Total</th>';
        for(var i=0; i<Object.keys(gImpuestos).length;i++){
            html+='<th rowspan="2">'+ gImpuestos[i]['nombre_impuesto']+'</th>'
            if(gImpuestos[i]['tipo_calculo']!="FIJO"){
                html+='<th rowspan="2">Gravado '+ gImpuestos[i]['nombre_impuesto']+'</th>';
            }
        }
        html+='<th rowspan="2">Total Iva</th>'+
            '<th rowspan="2">Anulaciones</th>'+
            '                                <th rowspan="2">Devoluciones</th>'+
            '                                <th rowspan="2">Gravado + Excluido</th>'+
            '                                <th rowspan="2">Venta Total</th>'+
            '                            </tr>'+
            '                            <tr>'+
            '                                <th>Valor</th>'+
            '                                <th>Descuento</th>'+
            '                                <th>Base</th>'+
            '                                <th>Descuento</th>';

        html+='                            </tr>';

        var tfoot=" <tr>" +
            "<th></th>";
        if($("#tipos_venta_select").val()!="TODOS"){
            tfoot+='<th></th>';
        }
        tfoot+="<th></th><th></th><th></th>";
        for(var i=0; i<Object.keys(gImpuestos).length;i++){
            tfoot+='<th></th>'
            if(gImpuestos[i]['tipo_calculo']!="FIJO"){
                tfoot+='<th></th>';
            }
        }
        tfoot+=" <th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
        $("#tbody").html('');
        $("#theadtabla").html('')

        $("#tfoot").html('');
        $("#tfoot").html(tfoot);

        $("#theadtabla").html(html)
    }

    $(function () {


        $(".fecha").datepicker({format: 'dd-mm-yyyy'});

        $(".campos").on("change", function () {
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
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();

        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/ventasPorHora_data', 0, 'tabla', {
                fecha_desde: fercha_desde,
                fecha_hasta: fercha_hasta,
            }, false, false, false, false,false,true,false,false,false,[{width: '10%', targets: 0}]
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
