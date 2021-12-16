<?php $ruta = base_url(); ?>
<?php echo $header; ?>
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <form name="formtraslado" action="<?= base_url() ?>traslado/guardar" method="post"
                  id="formtraslado">
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="row">
                                <label class="col-md-3 panel-admin-text">Desde:</label>
                                <div class="col-md-9">
                                    <select class="form-control select-chosen" id="localform1" name="localdesde"
                                            onchange="Traslado.cambiarlocal()"
                                        <?php if(count($detalle) > 0){ echo "disabled"; } ?>
                                    >
                                        <?php foreach ($locales as $local) { ?>
                                            <option
                                                <?php if ($local_select != false && $local_select == $local['int_local_id']) {
                                                    echo "selected";
                                                }
                                                if (count($detalle) > 0 && $detalle[0]->local_salida == $local['int_local_id']) {
                                                    echo "selected";
                                                }
                                                ?>
                                                    value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <label class="col-md-3 panel-admin-text">Hacia: </label>
                                <div class="col-md-9"><select class="form-control select-chosen" id="localform2"
                                                              name="localhasta"
                                        <?php if(count($detalle) > 0){ echo "disabled"; } ?>
                                    >
                                        <?php $n = 0;
                                        foreach ($locales as $local): ?>
                                            <option
                                                <?php
                                                if (count($detalle) > 0 && $detalle[0]->local_destino == $local['int_local_id']) {
                                                    echo "selected";
                                                }
                                                ?>
                                                    value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>

                                        <?php endforeach; ?>

                                    </select></div>
                            </div>
                        </div>

                        <label class="col-md-1 panel-admin-text">Fecha:</label>
                        <div class="col-md-3">

                            <input type="text" name="fecha_traslado" readonly <?php if(count($detalle) > 0){ echo "disabled"; } ?>
                                   style="cursor: pointer;"
                                   id="fecha_traslado"
                                   value="<?= count($detalle) > 0 ? date('d-m-Y', strtotime($detalle[0]->fecha)) : date('d-m-Y') ?>"
                                   class='input-small form-control input_datepicker'>
                        </div>

                    </div>

                </div>

                <br>
                <?php if (count($detalle) < 1) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <?php foreach ($unidades_medida as $unidad) { ?>
                                    <div class="col-md-2">
                                        Existencia en <?= $unidad['nombre_unidad'] ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label id="existencia_<?= $unidad['id_unidad'] ?>"
                                               class="label label-warning"
                                        ></label>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                <?php } ?>


                <div class="table-responsive" style="position: relative;">
                    <table class="table table-striped dataTable table-bordered
                                             table-hover table-featured" id="tablaresult">
                        <thead id="thead_tablaresult">

                        </thead>
                        <tbody id="columnas">

                        <tr id="trvacio">
                            <td style="padding-top: 0px; padding-bottom: 0px">
                                <input type="text" class="form-control inputsearchproduct"
                                       id="inputsearchproduct">
                            </td>
                            <td style="padding-top: 0px; padding-bottom: 0px"></td>
                            <?php foreach ($unidades_medida as $unidad): ?>
                                <td style="padding-top: 0px; padding-bottom: 0px"><input type="number" disabled
                                                                                         class="form-control">
                                </td>
                            <?php endforeach; ?>
                        </tr>

                        </tbody>
                    </table>
                </div>


            </form>
            <br>
            <?php if (count($detalle) < 1) { ?>
            <div class="block-section">
                <button type="button" id="guardar" class="btn btn-primary" onclick="Traslado.guardar()">Trasladar
                </button>
                <button type="button" class="btn btn-default" onclick="Traslado.form(false)">Limpiar</button>

            </div>
            <?php  } ?>
        </div>
    </div>
</div>

<div class="modal bs-example-modal-lg" id="seleccionunidades" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeseleccionunidades" data-dismiss="modal"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Productos</h4> <h5 id="nombreproduto"></h5>
            </div>
            <div class="modal-body" id="modalbodyproducto">

                <div class="row">


                    <table id="tablaproductos" class="table datatable table-bordered table-striped ">
                        <thead>
                        <th>ID</th>
                        <th>C&oacute;digo</th>
                        <th>Nombre</th>
                        <th>Ubicacion</th>
                        <th>Principio activo</th>

                        <?php

                        foreach ($unidades_medida as $unidad) {
                            ?>
                            <th>Cant <?= $unidad['nombre_unidad'] ?></th>
                            <?php


                        } ?>
                        </thead>
                        <tbody id="preciostbody">


                        <tr></tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <!-- <div class="modal-footer">
                 <a href="#" class="btn btn-primary" id="agregarproducto">Agregar Producto</a>
                 <a href="#" class="btn btn-default closeseleccionunidades">Salir</a>
             </div>-->
        </div>
    </div>
</div>

<div class="modal fade" id="advertencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 99999999;">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="Traslado.cerrartransferir_advertencia()">&times;</button>
                <h4 class="modal-title">Advertencia</h4>
            </div>
            <div class="modal-body">
                <p>Si usted cambia el local de origen perderá todos los cambios</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmar" class="btn btn-primary"
                        onclick="Traslado.guardarLocal(),Traslado.form(false)">Confirmar
                </button>
                <button type="button" class="btn btn-default" onclick="Traslado.cerrartransferir_advertencia()">
                    Cancelar
                </button>

            </div>
        </div>
    </div>
    <!-- /.modal-content -->


</div>

<div class="modal fade" id="trasladomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="false" data-backdrop="static" data-keyboard="false">


    <div class="modal-dialog modal-lg" style="width: 60%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" onclick="cancelarcerrar()">&times;</button>
                <h4 class="modal-title">Traslado de productos</h4>
            </div>

            <div class="modal-body" id="modal_body_traslado">

            </div>
            <div class="modal-footer">
                <button type="button" id="btn_confirmar" class="btn btn-primary" onclick="preguntar()">Confirmar
                </button>
                <button type="button" class="btn btn-default" onclick="cancelarcerrar()">Cancelar</button>
            </div>
        </div>


    </div>

</div>


<div class="modal fade" id="advertencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index: 99999999;">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="cerrartransferir_advertencia()">&times;</button>
                <h4 class="modal-title">Advertencia</h4>
            </div>
            <div class="modal-body">
                <p>Si usted cambia el local de origen perderá todos los cambios</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmar" class="btn btn-primary" onclick="reiniciar_form()">Confirmar
                </button>
                <button type="button" class="btn btn-default" onclick="cerrartransferir_advertencia()">Cancelar</button>

            </div>
        </div>
    </div>
    <!-- /.modal-content -->

</div>

<script type="text/javascript">
    $(function () {

        Traslado.init(<?php echo json_encode($unidades_medida) ?>,<?php echo json_encode($detalle) ?>,
            <?php echo json_encode($productosDetalle) ?>);
    });
</script>