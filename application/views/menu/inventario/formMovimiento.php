<?php $ruta = base_url(); ?>
<form name="formagregar" action="<?php echo $ruta; ?>inventario/guardar" method="post">
    <input id="maximahidden" type="hidden">

    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Movimiento de Inventario -

                    <?php if ($local == "TODOS") { ?>
                        TODOS
                    <?php } else {
                        echo $local['local_nombre'];
                        $local = $local['int_local_id'];
                    } ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive" style="position: relative;">
                    <table class="table table-striped  dataTable table-bordered table-condensed" id="tablaresultformmo">
                        <thead>
                        <tr>

                            <th>ID</th>
                            <th>Fecha</th>
                            <!--<th>Tipo</th>-->

                            <th>Documento</th>
                            <th>IdTransaccion</th>
                            <th>Operación</th>
                            <th>Tipo</th>
                            <!--  <th>Estado</th>-->
                            <th class="danger">Unidad de Medida</th>
                            <th class="success">Entrada Cantidad</th>
                            <th class="success">Entrada Costo Unitario</th>
                            <!--     <th class="success">Entrada Costo Total</th>-->
                            <th class="warning">Salida Cantidad</th>
                            <th class="warning">Salida Valor Unitario</th>
                            <!-- <th class="warning">Salida Costo Total</th>-->
                            <th>Stock final</th>


                        </tr>
                        </thead>
                        <tbody id="columnas">

                        <?php if (count($kardex) > 0) {

                            foreach ($kardex as $arreglo) {


                                ?>
                                <tr>
                                    <td><?= $arreglo['nkardex_id'] ?></td>
                                    <td><span
                                                style="display: none"><?= date('YmdHis', strtotime($arreglo['dkardexFecha'])) ?></span><?= date('d-m-Y H:i', strtotime($arreglo['dkardexFecha'])) ?>
                                    </td>


                                    <td><?= $arreglo['cKardexNumeroDocumento'] ?></td>
                                    <td><?= $arreglo['cKardexIdOperacion'] ?></td>
                                    <td><?= $arreglo['ckardexReferencia'] ?></td>

                                    <td><?= $arreglo['cKardexTipo'] ?></td>

                                    <td class="danger"><?= $arreglo['nombre_unidad'] ?></td>

                                    <?php if ($arreglo['cKardexTipo'] == "ENTRADA") { ?>
                                        <td class="success"><?= $arreglo['nKardexCantidad'] ?></td>
                                        <td class="success"><?= $arreglo['nKardexPrecioUnitario'] ?></td>

                                        <?php
                                    } else { ?>
                                        <td class="success"></td>
                                        <td class="success"></td>

                                        <?php
                                    }
                                    ?>

                                    <?php if ($arreglo['cKardexTipo'] == "SALIDA") { ?>
                                        <td class="warning"><?= $arreglo['nKardexCantidad'] ?></td>
                                        <td class="warning"><?= $arreglo['nKardexPrecioUnitario'] ?></td>

                                        <?php
                                    } else { ?>
                                        <td class="warning"></td>
                                        <td class="warning"></td>

                                        <?php
                                    }
                                    ?>

                                    <td><?php

                                        if (isset($arreglo['stockUMactual'])) {
                                            $stockfinal = json_decode($arreglo['stockUMactual']);


                                            if (is_array($stockfinal) || is_object($stockfinal)) {

                                                foreach ($stockfinal as $stock) {
                                                    $stock = (array)$stock;

                                                    echo $stock['nombre'] . " : " . $stock['cantidad'] . "<br>";
                                                }
                                            }
                                        }

                                        ?>  </td>


                                </tr>

                                <?php
                            }
                        }
                        ?>

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <input type="button" id="" class="btn btn-primary" value="Confirmar" data-dismiss="modal">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>


</form>

<script type="text/javascript">

    $(function () {
        <?php
        if (count($kardex) > 0) { ?>
             NoScrollTable.init(1, 'tablaresultformmo', 'desc');
        <?php } ?>



        $("#fecha").datepicker({format: 'dd-mm-yyyy'});


        $("#select").chosen({
            placeholder: "Seleccione el producto",
            allowClear: true
        });
        $("#locales_in").chosen({
            placeholder: "Seleccione el producto",
            allowClear: true
        });
        $('#select').on("change", function () {
            if ($(this).val() != "seleccione") {
                $("#maxima").remove();
                $("#minima").remove();
                $.ajax({
                    url: '<?= base_url()?>inventario/get_unidades_has_producto',
                    type: 'POST',
                    headers: {
                        Accept: 'application/json'
                    },
                    data: {'id_producto': $(this).val()},
                    success: function (data) {

                        $("#fraccion").attr('max', data.unidades[0].unidades);
                        $("#existencia").css("display", "block");
                        $("#cantidad").val("");
                        $("#fraccion").val("");
//data.unidades[data.unidades.length -1].unidades
                        $("#unidad_maxima").append("<div id='maxima'><div class='col-md-5'> Unidad Maxima " + data.unidades[0].nombre_unidad + "</div></div> ");
                        $("#unidad_minima").append("<div id='minima'><div class='col-md-5'> Unidad Minima " + data.unidades[data.unidades.length - 1].nombre_unidad + "</div></div> ");

                        $("#maximahidden").val(data.unidades[0].nombre_unidad);


                    }
                })
            }
        });


    });


</script>