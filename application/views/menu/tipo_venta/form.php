<form name="formagregar" action="<?= base_url() ?>tipo_venta/guardar" method="POST" id="formagregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo tipo de venta</h4>
            </div>
            <div class="modal-body">


                <div class="row">

                    <div class="form-group">
                        <input type="hidden" name="tipo_venta_id" id="tipo_venta_id" required="true"
                               value="<?php if (isset($tipo['tipo_venta_id'])) echo $tipo['tipo_venta_id']; ?>">

                        <div class="col-md-3">
                            Nombre
                        </div>
                        <div class="col-md-9"><input type="text" name="tipo_venta_nombre" id="tipo_venta_nombre"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($tipo['tipo_venta_nombre'])) echo $tipo['tipo_venta_nombre']; ?>">
                        </div>


                    </div>


                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Solicita código del vendedor
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="solicita_cod_vendedor"
                                                     id="solicita_cod_vendedor"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['solicita_cod_vendedor']) && $tipo['solicita_cod_vendedor'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

              <!--  <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Genera datos para cartera
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="genera_datos_cartera"
                                                     id="genera_datos_cartera"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['genera_datos_cartera']) && $tipo['genera_datos_cartera'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>-->

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Admite datos de clientes
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="admite_datos_cliente"
                                                     id="admite_datos_cliente"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['admite_datos_cliente']) && $tipo['admite_datos_cliente'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Permite modificar datos cliente
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="datos_adic_clientes" id="datos_adic_clientes"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['datos_adic_clientes']) && $tipo['datos_adic_clientes'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Genera control de domicilios
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="genera_control_domicilios"
                                                     id="genera_control_domicilios"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['genera_control_domicilios']) && $tipo['genera_control_domicilios'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Maneja formas de pago
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="maneja_formas_pago" id="maneja_formas_pago"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['maneja_formas_pago']) && $tipo['maneja_formas_pago'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <!--<div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Liquida IVA
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="liquida_iva" id="liquida_iva"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['liquida_iva']) && $tipo['liquida_iva'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>-->
                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Maneja descuentos
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="maneja_descuentos" id="maneja_descuentos"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['maneja_descuentos']) && $tipo['maneja_descuentos'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Maneja tipos de impresión
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="maneja_impresion" id="maneja_impresion"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['maneja_impresion']) && $tipo['maneja_impresion'] == '1') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Aproximar precio a:
                        </div>
                        <div class="col-md-7"><input type="text" name="aproximar_precio" id="aproximar_precio"  onkeydown="return soloNumeros(event);"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($tipo['aproximar_precio'])) echo $tipo['aproximar_precio']; ?>">
                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-5">
                            Condición de pago:
                        </div>
                        <div class="col-md-7">
                            <select name="condicionpago" id="condicionpago" required="true"
                                    class="form-control">

                                <option value="">Seleccione</option>

                                    <?php foreach ($condiciones as $condicion): ?>
                                        <option
                                            value="<?php echo $condicion['id_condiciones'] ?>" <?php if (isset($tipo['condicion_pago']) and $tipo['condicion_pago'] == $condicion['id_condiciones']) echo 'selected' ?>><?= $condicion['nombre_condiciones'] ?></option>
                                    <?php endforeach ?>


                            </select>

                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-5">
                            Generar:
                        </div>
                        <div class="col-md-7">
                            <select name="documento_generar" id="documento_generar" required="true"
                                    class="form-control">

                                <option
                                    value="FACTURA" <?php if (isset($tipo['documento_generar']) and $tipo['documento_generar'] == 'FACTURA') echo 'selected' ?>>
                                    FACTURA
                                </option>
                                <!--<option
                                    value="REMISION" <?php if (isset($tipo['documento_generar']) and $tipo['documento_generar'] == 'REMISION') echo 'selected' ?>>
                                    REMISION
                                </option>-->

                            </select>

                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                           Número de copias a imprimir
                        </div>
                        <div class="col-md-7"><input type="text" name="numero_copias" id="numero_copias"
                                                     required="true"
                                                     class="form-control"
                                                     value="<?php if (isset($tipo['numero_copias'])) echo $tipo['numero_copias']; ?>"   onkeydown="return soloNumeros(event);">
                        </div>


                    </div>
                </div>

                <div class="row">


                    <div class="form-group">
                        <div class="col-md-5">
                            Activa opciones de call center
                        </div>
                        <div class="col-md-7"><input type="checkbox" name="opciones_call_center" id="opciones_call_center"
                                                     required="true"
                                                     class="checkbox"
                                <?php if (isset($tipo['opciones_call_center']) && $tipo['opciones_call_center'] == '1') echo 'checked'; ?>>
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

