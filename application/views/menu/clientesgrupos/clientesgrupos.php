<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Grupos cliente</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>

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

            <?php
            echo validation_errors('<div class="alert alert-danger alert-dismissable"">', "</div>");
            ?>

            <!-- Progress Bars Wizard Title -->


            <a class="btn btn-primary" onclick="agregar();">
                <i class="fa fa-plus "> Nueva</i>
            </a>
            <br>

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
                    <?php if (count($grupos) > 0) {

                        foreach ($grupos as $grupo) {
                            ?>
                            <tr>

                                <td class="center"><?= $grupo['id_grupos_cliente'] ?></td>
                                <td><?= $grupo['nombre_grupos_cliente'] ?></td>


                                <td class="center">
                                    <div class="btn-group">
                                        <?php

                                        echo '<a class="btn btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="fa fa-comment-o"
                                            href="#" onclick="editar(' . $grupo['id_grupos_cliente'] . ');">'; ?>
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <?php echo '<a class="btn btn-default" data-toggle="tooltip"
                                     title="Eliminar" data-original-title="fa fa-comment-o"
                                     onclick="borrar(' . $grupo['id_grupos_cliente'] . ',\'' . $grupo['nombre_grupos_cliente'] . '\');">'; ?>
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


        <!-- Modales for Messages -->
        <div class="modal hide" id="mOK">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        onclick="javascript:window.location.reload();">
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

    </div>
</div>


<script type="text/javascript">

    function borrar(id, nom) {

        $('#borrar').modal('show');
        $("#id_borrar").attr('value', id);
        $("#nom_borrar").attr('value', nom);
    }


    function editar(id) {
        Utilities.showPreloader();
        $.ajax({
            url: '<?= $ruta ?>clientesgrupos/form/' + id,
            type: 'post',

            success: function (data) {
                Utilities.hiddePreloader();
                $("#agregar").html(data);
                $('#agregar').modal({show: true, keyboard: false, backdrop: 'static'});
            },
            error: function (error) {

                Utilities.hiddePreloader();

                Utilities.alertModal('Ha ocurrido un error');

            }

        });


    }

    function agregar() {
        Utilities.showPreloader();
        $.ajax({
            url: '<?= $ruta ?>clientesgrupos/form',
            type: 'post',
            success: function (data) {
                Utilities.hiddePreloader();
                $("#agregar").html(data);
                $('#agregar').modal({show: true, keyboard: false, backdrop: 'static'});
            },
            error: function (error) {

                Utilities.hiddePreloader();
                Utilities.alertModal('Ha ocurrido un error');
            }

        });
    }

    var grupo = {
        ajaxgrupo: function () {
            return $.ajax({
                url: '<?= base_url()?>clientesgrupos'

            })
        },
        guardar: function () {
            if ($("#nombre_grupos_cliente").val() == '') {

                Utilities.alertModal('Debe seleccionar el nombre','warning');


                $(this).prop('disabled', true);

                return false;
            }
            App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'agregar', 'formagregar');
        }
    }

    function eliminar() {

        App.formSubmitAjax($("#formeliminar").attr('action'), grupo.ajaxgrupo, 'borrar', 'formeliminar');

    }
</script>

<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>clientesgrupos/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Grupo</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar el Grupo de Cliente seleccionado?</p>
                    <input type="hidden" name="id" id="id_borrar">
                    <input type="hidden" name="nombre" id="nom_borrar">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary" onclick="eliminar()">Confirmar
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