<?php $ruta = base_url(); ?>
<style>
	caption {
		display: none
	}
</style>
<div class="row bg-title">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
		<h4 class="page-title">Productos mas vendidos</h4></div>
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




					<div class="table-responsive" id="">
						<table class="table table-striped dataTable table-bordered" id="tabla">

							<thead id="theadtabla">
							<tr>
								<th>Código Producto</th>
								<th>Nombre</th>
								<th>Cantidad de registros</th>
								<th>Total Cajas Vendidas</th>
								<th>Total Blister Vendidos</th>
								<th>Total Unidades Vendidas</th>
								<th>Rep. Cajas Vendidas</th>
								<th>Rep. Blister Vendidos</th>
								<th>Rep. Unidades Vendidas</th>

							</tr>

							</thead>
							<tbody id="tbody">


							</tbody>
						</table>
					</div>

		</div>
	</div>
</div>

<script type="text/javascript">

    function get_datos() {
        var table = $('#tabla').DataTable();
        table.destroy();

        var fecha_desde = $("#fecha_desde").val();
        var fecha_hasta = $("#fecha_hasta").val();

        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/productos/productos_mas_vendidos_data', 0, 'tabla', {
                fecha_desde: fecha_desde,
                fecha_hasta: fecha_hasta,
                //filtrarpor: $("input[name='radio']:checked").val()
            },false
        );
    }


    $(function () {
        $(".fecha").datepicker({format: 'dd-mm-yyyy'});
        $(".fecha").on("change", function () {
            get_datos();
        });
        get_datos();
    });

</script>



