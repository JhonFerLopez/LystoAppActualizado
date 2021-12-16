var StatusCajaService = {

    urlController: 'StatusCaja',
    urlApi: 'api/StatusCaja',


    guardar: function (data) {
        return $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: baseurl + this.urlController + '/guardar'
        });
    },


    getCajaSession: function () {
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseurl + this.urlController + '/getCajaSession'
        });
    },



}