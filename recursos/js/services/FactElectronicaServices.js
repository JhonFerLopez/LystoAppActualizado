var FactElectronicaServices = {


    shutdown: function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'server/shutdown',
            dataType: "json"
        });
    },

    registrarEmpresa: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            url: baseurl + 'facturacionElectronica/registrarEmpresa',
            dataType: "json"
        });
    },

    registrarSoftare: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            url: baseurl + 'facturacionElectronica/registrarSoftare',
            dataType: "json"
        });
    },
    registrarResolucion: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            url: baseurl + 'facturacionElectronica/registrarResolucion',
            dataType: "json"
        });
    },
    deleteResolucion: function (id) {
        console.log('REOSLUTION ID ', id);
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: {id:id},
            url: baseurl + 'facturacionElectronica/deleteResolucion',
            dataType: "json"
        });
    },
    registrarCertificado: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            mimeType: "multipart/form-data",
            url: baseurl + 'facturacionElectronica/registrarCertificado',
            dataType: "json"
        });
    },

    registrarLogo: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            mimeType: "multipart/form-data",
            url: baseurl + 'facturacionElectronica/registrarLogo',
            dataType: "json"
        });
    },
    registrarFactExterna: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            mimeType: "multipart/form-data",
            url: baseurl + 'facturacionElectronica/registrarFactExterna',
            dataType: "json"
        });
    },
    consultarRangos: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            mimeType: "multipart/form-data",
            url: baseurl + 'facturacionElectronica/consultarRangos',
            dataType: "json"
        });
    },
    saveOpciones: function (data) {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            data: data,
            url: baseurl + 'facturacionElectronica/saveOptions',
            dataType: "json"
        });
    },


}