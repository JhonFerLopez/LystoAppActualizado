var CompraService = {

    urlController: 'ingresos',

    getDetalleEspecial: function (detalle_ingreso_id) {

        return $.ajax({
            url: baseurl + this.urlController + '/getDetalleEspecial',
            data: {'detalle_ingreso_id': detalle_ingreso_id},
            type: 'POST',
            dataType: "json",
            async:false
        });
    },


}