<?php $ruta = base_url(); ?>

<input id="producto_cualidad" type="hidden">

<div class="row">
    <div class="col-md-12">
        <div class="white-box">


            <form id="frmCompra">

                <input id="ingreso_id" name="id_ingreso" type="hidden"
                       value="<?php if (isset($ingreso->id_ingreso)) echo $ingreso->id_ingreso; ?>">

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group">

                                <div class="col-md-3">
                                    <label for="Proveedor" class="control-label">Proveedor:</label>

                                    <select name="cboProveedor"
                                            id="cboProveedor" onchange="Compra.verificarProveedor()"
                                            class='cho form-control' required="true" required="true">
                                        <option value="">Seleccione</option>
                                        <?php if (count($lstProveedor) > 0): ?>
                                            <?php foreach ($lstProveedor as $pv): ?>
                                                <option
                                                        value="<?php echo $pv->id_proveedor; ?>" <?php if (!isset($ingreso->id_proveedor) && strtoupper($pv->proveedor_nombre) === 'OTROS') echo 'selected' ?>   <?php if (isset($ingreso->id_proveedor) && $ingreso->id_proveedor == $pv->id_proveedor) echo 'selected'; ?>><?php echo $pv->proveedor_nombre; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label class="control-label">N&uacute;mero de documento:</label>

                                    <input type="text" class='input-medium required form-control'
                                           name="doc_numero" id="doc_numero"
                                           required="true"
                                           maxlength="20"
                                           value="<?php echo isset($ingreso->documento_numero) ? $ingreso->documento_numero : '' ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Condici&oacute;n de pago:</label>
                                    <select class="form-control select-chosen" name="condicion_pago" id="condicion_pago"
                                            onchange="Cartera.toogleBanco()">
                                        <?php
                                        if (count($metodos) > 0) {
                                            foreach ($metodos as $metodo) { ?>
                                                <option
                                                        value="<?= $metodo['id_condiciones'] ?>" <?php if ((isset($ingreso->condicion_pago) && $ingreso->condicion_pago == $metodo['id_condiciones']) or (!isset($ingreso->condicion_pago) && $metodo['dias'] == 0)) echo 'selected'; ?>><?= $metodo['nombre_condiciones'] ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for=" Fecha" class="control-label">Fecha:</label>

                                    <input type="text" name="fecEmision" readonly id="fecEmision" required="true"
                                           class="form-control"
                                           value="<?php echo isset($ingreso->fecha_emision) ?
                                               date('d-m-Y', strtotime($ingreso->fecha_emision)) : date('d-m-Y') ?>">
                                </div>


                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?php foreach ($unidades_medida as $unidad) { ?>
                                        <div class="col-md-2">
                                            Existencia en <?= $unidad['nombre_unidad'] ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label id="existencia_<?= $unidad['id_unidad'] ?>"
                                                   class="label label-warning"
                                            ></label>
                                        </div>
                                    <?php } ?>

                                </div>
                                <?php
                                if(isset($condiciones) and count($condiciones)>0) {
                                    foreach ($condiciones as $condicion) { ?>
                                        <div class="row">
                                            <?php foreach ($unidades_medida as $unidad) { ?>
                                                <div class="col-md-2">
                                                    Precio
                                                    a <?= $condicion['nombre_condiciones'] ?> <?= $unidad['nombre_unidad'] ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <label id="ultimoprecioventa_<?= $condicion['id_condiciones'] ?>_<?= $unidad['id_unidad'] ?>"
                                                           class="label label-warning ultimosprecios"
                                                    ></label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    <?php }
                                }
                                ?>
                                <div class="row">

                                    <div class="col-md-2" id="">
                                        C&oacute;digo:
                                    </div>
                                    <div class="col-md-2" id="">
                                        <input type="text" id="mostrar_codigo" class="form-control"
                                               disabled>
                                    </div>

                                    <div class="col-md-4" id="mostrar_nombre">
                                        Nombre:
                                    </div>


                                    <div class="col-md-2" id="">
                                        &Uacute;ltimo costo de compra:
                                    </div>

                                        <div class="col-md-2" id="">
                                            <div class="input-group">
                                        <div class="input-group-addon" id="imagenultimocosto">

                                        </div>
                                        <input type="text" class="form-control" id="ultimo_costo_compra" disabled>
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-2" id="abrirNuevoProducto">

                                        <a class="" href="#"
                                           onclick="Producto.agregar(Compra.addCompraEnProducto);"><i
                                                    class="fa fa-plus-circle"></i> Nuevo Producto</a>

                                    </div>
                                    <div class="col-md-2" id="">

                                    </div>

                                    <div class="col-md-2" id="">
                                    <label class="switch switch-primary" data-toggle="tooltip" title="">
                                        <span>Desea colocar los mismos precios de Contado en Cr&eacute;dito? </span>
                                        <input type="checkbox" id="creditosmismo"
                                               onclick="Compra.contadoacredito()" name="creditosmismo" >

                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="table-responsive  " id="open_table_compra" style="position: relative;">

                                    <table
                                            class="table table-striped dataTable table-bordered
                                             table-hover table-featured"
                                            id="tabla_lista_productos">

                                        <thead class="" id="theadtabla_ingresos">

                                        </thead>
                                        <tbody class="" id="tbodyproductos">

                                        <!--  Carga el tbody de Otros proveedores por defecto  -->
                                        <tr id="trvacio">

                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control inputsearchproduct">
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px" class='nombre'>
                                                <input type="text" readonly class="form-control " style='width:250px !important'">
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px" class='nombre'>
                                                <input type="text" readonly class="form-control "">
                                            </td>
                                            <?php
                                            $cont = 0;

                                            //campo cantidad y costo por unidad
                                            foreach ($unidades_medida as $unidad):

                                                ?>

                                                <td style="padding-top: 0px; padding-bottom: 0px"><input
                                                            type="text"
                                                            class="form-control"
                                                            readonly>
                                                </td>
                                                <td style="padding-top: 0px; padding-bottom: 0px"><input
                                                            type="text"
                                                            class="form-control"
                                                            readonly>
                                                </td>
                                                <?php

                                            endforeach;
                                            ?>

                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control " readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control " readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control " readonly>
                                            </td>

                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control " readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px">
                                                <input type="text" class="form-control " readonly>
                                            </td>
                                            <?php

                                            //contenido interno dinamico
                                            foreach ($unidades_medida as $unidad): ?>

                                                <td style="padding-top: 0px; padding-bottom: 0px"><input
                                                            type="text"
                                                            class="form-control"
                                                            readonly>
                                                </td>

                                            <?php endforeach;

                                            //utilidad y precio dinamico
                                            foreach ($condiciones as $condicion_pago):
                                                foreach ($unidades_medida as $unidad): ?>

                                                    <th style="padding-top: 0px; padding-bottom: 0px"><input
                                                                type="text"
                                                                class="form-control"
                                                                readonly>
                                                    </th>
                                                    <th style="padding-top: 0px; padding-bottom: 0px"><input
                                                                type="text"
                                                                class="form-control"
                                                                readonly></th>
                                                <?php endforeach;
                                            endforeach;
                                            ?>
                                            <td style="padding-top: 0px; padding-bottom: 0px"><input type="text" style='width:150px !important'"
                                                                                                     class="form-control"
                                                                                                     readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px"><input type="text"
                                                                                                     class="form-control"
                                                                                                     readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px"><input type="text"
                                                                                                     class="form-control"
                                                                                                     readonly>
                                            </td>
                                            <td style="padding-top: 0px; padding-bottom: 0px"><input type="text"
                                                                                                     class="form-control"
                                                                                                     readonly>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="bold control-label">Totales
                                            factura <?= MONEDA ?>:</label>


                                    </div>
                                    <div class="col-md-10">
                                        <div id="" class="row">

                                            <div class="col-md-2 bold"> Productos</div>
                                            <div class="col-md-2 bold"> Total Costo</div>
                                            <div class="col-md-2 bold">Total Iva</div>
                                            <div class="col-md-2 bold" id="th_total_facturado">Total facturado</div>

                                            <div class="col-md-2 bold" id="th_total_bonificado">Total
                                                Bonificado
                                            </div>
                                            <div class="col-md-2 bold">Total Descuento</div>
                                        </div>
                                            <div id="" class="row">
                                                <div class="col-md-2">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_productos"
                                                           id="total_productos"
                                                           readonly value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_costo"
                                                           id="total_costo"
                                                           readonly value="0.00">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_iva"
                                                           id="total_iva"
                                                           readonly value="0.00">
                                                </div>
                                                <div class="col-md-2" id="div_total_facturado">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_facturado"
                                                           id="total_facturado"
                                                           readonly value="0.00">
                                                </div>
                                                <div class="col-md-2" id="div_total_bonificado">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_bonificado"
                                                           id="total_bonificado"
                                                           readonly value="0.00">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                           class='input-square input-small form-control'
                                                           name="total_descuento"
                                                           id="total_descuento"
                                                           readonly value="0.00">
                                                </div>


                                            </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="control-label bold">Cantidades:</label>


                                    </div>
                                    <div class="col-md-10">

                                        <div class="row">

                                            <?php foreach ($unidades_medida as $unidad) { ?>

                                                <div class="col-md-2 bold">
                                                    Cantidad <?= $unidad['nombre_unidad'] ?>
                                                </div>
                                            <?php } ?>

                                        </div>


                                        <div class="row">

                                            <?php foreach ($unidades_medida as $unidad) { ?>
                                                <div class="col-md-2 ">
                                                    <input
                                                            name="total_cantidad_[<?= $unidad['id_unidad'] ?>]"
                                                            id="total_cantidad_<?= $unidad['id_unidad'] ?>" value="0"
                                                            type="text"
                                                            class="form-control" readonly>

                                                </div>
                                            <?php } ?>
                                            <div class="col-md-2">


                                                <button class="btn btn-success btn-outline waves-effect waves-light btn-group-justified"
                                                        id="btnGuardarCompra"
                                                        type="button"><i
                                                            class="fa fa-save fa-4x"></i> </br>
                                                    <b>GUARDAR (F6)</b>

                                                </button>
                                            </div>
                                            <div class="col-md-2" id="divBtnCompraPendiente">
                                            <button class="btn btn-default btn-outline waves-effect waves-light btn-group-justified "
                                                    id="btnCompraPendiente" onclick="Compra.preguntarAntesDeSalir();"
                                                    type="button" >
                                                <i class="fa fa-clock-o fa-4x "></i> </br>
                                                <b>COMPRA PENDIENTE</b>
                                            </button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>


                    </div>
            </form>
        </div>


        <div class="modal fade" id="confirmTipoProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">


            <div class="modal-dialog" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="$('#confirmTipoProducto').modal('hide')"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Confirmar</h4>
                    </div>
                    <div class="modal-body">
                        <p id="">El producto seleccionado es un Obsequio, un Prepack o un Producto Especial?</p>


                    </div>

                    <div class="modal-footer">


                        <div class="text-left col-md-2" id=""><a href="#" class="btn btn-primary"
                                                                 style="text-align: left"
                                                                 onclick="Compra.obsequios()">Obsequio</a></div>
                        <div class="text-left col-md-2" id="">
                            <a href="#" class="btn btn-primary"
                               style="text-align: left" onclick="Compra.prepack()">Prepack</a></div>

                        <div class="text-left col-md-2" id="">
                            <a href="#" class="btn btn-primary" title=""
                               style="text-align: left" onclick="Compra.producto_separata()">Producto Especial</a></div>

                        <div class="text-right" id="">
                            <button type="button" class="btn btn-default"
                                    onclick="$('#confirmTipoProducto').modal('hide')">
                                Cancelar
                            </button>
                        </div>



                    </div>
                </div>
                <!-- /.modal-content -->
            </div>


        </div>


        <div class="modal fade" id="productomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">


        </div>


        <div class="modal fade" id="modal_prepacks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="$('#modal_prepacks').modal('hide')"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Prepack</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group" id="">

                            <div class="col-md-3">

                                Seleccione los productos que componen el prepack:

                            </div>
                            <div class="col-md-5">
                                <select name=""
                                        id="selectProductosPrepacks" placeholder="Seleccione"
                                        class='cho form-control'>

                                </select>

                            </div>

                            <div class="col-md-4">
                                <button type="button" id="addPrepack" class="btn btn-success"
                                        onclick="Compra.enviarProdToPrepack()">Agregar
                                </button>
                            </div>
                        </div>

                        <div class="form-group" id="">
                            <div class="col-md-3">

                                El costo del Prepack es:

                            </div>

                            <div class="col-md-5">

                                <p id="monto_prepack"></p>

                            </div>


                        </div>

                        <div class="form-group" id="">
                            <div class="col-md-3">

                                Total Costo Productos:

                            </div>

                            <div class="col-md-5">

                                <p id="restante_prepack"></p>

                            </div>


                        </div>

                        <div class="form-group" id=""
                             style="">
                            <table class="table table-responsive" id="">
                                <thead>
                                <th>C&oacute;digo</th>
                                <th>Producto</th>
                                <?php
                                $cont = 0;
                                foreach ($unidades_medida as $unidad) {
                                    if ($cont == 0) {
                                        $cont++;
                                        ?>
                                        <th>Cantidad <?= $unidad['nombre_unidad'] ?></th>
                                        <th>Costo Total <?= $unidad['nombre_unidad'] ?></th>
                                        <?php
                                    }
                                } ?>

                                </thead>
                                <tbody id="tbody_productos_prepack">

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer" id="footer_modal_prepack">
                        <button type="button" id="confirmarGuardarPrepack" class="btn btn-primary"
                                onclick="Compra.validarDescomPrepack()">
                            Confirmar
                        </button>
                        <button type="button" class="btn btn-default" onclick="$('#modal_prepacks').modal('hide')">
                            Cancelar
                        </button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

        </div>


        <div class="modal fade" id="modal_obsequios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="$('#modal_obsequios').modal('hide')"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Obsequio</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group" id="">
                            <div class="col-md-3">

                                Este obsequio fu&eacute; asociado a:

                            </div>

                            <div class="col-md-6 text-right">
                                <label id="" class="label label-warning">Si desea asociarlo a otro producto
                                    debe
                                    eliminar <br>
                                    el actual, haciendo click en boton eliminar</label>
                            </div>
                        </div>

                        <div class="form-group" id=""
                             style="">
                            <table class="table table-responsive" id="">
                                <thead>
                                <th>C&oacute;digo</th>
                                <th>Producto</th>
                                <?php
                                $cont = 0;
                                foreach ($unidades_medida as $unidad) {
                                    if ($cont == 0) {
                                        $cont++;
                                        ?>
                                        <th>Cantidad <?= $unidad['nombre_unidad'] ?></th>
                                        <th>Costo <?= $unidad['nombre_unidad'] ?></th>
                                        <?php
                                    }
                                } ?>

                                <th>Opci&oacute;n</th>

                                </thead>
                                <tbody id="tbody_productos_obsequios">

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer" id="footer_modal_prepack">

                        <button type="button" class="btn btn-default" onclick="$('#modal_obsequios').modal('hide')">
                            Salir
                        </button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

        </div>

        <div class="modal fade bs-example-modal-lg" id="modal_codigo_barra" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="Compra.cerrarmodalCodigoBarra()"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title" id="texto_modal_prod">C&oacute;digos de Barra </h4> <h5
                                id="nombreproduto_codigo"></h5>
                    </div>
                    <div class="modal-body" id="">

                        <div class="row">
                            <div class="form-group" id="div_inicial_barra">
                                <div class="col-md-3">
                                    <label class="control-label panel-admin-text">Ingrese el nuevo c&oacute;digo de
                                        barra: </label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="codigos_barracompra[]" id="codigo_barra_originalCompra"
                                           class='cho form-control' placeholder=""
                                           value=""/>

                                </div>

                                <div class="col-md-2">
                                    <button class="fa fa-plus btn-default" title="Agregar" id="agregar_barraCompra"></button>
                                </div>
                            </div>

                        </div>
                        <div class="row" id="" style="text-align: right">

                            <div class="col-md-3">
                                <label class="control-label panel-admin-text">Agregados:</label>
                            </div>

                        </div>

                        <div class="row" id="abrir_codigos_barra">

                        </div>


                    </div>
                    <div class="modal-footer" id="">
                        <div class="text-right">
                            <a href="#" class="btn btn-primary" onclick="Compra.guardarCodigosBarra()">Guardar</a>
                            <a href="#" class="btn btn-default" onclick="Compra.cerrarmodalCodigoBarra()">Salir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" id="modal_compra_bodegas" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                aria-hidden="true" onclick="$('#modal_compra_bodegas').modal('hide')">&times;
                        </button>
                        <h4 class="modal-title" id="texto_modal_prod">Mover a Bodegas </h4> <h5
                                id="nombreproducto_bodega"></h5>
                    </div>
                    <div class="modal-body" id="">

                        <div class="row" id="append_div_bodegas">

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="" class="control-label">Seleccione la Bodega:</label>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="selectbodegas"
                                                id="selectbodegas"
                                                class='cho form-control'  >
                                            <?php if (count($locales) > 0): ?>
                                                <?php foreach ($locales as $local): ?>
                                                    <option
                                                            value="<?php echo $local->int_local_id; ?>"><?php echo $local->local_nombre; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <table id="table_bodegas" >
                                        <thead id="thead_table_bodegas"></thead>
                                        <tbody id="tbody_table_bodegas" ></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer" id="">
                        <div class="col-md-12">
                            <div class="col-md-8">
                        <div class="text-left">
                            <a href="#" class="btn btn-primary"
                               onclick="Compra.limpiarcantBodegas(Compra.oproducto_o_codigo(Compra.producto_seleccionadoid, Compra.producto_seleccionadocodigo))"
                            >Limpiar Todo</a>
                        </div>
                            </div>
                        <div class="text-right">
                            <div class="col-md-4">
                            <a href="#" class="btn btn-primary" onclick="Compra.guardarArrBodegas()">Guardar</a>

                            <a href="#" class="btn btn-default"
                               onclick="$('#modal_compra_bodegas').modal('hide')">Salir</a>
                                </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal bs-example-modal-xl" id="seleccionunidades" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closeseleccionunidades"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title" id="texto_modal_prod">Productos</h4> <h5 id="nombreproduto"></h5>
                    </div>
                    <div class="modal-body" id="modalbodyproducto">

                        <div class="row">
                            <table id="tablaproductos" class="table datatable table-bordered table-striped ">
                                <thead>
                                <th>ID</th>
                                <?php
                                $cont = 0;
                                $yaentroenunidades = false;
                                if ($columnasToProd) {

                                    foreach ($columnasToProd as $columna) {

                                        if ($columna->mostrar == 1) {

                                            if (

                                                $columna->nombre_columna == 'cant'
                                                ||
                                                $columna->nombre_columna == 'precio'
                                                ||
                                                $columna->nombre_columna == 'porcent_utilidad'


                                            ) {

                                                if ($yaentroenunidades == false) {

                                                    $yaentroenunidades = true;

                                                    foreach ($unidades_medida as $unidad) {

                                                        if ($columna->nombre_columna == 'cant') {
                                                            echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                            if ($columnasToProd[6]->mostrar == 1) echo '<th>' . $columnasToProd[6]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                            if ($columnasToProd[7]->mostrar == 1) echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                        }

                                                        if ($columna->nombre_columna == 'precio') {
                                                            echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                            if ($columnasToProd[7]->mostrar == 1) echo '<th>' . $columnasToProd[7]->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                        }

                                                        if ($columna->nombre_columna == 'porcent_utilidad') {
                                                            echo '<th>' . $columna->nombre_mostrar . ' ' . $unidad['nombre_unidad'] . '</th>';
                                                        }

                                                    }

                                                }

                                            } else {
                                                echo '<th>' . $columna->nombre_mostrar . '</th>';
                                            }

                                        }
                                        $cont++;
                                    }
                                }

                                ?>

                                </thead>
                                <tbody id="preciostbody"></tbody>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer" id="footer_seleccionunidades"></div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="catalogoIngreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">


        </div>

        <div class="modal fade" id="catalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">


        </div>

        <div class="modal fade" id="confirmar_salida_ingreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Confirmar</h4>
                    </div>
                    <div class="modal-body">
                        <p id="">Est&aacute; seguro que desea cerrar esa ventana?</p>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="confirmarSaveCatalogo" class="btn btn-primary"
                                onclick="Compra.guardarCheckCatalogo();">
                            Confirmar
                        </button>
                        <button type="button" class="btn btn-default" onclick="Compra.cerrarmodalConfirmarCatalogo()">
                            Cancelar
                        </button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

        </div>

        <div class="modal fade" id="confirmar_catalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" style="width: 60%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="Compra.cerrarmodalConfirmarCatalogo()"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Confirmar</h4>
                    </div>
                    <div class="modal-body">
                        <p id="textoConfirmarCatalogo">Est&aacute; seguro que desea asociar el producto?</p>
                        <input type="hidden" name="id" id="id_borrar">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="confirmarSaveCatalogo" class="btn btn-primary"
                                onclick="Compra.guardarCheckCatalogo();">
                            Confirmar
                        </button>
                        <button type="button" class="btn btn-default" onclick="Compra.cerrarmodalConfirmarCatalogo()">
                            Cancelar
                        </button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

        </div>

        <div class="modal fade" id="confirmarmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="$('#confirmarmodal').modal('hide');"
                                aria-hidden="true">&times;
                        </button>
                        <h4 class="modal-title">Confirmar</h4>
                    </div>
                    <div class="modal-body">
                        <p>Est&aacute; seguro que desea registrar el ingreso de los productos seleccionados?</p>
                        <input type="hidden" name="id" id="id_borrar">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="botonconfirmar" class="btn btn-primary"
                                onclick="Compra.guardaringreso();">
                            Confirmar
                        </button>
                        <button type="button" class="btn btn-default" onclick="$('#confirmarmodal').modal('hide');">
                            Cancelar
                        </button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

        </div>


        <div class="modal fade" id="modal_select_ruta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" style="width: 40%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="Compra.cerrar_modalruta()" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Confirmar</h4>
                    </div>

                    <div class="modal-body">

                        <div class="form-group" id="row_ruta_o_manual">
                            <div class="row" id="">
                                <div class="col-md-12"><H3>Para realizar la carga manual presione F1</H3></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><H3>Para realizar la carga automatica F2</H3></div>
                            </div>
                        </div>

                        <?= form_open_multipart(base_url() . 'ingreso/procesar_arcoopidrogas', array('id' => 'formselect_ruta')) ?>
                        <div class="row" id="row_buscar_ruta" style="display: none">
                            <div class="form-group">
								<div class="col-md-12">
									<label class="col-md-6">Factura con condición especial?</label>
									<label class="col-md-1"></label>
									<label class="col-md-1">SI</label>
									<div class="col-md-1">
										<input type="checkbox" class="form-control" name="facturaespecial" id="checkfacturaespecial">
									</div>
								</div>
								<br>
								<br>
								<div class="col-md-12">

                                <div class="col-md-3">Seleccione el archivo:</div>
                                <div class="col-md-8">
                                    <input type="file" name="buscar_ruta" id="buscar_ruta" class="form-control"
                                           accept=".dat">
                                </div>
								</div>
                            </div>
                        </div>
                        <?= form_close(); ?>

                    </div>
                    <div class="modal-footer" id="footer_modal_select_ruta">

                        <button type="button" class="btn btn-primary pull-right btn-group-justified "
                                id="boton_carga_automatica"
                                onclick="Compra.mostrarRow_buscar_ruta()">
                            CARGA AUTOMATICA(F2)
                        </button>

                        <button type="button" class="btn btn-default pull-right btn-group-justified "
                                onclick="Compra.cerrar_modalruta()">
                            CARGA MANUAL(F1)
                        </button>

                        <button type="button" id="btn_procesar_archivo" style="display: none;"
                                class="btn btn-primary pull-right btn-group-justified "
                                onclick="Compra.procesar_archivo()">
                            Confirmar
                        </button>


                    </div>


                </div>
                <!-- /.modal-content -->
            </div>

        </div>
    </div>
</div>
<script>

    $(document).ready(function () {

        App.sidebar('close-sidebar');

        Compra.init('<?= $id_ingreso ?>',<?php echo json_encode($ingreso); ?>, '<?= $this->session->userdata('CALCULO_PRECIO_VENTA') ?>',
            <?php echo json_encode($unidades_medida); ?>,<?php echo json_encode($condiciones); ?>,<?php echo json_encode($tipos_productos); ?>,
            <?php echo json_encode($ubicaciones); ?>,<?php echo json_encode($grupos); ?>,<?php echo json_encode($detalles); ?>,
            <?php echo json_encode($detalle_especial); ?>,'<?=  INGRESO_PENDIENTE ?>');
    });


    function procesar_catalogo(check) {

        //el modal del catalogo coopidrogas es llamado tanto desde prepack y obsequios
        Compra.procesar_catalogo(check);

    }

</script>

