<?php $ruta = base_url(); ?>
<!-- Load and execute javascript code used only in this page -->


<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Devolver Ventas</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active">Ventas</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- END Datatables Header -->
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <div class="row">


                <div class="col-md-12">

                    <!--<div class="col-md-1">
                        Desde
                    </div>-->
                    <input type="hidden" name="fecha_desde" onchange="get_ventas()" id="fecha_desde"
                           value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                  <!--  <div class="col-md-1">
                        Hasta
                    </div>-->
                    <input type="hidden" name="fecha_hasta" onchange="get_ventas()" id="fecha_hasta"
                           value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">

                    <div class="col-md-1">
                        Vendededor
                    </div>

                    <div class="col-md-2">
                        <select name="vendedor" onchange="get_ventas()" id="vendedor" class="form-control">
                            <option value="">Seleccione</option>
                            <?php

                            foreach ($vendedores as $vendedor) {
                                ?>
                                <option value="<?= $vendedor['nUsuCodigo'] ?>"><?= $vendedor['nombre'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                </div>
            </div>
            <div class="box-content box-nomargin">

                <div class="tab-content">
                    <div class="table-responsive">
                        <table class='table table-striped dataTable table-bordered' id="tabla">
                            <thead>
                            <tr>

                                <th>ID</th>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total <?php echo MONEDA ?></th>
                                <th>Estatus</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="ventamodal" style=" overflow: auto;
  margin: auto;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fa fa-close"></i></button>

                <h3>Nota debito</h3>
            </div>
            <div class="modal-body" id="ventamodalbody">

            </div>

        </div>

    </div>

</div>


<script>

    function get_ventas() {


        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var vendedor = $("#vendedor").val();
        var venta_status = '<?= COMPLETADO?>';


        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Venta/ventas_devolver', 0, 'tabla', {
            fecha_desde: fercha_desde,
            fecha_hasta: fercha_hasta,
            vendedor: vendedor,
            venta_status: venta_status,
            uuid: true,
            notadebito: true,
        }, 'Reporte de participacion en ventas');


    }


    $(function () {

        TablesDatatables.init();


        $("#fecha").datepicker({format: 'dd-mm-yyyy'});

        get_ventas();

    });


</script>