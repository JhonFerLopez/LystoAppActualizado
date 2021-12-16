<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Usuarios</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>

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


            <div class="row">
                <div class="col-md-1">
                    <a class="btn btn-primary" onclick="agregar();">
                        <i class="fa fa-plus "> Nuevo</i>
                    </a>
                </div>

            </div>
            <br>

            <div class="box-content box-nomargin">
                <div class="table-responsive">


                    <table class='table table-striped table-media dataTable table-bordered' id="tabla_usuarios">
                        <thead>
                        <tr>
                            <th>ID Usuario</th>
                            <th>Uername</th>

                            <th>Cargo&nbsp;&nbsp;</th>
                            <th>Genero</th>
                            <th>Sueldo</th>
                            <!-- <th>Zonas</th>-->
                            <th>Acceso Movil</th>
                            <th>Status</th>
                            <th>Accion</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($lstUsuario) > 0): ?>
                            <?php foreach ($lstUsuario as $usu): ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $usu->nUsuCodigo; ?></td>
                                    <td style="text-align: center;"><?php echo $usu->username; ?></td>

                                    <td><?php echo $usu->nombre_grupos_usuarios; ?></td>
                                    <td><?php echo $usu->genero; ?></td>
                                    <td><?php echo $usu->sueldo; ?></td>
                                    <!-- <td class='actions_big'>
                                <div class="btn-group">
                                    <a style="cursor:pointer;"
                                       onclick="zonas(<?php echo $usu->nUsuCodigo; ?>,'<?php echo $usu->username; ?>')"
                                       class='btn btn-default tip' title="zonas">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>-->
                                    <td><?php if ($usu->smovil == '1') echo 'Si'; else echo 'No'; ?></td>
                                    <td><?php if ($usu->activo == '1') echo 'ACTIVO'; else echo 'INACTIVO'; ?></td>
                                    <td class='actions_big'>
                                        <div class="btn-group">
                                            <a style="cursor:pointer;"
                                               onclick="editar(<?php echo $usu->nUsuCodigo; ?>,'<?php echo $usu->username; ?>')"
                                               class='btn btn-default tip' title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" class='btn btn-default tip'
                                               onclick="borrar(<?php echo $usu->nUsuCodigo; ?>,'<?php echo $usu->username; ?>')"
                                               title="Eliminar">
                                                <i class="fa fa-trash-o"></i>
                                            </a>


                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>


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
        console.log(id);
        $("#agregar").load('<?= $ruta ?>usuario/form/' + id);
        $('#agregar').modal('show');
    }

    function zonas(id) {
        console.log(id);
        $("#zonas").load('<?= $ruta ?>usuario/zonas/' + id);
        $('#zonas').modal('show');
    }

    function agregar() {
        $("#agregar").load('<?= $ruta ?>usuario/form');
        $('#agregar').modal('show');
    }


    var usuario = {
        ajaxgrupo: function () {
            return $.ajax({
                url: '<?= base_url()?>usuario'

            })
        },
        guardar: function () {
            if ($("#smovil").is(':checked')) {
                if ($("#username").val() == '') {


                   Utilities.alertModal('Debe seleccionar el username');
                    return false;
                }

                if ($("#var_usuario_clave").val() == '' && $("#nUsuCodigo").val() == '') {

                    Utilities.alertModal('Debe ingresar el password');

                    return false;
                }
            }


            if ($("#nombre").val() == '') {
                Utilities.alertModal('Debe ingresar el nombre completo');


                return false;
            }


            if ($("#identificacion").val() == '') {
                Utilities.alertModal('Debe ingresar la identificación');
                return false;
            }

            if ($("#grupo").val() == '') {
                Utilities.alertModal('Debe selecionar el rol');
                return false;
            }

            App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'agregar', 'formagregar');
        }
    }

    function eliminar() {

        App.formSubmitAjax($("#formeliminar").attr('action'), usuario.ajaxgrupo, 'borrar', 'formeliminar');

    }
</script>


<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade bs-example-modal-lg" id="zonas" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">

</div>

<div class="modal fade " id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>usuario/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">Eliminar Usuario</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar el usuario seleccionado?</p>
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


<script>$(function () {
        TablesDatatables.init(0, 'tabla_usuarios');

    });</script>

