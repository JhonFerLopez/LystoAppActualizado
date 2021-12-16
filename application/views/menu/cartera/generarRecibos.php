<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Elaboración de recibos</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <form id="frmBuscar">

                <div class="block-section block-alt-noborder">
                    <div class="row">

                        <div class="col-md-5">
                            <label>Cliente: </label>
                            <select name="cboCliente" id="cboCliente" class='form-control select-chosen'
                                    onchange="Cartera.buscarRecibos()">
                                <option value="-1">Seleccionar</option>
                                <?php if (count($lstCliente) > 0): ?>
                                    <?php foreach ($lstCliente as $cl): ?>
                                        <option
                                                value="<?php echo $cl['id_cliente']; ?>"><?php echo $cl['nombres'] . " " . $cl['apellidos']; ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-2 col-md-offset-1">
                            <label>Número de recibo</label>
                            <input readonly class="form-control" size="2" value="<?= $recibo ?>" id="id_recibo">
                        </div>

                    </div>
                </div>
            </form>

            <div class="block-section block-alt-noborder">
                <div class="row">
                    <?php $ruta = base_url(); ?>

                    <div class="col-md-6">
                        <div class="block row">

                            <?php $ruta = base_url(); ?>
                            <!--<script src="<?php echo $ruta; ?>recursos/js/custom.js"></script>-->
                            <div class="col-md-12 text-right">
                                <label class="control-label badge label-success">D&iacute;as < 8</label>
                                <label class="control-label badge label-warning">D&iacute;as < 31</label>
                                <label class="control-label badge label-danger">D&iacute;as >= 31</label>
                            </div>
                            <div class="col-md-12">
                                <table
                                        class='table table-striped dataTable table-bordered no-footer table-condensed'
                                        id="lstPagP" name="lstPagP">
                                    <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th class='tip' title="Monto Credito Solicitado">Deuda <?php echo MONEDA ?></th>
                                        <th>D&iacute;as vencidos</th>
                                        <th>Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <!--  <div class="col-md-6">Número de factura</div>
                              <div class="col-md-6"><input class="form-control" name="numero_factura"
                                                           id="numero_factura"/>
                              </div>-->
                            <div class="col-md-6">Valor a abonar <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="valor_abonar"
                                                         id="valor_abonar" onkeydown="return soloDecimal(this,event);"/>
                            </div>
                            <!-- <div class="col-md-6">Descuento otorgado <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="descuento_otorgado"
                                                         id="descuento_otorgado"/>
                            </div>
                            <div class="col-md-6">Retención practicada <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="retencion_practicada"
                                                         id="retencion_practicada"/></div>
                            <div class="col-md-6">Retención IVA <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="retencion_iva"
                                                         id="retencion_iva"/></div>
                            <div class="col-md-6">Retención ICA <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="retencion_ica"
                                                         id="retencion_ica"/></div>
                            <div class="col-md-6">Otras retenciones <?= MONEDA ?></div>
                            <div class="col-md-6"><input class="form-control" name="otras_retenciones"
                                                         id="otras_retenciones"/></div>-->


                            <div class="col-md-6">Tipo de pago</div>
                            <div class="col-md-6"><select class="form-control select-chosen" name="metodo" id="metodo"
                                                          onchange="Cartera.toogleBanco()">
                                    <option value="">Seleccione</option>
                                    <?php
                                    if (count($metodos) > 0) {
                                        foreach ($metodos as $metodo) { ?>
                                            <option
                                                    value="<?= $metodo['id_metodo'] ?>"><?= $metodo['nombre_metodo'] ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-6 carterabanco">Código del banco</div>
                            <div class="col-md-6 carterabanco">

                                <select class="form-control select-chosen" name="codigo_banco" id="codigo_banco">
                                    <option value="">Seleccione</option>
                                    <?php
                                    if (count($bancos) > 0) {
                                        foreach ($bancos as $banco) { ?>
                                            <option
                                                    value="<?= $banco['banco_id'] ?>"><?= $banco['banco_nombre'] ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-6 carterabanco">Número de documento</div>
                            <div class="col-md-6 carterabanco"><input class="form-control" name="numero_documento"
                                                                      id="numero_documento"/></div>
                            <div class="col-md-6 carterabanco">Fecha de consignación</div>
                            <div class="col-md-6 carterabanco"><input class="form-control input-datepicker"
                                                                      name="fecha_consignacion"
                                                                      id="fecha_consignacion"/></div>

                            <div class="col-md-6">Observaciones Adicionales</div>
                            <div class="col-md-6"><textarea class="form-control" placeholder="Observaciones Adicionales" name="observaciones_adicionales"
                                                            id="observaciones_adicionales" ></textarea></div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label>Saldo <?= MONEDA ?></label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" id="saldo" value="0" readonly>

                    </div>

                    <div class="col-md-1">

                        <label>Recibo <?= MONEDA ?></label>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" id="monto_seleccionado" value="0" readonly>
                    </div>


                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" id="guardarPago" onclick=" $('#generarventa').modal('show');">
                            Aceptar
                        </button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-default" >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="generarventa" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <h3>Desea imprimir el recibo?</h3>

            </div>
            <div class="modal-footer">

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success  btn-outline waves-effect waves-light btn_imprimir_recibo"
                                type="button" id="realizarventa"
                                onclick="javascript:Cartera.guardarPago(0);"><i
                                    class="fa fa-save"></i> (F2)No
                        </button>
                        <button href="#" class="btn btn-success  waves-effect waves-light btn_imprimir_recibo"
                           id="btnRealizarVentaAndView"
                           onclick="javascript:Cartera.guardarPago(1);" type="button"><i
                                    class="fa fa-print"></i> (F6)Si
                        </button>
                        <button class="btn btn-default waves-effect waves-light closegenerarventa"
                                type="button" onclick="$('#generarventa').modal('hide');"><i
                                    class="fa fa-close"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="mCopiaReciboCartera" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">

                <h3>Desea imprimir imprimir una copia?</h3>

            </div>
            <div class="modal-footer">

                <div class="row">
                    <div class="col-md-12">

                        <a href="#" class="btn btn-success  waves-effect waves-light"
                           id="btnRealizarVentaAndView"
                           onclick="javascript:Cartera.imprimircopia();" type="button"><i
                                    class="fa fa-print"></i> Si
                        </a>
                        <button class="btn btn-default waves-effect waves-light closegenerarventa"
                                type="button" onclick="$('#mCopiaReciboCartera').modal('hide');"><i
                                    class="fa fa-close"></i> No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seccion Visualizar -->

<div class="modal fade" id="borrar" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="form_notacredito" id="form_notacredito" method="post"
          action="<?= $ruta ?>venta/guardar_notacredito">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">Nota de Cr&eacute;dito</h4>
                </div>
                <div class="modal-body">
                    <h5><p>Est&aacute; seguro que desea registrar una nota de cr&eacute;dito
                            para la venta numero:

                        <div id="abrir_venta"></div>
                        </p></h5>
                    <input type="hidden" name="id" id="id_venta">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary"
                            onclick="guardar_notacredito()">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Cancelar
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
</div>


<script type="text/javascript">
    $('document').ready(function () {
        Cartera.init(<?php echo json_encode($metodos);?>,'<?= $this->session->userdata('TIPO_IMPRESION') ?>',
            '<?= $this->session->userdata('IMPRESORA') ?>','<?=  $this->session->userdata('USUARIO_IMPRESORA'); ?>');
    });
</script>