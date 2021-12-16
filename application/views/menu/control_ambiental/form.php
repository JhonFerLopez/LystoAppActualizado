<form name="formagregar" action="<?= base_url() ?>control_ambiental/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=count($control)>0?"Editar Control Ambiental":"Nuevo Control Ambiental" ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-2">
                        Mes
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="mes" id="mes" required="true" class="form-control"
                                                  value="<?php if (isset($control['control_ambiental_id'])) echo date("m-Y",strtotime($control['periodo'])); ?>">
                    </div>
                    <input type="hidden" name="id" id="" required="true"
                           value="<?php if (isset($control['control_ambiental_id'])) echo $control['control_ambiental_id']; ?>">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="ControlAmbiental.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</form>


<script>
    $(function(){

        $("#mes").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months"
            });
    })
</script>