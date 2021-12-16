<div class="modal-dialog" style="width: 60%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Configurar horas de Notificaci&oacute;n </h4>
        </div>
        <div class="modal-body">
            <form name="formguardardetalle" action="<?= base_url() ?>control_ambiental/guardarhrsNotif" method="post"
                  id="formguardardetalle">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive  " style="position: relative;">
                            <table
                                    class="table table-striped table-bordered"
                                    id="tabladetallecontrol">

                                <thead class="">
                                <tr>
                                    <th style="padding-top: 0px; padding-bottom: 0px "></th>
                                    <th style="padding-top: 0px; padding-bottom: 0px ">Temperatura, Humedad y Cadena de Fr&iacute;o</th>

                                </tr>

                                </thead>
                                <tbody class="">
                                <tr>
                                <td
                                        style="padding-top: 0px; padding-bottom: 0px"
                                        class=''>AM
                                </td>
                                <?php
                                if (sizeof($horasnot) > 0) {
                                    $cont=0;
                                    foreach ($horasnot as $row) {
                                        $cont++;
                                        if($cont>1) continue;
                                        ?>

                                        <td id="<?= $row->alias ?>"
                                            style="padding-top: 0px; padding-bottom: 0px">
                                            Hora:<input type="number" class="form-control" name="hora_am" maxlength="2" min="0"  max="12"
                                                        value="<?= str_pad($row->hora, 2, "0", STR_PAD_LEFT)  ?>"
                                                        id="hora<?= $row->alias ?>" >
                                            Minuto:<input type="number" class="form-control" name="minuto_am" maxlength="2" min="0"  max="59"
                                                          value="<?= str_pad($row->minutos, 2, "0", STR_PAD_LEFT)  ?>"
                                                          id="hora<?= $row->alias ?>" >
                                        </td>
                                    <?php

                                    }
                                }else{ ?>
                                    <td class=''></td>
                               <?php } ?>
                                </tr>
                                <tr>
                                    <td
                                            style="padding-top: 0px; padding-bottom: 0px"
                                            class=''>PM
                                    </td>
                                    <?php
                                    if (sizeof($horasnot) > 0) {
                                        $cont=0;

                                        foreach ($horasnot as $row) {
                                            $cont++;
                                            if($cont<3 || $cont>3) continue;
                                            ?>
                                            <td id="<?= $row->alias ?>"
                                                style="padding-top: 0px; padding-bottom: 0px"
                                                class=''>
                                                Hora:<input type="number" class="form-control" name="hora_pm" maxlength="2" min="0" max="12"
                                                            value="<?= str_pad($row->hora, 2, "0", STR_PAD_LEFT)  ?>" id="hora<?= $row->alias ?>" >
                                                Minuto:<input type="number" class="form-control" name="minuto_pm" maxlength="2" min="0"  max="59"
                                                              value="<?= str_pad($row->minutos, 2, "0", STR_PAD_LEFT)  ?>" id="hora<?= $row->alias ?>" >
                                            </td>
                                        <?php    }
                                    }else{ ?>
                                        <td class=''></td>
                                  <?php  }
                                    ?>

                                </tr>
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
        var columnDefs = [{width: '2%', targets: 0}]
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
            "bStateSave": false,
            "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],
            "language": DatatablesSettings.language,
            "fnInitComplete": function (data, json) {
                setTimeout(function () {
                    $('div.dataTables_filter input').focus();
                }, 5);
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();

                var i = 0;
                var sumar = false;
                api.columns().every(function (index) {
                    // console.log('index',index)

                    decimales = 2;
                    /*si tiene el atributo sumcuantosdecimales, se le dice cuantos decimales va a sumar, por defecto esta en 2,
                     * puede venir en 0 para que no los sume */
                    sumar = false;
                    /*en lista de ventas, y me imagino que en algunas partes mas, no hace falta sumar algunas filas, y menos
                     * si son textos, por lo tanto, en el <td> de cada registro, se debe indicar con un atributo data:
                     * data-sumar="true" para saber si aqui se va a sumar o no*/

                    api.column(index).nodes().toArray().map(function (node) {
                        if ($(node).attr('data-sumar') != undefined && $(node).attr('data-sumar') == "true") {
                            sumar = true;
                        }

                        if ($(node).attr('data-sumcuantosdecimales') != undefined) {
                            decimales = $(node).attr('data-sumcuantosdecimales');
                        }
                    });

                    if (i > 0 && sumar == true) {

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

    })
</script>
