<form name="formagregar" action="<?= base_url() ?>regimen/guardar" method="post" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Datos de régimen contributivo</h4>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="form-group">
                        <input type="hidden" name="regimen_id" id="regimen_id" required="true"
                               value="<?php if (isset($regimen['regimen_id'])) echo $regimen['regimen_id']; ?>">

                        <div class="col-md-3">
                            Nombre
                        </div>
                        <div class="col-md-9"><input type="text" name="regimen_nombre" id="regimen_nombre"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($regimen['regimen_nombre'])) echo $regimen['regimen_nombre']; ?>">
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            En compra le retienen
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="compra_retienen" id="compra_retienen"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['compra_retienen']) && $regimen['compra_retienen'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="form-group">
                        <div class="col-md-5">
                            En compra le retienen iva
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="compra_retienen_iva" id="compra_retienen_iva"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['compra_retienen_iva']) && $regimen['compra_retienen_iva'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            En venta le retienen
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="venta_retienen" id="venta_retienen"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['venta_retienen']) && $regimen['venta_retienen'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            En venta le retienen iva
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="venta_retienen_iva" id="venta_retienen_iva"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['venta_retienen_iva']) && $regimen['venta_retienen_iva'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Calcula iva
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="genera_iva" id="genera_iva"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['genera_iva']) && $regimen['genera_iva'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="form-group">
                        <div class="col-md-5">
                            Autoretenedor
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="autoretenedor" id="autoretenedor"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['autoretenedor']) && $regimen['autoretenedor'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Gran contribuyente
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="gran_contribuyente" id="gran_contribuyente"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($regimen['gran_contribuyente']) && $regimen['gran_contribuyente'] == '1') echo 'checked'; ?>>
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

