var VentaService = {

    urlController : 'venta',
    urlApi : 'api/Venta',


    save: function (data) {
        return $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: baseurl + VentaService.urlController+ '/registrar_venta',

        });
    },
    ultimoPrecioProducto: function (producto_id) { //busca los ultimos precios de un producto
        return $.ajax({
            type: 'POST',
            data: {'producto_id': producto_id},
            dataType: 'json',
            url: baseurl + VentaService.urlController+ '/ultimoPrecioProducto',

        });
    },


    getVentaByStatus: function (estatus, id_local) { //busca los ultimos precios de un producto
        return $.ajax({
            type: 'POST',
            data: {estatus: estatus,id_local:id_local },
            dataType: 'json',
            url: baseurl + VentaService.urlApi+ '/get_ventas_por_status',

        });
    },

    getDomicilios: function (datos) { //busca los domicilios
        return $.ajax({
            type: 'POST',
            data: datos,
            dataType: 'json',
            url: baseurl + VentaService.urlApi+ '/getDomicilios',

        });
    },

    marcarDomicilioComo: function (usuario,domicilio_id,usuario_asigna,estatus) { //asiga un usuario a un domicilio
        return $.ajax({
            type: 'POST',
            data: {usuario_id:usuario,domicilio_id:domicilio_id,usuario_asigna:usuario_asigna,estatus:estatus},
            dataType: 'json',
            url: baseurl + VentaService.urlApi+ '/marcarDomicilioComo',

        });
    },

    getHistDomicilio: function (datos) { //el historial del domicilio
        return $.ajax({
            type: 'POST',
            data: datos,
            dataType: 'json',
            url: baseurl + VentaService.urlApi+ '/getHistDomicilio',
        });
    },

    printCotizarPdf: function (data) {
        return $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: baseurl + VentaService.urlController+ '/directPrintCotizarPdf',

        });
    },
    getAllColumnsModalProductos: function () {
        return $.ajax({
            type: 'GET',
            dataType: 'json',
            asyn:false,
            url: baseurl + VentaService.urlApi + '/getAllColumnsModalProductos'
        });
    },

    saveColumnsModalProductos: function (datos) { //el historial del domicilio
        return $.ajax({
            type: 'POST',
            data: datos,
            dataType: 'json',
            url: baseurl + VentaService.urlApi+ '/saveColumnsModalProductos',
        });
    },
    indexColumnasModalProductos: function () {
        return $.ajax({
            url: baseurl + VentaService.urlController+ '/columnasmodalproductos'

        })
    },

}