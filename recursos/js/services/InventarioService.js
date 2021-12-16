var InventarioService = {

    urlController: 'inventario',
    urlApi: 'api/Inventario',

    buscarExistenciayPrecios: function (producto_id, getprecios, is_paquete,local) {

        return $.ajax({
            url: baseurl + this.urlController + '/buscarExistenciayPrecios',
            type: 'POST',
            dataType: "json",
            data: {'producto': producto_id,'getprecios':getprecios,'is_paquete':is_paquete,'local':local},

        });
    },

    soloStock: function (producto_id,local) { //devuelve el stock de un producto, en modo normal, y en unidades minimas

        return $.ajax({
            url: baseurl + this.urlController + '/soloStock',
            data: {'producto': producto_id,'local_id':local},
            type: 'POST',
            dataType: "json",
            async:false
        });
    },

    converUnidadesMinimas: function (unidades_producto,unidad,cantidad) {
        //aqui convierto las cantidades colocadas en los campos, a unidades minimas

        var total_unidades_minimas_viejas=0;
        $.each(unidades_producto, function (key, value) {

            if (unidad == value['id_unidad'] && value['orden'] == 1) {
                total_unidades_minimas_viejas += cantidad * value['unidades'];
            }

            if (unidad == value['id_unidad'] && value['orden'] == 2) {
                total_unidades_minimas_viejas += cantidad * unidades_producto[2]['unidades'];
            }

            if (unidad == value['id_unidad'] && value['orden'] == 3) {
                total_unidades_minimas_viejas += cantidad;

            }

        })

        return total_unidades_minimas_viejas;
    },

    buscarExistenciaProducto: function (unidades,producto_id,getprecios, is_paquete,local) {
        //para mostrar la existencia en stock del producto

        $.each(unidades, function (key, value) {
            $("#existencia_" + value.id_unidad).text(0);
        });

        var varAjaxexistecia = InventarioService.buscarExistenciayPrecios(producto_id,getprecios,is_paquete,local);
        varAjaxexistecia.success(function (data) {
            var stock = data.stock;
            if (stock.length > 0) {
                $.each(stock, function (key, value) {
                    //$("#contenido_" + value.id_unidad).val(value.unidades);
                    $("#existencia_" + value.id_unidad).text(value.cantidad);
                });
            }
        });
    },


}