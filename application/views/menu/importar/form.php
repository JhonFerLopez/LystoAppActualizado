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

            <div class="block-title">
                <h2><strong>Importar Datos</strong></h2>
            </div>





            <input type="hidden" name="id" id="id"
                   class='form-control' autofocus="autofocus" maxlength="15"
                   value="<?php if (isset($producto['producto_id'])) echo $producto['producto_id'] ?>">

            <div id="mensaje"></div>


            <ul class="nav customtab nav-tabs" role="tablist">

                <li class='nav-item' role="presentation">
                    <a href="#productos" class="nav-link active" aria-controls="lista" role="productos"
                       data-toggle="tab">Productos</a>
                </li>


                <li class='nav-item' role="grupos">
                    <a href="#grupos" class="nav-link" aria-controls="grupos" role="grupos"
                       data-toggle="tab">Grupos</a>
                </li>

                <li class='nav-item' role="clientes">
                    <a href="#clientes" class="nav-link" aria-controls="clientes" role="clientes"
                       data-toggle="tab">Clientes</a>
                </li>

            </ul>

            <div class="tab-content row" style="height: auto">


                <div class="tab-pane col-md11 active in " role="tabpanel" id="productos" role="tabpanel">


                    <div class="row">

                            <div class="col-md-12">
                                <h3 class="text-warning">
                                    Convierta el archivo que contiene los datos a xls, luego, en Excel <br>
                                        presione "Guardar Como" y seleccione en el tipo de archivo: CSV delimitado por
                                        comas ","
                             </h3>
                            </div>


                    </div>
                    <?= form_open_multipart(base_url() . 'importar/productosdos', array('id' => 'formguardar_productosdos')) ?>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">Productos con DOS unidades de medida,
                                    Seleccione el archivo en formato CSV:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="userfile"
                                       id=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar('formguardar_productosdos')">Confirmar</button>

                    </div>

                    <?= form_close() ?>

                    <br>

                    <?= form_open_multipart(base_url() . 'importar/productosdosCodigoBarra', array('id' => 'formProductosdosCodigoB')) ?>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">Ingrese el mismo archivo de Dos unidades de Medida.
                                    Solo se guardar&aacute;n los c&oacute;digos de barra:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="codigobarrados"
                                       id=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar('formProductosdosCodigoB')">Confirmar</button>

                    </div>

                    <?= form_close() ?>

                    <br>

                    <?= form_open_multipart(base_url() . 'importar/productostres', array('id' => 'formguardar_productostres')) ?>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">Productos con TRES unidades de medida,
                                    Seleccione el archivo en formato CSV:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="archivo"
                                       id="productostres">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">C&oacute;digos de Barra,
                                    Seleccione el archivo en formato CSV:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="codigos_barra"
                                       id="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar('formguardar_productostres')">Confirmar</button>

                    </div>

                    <?= form_close() ?>
                    <br>

                    
                </div>


                <div class="tab-pane col-md11 in " role="tabpanel" id="grupos" role="tabpanel">


                    <div class="row">

                        <div class="col-md-12">
                            <h3 class="text-warning">
                                Convierta el archivo que contiene los datos a xls, luego, en Excel <br>
                                presione "Guardar Como" y seleccione en el tipo de archivo: CSV delimitado por
                                comas ","
                            </h3>
                        </div>


                    </div>
                    <?= form_open_multipart(base_url() . 'importar/grupos', array('id' => 'formguardar_grupos')) ?>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">Grupos, Seleccione el archivo en formato CSV:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="userfilegrupo"
                                       id=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar('formguardar_grupos')">Confirmar</button>

                    </div>

                    <?= form_close() ?>

                </div>



                <div class="tab-pane col-md11 in " role="tabpanel" id="clientes" role="tabpanel">


                    <div class="row">

                        <div class="col-md-12">
                            <h3 class="text-warning">
                                Convierta el archivo que contiene los datos a xls, luego, en Excel <br>
                                presione "Guardar Como" y seleccione en el tipo de archivo: CSV delimitado por
                                comas ","
                            </h3>
                        </div>


                    </div>
                    <?= form_open_multipart(base_url() . 'importar/clienteCustom', array('id' => 'formguardar_clientes')) ?>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-4">
                                <label class="control-label">Clientes, Seleccione el archivo en formato CSV:</label>
                            </div>
                            <div class="col-md-5">
                                <input type="file" accept=".csv" class="form-control" name="clientes"
                                       id=""
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar('formguardar_clientes')">Confirmar</button>

                    </div>

                    <?= form_close() ?>

                </div>


            </div>


        </div>

    </div>


</div>




<script>

    $(document).ready(function () {

    })
    var grupo = {
        ajaxgrupo: function () {
            return $.ajax({
                url: '<?= base_url()?>importar'

            })
        },
        guardar: function (form) {
            Utilities.showPreloader();
            Utilities.alertModal('<h4>Alerta</h4> <p>Esto puede tardar varios minutos,' +
                ' aunque parezca que no est&aacute; trabajando no cierre la ventana</p>', 'info', false);

            var formData = new FormData($("#"+form)[0]);
            var contentType = false;
            var callback = this.ajaxgrupo;

            $.ajax({
                url: $("#"+form).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (data) {


                    if (data.error == undefined) {
                        Utilities.closeAlertModal();
                        var resultcal = callback();


                        resultcal.success(function (data2) {
                            $('#page-content').html(data2);
                        });

                        Utilities.hiddePreloader();

                        if (data.success) {

                            setTimeout(function () {

                                Utilities.alertModal(data.success, 'success', false);
                            }, 500)
                        }

                    } else {
                        Utilities.closeAlertModal();
                        Utilities.hiddePreloader();
                        Utilities.alertModal(data.error, 'error');
                    }
                },
                error: function (response) {
                    Utilities.closeAlertModal();
                    Utilities.hiddePreloader();
                    Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'error');

                }
            })
        }
    }

</script>
