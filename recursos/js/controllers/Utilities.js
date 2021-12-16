var Utilities = {

    fact_elec_desc_general: 10,
    unidades: new Array(),
    droguerias: new Array(),
    dom: {
        'barloadermodal': $('#barloadermodal')
    },
    alertModal: function (message, type, hideAfter=4500, id=false, callbackonclose=false) {

        var loaderBg = '#ff6849';
        var icon = 'error';

        var titulo = '';
        if (type == 'error') {
            var loaderBg = '#ff6849';
            var icon = 'error';
            titulo = 'ERROR'
        }
        if (type == 'success') {
            var loaderBg = '#ff6849';
            var icon = 'success';
            titulo = 'FELICIDADES'
        }
        if (type == 'warning') {
            var loaderBg = '#ff6849';
            var icon = 'warning';
            titulo = 'ATENCION'
        }
        if (type == 'info') {
            var loaderBg = '#ff6849';
            var icon = 'info';
            titulo = 'INFORMACIÓN'
        }

        if (hideAfter === true) {
            hideAfter = 4500;
        }
        if (hideAfter === false) {
            hideAfter = false;
        }
        return $.toast({
            heading: titulo,
            text: message,
            position: 'top-right',
            loaderBg: loaderBg,
            icon: icon,
            hideAfter: hideAfter,
            stack: 6,
            afterHidden: function () {
                //esta es la accion de cierre del toast, llama a una funcion que se le pase y
                // el id de la variable que tendra los valores de este toast, en el return arriba.
                //Lo ideal es que sea el ID sea el indice de un arreglo, el cual se puede llamar en la funcion que se pase
                //de ejemplo vea la funcion: closeNotifiControlAmb
                if (callbackonclose != false) {
                    callbackonclose(id);
                }
            }

        });
    },
    closeAlertModal: function () {
        $(".jq-toast-single").css('display', 'none');
    },
    showPreloader: function () {
        /*$('#barloadermodal').modal({
         show: true,
         backdrop: 'static'
         });*/

        $('#wrapper').block({
            message: '<p style="margin:0;padding:8px;font-size:24px;">Por favor espere...</p>'
            , css: {
                color: '#fff'
                , border: '1px solid #fb9678'
                , backgroundColor: '#fb9678'
            }
        });


    },
    hiddePreloader: function () {

        $('#wrapper').unblock();
        // Utilities.hiddePreloader();
    },
    hideModal: function (modal) {
        $("#" + modal).modal('hide');

    },
    showModal: function (modal) {
        $("#" + modal).modal('show');
    },
    selectSelectableElement: function (selectableContainer, elementToSelect) {

        // add unselecting class to all elements in the styleboard canvas except current one
        jQuery("tr", selectableContainer).each(function () {
            if (this != elementToSelect[0]) {
                jQuery(this).removeClass("ui-selected");
            }
        });


        // add ui-selecting class to the element to select

        elementToSelect.addClass("ui-selected");

        //  checkCantidad();
        selectableContainer.selectable('refresh');


        // trigger the mouse stop event (this will select all .ui-selecting elements, and deselect all .ui-unselecting elements)
        //selectableContainer.data("selectable")._mouseStop(null);


        var topmodal = $("#preciostbody").offset().top;

        if ($(".ui-selected").length > 0) {
            var top = $(".ui-selected").offset().top;

        } else {
            top = 0;
        }
        $('#tablaproductos_wrapper .dataTables_scrollBody').animate({


            scrollTop: top - topmodal
        }, 100);


    },
    setfocus: function (element, time) {

        if (time == undefined) {
            time = 600
        }
        setTimeout(function () {

            $("" + element + "").focus();

        }, time);
    },


    drogueriasRelacionadasModal: function () {
        $("#tbodydroguerias").html('');
        Utilities.hiddePreloader();
        $("#modaldroguerias").modal('show');
    },
    buscarCatalogoDroguerias: function () {
        console.log('buscando dorguerias');
        $("#tbodydroguerias").html('');
        jQuery.each(Utilities.droguerias, function (i, drogueria) {
            console.log(drogueria);
            if (typeof (Storage) !== undefined) {
                if (sessionStorage["drogueria_" + drogueria.drogueria_id] === undefined) {
                    var authajax = SecurityService.login('PROSODE', 'SysCalVE87901.-', drogueria.drogueria_domain);
                    console.log(authajax);
                    //TODO HAY QUE HACER EL MODULO DE SEGURIDAD COMPLETO DONDE SE AUTORICE EL USO DEL API Y LE RETORNE UN USUARIO Y PASSWORD EL CUAL SE ALAMACENARA EN BD Y SE USARA AQUI

                    authajax.success(function (data) {
                        sessionStorage["api_drogueria_" + drogueria.drogueria_id] = data.api_key;
                        console.log(sessionStorage);
                        Utilities.doSpecialSearch(drogueria)
                    });
                } else {
                    console.log('ya etsa en session');
                }

            } else {
                Utilities.alertModal('El navegador no soporta sessionStorage');

            }
        });
    },

    doSpecialSearch: function (drogueria) {

        Utilities.showPreloader();
        var productserasch = ProductoService.specialSearch({
            'drogueria_id': drogueria.drogueria_id,
            'search[value]': $("#drogueriasearch").val()
        }, drogueria.drogueria_domain, sessionStorage["api_drogueria_" + drogueria.drogueria_id]);
        productserasch.success(function (datasearch) {

            if (datasearch.productos != undefined) {
                jQuery.each(datasearch.productos, function (i, value) {
                    var html = '';
                    var codigo_interno = value['producto_codigo_interno'];
                    // console.log(codigo_interno);
                    if ($("#droguerias_prod_" + codigo_interno).length == 0) {
                        html += '<tr id="droguerias_prod_' + value['producto_codigo_interno'] + '" tabindex="' + i + '" data-name="' + value['producto_nombre'] + '"">';
                        html += '<td>' + value['producto_codigo_interno'] + '</td>';
                        html += '<td>' + value['producto_nombre'] + '</td>';
                        jQuery.each(Utilities.droguerias, function (j, drog) {

                            html += '<td id="' + value['producto_codigo_interno'] + '_drogueria_' + drog.drogueria_id + '">';

                            if (drog.drogueria_id == drogueria.drogueria_id) {
                                html += '<table class="table table-condensed table-bordered"><tr class="">';

                                jQuery.each(Utilities.unidades, function (u, unidad) {
                                    html += '<td>';
                                    var tieneantidad = false;
                                    jQuery.each(value.existencia, function (p, exis) {

                                        if (unidad.id_unidad == exis.id_unidad) {
                                            html += 'Stock: ' + exis['cantidad'];
                                            html += '<br>Precio: ' + exis['precio'];
                                            tieneantidad = true;
                                        }
                                    });
                                    if (tieneantidad == false) {
                                        html += 'Stock :0';
                                        html += '';
                                    }
                                    html + '</td>';
                                });
                                html += '</tr></table>';
                            }
                            html += '</td>';
                        });
                        html += '</tr>';

                        $("#tbodydroguerias").append(html);
                    } else {


                        jQuery.each(Utilities.droguerias, function (j, drog) {


                            if (drog.drogueria_id == drogueria.drogueria_id) {
                                html += '<table class="table table-condensed table-bordered"><tr class="">';

                                jQuery.each(Utilities.unidades, function (u, unidad) {
                                    html += '<td>';
                                    var tieneantidad = false;
                                    jQuery.each(value.existencia, function (p, exis) {
                                        if (unidad.id_unidad == exis.id_unidad) {
                                            html += 'Stock: ' + exis['cantidad'];
                                            html += '<br>Precio: ' + exis['precio'];
                                            tieneantidad = true;
                                        }

                                    });
                                    if (tieneantidad == false) {
                                        html += ' Stock :0';
                                        html += '';
                                    }
                                    html + '</td>';
                                });
                                html += '</tr></table>';
                            }

                        });
                        $("#" + value['producto_codigo_interno'] + "_drogueria_" + drogueria.drogueria_id).html(html);
                    }

                });
            }
            Utilities.hiddePreloader();


        });
    },
    validateEmail: function (email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    },
    FlotChart: function (element, barData, barOptions, animation) {
        //Flot Bar Chart


        if (animation == true) {

            var plot = $.plotAnimator(element, barData, barOptions);
        } else {
            var plot = $.plot(element, barData, barOptions);
        }


        return plot;
    },


    init: function (droguerias, unidades) {
        this.unidades = unidades;

        this.droguerias = droguerias;

    },
    precioscontadoacredito: function () {
        $.ajax({
            url: baseurl + 'opciones/precioscontadoacredito',
            type: 'POST',
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (data) {

                console.log(data)
            }
        })
    },


}

