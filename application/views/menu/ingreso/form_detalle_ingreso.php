<?php $ruta = base_url(); ?>

<div class="modal-dialog modal-lg" style="width: 100%" >
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Detalle Ingreso</h4>
        </div>
        <div class="modal-body">

            <div class="table-responsive">
                <table class="table dataTable dataTables_filter table-bordered table-hover table-featured table-striped" id="tabledetail">

                    <thead>
                    <tr>
                        <th>ID de Detalle</th>
                        <th>C&oacute;digo</th>
                        <th>Producto</th>
                        <?php  if(count($unidades)>0 ){

                            foreach ($unidades as $unidad){ ?>

                                <th>Cantidad <?= $unidad['nombre_unidad']  ?></th>

                                <?php if($this->session->userdata("nombre_grupos_usuarios")=="PROSODE_ADMIN" ||
                                    $this->session->userdata("nombre_grupos_usuarios")=="ADMINISTRADOR"){ ?>
                                <th>Costo por unidad <?= $unidad['nombre_unidad']  ?></th>

                                <?php  } ?>

                           <?php  }
                        }  ?>
                        <th style="float: right">Total</th>
                       <!-- <th>Unidad de Medida</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($detalles)) {
                        foreach ($detalles as $detalle) {

                            ?>
                            <tr>
                                <td>
                                    <?= $detalle->id_detalle_ingreso ?>
                                </td>

                                <td>
                                    <?= $detalle->producto_codigo_interno ?>
                                </td>
                                <td>
                                    <?= $detalle->producto_nombre ?>
                                </td>
                                <?php  if(count($unidades)>0 ){

                                    foreach ($unidades as $unidad) {

                                        $encontro=false;
                                        foreach ($detalle->detalle_unidad as $row) {
                                            if($row->unidad_id==$unidad['id_unidad']) {
                                                $encontro=true;
                                                ?>

                                                <td> <?= $row->cantidad ?></td>

                                                <?php if($this->session->userdata("nombre_grupos_usuarios")=="PROSODE_ADMIN" ||
                                                    $this->session->userdata("nombre_grupos_usuarios")=="ADMINISTRADOR") { ?>
                                                    <td style="float: right">
                                                        <label class="label label-success"><?= MONEDA . " " . $row->costo ?></label>
                                                    </td>

                                                    <?php
                                                }

                                            }
                                        }

                                        if($encontro==false) { ?>
                                            <td></td>
                                            <?php if ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                                                $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR") { ?>
                                                <td></td>
                                            <?php }
                                        }
                                    }
                                }
                                ?>

                                <td>
                                    <label class="label label-success"> <?=   MONEDA." ".$detalle->total_detalle ?> </label>
                                </td>

                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>


            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

        </div>
    </div>
    <!-- /.modal-content -->
</div>


<script>
    $(function () {

        TablesDatatables.init(0,'tabledetail','asc');

    });
</script>
