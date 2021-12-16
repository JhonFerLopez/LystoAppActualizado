<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Grupos</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <div class="row">


            <!-- Progress Bars Wizard Title -->



            <a class="btn btn-primary" onclick="agregargrupo();">
                <i class="fa fa-plus "> Nuevo</i>
            </a>
            <br>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="example">
                    <thead>
                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th class="desktop">Acciones</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($niveles) > 0) {

                        foreach ($niveles as $nivel) {
                            ?>
                            <tr>

                                <td class="center"><?= $nivel->nivel_id ?></td>
                                <td><?= $nivel->nombre_nivel ?></td>
                                <td><?= $nivel->nivel ?></td>

                                <td class="center">
                                    <div class="btn-group">
                                        <?php

                                        echo '<a class="btn btn-default btn-default btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="Editar"
                                            href="#" onclick="editargrupo(' . $nivel->nivel_id . ');">'; ?>
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <?php echo '<a class="btn btn-default btn-default btn-default" data-toggle="tooltip"
                                     title="Eliminar" data-original-title="Eliminar" onclick="borrargrupo(' . $nivel->nivel_id . ',\'' . $nivel->nombre_nivel . '\');">'; ?>
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


        <script type="text/javascript">

            function borrargrupo(id, nom) {

                $('#borrargrupo').modal('show');
                $("#id_borrar").attr('value', id);
                $("#nom_borrar").attr('value', nom);
            }


            function editargrupo(id) {
                $("#agregargrupo").load('<?= $ruta ?>grupo/formnivel/' + id);
                $('#agregargrupo').modal('show');
            }

            function agregargrupo() {
                $("#agregargrupo").load('<?= $ruta ?>grupo/formnivel');
                $('#agregargrupo').modal('show');
            }

            var gruponivel = {
                ajaxgrupo: function () {
                    return $.ajax({
                        url: '<?= base_url()?>grupo/niveles'

                    })
                },
                guardar: function () {
                    if ($("#nombre").val() == '') {
                        $("#nombre").focus();
                        Utilities.alertModal('<h4>Debe seleccionar el nombre</h4>', 'warning', true);
                        return false;
                    }

                    if ($("#nivel").val() == '') {
                        $("#nivel").focus();
                        Utilities.alertModal('<h4>Debe seleccionar un nivel</h4>', 'warning', true);
                        return false;
                    }


                    App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'agregargrupo', 'formagregar');
                }
            }
            function eliminar() {

                App.formSubmitAjax($("#formeliminar").attr('action'), gruponivel.ajaxgrupo, 'borrargrupo', 'formeliminar');

            }


        </script>
        <div class="modal fade" id="agregargrupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

        </div>


        <div class="modal fade" id="borrargrupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>grupo/eliminarnivel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Eliminar Nivel</h4>
                        </div>
                        <div class="modal-body">
                            <p>Est&aacute; seguro que desea eliminar el nivel seleccionado?</p>
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
