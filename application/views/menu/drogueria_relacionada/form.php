<form name="formagregar" action="<?= base_url() ?>drogueria_relacionada/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nueva drogueria relacionada</h4>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="form-group">
                        <div class="col-md-6">
                            Nombre de la drogueria
                        </div>
                        <div class="col-md-6"><input type="text" name="drogueria_nombre" id="drogueria_nombre"

                                                     required="true" class="form-control"
                                                     value="<?php if (isset($drogueria['drogueria_nombre'])) echo $drogueria['drogueria_nombre']; ?>">
                        </div>

                        <input type="hidden" name="drogueria_id" id="drogueria_id" required="true"
                               value="<?php if (isset($drogueria['drogueria_id'])) echo $drogueria['drogueria_id']; ?>">

                        <div class="col-md-6">
                            URL de la drogueria (Dirección IP o dominio)
                        </div>
                        <div class="col-md-6"><input type="text" name="drogueria_domain" id="drogueria_domain" PLACEHOLDER="http://ejemplo.com/"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($drogueria['drogueria_domain'])) echo $drogueria['drogueria_domain']; ?>">
                        </div>


                    </div>


                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="guardar" onclick="DrogueriaRelacionada.save()">
                    Confirmar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</form>


<script>$(function () {

        DrogueriaRelacionada.init();
    });</script>