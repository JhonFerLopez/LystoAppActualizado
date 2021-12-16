var AjusteInventarioService = {
    urlController: 'inventario',
    urlApi: 'api/inventario',
    add: function (tipo) {
        return $.ajax({
            url: baseurl+AjusteInventarioService.urlController+'/addajuste/'+tipo
        })
    },
    index: function () {
        return $.ajax({
            url: baseurl+AjusteInventarioService.urlController+'/ajuste'

        })

    },
    guardar:function(data){
        return $.ajax({
            type:'POST',
            data:data,
            dataType:'json',
            url: baseurl+AjusteInventarioService.urlController+'/guardar'

        })

    },
    anularMovim:function(id_ajusteinventario){
        return $.ajax({
            type:'POST',
            data:{id_ajusteinventario:id_ajusteinventario},
            dataType:'json',
            url: baseurl+AjusteInventarioService.urlController+'/anularMovim'

        })

    }
}