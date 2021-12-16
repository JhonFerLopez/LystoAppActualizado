<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Droguerias relacionadas</h4></div>
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

            <a class="btn btn-primary" onclick="DrogueriaRelacionada.add();">
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

                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Dominio/IP</th>

                        <th class="desktop">Acciones</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($droguerias_relacionadas) > 0) {

                        foreach ($droguerias_relacionadas as $drogueria) {
                            ?>
                            <tr>

                                <td class="center"><?= $drogueria['drogueria_id'] ?></td>
                                <td><?= $drogueria['drogueria_nombre'] ?></td>
                                <td><?= $drogueria['drogueria_domain'] ?></td>


                                <td class="center">
                                    <div class="btn-group">
                                        <?php

                                        echo '<a class="btn btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="fa fa-comment-o"
                                            href="#" onclick="DrogueriaRelacionada.edit(' . $drogueria['drogueria_id'] . ');">'; ?>
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <?php echo '<a class="btn btn-default" data-toggle="tooltip"
                                     title="Eliminar" data-original-title="fa fa-comment-o" onclick="DrogueriaRelacionada.delete(' . $drogueria['drogueria_id'] . ',\'' . $drogueria['drogueria_nombre'] . '\');">'; ?>
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
        <button type="button" class="close" data-dismiss="modal"
                onclick="javascript:window.location.reload();">
        </button>
        <h3>Notificaci&oacute;n</h3>
    </div>
    <div class="modal-body">
        <p>Registro Exitoso</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary" data-dismiss="modal"
           onclick="javascript:window.location.reload();">Close</a>
    </div>
</div>


<script type="text/javascript">

</script>


<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post"
          action="<?= $ruta ?>drogueria_relacionada/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">Eliminar empresa</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar la empresa?</p>
                    <input type="hidden" name="drogueria_relacionada_id" id="id_borrar">
                    <input type="hidden" name="drogueria_relacionada_nombre" id="nom_borrar">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary"
                            onclick="DrogueriaRelacionada.confirmdelete()">Confirmar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>


<script>$(function () {
        TablesDatatables.init();
        DrogueriaRelacionada.init();
    });</script>