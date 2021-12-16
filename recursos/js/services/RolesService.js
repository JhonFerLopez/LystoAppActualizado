var RolesService = {
    urlController: 'usuariosgrupos',
    urlApi: 'api/Usuariosgrupos',
    index: function() {
        return $.ajax({
            url: baseurl + RolesService.urlController
        })
    },
    all: function() {
        return $.ajax({
            url: baseurl + RolesService.urlApi
        })
    },
    edit: function(id) {
        return $.ajax({
            url: baseurl + RolesService.urlController+'/form/' + id,
            type: 'post'
        });
    },
    add: function() {
        return $.ajax({
            url: baseurl + RolesService.urlController+'/form/',
            type: 'post'
        });
    }

}