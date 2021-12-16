var SecurityService = {
    urlController: 'Auth',
    urlApi: '/api/Auth/login',
    login: function (username, password, domain) {
        return $.ajax({
            url: domain   +SecurityService.urlApi + '/',
            /*xhrFields: {
             withCredentials: true
             },*/
            //data: {username: username, password: password},
            type: 'post',
            dataType: 'json'
        });
    },


}