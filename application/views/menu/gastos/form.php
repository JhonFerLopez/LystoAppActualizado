<form name="formagregar" action="<?= base_url() ?>gastos/guardar" method="post" id="formagregar">

    <input type="hidden" name="id" id="" required="true" value="<?php if (isset($gastos['id_gastos'])) echo $gastos['id_gastos']; ?>">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo Gasto</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Fecha en la que se realizó el gasto </label>
                            <small>(Esta NO es la fecha de registro sino la fecha que aparece en la factura del gasto)</small>
                        </div>
                        <div class="col-md-6">
                            <?php $MODIFICAR_FECHA_GASTOS = $this->session->userdata('MODIFICAR_FECHA_GASTOS');

                          ?>
                            <input type="text" name="fecha" id="fecha" required="true" <?php if ($MODIFICAR_FECHA_GASTOS == "NO") echo "readonly"; ?>  class="input-small input-datepicker form-control" value="<?php if (isset($gastos['fecha'])) {
                                                                                                                                                                                                                echo $gastos['fecha'];
                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                echo date('d-m-yy');
                                                                                                                                                                                                            } ?>" />


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Descripci&oacute;n</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="descripcion" id="descripcion" required="true" class="form-control" value="<?php if (isset($gastos['descripcion'])) echo $gastos['descripcion']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Total</label>
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="total" id="total" required="true" class="form-control" value="<?php if (isset($gastos['total'])) echo $gastos['total']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Tipo de Gasto</label>
                        </div>
                        <div class="col-md-6">

                            <select name="tipo_gasto" id="tipo_gasto" required="true" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($tiposdegasto as $gasto) : ?>
                                    <option value="<?php echo $gasto['id_tipos_gasto'] ?>" <?php if (isset($gastos['tipo_gasto']) and $gastos['tipo_gasto'] == $gasto['id_tipos_gasto']) echo 'selected' ?>><?= $gasto['nombre_tipos_gasto'] ?></option>
                                <?php endforeach ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Tienda/Bodega</label>
                        </div>
                        <div class="col-md-6">

                            <select name="local_id" id="local_id" required="true" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($local as $local) : ?>
                                    <option value="<?php echo $local['int_local_id'] ?>" <?php if (isset($gastos['local_id']) and $gastos['local_id'] == $local['int_local_id']) echo 'selected' ?>><?= $local['local_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="Gasto.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
            <!-- /.modal-content -->
        </div>
</form>
<script>
    <?php if($MODIFICAR_FECHA_GASTOS!='NO') {?>
    $("#fecha").datepicker({
        format: 'dd-mm-yyyy'
    });
<?php }?>
</script>