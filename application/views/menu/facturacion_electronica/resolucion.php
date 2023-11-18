<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica - resoluciones</h4>
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


            <h2>Configurar resoluciones</h2>
            <form id="form_config">




                <div class="row">
                    <div class="col-md-3"><label>Factura electrónica:</label></div>
                    <div class="col-md-6">
                        <select name="FACT_E_RESOLUCION_resolucion_FE" id="FACT_E_RESOLUCION_resolucion_FE" class="form-control select2">
                           
                            <?php

                            if (sizeof($resoluciones) > 0) {
                                foreach ($resoluciones as  $value) :
                                    $value = (object) $value;
                                    if ($value->type_document_id == '1') :
                            ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_resolucion_FE')) echo 'selected' ?>>
                                            Resolucion N° <?= $value->resolution ?> Prefijo: <?= $value->prefix ?> Del <?= $value->from ?> al <?= $value->to ?></option>
                            <?php
                                    endif;

                                endforeach;
                            } ?>
                            <option>Selecciona</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Nota crédito electrónica:</label></div>
                    <div class="col-md-6">
                        <select name="FACT_E_RESOLUCION_resolucion_NC" id="FACT_E_RESOLUCION_resolucion_NC" class="form-control select2">
                            <?php

                            if (sizeof($resoluciones) > 0) {
                                foreach ($resoluciones as  $value) :
                                    $value = (object) $value;
                                    if ($value->type_document_id == '5') :
                            ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_resolucion_NC')) echo 'selected' ?>>
                                            Resolucion N° <?= $value->resolution ?> Prefijo: <?= $value->prefix ?> Del <?= $value->from ?> al <?= $value->to ?></option>
                            <?php
                                    endif;


                                endforeach;
                            } ?>
                            <option>Selecciona</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3"><label>Nota débito electrónica:</label></div>
                    <div class="col-md-6">
                        <select name="FACT_E_RESOLUCION_resolucion_ND" id="FACT_E_RESOLUCION_resolucion_ND" class="form-control select2">
                            <?php
                            if (sizeof($resoluciones) > 0) {
                                foreach ($resoluciones as  $value) :
                                    $value = (object) $value;
                                    if ($value->type_document_id == '6') :
                            ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_resolucion_ND')) echo 'selected' ?>>
                                            Resolucion N° <?= $value->resolution ?> Prefijo: <?= $value->prefix ?> Del <?= $value->from ?> al <?= $value->to ?></option>
                            <?php
                                    endif;

                                endforeach;
                            } ?>
                            <option>Selecciona</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3"><label>Factura electrónica de contingencia FACTURADOR:</label></div>
                    <div class="col-md-6">
                        <select name="FACT_E_RESOLUCION_resolucion_FCF" id="FACT_E_RESOLUCION_resolucion_FCF" class="form-control select2">
                            <?php
                            if (sizeof($resoluciones) > 0) {
                                foreach ($resoluciones as  $value) :
                                    $value = (object) $value;
                                    if ($value->type_document_id == '3') :
                            ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_resolucion_FCF')) echo 'selected' ?>>
                                            Resolucion N° <?= $value->resolution ?> Prefijo: <?= $value->prefix ?> Del <?= $value->from ?> al <?= $value->to ?></option>
                            <?php
                                    endif;

                                endforeach;
                            } ?>
                            <option>Selecciona</option>
                        </select>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-3"><label>Factura electrónica de contingencia DIAN:</label></div>
                    <div class="col-md-6">
                        <select name="FACT_E_RESOLUCION_resolucion_FCD" id="FACT_E_RESOLUCION_resolucion_FCD" class="form-control select2">
                            <?php
                            if (sizeof($resoluciones) > 0) {
                                foreach ($resoluciones as  $value) :
                                    $value = (object) $value;
                                    if ($value->type_document_id == '4') :
                            ?>


                                        <option value="<?= $value->id ?>" <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_resolucion_FCD')) echo 'selected' ?>>
                                            Resolucion N° <?= $value->resolution ?> Prefijo: <?= $value->prefix ?> Del <?= $value->from ?> al <?= $value->to ?></option>
                            <?php
                                    endif;

                                endforeach;
                            } ?>
                            <option>Selecciona</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-success" onclick="FacturacionElectronica.registrarConfiguracionResolucion();" type="button">GUARDAR CONFIGURACION
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="white-box">

            <h2>Consultar rangos de numeración</h2>
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-success" onclick="FacturacionElectronica.consultarRangos();" type="button">CONSULTAR RANGOS DE NUMERACION
                    </button>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" id="fe_rangos_result">

                </div>
            </div>


        </div>

        <div class="white-box">
            <h2>Crear o actualizar resoluciones</h2>
            <form id="form_empresa">


                <!--

