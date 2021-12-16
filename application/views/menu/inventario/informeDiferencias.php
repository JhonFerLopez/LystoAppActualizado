<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Informe de diferencias</h4>
        </div>
        <div class="modal-body">

            <div class="panel row nota_entrega_seccion " class="text-center" id="">



                <div class="col-md-12" class="resumen_venta" ID="resumen_venta">
                    <div>
                        <div class="row dash">
                            <div class="col-xs-12">
                                <span id=""><?= isset($detalles[0]) ? strtoupper($detalles[0]->documento_nombre) : '' ?></span>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xs-5">
                                Movimiento Nº:
                            </div>
                            <div class="col-xs-7">
                                <?= isset($detalles[0]) ? isset($detalles[0]->id_ajusteinventario) : '' ?>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xs-5">
                                Fecha Movimiento:
                            </div>
                            <div class="col-xs-7">
                                <?= date('Y-m-d', strtotime(isset($detalles[0]) ? $detalles[0]->fecha : '')) ?>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-xs-5">
                                Usuario:
                            </div>
                            <div class="col-xs-7">
                                <?= isset($detalles[0]) ? $detalles[0]->usuario_username : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row dash">
                        <div class="col-xs-12">
                            <table id="tabla_resumen_productos" class="" cellpadding="12" cellspacing="20">
                                <thead>
                                <tr>
                                    <th style="border: 1px #000 solid; ">Codigo</th>
                                    <th style="border: 1px #000 solid;">Nombre</th>
                                    <th style="border: 1px #000 solid;">Unidad</th>
                                    <!--  <th style="border-bottom: 1px #000 dashed;">Costo</th>-->
                                    <th style="border: 1px #000 solid; ">Cantidad</th>
                                    <th style="border: 1px #000 solid; ">Diferencia</th>
                                    <th style="border: 1px #000 solid; ">Costo</th>
                                    <th style="border: 1px #000 solid; ">Tipo</th>
                                    <th style="border: 1px #000 solid;">Antes</th>
                                    <th style="border: 1px #000 solid;">Despues</th>
                                </tr>
                                </thead>
                                <tbody id="detalle_contenido_producto">
                                <?php
                                $total = 0;
                                if (isset($detalles) && sizeof($detalles) > 0) {
                                    foreach ($detalles as $detalle) {
                                        ?>
                                        <tr>
                                            <td style="border: 1px #000 solid; ">
                                                <?= $detalle->producto_id ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?= $detalle->producto_nombre ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?= $detalle->abreviatura ?>
                                            </td>
                                            <!--<td>
                                                <?= $detalle->costo ?>
                                            </td>-->

                                            <td style="border: 1px #000 solid; ">
                                                <?= $detalle->cantidad_detalle ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?= number_format($detalle->nKardexCantidad, 0) ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?= number_format($detalle->costo_diferencia, 2, ',', '.') ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?= $detalle->cKardexTipo ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?php $stokactual = json_decode($detalle->stockUManterior);
                                                $stok = "";
                                                if (sizeof($stokactual) > 0) {
                                                    foreach ($stokactual as $key => $value) {
                                                        if ($key == $detalle->id_unidad) {
                                                            $stok = $value->cantidad;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?= $stok ?>
                                            </td>
                                            <td style="border: 1px #000 solid; ">
                                                <?php $stokactual = json_decode($detalle->stockUMactual);
                                                if (sizeof($stokactual) > 0) {
                                                    foreach ($stokactual as $key => $value) {
                                                        if ($key == $detalle->id_unidad) {
                                                            $stok = $value->cantidad;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?= $stok ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <!-- END TABLA DE PRODUCTOS -->
                    <!-- <div class="row">

                        <div class="col-xs-12 col-lg-12">
                            <table class="table" id="totales_">


                                <tr>
                                    <td>
                                        TOTAL
                                    </td>
                                    <td>
                                        ---
                                    </td>
                                    <td>
                                        <?php echo MONEDA ?> <span
                                                id="totalR"><?= ceil($total * 10) / 10 ?></span>
                                    </td>
                                </tr>

                            </table>
                        </div>


                    </div>-->

                </div>

            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
            <a href="#" tabindex="0" type="button" id="imprimir" class="btn btn-primary"> <i
                        class="fa fa-print"></i>Impresora normal</a>
            <a href="#" tabindex="0" type="button" id=""
               onclick="AjusteInventario.directPrintInformeDiferencia(<?= isset($detalles[0]) ? $detalles[0]->id_ajusteinventario : '' ?>)"
               class="btn btn-primary"> <i
                        class="fa fa-print"></i>Impresora fiscal</a>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>recursos/js/printThis.js"></script>
<script>
    $(function () {
        $("#imprimir").click(function (e) {
            e.preventDefault();
            $("#resumen_venta").printThis({
                importCSS: true,
                loadCSS: "<?= base_url()?>recursos/css/page.css"
            });
        });
        setTimeout(function () {
            $("#imprimir").focus();
        }, 500);
    })
</script>

