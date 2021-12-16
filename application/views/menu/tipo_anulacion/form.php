<form name="formagregar" action="<?= base_url() ?>tipo_anulacion/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo tipo de anulacion</h4>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="form-group">
                        <input type="hidden" name="tipo_anulacion_id" id="tipo_anulacion_id" required="true"
                               value="<?php if (isset($tipo['tipo_anulacion_id'])) echo $tipo['tipo_anulacion_id']; ?>">

                        <div class="col-md-3">
                            Nombre
                        </div>
                        <div class="col-md-9"><input type="text" name="tipo_anulacion_nombre" id="tipo_anulacion_nombre"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($tipo['tipo_anulacion_nombre'])) echo $tipo['tipo_anulacion_nombre']; ?>">
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

