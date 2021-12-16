<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Productos</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="">SID</a></li>
            <li class="active">Parametrizaci&oacute;n r&aacute;pida</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">

            <div class="row">
                <div class="btn-group m-b-20">
                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="contenido_interno"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa"> </i>Contenido interno
                    </a>

                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="precios"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa"> </i>Precios
                    </a>

                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="codigos_barra"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa"> </i>C&oacute;digos de barra
                    </a>

                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="comision"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa"> </i>% Comisi&oacute;n
                    </a>

                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="precio_abierto"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa"></i> Precio abierto
                    </a>

                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="grupo"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa "> </i>Grupo
                    </a>
                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="tipo"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa "> </i>Tipo de producto
                    </a>
                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="ubicacion_fisica"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa "> </i>Ubicaci&oacute;n f&iacute;sica
                    </a>
                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="impuestos"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa "> </i>Impuestos
                    </a>
                    <a class="btn btn-default waves-effect waves-light opciones" data-opcion="tipo_item_dian"
                       onclick="Producto.changeBtn(this);">
                        <i class="fa "> </i>Tipo de Item (Dian)
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
                        <select class="form-control cho" id="locales" onchange="Producto.getproductosParamrap()">

                            <?php foreach ($locales as $local) { ?>
                                <option value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>
                            <?php } ?>

                        </select>
                    </div>
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



                </div>
            </div>
            <br>

            <div class="table-responsive" id="productostable">
                <table class='table table-striped dataTable table-bordered' id="table">
                    <thead id="thead">
                    <tr>
                        <th>ID</th>
                        <th>C&oacute;digo</th>
                        <th id="ultimoth">Nombre</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">


                    </tbody>
                </table>
            </div>

            <div class="block-section">
                <button type="button" id="" class="btn btn-primary" onclick="Producto.guardarParamRap()">Confirmar
                </button>

            </div>
        </div>



    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="modal_codigo_barra" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="Producto.cerrarmodalCodigoBarra()"
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
                            <input type="text" name="codigos_barra[]" id="codigo_barra_original"
                                   class='cho form-control' placeholder=""
                                   value=""/>

                        </div>

                        <div class="col-md-2">
                            <button class="fa fa-plus btn-default" title="Agregar" id="agregar_barra"
                                    onclick="Producto.addBarraRap();"></button>
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
                    <a href="#" class="btn btn-primary" onclick="Producto.guardarCodigosBarra()">Guardar</a>
                    <a href="#" class="btn btn-default" onclick="Producto.cerrarmodalCodigoBarra()">Salir</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>


    $(function () {
        Producto.init(<?php echo json_encode($droguerias_relacionadas); ?>, false,<?php echo json_encode($unidades); ?>,
            <?php echo json_encode($condiciones); ?>,'<?= $this->session->userdata('CALCULO_PRECIO_VENTA') ?>');

        Producto.getproductosParamrap();
    });</script>
