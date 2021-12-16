<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Valor del inventario</h4></div>
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
            <!-- Progress Bars Wizard Title -->


            <div class="row">
                <input type="hidden" name="listar" id="listar" value="ventas">

                <div class="col-md-1">
                    Fecha
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <!-- <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>

               -->


                <div class="col-md-3">
                    <button type="button" class="btn btn-info" onclick="get_ventas()"><i class="fa fa-bar-search"></i>Generar
                        reporte
                    </button>
                </div>


            </div>

            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">

                        <table id="tabla">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDADES</th>
                                <th>COSTO</th>
                                <th>IVA</th>
                                <th>COSTO+IVA</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- /.row -->


<script type="text/javascript">


    $(document).ready(function () {
        get_ventas();
    });
    function get_ventas() {


        var hoy = new Date(Date.now()).toLocaleString();
        var usuario = '<?= $this->session->userdata("username")?>';
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var vendedor = $("#vendedor").val();

        if (vendedor == '') {
            Utilities.alertModal('Debe seleccionar el vendedor');
            return false;
        }

        var mensajetop = 'Desde: ' + fercha_desde + '<br>&#013;Hasta: ' + fercha_hasta + '<br>&#013;Fecha-Hora: ' + hoy + '<br>&#013;Vendedor: ' + vendedor + '<br>&#013;Usuario: ' + usuario;
        $('#tabla').append('<caption style="caption-side: top">' + mensajetop + '</caption>');

        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Inventario/valor', 0, 'tabla', {
            fecha_desde: fercha_desde,
            fecha_hasta: fercha_hasta,
            vendedor: vendedor,
            reporte: true,
        }, 'Reporte de participacion en ventas', false, false,false,false,false,false,'300px');


    }


</script>
