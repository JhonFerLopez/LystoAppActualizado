<?php $ruta = base_url(); ?>
<div class="modal-dialog ">
    <div class="modal-content">

        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Vista Previa</h4>
        </div>
        <div class="modal-body">


            <!------AQUI COMIENZA CADA NOTA DE ENTREGA ---->


            <div class="panel row nota_entrega_seccion" class="text-center" id="panel_documento">
                <?php if (isset($cierrecaja)) {

                //var_dump($ventas[0] );


                ?>
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
                                REPORTES/CUADRE CAJA
                            </div>


                        </div>
                        <div class="row dash">

                            <div class="col-xs-12">
                                CUADRE CAJA: <?= $cierrecaja['id'] ?>
                            </div>


                        </div>


                        <div class="row">
                            <div class="col-xs-4">
                                Fecha:<br>


                                <?= date('d-m-Y', strtotime($cierrecaja['apertura'])) . " A " . date('d-m-Y', strtotime($cierrecaja['cierre'])) ?>
                            </div>
                            <div class="col-xs-3">
                                Hora:


                                <?= date('h:i: A', strtotime($cierrecaja['apertura'])) . " A " . date('h:i: A', strtotime($cierrecaja['cierre'])) ?>
                            </div>


                            <div class="col-xs-2">
                                Cajero:


                                <?= $cierrecaja['nombre'] ?>
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
                        <!--<div class="col-md-3">Registro</div>-->


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
                                    $totalefectivoventas = 0;
                                    $totalefectivo = 0;
                                    $totalsistema = 0;
                                    $totalefectivosistema = 0;

                                    foreach ($formaspago as $formapago) {


                                        $totales = $formapago['totales'];

                                        ?>

                                        <div class="row">

                                            <div class="col-md-1"><?= $formapago['id_metodo'] ?></div>
                                            <div class="col-md-5"><?= $formapago['nombre_metodo'] ?></div>
                                            <div class="col-md-3"><?php echo number_format($totales['total'], 2, ',', '.') ?></div>
                                            <div class="col-md-3"><?php echo $totales['totalregistros'] ?></div>


                                            <?php

                                            if ($formapago['suma_total_ingreso'] == 1) {
                                                $totalefectivoventas = $totalefectivoventas + $totales['total'];
                                            } else {
                                                $totalotros = $totalotros + $totales['total'];
                                            }

                                            $totaldesceuntos = $totaldesceuntos + $totales['descuentos'];


                                            ?>

                                        </div>

                                    <?php }
                                    $restar = $calculodevoluciones['total'] + $calculoanulaciones['total'];

                                    $totalefectivo = ($totalefectivoventas + $abonosacarteraresult['efectivo']) - $restar;


                                    $totalsistema = $totalefectivo + $totalotros + $totalventascredito - $abonosacarteraresult['efectivo'];
                                    $restar_credito = $calculodevoluciones_credito['total'] + $calculoanulaciones_credito['total'];


                                    $totalingresos = $totalefectivoventas + $totalotros + $credito['suma'] - ($restar + $restar_credito);

                                    //   var_dump($calculodevoluciones);


                                    $sobrante = $cierrecaja['monto_cierre'] - $totalefectivo;
                                    ?>

                                    <div class="row">

                                        <?php $totdev = $calculodevoluciones['total'] ?>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">Devoluci&oacute;n efectivo</div>
                                        <div class="col-md-3"><?= number_format($totdev, '2', ',', '.') ?></div>
                                        <div class="col-md-3"><?php echo $calculodevoluciones['registros'] ?></div>

                                    </div>

                                    <div class="row">

                                        <?php $totdev =  $calculodevoluciones_credito['total'];?>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">Devoluci&oacute;n cr&eacute;dito</div>
                                        <div class="col-md-3"><?= number_format($totdev, '2', ',', '.') ?></div>
                                        <div class="col-md-3"><?php echo $calculodevoluciones_credito['registros'] ?></div>

                                    </div>

                                    <div class="row">

                                        <?php $totan = $calculoanulaciones['total'] ?>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">Anulaci&oacute;n Efectivo</div>
                                        <div class="col-md-3"><?= number_format($totan, '2', ',', '.') ?></div>
                                        <div class="col-md-3"><?php echo $calculoanulaciones['registros'] ?></div>

                                    </div>
                                    <div class="row">

                                        <?php $totan =  $calculoanulaciones_credito['total'];?>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">Anulacion Credito</div>
                                        <div class="col-md-3"><?= number_format($totan, '2', ',', '.') ?></div>
                                        <div class="col-md-3"><?php echo  $calculoanulaciones_credito['registros'] ?></div>

                                    </div>
                                    <div class="row">


                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">CR&Eacute;DITO</div>
                                        <div class="col-md-3"><?= number_format($totalventascredito, '2', ',', '.') ?></div>
                                        <div class="col-md-3"><?php echo $credito['registros'] ?></div>


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
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="subtotalR"><span
                                                        id="totalR"><?php echo number_format($totalingresos, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            TOTAL EFECTIVO
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="subtotalR"><span
                                                        id="totalR"><?php echo number_format($totalefectivo, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            TOTAL OTROS DOC
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="subtotalR"><span
                                                        id="totalR"><?php echo number_format($totalotros + $totalventascredito, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            DESCUENTOS
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span id="subtotalR"><span
                                                        id="totalR"><?php echo number_format($totaldesceuntos, 2, ',', '.') ?></span> (<?= $totalventascondescuento['num'] ?>)
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            ABONOS CARTERA FECTIVO
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($abonosacarteraresult['efectivo'], 2, ',', '.') ?></span> (<?= $abonosacarteraresult['num'] ?>)
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            ABONOS CARTERA OTROS METODOS DE PAGO
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($abonosacarteraresult['otros'], 2, ',', '.') ?></span> (<?= $abonosacarteraresult['num'] ?>)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><br></td>
                                    </tr>

                                    <!--- <tr>
                                        <td>
                                            TOTAL SISTEMA
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>

                                            <?php echo MONEDA ?> <?php echo number_format($totalsistema, 2, ',', '.') ?>
                                        </td>
                                    </tr>-->
                                    <tr>
                                        <td>
                                            Valor entregado
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($cierrecaja['monto_cierre'], 2, ',', '.') ?></span>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><br></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Sobrante/Faltante
                                        </td>
                                        <td>
                                            --
                                        </td>
                                        <td>
                                            <?php echo MONEDA ?> <span
                                                    id="totalR"><?= number_format($sobrante, 2, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><br><br></td>
                                    </tr>

                                </table>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-6">Recibe</div>
                                <div class="col-md-6">Entrega</div>


                            </div>
                            <br>

                            <div class="row text-center">
                                <div class="col-md-6">--------------------</div>
                                <div class="col-md-6">--------------------</div>


                            </div>

                            <div class="row text-center">
                                <div class="col-md-12">Derechos reservados Prosode SAS <?= date('Y') ?></div>

                            </div>

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




