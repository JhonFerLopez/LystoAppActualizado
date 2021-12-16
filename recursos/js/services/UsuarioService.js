var UsuarioService = {

    urlController: 'grupo',
    urlApi: 'api/Usuarios',

    /**
     * Services de Usuarios
     */
    getUsuariosByRolNomb: function (rol) {

        //busca todos los usuarios que pertenezcan a x rol, filrado pr el nombre del rol
        return $.ajax({
            url: baseurl + this.urlApi + '/getUsuariosByRolNomb',
            data: {rol: rol},
            type: 'POST',
            dataType: "json"
        });
    },

    getPosiDomiciliario: function () { //busca los domicilios
        return $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseurl + UsuarioService.urlApi+ '/getPosiDomiciliario',

        });
    },

}
