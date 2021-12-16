<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Productos</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Productos</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">


            <div class="row">
                <div class="btn-group m-b-20">
                    <a class="btn btn-info waves-effect waves-light" onclick="Producto.agregar(false);">
                        <i class="fa fa-plus "> </i>Nuevo
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light" onclick="Producto.duplicar();">
                        <i class="fa fa-angle-double-right "> </i>Duplicar
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light"
                       onclick="Producto.editarProducto();">
                        <i class="fa fa-edit"> </i>Editar
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light" onclick="Producto.confirmar();">
                        <i class="fa fa-remove"> </i>Eliminar
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light" onclick="Producto.ver_imagen();">
                        <i class="fa fa-columns"></i> Imagen
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light" onclick="Producto.columnas();">
                        <i class="fa fa-columns "> </i>Columnas
                    </a>

                    <a class="btn btn-default btn-outline waves-effect waves-light" onclick="Producto.updateCostos();">
                        <i class="fa fa-money "> </i>Actualizar costos
                    </a>
                    <a class="btn btn-default btn-outline waves-effect waves-light"
                       onclick="Producto.updateCostosPromedio();">
                        <i class="fa fa-money "> </i>Actualizar costos Promedio
                    </a>
                    <a class="btn btn-default btn-outline waves-effect waves-light"
                       onclick="Producto.preciosEnLoteModal();">
                        <i class="fa fa-money "> Precios en lote</i>
                    </a>

                </div>
            </div>
            <br>

            <div class="row">
                <div class="form-group">
                    <div class="col-md-1">
                        <label>Bodega</label>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control cho" id="locales" onchange="Producto.filterProducts()">

                            <?php foreach ($locales as $local) { ?>
                                <option value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Control de inventario</label>
                    </div>
                    <div class="col-md-4">
                        <select onchange="Producto.filterProducts()" id="control_inventario" name="control_inventario">
                            <option value="">TODOS</option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                            <option value="null">SIN CONFIGURAR</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-1">
                        <label>Grupo</label>
                    </div>

                    <div class="col-md-5">
                        <select onchange="Producto.filterProducts()" id="produto_grupo" name="produto_grupo">
                            <option value="">TODOS</option>
                            <?php
                            if (sizeof($grupos) > 0):
                                foreach ($grupos as $grupo) { ?>
                                    <option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre_grupo'] ?></option>
                                <?php }
                            endif; ?>

                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Control de inventario diario</label>
                    </div>
                    <div class="col-md-4">
                        <select onchange="Producto.filterProducts()" id="control_inventario_diario" name="control_inventario_diario">
                            <option value="">TODOS</option>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                            <option value="null">SIN CONFIGURAR</option>
                        </select>
                    </div>



                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-1">
                        <label>Activo</label>
                    </div>

                    <div class="col-md-5">
                        <select onchange="Producto.filterProducts()" id="producto_activo" name="producto_activo">
                            <option value="">TODOS</option>
                            <option value="1" selected>SI</option>
                            <option value="0">NO</option>
                           

                        </select>
                    </div>

                  


                </div>
            </div>
            <div class="table-responsive" id="productostable">
                <table class='table table-striped dataTable table-bordered' id="table">
                    <thead>
                    <tr>
                        <?php foreach ($columnas as $col): ?>
                            <?php if ($col->mostrar == TRUE && $col->nombre_columna != 'producto_activo') {

                                if ($col->nombre_columna == 'producto_precios') {

                                    if ($condicionesDePago) {

                                        if (count($unidades) > 0) {

                                            foreach ($condicionesDePago as $condicionPago) {

                                                foreach ($unidades as $todas) {
                                                    echo " <th>% UTILIDAD " . $condicionPago->nombre_condiciones . " " . $todas['nombre_unidad'] . "</th>";
                                                    echo " <th>% PRECIO " . $condicionPago->nombre_condiciones . " " . $todas['nombre_unidad'] . "</th>";
                                                }
                                            }
                                        }
                                    }

                                } else {
                                    echo " <th>" . $col->nombre_mostrar . "</th>";
                                }
                            }
                           endforeach; ?>

                        <?php
                        //tomando en cuenta de que siemprd va a venir caja de primero, luego blister, luego unidad, ya que el orden
                        //puede depender de cada producto
                        if (count($unidades) > 0) {
                            foreach ($unidades as $row): ?>
                                <th> Inventario <?= $row['nombre_unidad'] ?>    </th>

                            <?php endforeach;

                        } ?>


                        <th>Imagen</th>
                        <th>Activo</th>


                    </tr>
                    </thead>
                    <tbody id="tbody">


                    </tbody>
                </table>
            </div>


        </div>


    </div>
</div>


<div class="modal fade" id="imagen_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>

<div class="modal fade" id="productomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>

<div class="modal fade" id="columnas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>

<div class="modal fade" id="catalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>
<div class="modal fade" id="precioslote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formprecioslote" id="formprecioslote" method="post" action="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Actualizar precios en lote</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-info">Los precios se incrementarán con base al margen de utilidad
                        ingresada sobre el costo unitario, los productos que no tengan costo unitario
                        configurado serán omitidos
                    </div>
                    <div class="row">
                        <div class="col-md-4">Precio a actualizar</div>
                        <div class="col-md-8">
                            <select name="precio_actulizar" id="precio_actualizar" class="form-control">

                                <?php foreach ($precios as $precio) {
                                    ?>

                                    <option value="<?= $precio['id_condiciones'] ?>"><?= $precio['nombre_condiciones'] ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">Precio base</div>
                        <div class="col-md-8">
                            <select name="precio_base" id="precio_base" class="form-control">
                                <option value="COSTO">COSTO</option>
                                <?php foreach ($precios as $precio) {
                                    ?>

                                    <option value="<?= $precio['id_condiciones'] ?>"><?= $precio['nombre_condiciones'] ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>


                    <input type="hidden" name="tipo_calculo"
                           value="<?= $this->session->userdata("CALCULO_PRECIO_VENTA") ?>">

                    <div class="row">
                        <div class="col-md-4">Porcentaje de utilidad</div>
                        <div class="col-md-8">
                            <input type="text" name="porcentaje" id="porcentaje"
                                   onkeydown="return soloDecimal3(this, event);" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">Tipos de producto</div>
                        <div class="col-md-8">
                            <select name="tipo_producto[]" id="tipo_producto" class="form-control" multiple
                                    data-placeholder="Tipo de producto">
                                <option value="-1">Todos los productos</option>
                                <?php foreach ($tipos_producto as $tipo) { ?>

                                    <option value="<?= $tipo['tipo_prod_id'] ?>"><?= $tipo['tipo_prod_nombre'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">Redondeo</div>
                        <div class="col-md-8">
                            <select class="form-control" name="redondeo" id="redondeo">
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                            onclick="Producto.updatePreciosLote()">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </form>
</div>


<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="formeliminarProducto" id="formeliminarProducto" method="post"
          action="<?= $ruta ?>producto/eliminar">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Producto</h4>
                </div>
                <div class="modal-body">

                    <p>Est&aacute; seguro que desea eliminar el producto seleccionado</p>
                    <input type="hidden" name="id" id="id_borrar">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                            onclick="Producto.eliminar()">
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
    </form>
</div>

<div class="modal fade" id="confirmar_selec_catalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form name="" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Producto</h4>
                </div>
                <div class="modal-body">
                    <p>En estos momentos est&aacute; editando un Producto, si continua perder&aacute; todos los
                        cambios
                        que haya realizado.
                        Desea continuar?</p>
                    <input type="hidden" name="">

                </div>
                <div class="modal-footer">
                    <button type="button" id="id_producto_catalogo" value="" class="btn btn-primary"
                            onclick="Producto.mostrar_datos_catalogo(this.value)">Confirmar
                    </button>
                    <button type="button" class="btn btn-default" onclick="Producto.cerrar_confir_catalogo()">
                        Cancelar
                    </button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>


<script>


    $(function () {
        Producto.init(<?php echo json_encode($droguerias_relacionadas); ?>, false);

        Producto.filterProducts();
    });</script>
