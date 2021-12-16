var EmpresaAfiliadaService = {

    urlController : 'afiliado',
    urlApi : 'api/Afiliado',

    getById: function (id) {
        return $.ajax({
            url: baseurl + this.urlApi,
            data:{id:id}

        });
    },
    index: function() {
        return $.ajax({
            url: baseurl + EmpresaAfiliadaService.urlController
        })
    },
    all: function() {
        return $.ajax({
            url: baseurl + EmpresaAfiliadaService.urlApi
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
    }

}