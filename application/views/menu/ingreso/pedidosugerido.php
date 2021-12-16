<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Ventas</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Ventas</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">

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

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">

                <div class="col-md-1">
                    Tipo
                </div>
                <div class="col-md-3">
                    <select id="tipo" class="form-control campos" name="tipo">
                        <!--<option value="">SELECCIONE</option>-->
                        <option value="POR_FECHA">POR FECHA</option>
                        <option value="POR_STOCK">POR STOCK</option>
                        <!--<option value="EN ESPERA">EN ESPERA</option>
                        <option value="ANULADO">ANULADO</option>
                        <option value="DEVUELTO">DEVUELTO</option>-->
                    </select>
                </div>
                <div class="col-md-1">
                    Desde
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>



            </div>

            <div class="divider"><br></div>

            <input type="hidden" name="listar" id="listar" value="ventas">

            <div class="row">
                <div class="col-md-12">
                    <div class="box-body" id="">
                        <form id="form_pedido" >
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead>
                            <tr>
                                <th rowspan="2">Código</th>
                                <th rowspan="2">Descripción</th>
                                <th colspan="3">Vendido</th>
                                <th colspan="3">Inventario</th>
                                <th rowspan="2">Cantidad a pedir</th>
                            </tr>
                            <tr>


                                <th>Caja</th>
                                <th>Blister</th>
                                <th>Unidad</th>


                                <th>Caja</th>
                                <th>Blister</th>
                                <th>Unidad</th>

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
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                            </tfoot>
                        </table>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<a class="dt-button reportbutton" id="exportexcel" data-type="excel" href="#" tabindex="0" aria-controls="tabla" data-href="#"><span>EXCEL</span></a>
<a class="dt-button reportbutton" id="" data-type="txt" href="#" tabindex="0" aria-controls="tabla" data-href="#"><span>TXT</span></a>

<script type="text/javascript">

    var tabla;
    function get_data_pedido_sugerido() {


        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var usuario = $("#usuario").val();
        var tipo = $("#tipo").val();
        var form_pedido = $("#form_pedido").serialize();

        if(tipo=='POR_FECHA') {

            tabla = TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/geProductosParaPedidoSugerido', 0, 'tabla',
                {
                    fecha_desde: fercha_desde,
                    fecha_hasta: fercha_hasta,
                    reporte: true,
                    tipo: tipo,
                }, false, false, false, false, [], true, false, '300px'
            );
        }
        if(tipo=='POR_STOCK') {

            tabla = TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/geProductosParaPedidoSugeridoBytock', 0, 'tabla',
                {
                    fecha_desde: fercha_desde,
                    fecha_hasta: fercha_hasta,
                    reporte: true,
                    tipo: tipo,
                }, false, false, false, false, [], true, false, '300px'
            );
        }



    }




    $(function () {


        $(".reportbutton").on('click', function (e) {

            e.preventDefault();

            var fercha_desde = $("#fecha_desde").val();
            var fercha_hasta = $("#fecha_hasta").val();
            var usuario = $("#usuario").val();
            var type = $(this).attr('data-type');
            var form_pedido = $("#form_pedido").serialize();

            $(this).attr('data-href',  baseurl + 'reportesExcel/geProductosParaPedidoSugerido?fecha_desde='
                + fercha_desde + '&fecha_hasta=' + fercha_hasta+'&type='+type+'&'+form_pedido)

            window.location.href=$(this).attr('data-href');
        });

        $(".campos").on("change", function () {
            get_data_pedido_sugerido();
            get_data_pedido_sugerido();
        });

        get_data_pedido_sugerido();

    });

</script>
