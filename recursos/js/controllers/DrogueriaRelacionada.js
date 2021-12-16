var DrogueriaRelacionada = {

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
    },
    delete: function (id, nom) {
        this.cache.borrar.modal('show');
        this.cache.id_borrar.attr('value', id);
        this.cache.nom_borrar.attr('value', nom);
    },
    edit: function (id) {
        Utilities.showPreloader();
        var ajax = DrogueriaRelacionadaService.edit(id);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            DrogueriaRelacionada.cache.globalModal.html(data);
            DrogueriaRelacionada.cache.globalModal.modal({show: true});
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    },
    add: function () {
        Utilities.showPreloader();
        var ajax = DrogueriaRelacionadaService.add();
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            DrogueriaRelacionada.cache.globalModal.html(data);
            DrogueriaRelacionada.cache.globalModal.modal({show: true, keyboard: false, backdrop: 'static'});
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    }
    ,
    save: function () {

        console.log(DrogueriaRelacionada.cache);
        DrogueriaRelacionada.cache.guardar.addClass('disabled');
        if (DrogueriaRelacionada.cache.drogueria_nombre.val() == '') {
            DrogueriaRelacionada.cache.guardar.removeClass('disabled');
            Utilities.alertModal('<h4>Debe ingresar el nombre</h4>', 'warning');
            return false;
        }

        if (DrogueriaRelacionada.cache.drogueria_domain.val() == '') {
            DrogueriaRelacionada.cache.guardar.removeClass('disabled');
            Utilities.alertModal('<h4>Debe ingresar el dominio</h4>', 'warning');
            return false;
        }

        App.formSubmitAjax($("#formagregar").attr('action'), DrogueriaRelacionadaService.index, 'globalModal', 'formagregar', 'guardar');
    }
    ,
    confirmdelete: function () {
        App.formSubmitAjax($("#formeliminar").attr('action'), DrogueriaRelacionadaService.index, 'borrar', 'formeliminar');
    }
}



