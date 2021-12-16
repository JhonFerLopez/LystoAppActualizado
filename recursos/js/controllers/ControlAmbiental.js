var ControlAmbiental = {
    closeNotifiControlAmb: function (alias) {
        //guarda la accion de cerrar la notificacion del control de cambios
        $.ajax({
            url: baseurl + 'control_ambiental/cerrarnotificacion',
            dataType: 'json',
            type: 'post',
            data: {'alias': alias, control_id: control_items[alias].control_id, dia: control_items[alias].dia},
            success: function (data) {
                if (data.success) {

                    ControlAmbiental.buscarcontrolambiental(control_items[alias].control_id);
                    control_items[alias].reset();
                    control_items[alias] = '';
                    return true;
                }
            }
        })
    },
    buscarcontrolambiental: function (id) {

        $("#addcontrol").load(baseurl + 'control_ambiental/buscarcontrolambiental/' + id);
        $('#addcontrol').modal('show');
    },
    ajaxgrupo: function () {

    },
    guardar: function () {
        if ($("#mes").val() == '') {
            var growlType = 'warning';

            $.bootstrapGrowl('<h4>Debe seleccionar el mes</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });
            $(this).prop('disabled', true);
            return false;
        }
        App.formSubmitAjax($("#formagregar").attr('action'), this.ajaxgrupo, 'addcontrol', 'formagregar');
    },
    guardarDetalle: function () {
        App.formSubmitAjax($("#formguardardetalle").attr('action'), this.ajaxgrupo, 'addcontrol', 'formguardardetalle');
    },

	showgraficaControl: function (idcontrol) {

		$.ajax({
			url: baseurl + 'control_ambiental/showGrafica',
			dataType: 'json',
			type: 'post',
			data: {'control_id': idcontrol},
			success: function (data) {
				if (data.success) {
					ControlAmbiental.definirGrafica(data)
					$('#graficaControlAmb').modal('show')
				}
			}
		})
	},
	definirGrafica: function (data){
		Highcharts.chart('div_show_grafica', {
			chart: {
				type: 'spline',
				scrollablePlotArea: {
					minWidth: 600,
					scrollPositionX: 1
				}
			},
			title: {
				text: 'Gráfica de Control Ambiental del mes de '+data.mestexto,
				align: 'left'
			},
			subtitle: {
				text: 'Aquí podrá visualizar los cambios de temperatura del mes de '+data.mestexto,
				align: 'left'
			},
			plotOptions: {
				spline: {
					lineWidth: 4,
					states: {
						hover: {
							lineWidth: 5
						}
					},
					marker: {
						enabled: false
					},
				}
			},
			xAxis: {
				type: 'category',
				labels: {
					overflow: 'justify'
				},
				title: {
					text: 'Días del mes'
				},
			},
			yAxis: {
				align: 'left',
				title: {
					text: 'Grados (°)'
				},
				minorGridLineWidth: 0,
				gridLineWidth: 0,
				alternateGridColor: null,
			},
			tooltip: {
				valueSuffix: '°'
			},
			series: data.dataretorno,
			navigation: {
				menuItemStyle: {
					fontSize: '10px'
				}
			}
		});


	}

}
