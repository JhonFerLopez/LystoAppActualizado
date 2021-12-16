<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Clientes</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>



<!--row -->
<div class="row">



    <div class="col-md-12">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissable" id="success"
                         style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                        <span id="successspan"><?php echo isset($success) ? $success : '' ?></div>
                    </span>
                </div>

                <?php
                echo validation_errors('<div class="alert alert-danger alert-dismissable">', "</div>");
                ?>


            </div>

            <a class="btn btn-primary" onclick="Cliente.agregar();">
                <i class="fa fa-plus "> Nuevo</i>
            </a>
            <br>

            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="tabla">
                    <thead>
                    <tr>


                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombres</th>
                        <th>Apelidos</th>
                        <th>Identificacion</th>
                        <th>Direcci&oacute;n</th>
                        <th class="desktop">Acciones</th>

                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>

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


            <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">


            </div>

            <div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>cliente/eliminar">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">Eliminar Cliente</h4>
                            </div>
                            <div class="modal-body">
                                <p>Est&aacute; seguro que desea eliminar el Cliente seleccionado?</p>
                                <input type="hidden" name="id" id="id_borrar">
                                <input type="hidden" name="nombre" id="nom_borrar">
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="confirmar" class="btn btn-primary"
                                        onclick="Cliente.eliminar()">Confirmar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                            </div>
                        </div>
                        <!-- /.modal-content -->


                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {

        TablesDatatablesLazzy.init('<?php echo base_url()?>api/Clientes', 0, 'tabla');


    });
</script>