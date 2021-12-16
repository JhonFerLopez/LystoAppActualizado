<form name="formagregar" action="<?= base_url() ?>resolucion_dian/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva resolución</h4>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="form-group">
                        <input type="hidden" name="resolucion_id" id="resolucion_id" required="true"
                               value="<?php if (isset($tipo['resolucion_id'])) echo $tipo['resolucion_id']; ?>">

                        <div class="col-md-3">
                            Número de la resolución
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_numero" id="resolucion_numero"
                                                     required="true"
                                                     class="form-control" onkeydown="return soloNumeros(event);"
                                                     value="<?php if (isset($tipo['resolucion_numero'])) echo $tipo['resolucion_numero']; ?>">
                        </div>


                    </div>


                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Prefijo de la autorización
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_prefijo" id="resolucion_prefijo"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($tipo['resolucion_prefijo'])) echo $tipo['resolucion_prefijo']; ?>">
                        </div>

                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Número inicial de la factura
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_numero_inicial"
                                                     id="resolucion_numero_inicial"
                                                     required="true"
                                                     class="form-control" onkeydown="return soloNumeros(event);"
                                                     value="<?php if (isset($tipo['resolucion_numero_inicial'])) echo $tipo['resolucion_numero_inicial']; ?>">
                        </div>

                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Número final de la factura
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_numero_final"
                                                     id="resolucion_numero_final"
                                                     required="true"
                                                     class="form-control" onkeydown="return soloNumeros(event);"
                                                     value="<?php if (isset($tipo['resolucion_numero_final'])) echo $tipo['resolucion_numero_final']; ?>">
                        </div>

                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Fecha de aprobación
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_fech_aprobacion"
                                                     id="resolucion_fech_aprobacion"
                                                     required="true"
                                                     class="form-control datepicker"
                                                     value="<?php if (isset($tipo['resolucion_fech_aprobacion'])) echo $tipo['resolucion_fech_aprobacion']; ?>">
                        </div>

                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Fecha de vencimiento
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_fech_vencimiento"
                                                     id="resolucion_fech_vencimiento"
                                                     required="true"
                                                     class="form-control datepicker"
                                                     value="<?php if (isset($tipo['resolucion_fech_vencimiento'])) echo $tipo['resolucion_fech_vencimiento']; ?>">
                        </div>

                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-3">
                            Avisar cuando llegue a:
                        </div>
                        <div class="col-md-9"><input type="text" name="resolucion_avisar" id="resolucion_avisar"
                                                     required="true"
                                                     class="form-control" onkeydown="return soloNumeros(event);"
                                                     value="<?php if (isset($tipo['resolucion_avisar'])) echo $tipo['resolucion_avisar']; ?>">
                        </div>

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="guardar" onclick="objeto.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>

</form>

<script>
    $(document).ready(function () {

        $("#resolucion_fech_aprobacion").datepicker({weekStart: 1, format: 'yyyy-mm-dd'});
        $("#resolucion_fech_vencimiento").datepicker({weekStart: 1, format: 'yyyy-mm-dd'});


    });

</script>