var GastoService = {

    urlController : 'gastos',
    urlApi : 'api/Gasto',

    getAll: function () {
        return $.ajax({
            url: baseurl + this.urlApi,

        });
    },
    get: function (id) {
        return $.ajax({
            url: baseurl + this.urlApi+'/ver',
            data:{id:id},
            type:'GET',
            dataType:'json'

        });
    },

    save: function(data) {
        return $.ajax({
            url: baseurl + this.urlController+'/guardar',
            data:data,
            dataType:'json',
            type: 'post'
        });
    },

    
}