<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Stock</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>
            <li class="active">Stock</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-success alert-dismissable" id="success"
                         style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                        <span id="successspan"><?php echo isset($success) ? $success : '' ?></div>
                    </span>
                </div>
            </div>

            <?php if ($tipo == 'bodegas') { ?>
                <!--<div class="row">
                    <div class="col-md-1">
                        <a class="btn btn-default" onclick="Producto.unidadesycostos();">
                            <i class="fa fa-list-ol"> Unidades y costos</i>
                        </a>
                    </div>
                </div>-->
            <?php } ?>
            <br>
            <div class="row">
                <div class="form-group">
                    <?php if ($tipo == 'bodegas') {
                        ?>
                        <div class="col-md-1">
                            <label>Ubicaci&oacute;n Inventario</label>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control" id="locales" onchange="Producto.filterProducts()">
                                <?php foreach ($locales as $local) { ?>
                                    <option value="<?= $local['int_local_id'] ?>"><?= $local['local_nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                    <?php if ($tipo == 'droguerias') {
                        ?>
                        <div class="col-md-1">
                            <label>Drogueria relacionada</label>
                        </div>
                        <div class="col-md-5">
                            <input type="hidden" name="drogueria_domain" id="drogueria_domain" value="">
                            <select class="form-control cho" id="drogueria"
                                    onchange="Producto.getproductosbyDrogueria()">

                                <?php foreach ($droguerias_relacionadas as $drogueria) { ?>
                                    <option
                                            value="<?= $drogueria['drogueria_id'] ?>"><?= $drogueria['drogueria_nombre'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <br>

            <div class="table-responsive" id="productostable">
                <table class='table table-striped dataTable table-bordered' id="table">
                    <thead>
                    <tr>
                        <?php foreach ($columnas as $col): ?>
                            <?php if ($col->mostrar == TRUE && $col->nombre_columna != 'producto_activo') echo " <th>" . $col->nombre_mostrar . "</th>" ?>
                        <?php endforeach; ?>
                        <?php
                        if (count($unidades) > 0) {
                            foreach ($unidades as $row): ?>
                                <th> Inventario <?= $row['nombre_unidad'] ?>    </th>
                            <?php endforeach;
                        } ?>
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
<div class="modal fade" id="productomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
</div>
<div class="modal fade" id="columnas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
</div>

<script type="text/javascript">

    var tipo = '<?= $tipo ?>';
    $(function () {
        Producto.init(<?php echo json_encode($droguerias_relacionadas); ?>, true);
        if (tipo == 'bodegas') {
            Producto.filterProducts();
        } else {
            Producto.getproductosbyDrogueria();
        }
    });
</script>
