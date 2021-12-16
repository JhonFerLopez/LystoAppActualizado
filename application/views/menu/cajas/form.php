<form name="formagregar" action="<?= base_url() ?>cajas/guardar" method="post" id="formagregar">


    <input type="hidden" name="id" id="" required="true"
           value="<?php if (isset($cajas['caja_id'])) echo $cajas['caja_id']; ?>">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva Caja</h4>
            </div>

            <div class="modal-body">


                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Nombre</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="alias" id="alias" required="true"
                                   class="form-control"
                                   value="<?php if (isset($cajas['alias'])) echo $cajas['alias']; ?>">
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar()" >Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
            <!-- /.modal-content -->
    </div>

</form>

<script>
    $('.selectpicker').selectpicker();
</script>




