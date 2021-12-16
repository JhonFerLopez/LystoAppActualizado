<div class="row bg-title">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Reporte de contribuci&oacute;n marginal <?php if (isset($productos)) {
                echo "Por Productos";
            } ?>
            <?php if (isset($cliente)) {
                echo "Por Clientes";
            } ?>
            <?php if (isset($proveedor)) {
                echo "Por Proveedor";
            } ?></h4></div>
    <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?php base_url() ?>">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <?php if (isset($todo)) { ?>  <input type="hidden" id="utilidades" value="TODOS">  <?php } ?>
            <?php if (isset($productos)) { ?>  <input type="hidden" id="utilidades" value="PRODUCTOS">  <?php } ?>
            <?php if (isset($cliente)) { ?>  <input type="hidden" id="utilidades" value="CLIENTE">  <?php } ?>
            <?php if (isset($proveedor)) { ?>  <input type="hidden" id="utilidades" value="PROVEEDOR">  <?php } ?>
            <div class="row">
                <div class="col-md-2">
                    Desde
                </div>
                <div class="col-md-4">
                    <input type="text" name="fecha_desde" id="fecha_desde" required="true" readonly value="<?= date('d-m-Y')?>"
                           class="form-control fecha campos input-datepicker">
                </div>
                <div class="col-md-2">
                    Hasta
                </div>
                <div class="col-md-4">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" required="true" readonly value="<?= date('d-m-Y')?>"
                           class="form-control fecha campos input-datepicker">
                </div>
            </div>

            <div class="row">
                <!--  -->
                <div class="col-md-2">
                    Grupo
                </div>
                <div class="col-md-4">
                    <select id="id_grupo" class="form-control campos select2normal" name="id_grupo">
                        <option value="">SELECCIONE</option>
                        <?php
                        foreach ($grupos as $row) {
                            ?>
                            <option value="<?= $row['id_grupo'] ?>"><?= $row['nombre_grupo'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    Tipo
                </div>
                <div class="col-md-4">
                    <select id="tipo_prod_id" class="form-control campos" name="tipo_prod_id">
                        <option value="">SELECCIONE</option>
                        <?php
                        foreach ($tipos as $row) {
                            ?>
                            <option value="<?= $row['tipo_prod_id'] ?>"><?= $row['tipo_prod_nombre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!--  -->
                <div class="col-md-2">
                    Clasificaci&oacute;n
                </div>
                <div class="col-md-4">
                    <select id="clasificacion_id" class="form-control campos" name="clasificacion_id">
                        <option value="">SELECCIONE</option>
                        <?php
                        foreach ($clasificaciones as $row) {
                            ?>
                            <option value="<?= $row['clasificacion_id'] ?>"><?= $row['clasificacion_nombre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2">
                    Ubicación Física
                </div>
                <div class="col-md-4">
                    <select id="ubicacion_id" class="form-control campos" name="ubicacion_id">
                        <option value="">SELECCIONE</option>
                        <?php
                        foreach ($ubicaciones as $row) {
                            ?>
                            <option value="<?= $row['ubicacion_id'] ?>"><?= $row['ubicacion_nombre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">

                <div class="col-md-2">
                    Principio Activo
                </div>
                <div class="col-md-4">
                    <select id="componente_id" class="form-control campos" name="componente_id" multiple="true"
                            data-placeholder="Seleccione un Principio Activo">
                        <?php
                        foreach ($componentes as $row) {
                            ?>
                            <option value="<?= $row['componente_id'] ?>"><?= $row['componente_nombre'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

            </div>


            <div class="box-body" id="tabla">

                <br>

            </div>

            <!-- -->
        </div>


    </div>
</div>


<!-- /.modal-dialog -->

<script type="text/javascript">
    $(function () {

        $(".campos").on("change", function () {

            traer();
        });

        $('select').chosen({search_contains: true});
        $('.input_datepicker').datepicker({weekStart: 1, format: 'dd-mm-yyyy'});
        traer();

    });

    function traer() {

        Utilities.showPreloader();
        $.ajax({
            url: '<?= base_url()?>reportes/getUtiidadesVentas',
            data: {
                'fecIni': $("#fecha_desde").val(),
                'fecFin': $("#fecha_hasta").val(),
                'id_grupo': $("#id_grupo").val(),
                'tipo_prod_id': $("#tipo_prod_id").val(),
                'componente_id': $("#componente_id").val(),
                'ubicacion_id': $("#ubicacion_id").val(),
                'clasificacion_id': $("#clasificacion_id").val(),
                'utilidades':"TODOS"
            },
            type: 'POST',
            success: function (data) {
                // $("#query_consul").html(data.consulta);
                if (data.length > 0)
                    $("#tabla").html(data);
                $("#tablaresult").dataTable();
                Utilities.hiddePreloader();
            },
            error: function () {
                Utilities.hiddePreloader();
                Utilities.alertModal("Ha ocurrido un error, por favor intente nuevamente")
            }
        })


    }


</script>
