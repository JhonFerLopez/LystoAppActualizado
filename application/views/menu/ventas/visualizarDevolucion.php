<?php $ruta = base_url(); ?>
<div class="modal-dialog ">
    <div class="modal-content">

        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vista Previa - Devolución </h4>
        </div>
        <div class="modal-body">


            <!------AQUI COMIENZA CADA NOTA DE ENTREGA ---->


            <?php


            ?>
            <br>
            <br>

            <div class="panel row nota_entrega_seccion" class="text-center" id="panel_documento">
                <?php if (isset($ventas[0])) {

                    //var_dump($ventas[0] );


                ?>
                    <div class="col-md-12" class="resumen_venta" ID="resumen_venta">
                        <div>
                            <div class="row ">
                                <div class="col-xs-12">

                                    <span id="">NOTA DE DEVOLUCION DE PRODUCTOS</span>

                                </div>
                            </div>


                            <div class="col-xs-4">
                                Fecha:<br>


                                <?= date('Y-m-d', strtotime($ventas[0]['fechaemision'])) ?>
                            </div>
                            <div class="col-xs-3">
                                Hora:


                                <?= date('h:i A', strtotime($ventas[0]['fechaemision'])) ?>
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


                        <div class="row dash">
                            <div class="col-xs-6 ">
                                FACTURA DE VENTA Nº:
                            </div>
                            <div class="col-xs-6">
                                <?= isset($ventas[0]['resolucion_prefijo']) ? $ventas[0]['resolucion_prefijo'] . '-' : '' . "" ?><?= isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '' ?>
                            </div>


                        </div>

                        <?php if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) { ?>
                            <div class="row">

                                <div class="col-xs-2">
                                    Vence:
                                </div>
                                <div class="col-xs-10">

                                    <?php

                                    $fecha_inicio_plan = date('Y-m-d H:i:s');
                                    $fecha_fin_plan = new DateTime($fecha_inicio_plan);
                                    date_add($fecha_fin_plan, date_interval_create_from_date_string($ventas[0]['dias'] . ' day'));

                                    $fecha_fin_plan = date_format($fecha_fin_plan, 'd/m/Y');
                                    ?>
                                    <?= $fecha_fin_plan ?>
                                </div>

                            </div>
                            <div class="row dash">

                                <div class="col-xs-12">
                                    Vencimiento <?= $ventas[0]['dias'] ?> a partir de la fecha
                                </div>


                            </div>
                        <?php } ?>


                    </div>
                    <!-- /.row -->
                    <!-- Table row -->
                    <div class="row dash">
                        <div class="col-xs-12">
                            <div id="tabla_resumen_productos">

                                <div id="detalle_contenido_producto" class="row">
                                    <?php

                                    $subtotal_total = 0;
                                    $total_total = 0;
                                    foreach ($detalle_devolucion as $detalle) {


                                        if ($detalle->cantidad > 0) {


                                            $um = isset($detalle->abreviatura) ? $detalle->abreviatura : $detalle->nombre_unidad;

                                            $cantidad = $detalle->cantidad;
                                            $producto_codigo_interno = $detalle->producto_codigo_interno;

                                            $precio = $detalle->precio;
                                            $nombre = $detalle->producto_nombre;

                                            $subtotal = $detalle->subtotal;
                                            $subtotal_total = $subtotal_total + $detalle->subtotal_total;
                                            $total_total = $total_total + $detalle->total_total;
                                    ?>

                                            <row>

                                                <div class="col-md-4"><?= $producto_codigo_interno ?></div>
                                                <div class="col-md-8"><?= $nombre ?></div>
                                                <div class="col-md-3"><?php echo $precio ?>$</div>
                                                <div class="col-md-3"><?php echo $cantidad ?></div>
                                                <div class="col-md-3" align=""><?php echo $subtotal ?></div>
                                                <div class="col-md-3"><?php echo $um ?></div>


                                            </row>
                                        <?php


                                        } ?>


                                    <?php

                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- END TABLA DE PRODUCTOS -->
                    <div class="row">
                        <?php


                        $descuento = isset($detalle_devolucion[0]) ? $detalle_devolucion[0]->descuento_total : 0;


                        if (isset($detalle_devolucion[0])) :

                        ?>


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
                                            <span id="subtotalR"><span id="totalR"><?php

                                                                                    if ($ventas[0]['regimen_iva'] == 1)
                                                                                        echo number_format( $subtotal_total, 2, ',', '.');
                                                                                    else
                                                                                        echo number_format($total_total + $descuento, 2, ',', '.');
                                                                                    ?></span>
                                        </td>
                                    </tr>

                                    <?php if ($ventas[0]['regimen_iva'] == 1) { ?>


                                        <tr>
                                            <td>
                                                Total IVA
                                            </td>
                                            <td>
                                                ---
                                            </td>
                                            <td>
                                                <!--  <label id="igvR">12</label>-->
                                                <?php echo number_format($detalle_devolucion[0]->impuesto_total, 2, ',', '.') ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                Otros impuestos
                                            </td>
                                            <td>
                                                ---
                                            </td>
                                            <td>
                                                <!--  <label id="igvR">12</label>-->
                                                <?php echo number_format($detalle_devolucion[0]->otro_impuesto_total, 2, ',', '.') ?>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <td>
                                            Descuento
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="totalR"><?= number_format($detalle_devolucion[0]->descuento_total, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            TOTAL DEVOLUCION
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="totalR"><?= number_format($total_total, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                        <?php endif; ?>

                    </div>

            </div>
        <?php } ?>
        </div>


        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
            <!--   <a href="#" tabindex="0" type="button" id="imprimir" class="btn btn-primary"> <i
                           class="fa fa-print"></i>Imprimir</a>-->
        </div>
    </div>

</div>


<script>
    $(function() {

        $("#imprimir").click(function(e) {
            e.preventDefault();

            console.log('imprimiendo ...');
            var id_venta = '<?php echo $id_venta; ?>';
            var url = '<?= $ruta ?>venta/directPrint/' + id_venta;
            console.log(url);
            window.open(url);


        });

        setTimeout(function() {
            $("#imprimir").focus();
        }, 500);
    })
</script>