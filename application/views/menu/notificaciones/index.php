<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Notificaciones</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Notificaciones</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">

            <a class="btn btn-primary" onclick="Notificaciones.modalNewNotificacion(true);">
                <i class="fa fa-plus ">Enviar Nuevo Mensaje</i>
            </a>
            <br>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="example">
                    <thead>
                    <tr>

                        <th>ID</th>
                        <th>Aplicación</th>
                        <th>Fecha</th>
                        <th>Título</th>
                        <th>Mensaje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($notificaciones && count($notificaciones) > 0) {
                        foreach ($notificaciones as $notificacion) { ?>
                            <tr>
                                <td class="center"><?= $notificacion['id'] ?></td>
                                <td><?= $notificacion['aplicacion'] ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($notificacion['fecha'])) ?></td>
                                <td><?= $notificacion['titulo'] ?></td>
                                <td><?= $notificacion['mensaje'] ?></td>
                            </tr>
                        <?php }
                    } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>$(function () {
        TablesDatatables.init();
    });</script>