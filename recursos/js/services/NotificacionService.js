var NotificacionService = {

    urlController: 'notificaciones',
    urlApi: 'api/Notificaciones',
    sendMsjAppAndroid: function (data) {
        return $.ajax({
            dataType: 'json',
            type: 'POST',
            data: data,
            url: baseurl + this.urlApi + '/sendNotifToTopicAppCustomers'
        });
    },
    indexPage: function () {
        return $.ajax({
            url: baseurl+'notificaciones'
        })
    },

}