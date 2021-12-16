<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="EMPRESA_NOMBRE" value="<?= $this->session->userdata('EMPRESA_NOMBRE'); ?>">
<input type="hidden" id="REGIMEN_CONTRIBUTIVO" value="<?= $this->session->userdata('REGIMEN_CONTRIBUTIVO'); ?>">
<input type="hidden" id="EMPRESA_DIRECCION" value="<?= $this->session->userdata('EMPRESA_DIRECCION'); ?>">
<input type="hidden" id="EMPRESA_TELEFONO" value="<?= $this->session->userdata('EMPRESA_TELEFONO'); ?>">
<input type="hidden" id="NIT" value="<?= $this->session->userdata('NIT'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3>Cierre de Caja</h3>
        </div>
        <form id="frmCierreCaja" class='validate form-horizontal' target="_blank" method="post"
              action="#">
            <div class="modal-body">
                <fieldset>

                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Caja:</label>


                        <div class="controls">

                            <?php
                            if (count($cajas_abiertas) > 0) {
                                foreach ($cajas_abiertas as $caja) {

                                    if ($caja['id'] == $this->session->userdata('cajapertura')) {
                                        echo $caja['alias'];
                                        ?>
                                        <input type="hidden" id="caja_id" name="caja_id"
                                                           value="<?php echo $caja['caja_id']?>">

                                        <input type = "hidden" name="id" id="id"  value="<?php echo $caja['id']?>">
                                               <?php
                                    }

                                }
                            }
                            ?>
                            <!--<select name="id" id="id" class="form-control">
                                <?php
                            if (count($cajas_abiertas) > 0) {
                                foreach ($cajas_abiertas as $caja) {
                                }
                                ?>
                                    <option value="<?= $caja['id'] ?>"><?= $caja['alias'] ?></option>
                                    <?php
                            }
                            ?>
                            </select>-->
                            <br>
                            <br>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Cajero:</label>

                        <div class="controls">
                            <input type="hidden" name="cajero" id="cajero"
                                   value="<?php echo $this->session->userdata('nUsuCodigo') ?>">
                            <?php echo $this->session->userdata('username') ?>
                            <!--<select name="cajero" id="cajero" class="form-control">
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
                            <input type="text" name="fecha" id="fecha" readonly value="<?php echo date('d-m-yy') ?>"
                                   class='input-small input-datepicker form-control'
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha" class="control-label">Hora:</label>

                        <div class="controls">
                            <input type="text" name="hora" id="hora" readonly value="<?php echo date('H:i:s') ?>"
                                   class='input-small input-datepicker form-control'
                            >
                        </div>
                    </div>
                    <input name="operacion" type="hidden" value="CIERRE">
                    <div class="col-md-6">
                        <label for="fecha" class="control-label">Valor cierre:</label>

                        <div class="controls">
                            <input type="text" name="monto" id="monto" value="0"
                                <?= $this->session->userdata('PEDIR_VALOR_CIERRE_CAJA')=="NO"? "readonly" :"" ?>
                                   class='form-control'>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="fecha" class="control-label">Observacion:</label>

                        <div class="controls">
                            <textarea class="form-control" name="observacion_cierre" id="observacion_cierre"></textarea>
                        </div>
                    </div>

                </fieldset>
            </div>
            <div class="modal-footer">
                <input type="button" id="submitaperuracaja"
                       onclick="StatusCaja.guardar('CIERRE','frmCierreCaja','cierre_caja')" value="Cerrar Caja"
                       class="btn btn-primary">
                <a href="#" class="btn btn-danger" data-dismiss="modal">Salir</a>
            </div>
        </form>
    </div>
</div>