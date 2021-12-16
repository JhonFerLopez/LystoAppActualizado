<?php $ruta = base_url(); ?>
<!-- Load and execute javascript code used only in this page -->
<input type="hidden" id="FACT_E_habilitacionn" value="<?= $this->session->userdata('FACT_E_habilitacionn'); ?>">
<input type="hidden" id="FACT_E_syncrono" value="<?= $this->session->userdata('FACT_E_syncrono'); ?>">
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Anular ventas</h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>
            <li class="active">Ventas</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- END Datatables Header -->
<div class="row">


    <div class="col-md-12">

        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success alert-dismissable" id="success" style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                    <span id="successspan"><?php echo isset($success) ? $success : '' ?>
                </div>
                </span>
            </div>
        </div>

        <div class="white-box">
            <div class="box">

                <div class="box-content box-nomargin">

                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class='table table-striped dataTable table-bordered'>
                                <thead>
                                    <tr>

                                        <th>Id. Venta</th>
                                        <th>Nro. Factura</th>
                                        <th>Cliente</th>
                                        <th>Fecha Reg</th>
                                        <th>Monto Total <?php echo MONEDA ?></th>
                                        <th>Estatus</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($ventas) > 0) : ?>
                                        <?php foreach ($ventas as $venta) : ?>
                                            <tr>
                                                <?php
                                                $numeracion = '';


                                                if (empty($venta->uuid)) {

                                                    $numeracion = !empty($venta->resolucion_prefijo) ? $venta->resolucion_prefijo . "-" . $venta->documento_Numero : $venta->documento_Numero;
                                                } else {
                                                    $numeracion  = "<label class='label label-info'> " . $venta->fe_prefijo . "-" .  $venta->fe_numero . "</label>";
                                                }
                                                ?>

                                                <td><?php echo $venta->id_venta; ?></td>
                                                <td><?php echo $numeracion; ?></td>
                                                <td><?php echo $venta->nombres . ' ' . $venta->apellidos; ?></td>
                                                <td style="text-align: center;"><?php echo $venta->fecha; ?></td>
                                                <td style="text-align: center;"><?php echo $venta->total; ?></td>
                                                <td style="text-align: center;"><?php echo $venta->venta_status; ?></td>
                                                <td class='actions_big'>

                                                    <?php if (empty($venta->uuid)) { ?>
                                                        <div class="btn-group">
                                                            <a onclick="VentaAnular.anular(<?php echo $venta->venta_id; ?>, '<?php echo $venta->uuid; ?>')" class='btn btn-outline btn-default waves-effect waves-light tip'><i class="fa fa-remove"></i>
                                                                Anular</a>
                                                            <a style="cursor:pointer;" onclick="Venta.verVenta(<?php echo $venta->venta_id; ?>)" class='btn btn-outline btn-default waves-effect waves-light tip' title="Ver Venta">
                                                                <i class="fa fa-search"></i>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="anular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form name="formeliminar" method="post" id="formeliminar" action="<?= $ruta ?>venta/anular_venta">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Anular Venta</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    Tipo de anulación
                                </div>
                                <div class="col-md-8">
                                    <select class="select-chosen" name="motivo" id="motivo" onchange="VentaAnular.changeTipo()">
                                        <?php
                                        foreach ($tipos as $tipo) {
                                        ?>
                                            <option value="<?= $tipo['tipo_anulacion_id'] ?>"><?= $tipo['tipo_anulacion_nombre'] ?></option>
                                        <?php
                                        } ?>
                                    </select>

                                </div>
                                <input type="hidden" name="id" id="id" required="true" class="form-control">
                                <input type="hidden" name="tipo_anulacion_nombre" id="tipo_anulacion_nombre" required="true" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="anularbutton" class="btn btn-primary" onclick="VentaAnular.guardar()">
                                Confirmar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </form>
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
                <button type="button" id="" class="btn btn-success" onclick="VentaAnular.guardar();"> Reintentar</button>

                <button type="button" class="btn btn-default" onclick="$('#modal_facturacion_electronica').modal('hide');">
                    Cancelar
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

</div>

<script>
    $(function() {
        VentaAnular.init(<?= json_encode($tipos) ?>);
    });
</script>