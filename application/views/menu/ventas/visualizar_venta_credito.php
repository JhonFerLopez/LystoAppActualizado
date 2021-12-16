<div class="modal-dialog modal-lg" >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    onclick="javascript:$('#visualizar_venta').hide();">&times;
            </button>
            <h3>Detalle de la deuda</h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid force-margin">

                <?php if (isset($ventas[0])) { ?>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="fec_primer_pago" class="control-label">Fecha Emision:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="input-prepend">
                                    <?= isset($ventas[0]['fechaemision']) ? date('d-m-Y', strtotime($ventas[0]['fechaemision'])) : '' ?>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="nro_venta" class="control-label">Nro Factura:</label>
                            </div>
                            <div class="col-md-3">

                                <?= $ventas[0]['numero'] ?>

                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="fec_primer_pago" class="control-label">Cliente:</label>
                            </div>
                            <div class="col-md-1">
                                <?= $ventas[0]['cliente_id'] ?>
                            </div>

                            <div class="col-md-2">
                                <?= $ventas[0]['cliente'] . " " . $ventas[0]['apellidos'] ?>
                            </div>
                            <div class="col-md-2">
                                <label for="monto_total" class="control-label">Valor:</label>
                            </div>
                            <div class="col-md-3">
                                <div class="input-prepend">
                                    <?= $ventas[0]['montoTotal'] ?>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row">

                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="fec_primer_pago" class="control-label">Días de atraso:</label>
                            </div>
                            <div class="col-md-1">
                                <?php $days = (strtotime(date('d-m-Y')) - strtotime($ventas[0]['fechaemision'])) / (60 * 60 * 24);
                                if ($days < 0)
                                    $days = 0;

                                $label = "<div><label class='label ";
                                if (floor($days) < 8) {
                                    $label .= "label-success";
                                } elseif (floor($days) < 31) {
                                    $label .= "label-warning";
                                } else {
                                    $label .= "label-danger";
                                }
                                $label .= "'>" . floor($days) . "</label></div>";
                                ?>
                                <?= $label ?>
                            </div>


                        </div>


                    </div>

                <?php } else {

                    echo "Saldo inicial";
                } ?>
                <div class="row-fluid"></div>


                <div class="row-fluid">
                    <div class="block">
                        <div class="block-title">
                            <h3>Historial de Pago</h3>
                        </div>
                        <div class="box-content box-nomargin">
                            <div id="lstTabla" class="table-responsive">
                                <table id="table" class="table dataTable dataTables_filter table-striped">
                                    <thead>

                                    <th>Fecha</th>
                                    <th>#Recibo</th>
                                    <th>Monto Pagado</th>
                                    <th>Metodo de pago</th>
                                    <th>Usuario</th>
                                    <!--  <th>Restante</th>-->

                                    <th>Acci&oacute;n</th>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if (count($historial) > 0) {

                                        foreach ($historial as $row):
                                            $restante = $row['monto_restante']; ?>
                                            <tr>

                                                <td><?= date("d-m-Y H:i:s", strtotime($row['fecha'])) ?></td>
                                                <td><?= $row['recibo_id'] ?></td>
                                                <td>
                                                    <?php $pos = strrpos($row['historial_monto'], '.');
                                                    echo " " . MONEDA;
                                                    if ($pos === false) {
                                                        echo $row['historial_monto'];
                                                    } else {
                                                        echo substr($row['historial_monto'], 0, $pos + 3);
                                                    } ?>

                                                </td>
                                                <td><?= $row['nombre_metodo'] ?></td>
                                                <td><?= $row['username'] ?></td>
                                                <!--  <td><?php
                                                $restante = ($restante);
                                                echo $restante;

                                                ?></td>-->


                                                <td class=''>

                                                        <?php if ($row['anulado'] != 1): ?>
                                                            <a class='btn btn-danger tip' title="anular recibo"
                                                               onclick="Cartera.confirmAnularRecibo(<?= $row['recibo_id'] ?>,
                                                               '<?php echo $this->session->userdata('CLAVE_MAESTRA') ?>',
                                                               '<?php echo $this->session->userdata('CLAVE_MAESTRA_ANULAR_CARTERA')?>')"><i
                                                                        class="fa fa-trash"></i> Anular Recibo </a>
                                                        <?php else: ?>
                                                        <label class="label label-danger">ANULADO</label>
                                                        <?php endif; ?>

                                                        <a class='btn btn-default tip' title="Ver Pago"
                                                           onclick="Cartera.cargaData_Impresion(<?= $row['recibo_id'] ?>)"><i
                                                                    class="fa fa-print"></i>  </a>

                                                    <a class='btn btn-default tip' title="Ver Nota"
                                                       onclick="Cartera.modalObservaciones('<?= $row['observaciones_adicionales'] ?>')"><i
                                                                class="fa fa-search"></i>  </a>


                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    } ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <a href="#" class="btn btn-danger" data-dismiss="modal"
                   onclick="javascript:$('#visualizar_venta').hide();">Salir</a>
            </div>
        </div>
    </div>


