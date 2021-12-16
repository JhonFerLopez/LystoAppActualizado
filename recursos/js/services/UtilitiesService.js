var UtilitiesService = {


    verySession: function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'inicio/very_sesion',
            dataType: "json"
        });
    },

    updateBd:function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'migrate',
            dataType: "json"
        });
    },
    updateRepo:function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'migrate/pull',
            dataType: "json"
        });
    },
    generarBackup:function () {
        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + 'opciones/generatebackup',
            dataType: "json",
            async:false
        });
    },
	saveRecibeNotControlAmb:function (token) {
		return $.ajax({	//create an ajax request to load_page.php
			type: "POST",
			url: baseurl + 'control_ambiental/saveRecibeNotControlAmb',
			dataType: "json",
			async:false,
			data:{token:token}
		});
	},
	restartRecibeNotControlAmb:function (token) {
		return $.ajax({	//create an ajax request to load_page.php
			type: "POST",
			url: baseurl + 'control_ambiental/restartRecibeNotControlAmb',
			dataType: "json",
			async: false,
			success: function (data) {
				Utilities.alertModal('Restablecido usuario para notificación de control ambiental', 'success', true);
			}
		});
	}

}
