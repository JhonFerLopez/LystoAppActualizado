var SecurityService = {
    urlController: 'Auth',
    urlApi: '/api/Auth/login',
    login: function (username, password, domain) {
        $.ajax({
            url: domain   +SecurityService.urlApi + '/',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                console.log('success_kerigma');
                console.log(data.api_key);
                console.log('<---');
            },
            error: function (response) {
                console.log('error_kerigma');
                console.log(response);
            }
        })

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