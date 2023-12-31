
<form name="formagregar" id="formagregar" action="<?= base_url() ?>grupo/guardarnivel" method="post">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo Nivel</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            Nombre
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nombre" id="nombre" required="true" class="form-control"
                                   value="<?php if (isset($nivel['nombre_nivel'])) echo $nivel['nombre_nivel']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            Nivel
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="nivel" id="nivel" required="true" class="form-control" min="1"
                                   value="<?php if (isset($nivel['nivel'])) echo $nivel['nivel']; ?>">

                        </div>

                        <input type="hidden" name="id" id="" required="true"
                               value="<?php if (isset($nivel['nivel_id'])) echo $nivel['nivel_id']; ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="gruponivel.guardar()" >Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>

            <!-- /.modal-content -->
        </div>
</form>