var CuentasPorPagarService = {

    urlController: 'cuentasPorPagar',
    urlApi: 'api/CuentasPorPagar',

    buscarJson: function (url, data) {
        return $.ajax({
            type: 'GET',
            data: data,
            dataType: 'json',
            url: baseurl + this.urlApi + '/' + url
        });
    },
    verVentaCredito: function (id) {

        return $.ajax({
            url: baseurl + 'cartera/verVentaCredito',
            type: 'post',
            data: {'idventa': id}
        })
    },

    guardarPago: function (miJSON) {
        return $.ajax({
            type: 'GET',
            data: $('#form').serialize() + '&pago=' + miJSON,
            dataType: 'json',
            url: baseurl + this.urlApi + '/guardarPago'
        });
    },
    imprimirPagoPendiente: function (data) {
        return $.ajax({
            type: 'POST',
            data: data,
            url: baseurl + this.urlController + '/imprimir_pago_pendiente'
        });
    },

    getNextRecibo: function (data) {
        return $.ajax({
            type: 'GET',
            dataType:'json',
            url: baseurl + this.urlApi + '/nextRecibo'
        });
    },


}