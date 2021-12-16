<style>
    .row {
        margin-bottom: 10px;
    }

    .pac-container,
    .pac-item {
        z-index: 2147483647 !important;
    }
</style>
<input type="hidden" value="<?= $cliente['estados_id'] ?>" id="estado_id_hidden" />
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">


            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Nuevo Cliente</h4>
        </div>
        <div class="modal-body">

            <form name="formagregar" action="<?php echo base_url(); ?>cliente/guardar" method="post" id="formagregar">
                <input type="hidden" name="id" id="" value="<?php if (isset($cliente['id_cliente'])) echo $cliente['id_cliente']; ?>">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Codigo</label>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="codigo_interno" id="codigo_interno" required="true" class="form-control" value="<?php if (isset($cliente['codigo_interno'])) echo $cliente['codigo_interno']; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Identificación</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="identificacion" id="identificacion" required="true" class="form-control" value="<?php if (isset($cliente['identificacion'])) echo $cliente['identificacion']; ?>">
                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="form-group">

                        <div class="col-md-2">
                            <label>Nombres</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="nombres" id="nombres" required="true" class="form-control" value="<?php if (isset($cliente['nombres'])) echo $cliente['nombres']; ?>">
                        </div>


                        <div class="col-md-2">
                            <label>Apellidos</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="apellidos" id="" class="form-control" value="<?php if (isset($cliente['apellidos'])) echo $cliente['apellidos']; ?>">
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Correo</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($cliente['email'])) echo $cliente['email']; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>Pais</label>
                        </div>
                        <div class="col-md-4">
                            <select name="pais_id" id="id_pais" required="true" class="form-control chosen" onchange="region.actualizarestados();">

                                <?php
                                $p = 1;
                                foreach ($paises as $pais) {
                                    $paais['pais' . $p] = $pais['id_pais'];
                                ?>
                                    <option value="<?php echo $pais['id_pais'] ?>" <?php if (isset($cliente['id_pais']) and $cliente['pais_id'] == $pais['id_pais']) echo 'selected' ?>><?= $pais['nombre_pais'] ?></option>
                                <?php
                                    $p++;
                                } ?>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="form-group">


                        <div class="col-md-2">
                            <label>Departamento</label>
                        </div>
                        <div class="col-md-4">
                            <?php
                            $e = 1;
                            if (isset($paais['pais1'])) {
                            ?>
                                <select name="estados_id" id="estado_id" required="true" class="form-control chosen" onchange="region.actualizardistritos();">

                                    <?php foreach ($estados as $estado) {
                                        $eestado['estado' . $e] = $estado['estados_id'];
                                    ?>
                                        <option value="<?php echo $estado['estados_id'] ?>" <?php if (isset($cliente['estados_id']) and $cliente['estados_id'] == $estado['estados_id']) echo 'selected' ?>><?= $estado['estados_nombre'] ?></option>
                                    <?php $e++;
                                    } ?>

                                </select>
                            <?php
                            } else {
                            ?>
                                <select name="estados_id" id="estado_id" required="true" class="form-control chosen" onchange="region.actualizardistritos();">
                                    <option value="">Seleccione</option>
                                    <?php if (isset($cliente['id_cliente'])) :
                                        $eestado['estado' . $e] = $estado['estados_id'];
                                    ?>
                                        <?php foreach ($estados as $estado) : ?>
                                            <option value="<?php echo $estado['estados_id'] ?>" <?php if (isset($cliente['estados_id']) and $cliente['estados_id'] == $estado['estados_id']) echo 'selected' ?>><?= $estado['estados_nombre'] ?></option>
                                        <?php $e++;
                                        endforeach ?>
                                    <?php endif ?>
                                </select>
                            <?php
                            }
                            ?>
                        </div>


                        <div class="col-md-2">
                            <label>Ciudad</label>
                        </div>
                        <div class="col-md-4">
                            <?php

                            if (isset($eestado['estado1'])) { ?>
                                <select name="ciudad_id" id="ciudad_id" required="true" class="form-control chosen" onchange="region.actualizarzonas();">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($ciudades as $ciudad) : ?>
                                        <option value="<?php echo $ciudad['ciudad_id'] ?>" <?php if (isset($cliente['ciudad_id']) and $cliente['ciudad_id'] == $ciudad['ciudad_id']) echo 'selected' ?>><?= $ciudad['ciudad_nombre'] ?></option>
                                    <?php endforeach ?>

                                </select>
                            <?php
                            } else {
                            ?>
                                <select name="ciudad_id" id="ciudad_id" required="true" class="form-control chosen" onchange="region.actualizarzonas();">
                                    <option value="">Seleccione</option>
                                    <?php if (isset($cliente['id_cliente'])) : ?>
                                        <?php foreach ($ciudades as $ciudad) : ?>
                                            <option value="<?php echo $ciudad['ciudad_id'] ?>" <?php if (isset($cliente['ciudad_id']) and $cliente['ciudad_id'] == $ciudad['ciudad_id']) echo 'selected' ?>><?= $ciudad['ciudad_nombre'] ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            <?php } ?>
                        </div>

                    </div>
                </div>


                <!--<div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Vendedor</label>
                        </div>
                        <div class="col-md-4">
                            <select name="vendedor" id="vendedor" required="true" class="form-control"
                                    onchange="region.actualizarzona();">
                                <option value="0">Seleccione</option>
                                <?php foreach ($vendedores as $vendedor) :

                                ?>
                                    <option
                                        value="<?php echo $vendedor['nUsuCodigo'] ?>" <?php if (isset($cliente['vendedor_a']) and $cliente['vendedor_a'] == $vendedor['nUsuCodigo']) echo 'selected' ?>><?= $vendedor['nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>-->
                <div class="row">
                    <div class="form-group">

                        <div class="col-md-2">
                            <label>Barrio</label>
                        </div>
                        <div class="col-md-4">
                            <?php
                            //   if()
                            ?>
                            <select name="zona" id="zona" required="true" class="form-control chosen">
                                <option value="0">Seleccione</option>
                                <?php foreach ($zonas as $zona) : ?>
                                    <option value="<?php echo $zona['zona_id'] ?>" <?php if (isset($cliente['id_zona']) and $cliente['id_zona'] == $zona['zona_id']) echo 'selected' ?>><?= $zona['zona_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label>Empresa afiliado </label>
                        </div>
                        <div class="col-md-4">
                            <select name="afiliado" id="afiliado" required="true" class="form-control chosen">
                                <option value="">Seleccione</option>
                                <?php foreach ($afiliados as $afiliado) : ?>
                                    <option value="<?php echo $afiliado['afiliado_id'] ?>" <?php if (isset($cliente['afiliado']) and $cliente['afiliado'] == $afiliado['afiliado_id']) echo 'selected' ?>><?= $afiliado['afiliado_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>

                        </div>


                    </div>
                </div>


                <div class="row">
                    <div class="form-group">

                        <div class="col-md-2">
                            <label>Tipo de cliente</label>
                        </div>
                        <div class="col-md-4">

                            <select name="grupo_id" id="grupo_id" required="true" class="form-control chosen">
                                <option value="">Seleccione</option>
                                <?php foreach ($grupos as $grupo) : ?>
                                    <option value="<?php echo $grupo['id_grupos_cliente'] ?>" <?php if (isset($cliente['grupo_id']) and $cliente['grupo_id'] == $grupo['id_grupos_cliente']) echo 'selected' ?>><?= $grupo['nombre_grupos_cliente'] ?></option>
                                <?php endforeach ?>
                            </select>

                        </div>


                        <div class="col-md-2">
                            <label>Tel&eacute;fono </label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="telefono" id="" class="form-control" value="<?php if (isset($cliente['telefono'])) echo $cliente['telefono']; ?>">
                        </div>


                    </div>
                </div>


                <div class="row">
                    <div class="form-group">

                        <div class="col-md-2">
                            <label> Celular</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="celular" id="" class="form-control" value="<?php if (isset($cliente['celular'])) echo $cliente['celular']; ?>">
                        </div>


                        <div class="col-md-2">
                            <label>Sexo</label>
                        </div>
                        <div class="col-md-4">

                            Masculino
                            <input name="sexo" type="radio" value="M" <?php if (isset($cliente['sexo']) && $cliente['sexo'] == 'M') echo 'checked'; ?>>
                            Femenino
                            <input name="sexo" type="radio" value="F" <?php if (isset($cliente['sexo']) && $cliente['sexo'] == 'F') echo 'checked'; ?>>
                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="form-group">


                        <div class="col-md-2">
                            <label>Fecha de nacimiento</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="<?php if (isset($cliente['fecha_nacimiento'])) echo date('d-m-Y', strtotime($cliente['fecha_nacimiento'])); ?>">
                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="form-group">

                        <div class="col-md-5">
                            <label>Vender a crédito</label>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="valida_venta_credito" id="valida_venta_credito" class="" <?php if (isset($cliente['valida_venta_credito']) && $cliente['valida_venta_credito'] == '1') echo 'checked="true"'; ?>>
                        </div>
                        <div class="col-md-2 validacredito">
                            <label>Días de Crédito</label>
                        </div>
                        <div class="col-md-4 validacredito">
                            <input type="text" name="dias_credito" id="dias_credito" class="form-control" value="<?php if (isset($cliente['dias_credito'])) echo $cliente['dias_credito']; ?>" onkeydown="return soloNumeros(event);">
                        </div>
                    </div>
                </div>
                <div class="row validacredito">
                    <div class="form-group ">


                        <div class="col-md-5">
                            <label>¿Desea bloquear al cliente cuando supere el monto máximo?</label>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="valida_fact_maximo" id="valida_fact_maximo" class="" <?php if (isset($cliente['valida_fact_maximo']) && $cliente['valida_fact_maximo'] == '1') echo 'checked="true"'; ?>>
                        </div>

                        <div class="col-md-2">
                            <label>Valor máximo crédito</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="facturacion_maximo" id="facturacion_maximo" class="form-control" value="<?php if (isset($cliente['facturacion_maximo'])) echo $cliente['facturacion_maximo']; ?>" onkeydown="return soloDecimal(this, event);">
                        </div>


                    </div>
                </div>

                <div class="row validacredito">
                    <div class="form-group ">


                        <div class="col-md-5">
                            <label>Permitir ventas con deuda vencida</label>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="permitir_deuda_vencida" id="permitir_deuda_vencida" class="" <?php if (isset($cliente['permitir_deuda_vencida']) && $cliente['permitir_deuda_vencida'] == '1') echo 'checked="true"'; ?>>
                        </div>


                    </div>
                </div>
                <div class="row">

                    <div class="col-md-2">
                        <label for="" class="control-label">Direcci&oacute;n</label>
                    </div>
                    <div class="col-md-4">

                        <input type="text" name="direccion" id="location" class="form-control"
                               title="Dirección"
                               autocomplete="on" value="<?php if (isset($cliente['direccion'])) echo $cliente['direccion']; ?>">
                    </div>

                </div>


                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Facturación electronica</h2>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-2">
                        <label>Tipo de responsabilidad</label>
                    </div>
                    <div class="col-md-4">
                        <select name="fe_type_liability" id="fe_type_liability" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($liabilities) > 0) : ?>
                                <?php foreach ($liabilities as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['fe_type_liability']) &&
                                                                                    $cliente['fe_type_liability'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Régimen</label>
                    </div>
                    <div class="col-md-4">
                        <select name="fe_regime" id="fe_regime" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($regimes) > 0) : ?>
                                <?php foreach ($regimes as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['fe_regime']) &&
                                                                                    $cliente['fe_regime'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Registro mercantil</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="merchant_registration" id="merchant_registration" class="form-control" value="<?php if (isset($cliente['merchant_registration'])) echo $cliente['merchant_registration']; ?>">
                    </div>

                    <div class="col-md-2">
                        <label>Municipio (DIAN)</label>
                    </div>
                    <div class="col-md-4">
                        <select name="fe_municipality" id="fe_municipality" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($municipalities) > 0) : ?>
                                <?php foreach ($municipalities as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['fe_municipality']) &&
                                                                                    $cliente['fe_municipality'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>

                <div class="row">


                    <div class="col-md-2">
                        <label>Tipo de documento</label>
                    </div>
                    <div class="col-md-4">
                        <select name="type_document_identification_id" id="type_document_identification_id" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($types_document) > 0) : ?>
                                <?php foreach ($types_document as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['type_document_identification_id']) &&
                                                                                    $cliente['type_document_identification_id'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Tipo de organizacion</label>
                    </div>
                    <div class="col-md-4">
                        <select name="type_organization_id" id="type_organization_id" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($types_organization) > 0) : ?>
                                <?php foreach ($types_organization as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['type_organization_id']) &&
                                                                                    $cliente['type_organization_id'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Impuesto</label>
                    </div>
                    <div class="col-md-4">
                        <select name="tax_detail_id" id="tax_detail_id" class='cho form-control'>
                            <option value="">Seleccione</option>
                            <?php if (count($taxes) > 0) : ?>
                                <?php foreach ($taxes as $item) : ?>
                                    <option value="<?php echo $item->id; ?>" <?php if (
                                                                                    isset($cliente['tax_detail_id']) &&
                                                                                    $cliente['tax_detail_id'] == $item->id
                                                                                ) echo 'selected' ?>>
                                        <?php echo $item->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>
            </form>

            <div class="modal-footer">
                <button type="button" id="guardarcliente" class="btn btn-primary" onclick="Cliente.guardar()" value="asas">Confirmar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
        </div>


        <!-- /.modal-content -->
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            Cliente.init();
            $("#fe_municipality").chosen({
                width: "100%",
                search_contains: true
            });

        });
    </script>