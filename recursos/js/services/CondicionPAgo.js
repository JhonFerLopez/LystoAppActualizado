var CondicionPagoService = {

    urlController : 'condicionespago',
    urlApi : 'api/pagos',

    getByCliente: function () {
        return $.ajax({
            url: baseurl + this.urlApi,

        });
    },


}