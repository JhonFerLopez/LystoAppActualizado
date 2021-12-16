<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Licenciamiento</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>

</div>


<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">

        <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="white-box">


                    <div class="row">
                        <div class="col-md-3"> <label>Fecha de inicio:</label></div>
                        <div class="col-md-9"> <?= date('d-m-Y', strtotime($this->session->userdata('FECHA_INICIO'))); ?></div>
                    </div>



                    <?php

                    $today = date('d-m-Y', time());
                    $exp = date('d-m-Y', strtotime($this->session->userdata('SYS_EXP_DAT'))); //query result form database
                    $expDate = date_create($exp);
                    $todayDate = date_create($today);
                    $diff = date_diff($todayDate, $expDate);
                    $remaingin = $diff->format("%R%a days");
                    $status='ACTIVO';
                    if ($diff->format("%R%a") > 0) {
                        $remaingin = $diff->format("%R%a days");

                    }else{
                        $status='VENCIDO';
                    }
                    ?>

                    <div class="row">
                        <div class="col-md-3">  <label>Estado de la licencia:</label> </div>
                        <div class="col-md-9">  <?= $status ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"> <label>Dias de corte:</label> </div>
                        <div class="col-md-9">  <?= date('d', strtotime($this->session->userdata('SYS_EXP_DAT'))); ?> de cada mes</div>
                    </div>


                    <div class="row">
                        <div class="col-md-3"> <label>Fecha de próximo vencimiento:</label> </div>
                        <div class="col-md-9">  <?= date('d-m-Y', strtotime($this->session->userdata('SYS_EXP_DAT'))); ?></div>
                    </div>


                    <div class="row">
                        <div class="col-md-3">  <label>Renovar licencia hasta la fecha:</label> </div>
                        <div class="col-md-6">   <input name="SYS_EXP_DAT" id="SYS_EXP_DAT" class="form-control fecha campos input-datepicker"> </div>
                        <div class="col-md-3">  <button class="btn btn-success" onclick="Server.renovarLiencia();" type="button">RENOVAR</button></div>
                    </div>


                </div>
            </div>

        </div>
    </div>

</div>
<script>
    $(document).ready(function () {

        $('#SYS_EXP_DAT').datepicker({todayHighlight: true});
    })
</script>