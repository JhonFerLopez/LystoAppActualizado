<form name="formagregar" action="<?= base_url() ?>afiliado/guardar" method="post" id="formagregar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva empresa afiliada</h4>
            </div>
            <div class="modal-body">

                <header><h4>Información general de la línea de descuento empresa</h4></header>
                <div class="row">

                    <div class="form-group">
                        <div class="col-md-2">
                            Código de la empresa
                        </div>
                        <div class="col-md-3"><input type="text" name="afiliado_codigo" id="afiliado_codigo" onkeydown="return soloNumeros(event);"
                                                     required="true" class="form-control"
                                                     value="<?php if (isset($empresa['afiliado_codigo'])) echo $empresa['afiliado_codigo']; ?>">
                        </div>

                        <input type="hidden" name="afiliado_id" id="afiliado_id" required="true"
                               value="<?php if (isset($empresa['afiliado_id'])) echo $empresa['afiliado_id']; ?>">

                        <div class="col-md-2">
                            Nombre de la empresa
                        </div>
                        <div class="col-md-5"><input type="text" name="afiliado_nombre" id="afiliado_nombre"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($empresa['afiliado_nombre'])) echo $empresa['afiliado_nombre']; ?>">
                        </div>


                    </div>
                    </div>
                <div class="row">

                    <div class="form-group">
                        <div class="col-md-2">
                            Monto total cartera
                        </div>
                        <div class="col-md-3"><input type="number" name="afiliado_monto_cartera"
                                                     id="afiliado_monto_cartera" required="true"
                                                     class="form-control" onkeydown="return soloDecimal(this, event);"
                                                     value="<?php if (isset($empresa['afiliado_monto_cartera'])) echo $empresa['afiliado_monto_cartera']; ?>">
                        </div>





                    </div>


                </div>

                <header><h4>Descuentos o incrementos a productos</h4>

                </header>
                <div class="alert alert-warning ">
                    Para su utilización use manejo financiero. Si quiere incrementar el 10% use 110, si desea disminuir
                    utilice 90, este porcentaje opera sobre la lista de precios que utilice.
                    </span>
                </div>
                <div class="row">

                    <div class="col-md-12">


                        <table class="table table-responsive table-hover table-condensed ">

                            <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($unidades as $unidad) {
                                    ?>
                                    <th><?= $unidad['nombre_unidad'] ?></th>
                                    <?php
                                } ?>
                            </tr>
                            </thead>
                            <?php foreach ($tipos as $tipo) {

                                ?>

                                <tbody>
                                <tr>
                                    <td><?= $tipo['tipo_prod_nombre'] ?>
                                    </td>
                                    <?php


                                    if (isset($descuentos) && sizeof($descuentos)>0) {


                                        foreach ($descuentos as $descuento) {
                                            if (isset($descuento['tipo_prod_id']) && $descuento['tipo_prod_id'] == $tipo['tipo_prod_id']) {
                                                doImputs($descuento['tipo_prod_id'], $descuento['unidad_id'], $descuento['porcentaje']);

                                            }
                                        }
                                    } else {


                                        foreach ($unidades as $unidad) {
                                            doImputs($tipo['tipo_prod_id'], $unidad['id_unidad'] ,'');

                                        }
                                    } ?>
                                </tr>
                                </tbody>
                                <?php
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="guardar" onclick="EmpresaAfiliada.save()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</form>

<?php

function doImputs($tipo_prod, $unidad, $porcentaje)
{
    echo '<td> <input type="text"  name="unidad_'.$tipo_prod.'_'.$unidad.'"  onkeydown="return soloDecimal(this, event);"
    size="7" value="'.$porcentaje.'" id="'.$unidad.'"></td>';

} ?>

<script>$(function () {

        EmpresaAfiliada.init();
    });</script>
