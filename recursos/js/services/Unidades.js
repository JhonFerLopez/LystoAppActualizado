var UnidadesService = {

    urlController : 'unidades',
    urlApi : 'api/unidades',

    getUnidadesByProd: function (producto_id) {
        return $.ajax({
            url: baseurl + UnidadesService.urlController+'/get_by_producto',
            data: {'producto': producto_id},
            type: 'POST',
            dataType: "json"


        });
    },

    getSoloPreciosByProdNoAsync: function (producto_id) {
        return $.ajax({
            url: baseurl + this.urlController+'/getSoloPrecios',
            data: {'producto': producto_id},
            type: 'POST',
            dataType: "json"
        });
    },

    //esta trae solo los datos de la tabla unidades_has_precio, sin ningun join con otra tabla
    getSoloPreciosByProd: function (producto_id) {
        return $.ajax({
            url: baseurl + this.urlController+'/getSoloPrecios',
            data: {'producto': producto_id},
            type: 'POST',
            dataType: "json",
            async:false
        });
    },


    //esta trae solo los datos de la tabla unidades_has_producto, sin ningun join con otra tabla
    getSoloUnidadesByProd: function (producto_id) {
        return $.ajax({
            url: baseurl + this.urlController+'/getSoloUnidadesHasProducto',
            data: {'producto': producto_id},
            type: 'POST',
            dataType: "json",
            async:false
        });
    }

}