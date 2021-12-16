<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vista Previa</h4>
        </div>
        <div class="modal-body">

            <div class="panel row nota_entrega_seccion" class="text-center" id="panel_documento">


                <div class="col-md-12" class="resumen_venta" ID="resumen_venta">
                    <div>
                        <div class="row dash">
                            <div class="col-xs-12">
                                <span id=""><?= isset($detalles[0]->documento_nombre) ? strtoupper($detalles[0]->documento_nombre) : '' ?></span>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xs-5">
                                Movimiento Nº:
                            </div>
                            <div class="col-xs-7">
                                <?= isset($detalles[0]->id_ajusteinventario) ? isset($detalles[0]->id_ajusteinventario) : '' ?>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xs-5">
                                Fecha Movimiento:
                            </div>
                            <div class="col-xs-7">
                                <?= date('Y-m-d', strtotime($detalles[0]->fecha)) ?>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-xs-5">
                               Usuario:
                            </div>
                            <div class="col-xs-7">
                                <?= $detalles[0]->username ?>
                            </div>
                        </div>
                    </div>
                    <div class="row dash">
                        <div class="col-xs-12">
                            <table id="tabla_resumen_productos" class="">
                                <thead>
                                <tr>
                                    <th style="border-bottom: 1px #000 dashed; width: 60%">Codigo</th>
                                    <th style="border-bottom: 1px #000 dashed; width: 20%"> Nombre</th>
                                    <th style="border-bottom: 1px #000 dashed; width: 20%">Unidad</th>
                                    <th style="border-bottom: 1px #000 dashed; width: 20%">Cantidad</th>
                                    <th style="border-bottom: 1px #000 dashed; width: 20%">Costo</th>
                                   
                                </tr>
                                </thead>
                                <tbody id="detalle_contenido_producto">
                                <?php
                                $total = 0;
                                if (isset($detalles)) {
                                    foreach ($detalles as $detalle) {
                                        $total = ($detalle->costo) +$total;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $detalle->producto_id ?>
                                            </td>
                                            <td>
                                                <?= $detalle->producto_nombre ?>
                                            </td>
                                            <td>
                                                <?= $detalle->nombre_unidad ?>
                                            </td>
                                            <td>
                                                <?= $detalle->cantidad_detalle ?>
                                            </td>
                                            <td>
                                            <!-- aqui en realidad s esta almacenando el costo toal como costo unitario en kardex-->
                                                <?= $detalle->costo ?>
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
                    <div class="row">

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


                    </div>

                </div>

            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
            <a href="#" tabindex="0" type="button" id="imprimir" class="btn btn-primary"> <i
                        class="fa fa-print"></i>Imprimir</a>
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

