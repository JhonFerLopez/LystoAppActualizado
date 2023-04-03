<?php $ruta = base_url(); ?>
<?php $disabled = (isset($venta[0])) ? 'disabled' : ''; ?>

<div id="inentariocontainer" style="display: none;"></div>
<input type="hidden" id="producto_cualidad" value="">
<input type="hidden" id="devolver" value="<?php echo isset($devolver) ? 'true' : 'false'; ?>">
<input type="hidden" id="deuda" value="<?php echo isset($deuda) ? $deuda : 'false'; ?>">
<input type="hidden" id="idlocal" value="<?= $this->session->userdata('id_local'); ?>">
<input type="hidden" id="MOSTRAR_SIN_STOCK" value="<?= $this->session->userdata('MOSTRAR_SIN_STOCK'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="ABRIR_CAJA_REGISTRADORA" value="<?= $this->session->userdata('ABRIR_CAJA_REGISTRADORA'); ?>">
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="FACT_E_habilitacionn" value="<?= $this->session->userdata('FACT_E_habilitacionn'); ?>">
<input type="hidden" id="FACT_E_syncrono" value="<?= $this->session->userdata('FACT_E_syncrono'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<input type="hidden" id="43" value="<?= isset($venta[0]['montoTotal']) ? $venta[0]['montoTotal'] : '' ?>">
<input type="hidden" id="uuid" value="<?= isset($venta[0]['uuid']) ? $venta[0]['uuid'] : '' ?>">
<input type="hidden" id="VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS" value="<?= $this->session->userdata('VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS'); ?>">
<input type="hidden" id="VENDEDOR_EN_FACTURA" value="<?= $this->session->userdata('VENDEDOR_EN_FACTURA'); ?>">
<input type="hidden" id="preciosugerido" value="<?php echo isset($preciosugerido) ? 'true' : 'false'; ?>">
<input type="hidden" id="ALLOW_FACT_E" value="<?php echo $this->session->userdata('FACT_E_ALLOW'); ?>">
<input type="hidden" id="notadebito" value="<?= $notadebito; ?>">
<input type="hidden" id="resolucion_avisar" value="<?php echo $last_resolucion['resolucion_avisar'] ?>">

<?php
	$fechavencimiento = strtotime($last_resolucion['resolucion_fech_vencimiento']);
	$datetime2 = new DateTime(date('Y-m-d', $fechavencimiento));
	$datetime1 = new DateTime(date('Y-m-d'));
	$interval = $datetime1->diff($datetime2);
	$days = intval($interval->format('%R%a'));
//printf("%d years, %d months, %d days\n", $years, $months, $days);
?>
<input type="hidden" id="resolucion_avisar_vencimiento" value="<?= $days ?>">
<input type="hidden" id="desc_global" value="<?php echo isset($venta[0]['desc_global']) ? $venta[0]['desc_global'] : 0 ?>">

<script>
    Venta.total = 0;
    Venta.lst_producto = new Array();
</script>

