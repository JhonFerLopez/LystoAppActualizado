<div class="modal-dialog">
    <div class="modal-content">

        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vista Previa</h4>
        </div>
        <div class="modal-body">


            <div class="panel row nota_entrega_seccion" class="text-center" id="panel_documento">
                <?php if (isset($ventas[0])) {

                    $ventas[0] = $ventas[0];


                    ?>
                    <div class="col-md-12" class="resumen_venta" ID="resumen_venta">
                        <div>
                            <div class="row ">
                                <div class="col-xs-12">

                                        <span
                                                id=""><?= isset($ventas[0]['REPRESENTANTE_LEGAL']) ? strtoupper($ventas[0]['REPRESENTANTE_LEGAL']) : '' ?></span>

                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-xs-12">

                                        <span
                                                id=""><?= isset($ventas[0]['RazonSocialEmpresa']) ? strtoupper($ventas[0]['RazonSocialEmpresa']) : '' ?></span>

                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-xs-6">

                                        <span
                                                id="">NIT <?= isset($ventas[0]['NIT']) ? strtoupper($ventas[0]['NIT']) : '' ?></span>

                                </div>

                                <div class="col-xs-6">

                                        <span
                                                id="">REGIMEN <?= isset($ventas[0]['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas[0]['REGIMEN_CONTRIBUTIVO']) : '' ?></span>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">

                                        <span
                                                id=""><?= isset($ventas[0]['DireccionEmpresa']) ? $ventas[0]['DireccionEmpresa'] : '' ?></span>

                                </div>
                            </div>

                            <div class="row dash">
                                <div class="col-xs-12">

                                    Telf: <span
                                            id=""><?= isset($ventas[0]['TelefonoEmpresa']) ? $ventas[0]['TelefonoEmpresa'] : '' ?></span>

                                </div>


                            </div>

                            <div class="row dash">
                                <div class="col-xs-12">

                                    COTIZACION DE MEDICAMENTOS

                                </div>


                            </div>

                            <?php if (isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != '' && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])) { ?>
                                <div class="row ">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['documento_cliente'] ?>
                                    </div>


                                </div>
                                <!-- info row -->
                                <div class="row dash">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['cliente'] ?>
                                    </div>


                                </div>
                                <div class="row dash">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['direccion_cliente'] ?>
                                    </div>


                                </div>


                            <?php } ?>

                            <div class="row dash">
                                <div class="col-xs-4">
                                    Fecha:<br>


                                    <?= date('Y-m-d') ?>
                                </div>
                                <div class="col-xs-3">
                                    Hora:


                                    <?= date('h:i A') ?>
                                </div>


                                <div class="col-xs-2">
                                    Cajero:


                                    <?= $ventas[0]['id_vendedor'] ?>
                                </div>
                                <div class="col-xs-2">
                                    Vendedor:


                                    <?= $ventas[0]['id_vendedor'] ?>
                                </div>
                            </div>

                        </div>
                        <!-- /.row -->
                        <!-- Table row -->
                        <div class="row dash">
                            <div class="col-xs-12">
                                <div id="tabla_resumen_productos" class="">
                                    <!-- <thead>
                                     <tr>
                                         <th style="border-bottom: 1px #000 dashed; width: 60%">Descripci&oacute;n</th>

                                         <th style="border-bottom: 1px #000 dashed; width: 20%"> Cantidad</th>


                                         <th style="border-bottom: 1px #000 dashed; width: 20%">Importe</th>
                                     </tr>
                                     </thead>-->
                                    <div id="detalle_contenido_producto">
                                        <?php

                                        foreach ($ventas as $venta) {
                                            ?>


                                            <?php

                                            foreach ($venta['unidades'] as $detalle_unidad) {
                                                $detalle_unidad = (array)$detalle_unidad;


                                                if ($detalle_unidad['cantidad'] > 0) {
                                                    $um = isset($detalle_unidad['abreviatura']) ? $detalle_unidad['abreviatura'] : $detalle_unidad['nombre_unidad'];
                                                    $cantidad = $detalle_unidad['cantidad'];


                                                    ?>

                                                    <div class="row">

                                                        <div class="col-md-4"><?= $venta['producto_codigo_interno'] ?></div>
                                                        <div class="col-md-8"><?= $venta['nombre'] ?></div>
                                                        <div class="col-md-3"><?php echo $cantidad ?></div>
                                                        <div class="col-md-3"><?php echo $detalle_unidad['precio'] ?></div>
                                                        <div class="col-md-3"><?php echo($cantidad * $detalle_unidad['precio']) ?></div>
                                                        <div class="col-md-3"><?php echo $um ?></div>


                                                    </div>
                                                    <?php
                                                }
                                            } ?>


                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- END TABLA DE PRODUCTOS -->
                        <div class="row">

                            <div class="col-xs-12 col-lg-12">
                                <table class="table" id="totales_">
                                    <tr>
                                        <td>
                                            Subtotal
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($ventas[0]['subtotal'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Descuento
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <?php if ($ventas[0]['regimen_iva'] == 1) { ?>
                                        <tr>
                                            <td>
                                                Excluido
                                            </td>
                                            <td>
                                                ---
                                            </td>
                                            <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($ventas[0]['excluido'], 2, ',', '.') ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Gravado
                                            </td>
                                            <td>
                                                ---
                                            </td>
                                            <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($ventas[0]['gravado'], 2, ',', '.') ?></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Total IVA
                                            </td>
                                            <td>
                                                ---
                                            </td>
                                            <td>
                                                <!--  <label id="igvR">12</label>-->
                                                <?php echo number_format($ventas[0]['total_impuesto'], 2, ',', '.') ?>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <td>
                                            TOTAL COTIZACION
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($ventas[0]['total'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="row text-center">

                                <div class="col-xs-12">
                                    <h6><?= $this->session->userdata('MENSAJE_FACTURA') ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php } ?>
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


