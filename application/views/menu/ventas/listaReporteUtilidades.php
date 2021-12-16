<?php $ruta = base_url(); ?>
<div class="table-responsive">
    <table class="table table-striped dataTable table-bordered" id="tablaresultado">
        <caption style="caption-side: top">Desde: <?= $fecIni ?><br>
            Hasta: <?= $fecFin ?><br>
            Fecha-Hora: <?= date('d-m-Y H:i:s') ?><br>
            Usuario: <?= $this->session->userdata('username'); ?></caption>
        <thead>
        <?php if ($utilidades == "TODO") { ?>
            <tr>
                <th> Fecha y Hora</th>
                <th>N&uacute;mero</th>
                <th>Producto</th>
                <th>IVA</th>
                <th>Unidad</th>
                <th>Precio de Venta</th>
                <th>Costo Unitario</th>
                
                <th>Cantidad</th>
                <th>Total en venta</th>
                <th>Costo Total</th>
                <th>Diferencia (Vta-Costo)</th>
                <th>Rentabilidad</th>
            </tr>
        <?php } elseif ($utilidades == "PRODUCTO") { ?>
            <th>C&oacute;digo</th>
            <th>Producto</th>
            <th>Utilidad</th>
        <?php } elseif ($utilidades == "CLIENTE") { ?>
            <th>C&oacute;digo</th>
            <th>Cliente</th>
            <th>Utilidad</th>
        <?php } elseif ($utilidades == "PROVEEDOR") {
            ?>
            <th>C&oacute;digo</th>
            <th>Proveedor</th>
            <th>Utilidad</th>
        <?php }
        ?>


        </thead>
        <tbody>
        <?php

        $suma_costo_total = 0;
        $suma_total_diferencia = 0;
        if (count($ventas) > 0) {

            foreach ($ventas as $venta) {


                if ($utilidades == "TODO") {
                    ?>
                    <tr>
                        <td><?= date('d-m-Y H:i:s', strtotime($venta->fecha)) ?></td>
                        <td><?= $venta->documento_Numero ?></td>
                        <td><?= $venta->producto_nombre ?></td>
                        <td data-sumar="true"><?= $venta->porcentaje_impuesto ?></td>
                        <td><?= $venta->nombre_unidad ?></td>
                        <td data-sumar="true"><?= number_format($venta->precio_sin_iva, 2, ',', '.') ?></td>
                        <td data-sumar="true"><?php
                            $tipo_calculo = $this->session->userdata('CALCULO_UTILIDAD');
                            if($venta->is_paquete==1){
                                /**
                                 * Si el producto es paquete, el calculo para este producto, será con costo unitario siempre
                                 */
                                $tipo_calculo = COSTO_UNITARIO;
                            }
                            $costo = ($tipo_calculo == COSTO_UNITARIO) ? $venta->costo : $venta->costo_promedio;
                            echo number_format($costo, 2, ',', '.') ?></td>
                       
                        <td data-sumar="true"><?= $venta->cantidad ?></td>
                        <td data-sumar="true"><?php


                            //$totalventa = ($venta->precio_sin_iva - $venta->descuento) * $venta->cantidad;
                            $totalventa = ($venta->precio_sin_iva * $venta->cantidad) - $venta->descuento;

                            echo number_format($totalventa, 2, ',', '.');
                            ?></td>
                        <td data-sumar="true"><?php

                            $totalcosto = $costo * $venta->cantidad;
                            $suma_costo_total = $suma_costo_total+ $totalcosto;
                            echo number_format($totalcosto, 2, ',', '.') ?></td>
                        <td data-sumar="true"><?php


                            $utlidad_por_Promedio = ($totalventa) - ($totalcosto);

                            // $diferencia = ($tipo_calculo == COSTO_UNITARIO) ? $venta->utilidad : $utlidad_por_Promedio;
                            $diferencia = $utlidad_por_Promedio;
                            $suma_total_diferencia = $suma_total_diferencia +$diferencia;
                            echo number_format($diferencia, 2, ',', '.'); ?>
                        </td>
                        <td >
                            <?php


                            if($totalcosto>0) {
                                $division = $diferencia / ($totalcosto);
                            }else{
                                $division=0;
                            }
                            echo number_format($division * 100, 2, ',', '.'); ?>%
                        </td>
                    </tr>

                <?php } elseif ($utilidades == "PRODUCTO") { ?>

                    <tr>
                        <td><?= $venta->id_producto ?></td>
                        <td><?= $venta->producto_nombre ?></td>
                        <td data-sumar="true"><?= $venta->suma ?></td>
                    </tr>
                <?php } elseif ($utilidades == "CLIENTE") { ?>

                    <tr>
                        <td><?= $venta->id_cliente ?></td>
                        <td><?= $venta->razon_social ?></td>
                        <td data-sumar="true"><?= $venta->suma ?></td>
                    </tr>
                <?php } elseif ($utilidades == "PROVEEDOR") { ?>

                    <tr>
                        <td><?= $venta->id_proveedor ?></td>
                        <td><?= $venta->proveedor_nombre ?></td>
                        <td data-sumar="true"><?= $venta->suma ?></td>
                    </tr>
                <?php }
            }
        } ?>

        </tbody>
        <tfoot>
        <tr>
            <th>TOTAL</th>
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
            <th ><?= $suma_costo_total>0  &&  $suma_total_diferencia>0 ?number_format(($suma_total_diferencia /$suma_costo_total)*100, 2, ',', '.'):0 ?> %</th>

        </tr>
        </tfoot>

    </table>

</div>


</div>

<script type="text/javascript">
    $(function () {

        TablesDatatables.init(0, "tablaresultado", 'DESC', 'SID - REPORTE DE CONTRIBUCIÓN MARGINAL');

    });

</script>