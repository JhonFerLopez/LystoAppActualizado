var TrasladoService = {
    urlController: 'traslado',
    form: function (traslado_id,local) {
        return $.ajax({
            url: baseurl+TrasladoService.urlController+'/form/'+traslado_id+'/'+local
        })
    },
    index: function () {
        return $.ajax({
            url: baseurl+TrasladoService.urlController+'/ajuste'

        })

    },
    guardar:function(miJSON){
        return $.ajax({
            type:'POST',
            data: $('#formtraslado').serialize() + '&lst_producto=' + miJSON,
            dataType:'json',
            url: baseurl+TrasladoService.urlController+'/registrarTraslado'

        })

    },
    buscarTraslados: function () {
        return $.ajax({
            url: baseurl + TrasladoService.urlController+'/buscarTraslados',
            data: {'local_salida': $("#localform1").val(),'local_destino': $("#localform2").val(),
            'fecIni': $("#fecIni").val(),'fecFin': $("#fecFin").val()},
            type: 'POST',
            dataType: "json"
        });
    },
}