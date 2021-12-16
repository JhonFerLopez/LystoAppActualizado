/*
 *  Document   : uiDraggable.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Draggable Blocks page
 */

var UiDraggable = function() {

    return {
        init: function() {
            /* Initialize draggable and sortable blocks, check out more examples at https://jqueryui.com/sortable/ */
            $('.draggable-blocks').sortable({
                connectWith: '.block',
                items: '.block',
                opacity: 0.75,
                handle: '.block-title',
                placeholder: 'draggable-placeholder',
                tolerance: 'pointer',
                start: function(e, ui){
                    ui.placeholder.css('height', ui.item.outerHeight());
                }
            });

            $('.draggable-tbody').sortable({
                tolerance: 'pointer',
                stop: function (event, ui) {
                    /*con esto validamos si colocamos una unidad mayor encima de otra*/
                    var actual_unidad=$("#unidad0").val()
                    var error=false;
                    var esto= $( this );
                    $("#unidadescontainer input[id^='unidad']").each(function (index) {

                        if(parseInt($(this).val())>=parseInt(actual_unidad) && index!=0){
                            esto.sortable( "cancel" );
                            $.bootstrapGrowl('<h4>Las unidades deben ir ordenadas de mayor a menor</h4>', {
                                type: 'warning',
                                delay: 2500,
                                allow_dismiss: true
                            });
                            error=true;
                            return false;
                        }else{
                                actual_unidad=$(this).val();

                        }

                    });

                },
                start: function(e, ui){
                    ui.placeholder.css('height', ui.item.outerHeight());
                },
               // handle: '.fa-arrows-v',
                update: function( event, ui ) {


                        var count = 0;
                        $("tr[id^='trunidad']").each(function () {
                            $(this).attr('id', 'trunidad' + count);

                            $("#trunidad" + count + " select[name^='medida']").attr('name', 'medida[' + count + ']');
                            $("#trunidad" + count + " select[name^='medida']").attr('id', 'medida' + count + '');

                            $("#trunidad" + count + " input[name^='unidad']").attr('name', 'unidad[' + count + ']');
                            $("#trunidad" + count + " input[name^='unidad']").attr('id', 'unidad' + count + '');

                            var countprecio = 0;
                            $("#trunidad" + count + " input[name^='precio_id_']").each(function () {
                                $(this).attr('name', 'precio_id_' + count + '[' + countprecio + ']');
                                countprecio++;
                            });
                            $("#trunidad" + count + " input[name^='precio_id_']").attr('id', 'precio_id_' + count);

                            var countprecio = 0;
                            $("#trunidad" + count + " input[name^='precio_valor_']").each(function () {
                                $(this).attr('name', 'precio_valor_' + count + '[' + countprecio + ']');
                                $(this).attr('onkeyup', 'calcular_utilidad("' + count + '","' + countprecio + '")');
                                $(this).attr('id', 'precio_valor_' + count + '_' + countprecio + '');
                                countprecio++;
                            })


                            var countprecio = 0;
                            $("#trunidad" + count + " input[name^='utilidad_']").each(function () {
                                $(this).attr('name', 'utilidad_' + count + '[' + countprecio + ']');
                                $(this).attr('id', 'utilidad_' + count + '_' + countprecio + '');
                                $(this).attr('onkeyup', 'calcular_precio("' + count + '","' + countprecio + '")');
                                countprecio++;
                            });


                            $("#trunidad" + count + " a[id^='eliminar']").attr('id', 'eliminar' + count);
                            $("#trunidad" + count + " a[id^='eliminar']").attr('onclick', 'eliminarunidad(' + count + ')');

                            count++;
                        })

                }
            });
        }
    };
}();