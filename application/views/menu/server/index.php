<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Status del servidor</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>

</div>


<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">


    </div>

</div>
<script>
    $(document).ready(function () {
        Server.init();
    })
</script>