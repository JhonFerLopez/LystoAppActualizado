var Notificaciones = {
    onsuccessCallView:false,
    modalconfirmsendnotif: function () {
        $("#modalconfirmsendnotif").modal('show');
    },
    confirmSendMsj: function () {

            if (
                $('#titulonewnotif').val() != ''
                &&
                $('#textareanewnotif').val() != ''
                &&
                $('#topicnewnotif').val() != ''
            ) {
                Notificaciones.sendNotificacion();
            }else{
                Utilities.alertModal('Debe ingresar todos los datos, título y mensaje', 'warning', true);
            }

    },
    sendNotificacion: function () {
        var data = {
            'topic': $('#topicnewnotif').val(),
            'title': $('#titulonewnotif').val(),
            'message':  $('#textareanewnotif').val(),
        }

        Utilities.showPreloader();
        var sendMsj = NotificacionService.sendMsjAppAndroid(data); //busco los domicilios
        sendMsj.success(function (response) {
            Utilities.hiddePreloader();

            if (response.error != undefined && response.error == 1) {
                Utilities.alertModal(response.message, 'error', true);
                return false;

            } else {

                $("#modalconfirmsendnotif").modal('hide');
                $("#modalNewNotificacion").modal('hide');

                if(Notificaciones.onsuccessCallView==true){
                    var resultcal = NotificacionService.indexPage();
                    resultcal.success(function (data2) {
                        $('#page-content').html(data2);
                    })
                }

                Utilities.alertModal(response.message, 'success', true);
                return true;
            }


        });
        sendMsj.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal(error.message, 'error', true);
            return false;
        });

    },
    modalNewNotificacion: function (llamarIndexOnSuccess){
        Notificaciones.onsuccessCallView=llamarIndexOnSuccess;
        $('#titulonewnotif').val('');
        $('#textareanewnotif').val('');
        $('#modalNewNotificacion').modal('show')
    }
}