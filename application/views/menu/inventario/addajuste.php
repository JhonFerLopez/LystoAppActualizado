
<?php $ruta = base_url(); ?>
<div class="row bg-title">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
		<h4 class="page-title">
			<?php
			if ($tipo == 'ajuste'){ echo "Nuevo Movimiento"; }
			if ($tipo == 'todos'){ echo "Registra todos los Productos"; }
			if ($tipo == 'byGroup'){ echo "Registra por grupo"; }
			if ($tipo == 'byProduct'){ echo "Registra por producto"; }
			?>
		</h4>
	</div>
	<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
		<ol class="breadcrumb">
			<li><a href="">SID</a></li>
			<li class="">Inventario</li>
			<?php
			if ($tipo == 'ajuste'){  ?>
				<li class="">Movimientos diarios</li>
				<li class="active">Nuevo movimiento</li>
			<?php  }?>
				<?php
				if ($tipo == 'todos'){ echo '<li class="">Registro F&iacute;sicos</li><li class="active">Registra todos los Productos</li>'; }
				if ($tipo == 'byGroup'){ echo '<li class="">Registro F&iacute;sicos</li><li class="active">Registra por grupo</li>'; }
				if ($tipo == 'byProduct'){ echo '<li class="">Registro F&iacute;sicos</li><li class="active">Registra por producto</li>'; }
				?>
		</ol>
	</div>
	<!-- /.col-lg-12 -->
</div>
<div class="row">
	<div class="col-md-12">
<div class="white-box">
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
<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-success alert-dismissable" id="success"
			style="display:<?php echo isset($error) ? 'block' : 'none' ?>">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
			<h4><i class="icon fa fa-check"></i> Ha ocurrido un error al realizar la operación por favor
					intente
					nuevamente
			</h4>
			<span id="successspan"><?php echo isset($error) ? $error : '' ?></div>
		</span>
	</div>
</div>

