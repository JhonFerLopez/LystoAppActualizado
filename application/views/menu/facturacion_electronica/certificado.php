<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica - registrar certificado</h4></div>
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


                        <!--

 php artisan api:config
php artisan key:gen
                        -->
                        <div class="row">
                            <div class="col-md-3"><label>Certificado:</label></div>
                            <div class="col-md-6"><input type="file" name="FACT_E_CERTIFICADO"/></div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Clave:</label></div>
                            <div class="col-md-6"><input name="FACT_E_CERTIFICADO_CLAVE" id="FACT_E_CERTIFICADO_CLAVE"
                                                         value="<?= !empty($this->session->userdata('FACT_E_CERTIFICADO_CLAVE')) ? $this->session->userdata('FACT_E_CERTIFICADO_CLAVE')
                                                             : '' ?>" class="form-control"></div>

                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-success" onclick="FacturacionElectronica.registrarCertificado();"
                                        type="button">REGISTRAR CERTIFICADO
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