<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">

<?php
$colores_formapago = array();

$colores_formapago[0] = "#D7DF01";
$colores_formapago[1] = "#0101DF";
$colores_formapago[2] = "#FF0000";
$colores_formapago[3] = "#00FF00";
$colores_formapago[4] = "#0B614B";
$colores_formapago[5] = "#58D3F7";
$colores_formapago[6] = "#000";


?>


<div class="table-responsive">
    <table class="table table-striped dataTable table-bordered" id="tablaresultado">
        <thead>
        <tr>
            <th>ID</th>
            <th>Factura</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Fecha</th>
            <!--	<th>Tipo de Documento</th>-->
            <th>Estatus</th>
            <th>Local</th>
            <th>Condici&oacute;n</th>
            <th>Dto. Valor</th>
            <th>Dto. Porcentaje</th>
            <th>Total</th>
            <?php
            $cont_formas_de_pago = count($formas_de_pago);
            $cont_color=0;

            foreach ($formas_de_pago as $forma_pago) {

                echo "<th style='color: white; background-color: " . $colores_formapago[$cont_color]. "'>Monto</th>";
                echo "<th style='color: white; background-color: " . $colores_formapago[$cont_color] . "'>Nro Recibo</th>";

                unset($colores_formapago[$cont_color]);
                $cont_color++;
            }

            ?>
            <th>Acciones</th>

        </tr>
        </thead>
        <tbody>
        <?php if (count($ventas) > 0) {
            //var_dump($formas_de_pago);

            $cont = 0;
            foreach ($ventas as $venta) {
                $cont++;
                $venta_id = $venta->venta_id;
                $maneja_mpresion = $venta->maneja_impresion;
                $venta_status = $venta->venta_status;
                ?>
                <tr data-child-value="hidden <?= $cont ?>">

                    <td><?= $venta->venta_id ?></td>
                    <?php 
                    $numeracion='';
                    if(empty($venta->fe_numero)){
                        $numeracion = !empty($venta->resolucion_prefijo) ? $venta->resolucion_prefijo . "-" . $venta->documento_Numero : $venta->documento_Numero;
                    }else{
                        $numeracion  = "<label class='label label-info'> ".$venta->fe_prefijo ."-".  $venta->fe_numero."</label>";
                    }
                    ?>
                    <td><?=   $numeracion  ?></td>
                    <td><?= $venta->nombres ?> <?= $venta->apellidos ?> </td>
                    <td><?= $venta->nombre ?></td>
                    <td><?= date('d-m-Y H:i:s', strtotime($venta->fecha)) ?></td>
                    <!--<td><?= $venta->nombre_tipo_documento ?></td>-->
                    <?php if ($venta_status == 'EN ESPERA' || $venta_status == 'COMPLETADO') { ?>
                        <!--<td><a href="javascript:void(0)" class="edit_estatus_venta" id="<?php echo $venta_id; ?>"><?= $venta_status; ?></a></td>-->
                        <td><?= $venta_status; ?></td>
                    <?php } else { ?>
                        <td><?= $venta_status; ?></td>
                    <?php } ?>
                    <td><?= $venta->local_nombre ?></td>
                    <td><?= $venta->nombre_condiciones ?></td>
                    <td data-sumar="true"><?= number_format($venta->descuento_valor , 2, ',', '.')?></td>
                    <td data-sumar="true"><?= number_format($venta->descuento_porcentaje , 2, ',', '.')?></td>
                    <td
                            data-sumar="<?php
                            if(
                        $this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN"
                        ||
                        $this->usuarios_grupos_model->user_has_perm(
                                    $this->session->userdata('nUsuCodigo'),
                                    'historialventas_vertotalesventas')
                    ){
                        echo 'true';
                    }else{
                                echo 'false';
                            }?>"
                    ><?= number_format($venta->total , 2, ',', '.')?></td>

                    <?php
                    $contador_formas = 0;
                    $cont_foreach_formpago = 0;
                    $contadordecuantasvecespasa = 0;

                    $sumar_totales_formas_pago = '';
                    /**
                     * Con esto verifico si le muestro los totales o no
                     */
                    if(
                        $this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN"
                        ||
                        $this->usuarios_grupos_model->user_has_perm(
                                    $this->session->userdata('nUsuCodigo'),
                                    'historialventas_vertotalesventas')
                    ){
                        $sumar_totales_formas_pago='data-sumar="true"';
                    }

                    foreach ($formas_de_pago as $forma_pago) {

                           $td1="<td $sumar_totales_formas_pago>";
                           $td2="<td>";
                            if (count($venta->formas_de_pago) > 0) {
                                foreach ($venta->formas_de_pago as $row) {

                                    if ($row->id_forma_pago == $forma_pago['id_metodo']) {

                                        $monto = $row->monto;

                                        $td1.=number_format($monto , 2, ',', '.');
                                        $td2.=$row->nro_recibo;
                                       
                                        $contadordecuantasvecespasa++;
                                    }else if($row->id_forma_pago==null){
                                       
                                    }
                                }
                                
                            } else {
                               
                               
                            }
                            $td1.="</td>";
                            $td2.="</td>";
                            echo $td1;
                            echo $td2;
                            $contador_formas++;

                        $cont_foreach_formpago++;
                    }


                    ?>
                    <td>
                        <?php if ($venta_status == 'COMPLETADO') { ?>
                            <a onclick="Venta.modalPrint(<?= $maneja_mpresion?>, <?php echo $venta_id; ?>,'',1)"
                               class='btn btn-outline btn-default waves-effect waves-light tip' data-toggle="tooltip"
                               title="Imprimir">
                                <i class="fa fa-print"></i>
                            </a>
                        <?php } ?>
                        <a onclick="Venta.verVenta(<?php echo $venta->venta_id; ?>)"
                           class='btn btn-outline btn-default waves-effect waves-light tip' data-toggle="tooltip"
                           title="Ver Venta">
                            <i class="fa fa-search"></i>
                        </a>
                        <?php if ($venta_status == 'EN ESPERA') { ?>
                            <a onclick="Venta.deleteVenta(<?php echo $venta->venta_id; ?>)"
                               class='btn btn-outline btn-default waves-effect waves-light tip' data-toggle="tooltip"
                               title="Eliminar Venta">
                                <i class="fa fa-trash"></i>
                            </a>
                        <?php } ?>

                        <?php if ($venta->id_devolucion != NULL) { ?>
                            <a onclick="Venta.printDevolucion(<?php echo $venta->id_devolucion; ?>, <?php echo $venta->venta_id; ?>)"
                               class='btn btn-outline btn-default waves-effect waves-light tip' data-toggle="tooltip"
                               title="Imprimir devolucion">
                                <i class="glyphicon glyphicon-print"></i>
                            </a>
                        <?php } ?>


                        <?php if ($venta->id_devolucion != NULL) { ?>
                            <a onclick="Venta.verDevolucion(<?php echo $venta->id_devolucion; ?>, <?php echo $venta->venta_id; ?>)"
                               class='btn btn-outline btn-default waves-effect waves-light tip' data-toggle="tooltip"
                               title="Ver devolucion">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                        <?php } ?>

                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
        <tfoot>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <?php
        $contador_formas = 0;
        $cont_foreach_formpago = 0;
        $contadordecuantasvecespasa = 0;
        foreach ($formas_de_pago as $forma_pago) {
            echo " <th></th>";
            echo " <th></th>";
        }


        ?>
        <th></th>
        </tfoot>

    </table>
</div>


<script type="text/javascript">
    $(function () {
        TablesDatatables.init(0,'tablaresultado','desc','Ventas',[{width: '2%', targets: 0}],false);
        $('[data-toggle="tooltip"], .enable-tooltip').tooltip({animation: false});
    });


</script>
