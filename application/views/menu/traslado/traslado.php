<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Traslado</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="">SID</a></li>
            <li class="active">Traslado</li>
        </ol>
    </div>
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <form id="frmBuscar">
                <div class="block-title">
                </div>


                <?php if (count($locales) == 1): ?>
                    <div class="alert alert-warning" style="margin-bottom: 2px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        Este Usuario tiene un solo inventaro. Por lo tanto
                        no puede realizar traspasos.
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="panel-admin-text">Ubicaci&oacute;n Inventario:</label>
                            </div>
                            <div class="col-md-4">
                                <h4><?php echo $locales[0]['local_nombre'] ?></h4>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-2 panel-admin-text">Desde almac&eacute;n:</label>
                            <div class="col-md-4"><select class="form-control select-chosen buscar" id="localform1" name="desdealmacen"
                                                          >
                                    <option value="TODOS">TODOS</option>
                                    <?php
                                    $i = 0;
                                    foreach ($locales as $local) {
                                        ?>
                                        <option value="<?= $local['int_local_id'] ?>" <?php if ($i == 0) {
                                            echo "selected";
                                            $i++;
                                        } ?> >
                                            <?= $local['local_nombre'] ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <label class="col-md-2 panel-admin-text">Hasta almac&eacute;n:</label>
                            <div class="col-md-4"><select class="form-control select-chosen buscar" id="localform2" name="hastaalmacen"
                                                          >
                                    <option value="TODOS">TODOS</option>
                                    <?php
                                    $i = 0;
                                    foreach ($locales as $local) {
                                        ?>
                                        <option value="<?= $local['int_local_id'] ?>" <?php if ($i == 0) {
                                            echo "selected";
                                            $i++;
                                        } ?> >
                                            <?= $local['local_nombre'] ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group">
                        <label class="col-md-2 panel-admin-text">Desde:</label>
                        <div class="col-md-4">

                            <input type="text" name="fecIni" readonly  id="fecIni"
                                   value="<?= date('d-m-Y') ?>" class='input-small form-control input-datepicker buscar'>
                        </div>
                        <label class="col-md-2 panel-admin-text">Hasta:</label>
                        <div class="col-md-4">
                            <input type="text" name="fecFin" readonly id="fecFin"
                                   value="<?= date('d-m-Y') ?>" class='form-control form-control input-datepicker buscar'>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                        <!-- <label class="col-md-2 panel-admin-text">Productos: </label>
                        <div class="col-md-4"><select onchange="" class="form-control select-chosen"
                                                      id="productos_lista" name="productos_lista">
                                <option value="TODOS" selected>TODOS</option>
                               
                            </select></div>

                        <label class="col-md-2 panel-admin-text">Tipo de Movimiento:</label>
                        <div class="col-md-4">

                            <select onchange="" class="form-control select-chosen" id="tipo_mov" name="tipo_mov">
                                <option value="TODOS" selected>TODOS</option>
                                <option value="ENTRADA">ENTRADA</option>
                                <option value="SALIDA">SALIDA</option>
                            </select>
                        </div>
                        -->
                        </div>
                    </div>

                <?php endif; ?>
            </form>
            <div class="row">
                <div class="col-md-8">
                    <a class="btn btn-success" onclick="Traslado.form(false)" href="#">
                        <i class="fa fa-plus ">Nuevo Traslado</i>
                    </a>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="tabla">


                        <table
                            class="table table-striped dataTable table-bordered
                                             table-hover table-featured"
                            id="tabla_lista_productos"  style="position: relative;">

                            <thead class="" id="theadtabla">
                            <tr>

                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Cantidad de Productos</th>
                                <th>Acci&oacute;n</th>

                            </tr>
                            </thead>
                            <tbody class="" id="tbodyproductos">

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="traslado_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" data-backdrop="static" data-keyboard="false" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="Traslado.cerrarMostrarDetalle()"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="show_id_traslado"> </h4>
            </div>
            <div class="modal-body" id="modal_body_traslado">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="Traslado.cerrarMostrarDetalle()">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>



<script>
    $(function () {

        Traslado.init();
        Traslado.buscarTraslados();
    });
</script>
