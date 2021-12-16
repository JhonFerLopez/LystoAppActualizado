<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="EMPRESA_NOMBRE" value="<?= $this->session->userdata('EMPRESA_NOMBRE'); ?>">
<input type="hidden" id="REGIMEN_CONTRIBUTIVO" value="<?= $this->session->userdata('REGIMEN_CONTRIBUTIVO'); ?>">
<input type="hidden" id="EMPRESA_DIRECCION" value="<?= $this->session->userdata('EMPRESA_DIRECCION'); ?>">
<input type="hidden" id="EMPRESA_TELEFONO" value="<?= $this->session->userdata('EMPRESA_TELEFONO'); ?>">
<input type="hidden" id="NIT" value="<?= $this->session->userdata('NIT'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Historial cierre de caja</h4></div>
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

               <!-- <div class="col-md-3">
                    <select id="locales" class="form-control" onchange="StatusCaja.changeSearch()">
                        <?php if (isset($cajas)) {
                            foreach ($cajas as $caja) {
                                ?>
                                <option value="<?= $caja['caja_id']; ?>"> <?= $caja['alias'] ?> </option>
                            <?php }
                        } ?>

                    </select>
                </div>-->



            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">
                            <thead>
                            <tr>


                                <th>ID</th>
                                <th>CAJA</th>
                                <th>CAJERO</th>
                                <th>APERTURA</th>
                                <th>CIERRE</th>
                                <th>MONTO CIERRE</th>
                                <th class="desktop">Acciones</th>

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

<script>$(function () {
        TablesDatatablesLazzy.init('<?php echo base_url()?>api/statusCaja', 0, 'tabla',{test:1});


    });
</script>
