var Gasto = {


  
    print(id) {

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();
        var MENSAJE_FACTURA = $("#MENSAJE_FACTURA").val();
        var MOSTRAR_PROSODE = $("#MOSTRAR_PROSODE").val();
        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        if (is_nube) {


           
                $.ajax({
                    url: baseurl + 'Gastos/get_data_for_cloud_print',
                    type: 'POST',
                    data: { id: id },
                    success: function (data) {
                        var urltickera = TICKERA_URL;
                        //  var url = baseurl + 'venta/directPrint/' + id_venta;
                        var url = urltickera + '/print_gasto/';


                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                id: id,
                                data:data,
                                                       
                                IMPRESORA: IMPRESORA,
                           
                                MENSAJE_FACTURA: MENSAJE_FACTURA,
                                MOSTRAR_PROSODE: MOSTRAR_PROSODE
                            },
                            success: function (data) {
                                Utilities.alertModal('El ticket se ha enviado a la impresora', 'success');

                            }, error: function () {
                                Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                            }
                        });


                    }, error: function () {

                    }
                });
            

        } else {

            var url = baseurl + 'Gastos/print/' + id;


            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    id: id
                },
                success: function (data) {
                    Utilities.alertModal('El ticket se ha enviado a la impresora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });

        }

    },


    search: function () {
        // Utilities.showPreloader();
        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();
        var tipo = $("#tipo").val();


        TablesDatatablesLazzy.init(baseurl + 'gastos/all', 0, 'tabla', {
            fecha_desde: fercha_desde,
            fecha_hasta: fercha_hasta,
            tipo: tipo,

            reporte: true,
        }, 'Reporte gastos', false, false, false, false, false, false, '300px');

    },

    borrar: function (id, nom) {

        $('#borrar').modal('show');
        $("#id_borrar").attr('value', id);
    },


    editar: function (id) {

        $("#agregar").load(baseurl + 'gastos/form/' + id);
        $('#agregar').modal('show');
    },

    agregar: function () {

        $("#agregar").load(baseurl + 'gastos/form');
        $('#agregar').modal('show');
    },



    ajaxgrupo: function () {
        return $.ajax({
            url: baseurl + 'gastos'

        })
    },
    guardar: function () {
        if ($("#fecha").val() == '') {
            var growlType = 'warning';
            Utilities.alertModal('Debe seleccionar una fecha', 'danger');


            $(this).prop('disabled', true);

            return false;
        }

        if ($("#descripcion").val() == '') {
            var growlType = 'warning';
            Utilities.alertModal('Debe ingresar la descripcion', 'danger');


            $(this).prop('disabled', true);

            return false;
        }

        if ($("#descripcion").val() == '') {
            var growlType = 'warning';
            Utilities.alertModal('Debe ingresar el monto gastado', 'danger');


            $(this).prop('disabled', true);

            return false;
        }

        if ($("#tipo_gasto").val() == '') {
            var growlType = 'warning';
            Utilities.alertModal('Debe seleccionar el tipo de gasto', 'danger');


            $(this).prop('disabled', true);

            return false;
        }


        if ($("#local_id").val() == '') {
            var growlType = 'warning';
            Utilities.alertModal('Debe seleccionar el local', 'danger');


            $(this).prop('disabled', true);

            return false;
        }

        var ajaxreturn = GastoService.save($("#formagregar").serialize());
       // App.formSubmitAjax($("#formagregar").attr('action'), this.print, 'agregar', 'formagregar');

        ajaxreturn.success(function (data) {

            if (data.success != undefined) {

                Gasto.print(data.id);
                Utilities.hiddePreloader();
                $('#agregar').modal('hide');
                setTimeout(function () {
                    var ajaxgrupo = Gasto.ajaxgrupo();
                    ajaxgrupo.success(function (data2) {
                        $('#page-content').html(data2);


                        $("#successspan").html(data.success);
                        $("#success").css('display', 'block');

                    });
                },1000);


            } else {
                Utilities.hiddePreloader();
                Utilities.alertModal(data.error, 'warning');
            }


        });
        ajaxreturn.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error', 'warning');
        });


   
    },




    eliminar: function () {

        App.formSubmitAjax($("#formeliminar").attr('action'), Gasto.ajaxgrupo, 'borrar', 'formeliminar');
    },

    confirmarEdit: function (recibo, clave_maestra) {
        $('#globalModal').modal('hide');
      
            swal({
                title: 'Ingresa la clave maestra',
                text: "Para poder anular el recibo",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: 'Clave maestra'
            }, function (inputValue) {
                if (inputValue === false) {
                    swal.showInputError("Por favor ingrese la clave maestra!");
                    return false
                }
                if (inputValue === "") {
                    swal.showInputError("Por favor ingrese la clave maestra!");
                    return false
                }
                if (inputValue != clave_maestra) {
                    swal.showInputError("La clave maestra es incorrecta");
                    return false
                }
                swal.close();
                Gasto.editar(recibo);
            });


       


    },


}



