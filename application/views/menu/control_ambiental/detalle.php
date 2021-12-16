
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Control
                    Ambiental <?= sizeof($control) > 0 ? date("m-Y", strtotime($control['periodo'])) : '' ?> </h4>
            </div>
            <div class="modal-body">
                <form name="formguardardetalle" action="<?= base_url() ?>control_ambiental/guardardetalle" method="post"
                      id="formguardardetalle">

                         <input type="hidden" name="control_id"
                                value="<?= isset($control['control_ambiental_id'])?$control['control_ambiental_id']:'' ?>">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive  " style="position: relative;">
                                <table
                                        class="table table-striped dataTable table-bordered table-hover "
                                        id="tabladetallecontrol">

                                    <thead class="">
                                    <tr>
                                        <th style="padding-top: 0px; padding-bottom: 0px ">DIA</th>
										<th style="padding-top: 0px; padding-bottom: 0px ">TEMPERATURA °C AMBIENTAL AM</th>
                                        <th style="padding-top: 0px; padding-bottom: 0px ">HUMEDAD RELATIVA AM</th>
										<th style="padding-top: 0px; padding-bottom: 0px ">CADENA DE FRÍO AM</th>

                                        <th style="padding-top: 0px; padding-bottom: 0px ">TEMPERATURA °C AMBIENTAL PM</th>
										<th style="padding-top: 0px; padding-bottom: 0px ">HUMEDAD RELATIVA PM</th>
										<th style="padding-top: 0px; padding-bottom: 0px ">CADENA DE FRÍO PM</th>
                                    </tr>

                                    </thead>
                                    <tbody class="">

                                    <?php
                                    if (sizeof($control) > 0) {

                                        //obtengo el numero de dias que tiene el mes del control ambiental
                                        $número = cal_days_in_month(CAL_GREGORIAN,
                                            date("m", strtotime($control['periodo'])), date("Y", strtotime($control['periodo'])));


                                        for ($i = 1; $i <= $número; $i++) {

                                            $humedadAM = '';
                                            $tempambientalAM = '';
											$cadenaFrioAM='';
                                            $humedadPM = '';
                                            $tempambientalPM = '';
											$cadenaFrioPM='';
                                            if (count($detalle) > 0) {

                                                foreach ($detalle as $row) {

                                                    if ($row->dia == $i) {
                                                        $humedadAM = $row->humedad_relat_am;
                                                        $tempambientalAM = $row->temp_ambiental_am;
														$cadenaFrioAM = $row->cadena_frio_am;

                                                        $humedadPM = $row->humedad_relat_pm;
                                                        $tempambientalPM = $row->temp_ambiental_pm;
														$cadenaFrioPM = $row->cadena_frio_pm;

                                                    }
                                                }
                                            }
                                            ?>

                                            <tr id="tr_<?= $i ?>">
                                                <td id="td_dia<?= $i ?>"><?= $i ?></td>

												<td id="td_tempambientalAM<?= $i ?>"
													style="padding-top: 0px; padding-bottom: 0px" class=''>
													<input type="text" id="input_tempambientalAM<?= $i ?>"
														   class="form-control" style="width: 100% !important;"
														   name="input_tempambientalAM<?= $i ?>"
														   value="<?= $tempambientalAM!=null && $tempambientalAM!=''? number_format($tempambientalAM):'' ?>">
												</td>
                                                <td id="td_humedadAM<?= $i ?>"
                                                    style="padding-top: 0px; padding-bottom: 0px"
                                                    class=''>
                                                    <input type="text" id="input_humedadAM<?= $i ?>"
                                                           class="form-control"
                                                           name="input_humedadAM<?= $i ?>" style="width: 100% !important;"
                                                           value="<?= $humedadAM!=null && $humedadAM!=''? number_format($humedadAM):'' ?>">
                                                </td>
												<td id="td_cadenaFrioAM<?= $i ?>"
													style="padding-top: 0px; padding-bottom: 0px"
													class=''>
													<input type="text" id="input_cadenafrioAM<?= $i ?>"
														   class="form-control"
														   name="input_cadenafrioAM<?= $i ?>" style="width: 100% !important;"
														   value="<?= $cadenaFrioAM!=null && $cadenaFrioAM!=''? number_format($cadenaFrioAM):'' ?>">
												</td>

												<!-- empiezo PM -->

												<td id="td_tempambientalPM<?= $i ?>"
													style="padding-top: 0px; padding-bottom: 0px" class=''>
													<input type="text" id="input_tempambientalPM<?= $i ?>"
														   name="input_tempambientalPM<?= $i ?>"
														   class="form-control" style="width: 100% !important;"
														   value="<?= $tempambientalPM!=null && $tempambientalPM!=''? number_format($tempambientalPM):'' ?>">
												</td>

                                                <td id="td_humedadPM<?= $i ?>"
                                                    style="padding-top: 0px; padding-bottom: 0px"
                                                    class=''>
                                                    <input type="text" id="input_humedadPM<?= $i ?>"
                                                           class="form-control" style="width: 100% !important;"
                                                           name="input_humedadPM<?= $i ?>"
                                                           value="<?= $humedadPM!=null && $humedadPM!=''? number_format($humedadPM):'' ?>">
                                                </td>

												<td id="td_cadenaFrioAM<?= $i ?>"
													style="padding-top: 0px; padding-bottom: 0px"
													class=''>
													<input type="text" id="input_cadenafrioPM<?= $i ?>"
														   class="form-control"
														   name="input_cadenafrioPM<?= $i ?>" style="width: 100% !important;"
														   value="<?= $cadenaFrioPM!=null && $cadenaFrioPM!=''? number_format($cadenaFrioPM):'' ?>">
												</td>
                                            </tr>

                                            <?php

                                        }
                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="ControlAmbiental.guardarDetalle()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>



<script>
    var tabla = '';
    $(function () {
        var botones_mstrar = DatatablesSettings.buttons;
        var columnDefs=[{width: '100%', targets: 0}]
        tabla = $("#tabladetallecontrol").DataTable({
            keys: true,
            "searching": false,
            "ordering": false,
            "bPaginate": false,
            fixedHeader: {
                header: true,
                footer: true
            },
            scrollCollapse: true,
            paging: false,
            info: false,

            retrieve: true,
            columnDefs: columnDefs,
            "iDisplayLength": 20,
            fixedColumns: false,
            "bStateSave":false,
            "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],

            "dom": DatatablesSettings.dom,
            buttons: botones_mstrar,
            "language": DatatablesSettings.language,
            "fnInitComplete": function (data,json) {


                setTimeout(function () {
                    $('div.dataTables_filter input').focus();
                }, 5);


            },

            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();

                var i = 0;
                var sumar=false;
                api.columns().every(function (index) {
                    // console.log('index',index)

                    decimales=2;
                    /*si tiene el atributo sumcuantosdecimales, se le dice cuantos decimales va a sumar, por defecto esta en 2,
                     * puede venir en 0 para que no los sume */
                    sumar=false;
                    /*en lista de ventas, y me imagino que en algunas partes mas, no hace falta sumar algunas filas, y menos
                     * si son textos, por lo tanto, en el <td> de cada registro, se debe indicar con un atributo data:
                     * data-sumar="true" para saber si aqui se va a sumar o no*/

                    api.column(index).nodes().toArray().map(function(node) {
                        if($(node).attr('data-sumar')!=undefined && $(node).attr('data-sumar')=="true"){
                            sumar=true;
                        }

                        if($(node).attr('data-sumcuantosdecimales')!=undefined){
                            decimales=$(node).attr('data-sumcuantosdecimales');
                        }
                    });

                    if (i > 0 && sumar==true) {

                        var sum = this
                            .data()
                            .reduce(function (a, b) {

                                var x = parseFloat(a) || 0;

                                if (isNaN(x)) {
                                    x = 0;
                                }
                                //   console.log(x);
                                // console.log(b);
                                if (b != null) {
                                    if (isNaN(b)) {
                                        b = b.replace(/[,.]/g, function (m) {
                                            // m is the match found in the string
                                            // If `,` is matched return `.`, if `.` matched return `,`
                                            return m === ',' ? '.' : '';
                                        });
                                    }

                                    // console.log(b);
                                    // b=parseFloat(b.replace('.','').replace(',','.'));
                                    var y = parseFloat(b) || 0;


                                    //  console.log(x + y);
                                    return x + y;
                                }
                            }, 0);

                        // console.log(sum);
                        if (sum != undefined) {

                            $(this.footer()).html(sum.toLocaleString('de-DE', {
                                maximumFractionDigits: decimales,
                                minimumFractionDigits: decimales,
                            }));
                        }

                    }
                    i++;


                });


            }


        });

        tabla.off('key-focus');
        tabla.on('key-focus', function (e, datatable, cell) {

            var rowData = datatable.row(cell.index().row).data();
            var colData = cell.data();
            var objectCell = $($.parseHTML(colData));
            var elemento = $("#" + objectCell.attr('id'));


            console.log(objectCell[0].lastChild)
            if ($("#" + objectCell.attr('id')).length != 0) {

                //esto es para poner el curso al final del input y no al principio
                var strLength = $("#" + objectCell.attr('id')).val().length * 2;
                $("#" + objectCell.attr('id')).focus();
                $("#" + objectCell.attr('id')).select();//esto es para que seleccione todo el valor del campo de texto
                // $("#" + objectCell.attr('id'))[0].setSelectionRange(strLength, strLength);para que lo pongoal final
            } else if (objectCell[0].lastChild != null) {

                //aqui entra en el inputsearch para que le permita colocar el focus
                $("#" + objectCell[0].lastChild.id).focus();
                $("#" + objectCell[0].lastChild.id).select();
            }

            //esto lo hago para que cuandp pase el keyfocus sobre el td busque los datos del producto
            //sobre el cual se esta seleccionado
            var rowNode = datatable.row(cell.index().row).node();


        });
    })
</script>
