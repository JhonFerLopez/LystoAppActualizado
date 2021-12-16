<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<div class="table-responsive">

    <table class="table dataTable dataTables_filter table-bordered table-hover table-featured table-striped"
           id="tablaresult">
        <thead>
        <tr>
            <th>ID Ingreso</th>

            <th>Nro Documento</th>
            <th>Fecha Registro</th>
            <th>Status</th>
            <th>Proveedor</th>
            <th>Responsable</th>
            <th>Local</th>
            <?php if ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR"
            ) { ?>
                <th>Total</th>

            <?php } ?>
            <th>Acciones</th>


        </tr>
        </thead>
        <tbody>
        <?php if (count($ingresos) > 0) {

            foreach ($ingresos as $ingreso) {
                ?>
                <tr>
                    <td><?php echo $ingreso->id_ingreso ?></td>

                    <td><?php echo $ingreso->documento_numero ?></td>
                    <td><?= date('d-m-Y H:i:s', strtotime($ingreso->fecha_registro)) ?></td>
                    <td><label
                                class="label <?php if ($ingreso->ingreso_status == INGRESO_COMPLETADO) {
                                    echo 'label-success';
                                } elseif ($ingreso->ingreso_status == INGRESO_PENDIENTE) {
                                    echo 'label-danger';
                                } else {
                                    echo 'label-warning';
                                } ?>">
                            <?= $ingreso->ingreso_status ?></label>

                    </td>
                    <td><?= $ingreso->proveedor_nombre ?></td>
                    <td><?= $ingreso->nombre ?></td>
                    <td><?= $ingreso->local_nombre ?></td>
                    <?php if ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                        $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR"
                    ) { ?>
                        <td data-sumar="true"><?= number_format($ingreso->total_ingreso, 2, ',', '.') ?></td>
                    <?php } ?>
                    <td>
                        <div class="btn-group">
                            <?php

                            echo '<a class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
                                            title="Ver" data-original-title="Ver"
                                            href="#" onclick="ver(' . $ingreso->id_ingreso . ',' . $ingreso->local_id . ');">'; ?>
                            <i class="fa fa-search"></i>
                            </a>

                            <?php
                            if ($ingreso->ingreso_status == INGRESO_COMPLETADO
                                and
                                ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                                    $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR")
                            ) {
                                echo '<a class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
                                            title="Modificar Compra" data-original-title="Modificar Compra"
                                            href="#" onclick="editaringreso(' . $ingreso->id_ingreso . ');">'; ?>
                                <i class="fa fa-edit"></i>
                                </a>
                            <?php } ?>

                            <?php
                            if ($ingreso->ingreso_status == INGRESO_PENDIENTE and
                                ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                                    $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR")
                            ) {
                                echo '<a class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
                                            title="Retomar Compra" data-original-title="Retomar Compra"
                                            href="#" onclick="editaringreso(' . $ingreso->id_ingreso . ');">'; ?>
                                <i class="fa fa-hand-o-right"></i>
                                </a>
                            <?php } ?>


                            <?php
                            if ($ingreso->ingreso_status != INGRESO_ANULADO && $ingreso->ingreso_status != INGRESO_PENDIENTE
                                and
                                ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                                    $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR")
                            ) {
                                echo '<a class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
                                            title="Anular" data-original-title="Anular"
                                            href="#" onclick="modal_anular(' . $ingreso->id_ingreso . ',' . $ingreso->local_id . ');">'; ?>
                                <i class="fa fa-remove"></i>
                                </a>
                            <?php } ?>

                            <?php
                            if ($ingreso->ingreso_status == INGRESO_PENDIENTE and
                                ($this->session->userdata("nombre_grupos_usuarios") == "PROSODE_ADMIN" ||
                                    $this->session->userdata("nombre_grupos_usuarios") == "ADMINISTRADOR")
                            ) {
                                echo '<a class="btn btn-outline btn-default waves-effect waves-light tip" data-toggle="tooltip"
                                            title="Eliminar" data-original-title="Eliminar"
                                            href="#" onclick="modal_eliminar(' . $ingreso->id_ingreso . ',' . $ingreso->local_id . ');">'; ?>
                                <i class="fa fa-trash"></i>
                                </a>
                            <?php } ?>


                            <a class="btn btn-default btn-default btn-default" data-toggle="tooltip"
                               title="Imprimir" data-original-title="Ver Detalle"
                               href="#" onclick="imprimircompra('<?= $ingreso->id_ingreso ?>');">
                                <i class="fa fa-print"></i>
                            </a>


                        </div>
                    </td>


                </tr>
            <?php }
        } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>
        </tfoot>
    </table>
</div>


<div class="modal fade" id="ver" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" method="post" action="<?= $ruta ?>grupo/eliminar">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Anular Ingreso</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea anular el ingreso seleccionado?</p>
                    <input type="hidden" name="id" id="id_ingreso">
                    <input type="hidden" name="nombre" id="local_id">
                </div>
                <div class="modal-footer">
                    <input type="button" id="" class="btn btn-primary" value="Confirmar" onclick="anular()">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>

<div class="modal fade" id="eliminar_ingreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminarIngreso" method="post" action="<?= $ruta ?>grupo/eliminar">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Ingreso</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar el ingreso seleccionado?</p>
                    <input type="hidden" name="id_ingreso_e" id="id_ingreso_e">
                    <input type="hidden" name="local_elim" id="local_elim">
                </div>
                <div class="modal-footer">
                    <input type="button" id="" class="btn btn-primary" value="Confirmar" onclick="eliminar()">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>

