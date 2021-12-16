<form name="formagregar" action="<?= base_url() ?>metodosdepago/guardar" method="post" id="formagregar">

    <input type="hidden" name="id" id="" required="true"
           value="<?php if (isset($metodospago['id_metodo'])) echo $metodospago['id_metodo']; ?>">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo M&eacute;todo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Nombre</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="nombre_metodo" id="nombre_metodo" required="true"
                                   class="form-control"
                                   value="<?php if (isset($metodospago['nombre_metodo'])) echo $metodospago['nombre_metodo']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>ID (DIAN)</label>
                        </div>
                        <select class="form-control" id="fe_method_id" name="fe_method_id">

                            <option value="">Seleccione</option>
                            <?php


                            foreach ($fe_methods as $fe_impuesto) {
                                ?>

                                <option <?php if (isset($metodospago) && $fe_impuesto->id == $metodospago['fe_method_id']) echo 'selected' ?>
                                        value="<?= $fe_impuesto->id ?>"><?= $fe_impuesto->name ?></option>

                                <?php
                            } ?>
                        </select>

                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-11">
                            Suma total efectivo<br>

                            <p class="text-info">Indica que esta forma de pago se toma como EFECTIVO para el cuadre de
                                caja</p>
                        </div>
                        <div class="col-md-1"><input type="checkbox" name="suma_total_ingreso" id="suma_total_ingreso"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($metodospago['suma_total_ingreso']) && $metodospago['suma_total_ingreso'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-11">

                            Incluye en cuadre <br>

                            <p class="text-info">Indica si se muestra en el cuadre de caja o no</p>
                        </div>
                        <div class="col-md-1"><input type="checkbox" name="incluye_cuadre_caja" id="incluye_cuadre_caja"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($metodospago['incluye_cuadre_caja']) && $metodospago['incluye_cuadre_caja'] == '1') echo 'checked'; ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-11">
                            Centros/Bancos<br>
                            <p class="text-info">Nombre, Documentos, Centro, Numero adicional. Son utilizados para que
                                usted decida si el usuario los debe seleccionar al momento de seleccionar esta forma de
                                pago</p>
                        </div>
                        <div class="col-md-1"><input type="checkbox" name="centros_bancos" id="centros_bancos"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($metodospago['centros_bancos']) && $metodospago['centros_bancos'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>


            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="marca.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
            <!-- /.modal-content -->
        </div>
</form>