<!--row -->
<div class="row">
  <div class="col-md-12">
		<div class="white-box">
			<!-- Progress Bars Wizard Title -->
			<form method="post" id="frmVenta" action="#" class=''>
				<input type="hidden" name="url_refresh" id="url_refresh" value="/index">
        <input type="hidden" id="precio_sugerido" value="0">
				<input type="hidden" name="diascondicionpagoinput" id="diascondicionpagoinput" 
					value="<?php echo ( (isset($venta[0]['id_condiciones'])) ? $venta[0]['id_condiciones'] : 0 ) ?>">
				<input type="hidden" name="fe_payment_form_id" id="fe_payment_form_id" 
					value="<?php echo ( (isset($venta[0]['fe_payment_form_id'])) ? $venta[0]['fe_payment_form_id'] : 1 ) ?>">
				<input type="hidden" name="idventa" id="idventa" 
					value="<?php if(isset($venta[0]['venta_id'])) echo $venta[0]['venta_id'] ?>">
				<input type="hidden" name="cajero" id="cajero" 
					value="<?php echo $this->session->userdata("nUsuCodigo"); ?>">
				<input type="hidden" name="isadmin" id="isadmin" 
					value="<?php echo $this->session->userdata("admin"); ?>">
        <input type="hidden" id="venta_status" value="<?= COMPLETADO ?>" name="venta_status">
				<input type="hidden" id="condicion_pago" value="0" name="condicion_pago">
				<input type="hidden" id="condicion_pago_id" name="condicion_pago_id">
        <input type="hidden" id="maneja_descuentos" value="0" name="maneja_descuentos">
        <input type="hidden" id="maneja_impresion" value="0" name="maneja_impresion">
        <input type="hidden" id="documento_generar" value="0" name="documento_generar">
        <input type="hidden" id="afiliado" value="" name="afiliado">

				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="form-group">
								<div class="col-md-4">
									<label for="id_vendedor" class="control-label">Vendedor(Ctrl + Q)</label>
									<select name="id_vendedor" id="id_vendedor" class='form-control chosen' required="true" <?= $disabled; ?>>
										<option value="">Seleccione</option>
										<?php if (count($vendedores) > 0) : ?>
											<?php foreach ($vendedores as $vendedor) : ?>
												<option value="<?php echo $vendedor['nUsuCodigo']; ?>" 
													<?php 
													if ((isset($venta[0]['id_vendedor']) 
													and $venta[0]['id_vendedor'] == $vendedor['nUsuCodigo']) 
													or (!isset($venta[0]['id_vendedor'])))
														echo 'selected' ?> 
												> 
													<?php echo $vendedor['nUsuCodigo'] . '-' . $vendedor['nombre']; ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<div class="col-md-3">
										<label for="tipoventa" class="control-label">Tipo de venta(Ctrl + A)</label>
										<?php if ($disabled) { ?>
											<input type="hidden" name="tipoventa" id="tipoventa" value="<?= $venta[0]['venta_tipo'] ?>">
										<?php } ?>
										<select name="tipoventa" id="<?= ($disabled) ? '' : 'tipoventa' ?>" class='form-control chosen' onchange="Venta.getTipoVenta()" required="true" <?= $disabled; ?>>
											<option value="">Seleccione</option>
											<?php if (count($tipos_venta) > 0) : ?>
												<?php foreach ($tipos_venta as $tipoventa) : ?>
													<option value="<?php echo $tipoventa['tipo_venta_id']; ?>" 
														<?php 
														if ((isset($venta[0]['venta_tipo']) 
														and $venta[0]['venta_tipo'] == $tipoventa['tipo_venta_id']) 
														or (!isset($venta[0]['venta_tipo']) && $tipoventa['tipo_venta_nombre'] == 'MOSTRADOR'))
															echo 'selected' ?>
													>
														<?php echo $tipoventa['tipo_venta_nombre']; ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
								</div>

                                <div class="col-md-3">
                                    <label for="id_cliente" class="control-label">Cliente(Ctrl + M)</label>

                                    <select name="id_cliente" id="id_cliente" class='form-control' onchange="Venta.changeCliente()" required="true" <?= $disabled; ?>>
                                        <option value="" selected>Seleccione</option>
                                        <?php if (count($clientes) > 0) : ?>
                                            <?php foreach ($clientes as $cl) : ?>
                                                <option value="<?php echo $cl['id_cliente']; ?>" <?php if ((isset($venta[0]['cliente_id']) and $venta[0]['cliente_id'] == $cl['id_cliente'])
                                                                                                        or ($cl['nombres'] == 'CONSUMIDOR') && !isset($devolver) &&  $notadebito == '0'
                                                                                                    ) echo 'selected' ?>><?php echo $cl['nombres'] . " " . $cl['apellidos']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>


                                </div>
                                <div class="col-md-2 hidden-xs">

                                    <?php if (!isset($devolver)) { ?>

                                        <br>
                                        <button type="button" class="btn btn-info " data-toggle="modal" onclick="Venta.agregarCliente()">
                                            <i class="fa fa-plus-circle"></i> Nuevo
                                        </button>


                                    <?php } ?>
                                </div>


                            </div>
                        </div>


                        <?php
                        $FACT_E_ALLOW = $this->session->userdata('FACT_E_ALLOW');
                        if ($FACT_E_ALLOW === '1' && !isset($devolver)) {
                        ?>
                            <div class="row">

                                <div class="col-md-2 ">
                                    <label class="custom-control-label" for="customSwitches">Factura de
                                        contingencia</label>
                                    <input type="checkbox" class="custom-control-input" name="feContingenciaCheck" id="feContingenciaCheck">

                                </div>
                                <div class="col-md-5 hidden " id="selectFeDocument">
                                    <label class="custom-control-label" for="customSwitches">Tipo documento</label>
                                    <select id="feTypeDocument" name="feTypeDocument" class="form-control">
                                        <option value="3">Factura electrónica de contingencia FACTURADOR</option>
                                        <option value="4" selected>Factura electrónica de contingencia DIAN</option>
                                    </select>
                                </div>
                                <div class=" hidden" id="fact_electronica_contingencia_facturador">
                                    <div class="col-md-2">
                                        <label class="custom-control-label" for="customSwitches">Prefio y número de
                                            documento</label>
                                        <input type="text" name="fe_referencia" class="form-control" id="fe_referencia">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="custom-control-label" for="customSwitches">Fecha de emisión del
                                            documento</label>
                                        <input type="text" readonly name="fe_referencia_date" class="date form-control" id="fe_referencia_date">
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                        <div class="row">
													<?php if (isset($devolver)) { ?>
														<div class="form-group">
															<div class="col-md-4">
																<label for="cliente" class="control-label">Motivo devolucion</label>
																<select name="tipo_devolucion" id="tipo_devolucion" class='form-control chosen' required="true">
																	<?php if (count($tipos_devolucion) > 0) : ?>
																		<?php foreach ($tipos_devolucion as $cl) : ?>
																			<option value="<?php echo $cl['tipo_devolucion_id']; ?>"><?php echo $cl['tipo_devolucion_nombre'] ?></option>
																		<?php endforeach; ?>
																	<?php endif; ?>
																</select>
															</div>
														</div>
													<?php } ?>
													<br>

                            <?php if (!isset($devolver)) { ?>
                                <div class="col-md-12">

                                    <div class="row">
                                        <?php foreach ($unidades_medida as $unidad) { ?>
                                            <div class="col-xs-2 hidden-xs text-danger">
                                                Existencia en <?= $unidad['nombre_unidad'] ?>
                                            </div>
                                            <div class="col-xs-2 hidden-xs">
                                                <label id="existencia_<?= $unidad['id_unidad'] ?>" class="label label-warning"></label>
                                            </div>
                                        <?php } ?>

                                    </div>

                                    <div class="row ">
                                        <?php foreach ($unidades_medida as $unidad) { ?>
                                            <div class="col-md-2 hidden-xs">
                                                Contenido interno <?= $unidad['nombre_unidad'] ?>
                                            </div>
                                            <div class="col-md-2 hidden-xs">
                                                <label id="contenido_<?= $unidad['id_unidad'] ?>" class="label label-success"></label>
                                            </div>
                                        <?php } ?>


                                    </div>

                                    <div class="row ">
                                        <?php foreach ($unidades_medida as $unidad) { ?>
                                            <div class="col-md-2 hidden-xs">
                                                Precio de venta <?= $unidad['nombre_unidad'] ?>
                                            </div>
                                            <div class="col-md-2 hidden-xs">
                                                <label id="precio_venta_<?= $unidad['id_unidad'] ?>" class="label label-info"></label>
                                            </div>
                                        <?php } ?>

                                    </div>

                                </div>
                            <?php } ?>
                        </div>
                        <br>


                        <div class="row ">
                            <div class="col-md-12">
                                <div id="" class="table-responsive" style="position: relative;">
                                    <table id="tablalistaventa" class="table table-striped dataTable table-bordered
                                             table-hover table-featured">
                                        <thead>
                                            <tr>

                                                <th style="padding-top: 0px; padding-bottom: 0px">PRODUCTO</th>
                                                <th style="padding-top: 0px; padding-bottom: 0px">NOMBRE PRODUCTO</th>

                                                <!-- TODO HABILITAR LA CANTIDAD DE LAS UNIDADES SI EL PRODUCTO ESTA CONFIGURADO PARA VENDER ESA UNIDAD-->
                                                <?php foreach ($unidades_medida as $unidad) : ?>
                                                    <th style="padding-top: 0px; padding-bottom: 0px"><?= $unidad['nombre_unidad'] ?></th>
                                                    <?php if (isset($devolver) && $notadebito == '0') {
                                                    ?>
                                                        <th style="padding-top: 0px; padding-bottom: 0px">Devolver</th>
                                                    <?php
                                                    } ?>
                                                    <?php if (isset($devolver) && $notadebito == '1') {
                                                    ?>
                                                        <th style="padding-top: 0px; padding-bottom: 0px">Agregar</th>
                                                    <?php
                                                    } ?>
                                                    <th style="padding-top: 0px; padding-bottom: 0px">
                                                        TOTAL <?= $unidad['nombre_unidad'] ?></th>
                                                <?php endforeach; ?>

                                                <th style="padding-top: 0px; padding-bottom: 0px">DESC. VALOR</th>
                                                <th style="padding-top: 0px; padding-bottom: 0px">DESC. %</th>
                                                <th style="padding-top: 0px; padding-bottom: 0px">TOTAL PRODUCTO</th>

                                            </tr>
                                        </thead>
                                        <tbody id="tbodyproductos">

                                            <?php if (!isset($devolver)) {
                                            ?>
                                                <tr id="trvacio">
                                                    <td style="padding-top: 0px; padding-bottom: 0px">
                                                        <input type="text" id="inputsearchproduct" placeholder="Ctrl + G" class="form-control inputsearchproduct">
                                                    </td>
                                                    <td style="padding-top: 0px; padding-bottom: 0px"></td>
                                                    <?php foreach ($unidades_medida as $unidad) : ?>
                                                        <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" class="form-control">
                                                        </td>
                                                        <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" class="form-control" readonly></td>
                                                    <?php endforeach; ?>

                                                    <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" class="form-control">
                                                    </td>
                                                    <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" class="form-control">
                                                    </td>
                                                    <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" class="form-control" readonly>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                            $countproductos = 0;

                                            foreach ($venta as $ven) {

                                                /*if ($ven['preciounitario'] > 0) {
                                            $bono = $countproductos;
                                        }*/

                                            ?>

                                                <script type="text/javascript">
                                                    var nombre = "<?php echo $ven["nombre"]; ?>";


                                                    var detalle_unidad = new Array();

                                                    <?php
                                                    if (isset($ven['detalle_unidad'])) {
                                                        foreach ($ven['detalle_unidad'] as $unidad) {

                                                    ?>
                                                            var unidad_det = {};

                                                            unidad_det.unidad_id = <?= $unidad['unidad_id'] ?>;
                                                            unidad_det.cantidad = '<?= $unidad['cantidad'] ?>';
                                                            unidad_det.precio = '<?= $unidad['precio'] ?>';
                                                            unidad_det.detalle_venta_id = '<?= $unidad['detalle_venta_id'] ?>';
                                                            unidad_det.utilidad = '<?= $unidad['utilidad'] ?>';
                                                            unidad_det.costo = '<?= $unidad['costo'] ?>';
                                                            unidad_det.id_unidad = '<?= $unidad['id_unidad'] ?>';
                                                            unidad_det.nombre_unidad = '<?= $unidad['nombre_unidad'] ?>';
                                                            unidad_det.estatus_unidad = '<?= $unidad['estatus_unidad'] ?>';
                                                            unidad_det.abreviatura = '<?= $unidad['abreviatura'] ?>';
                                                            unidad_det.orden = '<?= $unidad['orden'] ?>';
                                                            detalle_unidad.push(unidad_det);
                                                            Venta.devolver = $('#devolver').val();
                                                            Venta.unidades = <?= json_encode($unidades_medida) ?>


                                                    <?php
                                                        }
                                                    }
                                                    ?>


                                                    Venta.addProductoToArray(<?php echo $ven['producto_id']; ?>,
                                                        encodeURIComponent(nombre),
                                                        <?= isset($ven['porcentaje_impuesto_backup']) ? $ven['porcentaje_impuesto_backup'] : 0; ?>,
                                                        <?= isset($ven['porcentaje_otro_impuesto_backup']) ? $ven['porcentaje_otro_impuesto_backup'] : 0; ?>,
                                                        '<?php echo $ven['tipo_impuesto']; ?>', '<?php echo $ven['tipo_otro_impuesto']; ?>',
                                                        <?php echo $ven['is_paquete']; ?>, <?= $ven['control_inven'] ?>,
                                                        '<?= $ven['producto_tipo'] ?>', '<?= $ven['producto_codigo_interno'] ?>',
                                                        '<?= $ven['fe_type_item_identification_id'] ?>',
                                                        '<?= $ven['fe_impuesto'] ?>',
                                                        '<?= $ven['fe_otro_impuesto'] ?>', detalle_unidad);

                                                    Venta.addproductototable(<?php echo $ven['producto_id']; ?>, nombre,
                                                        <?php echo $countproductos; ?>,
                                                        <?= isset($ven['porcentaje_impuesto_backup']) ? $ven['porcentaje_impuesto_backup'] : 0; ?>,
                                                        detalle_unidad, '<?php echo $ven['precio_abierto']; ?>', '<?php echo $ven['descuento']; ?>',
                                                        '<?php echo $ven['desc_porcentaje']; ?>');
                                                </script>
                                                <?php $countproductos++; ?>
                                            <?php

                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="col-md-12 visible-xs">
                                <button id="mobileSerch" class="btn btn-success" type="button" onclick="Venta.mobileSearch()">BUSCAR</button>
                            </div>
                        </div>




                        <br>

                        <div class="row ">
                            <div class="col-md-12">
                                <div class="col-md-4 block">

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label">Total:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="input-prepend input-append input-group">
                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="excluido" id="excluido" readonly value="0.00">

                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="subtotal" id="subtotal" readonly value="0.00">

                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="totApagar2" id="totApagar2" readonly value="0.00">


                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="totApagar_backup" id="totApagar_backup" readonly value="<?= isset($venta[0]['montoTotal']) ? $venta[0]['montoTotal'] : '' ?>">

                                                    <input type="hidden" class='input-square input-small form-control' name="totApagar" id="totApagar" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label">Entregado (Ctrl+S):</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="input-prepend input-append input-group">
                                                    <?php
                                                    $totalpagado = 0;
                                                    if (isset($formaspago) && sizeof($formaspago) > 0) {

                                                        foreach ($formaspago as $fm) {
                                                            $totalpagado = $totalpagado + $value = $fm->monto;
                                                        }
                                                    }

                                                    $totalpagadomostrar = isset($devolver) && !empty($venta[0]['pagado']) ? $venta[0]['pagado'] : $totalpagado;
                                                    ?>
                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="number" class='input-square input-small form-control' name="dineroentregado" id="dineroentregado" onkeydown="return soloDecimal3(this, event);" onblur="return Venta.calcular_importe(event);" onkeyup="return Venta.calcular_importe(event);" value="<?php echo $totalpagadomostrar ?>" <?php echo isset($devolver) ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label">Cambio:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="cambiomostrar" id="cambiomostrar" readonly value="0.00">

                                                    <input type="hidden" class='input-square input-small form-control' name="cambio" id="cambio" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label"><b>Nota:</b></label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea name="nota" class="form-control"><?= isset($venta[0]['nota']) ? $venta[0]['nota'] : '' ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row" style="padding-bottom: 20px">
                                        <div class="col-md-12">
                                            <button class="btn btn-success  waves-effect waves-light btn-group-justified" id="terminarventa" type="button">
                                                <i class="fa fa-save fa-2x"></i>
                                                <b> F6 <?= !isset($devolver) ? 'FACTURAR' : 'GUARDAR' ?></b>
                                            </button>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-default btn-outline waves-effect waves-light btn-group-justified " id="terminarventapendiente" type="button" <?php echo isset($devolver) ? 'disabled' : ''; ?>>
                                                <i class="fa fa-clock-o fa-2x "></i>
                                                <b>V. PENDIENTE(F7)</b>
                                            </button>
                                        </div>
                                    </div>

                                    <?php
                                    $FACT_E_ALLOW = $this->session->userdata('FACT_E_ALLOW');
                                    if ($FACT_E_ALLOW === '1' && !isset($devolver)) {
                                    ?>

                                        <div class="row">

                                            <div class="col-md-12">
                                                <button class="btn btn-info  waves-effect waves-light btn-group-justified " id="facturarElectronicamente" type="button" <?php echo isset($devolver) ? 'disabled' : ''; ?>>
                                                    <i class="fa fa-cloud-upload fa-2x "></i>
                                                    <b>FACT ELECTRÓNICA</b>
                                                </button>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-4">

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label class="control-label">Dsc. valor (Ctrl+L):</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span>

                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="descuentoenvalorhidden" id="descuentoenvalorhidden" onkeydown="return soloDecimal3(this, event);" onkeyup="return Venta.calculadescuentos(event);" value="<?php echo isset($venta[0]['descuento_valor']) && $venta[0]['desc_global'] ? $venta[0]['descuento_valor'] : ''; ?>" <?php echo isset($devolver) ? 'readonly' : ''; ?>>


                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="descuentoenvalor" id="descuentoenvalor" onkeydown="return soloDecimal3(this, event);" onkeyup="return Venta.calculadescuentos(event);" value="<?php echo isset($venta[0]['descuento_valor']) && $venta[0]['desc_global'] ? $venta[0]['descuento_valor'] : ''; ?>" <?php echo isset($devolver) ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label class="control-label">Dsc. porcentaje (Ctrl+O):</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span>

                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="descuentoenporcentajehidden" id="descuentoenporcentajehidden" onkeydown="return soloDecimal3(this, event);" onkeyup="return Venta.calculadescuentos(event);" value="<?php echo isset($venta[0]['porcentaje_desc']) && $venta[0]['desc_global'] ? $venta[0]['porcentaje_desc'] : ''; ?>" <?php echo isset($devolver) ? 'readonly' : ''; ?>>

                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="descuentoenporcentaje" id="descuentoenporcentaje" onkeydown="return soloDecimal3(this, event);" onkeyup="return Venta.calculadescuentos(event);" value="<?php echo isset($venta[0]['porcentaje_desc']) && $venta[0]['desc_global'] ? $venta[0]['porcentaje_desc'] : ''; ?>" <?php echo isset($devolver) ? 'readonly' : ''; ?>>

                                                    <input style="font-size: 14px;
																							font-weight: bolder;" type="hidden" class='input-square input-small form-control' name="descuentoenporcentajehidden_devolver" id="descuentoenporcentajehidden_devolver" onkeydown="return soloDecimal3(this, event);" <?php echo isset($devolver) ? 'readonly' : ''; ?>>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label class="control-label">Base Gravada:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="basegravada2" id="basegravada2" readonly value="0.00">
                                                    <input type="hidden" class='input-square input-small form-control' name="basegravada" id="basegravada" readonly value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label class="control-label">Valor total Iva:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="iva2" id="iva2" readonly value="0.00">
                                                    <input type="hidden" class='input-square input-small form-control' name="iva" id="iva" readonly value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label class="control-label">Otros impuestos:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-prepend input-append input-group">
                                                    <span class="input-group-addon"><?= MONEDA ?></span><input style="font-size: 14px;
																							font-weight: bolder;" type="text" class='input-square input-small form-control' name="otros_impuestos2" id="otros_impuestos2" readonly value="0.00">
                                                    <input type="hidden" class='input-square input-small form-control' name="otros_impuestos" id="otros_impuestos" readonly value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <!--<div class="row">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default btn-group-justified">Descuentos</button>
                                        </div>
                                    </div>-->
                                    <div class="row">

                                        <button type="button" onclick="Venta.diretPrintCotizar()" <?php echo isset($devolver) ? 'disabled' : ''; ?> class="btn btn-success btn-outline waves-effect waves-light  btn-group-justified">
                                            Cotizar(F4)
                                        </button>

                                    </div>
                                    <div class="row">

                                        <button type="button" class="btn btn-default btn-outline waves-effect waves-light btn-group-justified" id="formadepago" disabled>
                                            Formas
                                            de pago (F1)
                                        </button>

                                    </div>
                                    <div class="row">
                                        <button type="button" class="btn btn-info btn-group-justified" id="abrirventas" <?php echo isset($devolver) ? 'disabled' : ''; ?>>
                                            Abrir
                                        </button>
                                        <!-- <button type="button" class="btn btn-default" id="reiniciar">
                                             Reiniciar
                                         </button>-->
                                    </div>

                                    <div class="row">

                                        <button id="cancelar" type="button" class="btn btn-warning btn-group-justified">
                                            Salir
                                        </button>


                                    </div>

                                    <!--<div class="row">
                                        <div class="form-group">
                                            <button class="btn" type="button" id="cancelar"><i
                                                    class="fa fa-remove fa-3x text-warning fa-fw"></i> </br>
                                                Salir
                                            </button>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="confirmar_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title">Confirmaci&oacute;n</h4>
                                    </div>

                                    <div class="modal-body">

                                        <h3>Estas seguro que deseas eliminar este producto?</h3>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" id="eliminar_item" class="btn btn-primary">Confirmar
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="$('#confirmar_delete').modal('hide');">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>

                        </div>
                        <div class="modal fade" id="generarventa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">

                                        <h3>Desea imprimir <?= isset($devolver) ? 'el recibo' : 'la factura' ?>?</h3>

                                        <div id="fact_elect_errors_print">

                                        </div>
                                    </div>
                                    <div class="modal-footer">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-success  btn-outline waves-effect waves-light " type="button" id="realizarventa" onclick="javascript:Venta.hacerventa(0);"><i class="fa fa-save"></i> (F2)No
                                                </button>
                                                <a href="#" class="btn btn-success  waves-effect waves-light" id="btnRealizarVentaAndView" onclick="javascript:Venta.hacerventa(1);" type="button"><i class="fa fa-print"></i> (F6)Si
                                                </a>
                                                <button class="btn btn-default waves-effect waves-light closegenerarventa" type="button"><i class="fa fa-close"></i> Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal bs-example-modal-lg bs-example-modal-xl"  id="seleccionunidades" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close closeseleccionunidades" data-dismiss="modal" aria-hidden="true">&times;
                                        </button>
                                        <h4 class="modal-title">Productos</h4>
                                        <h5 id="nombreproduto"></h5>
                                    </div>
                                    <div class="modal-body" id="modalbodyproducto">

                                        <div class="row ">
                                            <table id="tablaproductos" class="table table-responsive datatable table-bordered table-striped ">
                                                <thead>
                                                    <th>ID</th>
                                                    <?php
                                                    $cont = 0;
                                                    $yaentroenunidades = false;
                                                    if ($columnasToProd) {

                                                        foreach ($columnasToProd as $columna) {

                                                            if ($columna->mostrar == 1) {

                                                                if (

                                                                    $columna->nombre_columna == 'cant'
                                                                    ||
                                                                    $columna->nombre_columna == 'precio'
                                                                    ||
                                                                    $columna->nombre_columna == 'porcent_utilidad'


                                                                ) {

                                                                    if ($yaentroenunidades == false) {

                                                                        $yaentroenunidades = true;

                                                                        foreach ($unidades_medida as $unidad) {

                                                                            if ($columna->nombre_columna == 'cant') {
                                                                                echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                                                if ($columnasToProd[6]->mostrar == 1) echo '<th>' . $columnasToProd[6]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                                                if ($columnasToProd[7]->mostrar == 1) echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                                            }

                                                                            if ($columna->nombre_columna == 'precio') {
                                                                                echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                                                if ($columnasToProd[7]->mostrar == 1) echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
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


                                                </tbody>
                                            </table>
                                            <button id="select_product" onclick="Venta.agregarProducto();" class="btn btn-success" type="button">SELECCIONAR</button>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </form>

        </div>
        <div class="modal " id="precioabiertomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closeprecioabiertomodal" data-dismiss="modal" aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Precio abierto </h4>
                        <h5 id="nombreproduto"></h5>
                    </div>
                    <div class="modal-body" id="">

                        <form id="precioabiertoform">
                            <input type="hidden" id="precioabiertoproducto">
                            <?php

                            foreach ($unidades_medida as $unidad) {
                            ?>
                                <div class="row">
                                    <div class="col-md-3"> Valor de <?= $unidad['nombre_unidad'] ?>
                                    </div>
                                    <div class="col-md-6"> Utilice entre <span id="permitido_<?= $unidad['id_unidad'] ?>"></span></div>
                                    <div class="col-md-3">
                                        <input readonly class="form-control disabled" onkeydown="return soloNumeros(event);" name="precio_abierto_<?= $unidad['id_unidad'] ?>" id="precio_abierto_<?= $unidad['id_unidad'] ?>">

                                        <input type="hidden" class="form-control disabled" name="minimo_<?= $unidad['id_unidad'] ?>" id="minimo_<?= $unidad['id_unidad'] ?>">
                                        <input type="hidden" class="form-control disabled" name="maximo_<?= $unidad['id_unidad'] ?>" id="maximo_<?= $unidad['id_unidad'] ?>">
                                    </div>
                                </div>
                            <?php


                            } ?>


                            <button type="button" class="btn btn-success" id="aceptarprecioabierto" onclick="Venta.aceptarPrecioAbierto()">Aceptar
                            </button>

                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" id="formasdepagomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Formas de pago</h4>
                        <h5 id="nombreproduto"></h5>

                    </div>
                    <div class="modal-body" id="modalbodyproducto">
                        <div class="span"><strong>Total a pagar $:</strong> <span id="formpagototalapagar"></span></div>
                        <form id="formaspagoform">
                            <div class="row">
                                <table id="formasdepagotable" class="table datatable table-bordered table-striped ">
                                    <thead>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>N&uacute;mero de recibo</th>
                                    </thead>
                                    <tbody id="">
                                        <?php foreach ($metodos_pago as $metodo) {

                                            $value = '';
                                            $recibo = '';

                                            if (isset($formaspago) && sizeof($formaspago) > 0) {

                                                foreach ($formaspago as $fm) {
                                                    if ($fm->id_forma_pago == $metodo['id_metodo']) {

                                                        $value = $fm->monto;
                                                        $recibo = $fm->nro_recibo;
                                                    }
                                                }
                                            }

                                        ?>
                                            <tr id="<?= $metodo['id_metodo'] ?>" data-name="<?= $metodo['id_metodo'] ?>">
                                                <td>
                                                    <?= $metodo['id_metodo'] ?>
                                                </td>
                                                <td><?= $metodo['nombre_metodo'] ?></td>
                                                <td>
                                                    <input type="hidden" class=" form-control" name="forma_pago[]" value="<?= $metodo['id_metodo'] ?>" id="forma_pago_<?= $metodo['id_metodo'] ?>" onkeydown="return soloDecimal3(this, event);">
                                                    <input type="hidden" class=" form-control" name="forma_pago_fe_id_[]" value="<?= $metodo['fe_method_id'] ?>" id="forma_pago_fe_id_<?= $metodo['id_metodo'] ?>" onkeydown="return soloDecimal3(this, event);">
                                                    <input type="text" class="formsdepagoinput form-control" name="forma_pago_monto_[]" value="<?= $value ?>" id="forma_pago_monto_<?= $metodo['id_metodo'] ?>" onkeydown="return soloDecimal3(this, event);">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="numero_recibo_monto_[]" value="<?= $recibo ?>" id="numero_recibo_monto_<?= $metodo['id_metodo'] ?>" onkeydown="return soloDecimal3(this, event);">
                                                </td>
                                            </tr>
                                        <?php

                                        } ?>

                                    </tbody>
                                </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" id="aceptarformasdepago">Aceptar</a>
                    <a href="#" class="btn btn-default clsoeformasdepago" data-dismiss="modal">Salir</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mvcotizarVenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="ventasabiertas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modificarcantidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closemodificarcantidad" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Editar cantidad</h4>
                    <h5 id="nombreproduto2"></h5>
                </div>
                <div class="modal-body" id="modalbodycantidad">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">Cantidad:</div>
                            <div class="col-md-3">
                                <input type="number" id="cantidadedit" class="form-control" onkeydown="return soloDecimal3(this, event);">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">Precio:</div>
                            <div class="col-md-3">
                                <input type="number" id="precioedit" class="form-control" onkeydown="return soloDecimal3(this, event);">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-default" type="button" id="guardarcantidad">
                                <i class="fa fa-save"></i>Guardar
                            </button>
                            <button class="btn btn-default closemodificarcantidad" type="button">
                                <i class="fa fa-close"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_facturacion_electronica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <h3>Se han encontrado los siguientes errores al generar la facturación electrónica:</h3>
                    <div id="fact_elect_errors">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="reintentar_bn" class="btn btn-success" onclick="Venta.hacerventa(1, 1);">
                        Reintentar
                    </button>
                    <?php if ((isset($devolver) && $devolver != '1') || !isset($devolver)) {
                    ?>
                        <button type="button" id="" class="btn btn-default" onclick="Venta.processPending();">Guardar
                            como venta pendiente
                        </button>
                    <?php
                    } ?>
                    <button type="button" class="btn btn-default" onclick="$('#modal_facturacion_electronica').modal('hide');">
                        Cancelar
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

    <div class="modal fade" id="confirmar_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmaci&oacute;n</h4>
                </div>
                <div class="modal-body">
                    <h3>Estas seguro que deseas eliminar este producto?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" id="eliminar_item" class="btn btn-primary">Confirmar</button>
                    <button type="button" class="btn btn-default" onclick="$('#confirmar_delete').modal('hide');">
                        Cancelar
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <div class="modal fade" id="agregarclienteventa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>
        <input type="hidden" id="base_url" value="<?= base_url() ?>">
    </div>
</div>
<div id="mensajeprodcalert" class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-warning myadmin-alert-bottom alertbottom"><i class="ti-user"></i><span id="mensajeproducto"></span> <a href="#" class="closed">×</a></div>

<script>
    $(document).ready(function() {
        $(".date").datepicker({
					format: 'dd-mm-yyyy'
        });
        Venta.init(<?= count($venta) ?>, <?= json_encode($unidades_medida) ?>, <?= json_encode($droguerias) ?>, <?= json_encode($tipos_devolucion) ?>, <?= json_encode($tipos_venta) ?>, <?= json_encode($clientes) ?>, <?= $last_factura['documento_Numero'] ?>);
        App.sidebar('close-sidebar');
    });
</script>