<?php $ruta = base_url(); ?>
<div class="modal-dialog ">
    <div class="modal-content">

        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vista Previa</h4>
        </div>
        <div class="modal-body">


            <!------AQUI COMIENZA CADA NOTA DE ENTREGA ---->


            <?php


            if (isset($ventas[0])) {


                ?>
                <div class="col-md-12 text-center">
                    ESTADO DE LA VENTA: <?= $ventas[0]['venta_status'] ?>

                    <?php
                    if (isset($ventas[0]['tipo_anulacion_nombre'])) {
                        ?>
                        <label class="label label-info"><?= $ventas[0]['tipo_anulacion_nombre'] ?></label>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }


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
                            <?php if (isset($ventas[0]['dias']) && $ventas[0]['dias'] != 0) { ?>

                                <div class="row dash">

                                    <div class="col-xs-12">
                                        VENTA A CREDITO
                                    </div>


                                </div>
                            <?php } ?>

                            <?php if (isset($ventas[0]['genera_control_domicilios']) && $ventas[0]['genera_control_domicilios'] == 1) { ?>

                                <div class="row dash">

                                    <div class="col-xs-12">
                                        VENTA A DOMICILIO
                                    </div>


                                </div>
                            <?php } ?>


                            <?php if (isset($ventas[0]['documento_cliente']) && $ventas[0]['documento_cliente'] != ''
                                && $ventas[0]['documento_cliente'] != null && !empty($ventas[0]['documento_cliente'])
                            ) { ?>
                                <div class="row ">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['documento_cliente'] ?>
                                    </div>


                                </div>
                                <!-- info row -->
                                <div class="row ">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] ?>
                                    </div>


                                </div>
                                <div class="row dash">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['direccion_cliente'] ?>
                                    </div>


                                </div>
                                <div class="row dash">

                                    <div class="col-xs-12">
                                        <?= $ventas[0]['zona_nombre'] ?>
                                    </div>


                                </div>
                            <?php } ?>


                            <div class="row">
                                <div class="col-xs-6">
                                    FACTURA DE VENTA Nº:
                                </div>
                                <div class="col-xs-6">
                                    <?= isset($ventas[0]['resolucion_prefijo']) ? $ventas[0]['resolucion_prefijo'].'-' : '' . "" ?><?=  isset($ventas[0]['numero']) ? $ventas[0]['numero'] : '' ?>
                                </div>


                            </div>
                            <div class="row dash">
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


                                    <?= $ventas[0]['cajero_id'] ?>
                                </div>
                                <div class="col-xs-2">
                                    Vendedor:


                                    <?= $ventas[0]['id_vendedor'] ?>
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


                                        foreach ($ventas as $venta) {



                                            if (isset($venta['detalle_unidad'])) {
                                                foreach ($venta['detalle_unidad'] as $detalle_unidad) {
                                                    if ($detalle_unidad['cantidad'] > 0) {
                                                        $um = isset($detalle_unidad['abreviatura']) ? $detalle_unidad['abreviatura'] : $detalle_unidad['nombre_unidad'];
                                                        $detalle_unidad['cantidad'];
                                                        $cantidad = $detalle_unidad['cantidad'];


                                                        $subtotal = ($cantidad * $detalle_unidad['precio']);


                                                        //TODO VER QUE LOS IMPUESTOS SEPARADOS LOS ETSE CALCULANDO BIEN Y NO LOS ESTE DUPLICANDO

                                                        if (isset($venta['id_impuesto'])) {

                                                            $actual_impuesto = isset($totales_impuestos[$venta['id_impuesto']]) ? $totales_impuestos[$venta['id_impuesto']] : 0;

                                                            $totales_impuestos[$venta['id_impuesto']] = $actual_impuesto + $detalle_unidad['impuesto'];
                                                        }


                                                        if (isset($venta['id_otro_impuesto'])) {

                                                            $actual_impuesto = isset($totales_impuestos[$venta['id_otro_impuesto']]) ? $totales_impuestos[$venta['id_otro_impuesto']] : 0;
                                                            $totales_impuestos[$venta['id_otro_impuesto']] = $actual_impuesto + $detalle_unidad['otro_impuesto'];
                                                        }

                                                        ?>

                                                        <row>

                                                            <div class="col-md-4"><?= $venta['producto_codigo_interno'] ?></div>
                                                            <div class="col-md-8"><?= $venta['nombre'] ?></div>
                                                            <div class="col-md-3"><?php echo $cantidad ?></div>
                                                            <div class="col-md-3"><?php echo $detalle_unidad['precio'] ?></div>
                                                            <div class="col-md-3" align=""><?php echo $subtotal ?></div>
                                                            <div class="col-md-3"><?php echo $um ?></div>


                                                        </row>
                                                        <?php
                                                    }
                                                }
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
                            <?php $totaldesctablaventa = $ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'];
                            $descuento= $totaldesctablaventa > 0 ? $totaldesctablaventa : $ventas[0]['totaldescuento'];?>
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
                                                           id="totalR"><?php

                                                       if ($ventas[0]['regimen_iva'] == 1)
                                                           echo number_format($ventas[0]['subTotal'] , 2, ',', '.');
                                                       else
                                                           echo number_format($ventas[0]['montoTotal']+$descuento, 2, ',', '.');
                                                       ?></span>
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
                                                           id="totalR"><?php
                                                       echo number_format($descuento, 2, ',', '.') ?></span>
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
                                                <?php echo number_format($ventas[0]['impuesto'], 2, ',', '.') ?>
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
                                                <?php echo number_format($ventas[0]['total_otros_impuestos'], 2, ',', '.') ?>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <td>
                                            TOTAL FACTURA
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($ventas[0]['montoTotal'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            VALOR ENTREGADO
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($ventas[0]['pagado'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            CAMBIO
                                        </td>
                                        <td>
                                            ---
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($ventas[0]['cambio'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="row text-center">
                                <?php

                                if ($ventas[0]['regimen_iva'] == 1) {
                                    foreach ($impuestos as $impuesto) { ?>

                                        <div class="col-xs-3">
                                            <?= $impuesto['nombre_impuesto'] ?>
                                            <br>
                                            <?= isset($totales_impuestos[$impuesto['id_impuesto']]) ? $totales_impuestos[$impuesto['id_impuesto']] : '' ?>
                                        </div>
                                        <?php

                                    }
                                } ?>


                            </div>

                            <?php

                            if ($ventas[0]['regimen_iva'] == 1) { ?>
                                <div class="row text-center">
                                    <div class="col-xs-12">
                                        <h6>AUTORIZACION NUMERACION SEGUN RESOLUCION
                                            N <?= $ventas[0]['resolucion_numero'] ?>
                                            del <?= $ventas[0]['resolucion_fech_aprobacion'] ?>

                                            DEL <?= $ventas[0]['resolucion_prefijo'] ?>
                                            -<?= $ventas[0]['resolucion_numero_inicial'] ?>
                                            AL <?= $ventas[0]['resolucion_prefijo'] ?>
                                            -<?= $ventas[0]['resolucion_numero_final'] ?>
                                        </h6>
                                    </div>
                                    <div class="col-xs-12">
                                        <h6><?= $this->session->userdata('MENSAJE_FACTURA') ?></h6>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                    </div>
                <?php } ?>
            </div>

        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
            <!--   <a href="#" tabindex="0" type="button" id="imprimir" class="btn btn-primary"> <i
                           class="fa fa-print"></i>Imprimir</a>-->
        </div>
    </div>

</div>


<script>
    $(function () {

        $("#imprimir").click(function (e) {
            e.preventDefault();

            console.log('imprimiendo ...');
            var id_venta = '<?php echo $id_venta; ?>';
            var url = '<?= $ruta ?>venta/directPrint/' + id_venta;
            console.log(url);
            window.open(url);


        });

        setTimeout(function () {
            $("#imprimir").focus();
        }, 500);
    })
</script>

