var DrogueriaRelacionadaService = {
    urlController: 'drogueria_relacionada',
    urlApi: 'api/DrogueriaRelacionada',
    index: function() {
        return $.ajax({
            url: baseurl + DrogueriaRelacionadaService.urlController
        })
    },
    all: function() {
        return $.ajax({
            url: baseurl + DrogueriaRelacionadaService.urlApi
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