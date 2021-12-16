<?php $ruta = base_url(); ?>


<ul class="breadcrumb breadcrumb-top">
    <li>L&iacute;neas</li>
    <li><a href="">Agregar y editar l&iacute;neas</a></li>
</ul>

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

<div class="block">
    <!-- Progress Bars Wizard Title -->


    <a class="btn btn-primary" onclick="agregargrupo();">
        <i class="fa fa-plus "> Nueva</i>
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
            <?php if (count($lineas) > 0) {

                foreach ($lineas as $linea) {
                    ?>
                    <tr>

                        <td class="center"><?= $linea['id_linea'] ?></td>
                        <td><?= $linea['nombre_linea'] ?></td>


                        <td class="center">
                            <div class="btn-group">
                            <?php

                            echo '<a class="btn btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="fa fa-comment-o"
                                            href="#" onclick="editargrupo(' . $linea['id_linea'] . ');">'; ?>
                            <i class="fa fa-edit"></i>
                            </a>
                            <?php echo '<a class="btn btn-default" data-toggle="tooltip"
                                     title="Eliminar" data-original-title="fa fa-comment-o"
                                     onclick="borrargrupo(' . $linea['id_linea'] . ',\'' . $linea['nombre_linea'] . '\');">'; ?>
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

        $('#borrar').modal('show');
        $("#id_borrar").attr('value', id);
        $("#nom_borrar").attr('value', nom);
    }


    function editargrupo(id) {


        $("#agregar").load('<?= $ruta ?>linea/form/'+id);
        $('#agregar').modal('show');
    }

    function agregargrupo() {

        $("#agregar").load('<?= $ruta ?>linea/form');
        $('#agregar').modal('show');
    }

    var grupo = {
        ajaxgrupo : function(){
            return  $.ajax({
                url:'<?= base_url()?>linea'

            })
        },
        guardar : function () {
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
            App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'agregar', 'formagregar');
        }
    }
    function eliminar(){

        App.formSubmitAjax($("#formeliminar").attr('action'), grupo.ajaxgrupo, 'borrar', 'formeliminar' );

    }

</script>

<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>linea/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar L&iacute;nea</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar la l&iacute;nea seleccionada</p>
                    <input type="hidden" name="id" id="id_borrar">
                    <input type="hidden" name="nombre" id="nom_borrar">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary" onclick="eliminar()">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>
<!-- /.modal-dialog -->
</div>

<script>$(function(){ TablesDatatables.init(); });</script>