<form name="formaregar" action="<?php echo $ruta; ?>inventario/guardar" method="post" id="formagregar">
	<input id="maximaghidden" type="hidden">

	<div class="block-section">
		<div class="form-group row">
			<div class="col-md-2">
				Fecha
				<input type="text" name="fecha" id="fecha" required="true"
					class="form-control input-datepicker"
					value="<?php echo date('d-m-Y') ?>">
			</div>
				<div class="col-md-4">
					Ubicaci&oacute;n
					<select id="locales_in" class="select-chosen" name="local" style="width:250px">
						<option value="seleccione"> Seleccione</option>
						<?php if (count($locales) > 0) {
								foreach ($locales as $local) {?>
									<option <?php if ($this->session->userdata('BODEGA_PRINCIPAL') == $local['int_local_id']) echo 'selected' ?>
										value="<?= $local['int_local_id']; ?>"> <?= $local['local_nombre'] ?> </option>
								<?php }
						} ?>
					</select>
				</div>
				<?php if ($tipo == 'ajuste') { ?>
					<div class="col-md-3">
						Tipo de movimiento
						<select id="tipo" name="tipo_movimiento" class="form-control" onchange="AjusteInventario.changeTipo()">
							<option value="">Seleccione</option>
							<option>ENTRADA</option>
							<option>SALIDA</option>
						</select>
					</div>
					<div class="col-md-3">
						Documento
						<select name="tipoajuste" id="tipoajuste" class="form-control" placeholder="Tipo">
							<option value="">Seleccione</option>
						</select>
					</div>
				<?php } ?>
				<?php if ($tipo == 'byGroup') { ?>
					<div class="col-md-3">
						Grupo
						<select id="grupo" name="grupo" class="form-control"
							onchange="AjusteInventario.changeGrupo()">
							<option value="">Seleccione</option>
							<?php foreach ($grupos as $grupo) { ?>
								<option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre_grupo'] ?></option>
							<?php } ?>
						</select>
					</div>
				<?php } ?>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<?php foreach ($unidades_medida as $unidad) { ?>
							<div class="col-md-2">
								Existencia en <?= $unidad['nombre_unidad'] ?>
							</div>
							<div class="col-md-2">
								<label id="existencia_<?= $unidad['id_unidad'] ?>"
									class="label label-warning"
								></label>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<br>
			<div class="table-responsive" >
				<table class="table table-striped dataTable table-bordered" id="tablaresult" >
					<thead>
					<tr>
						<th>C&oacute;digo</th>
						<th>Nombre</th>
						<th>Ubicación</th>
						<?php
						foreach ($unidades_medida as $unidad) {
							echo "<th> " . $unidad['nombre_unidad'] . "</th>";
							if ($tipo === 'ajuste') {
								echo "<th> Costo UNITARIO </th>";
								echo "<th> Costo TOTAL </th>";
							}
						}
						if ($tipo == 'byProduct' || $tipo == 'ajuste') {
							echo "<th> Acci&oacute;n </th>";
						}
						?>
					</tr>
					</thead>
					<tbody id="columnas">
					<?php
						if ($tipo == 'byProduct' || $tipo == 'ajuste') { ?>
							<tr id="trvacio">
								<td style="padding-top: 0px; padding-bottom: 0px">
									<input type="text" class="form-control inputsearchproduct"
										id="inputsearchproduct">
								</td>
								<td style="padding-top: 0px; padding-bottom: 0px"></td>
								<td style="padding-top: 0px; padding-bottom: 0px"></td>
								<?php foreach ($unidades_medida as $unidad): ?>
									<td style="padding-top: 0px; padding-bottom: 0px">
										<input type="number" class="form-control">
									</td>
									<?php if ($tipo === 'ajuste') { ?>
										<td style="padding-top: 0px; padding-bottom: 0px">
											<input type="number" class="form-control">
										</td>
										<td style="padding-top: 0px; padding-bottom: 0px">
											<input type="number" class="form-control" >
										</td>
									<?php } ?>
								<?php endforeach; ?>
								<td style="padding-top: 0px; padding-bottom: 0px"></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<div class="col-md-3 col-md-offset-9 ">
					<p class="font-weight-bold">Total movimiento:
						<span id="totalCosto">0.00</span> 
					</p>
				</div>
			</div>
		</div>
		<div class="block-section">
				<button type="button" id="btn_ajusteInventario_guardar" class="btn btn-primary"
					onclick="AjusteInventario.guardar()">Confirmar
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		</div>
		<!-- /.modal-content -->
</form>
<div class="modal bs-example-modal-lg" id="seleccionunidades" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close closeseleccionunidades" data-dismiss="modal"
					aria-hidden="true">&times;
				</button>
				<h4 class="modal-title">Productos</h4> <h5 id="nombreproduto"></h5>
			</div>
			<div class="modal-body" id="modalbodyproducto">
				<div class="row">
					<table id="tablaproductos" class="table datatable table-bordered table-striped ">
						<thead>
							<th>ID</th>
							<?php
							$cont = 0;
							$yaentroenunidades = false;
							if ($columnasToProd) {
								foreach ($columnasToProd as $columna) {
									if ($columna->mostrar == 1) {
										if ($columna->nombre_columna == 'cant' ||
												$columna->nombre_columna == 'precio' ||
												$columna->nombre_columna == 'porcent_utilidad'
										) {
											if ($yaentroenunidades == false) {
												$yaentroenunidades = true;
												foreach ($unidades_medida as $unidad) {
													if ($columna->nombre_columna == 'cant') {
														echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
														if ($columnasToProd[6]->mostrar == 1) 
															echo '<th>' . $columnasToProd[6]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
															if ($columnasToProd[7]->mostrar == 1) 
																echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
													}

													if ($columna->nombre_columna == 'precio') {
														echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
														if ($columnasToProd[7]->mostrar == 1) 
															echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
													}
													if ($columna->nombre_columna == 'porcent_utilidad') {
														echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
													}
												}
											}
										} else {
												echo '<th>' . $columna->nombre_mostrar . '</th>';
										}
									}
								$cont++;
								}
							}
						?>
						</thead>
						<tbody id="preciostbody">
							<tr></tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<a href="#" class="btn btn-primary" id="agregarproducto">Agregar Producto</a>
				<a href="#" class="btn btn-default closeseleccionunidades">Salir</a>
			</div>-->
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
			AjusteInventario.init(<?php echo json_encode($unidades_medida);?>, '<?= $tipo ?>',<?php echo json_encode($documentos_inventario); ?>
					,<?php echo json_encode($ubicaciones); ?>,null, null, '<?= $INVENTARIO_UBICACION_REQUERIDO?>');
	});
</script>