<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<a onclick="imprimirPropProd()" class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
   title="Imprimir">
    <i class="glyphicon glyphicon-print"></i>
</a>
<div class="table-responsive">
    <table class="table table-striped dataTable table-bordered" id="tablaresultado">
        <thead>
        <tr>
            <th>C&oacute;digo</th>
            <th>Producto</th>
            <th>Costo Unitario</th>
            <?php
            foreach ($unidades as $row) { ?>
                <th>Stock <?= $row['nombre_unidad'] ?></th>
            <?php } ?>
            <?php

            foreach ($condiciones_pago as $condiciones) {
                if($condiciones['nombre_condiciones']!="CREDITO") {
                    foreach ($unidades as $row) { ?>
                        <th>Precio <?= $condiciones['nombre_condiciones'] ?> <?= $row['nombre_unidad'] ?></th>
                    <?php }
                }
            } ?>

            <th>Impuesto</th>
            <th>% Comisi&oacute;n</th>
            <?= isset($categoria) && $categoria!="" ? " <th>".$categoria."</th>":""  ?>


            <?php if(isset($categoria) && $categoria=="TIPO"){ ?>
                <th>Ubicaci&oacute;n Física</th>
            <?php  } ?>
            <?php if(isset($categoria) && $categoria=="UBICACION_FISICA"){ ?>
                <th>Tipo</th>
            <?php  } ?>

        </tr>
        </thead>
        <tbody>
        <?php

            $cont = 0;
            foreach ($productos as $producto) {
                $cont++;
                ?>
                <tr data-child-value="hidden <?= $cont ?>">

                    <td><?= $producto['producto_codigo_interno'] ?></td>
                    <td><?= $producto['producto_nombre'] ?></td>
                    <td><?= number_format($producto['costo_unitario'] , 2, ',', '.') ?></td>
                    <?php
                    foreach ($unidades as $unidad) {
                        $inventario = "";
                        foreach ($producto['stock'] as $stock) {
                            if ($unidad['id_unidad'] == $stock['id_unidad']) {
                                $inventario = $stock['cantidad'];
                                break;
                            }
                        }
                        ?>
                        <td><?= $inventario ?></td>
                    <?php } ?>

                    <?php
                    foreach ($condiciones_pago as $condiciones) {
                        if($condiciones['nombre_condiciones']!="CREDITO") {
                            foreach ($unidades as $unidad) {
                                $precio = "";
                                foreach ($producto['precios'] as $precios) {

                                    if ($precios['id_unidad'] == $unidad['id_unidad'] &&
                                        $condiciones['id_condiciones'] == $precios['id_condiciones_pago']
                                    ) {
                                        $precio = $precios['precio'];
                                        break;
                                    }
                                }
                                ?>
                                <td><?= $precio ?></td>
                            <?php }
                        }
                    } ?>

                    <td><?= $producto['nombre_impuesto'] ?> </td>
                    <td><?= $producto['producto_comision'] ?></td>
                    <?= isset($categoria) && $categoria!="" ? " <td>".$subcategoria."</td>":""  ?>


                    <?php if(isset($categoria) && $categoria=="TIPO"){ ?>
                        <td>
                            <?php foreach ($ubicaciones as $ub) {
                                if($ub['ubicacion_id']==$producto['producto_ubicacion_fisica']){
                                    echo $ub['ubicacion_nombre'];
                                }
                            }
                            ?>
                        </td>
                    <?php  } ?>
                    <?php if(isset($categoria) && $categoria=="UBICACION_FISICA"){ ?>
                        <td>
                            <?php foreach ($tipos as $tip) {
                                if($tip['tipo_prod_id']==$producto['producto_tipo']){
                                    echo $tip['tipo_prod_nombre'];
                                }
                            }
                            ?>

                        </td>
                    <?php  } ?>


                </tr>
            <?php }
         ?>
        </tbody>

    </table>
</div>


<script type="text/javascript">
    $(function () {
        TablesDatatables.init(1,'tablaresultado','asc');
        $('[data-toggle="tooltip"], .enable-tooltip').tooltip({animation: false});


    });



</script>