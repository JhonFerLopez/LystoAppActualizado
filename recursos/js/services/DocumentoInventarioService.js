var DocumentoInventarioService = {
    urlController: 'Documento_inventario',
    urlApi: 'api/DocumentoInventario',
    all: function() {
        return $.ajax({
            url: baseurl + DocumentoInventarioService.urlController
        })
    },
    edit: function(id) {
        return $.ajax({
            url: baseurl + this.urlController+'/form/' + id,
            type: 'post'
        });
    },
    add: function() {
        return $.ajax({
            url: baseurl + this.urlController+'/form/',
            type: 'post'
        });
    },
    getByTipo: function (tipo) {
        return $.ajax({
            url: baseurl + this.urlApi + '/show_by_tipo',
            data: {'tipo': tipo},
            type: 'GET',
            dataType: "json"
        });
    },


}