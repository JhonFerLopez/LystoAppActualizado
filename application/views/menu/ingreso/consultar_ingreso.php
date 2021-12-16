<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Ingresos</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">Ingresos</a></li>
            <li class="active">Reporte de Ingreso</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>


<div class="row">
    <div class="col-md-12">
        <div class="white-box">


            <div class="form-group row">
                <div class="col-md-2">
                    Ubicaci&oacute;n
                </div>
                <div class="col-md-4">
                    <select id="locales" class="form-control campos" name="locales">

                        <?php if (isset($locales)) {
                            foreach ($locales as $local) {
                                ?>
                                <option <?php if ($this->session->userdata('id_local') == $local['int_local_id']) echo 'selected'; ?>
                                        value="<?= $local['int_local_id']; ?>"> <?= $local['local_nombre'] ?> </option>

                            <?php }
                        } ?>

                    </select>

                </div>

                <div class="col-md-2">
                    Status
                </div>
                <div class="col-md-4">
                    <select id="status" class="form-control campos" name="status">
                        <option value="seleccione"> Seleccione</option>
                        <option selected value="<?= INGRESO_COMPLETADO ?>"><?= INGRESO_COMPLETADO ?></option>
                        <option value="<?= INGRESO_ANULADO ?>"><?= INGRESO_ANULADO ?></option>
                        <option value="<?= INGRESO_PENDIENTE ?>"><?= INGRESO_PENDIENTE ?></option>
                    </select>

                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2">
                    Desde
                </div>
                <div class="col-md-4">
                    <input type="text" name="fecha_desde" value="<?= date('d-m-Y') ?>"
                           id="fecha_desde" required="true" class="form-control fecha campos">
                </div>
                <div class="col-md-2">
                    Hasta
                </div>
                <div class="col-md-4">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y') ?>"
                           required="true" class="form-control fecha campos">
                </div>

            </div>
            <div class="row">
                <div class="col-md-2">
                    Proveedor
                </div>
                <div class="col-md-4">
                    <select id="proveedor" class="form-control campos" name="proveedor">
                        <option value="seleccione"> Seleccione</option>
                        <?php

                        foreach ($proveedores as $proveedor):

                            ?>
                            <option value="<?= $proveedor['id_proveedor'] ?>"><?= $proveedor['proveedor_nombre'] ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>

                </div>
            </div>
           <br>
            <div class="row">
                <div class="col-md-12" id="tabla">

                </div>
            </div>

            <br>

        </div>
    </div>
</div>


<!-- /.modal-dialog -->

<script type="text/javascript">
    $(function () {
        recargarlista();

        $(".fecha").datepicker({
            format: 'dd-mm-yyyy'
        });
        $(".campos").on("change", function () {

            recargarlista();

        });

    });

    function recargarlista() {
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var locales = $("#locales").val();
        var status = $("#status").val();
        var proveedor = $("#proveedor").val();

        // $("#hidden_consul").remove();

        $.ajax({
            url: '<?= base_url()?>ingresos/get_ingresos',
            data: {
                'id_local': locales,
                'desde': fercha_desde,
                'hasta': fercha_hasta,
                'status': status,
                'proveedor': proveedor
            },
            type: 'POST',
            success: function (data) {
                // $("#query_consul").html(data.consulta);
                if (data.length > 0) {
                    $("#tabla").html(data);

                }
            },
            error: function () {

                alert('Ocurrio un error por favor intente nuevamente');
            }
        })
    }


</script>