php artisan api:config
php artisan key:gen
                -->
                <div class="row">
                    <div class="col-md-3"><label>Id de resolucion:</label></div>
                    <div class="col-md-6"><input readonly name="FACT_E_RESOLUCION_resolution_id" id="FACT_E_RESOLUCION_resolution_id" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_resolution_id')) ? $this->session->userdata('FACT_E_RESOLUCION_resolution_id')
                                                                                                                                                    : '' ?>" class="form-control"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Numero de resolucion:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_resolution" id="FACT_E_RESOLUCION_resolution" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_resolution')) ? $this->session->userdata('FACT_E_RESOLUCION_resolution')
                                                                                                                                    : '' ?>" class="form-control"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Prefijo:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_prefix" id="FACT_E_RESOLUCION_prefix" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_prefix')) ? $this->session->userdata('FACT_E_RESOLUCION_prefix')
                                                                                                                            : '' ?>" class="form-control"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Llave técnica:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_technical_key" id="FACT_E_RESOLUCION_technical_key" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_technical_key')) ? $this->session->userdata('FACT_E_RESOLUCION_technical_key')
                                                                                                                                        : '' ?>" class="form-control"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Fecha:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_resolution_date" id="FACT_E_RESOLUCION_resolution_date" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_resolution_date')) ? $this->session->userdata('FACT_E_RESOLUCION_resolution_date')
                                                                                                                                            : '' ?>" class="form-control date"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Fecha desde:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_date_from" id="FACT_E_RESOLUCION_date_from" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_date_from')) ? $this->session->userdata('FACT_E_RESOLUCION_date_from')
                                                                                                                                : '' ?>" class="form-control date"></div>

                </div>
                <div class="row">
                    <div class="col-md-3"><label>Fecha hasta:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_date_to" id="FACT_E_RESOLUCION_date_to" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_date_to')) ? $this->session->userdata('FACT_E_RESOLUCION_date_to')
                                                                                                                            : '' ?>" class="form-control date"></div>

                </div>

                <div class="row">
                    <div class="col-md-3"><label>Desde:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_from" id="FACT_E_RESOLUCION_from" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_from')) ? $this->session->userdata('FACT_E_RESOLUCION_from')
                                                                                                                        : '' ?>" class="form-control"></div>

                </div>

                <div class="row">
                    <div class="col-md-3"><label>Hasta:</label></div>
                    <div class="col-md-6"><input name="FACT_E_RESOLUCION_to" id="FACT_E_RESOLUCION_to" value="<?= !empty($this->session->userdata('FACT_E_RESOLUCION_to')) ? $this->session->userdata('FACT_E_RESOLUCION_to')
                                                                                                                    : '' ?>" class="form-control"></div>

                </div>

                <div class="row">
                    <div class="col-md-3"><label>Tipo de documento:</label></div>
                    <div class="col-md-6">
                        <?php 
                            /*echo "<pre>"; 
                            print_r($listing2);
                            echo "<hr>";
                            print_r($listing['TypeDocument']);
                            echo "</pre>";*/
                        ?>
                        <select name="FACT_E_RESOLUCION_type_document_id" id="FACT_E_RESOLUCION_type_document_id" class="form-control select2">
                            <?php
                            
                            foreach ($listing['TypeDocument'] as  $value) : ?>
                                <option value="<?= $value->id ?>" 
                                    <?php if ($value->id == $this->session->userdata('FACT_E_RESOLUCION_type_document_id')) echo 'selected' ?>
                                >
                                    <?= $value->id ?> - <?= $value->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!--   <div class="row">
                    <div class="col-md-3"><label>Actualizar (se actualizaran las resoluciones del tipo de documento seleccionado):</label></div>
                    <div class="col-md-6">
                        <input type="checkbox" name="FACT_E_resolucion_actualizar" <?= !empty($this->session->userdata('FACT_E_resolucion_actualizar') && $this->session->userdata('FACT_E_resolucion_actualizar') == '1') ? 'checked'
                                                                                        : '' ?> id="FACT_E_resolucion_actualizar" class="">
                    </div>

                </div>-->
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-success" onclick="FacturacionElectronica.registrarResolucion();" type="button">REGISTRAR RESOLUCIÓN
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-default" onclick="FacturacionElectronica.emptyResolutionform();" type="button">LIMPIAR
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-responsive table-striped">
            <tr>
                <th>PREFIX</th>
                <th>RESOLUTION</th>
                <th>FROM</th>
                <th>TO</th>
                <th>DATE FROM</th>
                <th>DATE TO</th>
                <th>TECHNICAL KEY</th>
                <th>#</th>

            </tr>

            <?php
            if (sizeof($resoluciones) > 0) {
                foreach ($resoluciones as $resolucion) {

                    $resolucion_array = $resolucion;
                    $resolucion = (object) $resolucion;

            ?>
                    <tr>
                        <td><?= $resolucion->prefix ?></td>
                        <td><?= $resolucion->resolution ?></td>
                        <td><?= $resolucion->from ?></td>
                        <td><?= $resolucion->to ?></td>
                        <td><?= $resolucion->date_from ?></td>
                        <td><?= $resolucion->date_to ?></td>
                        <td><?= $resolucion->technical_key ?></td>
                        <td><a href="#" class="btn btn-danger" onclick="FacturacionElectronica.confirmDeleteResolution(<?= $resolucion->id ?>)"><i class="fa fa-trash"></i></a>
                            <a href="#" class="btn btn-success" onclick="FacturacionElectronica.formEditResolution('<?= $resolucion->prefix ?>', 
                       '<?= $resolucion->resolution ?>', '<?= $resolucion->from ?>',  '<?= $resolucion->to ?>',
                      '<?= $resolucion->date_from ?>', '<?= $resolucion->date_to ?>', '<?= $resolucion->technical_key ?>', 
                      '<?= $resolucion->id ?>', '<?= $resolucion->resolution_date ?>' ,'<?= $resolucion->type_document_id ?>' )"><i class="fa fa-edit"></i></a> </td>
                    </tr>

            <?php
                }
            } ?>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        // $(".select2").select2();
        $('.date').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
    })
</script>