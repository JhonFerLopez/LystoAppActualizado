<?php $ruta = base_url(); ?>


<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Columnas del Producto en Ventas</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <!--<a href="" target="_blank"
                                                         class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Buy
            Now</a>-->
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="white-box">

            <div class="row">
                <a class="btn btn-primary"
                   onclick="Venta.modalAddColumnasToProduct();">
                    <i class="fa fa-plus ">Modificar Columnas</i>
                </a>

                <div class="table-responsive">
                    <table class="table table-striped dataTable table-bordered" id="example">
                        <thead>
                        <tr>

                            <th>ID</th>
                            <th>Columna</th>
                            <th>Activa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($columnas->count() > 0) {

                            foreach ($columnas as $columna) {
                                ?>
                                <tr>

                                    <td class="center"><?= $columna->id ?></td>
                                    <td><?= $columna->nombre_mostrar ?></td>
                                    <td><?= $columna->mostrar==1?'SI':'NO' ?></td>


                                </tr>
                            <?php }
                        } ?>

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">

                <form name="formagregar" id="columnasform"  method="post">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Columnas</h4>
                            </div>
                            <div class="modal-body">

                                <div class="table-responsive">

                                    <table class='table table-bordered'>
                                        <thead>
                                        <tr>
                                            <th>Columna</th>
                                            <th>Mostrar</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodycolumnas">

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" id="submitcolumnas" class="btn btn-primary" onclick="Venta.guardarColumnsProductos()">
                                    Confirmar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                            </div>


                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>

            </div>

            <!-- /.modal-dialog -->
        </div>
    </div>
</div>

<script>$(function () {
        TablesDatatables.init();
    });</script>