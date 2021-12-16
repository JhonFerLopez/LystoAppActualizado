<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Reportes</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="">Reportes</li>
            <li class="active">Informes Estad&iacute;sticos</li>
            <li class="active">Promedio de Compras por Cliente</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">
                <div class="col-md-1">
                    Estatus
                </div>
                <div class="col-md-3">
                    <select id="estatus" class="form-control campos" name="estatus" multiple>
                        <option value="COMPLETADO" selected>COMPLETADO</option>
                        <option value="EN ESPERA">EN ESPERA</option>
                        <option value="ANULADO">ANULADO</option>
                        <option value="DEVUELTO">DEVUELTO</option>
                        <option value="ELIMINADO">ELIMINADO</option>
                    </select>
                </div>
                <div class="col-md-1">
                    Desde
                </div>
                <div class="col-md-3">
                    <input type="text" readonly name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-3">
                    <input type="text" readonly name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>



            </div>

            <div class="divider"><br></div>

            <input type="hidden" name="listar" id="listar" value="ventas">

            <div class="row">
                <div class="col-md-12">
                    <div class="box-body" id="tabla">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    function buscar(){
        Utilities.showPreloader();
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();

        $.ajax({
            url: baseurl + 'reportes/tbl_prom_compra_cliente',
            data: {
                'desde': fercha_desde,
                'hasta': fercha_hasta,
                'estatus':$("#estatus").val()
            },
            type: 'POST',
            success: function (data) {
                Utilities.hiddePreloader();
                if (data.length > 0) {
                    $("#tabla").html(data);
                }
                $("#tablaresult").dataTable();
            },
            error: function () {
                Utilities.hiddePreloader();
                Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
            }
        })
    }

    $(function () {

        $("#estatus").select2(
            {
                //dropdownParent: $("#modal_enviar"),
                allowClear: true,
                language: "es",
                width: "100%",
                placeholder: 'Buscar Estatus',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work

                language: {
                    inputTooShort: function () {
                        return 'Ingrese un nombre para buscar';
                    },
                    noResults: function () {
                        return "Sin resultados";
                    }
                }
            });
        buscar();
        $(".campos").on("change", function () {
            buscar();
        });
    });


</script>
