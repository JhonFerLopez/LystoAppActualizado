var Server = {


    licenciamientoIndex: function () {
        $.ajax({
            url: baseurl + 'licenciamiento',
            success: function (data2) {
                $('#page-content').html(data2);
            }
        })
    },


    shutDown: function () {


        var result = ServerServices.shutdown();
        result.success(function (data) {

            if (data.result == 'success') {
                Utilities.alertModal('El servidor ha sido apagado, el sistema dejará de funcionar', 'success');
            } else {
                Utilities.alertModal(data.result, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('No se ha podido apagar el servidor, por favor contacte con soporte');

        })
    }
    ,

    pruevadrive: function () {


        var result = ServerServices.pruevadrive();
        result.success(function (data) {

            if (data.drive ==true) {
                Utilities.alertModal('se subio con exito', 'success');
                Server.licenciamientoIndex();
            } else {
                Utilities.alertModal(data.error, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    },

    renovarLiencia: function () {


        var result = ServerServices.renovarLicencia($("#SYS_EXP_DAT").val());
        result.success(function (data) {

            if (data.result == 'success') {
                Utilities.alertModal('La licencia ha sido actualizada', 'success');
                Server.licenciamientoIndex();
            } else {
                Utilities.alertModal(data.result, 'error', 6000);
            }
        })
        result.error(function () {

            Utilities.alertModal('Ha ocurrido un error');

        })
    }
    ,

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