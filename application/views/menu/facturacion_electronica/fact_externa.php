<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica externa</h4></div>
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

                    <form id="form_empresa">
                        <div class="row">
                            <div class="col-md-3"><label>Archivo XML:</label></div>
                            <div class="col-md-6"><input type="file" name="FACT_EXTERNA"/></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-success" onclick="FacturacionElectronica.registrarFactExterna();"
                                        type="button">REGISTRAR
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
<script>
    $(document).ready(function () {

        $(".select2").select2();
        //$('#SYS_EXP_DAT').datepicker({todayHighlight: true});
    })
</script>