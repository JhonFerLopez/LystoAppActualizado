var VentaAnular = {

    tipos: new Array(),
    uuid: '',
    dom: {},
    cache: function (selector) {
        if (undefined === this.dom[selector])
            this.dom[selector] = $(selector);
        return this.dom[selector];
    },
    ajaxgrupo: function () {
        return $.ajax({
            url: baseurl + 'venta/cancelar'
        })
    },
    guardar: async function () {
        if ($("#motivo").val() == '') {
            var growlType = 'warning';
            $.bootstrapGrowl('<h4>Debe ingresar un motivo</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });

            $(this).prop('disabled', true);

            return false;
        }
        var id = $("#id").val();

        var modal = 'anular';
        var iselectronica = false;

        if (VentaAnular.uuid != '' && VentaAnular.uuid != undefined) {
            iselectronica = true;
        }

        if (iselectronica == true) {
            VentaAnular.processNotacredito(id);
        } else {
            VentaAnular.guardarAnulacion(id);
        }


    },

    guardarAnulacion() {
        var boton = 'anularbutton';
        $.ajax({
            url: $("#formeliminar").attr('action'),
            type: 'post',
            data: $("#formeliminar").serialize(),
            dataType: 'json',
            success: function (data) {

                var callback = this.ajaxgrupo;
                $("#" + boton).removeClass('disabled');
                if (data.error == undefined) {


                    if (data.success) {

                        Utilities.alertModal(data.success, 'success');
                        $('#anular').on('hidden.bs.modal', function () {

                            $("#success").css('display', 'block');
                        });

                        $("#anular").modal('hide');


                        var resultcal = VentaAnular.ajaxgrupo();


                        if (resultcal != undefined) {
                            resultcal.success(function (data2) {




                                $('#page-content').html(data2);

                                $("#successspan").html(data.success);

                                $("#success").css('display', 'block');
                                Utilities.hiddePreloader();



                            })
                        } else {


                            $("#successspan").html(data.success);
                            $("#success").css('display', 'block');
                            if (modal != null) {
                                $("#" + modal).modal('hide');
                            }

                            Utilities.hiddePreloader();
                        }


                    }
                    else {


                        Utilities.alertModal(data.error, 'error');


                        /*$("#errorspan").text(data.error);
                         $("#error").css('display','block');*/
                        Utilities.hiddePreloader();

                    }
                }

                setTimeout(function () {
                    //$(".alert-danger").css('display','none');
                    $(".alert-success").css('display', 'none');
                }, 3000)
            },
            error: function (response) {
                //
                $("#" + boton).removeClass('disabled');
                Utilities.hiddePreloader();


                Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'error');

            }

        });
    },
    async processNotacredito(id) {
        Utilities.showPreloader();
        var factured = await VentaAnular.hacerNotaCredito('devolver=false&idventa=' + id);

        if (factured.errors !== undefined || factured.error !== undefined) {
            Utilities.hiddePreloader();
            $("#modal_facturacion_electronica").modal();
            if (factured.errors != undefined) {
                jQuery.each(factured.errors, function (i, value) {
                    var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                    $("#fact_elect_errors").append(error_div);
                })
            }
            if (factured.error != undefined) {
                var error_div = "  <div class='alert alert-danger'>" + factured.error + "</div>";
                $("#fact_elect_errors").append(error_div);
            }

            $("#realizarventa").removeClass('disabled');
            $("#btnRealizarVentaAndView").removeClass('disabled');
            //TODO SHOW ERRORS
            return false;
        } else {

            var data = factured;
            var test = VentaAnular.FACT_E_habilitacionn == '1' ? true : false;
            var sync = VentaAnular.FACT_E_syncrono == '1' ? true : false;

            var ZipKey = '';
            var uuid = data.uuid;
            var number = data.number;
            var resolution_id = data.resolution_id;
            var issue_date = data.issue_date;
            var prefijo = data.fe_prefijo;


            console.log('prefijo', prefijo);

            //entor a verificar el zip para ver si la respuesta fue valida, en caso de que se haya enviado  asyncrono
            if (sync != true) {
                ZipKey = data.responseDian.Envelope.Body.SendTestSetAsyncResponse.SendTestSetAsyncResult.ZipKey;
                Venta.zipkey = ZipKey;


                $.ajax({
                    url: baseurl + 'FacturacionElectronica/statusZip',
                    type: 'post',
                    dataType: 'json',
                    data: { trackid: ZipKey },
                    success: function (datazip) {
                        Utilities.hiddePreloader();
                        Venta.XmlFileName = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.XmlFileName;
                        var is_valid = datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.IsValid;
                        if (is_valid === "false") {
                            Utilities.alertModal('No se ha podido enviar la nota de credito electrónica, intente nuevamente', true);
                            $("#fact_elect_errors").html('');
                            $("#modal_facturacion_electronica").modal();

                            if (Array.isArray(data.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.ErrorMessage.string)) {
                                jQuery.each(data.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.ErrorMessage.string, function (i, value) {
                                    var error_div = "  <div class='alert alert-danger'>" + value + "</div>";
                                    $("#fact_elect_errors").append(error_div);
                                });
                            } else {
                                var error_div = "  <div class='alert alert-danger'>" + data.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.DianResponse.StatusDescription + "</div>";
                                $("#fact_elect_errors").append(error_div);
                            }


                        } else {


                            Utilities.alertModal(datazip.responseDian.Envelope.Body.GetStatusZipResponse.GetStatusZipResult.StatusMessage, 'success');
                            Utilities.alertModal('Por favor espere mientras SID guarda la venta', 'success');
                            VentaAnular.postProcessNotacredito(uuid, number, resolution_id, issue_date, id, ZipKey, data, prefijo);
                        }
                    }
                    , error: function () {
                        Utilities.hiddePreloader();
                        $("#realizarventa").removeClass('disabled');
                        $("#btnRealizarVentaAndView").removeClass('disabled');

                        Utilities.alertModal('No se ha podido enviar la nota de credito electrónica, intente nuevamente', true);
                        $("#fact_elect_errors").html('');

                    }
                })


            } else {
                //sino, hago la verificacion desde el modo de pruebas


                var is_valid = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.IsValid;
                console.log('is_valid', is_valid);
                //estos son los errores de la dian
                if (is_valid === "false") {

                    console.log('es false');

                    Utilities.alertModal('No se ha podido enviar la factura electrónica, intente nuevamente', true);
                    $("#fact_elect_errors").html('');
                    $("#modal_facturacion_electronica").modal();
                    console.log(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string);
                    if (Array.isArray(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string)) {
                        jQuery.each(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string, function (i, value) {

                            var error_div = "  <div class='alert alert-danger'>" + value + "</div>";

                            $("#fact_elect_errors").append(error_div);
                        });
                    } else {
                        var error_div = "  <div class='alert alert-danger'>" + data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.ErrorMessage.string + "</div>";
                        $("#fact_elect_errors").append(error_div);
                    }

                    Utilities.hiddePreloader();
                } else {


                    $("#fact_elect_errors_print").html('');
                    Utilities.hideModal('generarventa');
                    console.log('es true');
                    // $("#modal_facturacion_electronica").modal('hide');
                    Venta.XmlFileName = data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.XmlFileName;

                    Utilities.alertModal(data.responseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusMessage, 'success');
                    Utilities.alertModal('Por favor espere mientras SID guarda la venta', 'success');
                    VentaAnular.postProcessNotacredito(uuid, number, resolution_id, issue_date, id, ZipKey, data, prefijo);
                }
            }


        }
    },
    postProcessNotacredito(uuid, number, resolution_id, issued_date, venta_id, ZipKey, fe_reponseDian, prefijo) {

        $.ajax({
            url: baseurl + 'facturacionElectronica/postProcesNotaCredito',
            type: 'post',
            dataType: 'JSON',
            data: {
                uuid: uuid,
                number: number,
                resolution_id: resolution_id,
                issued_date: issued_date,
                venta_id: venta_id,
                type: 'ANULACION',
                zipkey: ZipKey,
                reponseDian: fe_reponseDian,
                status: 'ENVIADO',
                prefijo: prefijo
            },
            success: function (response) {
                if (response.success == true) {
                    $("#modal_facturacion_electronica").modal('hide');
                    VentaAnular.guardarAnulacion(id);
                } else {
                    Utilities.alertModal('No se pudo almacenar la nota de credito en SID, por favor intente nuevamente', 'error')
                    VentaAnular.guardarAnulacion(id);
                }


            },
            error: function () {
                Utilities.alertModal('No se pudo almacenar la nota de credito en SID, por favor intente nuevamente', 'error')
                VentaAnular.guardarAnulacion(id);
            }
        })
    },
    hacerNotaCredito: function (data_venta) {

        return $.ajax({
            url: baseurl + 'FacturacionElectronica/notaCredito',
            type: 'post',
            data: data_venta,
            dataType: 'json',

        });

    },
    anular:

        function (id, uuid) {

            $('#anular').modal('show');
            $("#id").attr('value', id);
            VentaAnular.uuid = uuid;
        }

    ,
    changeTipo: function () {


    }
    ,
    events: function () {
        TablesDatatables.init();
        $("#fecha").datepicker({ format: 'dd-mm-yyyy' });
    }
    ,
    init: function (tipos) {
        this.events();
        this.tipos = tipos;
        this.FACT_E_habilitacionn = $("#FACT_E_habilitacionn").val();
        this.FACT_E_syncrono = $("#FACT_E_syncrono").val();
    }

}