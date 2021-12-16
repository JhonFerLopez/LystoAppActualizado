<?php $ruta = base_url(); ?>


<!------AQUI COMIENZA CADA NOTA DE ENTREGA ---->


<div class="panel row nota_entrega_seccion" class="text-center" id="panel_documento">


    <div class="col-md-12" class="resumen_venta" ID="resumen_venta">
        <div>
            <div class="row ">
                <div class="col-xs-12">

                                        <span
                                                id=""><?= $this->session->userdata('REPRESENTANTE_LEGAL') ?></span>

                </div>
            </div>
            <div class="row ">
                <div class="col-xs-12">

                                        <span
                                                id=""><?= $this->session->userdata('EMPRESA_NOMBRE') ?></span>

                </div>
            </div>
            <div class="row ">
                <div class="col-xs-6">

                                        <span
                                                id="">NIT <?= $this->session->userdata('NIT') ?></span>

                </div>

                <div class="col-xs-6">

                                        <span
                                                id="">REGIMEN <?= $REGIMEN_CONTRIBUTIVO['regimen_nombre'] ?></span>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">

                                        <span
                                                id=""><?= $this->session->userdata('EMPRESA_DIRECCION') ?></span>

                </div>
            </div>

            <div class="row dash">
                <div class="col-xs-12">

                    Telf: <span
                            id=""><?= $this->session->userdata('EMPRESA_TELEFONO') ?></span>

                </div>


            </div>


            <div class="row ">

                <div class="col-xs-12">
                    REPORTES/COMPROBANTEDIARIOVENTAS
                </div>


            </div>


            <div class="row dash">
                <div class="col-xs-4">
                    FECHA DE GENERACIÓN:<br>


                    <?= date('d-m-Y h:i A', strtotime($fecha_impreso)) ?>
                </div>
                <div class="col-xs-3">
                    FECHA IMPRESIÓN:


                    <?= date('d-m-Y h:i A') ?>
                </div>


            </div>
            <div class="row dash">
                <div class="col-xs-6">
                    Factura:


                    <?= $factura_inicio ?>
                </div>
                <div class="col-xs-6">
                    Al:


                    <?= $factura_fin ?>
                </div>
            </div>


        </div>
        <!-- /.row -->

        <div class="row dash">

            <div class="col-md-1">Forma de pago</div>
            <div class="col-md-5"></div>
            <div class="col-md-3">Valor total</div>
            <div class="col-md-3">Registro</div>


        </div>

        <!-- Table row -->
        <div class="row dash">
            <div class="col-xs-12">
                <div id="tabla_resumen_productos">

                    <div id="detalle_contenido_producto" class="row">
                        <?php


                        $totalefectivo = 0;
                        $totalotros = 0;
                        $totaldesceuntos = 0;
                        $abonosacartera = 0;
                        $abnosaseparados = 0;
                        $gastosporcaja = 0;
                        $pagosproveedor = 0;
                        $pagosproveedorefect = 0;

                        $totalgravado = 0;
                        $totalexcluido = 0;
                        $totaliva = 0;
                        $totalotrosimpuestos = 0;

                        $venta_id = '';

                        foreach ($formaspago as $formapago) {


                            $totales = $formapago['totales'];

                            //var_dump($totales)

                            ?>

                            <div class="row">

                                <div class="col-md-1"><?= $formapago['id_metodo'] ?></div>
                                <div class="col-md-5"><?= $formapago['nombre_metodo'] ?></div>
                                <div class="col-md-3"><?php echo number_format($totales['total'], 2, ',', '.') ?></div>
                                <div class="col-md-3"><?php echo $totales['totalregistros'] ?></div>


                                <?php


                                if ($formapago['suma_total_ingreso'] == 1) {
                                    $totalefectivo = $totalefectivo + $totales['total'];
                                } else {
                                    $totalotros = $totalotros + $totales['total'];
                                }




                                //  echo $totales['excluido']." ". $totales['gravado']." ". $totales['iva'];


                                    $totaldesceuntos = $totaldesceuntos + $totales['descuentos'];
                                    $totalgravado = $totalgravado + $totales['gravado'];
                                    $totalexcluido = $totalexcluido + $totales['excluido'];
                                    $totaliva = $totaliva + $totales['iva'];
                                    $totalotrosimpuestos = $totalotrosimpuestos + $totales['otros_impuestos'];

                                $restar_credito = $calculodevoluciones_credito['total'] + $calculoanulaciones_credito['total'];




                                ?>

                            </div>


                        <?php }


                        ?>

                        <div class="row">

                            <div class="col-md-1"></div>
                            <div class="col-md-5">Devolucion Contado</div>
                            <div class="col-md-3"><?= number_format($calculodevoluciones['total'], '2', ',', '.') ?></div>
                            <div class="col-md-3"><?= $calculodevoluciones['registros'] + $calculodevoluciones_credito['registros'] ?></div>

                        </div>

                        <div class="row">

                            <div class="col-md-1"></div>
                            <div class="col-md-5">Devolucion Credito</div>
                            <div class="col-md-3"><?= number_format($calculodevoluciones_credito['total'], '2', ',', '.') ?></div>
                            <div class="col-md-3"><?= $calculodevoluciones['registros'] + $calculodevoluciones_credito['registros'] ?></div>

                        </div>


                        <div class="row">

                            <div class="col-md-1"></div>
                            <div class="col-md-5">Anulacion Contado</div>
                            <div class="col-md-3"><?= number_format($calculoanulaciones['total'], '2', ',', '.') ?></div>
                            <div class="col-md-3"><?= $calculoanulaciones['registros'] + $calculoanulaciones_credito['registros'] ?></div>

                        </div>
                        <div class="row">

                            <div class="col-md-1"></div>
                            <div class="col-md-5">Anulacion Credito</div>
                            <div class="col-md-3"><?= number_format($calculoanulaciones_credito['total'], '2', ',', '.') ?></div>
                            <div class="col-md-3"><?= $calculoanulaciones['registros'] + $calculoanulaciones_credito['registros'] ?></div>

                        </div>


                        <div class="row">

                            <div class="col-md-1"></div>
                            <div class="col-md-5">CREDITO</div>

                            <div class="col-md-3"><?= number_format($totalventascredito, '2', ',', '.') ?></div>
                            <div class="col-md-3"><?= $credito['registros'] ?></div>
                        </div>
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
                            TOTAL INGRESOS
                        </td>
                        <td>
                            ---
                        </td>
                        <td>


                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format(($totalefectivo + $totalotros + $totalventascredito) -
                                                           ($calculoanulaciones['total'] + $calculodevoluciones['total'] + $restar_credito)
                                                           , 2, ',', '.') ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TOTAL EFECTIVO
                        </td>
                        <td>
                            ---
                        </td>
                        <td>
                                                <span id="subtotalR"><span
                                                            id="totalR"><?php echo number_format(($totalefectivo + $abonosacarteraresult['efectivo'])
                                                            - ($calculoanulaciones['total'] + $calculodevoluciones['total']),
                                                            2, ',', '.') ?></span>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            DESCUENTOS
                        </td>
                        <td>
                            ---
                        </td>
                        <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($totaldesceuntos, 2, ',', '.') ?></span> (<?= $totalventascondescuento['num'] ?>)
                        </td>
                    </tr>


                    <tr>
                        <td>
                            TOTAL ABONOS
                        </td>
                        <td>
                            <?php

                            $abononumregistros = $abonosacarteraresult['num'];
                            ?>
                            ---
                        </td>
                        <td>
                            <?php echo MONEDA ?> <span
                                    id="totalR"><?= number_format($abonosacarteraresult['total'], 2, ',', '.') ?></span>
                            (<?= $abononumregistros ?>)
                        </td>
                    </tr>

                    <tr>
                        <td>
                            ABONOS EFECTIVO
                        </td>
                        <td>
                            <?php

                            $abononumregistros = $abonosacarteraresult['num'];
                            ?>
                            ---
                        </td>
                        <td>
                            <?php echo MONEDA ?> <span
                                    id="totalR"><?= number_format($abonosacarteraresult['efectivo'], 2, ',', '.') ?></span>
                        </td>
                    </tr>


                    <tr>
                        <td></td>
                        <td></td>
                        <td><br></td>
                    </tr>


                    <tr>
                        <td>
                            TOTAL GRAVADO
                            <?php


                         /*   $totalgravado = $totalgravado - ($calculoanulaciones['gravado']
                                    + $calculodevoluciones['gravado']
                                    + $calculoanulaciones_credito['gravado'] + $calculodevoluciones_credito['gravado']);
                            $totalgravado = $totalgravado + $credito['gravado'];*/



                            $totalgravado = $totales_reales_backup['gravado']- ($calculoanulaciones['gravado'] + $calculodevoluciones['gravado']
                                    + $calculoanulaciones_credito['gravado'] + $calculodevoluciones_credito['gravado']);

                            ?>
                        </td>
                        <td>
                            ---
                        </td>
                        <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($totalgravado, 2, ',', '.') ?></span>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            TOTAL EXCLUIDO
                        </td>
                        <td>
                            ---
                        </td>
                        <?php


                        /*$totalexcluido = $totalexcluido - ($calculoanulaciones['excluido'] + $calculodevoluciones['excluido']
                                + $calculoanulaciones_credito['excluido'] + $calculodevoluciones_credito['excluido']);
                        $totalexcluido = $totalexcluido + $credito['excluido'];*/
                        $totalexcluido = $totales_reales_backup['excluido']- ($calculoanulaciones['excluido'] + $calculodevoluciones['excluido']
                                + $calculoanulaciones_credito['excluido'] + $calculodevoluciones_credito['excluido']);
                        ?>
                        <td>
                                               <span id="subtotalR"><span
                                                           id="totalR"><?php echo number_format($totalexcluido, 2, ',', '.') ?></span>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            TOTAL IVA
                            <?php

                           // var_dump($calculodevoluciones_credito);

                         //   echo $calculodevoluciones_credito['gravado'];

                        /*    $totaliva = $totaliva - ($calculoanulaciones['iva'] + $calculodevoluciones['iva'] + $calculoanulaciones_credito['iva']
                                    + $calculodevoluciones_credito['iva']
                                );
                            $totaliva = $totaliva + $credito['iva'];*/





                            $totaliva = $totales_reales_backup['iva']- ($calculoanulaciones['iva'] + $calculodevoluciones['iva']
                                    + $calculoanulaciones_credito['iva'] + $calculodevoluciones_credito['iva']);

                            ?>
                        </td>
                        <td>
                            ---
                        </td>
                        <td>
                            <?php echo MONEDA ?> <span
                                    id="totalR"><?= number_format($totaliva, 2, ',', '.') ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            TOTAL OTROS IMPUESTOS
                        </td>
                        <td>
                            ---
                        </td>
                        <td>
                            <?php
                            $totalotrosimpuestos = $totalotrosimpuestos - ($calculoanulaciones['otrosimpuestos'] + $calculodevoluciones['otrosimpuestos']);
                            $totalotrosimpuestos = $totalotrosimpuestos + $credito['otros_impuestos'];
                            ?>
                            <?php echo MONEDA ?> <span
                                    id="totalR"><?= number_format($totalotrosimpuestos, 2, ',', '.') ?></span>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="row text-center">
                <div class="col-md-6">Firma del responsable</div>


            </div>

            <br>

            <div class="row text-center">

                <div class="col-md-6">______________________</div>


            </div>

        </div>
        <br>
        <br>
        <div class="row dash">
            LIQUIDACION DE IMPUESTO A LAS VENTAS
        </div>

        <div class="col-md-12">

            <div class="col-sm-4">%</div>
            <div class="col-sm-4">Gravado</div>
            <div class="col-sm-4">Iva</div
        </div>


        <?php
        $totalventas = 0;
        $totaliva = 0;
        foreach ($impuestos as $impuesto) {
            if ($impuesto['tipo_calculo'] == 'PORCENTAJE') {

               // var_dump($impuesto);
                ?>
                <div class="col-md-12">
                    <div class="col-sm-4">
                        <?= $impuesto['porcentaje_impuesto'] ?>
                    </div>
                    <div class="col-sm-4">
                        <?php

                        $totgrav = 0;
                        $totiva = 0;

                        $totgrav = isset($impuesto['totales']['subtotal']) ? $impuesto['totales']['subtotal'] : 0;
                        if ($totgrav > 0) {
                            $totdevgrav = isset($impuesto['totales']['devolucion_gravado']) ? $impuesto['totales']['devolucion_gravado'] : 0;
                            // $totdevgrav = isset($impuesto['totales']['devolucion_gravado_dia']) ? $impuesto['totales']['devolucion_gravado_dia'] : 0;
                            $totangrav = isset($impuesto['totales']['anulacion_gravado']) ? $impuesto['totales']['anulacion_gravado'] : 0;
                            $totgrav = $totgrav - ($totdevgrav + $totangrav);
                            // $totgrav = $totgrav - ( $totangrav);

                        }
                        if ($totgrav < 0) {
                            $totgrav = 0;
                        }


                        $totiva = isset($impuesto['totales']['iva']) ? $impuesto['totales']['iva'] : 0;
                        if ($totiva > 0) {
                            //  $totdeviva = isset($impuesto['totales']['devolucion_iva_dia']) ? $impuesto['totales']['devolucion_iva_dia'] : 0;
                            $totdeviva = isset($impuesto['totales']['devolucion_iva']) ? $impuesto['totales']['devolucion_iva'] : 0;


                            $totaniva = isset($impuesto['totales']['anulacion_iva']) ? $impuesto['totales']['anulacion_iva'] : 0;


                            $totiva = $totiva - ($totdeviva + $totaniva);
                            // $totiva = $totiva - ( $totaniva);
                        }
                        if ($totiva < 0) {
                            $totiva = 0;
                        }

                        ?>
                        <?= number_format($totgrav, 2, ',', '.') ?>
                    </div>
                    <div class="col-sm-4">
                        <?= number_format($totiva, 2, ',', '.') ?>
                    </div>
                </div>


                <?php
                $totalventas = $totalventas + $totgrav;
                $totaliva = $totaliva + $totiva;
            }
        } ?>

        <div class="col-md-12">
            <div class="col-sm-4">
                Total
            </div>
            <div class="col-sm-4">
                <?= number_format($totalventas, 2, ',', '.') ?>
            </div>
            <div class="col-sm-4">
                <?= number_format($totaliva, 2, ',', '.') ?>
            </div>
        </div>

        <div class="row dash">
            OTROS IMPUESTOS
        </div>


        <?php
        $totalventas = 0;
        $totaliva = 0;
        foreach ($impuestos as $impuesto) {
            if ($impuesto['tipo_calculo'] == 'FIJO') {

                $totgrav = isset($impuesto['totales_otros']['subtotal']) ? $impuesto['totales_otros']['subtotal'] : 0;
                if ($totgrav > 0) {
                    $totdevgrav = isset($impuesto['totales_otros']['devolucion_gravado']) ? $impuesto['totales_otros']['devolucion_gravado'] : 0;
                    $totangrav = isset($impuesto['totales_otros']['anulacion_gravado']) ? $impuesto['totales_otros']['anulacion_gravado'] : 0;
                    $totgrav = $totgrav - ($totdevgrav + $totangrav);
                }


                $totiva = isset($impuesto['totales_otros']['iva']) ? $impuesto['totales_otros']['iva'] : 0;
                if ($totiva > 0) {
                    $totdeviva = isset($impuesto['totales_otros']['devolucion_iva']) ? $impuesto['totales_otros']['devolucion_iva'] : 0;
                    $totaniva = isset($impuesto['totales_otros']['anulacion_iva']) ? $impuesto['totales_otros']['anulacion_iva'] : 0;
                    $totiva = $totiva - ($totdeviva + $totaniva);
                }


                ?>
                <div class="col-md-12">
                    <div class="col-sm-4">
                        <?= $impuesto['nombre_impuesto'] ?>
                    </div>

                    <div class="col-sm-4">
                        <?= number_format($totiva, 2, ',', '.') ?>
                    </div>
                </div>


                <?php
                $totalventas = $totalventas + $totgrav;
                $totaliva = $totaliva + $totiva;
            }
        } ?>


        <div class="col-md-12">
            <div class="col-sm-4">
                Total
            </div>

            <div class="col-sm-4">
                <?= number_format($totaliva, 2, ',', '.') ?>
            </div>
        </div>

        <div class="row dash"></div>

        <br>
        <br>
        <div class="row dash">
            INFORME POR DEPARTAMENTOS
        </div>

        <div class="col-md-12">

            <div class="col-sm-4">%</div>
            <div class="col-sm-4">Ventas</div>
            <div class="col-sm-4">Iva</div
        </div>


        <?php

        foreach ($grupos as $grupo) { ?>
            <div class="col-md-12">
                <div class="col-sm-4">
                    <?= $grupo['nombre_grupo'] ?>
                </div>
                <div class="col-sm-4">
                    <?= isset($grupo['totales']['subtotal']) ? $grupo['totales']['subtotal'] : '' ?>

                </div>
                <div class="col-sm-4">
                    <?= isset($grupo['totales']['iva']) ? $grupo['totales']['iva'] : '' ?>
                </div>
            </div>


            <?php

        } ?>


    </div>

</div>







