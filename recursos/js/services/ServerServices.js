var ServerServices = {


    shutdown: function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'server/shutdown',
            dataType: "json"
        });
    },

    renovarLicencia: function (date) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data:{date:date},
            url: baseurl + 'server/renovarLicencia',
            dataType: "json"
        });
    },

    pruevadrive: function (date) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data:{date:date},
            url: baseurl + 'opciones/backupdrive',
            dataType: "json"
        });
    },

}