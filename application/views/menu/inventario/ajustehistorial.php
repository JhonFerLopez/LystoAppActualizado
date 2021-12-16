<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Registro de físicos</h4></div>
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
            <div class="row">

                <div class="col-md-3">
                    <select id="locales" class="form-control">
                        <?php if (isset($locales)) {
                            foreach ($locales as $local) {
                                ?>
                                <option value="<?= $local['int_local_id']; ?>"> <?= $local['local_nombre'] ?> </option>
                            <?php }
                        } ?>

                    </select>
                </div>




            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="tabla">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>$(function () {

        var unidades = new Array();
        AjusteInventario.init(unidades, null, null,null, null, true, false);

    });
</script>
