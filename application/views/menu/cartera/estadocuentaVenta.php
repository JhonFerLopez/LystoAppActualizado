<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Movimientos de cartera</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Parametrizaci&oacute;n</li>
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

            <form id="frmBuscar">

                <div class="block-section block-alt-noborder">

                    <div class="row">
                        <div class="col-md-1">
                            <span class="add-on">Filtrar por:</span>
                        </div>
                        <div class="col-md-1">
                            Fecha de abono
                        </div>
                        <div class="col-md-2">
                            <input type="radio" name="filtro" value="ABONO" checked>
                        </div>
                        <div class="col-md-1">
                            Fecha de la venta
                        </div>
                        <div class="col-md-1">
                            <input type="radio" name="filtro" value="VENTA">
                        </div>
                        <div class="col-md-1">
                            D&iacute;as vencimiento
                        </div>
                        <div class="col-md-3">

                            <select name="diasvencimiento" id="diasvencimiento" class='form-control '>
                                <option value="-1">Seleccionar</option>
                                <option value="<8">DÍAS < 8</option>
                                <option value="<31">DÍAS < 31</option>
                                <option value=">=31">DÍAS >= 31</option>

                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-1">
                            <span class="add-on">Fecha:</span>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="fecIni" id="fecIni"
                                   class='form-control' value="<?php echo date('d-m-Y') ?>">
                        </div>
                        <div class="col-md-1">
                            hasta
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="fecFin" id="fecFin" class='form-control'
                                   value="<?php echo date('d-m-Y') ?>">
                        </div>

                        <div class="col-md-1">
                            Cliente
                        </div>
                        <div class="col-md-3">

                            <select name="cboCliente" id="cboCliente" class='form-control '>
                                <option value="-1">Seleccionar Cliente</option>
                                <?php if (count($lstCliente) > 0): ?>
                                    <?php foreach ($lstCliente as $cl): ?>
                                        <option value="<?php echo $cl['id_cliente']; ?>"><?php echo $cl['nombres'] . " " . $cl['apellidos']; ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                <?php endif; ?>
                            </select></div>

                        <button id="btnBuscar" class="btn btn-default" type="button" onclick="buscar()">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12">
        <div class="white-box">


            <?php $ruta = base_url(); ?>
            <!--<script src="<?php echo $ruta; ?>recursos/js/custom.js"></script>-->
            <div class="col-md-12 text-right">
                <label class="control-label badge label-success">D&iacute;as < 8</label>
                <label class="control-label badge label-warning">D&iacute;as < 31</label>
                <label class="control-label badge label-danger">D&iacute;as >= 31</label>
            </div>


            <table class='table dataTable table-striped dataTable table-bordered no-footer table-condensed' id="lstPagP"
                   name="lstPagP">
                <thead>
                <tr>
                    <th>ID venta</th>
                    <th>Documento</th>
                    <th>Nro Venta</th>
                    <th>Cliente</th>
                    <th class='tip' title="Fecha Registro">Fecha de venta.</th>
                    <th class='tip' title="Monto Credito Solicitado">Importe Venta <?php echo MONEDA ?></th>
                    <th class='tip' title="Monto Cancelado">Monto Canc <?php echo MONEDA ?></th>
                    <th class='tip' title="Monto Cancelado">Monto Restante <?php echo MONEDA ?></th>
                    <th class='tip' title="Monto Cancelado">Vendedor</th>

                    <th>D&iacute;as de atraso</th>
                    <th>Estado&nbsp;</th>
                    <th>Acciones</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>


            <div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <form name="form_notacredito" id="form_notacredito" method="post"
                      action="<?= $ruta ?>venta/guardar_notacredito">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title">Nota de Cr&eacute;dito</h4>
                            </div>
                            <div class="modal-body">
                                <h5><p>Est&aacute; seguro que desea registrar una nota de cr&eacute;dito para la
                                        venta numero:

                                    <div id="abrir_venta"></div>
                                    </p></h5>
                                <input type="hidden" name="id" id="id_venta">
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="confirmar" class="btn btn-primary"
                                        onclick="guardar_notacredito()">
                                    Confirmar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                </button>

                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>

            </div>
            <!--- ----------------- -->

            <!-- Pagar Visualizar -->
            <div class="modal fade" id="pagar_venta" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">

            </div>
            <!--- ----------------- -->


        </div>
    </div>
</div>

<div class="modal fade" id="modal_mostrar_observacion"  role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Observación</h4>
            </div>
            <div class="modal-body" >
                <p id="p_observacion"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

</div>


<script>

    Cartera.TIPO_IMPRESION = '<?= $this->session->userdata('TIPO_IMPRESION') ?>';
    Cartera.IMPRESORA= '<?= $this->session->userdata('IMPRESORA') ?>';
    Cartera.USUARIO_IMPRESORA= '<?=  $this->session->userdata('USUARIO_IMPRESORA'); ?>';

    $(document).ready(function () {

        $('select').chosen();
        $("#fecIni").datepicker({format: 'dd-mm-yyyy'});
        $("#fecFin").datepicker({format: 'dd-mm-yyyy'});
        $("#fecAbonoIni").datepicker({format: 'dd-mm-yyyy'});
        $("#fecAbonoFin").datepicker({format: 'dd-mm-yyyy'});
        buscar();
    });

    function buscar() {

        TablesDatatablesLazzy.init('<?php echo base_url()?>cartera/lst_reg_estadocuenta_json', 0, 'lstPagP', {
            fecIni: $("#fecIni").val(),
            fecFin: $("#fecFin").val(),
            filtro: $("input[name='filtro']:checked").val(),
            cboCliente: $("#cboCliente").val(),
            diasvencimiento:$("#diasvencimiento").val()
        });


    }

</script>