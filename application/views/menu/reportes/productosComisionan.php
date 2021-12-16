<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Informe de Ventas por fecha</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>

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
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>% COMISION</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- /.row -->


<script type="text/javascript">
    $(function () {


        get_ventas();



    });

    function get_ventas() {


        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/Productos/productosComisionan', 0, 'tabla', {reporte: true}, 'Reporte productos que comisionan', false, false, false);


    }


</script>
