<form name="formagregar" action="<?= base_url() ?>condicionespago/guardar" method="post" id="formagregar">

    <input type="hidden" name="id" id="" required="true" value="<?php if (isset($condicionespago['id_condiciones'])) echo $condicionespago['id_condiciones']; ?>">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva Condicion</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Nombre</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nombre_condiciones" id="nombre_condiciones" required="true" class="form-control" value="<?php if (isset($condicionespago['nombre_condiciones'])) echo $condicionespago['nombre_condiciones']; ?>" <?php if (isset($condicionespago['nombre_condiciones']) and (strtolower($condicionespago['nombre_condiciones']) == 'contado' or strtolower($condicionespago['nombre_condiciones']) == 'credito')) echo 'readonly'; ?>>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Dias</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" size="3" name="dias" id="dias" required="true" <?php if (isset($condicionespago['nombre_condiciones']) and (strtolower($condicionespago['nombre_condiciones']) == 'contado' or strtolower($condicionespago['nombre_condiciones']) == 'credito')) echo 'readonly'; ?> class="form-control" value="<?php if (isset($condicionespago['dias'])) echo $condicionespago['dias']; ?>">
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Se usa para ofertas</label>
                        </div>
                        <div class="col-md-10">
                            <input type="checkbox" size="3" name="is_offer" id="is_offer"
                             <?php if (isset($condicionespago['is_offer']) and (strtolower($condicionespago['is_offer']) == '1' )) echo 'checked'; ?> 
                            class="form-control" value="1">
                        </div>

                    </div>

                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>ID (DIAN)</label>
                        </div>
                        <select class="form-control" id="fe_payment_form_id" name="fe_payment_form_id">

                            <option value="">Seleccione</option>
                            <?php


                            foreach ($fe_forms as $fe_impuesto) {
                            ?>

                                <option <?php if (isset($condicionespago) && $fe_impuesto->id == $condicionespago['fe_payment_form_id']) echo 'selected' ?> value="<?= $fe_impuesto->id ?>"><?= $fe_impuesto->name ?></option>

                            <?php
                            } ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
            <!-- /.modal-content -->
        </div>
</form>