<div class="modal fade" id="ingresomodal" style="width: 95%;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i>
            </button>

            <h3>Editar Ingreso</h3>
        </div>
        <div class="modal-body" id="ingresomodalbody">

        </div>

    </div>

</div>

<div class="modal fade" id="imprimirlistaingreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<script type="text/javascript">

    var id_ingreso = "";
    var id_local = "";

    $(function () {
        TablesDatatables.init(3);
    });

    function imprimircompra(id) {


        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();

        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        var username ='<?= $this->session->userdata('username')?> ';
        var EMPRESA_NOMBRE ='<?= $this->session->userdata('EMPRESA_NOMBRE')?> ';
        var id_local ='<?= $this->session->userdata('id_local')?> ';


        if (is_nube) {

            $.ajax({
                url: baseurl + 'api/Ingreso/getDataDirectPrintCompra',
                type: 'POST',
                data: {  'ingreso_id': id},
                success: function (data) {
                    var urltickera = TICKERA_URL;

                    var url = urltickera + '/directPrintCompra/';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            ingreso:data.ingreso,
                            EMPRESA_NOMBRE:EMPRESA_NOMBRE,
                            EMPRESA_DIRECCION:'<?=$this->session->userdata('EMPRESA_DIRECCION') ?>',
                            EMPRESA_TELEFONO: '<?=$this->session->userdata('EMPRESA_TELEFONO') ?>',
                            NIT: '<?= $this->session->userdata('NIT') ?>',
                            IMPRESORA: '<?= $this->session->userdata('IMPRESORA') ?>'
                        },
                        success: function (data) {
                            Utilities.alertModal('El reporte se ha enviado a la impresora', 'success');

                        }, error: function () {
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                        }
                    });


                }, error: function () {

                }
            });


        } else {
            $("#imprimirlistaingreso").load(baseurl + 'ingresos/directPrintCompra/' + id);
            Utilities.alertModal('Se ha enviado el documento a la impresora', 'success');
        }



    }

    function ver(id, local) {


        $("#ver").load('<?= base_url()?>ingresos/form/' + id + '/' + local);
        $('#ver').modal('show');

    }
    function modal_anular(id, local) {

        id_ingreso = id;
        id_local = local;
        $('#borrar').modal('show');
        $("#id_ingreso").attr('value', id);
        $("#local_id").attr('value', local);
    }

    function modal_eliminar(id, local) {

        id_ingreso = id;
        id_local = local;
        $('#eliminar_ingreso').modal('show');
        $("#id_ingreso_e").attr('value', id);
        $("#local_elim").attr('value', local);
    }

    function editaringreso(id) {
        Utilities.showPreloader();
        $.ajax({
            url: '<?php echo base_url()?>ingresos',
            data: {'idingreso': id, 'editar': 1},
            type: 'post',
            success: function (data) {
                $('#page-content').html(data);
            }
        })


    }

    function anular() {

        Utilities.showPreloader();

        $.ajax({
            url: '<?= base_url()?>ingresos/anular_ingreso',
            data: {
                'id': id_ingreso,
                'local': id_local
            },
            type: 'POST',
            'dataType': 'json',
            success: function (data) {
                Utilities.hiddePreloader();

                if (data.error == undefined) {

                    $("#borrar").modal('hide');

                    Utilities.alertModal('<h4>El ingreso ha sido anulado</h4>', 'success', true);

                    recargarlista();

                } else {
                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'error', true);

                }
            },
            error: function () {
                Utilities.hiddePreloader();

                Utilities.alertModal('<h4>Ha ocurrido un error</h4>', 'error', true);
            }
        });

    }

    function eliminar() {

        Utilities.showPreloader();


        $.ajax({
            url: '<?= base_url()?>ingresos/eliminar_ingreso',
            data: {
                'id': id_ingreso,
                'local': id_local
            },
            type: 'POST',
            'dataType': 'json',
            success: function (data) {
                Utilities.hiddePreloader();

                if (data.error == undefined) {

                    $("#eliminar_ingreso").modal('hide');

                    Utilities.alertModal('<h4>El ingreso ha sido eliminado</h4>', 'success', true);

                    recargarlista();

                } else {
                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'error', true);

                }
            },
            error: function () {
                Utilities.hiddePreloader();

                Utilities.alertModal('<h4>Ha ocurrido un error</h4>', 'error', true);
            }
        });


    }

    function acomodar(id, local) {

        Utilities.showPreloader();


        $.ajax({
            url: '<?= base_url()?>ingresos/modificarcompraespecifico',
            data: {
                'id': id,
                'local': local
            },
            type: 'POST',
            'dataType': 'json',
            success: function (data) {
                Utilities.hiddePreloader();

                if (data.error == undefined) {

                    $("#eliminar_ingreso").modal('hide');

                    Utilities.alertModal('<h4>El ingreso ha sido modificado</h4>', 'success', true);

                    recargarlista();

                } else {
                    Utilities.alertModal('<h4>' + data.error + '</h4>', 'error', true);

                }
            },
            error: function () {
                Utilities.hiddePreloader();

                Utilities.alertModal('<h4>Ha ocurrido un error</h4>', 'error', true);
            }
        });


    }
</script>