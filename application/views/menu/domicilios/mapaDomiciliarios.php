<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Domicilios</h4></div>



    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Control de Domicilios</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->



</div>

<div class="row" id="mapadomiciliarios" style="width: 100%; height: 100%; position: absolute">


</div>

<script type="text/javascript">
    $(function () {
        Venta.initMapaDomicilio();

        App.sidebar();
    });


</script>
