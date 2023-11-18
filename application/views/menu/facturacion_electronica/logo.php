<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica - logo</h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>

</div>


<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="white-box">

            <form id="form_empresa">


                <!--

php artisan api:config
php artisan key:gen
                -->
                <div class="row">
                    <div class="col-md-3"><label>Logo:</label></div>
                    <div class="col-md-6">
                        <input id="b64" type="hidden" name="b64" value="<?= !empty($this->session->userdata('FACT_E_LOGO')) ? $this->session->userdata('FACT_E_LOGO')
                                                                                            : '' ?>">
                        <img id="img" height="150">
                        <input type="file" name="FACT_E_LOGO" id="FACT_E_LOGO" value="" class="form-control">
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-success" onclick="FacturacionElectronica.registrarLogo();" type="button">REGISTRAR LOGO
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>


<script>
    function readFile() {

        if (this.files && this.files[0]) {

            var FR = new FileReader();

            FR.addEventListener("load", function(e) {
                document.getElementById("img").src = e.target.result;
                document.getElementById("b64").value = e.target.result;
            });


            FR.readAsDataURL(this.files[0]);
        }

    }

    document.getElementById("FACT_E_LOGO").addEventListener("change", readFile);
</script>