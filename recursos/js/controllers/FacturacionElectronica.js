var FacturacionElectronica = {



    index: function () {
        $.ajax({
            url: baseurl + 'facturacionElectronica',
            success: function (data2) {
                $('#page-content').html(data2);
            }
        })
    },
    resolulciofe_type_item_identification_idn: function () {
        $.ajax({
            url: baseurl + 'facturacionElectronica/resolucion',
            success: function (data2) {
                $('#page-content').html(data2);
            }
        })
    },

    registrarEmpresa: function () {


        var data = $("#form_empresa").serialize();

        console.log('data', data);
        //primero salvo registro el sistema en el api
        var result = FactElectronicaServices.registrarEmpresa(data);

        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.errors === undefined) {

                var api_token = datareturn.token;
                console.log(api_token);
                if (api_token != undefined) {
                    data = data + '&FACT_E_API_TOKEN=' + api_token;
                    console.log(data);
                }
                // si retorna succes almaceno en local
                var result = FactElectronicaServices.saveOpciones(data);
                result.success(function (datareturn2) {

                    if (datareturn2.success != undefined) {

                        Utilities.alertModal(datareturn.message, 'success');
                        FacturacionElectronica.index();
                    } else {
                        Utilities.alertModal('Ha ocurrido un error', 'error', 6000);
                    }

                });
            } else {
                Utilities.alertModal(datareturn.result, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    }
    ,
    registrarResolucion: function () {


        var data = $("#form_empresa").serialize();

        console.log('data', data);
        //primero salvo registro el sistema en el api
        var result = FactElectronicaServices.registrarResolucion(data);

        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.errors === undefined) {

                Utilities.alertModal(datareturn.message, 'success');

            } else {
                Utilities.alertModal(datareturn.result, 'error', 6000);
            }
        });
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    registrarConfiguracionResolucion: function () {
        var data = $("#form_config").serialize();

        var result = FactElectronicaServices.saveOpciones(data);
        result.success(function (datareturn2) {

            if (datareturn2.success != undefined) {

                Utilities.alertModal(datareturn2.message, 'success');

            } else {
                Utilities.alertModal(datareturn2.error, 'error', 6000);
            }

        });
    },
    registrarSoftare: function () {


        var data = $("#form_empresa").serialize();

        console.log('data', data);
        //primero salvo registro el sistema en el api
        var result = FactElectronicaServices.registrarSoftare(data);

        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.errors != undefined) {
                jQuery.each(datareturn.errors, function (i, value) {
                    console.log('i', i)
                    console.log('value', value)
                    Utilities.alertModal(i[0], 'success');
                });

            } else if (datareturn.error != undefined) {
                Utilities.alertModal(datareturn.error, 'error', 6000);
            } else {
                // si retorna succes almaceno en local
                var result = FactElectronicaServices.saveOpciones(data);
                result.success(function (datareturn2) {

                    if (datareturn2.success != undefined) {

                        Utilities.alertModal(datareturn.message, 'success');
                        // FacturacionElectronica.index();
                    } else {
                        Utilities.alertModal(datareturn2.error, 'error', 6000);
                    }

                });
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    consultarRangos: function () {


        var data = $("#form_empresa").serialize();

        console.log('data', data);
        //primero salvo registro el sistema en el api
        var result = FactElectronicaServices.consultarRangos(data);

        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.errors != undefined) {
                jQuery.each(datareturn.errors, function (i, value) {
                    console.log('i', i)
                    console.log('value', value)
                    Utilities.alertModal(i[0], 'success');
                });

            } else if (datareturn.error != undefined) {
                Utilities.alertModal(datareturn.error, 'error', 6000);
            } else {
                // si retorna succes almaceno en local

            }

            $("#fe_rangos_result").text(JSON.stringify(datareturn));
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },
    formEditResolution: function(prefix, resolution, from, to, date_from, date_to, technical_key , id, date, type_document_id){

        $("#FACT_E_RESOLUCION_resolution_id").val(id);
        $("#FACT_E_RESOLUCION_resolution").val(resolution);
        $("#FACT_E_RESOLUCION_prefix").val(prefix);
        $("#FACT_E_RESOLUCION_technical_key").val(technical_key);
        $("#FACT_E_RESOLUCION_resolution_date").val(date);
        $("#FACT_E_RESOLUCION_date_from").val(date_from);
        $("#FACT_E_RESOLUCION_date_to").val(date_to);
        $("#FACT_E_RESOLUCION_from").val(from);
        $("#FACT_E_RESOLUCION_to").val(to);
        $("#FACT_E_RESOLUCION_type_document_id").val(type_document_id);
    },
    emptyResolutionform: function(){

        $("#FACT_E_RESOLUCION_resolution_id").val('');
        $("#FACT_E_RESOLUCION_resolution").val('');
        $("#FACT_E_RESOLUCION_prefix").val('');
        $("#FACT_E_RESOLUCION_technical_key").val('');
        $("#FACT_E_RESOLUCION_resolution_date").val('');
        $("#FACT_E_RESOLUCION_date_from").val('');
        $("#FACT_E_RESOLUCION_date_to").val('');
        $("#FACT_E_RESOLUCION_from").val('');
        $("#FACT_E_RESOLUCION_to").val('');
        $("#FACT_E_RESOLUCION_type_document_id").val('');
    },
    confirmDeleteResolution: function (id) {
        swal({
            title: "Seguro que quieres eliminar esta reoslucion?",
            text: "Esta accion es irreversible!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarlo!",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {

                var result = FactElectronicaServices.deleteResolucion(id);
                

                result.success(function (datareturn) {

                    if (datareturn.error === undefined) {
                        Utilities.alertModal('Se eliminado la resolucion...', 'success');

                        FacturacionElectronica.index();
                    } else {
                        Utilities.alertModal(datareturn.error, 'error', 6000);
                    }
                })
                result.error(function () {

                    Utilities.alertModal('Ha ocurrido un error');

                })

                
            }


        });
    },

    registrarCertificado: function () {

        console.log('data', data);
        //primero salvo registro el sistema en el api
        var data = new FormData($("#form_empresa")[0]);
        var result = FactElectronicaServices.registrarCertificado(data);
        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.error === undefined) {
                var data = $("#form_empresa").serialize();
                // si retorna succes almaceno en local
                var result = FactElectronicaServices.saveOpciones(data);
                result.success(function (datareturn2) {

                    if (datareturn2.success != undefined) {

                        Utilities.alertModal(datareturn.message, 'success');
                        // FacturacionElectronica.index();
                    } else {
                        Utilities.alertModal(datareturn2.error, 'error', 6000);
                    }

                });
            } else {
                Utilities.alertModal(datareturn.error, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    registrarLogo: function () {


        //primero salvo registro el sistema en el api
        var data = new FormData($("#form_empresa")[0]);
        var result = FactElectronicaServices.registrarLogo(data);
        console.log('result', result);

        result.success(function (datareturn) {

            if (datareturn.error === undefined) {
                var data = $("#form_empresa").serialize();
                // si retorna succes almaceno en local
                var result = FactElectronicaServices.saveOpciones(data);
                result.success(function (datareturn2) {

                    if (datareturn2.success != undefined) {

                        Utilities.alertModal(datareturn.message, 'success');
                        // FacturacionElectronica.index();
                    } else {
                        Utilities.alertModal(datareturn2.error, 'error', 6000);
                    }

                });
            } else {
                Utilities.alertModal(datareturn.error, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    registrarFactExterna: function () {

        var data = new FormData($("#form_empresa")[0]);
        var result = FactElectronicaServices.registrarFactExterna(data);
        result.success(function (datareturn) {

            if (datareturn.error === undefined) {

            } else {
                Utilities.alertModal(datareturn.error, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    events: function () {

        //Warning Message
        $('#shudown').click(function () {
            swal({
                title: "Seguro que quieres apagar el servidor?",
                text: "Esta acción dejará sin servico el sistema hasta que vuelvas a encenderlo!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Apagarlo!",
                closeOnConfirm: true
            }, function () {
                Utilities.alertModal('Se ha enviado la orden de apago al servidor...', 'success');
                setTimeout(function () {
                    Server.shutDown();
                }, 500);

            });
        });
    }
    ,
    init: function () {
        Server.events();
    }


}