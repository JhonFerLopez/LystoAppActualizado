var Roles = {

    cache: {},
    inizializeDomCache: function () {
        this.cache.borrar = $('#borrar');
        this.cache.id_borrar = $('#id_borrar');
        this.cache.nom_borrar = $('#nom_borrar');
        this.cache.globalModal = $('#globalModal');
        this.cache.guardar = $('#guardar');
        this.cache.drogueria_domain = $('#drogueria_domain');
        this.cache.nombre = $('#nombre_grupos_usuarios');
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
        var ajax = RolesService.edit(id);
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            Roles.cache.globalModal.html(data);
            Roles.cache.globalModal.modal({show: true, keyboard: false, backdrop: 'static'});
        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    },
    toogleTodo: function () {
        console.log('toogle');
        jQuery.each($("input[name='perms\[\]']"), function (i, value) {

            if($(this).is(':checked')){

                $(this).prop('checked', false);
            } else {

                $(this).prop('checked', true);
            }
        });
    },
    add: function () {
        Utilities.showPreloader();
        var ajax = RolesService.add();
        ajax.success(function (data) {
            Utilities.hiddePreloader();
            Roles.cache.globalModal.html(data);
            Roles.cache.globalModal.modal({show: true, keyboard: false, backdrop: 'static'});

        });
        ajax.error(function (error) {
            Utilities.hiddePreloader();
            Utilities.alertModal('<h4> Ha ocurrido un error</h4>', 'warning');
        });
    }
    ,
    save: function () {

        Roles.cache.guardar.addClass('disabled');
        if (Roles.cache.nombre.val() == '') {
            Roles.cache.guardar.removeClass('disabled');
            Utilities.alertModal('<h4>Debe ingresar el nombre</h4>', 'warning');
            return false;
        }


        App.formSubmitAjax($("#formagregar").attr('action'), RolesService.index, 'globalModal', 'formagregar', 'guardar');
    }
    ,
    confirmdelete: function () {
        App.formSubmitAjax($("#formeliminar").attr('action'), RolesService.index, 'borrar', 'formeliminar');
    }
}



