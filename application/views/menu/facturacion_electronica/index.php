<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica - resgistrar empresa</h4>
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

        <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="white-box">


                    <form id="form_empresa">
                        <div class="row">
                            <div class="col-md-3"><label>Habilitar facturación electrónica(Si está chequeado el cliente
                                    podrá emitir facturación electrónica):</label></div>
                            <div class="col-md-6"><input name="FACT_E_ALLOW" id="FACT_E_ALLOW" type="checkbox" value="1" <?= !empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1') ? 'checked' : '' ?>></div>

                        </div>

                        <div class="row">
                            <div class="col-md-3"><label>Api destino:</label></div>
                            <div class="col-md-6">Soenac<input name="FACT_E_API_DESTINO" id="FACT_E_API_DESTINO" value="SOENAC" type="radio" value="1" <?= !empty($this->session->userdata('FACT_E_API_DESTINO') and $this->session->userdata('FACT_E_API_DESTINO') === 'SOENAC') ? 'checked' : '' ?>></div>
                            <div class="col-md-6">Facturalaram<input name="FACT_E_API_DESTINO" id="FACT_E_API_DESTINO" value="FACTURALATAM" type="radio" value="1" <?= !empty($this->session->userdata('FACT_E_API_DESTINO') and $this->session->userdata('FACT_E_API_DESTINO') === 'FACTURALATAM') ? 'checked' : '' ?>></div>

                        </div>
                        <!--

 php artisan api:config
