<form name="formagregar" action="<?= base_url() ?>clasificacion/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva Clasificación</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-2">
                        Nombre
                    </div>
                    <div class="col-md-10"><input type="text" name="nombre" id="nombre" required="true" class="form-control"
                                                  value="<?php if (isset($clasificacion['clasificacion_nombre'])) echo $clasificacion['clasificacion_nombre']; ?>">
                    </div>

                    <input type="hidden" name="id" id="" required="true"
                           value="<?php if (isset($clasificacion['clasificacion_id'])) echo $clasificacion['clasificacion_id']; ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="marca.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</form>