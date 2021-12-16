var AfiliadoDescuentosService = {

    urlController : 'afiliadoDescuento',
    urlApi : 'api/AfiliadoDescuento',

    getById: function (id) {
        return $.ajax({
            url: baseurl + this.urlApi,
            data:{id:id}

        });
    },
    getByEmpresa: function (id) {
        return $.ajax({
            url: baseurl + this.urlApi+'show_by_empresa',
            data:{empresa_id:id}

        });
    },


}