<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3>Aperturar Caja</h3>
        </div>
        <form id="frmAperturaCaja" class='validate form-horizontal' target="_blank" method="post"
              action="#">
            <div class="modal-body">
                <fieldset>
                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Caja:</label>

                        <div class="controls">
                            <select name="caja_id" id="caja_id" class="form-control">
                                <option value="">Seleccione</option>
                                <?php
                                if (count($cajas) > 0) {

                                    foreach ($cajas as $caja) {
                                        $mostrar = true;
                                        foreach ($cajas_abiertas as $abierta) {

                                            if ($abierta['caja_id'] == $caja['caja_id']) {
                                                $mostrar = false;
                                            }


                                        }

                                        if ($mostrar == true) {

                                            ?>
                                            <option value="<?= $caja['caja_id'] ?>"><?= $caja['alias'] ?></option>
                                            <?php

                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Cajero:</label>

                        <div class="controls">
                            <input type="hidden" name="cajero" id="cajero"
                                   value="<?php echo $this->session->userdata('nUsuCodigo') ?>">
                            <?php echo $this->session->userdata('username') ?>
                            <!-- <select name="cajero" id="cajero" class="form-control">
                                <?php

                            foreach ($usuarios as $usuario) {

                                ?>
                                    <option <?php if ($this->session->userdata('nUsuCodigo') == $usuario->nUsuCodigo) echo 'selected'; ?>
                                        value="<?= $usuario->nUsuCodigo ?>"><?= $usuario->username ?></option>
                                    <?php
                            }
                            ?>
                            </select>-->

                            <br>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha" class="control-label">Fecha:</label>

                        <div class="controls">
                            <input type="text" name="fecha" id="fecha" readonly value="<?php echo date('d-m-Y') ?>"
                                   class='input-small  form-control'
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha" class="control-label">Hora:</label>

                        <div class="controls">
                            <input type="text" name="hora" id="hora" readonly value="<?php echo date('H:i:s') ?>"
                                   class='input-small  form-control'
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Base:</label>

                        <div class="controls">
                            <input type="text" name="base" id="base" value="0"  onkeydown="return soloDecimal(this, event);"
                                   class='form-control'>
                        </div>
                    </div>
                    <input name="operacion" type="hidden" value="APERTURA">
                    <div class="col-md-12">
                        <label for="fecha" class="control-label">Observacion:</label>

                        <div class="controls">
                            <textarea class="form-control" name="observacion_apertura"
                                      id="observacion_apertura"></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <input type="button" id="submitaperuracaja"
                       onclick="StatusCaja.guardar('APERTURA','frmAperturaCaja','apertura_caja')" value="Aperturar"
                       class="btn btn-success">
                <a href="#" class="btn btn-default" data-dismiss="modal">Salir</a>
            </div>
        </form>
    </div>
</div>