var region = {

    actualizarestados: function () {

        $.ajax({
            url: baseurl + 'estados/get_by_pais',
            type: 'POST',
            data: {'pais_id': $("#id_pais").val()},
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (data) {
                if (data != 'undefined') {
                    var options = '<option value="">Seleccione</option>';
                    for (var i = 0; i < data.length; i++) {
                        let selected = $("#estado_id_hidden").val()==data[i].estados_id?true:false;

                        options += '<option selected="'+selected+'" value="' + data[i].estados_id + '">' + data[i].estados_nombre + '</option>';

                    }

                    $("#estado_id").html(options);
                    $("#estado_id").trigger('chosen:updated');
                }
            }
        })
    },


    actualizardistritos: function () {

        $.ajax({
            url: baseurl + 'ciudad/get_by_estado',
            type: 'POST',
            data: {'estado_id': $("#estado_id").val()},
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (data) {
                if (data != 'undefined') {
                    var options = '<option value="">Seleccione</option>';
                    for (var i = 0; i < data.length; i++) {

                        options += '<option value="' + data[i].ciudad_id + '">' + data[i].ciudad_nombre + '</option>';
                    }
                    $("#ciudad_id").html(options);
                    $("#ciudad_id").trigger('chosen:updated');
                }
            }
        })
    },

    actualizarzonas: function () {
        $.ajax({
            url: baseurl + 'zona/get_by_ciudad',
            type: 'POST',
            data: {'ciudad_id': $("#ciudad_id").val()},
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (data) {
                if (data != 'undefined') {
                    var options = '<option value="">Seleccione</option>';
                    for (var i = 0; i < data.length; i++) {

                        options += '<option value="' + data[i].zona_id + '">' + data[i].zona_nombre + '</option>';
                    }
                    $("#zona").html(options);
                    $("#zona").trigger('chosen:updated');
                }
            }
        })
    },
    actualizarvendedor: function () {
        if ($("#vendedor").val() == 0) {
            $.ajax({
                url: baseurl + 'usuario/get_by_usuario',
                type: 'POST',
                data: {'zona_id': $("#zona").val()},
                dataType: 'json',
                headers: {
                    Accept: 'application/json'
                },
                success: function (data) {
                    if (data != 'undefined') {

                        var options = '<option value="0">Seleccione</option>';
                        for (var i = 0; i < data.length; i++) {

                            options += '<option value="' + data[i].nUsuCodigo + '">' + data[i].nombre + '</option>';
                        }
                        $("#vendedor").html(options);
                        $("#vendedor").trigger('chosen:updated');
                    }
                }
            })
        }
    },
    actualizarzona: function () {
        if ($("#zona").val() == 0) {
            $.ajax({
                url: baseurl + 'zona/get_by_usuario_zona',
                type: 'POST',
                data: {'vendedor': $("#vendedor").val()},
                dataType: 'json',
                headers: {
                    Accept: 'application/json'
                },
                success: function (data) {
                    if (data != 'undefined') {

                        var options = '<option value="0">Seleccione</option>';
                        for (var i = 0; i < data.length; i++) {

                            options += '<option value="' + data[i].zona_id + '">' + data[i].zona_nombre + '</option>';
                        }
                        $("#zona").html(options);
                        $("#zona").trigger('chosen:updated');
                    }
                }
            })
        }
    },


}

var miperfil = {

    mostrarmodal: function () {
        $("#modal-user-settings").modal('show');
    },
    guardar: function () {
        if ($("#nombre").val() == '') {
            var growlType = 'warning';

            $.bootstrapGrowl('<h4>Debe ingresar el nombre</h4>', {
                type: growlType,
                delay: 2500,
                allow_dismiss: true
            });

            $(this).prop('disabled', true);

            return false;
        }

        $.ajax({
            url: baseurl + 'usuario/guardarsession',
            dataType: 'json',
            data: $("#modal-user-settings-form").serialize(),
            type: 'post',
            success: function (data) {
                if (data.error === 'undefined') {

                    var growlType = 'warning';

                    $.bootstrapGrowl('<h4>' + data.error + '</h4>', {
                        type: growlType,
                        delay: 2500,
                        allow_dismiss: true
                    });

                    $(this).prop('disabled', true);

                    return false;

                } else {
                    $("#modal-user-settings").modal('hide');
                }
            }

        })

    }
}