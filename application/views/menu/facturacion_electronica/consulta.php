<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Facturación eletrónica - consultar documentos</h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>

</div>

<input type="hidden" id="FACT_E_API_TOKEN" value="<?= $this->session->userdata('FACT_E_API_TOKEN') ?>">
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="white-box">

            <select id="document_type">

                <?php foreach ($document_types as $type) {
                ?>
                    <option value="<?= $type[0] ?>"><?= $type[1] ?></option>
                <?php

                } ?>
            </select>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <table class="table table-responsive table-striped" id="tabla">
                <thead>
                    <tr>
                        <th>NUMERO</th>
                        <th>TIPO</th>
                        <th>CLIENTE</th>
                        <th>FECHA EMISION</th>
                        <th>ESTADO</th>
                        <th>ESTADO RECEPCION</th>

                        <th>#</th>
                    </tr>
                </thead>
                <tbody>


                </tbody>

            </table>
        </div>
    </div>
</div>



<div class="modal bs-example-modal-lg" id="modalNewNotificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close " data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Enviar nueva Notificación</h4>
            </div>

            <div class="modal-body" id="">
                <div class="row">
                    <form name="formagregar" action="#" method="post">
                        <div class="form-group">
                            <div class="col-md-2">
                                Aplicación
                            </div>
                            <div class="col-md-10">
                                <select class="form-control" id="topicnewnotif">
                                    <option value="/topics/appcustomer" selected>Aplicación Clientes</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                Título
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="nombre" id="titulonewnotif" class="form-control" value="">
                            </div>

                            <div class="col-md-2">
                                Mensaje
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control" name="textareanewnotif" id="textareanewnotif"></textarea>
                            </div>

                        </div>

                    </form>
                </div>
            </div>




        </div>
    </div>
</div>

<div class="modal fade" id="modal_fe_send_mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalconfirmsendnotif').modal('hide')" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Destinatario</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="fe_send_mail_destinatario" value="" class="form-control">
                <input type="hidden" id="fe_send_mail_uuid" value="" class="form-control">

            </div>

            <div class="modal-footer">

                <div class="text-left col-md-2" id="">
                    <a href="#" class="btn btn-primary" style="text-align: left" onclick="confirmSendFEEmail()">Confirmar</a></div>

                <div class="text-right" id="">
                    <button type="button" class="btn btn-default" onclick="$('#modal_fe_send_mail').modal('hide')">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<script>
    $(document).ready(function() {

        $(".select2").select2();
        $('.date').datepicker({
            todayHighlight: true
        });


    })

    function logs() {
        $.ajax({
            url: baseurl + 'api/FacturacionElectronica/logs',
            type: 'post',
            data: {

                trackid: '4fa2f89ee3d1710dbed1a1fd97fed16c08684783f5c4daeb5598cbd6d966be05aa8361fc33c1fc27f4f74164d88b74ca',

            },
            dataType: 'json',
            success: function(data) {

                console.log(data);


            }

        })
    }

    function search() {
        TablesDatatablesLazzy.init('<?php echo base_url() ?>api/FacturacionElectronica/documents', 0, 'tabla', {
            document_type: $("#document_type").val(),
            FACT_E_API_TOKEN: $("#FACT_E_API_TOKEN").val()
        });
    }

    function confirmSendFEEmail() {
        let email = $("#fe_send_mail_destinatario").val();
        let uuid = $("#fe_send_mail_uuid").val();
        if (email == '') {
            Utilities.alertModal('Debe ingresar el destinatario', 'error');
            return;
        }
        $.ajax({
            url: baseurl + 'FacturacionElectronica/sendmail',
            type: 'post',
            data: {
                email: email,
                uuid: uuid,
            },
            dataType: 'json',
            success: function(data) {
                console.log("---> ");
                console.log(data);
                console.log(" <--- ");
                if (data.mail_sending_message == '' || 
                    data.mail_sending_message == null || 
                    data.mail_sending_message == undefined || 
                    data.mail_sending_message == 'null' ) 
                    {

                    Utilities.alertModal('Se enviado a representacion gráfica', 'success');
                    $('#modal_fe_send_mail').modal('hide')
                    $("#fe_send_mail_destinatario").val('');
                    $("#fe_send_mail_uuid").val('');

                } else {
                    Utilities.alertModal(data.error, 'error');
                }
            }
        })
    }

    function sendMail(uuid, email) {
        $("#fe_send_mail_destinatario").val(email);
        $("#fe_send_mail_uuid").val(uuid);
        $('#modal_fe_send_mail').modal('show')

    }
    $(function() {
        $("#document_type").on('change', function() {
            search();
        });
        search();
    });
</script>