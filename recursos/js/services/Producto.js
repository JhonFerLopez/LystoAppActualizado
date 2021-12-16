var ProductoService = {

    urlController: 'producto',
    urlApi: 'api/Productos',

    /**
     * TRae los pecios de todas las unidades que tiene configrada el producto segun el tipo DE VENTA seleccionado,
     * cada tipo de venta tiene asociado si es credito o contado, por alli se trae el precio para cada unidad segun condicion de pago
     */
    getPreciosByProdAndCondicionPago: function (producto_id, condicion_pago) {

        return $.ajax({
            url: baseurl + this.urlController + '/getPreciosByProdAndCondicionPago',
            data: {'producto': producto_id, 'condicion_pago_id': condicion_pago},
            type: 'POST',
            dataType: "json"
        });
    },
    getPreciosByProd: function (producto_id) {
        return $.ajax({
            url: baseurl + +this.urlController + '/getPreciosByProd',
            data: {'producto': producto_id},
            type: 'POST',
            dataType: "json"
        });
    },

    getByCodigo: function (codigo) {

        return $.ajax({
            url: baseurl + this.urlController + '/getByCodigo',
            data: {'codigo': codigo},
            type: 'POST',
            dataType: "json"
        });
    },

    validar_codigo_interno: function (codigo) {

        return $.ajax({
            url: baseurl + this.urlController + '/validar_codigo_interno',
            data:  {'codigo_interno': codigo, 'id': ''},
            type: 'POST',
            dataType: "json"
        });
    },

    /******Trae todos los datos el prodcuo*****/
    getProducto: function (producto) {


        return $.ajax({
            url: baseurl + this.urlController + '/buscar_id',
            data: {'id': producto},
            type: 'POST',
            dataType: "json"

        })

    },

    catalogoCoopidrogas: function (vistaACargar) {

        return $.ajax({
            url: baseurl + this.urlController + '/ver_catalogo_coopidrogras/',
            data: {'vistaACargar': vistaACargar},
            type: 'post'
        })

    },

    getproductosbyDrogueria: function (id) {
        return $.ajax({
            url: baseurl + this.urlApi + '/getproductosbyDrogueria',
            data: {'dorgueria_id': id},
            type: 'POST',
            dataType: "json"
        });
    },

    specialSearch: function (data,domain,apiKey) {

        jQuery.support.cors = true;
        return $.ajax({
            type:'GET',
            url: domain + ProductoService.urlApi+'/specialSearch',
            data: data,
            global: false,
            headers: {
                'X-api-key':apiKey,
            },
            dataType: 'json',
           // ContentType: "application/json"
        });
    },

    getCodigosBarra: function (producto_id) {

        return $.ajax({
            url: baseurl + this.urlController + '/barra_por_producto',
            data: {'producto_id': producto_id},
            type: 'POST',
            dataType: "json",
            async:false
        });
    },

    getSoloProductos: function () {
        //busca solo los datos de los roductos de la tabla producto
        return $.ajax({
            url: baseurl + this.urlController + '/getSoloProductos',
            type: 'POST',
            dataType: "json"
        });
    },

    soloConStock: function (data,domain,apiKey) { //este metodo solo llama a los productos que tengan inventario >0
        jQuery.support.cors = true;
        return $.ajax({
            type:'GET',
            url: domain + ProductoService.urlApi+'/soloConStock',
            data: data,
            global: false,
            headers: {
                'X-api-key':apiKey,
            },
            dataType: 'json',
            // ContentType: "application/json"
        });
    },
    guardarParamRap:function(data){ //guarda los datos de parametrizacion rapida
        return $.ajax({
            type:'POST',
            data:data,
            dataType:'json',
            url: baseurl+this.urlController+'/guardarParamRap'

        })
    },
    paramRapIndex:function(data){ //guarda los datos de parametrizacion rapida
        return $.ajax({
            type:'POST',
            data:data,
            dataType:'json',
            url: baseurl+this.urlController+'/paramrap'

        })
    },
    updateCostos:function () {
        return $.ajax({
            type:'POST',
            dataType:'json',
            url: baseurl+this.urlApi+'/updateCostos'

        })
    },
    //actualiza los costos promedios de todos los productos
    updateCostosPromedio:function () {
        return $.ajax({
            type:'POST',
            dataType:'json',
            url: baseurl+this.urlApi+'/updateCostosPromedio'

        })
    },

    /**
     *Actualiza los precios de forma masiva
     **/
    updatePrecios:function (data) {
        return $.ajax({
            type:'POST',
            dataType:'json',
            data:data,
            url: baseurl+this.urlApi+'/updatePrecios'

        })
    },


    /**
     *Busca los grupos de un producto
     **/
    getGruposByProducto:function (id) {
        return $.ajax({
            type:'POST',
            dataType:'json',
            url: baseurl+this.urlController+'/getGruposByProducto/'+id
        })
    },

}