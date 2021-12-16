<style type="text/css">
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
</style>
<div class="modal-dialog modal-xl">
    <?php $ruta = base_url(); ?>
    <?= form_open_multipart(base_url() . 'producto/registrar', array('id' => 'formguardar_productos')) ?>

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Datos del producto -
                <span> <?php if (isset($producto['producto_nombre'])) echo $producto['producto_nombre']; ?></span>
            </h4>
        </div>

        <div class="modal-body">

            <input type="hidden" name="id" id="id" class='form-control' autofocus="autofocus" maxlength="15"
                   value="<?php if (isset($producto['producto_id']) and empty($duplicar)) echo $producto['producto_id'] ?>">

            <div id="mensaje"></div>


            <ul class="nav customtab nav-tabs" role="tablist">
                <li class='nav-item' role="presentation">
                    <a href="#lista" class="nav-link active" aria-controls="lista" role="tab" data-toggle="tab"
                       aria-expanded="true">Datos Generales</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="#precios" class="nav-link" aria-controls="precios" role="tab" data-toggle="tab"
                       aria-expanded="false"> Unidades y Precios</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="#inventarios" class="nav-link" aria-controls="inventarios" role="tab" data-toggle="tab"
                       aria-expanded="false"> Inventarios</a>
                </li>
                <!--
            <li role="presentation">
                <a href="#promocion" data-toggle="tab">Bonificaciones</a>
            </li>
            <li role="presentation">
                <a href="#descuento" data-toggle="tab">Descuento</a>
            </li>   -->
                <li class="nav-item" role="imagenes">
                    <a href="#imagenes" class="nav-link" aria-controls="imagenes" role="tab" data-toggle="tab"
                       aria-expanded="false">Im&aacute;genes</a>
                </li>
                <li class="nav-item" role="paquete">
                    <a href="#paquete" class="nav-link" aria-controls="paquete" role="tab" data-toggle="tab"
                       aria-expanded="false" onclick="Producto.tooglepaquete()">Paquete</a>
                </li>
            </ul>

            <div class="tab-content row" style="height: auto">

                <div role="tabpanel" class="tab-pane fade active in" id="lista">
                    <?php

                    foreach ($columnas as $columna) : ?>

                        <?php if ($columna->nombre_columna == 'producto_id' && isset($producto['producto_id']) and !isset($duplicar)) { ?>
                            <div class="form-group">
                                <div class="col-md-3"><label class="control-label">ID:</label></div>
                                <div class="col-md-8">

                                    <input type="text" name="codigo" id="codigo" class='form-control'
                                           autofocus="autofocus" maxlength="15"
                                           value="<?php if (isset($producto['producto_id']) and !isset($duplicar)) echo $producto['producto_id'] ?>"
                                           readonly>

                                </div>
                            </div>
                        <?php } ?>


                        <?php if ($columna->nombre_columna == 'producto_codigo_interno' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3"><label class="control-label">C&oacute;digo del Producto:</label>
                                </div>
                                <div class="col-md-8">

                                    <input type="text" name="producto_codigo_interno" id="producto_codigo_interno"
                                           class='form-control' maxlength="50"
                                           value="<?php if (isset($producto['producto_codigo_interno'])) {
                                               echo $producto['producto_codigo_interno'];
                                           } elseif (isset($correlativo)) {
                                               echo $correlativo;
                                           } ?>">


                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($columna->nombre_columna == 'producto_nombre') { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Nombre:</label>
                                </div>

                                <div class="col-md-8">
                                    <input type="text" name="producto_nombre" required="true" id="producto_nombre"
                                           class='form-control' maxlength="100"
                                           value="<?php if (isset($producto['producto_nombre'])) echo $producto['producto_nombre'] ?>">
                                </div>
                            </div>


                        <?php } ?>

                        <?php if ($columna->nombre_columna == 'producto_sustituto' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Sustituto:</label>
                                </div>

                                <div class="col-md-8">
                                    <input type="text" name="producto_sustituto" required="true" id="producto_sustituto"
                                           class='form-control' maxlength="100"
                                           value="<?php if (isset($producto['producto_sustituto'])) echo $producto['producto_sustituto'] ?>">
                                </div>
                            </div>

                        <?php } ?>


                        <?php if ($columna->nombre_columna == 'producto_tipo' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Tipo Producto:</label>
                                </div>

                                <div class="col-md-8"><select name="producto_tipo" id="producto_tipo"
                                                              class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($tipos_producto) > 0) : ?>
                                            <?php foreach ($tipos_producto as $tipo_producto) : ?>
                                                <option value="<?php echo $tipo_producto['tipo_prod_id']; ?>" <?php if (isset($producto['producto_tipo']) && $producto['producto_tipo'] == $tipo_producto['tipo_prod_id']) echo 'selected' ?>><?php echo $tipo_producto['tipo_prod_nombre']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select></div>
                            </div>

                        <?php } ?>


                        <?php if ($columna->nombre_columna == 'producto_ubicacion_fisica' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Ubicaci&oacute;n F&iacute;sica:</label>
                                </div>

                                <div class="col-md-8"><select name="producto_ubicacion_fisica"
                                                              id="producto_ubicacion_fisica" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($ubicaciones) > 0) : ?>
                                            <?php foreach ($ubicaciones as $ubicacion) : ?>
                                                <option value="<?php echo $ubicacion['ubicacion_id']; ?>" <?php if (isset($producto['producto_ubicacion_fisica']) && $producto['producto_ubicacion_fisica'] == $ubicacion['ubicacion_id']) echo 'selected' ?>><?php echo $ubicacion['ubicacion_nombre']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select></div>
                            </div>

                        <?php } ?>


                        <?php if ($columna->nombre_columna == 'producto_clasificacion' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Clasificaci&oacute;n:</label>
                                </div>

                                <div class="col-md-8"><select name="producto_clasificacion" id="producto_clasificacion"
                                                              class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($clasificaciones) > 0) : ?>
                                            <?php foreach ($clasificaciones as $clasificacion) : ?>
                                                <option value="<?php echo $clasificacion['clasificacion_id']; ?>" <?php if (isset($producto['producto_clasificacion']) && $producto['producto_clasificacion'] == $clasificacion['clasificacion_id']) echo 'selected' ?>><?php echo $clasificacion['clasificacion_nombre']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select></div>
                            </div>

                        <?php } ?>

                        <?php if ($columna->nombre_columna == 'producto_estatus' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Estado del Producto:</label>
                                </div>

                                <div class="col-md-8">
                                    <input type="text" name="producto_estatus" required="true" id="producto_estatus"
                                           class='form-control' maxlength="100"
                                           value="<?php if (isset($producto['producto_estatus'])) echo $producto['producto_estatus'] ?>">
                                </div>
                            </div>

                        <?php } ?>

                        <?php if ($columna->nombre_columna == 'producto_componente' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Principio Activo:</label>
                                </div>

                                <div class="col-md-8"><select multiple name="producto_componente[]"
                                                              id="producto_componente" class='cho form-control'>
                                        <option value="">Seleccione</option>

                                        <?php foreach ($componentes as $compo) { ?>


                                            <?php if (isset($componentes_producto) && $componentes_producto != null) {
                                                $cantidad = count($componentes_producto);
                                                $i = 1;
                                                foreach ($componentes_producto as $usz) {
                                                    if (isset($usz['componente_id']) and $usz['componente_id'] == $compo['componente_id']) {
                                                        ?>
                                                        <option value="<?php echo $compo['componente_id'] ?>"
                                                                selected><?= $compo['componente_nombre'] ?></option>
                                                        <?php break;
                                                    }
                                                    if ($cantidad == $i) {
                                                        ?>
                                                        <option value="<?php echo $compo['componente_id'] ?>"><?= $compo['componente_nombre'] ?></option>
                                                        <?php
                                                    } else {
                                                        $i++;
                                                    }
                                                }
                                            } else { ?>
                                                <option value="<?php echo $compo['componente_id'] ?>"><?= $compo['componente_nombre'] ?></option>
                                            <?php };
                                        } ?>


                                    </select></div>
                            </div>

                        <?php } ?>

                        <?php

                        if ($columna->nombre_columna == 'producto_mensaje' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Alerta:</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="radio" value="0" name="check_mensaje_producto" checked> No
                                </div>
                                <div class="col-md-1">
                                    <input type="radio" value="1"
                                           name="check_mensaje_producto" <?php if (isset($producto['producto_mensaje']) and $producto['producto_mensaje'] != "") echo "checked";
                                    ?>> Si
                                </div>
                                <div class="col-md-6">
                                    <textarea name="producto_mensaje"
                                              data-texto="<?php if (isset($producto['producto_mensaje']) and $producto['producto_mensaje'] != "") echo $producto['producto_mensaje'] ?>"
                                              id="producto_mensaje" class='form-control'>
                                        <?php if (isset($producto['producto_mensaje']) and $producto['producto_mensaje'] != null) echo $producto['producto_mensaje'] ?></textarea>

                                </div>
                            </div>

                        <?php } ?>


                        <?php /*if ($columna->nombre_columna == 'producto_codigo_barra' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3"><label class="control-label">C&oacute;digo de barra:</label></div>
                                <div class="col-md-8">

                                    <input type="text" name="producto_codigo_barra" id="codigodebarra"
                                           class='form-control' autofocus="autofocus" maxlength="25"
                                           value="<?php if (isset($producto['producto_codigo_barra'])) echo $producto['producto_codigo_barra'] ?>">


                                </div>
                            </div>
                        <?php } ?>


                        <?php if ($columna->nombre_columna == 'producto_descripcion' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Descripcion:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="producto_descripcion" id="producto_descripcion"
                                           class='form-control'
                                           maxlength="500"
                                           value="<?php if (isset($producto['producto_descripcion'])) echo $producto['producto_descripcion'] ?>">
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_marca' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="linea" class="control-label">Marca:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_marca" id="producto_marca" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($marcas) > 0): ?>
                                            <?php foreach ($marcas as $marca): ?>
                                                <option
                                                    value="<?php echo $marca['id_marca']; ?>" <?php if (isset($producto['producto_marca']) && $producto['producto_marca'] == $marca['id_marca']) echo 'selected' ?> ><?php echo $marca['nombre_marca']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_linea' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="producto_linea" class="control-label">L&iacute;nea:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_linea" id="producto_linea" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($lineas) > 0): ?>
                                            <?php foreach ($lineas as $linea): ?>
                                                <option
                                                    value="<?php echo $linea['id_linea']; ?>" <?php if (isset($producto['producto_linea']) && $producto['producto_linea'] == $linea['id_linea']) echo 'selected' ?>><?php echo $linea['nombre_linea']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_familia' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="producto_familia" class="control-label">Familia:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_familia" id="producto_familia" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($familias) > 0): ?>
                                            <?php foreach ($familias as $familia): ?>
                                                <option
                                                    value="<?php echo $familia['id_familia']; ?>" <?php if (isset($producto['producto_familia']) && $producto['producto_familia'] == $familia['id_familia']) echo 'selected' ?>><?php echo $familia['nombre_familia']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <!--SUB FAMILIA -->
                        <?php if ($columna->nombre_columna == 'producto_subfamilia' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="producto_subfamilia" class="control-label">Sub Familia:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_subfamilia" id="producto_subfamilia"
                                            class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($subfamilias) > 0): ?>
                                            <?php foreach ($subfamilias as $familia): ?>
                                                <option
                                                    value="<?php echo $familia['id_subfamilia']; ?>" <?php if (isset($producto['producto_subfamilia']) && $producto['producto_subfamilia'] == $familia['id_subfamilia']) echo 'selected' ?>><?php echo $familia['nombre_subfamilia']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- FIN SUB FAMILIA -->
                        */
                        if ($columna->nombre_columna == 'produto_grupo' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="grupo" class="control-label">Grupo:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="produto_grupo" id="produto_grupo" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($grupos) > 0) : ?>
                                            <?php foreach ($grupos as $grupo) : ?>
                                                <option value="<?php echo $grupo['id_grupo']; ?>" <?php if (isset($producto['produto_grupo']) && $producto['produto_grupo'] == $grupo['id_grupo']) echo 'selected' ?>><?php echo $grupo['nombre_grupo']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <!--SUB GRUPO -->
                        <?php /* if ($columna->nombre_columna == 'producto_subgrupo' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="subgrupo" class="control-label">Sub Grupo:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_subgrupo" id="producto_subgrupo" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($subgrupos) > 0): ?>
                                            <?php foreach ($subgrupos as $grupo): ?>
                                                <option
                                                    value="<?php echo $grupo['id_subgrupo']; ?>" <?php if (isset($producto['producto_subgrupo']) && $producto['producto_subgrupo'] == $grupo['id_subgrupo']) echo 'selected' ?>><?php echo $grupo['nombre_subgrupo']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- FIN SUB GRUPO-->
                        <?php if ($columna->nombre_columna == 'producto_proveedor' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3"><label class="control-label">Proveedor:</label></div>
                                <div class="col-md-8">
                                    <select name="producto_proveedor" id="producto_proveedor" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($proveedores) > 0): ?>
                                            <?php foreach ($proveedores as $proveedor): ?>
                                                <option
                                                    value="<?php echo $proveedor->id_proveedor; ?>" <?php if (isset($producto['producto_proveedor']) && $producto['producto_proveedor'] == $proveedor->id_proveedor) echo 'selected' ?>><?php echo $proveedor->proveedor_nombre; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_stockminimo' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="stockmin" class="control-label">Stock M&iacute;nimo:</label>
                                </div>

                                <div class="col-md-8">


                                    <div class="input-prepend input-append input-group">
                                        <span class="input-group-addon">cant.</span>
                                        <input type="text" class='input-small input-square form-control'
                                               name="producto_stockminimo"
                                               id="producto_stockminimo" maxlength="11"
                                               onkeydown="return soloDecimal(this, event);"
                                               value="<?php if (isset($producto['producto_stockminimo'])) echo $producto['producto_stockminimo'] ?>">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_impuesto' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Impuesto:</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="producto_impuesto" id="producto_impuesto" class='cho form-control'>
                                        <option value="">Seleccione</option>
                                        <?php if (count($impuestos) > 0): ?>
                                            <?php foreach ($impuestos as $impuesto): ?>
                                                <option
                                                    value="<?php echo $impuesto['id_impuesto']; ?>" <?php if (isset($producto['producto_impuesto']) && $producto['producto_impuesto'] == $impuesto['id_impuesto']) echo 'selected'; elseif (strtoupper($impuesto['nombre_impuesto']) == "IGV") echo 'selected' ?>><?php echo $impuesto['nombre_impuesto']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_largo' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Largo:</label>
                                </div>
                                <div class="col-md-8">

                                    <div class="input-prepend input-append input-group">
                                        <span class="input-group-addon">Cm.</span>
                                        <input type="number" name="producto_largo" id="producto_largo"
                                               class='cho form-control'
                                               value="<?php if (isset($producto['producto_largo'])) echo $producto['producto_largo'] ?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_ancho' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Ancho:</label>
                                </div>
                                <div class="col-md-8">

                                    <div class="input-prepend input-append input-group">
                                        <span class="input-group-addon">Cm.</span>
                                        <input type="number" name="producto_ancho" id="producto_ancho"
                                               class='cho form-control'
                                               value="<?php if (isset($producto['producto_ancho'])) echo $producto['producto_ancho'] ?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_alto' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Alto:</label>
                                </div>
                                <div class="col-md-8">


                                    <div class="input-prepend input-append input-group">
                                        <span class="input-group-addon">Cm.</span>
                                        <input type="number" name="producto_alto" id="producto_alto"
                                               class='cho form-control'
                                               value="<?php if (isset($producto['producto_alto'])) echo $producto['producto_alto'] ?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php } */ ?>
                        <?php if ($columna->nombre_columna == 'producto_activo' and $columna->activo == 1) { ?>

                            <div class="col-md-12">
                                <div class="form-group row">

                                    <div class="col-md-3">
                                        <label for="impuesto" class="control-label">Estado</label>
                                    </div>

                                    <div class="col-md-8">
                                        <input type="radio" value="SI"
                                               name="producto_activo" <?php if ((isset($producto['producto_activo'])
                                                and $producto['producto_activo'] == 1) or (!isset($producto['producto_activo']))
                                        ) {
                                            echo "checked";
                                        } ?>> Activo

                                        <input type="radio" value="NO" name="producto_activo" <?php if (
                                            isset($producto['producto_activo'])
                                            and $producto['producto_activo'] == 0
                                        ) echo "checked"; ?>> Inactivo
                                    </div>


                                </div>
                            </div>

                        <?php } ?>
                        <?php /*if ($columna->nombre_columna == 'presentacion' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Presentacion</label>
                                </div>
                                <div class="col-md-8">

                                    <input type="text" name="presentacion" id="presentacion"
                                           class='cho form-control'
                                           value="<?php if (isset($producto['presentacion'])) echo $producto['presentacion'] ?>"/>
                                </div>

                            </div>

                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_peso' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Peso:</label>
                                </div>
                                <div class="col-md-8">


                                    <div class="input-prepend input-append input-group">
                                        <span class="input-group-addon">Kg.</span>
                                        <input type="number" name="producto_peso" id="producto_peso"
                                               class='cho form-control'
                                               value="<?php if (isset($producto['producto_peso'])) echo $producto['producto_peso'] ?>"/>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_nota' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Nota:</label>
                                </div>
                                <div class="col-md-8">
                            <textarea name="producto_nota" id="producto_nota"
                                      class='cho form-control'><?php if (isset($producto['producto_nota'])) echo $producto['producto_nota'] ?></textarea>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($columna->nombre_columna == 'producto_cualidad' and $columna->activo == 1) { ?>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="impuesto" class="control-label">Cualidad:</label>
                                </div>
                                <div class="col-md-8">

                                    <select class="form-control" id="producto_cualidad" name="producto_cualidad">
                                        <option value="">Seleccione</option>
                                        <option
                                            value="<?= PESABLE ?>" <?php if (isset($producto['producto_id']) and $producto['producto_cualidad'] == PESABLE) echo 'selected' ?>><?= PESABLE ?></option>
                                        <option
                                            value="<?= MEDIBLE ?>" <?php if (isset($producto['producto_id']) and $producto['producto_cualidad'] == MEDIBLE) echo 'selected' ?>><?= MEDIBLE ?></option>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($columna->nombre_columna == 'producto_titulo_imagen' and $columna->activo == 1) { ?>
                            <div class="form-group ">

                                <div class="col-md-3">
                                    <label for="" class="control-label">Detalle Imagen:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="titulo_imagen" id="titulo_imagen"
                                           class='cho form-control' placeholder="Titulo"
                                           value="<?php if (isset($producto['producto_titulo_imagen'])){ echo $producto['producto_titulo_imagen']; }  ?>"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="control-label">&nbsp;<br></label>
                                </div>
                                <div class="col-md-8">
                                <textarea id="compose-message" name="descripcion_imagen" rows="5" class="form-control textarea-editor" placeholder="Su descripcion">
                                    <?php if (isset($producto['producto_descripcion_img'])){ echo $producto['producto_descripcion_img']; }  ?>
                                </textarea>
                                </div>
                            </div>
                        <?php } */ ?>
                    <?php endforeach ?>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Tipo de item (DIAN):</label>
                        </div>

                        <div class="col-md-8">
                            <select name="fe_type_item_identification_id" id="fe_type_item_identification_id"
                                    class='cho form-control'>
                                <option value="">Seleccione</option>
                                <?php if (count($fe_typeitems) > 0) : ?>
                                    <?php foreach ($fe_typeitems as $typeitem) : ?>
                                        <option value="<?php echo $typeitem->id; ?>" <?php if (
                                            isset($producto['fe_type_item_identification_id']) &&
                                            $producto['fe_type_item_identification_id'] == $typeitem->id
                                        ) echo 'selected' ?>>
                                            <?php echo $typeitem->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="div_inicial_barra_formProducto">
                        <div class="col-md-3">
                            <label class="control-label panel-admin-text">C&oacute;digos de barra: </label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="codigos_barra[]" id="codigo_barra_original"
                                   class='cho form-control' placeholder="" value=""/>

                        </div>

                        <div class="col-md-2">
                            <button class="fa fa-plus btn-default" title="Agregar" id="agregar_barra"></button>
                        </div>
                    </div>

                    <hr/>


                </div>

                <div class="tab-pane" id="precios" role="tabpanel">
                    <div class="panel col-md-12">


                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>Costo Unitario (costo de la caja):</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="costo_unitario" id="costo_unitario" class="form-control"
                                           required onkeydown="return soloDecimal3(this, event);" min="0"
                                           onkeyup="Producto.calcularcostos_global()"
                                           value="<?php if (isset($producto['costo_unitario'])) echo $producto['costo_unitario'] ?>"/>
                                </div>
                                <div class="col-md-2">
                                    <label>Costo Promedio (costo promedio de la caja):</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="costo_promedio" id="costo_promedio" class="form-control"
                                           required onkeydown="return soloDecimal3(this, event);" min="0" disabled
                                           value="<?php if (isset($producto['costo_promedio'])) echo $producto['costo_promedio'] ?>"/>
                                </div>

                                <div class="col-md-2">
                                    <label>Descuentos:</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="producto_descuentos" id="producto_descuentos"
                                           class="form-control" required onkeydown="return soloDecimal3(this, event);"
                                           min="0" disabled
                                           value="<?php if (isset($producto['producto_descuentos'])) echo $producto['producto_descuentos'] ?>"/>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group">

                                <div class="col-md-2">
                                    <label>% Iva:</label>
                                </div>
                                <div class="col-md-2">

                                    <select name="producto_impuesto_costos" onchange="Producto.calcularcostos_global()"
                                            id="producto_impuesto_costos" class='cho form-control' style="
                                    width: 100%">
                                        <option value="" data-porcentaje="">Seleccione</option>
                                        <?php if (count($impuestos) > 0) : ?>
                                            <?php foreach ($impuestos as $impuesto) :
                                                if ($impuesto['tipo_calculo'] == 'PORCENTAJE') {
                                                    ?>
                                                    <option data-porcentaje="<?= $impuesto['porcentaje_impuesto']; ?>"
                                                            value="<?php echo $impuesto['id_impuesto']; ?>" <?php if (isset($producto['producto_impuesto']) && $producto['producto_impuesto'] == $impuesto['id_impuesto']) echo 'selected'; ?>><?php echo $impuesto['nombre_impuesto']; ?></option>
                                                <?php }
                                            endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Otros impuestos:</label>
                                </div>
                                <div class="col-md-2">

                                    <select name="otro_impuesto" id="otro_impuesto" class='cho form-control' style="
                                    width: 100%">
                                        <option value="" data-porcentaje="">Seleccione</option>
                                        <?php if (count($impuestos) > 0) : ?>
                                            <?php foreach ($impuestos as $impuesto) :
                                                if ($impuesto['tipo_calculo'] == 'FIJO') {
                                                    ?>
                                                    <option data-porcentaje="<?= $impuesto['porcentaje_impuesto']; ?>"
                                                            value="<?php echo $impuesto['id_impuesto']; ?>" <?php if (isset($producto['otro_impuesto']) && $producto['otro_impuesto'] == $impuesto['id_impuesto']) echo 'selected'; ?>><?php echo $impuesto['nombre_impuesto']; ?></option>
                                                <?php }
                                            endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <label>% Comisi&oacute;n:</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="producto_comision" id="producto_comision"
                                           class="form-control" required onkeydown="return soloDecimal3(this, event);"
                                           min="0"
                                           value="<?php if (isset($producto['producto_comision'])) echo $producto['producto_comision'] ?>"/>
                                </div>

                                <div class="col-md-2">
                                    <label> % Costo:</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="porcentaje_costo" id="porcentaje_costo"
                                           class="form-control" required onkeydown="return soloDecimal3(this, event);"
                                           min="0"
                                           value="<?php if (isset($producto['porcentaje_costo'])) echo $producto['porcentaje_costo'] ?>"/>
                                </div>

                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group">


                                <div class="col-md-2">
                                    <label> Precio Abierto:</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" onclick="Producto.precioAbierto(this)" value="1"
                                           name="precio_abierto"
                                           id="precio_abierto" <?= (isset($producto['precio_abierto']) &&
                                        $producto['precio_abierto'] == 1) ? 'checked' : '' ?>>
                                </div>


                                <div class="col-md-3">

                                    Este producto está en oferta

                                </div>
                                <div class="col-md-2">

                                    <input type="checkbox" name="in_offer"
                                           id="in_offer" <?= (isset($producto['in_offer']) && $producto['in_offer'] == 1) ? 'checked' : '' ?>>

                                </div>
                            </div>

                        </div>


                        <!-- Block -->
                        <table class="table block table-striped dataTable table-bordered">
                            <thead>
                            <th>Descripci&oacute;n</th>
                            <th>Contenido Interno</th>
                            <!--<th>Metro Cubicos</th>-->

                            <?php foreach ($condiciones as $condicion) :
                                ?>
                                <th>% Utilidad</th>
                                <th id="thappend_<?= $condicion['id_condiciones'] ?>">
                                    <?= $condicion['nombre_condiciones'] ?>
                                </th>

                            <?php endforeach ?>

                            </thead>
                            <tbody id="unidadescontainer">

                            <?php
                            /*esta variable s va a aumentar por cada unidad de medida*/
                            $countunidad = 0;
                            $contador_condicion = 0;

                            foreach ($unidades as $unidad) {

                                $esta_unidad = array();
                                $esta_unidad['unidades'] = "";
                                $disabled = true;
                                $readonly = "";
                                if (isset($unidades_producto) and count($unidades_producto) > 0) {

                                    foreach ($unidades_producto as $row) {

                                        if ($row['id_unidad'] == $unidad['id_unidad']) {
                                            $esta_unidad['unidades'] = $row['unidades'];
                                            $disabled = false;
                                        }
                                    }
                                }


                                if (isset($unidades_producto) && count($unidades_producto) > 1) {
                                    $readonly = "";
                                    $disabled = false;
                                }

                                if ($countunidad == 2) {
                                    $readonly = "readonly";
                                }

                                ?>
                                <tr id="trunidad<?= $countunidad ?>">

                                    <td>
                                        <?= $unidad['nombre_unidad'] ?>
                                        <input type="hidden" class="form-control" value='<?= $unidad['id_unidad'] ?>'
                                               min="0" name="medida[<?= $countunidad ?>]"
                                               id="medida<?= $countunidad ?>">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" <?= $readonly ?>
                                               required <?php if ($countunidad > 0 and $disabled == true) { ?> disabled <?php } ?>
                                               value='<?= $esta_unidad['unidades'] ?>' min="0"
                                               onkeyup="Producto.contenido_interno(this,<?= $countunidad ?>)"
                                               onkeydown='return soloNumeros(event), Producto.validar_numeropar(event,this,<?= $countunidad ?>)'
                                               name="unidad[<?= $countunidad ?>]" id="unidad<?= $countunidad ?>"/>
                                    </td>

                                    <?php

                                    /*esta variable se va a aumentar por cada condiciones de pago*/
                                    $countproducto = 0;
                                    $contador_condicion = 0;
                                    foreach ($condiciones as $condicion) {

                                        $utilidad = "";
                                        $id_condiciones_pago = $condicion['id_condiciones'];
                                        $precio_valor = "";
                                        if (count($precios_producto) > 0) {

                                            foreach ($precios_producto as $precio) {


                                                if (
                                                    $precio['id_condiciones_pago'] == $condicion['id_condiciones']
                                                    and $precio['id_unidad'] == $unidad['id_unidad']
                                                ) {

                                                    $utilidad = $precio['utilidad'];
                                                    $id_condiciones_pago = $precio['id_condiciones_pago'];
                                                    $precio_valor = $precio['precio'];
                                                }
                                            }
                                        }

                                        ?>
                                        <td>
                                            <input type="text" min="0"
                                                   class="form-control" <?php if ($countunidad > 0 and $disabled == true) { ?> disabled <?php } ?>
                                                   value='<?= $utilidad ?>'
                                                   id="utilidad_<?= $countunidad ?>_<?= $countproducto ?>"
                                                   onkeyup="Producto.calcular_precio('<?= $countunidad ?>','<?= $countproducto ?>')"
                                                   onkeydown="return soloDecimal(this, event);"
                                                   name="utilidad_<?= $countunidad ?>[<?= $countproducto ?>]">
                                        </td>

                                        <td id="tdappend_<?= $countunidad . "_" . $condicion['id_condiciones'] ?>">
                                            <input type="hidden" <?php if ($countunidad > 0 and $disabled == true) { ?> disabled <?php } ?>
                                                   value='<?= $id_condiciones_pago ?>'
                                                   name='precio_id_<?= $countunidad ?>[<?= $countproducto ?>]'/>
                                            <input min="0" type="text" class="form-control"
                                                   required <?php if ($countunidad > 0 and $disabled == true) { ?> disabled <?php } ?>
                                                   id="precio_valor_<?= $countunidad ?>_<?= $countproducto ?>"
                                                   onkeyup="Producto.calcular_utilidad('<?= $countunidad ?>','<?= $countproducto ?>')"
                                                   onkeydown="return soloDecimal(this, event);"
                                                   value='<?= $precio_valor ?>'
                                                   name="precio_valor_<?= $countunidad ?>[<?= $countproducto ?>]">

                                        </td>
                                        <?php

                                        $countproducto++;
                                        $contador_condicion++;
                                    }
                                    ?>

                                </tr>
                                <?php $countunidad++;
                            } ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="inventarios" role="tabpanel">
                    <div class="panel col-md-12">


                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label> Control inventario:</label>
                                </div>
                                <div class="col-md-1">

                                    <input type="radio" value="1" name="control_inventario" <?php if (
                                        (isset($producto['control_inven']) and
                                            $producto['control_inven'] == 1) ||
                                        (!isset($producto['control_inven']) && !isset($producto['producto_id']))
                                    ) {
                                        echo "checked";
                                    } ?>> Si

                                </div>

                                <div class="col-md-1">
                                    <input type="radio" value="0"
                                           name="control_inventario" <?php if (isset($producto['control_inven']) and $producto['control_inven'] == 0) {
                                        echo "checked";
                                    }
                                    ?>> No

                                </div>
                                <div class="col-md-2">
                                    <label> Control inventario diario:</label>
                                </div>

                                <div class="col-md-1">
                                    <input type="radio" value="1" name="control_inventario_diario" <?php if (
                                        isset($producto['control_inven_diario'])
                                        and $producto['control_inven_diario'] == 1
                                    ) {
                                        echo "checked";
                                    }
                                    ?>> Si
                                </div>
                                <div class="col-md-1">
                                    <input type="radio" value="0"
                                           name="control_inventario_diario" <?php if ((isset($producto['control_inven_diario']) and $producto['control_inven_diario'] == 0) ||
                                        (isset($producto) and $producto['control_inven_diario'] == null) ||
                                        !isset($producto['control_inven_diario'])
                                    ) {
                                        echo "checked";
                                    }
                                    ?>> No
                                </div>


                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group">


                                <div class="table-responsive ">
                                    <!-- Block -->
                                    <div class="col-md-12">
                                        <table class="table block table-striped dataTable table-bordered">

                                            <tbody id="tbody_stock" class="">

                                            <?php
                                            /*recorro las unidades del producto*/
                                            if (isset($unidades_producto) and count($unidades_producto) > 0) {
                                                $countunidad = 0;
                                                foreach ($unidades_producto as $unidad) {
                                                    ?>
                                                    <tr id="tr_stock<?php echo $unidad['id_unidad'] ?>">
                                                        <td id="td_stock_minimo<?php echo $unidad['id_unidad'] ?>">Stock
                                                            m&iacute;nimo <?= $unidad['nombre_unidad'] ?>

                                                        </td>
                                                        <td>

                                                            <input type="hidden" class="form-control"
                                                                   value='<?= $unidad['id_unidad'] ?>' min="0"
                                                                   id='control_stock_unidad<?= $unidad['id_unidad'] ?>'
                                                                   onkeydown='return soloNumeros(event);'
                                                                   name="control_stock_unidad[<?= $unidad['id_unidad'] ?>]">
                                                            <input type="text" class="form-control"
                                                                   value='<?= $unidad['stock_minimo'] ?>' min="0"
                                                                   onkeydown='return soloNumeros(event);'
                                                                   name="stock_minimo[<?= $unidad['id_unidad'] ?>]"
                                                                   id="stock_minimo<?= $countunidad ?>">
                                                        </td>

                                                        <td id="td_stock_maximo<?php echo $countunidad ?>">Stock m&aacute;ximo <?= $unidad['nombre_unidad'] ?> </td>
                                                        <td><input type="text" class="form-control"
                                                                   value='<?= $unidad['stock_maximo'] ?>' min="0"
                                                                   onkeydown='return soloNumeros(event);'
                                                                   name="stock_maximo[<?= $unidad['id_unidad'] ?>]"
                                                                   id="stock_maximo<?= $countunidad ?>"></td>

                                                    </tr>
                                                    <?php
                                                    $countunidad++;
                                                }
                                            }
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="table-responsive ">
                                    <!-- Block -->
                                    <div class="col-md-12">
                                        <table class="table block table-striped dataTable table-bordered">
                                            <tbody id="" class="" style="width: 100%">

                                            <tr>
                                                <td style="width: 20% "></td>
                                                <td style="width: 20% ">
                                                    <?php if (isset($unidades_producto) and count($unidades_producto) > 0) { ?>
                                                        <strong> Actual</strong>
                                                    <?php } ?>

                                                </td>
                                                <td style="width: 20% "></td>

                                            </tr>

                                            <?php
                                            /*recorro las unidades del producto*/
                                            if (isset($unidades_producto) and count($unidades_producto) > 0) {

                                            foreach ($unidades_producto

                                            as $unidad) {
                                            /*$encontro_inventario es para saber si existe nventario para esa unidad de medida*/
                                            $encontro_inventario = false;
                                            ?>

                                            <tr>
                                                <td style="width: 20% ">
                                                    Inventarios <?= $unidad['nombre_unidad'] ?> </td>

                                                <?php


                                                if (count($inventarios) > 0) {
                                                    /*recorro los inventarios si existen, vienen agrupados por unidad*/
                                                    foreach ($inventarios as $inventario) {

                                                        if (
                                                            isset($inventario['id_unidad']) &&
                                                            $inventario['id_unidad'] == $unidad['id_unidad']
                                                        ) {
                                                            $encontro_inventario = true;
                                                            ?>
                                                            <td style="width: 20% "><?= $inventario['cantidad'] ?></td>
                                                            <td style="width: 20% "></td>

                                                            <?php
                                                        }
                                                    }
                                                }
                                                if ($encontro_inventario == false) { ?>
                                                    <td style="width: 20% ">0</td>
                                                    <td style="width: 20% "></td>

                                                <?php }
                                                echo "</tr>";
                                                }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label> D&iacute;as sin vender:</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="" disabled class="form-control">
                                </div>


                            </div>
                        </div>


                    </div>
                    <br>


                </div>

                <div class="tab-pane table-responsive" id="promocion" role="tabpanel">
                    <br>
                    <table class="table table-striped dataTable table-bordered" id="tablaresultado">
                        <thead>
                        <tr>

                            <th>Unidad</th>
                            <th>Familia</th>
                            <th>Grupo</th>
                            <th>Marca</th>
                            <th>Linea</th>
                            <th>Cantidad</th>
                            <th>Bono unidad</th>
                            <th>Bono producto</th>
                            <th>Bono cantidad</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($promociones

                        as $promocion) { ?>
                        <tr>
                            <td>
                                <?php if (isset($promocion['id_unidad'])) echo $promocion['nombre_unidad']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['id_familia'])) echo $promocion['nombre_familia']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['id_grupo'])) echo $promocion['nombre_grupo']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['id_marca'])) echo $promocion['nombre_marca']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['id_linea'])) echo $promocion['nombre_linea']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['cantidad_condicion'])) echo $promocion['cantidad_condicion']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['unidad_bonificacion'])) echo $promocion['unidad_bonificacion']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['producto_bonificacion'])) echo $promocion['producto_bonificacion']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['bono_cantidad'])) echo $promocion['bono_cantidad']; ?>
                            </td>
                            <td>
                                <?php if (isset($promocion['fecha'])) echo date('d-m-Y', strtotime($promocion['fecha']));
                                } ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane" id="descuento" role="tabpanel">
                    <br>
                    <tr class="table-responsive ">
                        <table class="table table-striped dataTable table-bordered" id="tablaresultado">
                            <thead>
                            <tr>

                                <th>Regla descuento</th>
                                <th>Cantidades</th>
                                <th>Nombre producto</th>
                                <th>Unidad</th>
                                <th>Precio</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($descuentos

                            as $descuento) { ?>
                            <tr>
                                <td>
                                    <?php if (isset($descuento['nombre'])) echo $descuento['nombre']; ?>
                                </td>
                                <td>
                                    <?php if (isset($descuento['cantidad_minima'])) echo $descuento['cantidad_minima']; ?>
                                    a
                                    <?php if (isset($descuento['cantidad_maxima'])) echo $descuento['cantidad_maxima']; ?>
                                </td>
                                <td>
                                    <?php if (isset($descuento['producto_nombre'])) echo $descuento['producto_nombre']; ?>
                                </td>
                                <td>
                                    <?php if (isset($descuento['nombre_unidad'])) echo $descuento['nombre_unidad']; ?>
                                </td>
                                <td>
                                    <?php if (isset($descuento['precio'])) echo $descuento['precio'];
                                    } ?>
                                </td>


                            </tr>
                            </tbody>
                        </table>
                </div>


                <div class="tab-pane" id="imagenes" role="tabpanel">

                    <div class="form-group" id="row1">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="input-prepend input-append input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder"></i> </span>
                                    <input type="file" onchange="Producto.asignar_imagen(0)"
                                           class="form-control input_imagen" data-count="0" name="userfile[]"
                                           accept="image/jpeg, image/png" id="input_imagen0">

                                </div>


                            </div>

                            <div class="col-md-2">
                                <img id="imgSalida0" data-count="0"
                                     src="<?= base_url(); ?>/recursos/img/default_img.png" width="100">

                            </div>
                        </div>


                    </div>
                    <div>
                        <button id="agregar_img" align="center" class="btn btn-default">Agregar otra imagen</button>
                    </div>
                    <br>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">

                                <?php if (isset($producto['producto_id'])) : ?>
                                    <?php $ruta_imagen = "uploads/" . $producto['producto_id'] . "/" ?>


                                    <div class="row">
                                        <?php
                                        $con_image = 0;

                                        foreach ($images as $img) : ?>
                                            <div class="col-sm-4" style="text-align: center; margin-bottom: 20px;"
                                                 id="div_imagen_producto<?= $con_image ?>">

                                                <a href="#" class="img_show"
                                                   data-src="<?php echo $ruta . $ruta_imagen . $img; ?>">
                                                    <img width="200" height="200"
                                                         src="<?php echo $ruta . $ruta_imagen . $img; ?>">
                                                </a>
                                                <br>
                                                <a href="#"
                                                   onclick="Producto.borrar_img('<?= $producto['producto_id'] ?>','<?= $img ?>','<?= $con_image ?>')"
                                                   style="width: 200px; margin: 0;" class="btn btn-danger"><i
                                                            class="fa fa-trash-o"></i> Eliminar</a>
                                            </div>


                                            <?php
                                            $con_image++;
                                        endforeach; ?>
                                    </div>


                                <?php endif; ?>


                            </div>

                        </div>

                    </div>


                </div>

                <div class="tab-pane" id="paquete" role="tabpanel">

                    <div class="form-group" id="info_is_paquete" style="display: <?= (isset($producto['is_paquete']) && $producto['is_paquete'] == 1)?'block':'none' ?>">

                        <div class="row">
                        <div class="col-md-9 alert alert-info">
                            <strong>Recuerde que al activar este producto como paquete, utilizará costo unitario para el sistema.</strong>
                        </div>

                        <div class="col-md-3">
                        </div>
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-3">

                            Este producto es un paquete

                        </div>
                        <div class="col-md-9">

                            <input type="checkbox" name="is_paquete"
                                   id="is_paquete" <?= (isset($producto['is_paquete']) && $producto['is_paquete'] == 1) ? 'checked' : '' ?>>

                        </div>
                    </div>

                    <div class="form-group" id="div_productos_paquete"
                         style="display:<?= (isset($producto['is_paquete']) && $producto['is_paquete'] == 1) ? 'block' : 'none' ?>">

                        <div class="col-md-3">

                            Productos que contiene el paquete

                        </div>
                        <div class="col-md-5">
                            <select name="" id="productos_paquete" placeholder="Seleccione"
                                    class='cho form-control' <?= (isset($producto['is_paquete']) && $producto['is_paquete'] != 1) ? 'disabled' : '' ?>>

                            </select>


                        </div>
                        <div class="col-md-4">
                            <button type="button" id="addprodpaq" class="btn btn-success" onclick="Producto.addprod()">
                                Agregar
                            </button>
                        </div>
                    </div>

                    <div class="form-group" id="div_tab_productos_paquete"
                         style="display:<?= (isset($producto['is_paquete']) && $producto['is_paquete'] == 1) ? 'block' : 'none' ?>">
                        <table class="table table-responsive" id="tabla_productos">
                            <thead>
                            <th>Producto</th>
                            <?php foreach ($unidades as $unidad) { ?>
                                <th><?= $unidad['nombre_unidad'] ?></th>
                            <?php } ?>
                            <th></th>
                            </thead>
                            <tbody>

                            <?php


                            if (isset($paquete_has_prod) and sizeof($paquete_has_prod) > 0) :
                                foreach ($paquete_has_prod as $paquete) :

                                    ?>
                                    <tr id="tr_<?= $paquete['prod_id'] ?>">
                                        <td><?= $paquete['prod_id'] ?> - <?= $paquete['producto_nombre'] ?><input
                                                    type="hidden" name="productos_paquete[]"
                                                    onkeydown="return soloNumeros(event);"
                                                    value="<?= $paquete['prod_id'] ?>"></td>
                                        <?php
                                        foreach ($unidades as $unidad) {
                                            ?>
                                            <td>
                                                <?php
                                                $tienelaunidad = false;
                                                foreach ($paquete['unidades_medida'] as $unidades_medidas) {
                                                    if ($unidades_medidas['id_unidad'] == $unidad['id_unidad']) {
                                                        $tienelaunidad = true;
                                                    }
                                                }

                                                $cantidad = 0;

                                                foreach ($paquete['unidades'] as $unidad_has) {

                                                    if ($unidad_has['unidad_id'] == $unidad['id_unidad']) {

                                                        $cantidad = $unidad_has['cantidad'];
                                                    }
                                                }
                                                if ($tienelaunidad) {
                                                    ?>
                                                    <input name="cantidad_<?= $paquete['prod_id'] ?>[]"
                                                           value="<?= $cantidad ?>" class="form-control">
                                                    <input type="hidden" name="unidad_<?= $paquete['prod_id'] ?>[]"
                                                           class="form-control" value="<?= $unidad['id_unidad'] ?>">
                                                <?php } else {
                                                    ?>
                                                    <input readonly name="cantidad_<?= $paquete['prod_id'] ?>[]"
                                                           value="" class="form-control">
                                                    <input type="hidden" name="unidad_<?= $paquete['prod_id'] ?>[]"
                                                           class="form-control" value="<?= $unidad['id_unidad'] ?>">
                                                    <?php
                                                } ?>
                                            </td>
                                            <?php
                                        } ?>
                                        <th>
                                            <button class="btn btn-success"
                                                    onclick="Producto.deleteProd(<?= $paquete['prod_id'] ?>)"><i
                                                        class="fa fa-trash"></i></button>
                                        </th>
                                    </tr>
                                <?php endforeach;
                            endif; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


        </div>
        <div class="modal-footer">
            <div class="text-left">
                <button class="btn btn-primary" type="button" onclick="Producto.ver_catalogo()" id="btnConsulProdCoo"><i
                            class="fa fa-search"></i> Consultar Productos Proveedor Principal
                </button>
            </div>
            <div class="text-right">
                <button class="btn btn-success waves-effect waves-light m-r-10" type="button"
                        onclick="Producto.guardarproducto()" id="btnGuardar"><i class="fa fa-save"></i> Guardar
                </button>
                <input type="reset" class='btn btn-default' value="Cancelar" data-dismiss="modal">
            </div>


        </div>


    </div>
    <?= form_close() ?>

</div>

<?php

if (!isset($producto)) {

    $producto = array();
}
?>

<script>
    $(function () {
        Producto.initAgregar('<?= $countunidad ?>', <?php echo json_encode($unidades); ?>,
            '<?= $this->session->userdata('CALCULO_PRECIO_VENTA') ?>', '<?= $contador_condicion; ?>',
            <?php echo json_encode($impuestos); ?>, <?php echo json_encode($condiciones); ?>,
            <?php echo json_encode($precios_producto); ?>, <?php echo json_encode($producto); ?>);
    });
</script>