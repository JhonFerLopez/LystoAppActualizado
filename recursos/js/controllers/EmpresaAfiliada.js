var EmpresaAfiliada = {

    cache: {},
    inizializeDomCache: function () {
        this.cache.borrar = $('#borrar');
        this.cache.id_borrar = $('#id_borrar');
        this.cache.nom_borrar = $('#nom_borrar');
        this.cache.globalModal = $('#globalModal');
        this.cache.guardar = $('#guardar');
        this.cache.drogueria_domain = $('#drogueria_domain');
        this.cache.drogueria_nombre = $('#drogueria_nombre');
    },
    init: function () {
        this.inizializeDomCache();
        TablesDatatables.init();
    },
    delete: function (id, nom) {
        this.cache.borrar.modal('show');
        this.cache.id_borrar.attr('value', id);
        this.cache.nom_borrar.attr('value', nom);
    },
    edit: function (id) {
        Utilities.showPreloader();
        var ajax = EmpresaAfiliadaService.edit(id);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            EmpresaAfiliada.cache.globalModal.html(data);
            EmpresaAfiliada.cache.globalModal.modal({show: true, keyboard: false, backdrop: 'static'});
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    },
    add: function () {
        Utilities.showPreloader();
        var ajax = EmpresaAfiliadaService.add();
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            EmpresaAfiliada.cache.globalModal.html(data);
            EmpresaAfiliada.cache.globalModal.modal({show: true, keyboard: false, backdrop: 'static'});
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    }
    ,
    save: function () {

        console.log(EmpresaAfiliada.cache);
        EmpresaAfiliada.cache.guardar.addClass('disabled');
        if ($("#afiliado_codigo").val() == '') {
            var growlType = 'warning';
            $.bootstrapGrowl('<h4>Debe ingresar el codigo</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });

            $("#guardar").removeClass('disabled');


            return false;
        }
        if ($("#afiliado_nombre").val() == '') {
            var growlType = 'warning';

            $.bootstrapGrowl('<h4>Debe ingresar el nombre de la empresa</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });

            $("#guardar").removeClass('disabled');

            return false;
        }
        if ($("#lista_precios").val() == '') {
            var growlType = 'warning';

            $.bootstrapGrowl('<h4>Debe seleccionar la lista de precios</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });

            $("#guardar").removeClass('disabled');

            return false;
        }


        App.formSubmitAjax($("#formagregar").attr('action'), EmpresaAfiliadaService.index, 'globalModal', 'formagregar', 'guardar');
    }
    ,
    confirmdelete: function () {
        App.formSubmitAjax($("#formeliminar").attr('action'), EmpresaAfiliadaService.index, 'borrar', 'formeliminar');
    }
}



