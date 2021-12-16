<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Tipo producto</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Parametrizacion</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">

            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-success alert-dismissable" id="success"
                         style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                        <span id="successspan"><?php echo isset($success) ? $success : '' ?></div>
                    </span>
                </div>
            </div>


            <!-- Progress Bars Wizard Title -->


            <a class="btn btn-primary" onclick="agregargrupo();">
                <i class="fa fa-plus "> Nuevo</i>
            </a>
            <br>


            <?php
            echo validation_errors('<div class="alert alert-danger alert-dismissable"">', "</div>");
            ?>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="example">
                    <thead>
                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>

                        <th class="desktop">Acciones</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($marcas) > 0) {

                        foreach ($marcas as $marca) {
                            ?>
                            <tr>

                                <td class="center"><?= $marca['tipo_prod_id'] ?></td>
                                <td><?= $marca['tipo_prod_nombre'] ?></td>


                                <td class="center">
                                    <div class="btn-group">
                                        <?php

                                        echo '<a class="btn btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="fa fa-comment-o"
                                            href="#" onclick="editargrupo(' . $marca['tipo_prod_id'] . ');">'; ?>
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <?php echo '<a class="btn btn-default" data-toggle="tooltip"
                                     title="Eliminar" data-original-title="fa fa-comment-o" onclick="borrargrupo(' . $marca['tipo_prod_id'] . ',\'' . $marca['tipo_prod_nombre'] . '\');">'; ?>
                                        <i class="fa fa-trash-o"></i>
                                        </a>
                                    </div>

                                </td>
                            </tr>
                        <?php }
                    } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modales for Messages -->
<div class="modal hide" id="mOK">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload();">
            X
        </button>
        <h3>Notificaci&oacute;n</h3>
    </div>
    <div class="modal-body">
        <p>Registro Exitosa</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary" data-dismiss="modal"
           onclick="javascript:window.location.reload();">Close</a>
    </div>
</div>


<script type="text/javascript">

    function borrargrupo(id, nom) {

        $('#borrar').modal('show');
        $("#id_borrar").attr('value', id);
        $("#nom_borrar").attr('value', nom);
    }


    function editargrupo(id) {
        $("#agregargrupo").load('<?= $ruta ?>tipo_producto/form/' + id);
        $('#agregargrupo').modal('show');
    }

    function agregargrupo() {

        $("#agregargrupo").load('<?= $ruta ?>tipo_producto/form');
        $('#agregargrupo').modal('show');
    }

    var marca = {
        ajaxgrupo: function () {
            return $.ajax({
                url: '<?= base_url()?>tipo_producto'
            })
        },
        guardar: function () {
            if ($("#nombre").val() == '') {
                var growlType = 'warning';

                $.bootstrapGrowl('<h4>Debe seleccionar el nombre</h4>', {
                    type: growlType,
                    delay: 2500,
                    allow_dismiss: true
                });

                $(this).prop('disabled', true);

                return false;
            }
            App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'agregargrupo', 'formagregar');
        }
    }
    function eliminar() {

        App.formSubmitAjax($("#formeliminar").attr('action'), marca.ajaxgrupo, 'borrar', 'formeliminar');

    }
</script>

<div class="modal fade" id="agregargrupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>tipo_producto/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Tipo</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar el tipo de producto seleccionado</p>
                    <input type="hidden" name="id" id="id_borrar">
                    <input type="hidden" name="nombre" id="nom_borrar">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary" onclick="eliminar()">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>
<!-- /.modal-dialog -->
</div>

<script>$(function () {
        TablesDatatables.init();
    });</script>