php artisan key:gen
                        -->
                        <div class="row">
                            <div class="col-md-3"><label>Token de enviroment( API_KEY ):</label></div>
                            <div class="col-md-6"><input name="FACT_E_env_token" id="FACT_E_env_token" value="<?= !empty($this->session->userdata('FACT_E_env_token')) ? $this->session->userdata('FACT_E_env_token')
                                                                                                                    : API_ENV_TOKEN ?>" class="form-control"></div>

                        </div>

                        <div class="row">
                            <div class="col-md-3"><label>Token de autenticacion(token api):</label></div>
                            <div class="col-md-6"><input name="FACT_E_API_TOKEN" id="FACT_E_API_TOKEN" value="<?= !empty($this->session->userdata('FACT_E_API_TOKEN')) ? $this->session->userdata('FACT_E_API_TOKEN')
                                                                                                                    : '' ?>" class="form-control"></div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Nit (Sin el dígito de verificación):</label></div>
                            <div class="col-md-6"><input name="FACT_E_NIT" id="FACT_E_NIT" value="<?= !empty($this->session->userdata('FACT_E_NIT')) ? $this->session->userdata('FACT_E_NIT')
                                                                                                        : '' ?>" class="form-control"></div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Dígito de verificación:</label></div>
                            <div class="col-md-6"><input name="FACT_E_DV" id="FACT_E_DV" value="<?= !empty($this->session->userdata('FACT_E_DV')) ? $this->session->userdata('FACT_E_DV')
                                                                                                    : '' ?>" class="form-control"></div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Tipo de documento:</label></div>
                            <div class="col-md-6">
                                <select name="FACT_E_type_document_identification_id" id="FACT_E_type_document_identification_id" class="form-control select2">
                                    <?php

                                    foreach ($listing['TypeDocumentIdentification'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_type_document_identification_id')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Tipo de persona:</label></div>
                            <div class="col-md-6">


                                <select name="FACT_E_type_organization_id" id="FACT_E_type_organization_id" class="form-control select2">
                                    <?php

                                    foreach ($listing['TypeOrganization'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_type_organization_id')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Tax detail:</label></div>
                            <div class="col-md-6">


                                <select name="FACT_E_TaxDetail" id="FACT_E_TaxDetail" class="form-control select2">
                                    <?php

                                    foreach ($listing['TaxDetail'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_TaxDetail')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3"><label>Tipo de regimen:</label></div>
                            <div class="col-md-6">

                                <select name="FACT_E_type_regime_id" id="FACT_E_type_regime_id" class="form-control select2">
                                    <?php

                                    foreach ($listing['TypeRegime'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_type_regime_id')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Tipo de responsabilidad:</label></div>
                            <div class="col-md-6">

                                <select name="FACT_E_type_liability_id" id="FACT_E_type_liability_id" class="form-control select2">
                                    <?php

                                    foreach ($listing['TypeLiability'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_type_liability_id')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Razón social:</label></div>
                            <div class="col-md-6"><input name="FACT_E_business_name" value="<?= !empty($this->session->userdata('FACT_E_business_name')) ? $this->session->userdata('FACT_E_business_name')
                                                                                                : '' ?>" id="FACT_E_business_name" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Registro mercantil:</label></div>
                            <div class="col-md-6"><input name="FACT_E_merchant_registration" value="<?= !empty($this->session->userdata('FACT_E_merchant_registration')) ? $this->session->userdata('FACT_E_merchant_registration')
                                                                                                        : '' ?>" id="FACT_E_merchant_registration" class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3"><label>Municicipio:</label></div>
                            <div class="col-md-6">

                                <select name="FACT_E_municipality_id" id="FACT_E_municipality_id" class="form-control select2">
                                    <?php

                                    foreach ($listing['Municipality'] as $value) : ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_municipality_id')) echo 'selected' ?>>
                                            <?= $value->name ?></option>
                                    <?php

                                    endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Dirección:</label></div>
                            <div class="col-md-6"><input name="FACT_E_address" value="<?= !empty($this->session->userdata('FACT_E_address')) ? $this->session->userdata('FACT_E_address')
                                                                                            : '' ?>" id="FACT_E_address" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Teléfono:</label></div>
                            <div class="col-md-6"><input name="FACT_E_phone" value="<?= !empty($this->session->userdata('FACT_E_phone')) ? $this->session->userdata('FACT_E_phone')
                                                                                        : '' ?>" id="FACT_E_phone" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Email:</label></div>
                            <div class="col-md-6"><input name="FACT_E_email" value="<?= !empty($this->session->userdata('FACT_E_email')) ? $this->session->userdata('FACT_E_email')
                                                                                        : '' ?>" id="FACT_E_email" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Test Set ID:</label>
                                <!-- <img src="<?= base_url('recursos/img/testset_id.png') ?>">--->
                            </div>
                            <div class="col-md-6"><input name="FACT_E_test_set_id" value="<?= !empty($this->session->userdata('FACT_E_test_set_id')) ? $this->session->userdata('FACT_E_test_set_id')
                                                                                                : '' ?>" id="FACT_E_test_set_id" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Comenzar a facturar en el numero Colocar en 1 para comenzar en el inicio de la resolucion):</label></div>
                            <div class="col-md-6"><input name="FACT_E_resolucion_start_in" value="<?= !empty($this->session->userdata('FACT_E_resolucion_start_in')) ? $this->session->userdata('FACT_E_resolucion_start_in')
                                                                                                        : '' ?>" id="FACT_E_resolucion_start_in" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Comenzar las facturas de contingecia facturador(03) en el numero:</label></div>
                            <div class="col-md-6"><input name="FACT_E_CONT_FACTURADOR_resolucion_start_in" value="<?= !empty($this->session->userdata('FACT_E_CONT_FACTURADOR_resolucion_start_in')) ? $this->session->userdata('FACT_E_CONT_FACTURADOR_resolucion_start_in')
                                                                                                                        : '' ?>" id="FACT_E_CONT_FACTURADOR_resolucion_start_in" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Comenzar las facturas de contingecia dian(04) en el numero:</label></div>
                            <div class="col-md-6"><input name="FACT_E_CONT_DIAN_resolucion_start_in" value="<?= !empty($this->session->userdata('FACT_E_CONT_DIAN_resolucion_start_in')) ? $this->session->userdata('FACT_E_CONT_DIAN_resolucion_start_in')
                                                                                                                : '' ?>" id="FACT_E_CONT_DIAN_resolucion_start_in" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Comenzar nota credito en el numero:</label></div>
                            <div class="col-md-6"><input name="FACT_E_resolucion_start_credit_note_in" value="<?= !empty($this->session->userdata('FACT_E_resolucion_start_credit_note_in')) ? $this->session->userdata('FACT_E_resolucion_start_credit_note_in')
                                                                                                                    : '' ?>" id="FACT_E_resolucion_start_credit_note_in" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3"><label>Comenzar nota debito en el numero:</label></div>
                            <div class="col-md-6"><input name="FACT_E_resolucion_start_debit_note_in" value="<?= !empty($this->session->userdata('FACT_E_resolucion_start_debit_note_in')) ? $this->session->userdata('FACT_E_resolucion_start_debit_note_in')
                                                                                                                    : '' ?>" id="FACT_E_resolucion_start_debit_note_in" class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3"><label>Modo hablitacion:</label></div>
                            <div class="col-md-6">
                                <input type="checkbox" name="FACT_E_habilitacionn" <?= !empty($this->session->userdata('FACT_E_habilitacionn') && $this->session->userdata('FACT_E_habilitacionn') == '1') ? 'checked'
                                                                                        : '' ?> id="FACT_E_habilitacionn" class="">
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-3"><label>Syncrono (Si el modo sincrono está habilitado , los documentos no serán validos para la dian en habilitacion, es decir, solo seran de prueba. 
                            Si está deshabilitado los documentos neviados serán validados por la dian en habilitacion. Se debe activar cuando se pase a produccion):</label></div>
                            <div class="col-md-6">
                                <input type="checkbox" name="FACT_E_syncrono" <?= !empty($this->session->userdata('FACT_E_syncrono') && $this->session->userdata('FACT_E_syncrono') == '1') ? 'checked'
                                                                                    : '' ?> id="FACT_E_syncrono" class="">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-success" onclick="FacturacionElectronica.registrarEmpresa();" type="button">REGISTRAR EMPRESA
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
    $(document).ready(function() {

        $(".select2").select2();
        //$('#SYS_EXP_DAT').datepicker({todayHighlight: true});
    })
</script>