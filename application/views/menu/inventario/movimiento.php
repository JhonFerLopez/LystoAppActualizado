<?php $ruta = base_url(); ?>


<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success alert-dismissable"
             style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-remove"></i>
            </button>
            <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
            <?php echo isset($success) ? $success : '' ?>
        </div>
    </div>
</div>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Movimiento inventario</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>
            <li class="active">Productos</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">

            <div class="row">
                <div class="form-group">
                    <div class="col-md-1">
                        <label>Ubicaci&oacute;n Inventario</label>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control" id="locales" onchange="getproductosbylocal()">

                            <?php foreach ($locales as $local) { ?>
                                <option value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>

                            <?php } ?>

                        </select>
                    </div>
                </div>
            </div>
            <br>

            <div class="table-responsive">

                <table class='table table-striped dataTable table-bordered' id="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>

                        <?php foreach ($unidades as $unidad) {
                            ?>
                            <th><?= $unidad['nombre_unidad'] ?></th>
                            <?php

                        } ?>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>


<div class="modal fade" id="ver" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>


<script type="text/javascript">


    function KARDEXINTERNO(id) {

        Utilities.showPreloader();
        var local = $("#locales").val();

        $.ajax({
            url:'<?= $ruta ?>inventario/kardex/' + id + '/' + local,
            success:function (data) {
                $("#ver").html(data);
                Utilities.hiddePreloader();
                $('#ver').modal('show');
            },
            error:function () {
                Utilities.hiddePreloader();
            }
        })

    }

    function KARDEXEXTERNO(ELID) {

        var documento_fiscal = true;
        var local = $("#locales").val();
        $("#ver").load('<?= $ruta ?>inventario/kardex/' + ELID + '/' + local + '/' + documento_fiscal);
        $('#ver').modal('show');
    }


    function getproductosbylocal() {

        TablesDatatablesLazzy.init('<?php echo base_url()?>inventario/getbyJson', 0, "table", {local: $('#locales').val()});


    }

</script>

<!-- Load and execute javascript code used only in this page -->

<script>$(function () {
        getproductosbylocal();

    });</script>

