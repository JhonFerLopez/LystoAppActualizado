<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Logs</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Logs</li>
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

                    <div class="col-md-1">
                        Desde
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fecha_desde" onchange="get_logs()" id="fecha_desde"
                               value="<?= date('d-m-Y'); ?>" required="true"
                               class="form-control fecha campos input-datepicker ">
                    </div>
                    <div class="col-md-1">
                        Hasta
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fecha_hasta" onchange="get_logs()" id="fecha_hasta"
                               value="<?= date('d-m-Y'); ?>" required="true"
                               class="form-control fecha campos input-datepicker">
                    </div>

                    <div class="col-md-1">
                        Usuario
                    </div>

                    <div class="col-md-2">
                        <select name="usuario" onchange="get_logs()" id="usuario" class="form-control">
                            <option value="">Seleccione</option>
                            <?php

                            foreach ($usuarios as $usuario) {
                                ?>
                                <option value="<?= $usuario->nUsuCodigo?>"><?= $usuario->nombre ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                </div>
            </div>

            <table class="table table-striped dataTable table-bordered" id="tabla">
                <thead>
                <tr>


                    <th>ID</th>
                    <th>USUARIO</th>
                    <th>IP</th>
                    <th>FECHA</th>
                    <th>TABLA</th>
                    <th>MOVIMIENTO</th>
                    <th>ANTES</th>
                    <th>DESPUES</th>

                </tr>
                </thead>
                <tbody>



                </tbody>
            </table>






        </div>
    </div>
</div>


<script>

    function get_logs() {


        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var usuario = $("#usuario").val();


        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/SystemLogs/datatable', 0, 'tabla', {
            fecha_desde: fercha_desde,
            fecha_hasta: fercha_hasta,
            usuario: usuario,
        }, 'Reporte de participacion en ventas');


    }
    $(function () {



        get_logs();


    });
</script>