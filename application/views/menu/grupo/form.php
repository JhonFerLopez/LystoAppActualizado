
<form name="formagregar" id="formagregar" action="<?= base_url() ?>grupo/guardar" method="post">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo Grupo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="form-group">
                    <div class="col-md-2">
                        Nombre
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="nombre" id="nombre" required="true" class="form-control"
                               value="<?php if (isset($grupo['nombre_grupo'])) echo $grupo['nombre_grupo']; ?>">
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="form-group">

                    <input type="hidden" name="id" id="" required="true"
                           value="<?php if (isset($grupo['id_grupo'])) echo $grupo['id_grupo']; ?>">
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar()" >Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>

            <!-- /.modal-content -->
        </div>
</form>