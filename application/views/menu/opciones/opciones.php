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
                <div class="col-xs-12">
                    <div class="alert alert-success alert-dismissable" id="success"
                         style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
                        <span id="successspan"><?php echo isset($success) ? $success : '' ?></div>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-dismissable" id="error"
                         style="display:<?php echo isset($error) ? 'block' : 'none' ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h4><i class="icon fa fa-check"></i> Error</h4>
                        <span id="errorspan"><?php //echo isset($error) ? $error : '' ?></div>
                </div>
            </div>


            <div class="block-title">
                <h2><strong>Parámetros generales</strong></h2>
            </div>


            <?= form_open_multipart(base_url() . 'opciones/save', array('id' => 'formguardar_opciones')) ?>


            <input type="hidden" name="id" id="id"
                   class='form-control' autofocus="autofocus" maxlength="15"
                   value="<?php if (isset($producto['producto_id'])) echo $producto['producto_id'] ?>"
            >

            <div id="mensaje"></div>


            <ul class="nav customtab nav-tabs" role="tablist">
                <li class='nav-item' role="presentation">
                    <a href="#lista" class="nav-link active" aria-controls="lista" role="tab" data-toggle="tab">Datos de
                        la empresa</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#facturacion" class="nav-link" aria-controls="facturacion" role="tab" data-toggle="tab"">Facturación</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#inventario" class="nav-link" aria-controls="inventario" role="tab" data-toggle="tab"">Inventario</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#precios" class="nav-link" aria-controls="lista" role="precios" data-toggle="tab">Configuraciones</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#impresion" class="nav-link" aria-controls="lista" role="impresion" data-toggle="tab">Impresión</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#backup" class="nav-link" aria-controls="lista" role="backup" data-toggle="tab">Backup</a>
                </li>
                <li class='nav-item' role="presentation">
                    <a href="#app_customer" class="nav-link" aria-controls="lista" role="app_customer"
                       data-toggle="tab">App Customer</a>
                </li>

                <?php

                if ($this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN') { ?>
                    <li class='nav-item' role="presentation">
                        <a href="#firebase" class="nav-link" aria-controls="lista" role="firebase"
                           data-toggle="tab">Firebase</a>
                    </li>

                <?php } ?>
            </ul>


            <div class="tab-content row" style="height: auto">

                <div role="tabpanel" class="tab-pane fade active in" id="lista">

                    <div class="form-group">
                        <div class="col-md-3">
                            <label for="linea" class="control-label">Pais:</label>
                        </div>
                        <div class="col-md-8">
                            <select name="EMPRESA_PAIS" id=EMPRESA_PAIS" required="true" class="form-control"
                                    onchange="region.actualizarestados();">

                                <?php
                                $p = 1;
                                $OPCION_PAIS = $this->session->userdata('EMPRESA_PAIS');
                                foreach ($paises as $pais) {
                                    $paais['pais' . $p] = $pais['id_pais'];
                                    ?>
                                    <option
                                            value="<?php echo $pais['id_pais'] ?>" <?php if (!empty($OPCION_PAIS) and $OPCION_PAIS == $pais['id_pais']) echo 'selected' ?>><?= $pais['nombre_pais'] ?></option>
                                    <?php
                                    $p++;
                                } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-3">
                            <label for="linea" class="control-label">Idioma:</label>
                        </div>
                        <div class="col-md-8">
                            <select name="producto_marca" id="producto_marca" class='cho form-control'>
                                <option value="">Espa&nacute;ol</option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Nombre de la empresa:</label>
                        </div>

                        <div class="col-md-8">
                            <input type="text" name="EMPRESA_NOMBRE" required="true" id="EMPRESA_NOMBRE"
                                   class='form-control'
                                   maxlength="100"
                                   value="<?php echo $this->session->userdata('EMPRESA_NOMBRE'); ?>">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Direcci&oacute;n:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="EMPRESA_DIRECCION" id="EMPRESA_DIRECCION"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('EMPRESA_DIRECCION'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Tel&eacute;fono:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="EMPRESA_TELEFONO" id="EMPRESA_TELEFONO"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('EMPRESA_TELEFONO'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Representante legal:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="REPRESENTANTE_LEGAL" id="REPRESENTANTE_LEGAL"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('REPRESENTANTE_LEGAL'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Régimen contributivo:</label>
                        </div>
                        <div class="col-md-8">

                            <?php $regimenSELECTED = $this->session->userdata('REGIMEN_CONTRIBUTIVO'); ?>
                            <select name="REGIMEN_CONTRIBUTIVO" id="REGIMEN_CONTRIBUTIVO" required="true"
                                    class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($regimenes as $regimen): ?>
                                    <option
                                            value="<?php echo $regimen['regimen_id'] ?>" <?php if (isset($regimenSELECTED) and $regimenSELECTED == $regimen['regimen_id']) echo 'selected' ?>><?= $regimen['regimen_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">NIT:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="NIT" id="NIT"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('NIT'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Código Proveedor Principal:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="CODIGO_COOPIDROGAS" id="CODIGO_COOPIDROGAS"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('CODIGO_COOPIDROGAS'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Tipo de empresa:</label>
                        </div>
                        <div class="col-md-8">
                            <?php $tipo_empresa = $this->session->userdata('TIPO_EMPRESA') ?>
                            <select name="TIPO_EMPRESA">
                                <option <?php echo $tipo_empresa === 'DROGUERIA' || empty($tipo_empresa) ? 'selected' : '' ?>
                                        value="DROGUERIA">DROGUERIA
                                </option>
                                <option <?php echo $tipo_empresa == 'OTRO' ? 'selected' : '' ?> value="OTRO">OTRO
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" role="tabpanel" id="facturacion" role="tabpanel">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label">Mensaje para la factura:</label>
                            </div>
                            <div class="col-md-8">
                    <textarea name="MENSAJE_FACTURA" id="MENSAJE_FACTURA"
                              class="form-control"><?php echo $this->session->userdata('MENSAJE_FACTURA'); ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label">C&aacute;lculo de Precio de Venta:</label>
                            </div>
                            <div class="col-md-8">

                                <input type="radio" name="CALCULO_PRECIO_VENTA" id="" class=''
                                       value="MATEMATICO"
                                    <?php echo $this->session->userdata('CALCULO_PRECIO_VENTA') == "MATEMATICO" ? 'checked' : '' ?>>
                                MATEM&Aacute;TICO
                                <input type="radio" name="CALCULO_PRECIO_VENTA" id="" class=''
                                       value="FINANCIERO"
                                    <?php echo $this->session->userdata('CALCULO_PRECIO_VENTA') == "FINANCIERO" ? 'checked' : '' ?>>
                                FINANCIERO

                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Mostrar productos sin Stock:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="MOSTRAR_SIN_STOCK" id="MOSTRAR_SIN_STOCK"
                                       value="1" <?php echo ($this->session->userdata('MOSTRAR_SIN_STOCK') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label">Calculo de la utilidad(Reportes):</label>
                            </div>
                            <div class="col-md-8">

                                <input type="radio" name="CALCULO_UTILIDAD" id="" class=''
                                       value="COSTO_UNITARIO"
                                    <?php echo $this->session->userdata('CALCULO_UTILIDAD') == "COSTO_UNITARIO" ? 'checked' : '' ?>>
                                COSTO UNITARIO
                                <input type="radio" name="CALCULO_UTILIDAD" id="" class=''
                                       value="COSTO_PROMEDIO"
                                    <?php echo $this->session->userdata('CALCULO_UTILIDAD') == "COSTO_PROMEDIO" ? 'checked' : '' ?>>
                                COSTO PROMEDIO

                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Mostrar Bienes Exentos:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="MOSTRAR_PROSODE" id="MOSTRAR_PROSODE"
                                       value="1" <?php echo ($this->session->userdata('MOSTRAR_PROSODE') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>

                        </div>


                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Mostrar Datos del Vendedor en Factura:</label>
                            </div>
                            <div class="col-md-8">

                                <input type="radio" name="VENDEDOR_EN_FACTURA" id="" class=''
                                       value="CODIGO"
                                    <?php echo $this->session->userdata('VENDEDOR_EN_FACTURA') == "CODIGO" ? 'checked' : '' ?>>
                                CÓDIGO
                                <input type="radio" name="VENDEDOR_EN_FACTURA" id="" class=''
                                       value="NOMBRE"
                                    <?php echo $this->session->userdata('VENDEDOR_EN_FACTURA') == "NOMBRE" ? 'checked' : '' ?>>
                                NOMBRE

                            </div>

                        </div>

                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Clave maestra:</label>
                            </div>
                            <div class="col-md-8">

                                <input type="text" class="form-control" name="CLAVE_MAESTRA" id=""
                                       value="<?php echo $this->session->userdata('CLAVE_MAESTRA') ?>">


                            </div>

                        </div>

                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Pedir clave maestra en los siguientes procesos:</label>
                            </div>
                            <div class="col-md-8">

                                <label>Anular recibos de cartera</label>
                                <input type="checkbox" name="CLAVE_MAESTRA_ANULAR_CARTERA" id="" class=''
                                       value="1" <?php echo $this->session->userdata('CLAVE_MAESTRA_ANULAR_CARTERA') == 1 ? 'checked' : '' ?>>


                            </div>

                        </div>


                    </div>
                </div>

                <div class="tab-pane" role="tabpanel" id="inventario" role="tabpanel">
                    <div class="form-group">


                        <div class="row ">
                            <div class="col-md-4">
                                <label class="control-label">Ubicación requerido:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="INVENTARIO_UBICACION_REQUERIDO"
                                       id="INVENTARIO_UBICACION_REQUERIDO"
                                       value="1" <?php echo ($this->session->userdata('INVENTARIO_UBICACION_REQUERIDO') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="tab-pane col-md11 " role="tabpanel" id="precios" role="tabpanel">


                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Moneda:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="MONEDA" id="MONEDA"
                                   class='form-control'
                                   maxlength="500"
                                   value="<?php echo $this->session->userdata('MONEDA'); ?>">
                        </div>
                    </div>


                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Actualizar Cat&aacute;logo Principal:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" accept="text/plain" class="form-control" name="CATALOGO"
                                   id="CATALOGO"
                            >
                        </div>
                    </div>


                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Usar Progresivo C&oacute;digo de Producto:</label>
                        </div>
                        <div class="col-md-<?= $correlativo['config_value'] == "NO" ? "8" : "4" ?>"
                             id="div_element_8">
                            <div class="form-control">
                                <input type="radio" name="CORRELATIVO_PRODUCTO" id="" class='' value="SI"
                                    <?php echo $correlativo['config_value'] != "NO" ? 'checked' : '' ?>> SI
                                <input type="radio" name="CORRELATIVO_PRODUCTO" id="" class='' value="NO"
                                    <?php echo $correlativo['config_value'] == "NO" ? 'checked' : '' ?>> NO
                            </div>
                        </div>
                        <div class="col-md-4" id="div_id_correlativo"
                             style="display: <?= $this->session->userdata('CORRELATIVO_PRODUCTO') == "NO" ? "none" : "block" ?>">
                            <input onkeydown="return soloNumeros(event);"
                                   type="number" size="10" name="CORRELATIVO_NUMERO" id=""
                                   class="form-control"
                                   value="<?= $correlativo['config_value'] == "NO" ? "" : $correlativo['config_value'] ?>">
                        </div>

                    </div>

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Bodega principal (De esta bodega se descontará el stock por
                                defecto):</label>
                        </div>
                        <div class="col-md-8">
                            <select name="BODEGA_PRINCIPAL" id="BODEGA_PRINCIPAL">
                                <?php
                                foreach ($bodegas as $bodega) {
                                    ?>
                                    <option value="<?= $bodega['int_local_id'] ?>" <?php echo ($this->session->userdata('BODEGA_PRINCIPAL') == $bodega['int_local_id']) ? 'selected' : ''; ?>>
                                        <?= $bodega['local_nombre'] ?>
                                    </option>

                                    <?php
                                }
                                ?>
                            </select>
                            <BR>
                            <BR>
                        </div>


                    </div>

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Modo pantalla completa:</label>
                        </div>
                        <div class="col-md-8" id="">
                            <div class="form-control">
                                <input type="radio" name="PANTALLA_COMPLETA" id="" class='' value="SI"
                                    <?php echo $this->session->userdata('PANTALLA_COMPLETA') != "NO" ? 'checked' : '' ?>>
                                SI
                                <input type="radio" name="PANTALLA_COMPLETA" id="" class='' value="NO"
                                    <?php echo $this->session->userdata('PANTALLA_COMPLETA') == "NO" ? 'checked' : '' ?>>
                                NO
                            </div>
                        </div>

                    </div>

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Ventas: Mostrar TODOS los productos por defecto</label>
                        </div>
                        <div class="col-md-8" id="">
                            <label></label>
                            <input type="checkbox" name="VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS"
                                   id="VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS"
                                   value="1" <?php echo ($this->session->userdata('VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS') == TRUE) ? 'checked="checked"' : NULL; ?>>
                        </div>

                    </div>

                    <div class="row form-group">
                        <div class="col-md-4">
                            <label class="control-label">Es obligatorio digitar el valor del cierre, en el cierre de
                                Caja?</label>
                        </div>
                        <div class="col-md-8">

                            <input type="radio" name="PEDIR_VALOR_CIERRE_CAJA" id="" class=''
                                   value="SI"
                                <?php echo $this->session->userdata('PEDIR_VALOR_CIERRE_CAJA') == "SI" ? 'checked' : '' ?>>
                            SI
                            <input type="radio" name="PEDIR_VALOR_CIERRE_CAJA" id="" class=''
                                   value="NO"
                                <?php echo $this->session->userdata('PEDIR_VALOR_CIERRE_CAJA') == "NO" ? 'checked' : '' ?>>
                            NO

                        </div>

                    </div>

                    <div class="row form-group" title="Al presionar el botón restablecer, aparecerá en el menu derecho,
el botón para que el usuario pueda indicar si quiere recibir las notificaciones del control ambiental">
                        <div class="col-md-3">
                            <label class="control-label">Restablecer usuario para notificación de control
                                ambiental</label>
                        </div>
                        <div class="col-md-8" id="">
                            <label></label>
                            <button onclick="UtilitiesService.restartRecibeNotControlAmb();" type="button" id=""
                                    class="btn btn-danger">Restablecer
                            </button>
                        </div>

                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">MODIFICAR FECHA DE GASTOS:</label>
                        </div>
                        <div class="col-md-8" id="">
                            <div class="form-control">
                                <input type="radio" name="MODIFICAR_FECHA_GASTOS" id="" class='' value="SI"
                                    <?php echo $this->session->userdata('MODIFICAR_FECHA_GASTOS') != "NO" ? 'checked' : '' ?>>
                                SI
                                <input type="radio" name="MODIFICAR_FECHA_GASTOS" id="" class='' value="NO"
                                    <?php echo $this->session->userdata('MODIFICAR_FECHA_GASTOS') == "NO" ? 'checked' : '' ?>>
                                NO
                            </div>
                        </div>

                    </div>


                </div>

                <div class="tab-pane col-md11 " id="impresion" role="tabpanel">

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Tipo de impresion :</label>
                        </div>
                        <div class="col-md-8">
                            <label>En la nube</label> <input
                                    type="radio" <?php echo $this->session->userdata('TIPO_IMPRESION') == 'NUBE' ? 'checked' : ''; ?>
                                    value="NUBE" name="TIPO_IMPRESION">
                            <label>Red Local</label> <input
                                    type="radio" <?php echo $this->session->userdata('TIPO_IMPRESION') != 'NUBE' ? 'checked' : ''; ?>
                                    VALUE="LOCAL" name="TIPO_IMPRESION">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Sistema operativo:</label>
                        </div>
                        <div class="col-md-8">
                            <label>LINUX</label> <input
                                    type="radio" <?php echo $this->session->userdata('SISTEMA_OPERATIVO') == 'LINUX' ? 'checked' : ''; ?>
                                    value="0" name="SISTEMA_OPERATIVO">
                            <label>WINDOWS</label> <input
                                    type="radio" <?php echo $this->session->userdata('SISTEMA_OPERATIVO') != 'WINDOWS' ? 'checked' : ''; ?>
                                    VALUE="2" name="SISTEMA_OPERATIVO">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Impresora :</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="IMPRESORA" id="IMPRESORA"
                                   class="form-control" PLACEHOLDER="smb://Principal-PC/Generic"
                                   value="<?php echo $this->session->userdata('IMPRESORA'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Usuario :</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="USUARIO_IMPRESORA" id="USUARIO_IMPRESORA"
                                   class="form-control" PLACEHOLDER=""
                                   value="<?php echo $this->session->userdata('USUARIO_IMPRESORA'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Password :</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="PASSWORD_IMPRESORA" id="PASSWORD_IMPRESORA"
                                   class="form-control" PLACEHOLDER=""
                                   value="<?php echo $this->session->userdata('PASSWORD_IMPRESORA'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Workgroup :</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="WORKGROUP_IMPRESORA" id="WORKGROUP_IMPRESORA"
                                   class="form-control" PLACEHOLDER=""
                                   value="<?php echo $this->session->userdata('WORKGROUP_IMPRESORA'); ?>">
                        </div>
                    </div>
                    <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Abrir caja registradora despues de imprimir:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="ABRIR_CAJA_REGISTRADORA"
                                       id="ABRIR_CAJA_REGISTRADORA"
                                       value="1" <?php echo ($this->session->userdata('ABRIR_CAJA_REGISTRADORA') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>
                        </div>

                </div>

                <div class="tab-pane col-md11 " id="backup" role="tabpanel">

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Cuenta de google :</label>
                        </div>
                        <div class="col-md-8">
                            <?php if ($this->session->userdata('GOOGLE_CLENT_USERNAME') != '') {
                                echo $this->session->userdata('GOOGLE_CLENT_USERNAME');

                                ?>

                                <a href="<?= base_url() ?>/GoogleUtil/auth" type="button" id="" class="btn btn-info">Cambiar
                                    cuenta
                                </a>


                                <button onclick="Server.pruevadrive();" type="button" id="" class="btn btn-success">
                                    Subir backup a Drive
                                </button>

                                <?php
                            } else {
                                echo 'No tiene cuenta de google parametrizada' ?>

                                <a href="<?= base_url() ?>/GoogleUtil/auth" type="button" id="" class="btn btn-info">Agregar
                                    cuenta
                                </a>

                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label">Restablecer Base de Datos:</label>
                        </div>
                        <div class="col-md-8">
                            <button type="button" id="" class="btn btn-danger "
                                    onclick="opcionesgenerales.modalconfirmrestablecer();">
                                RESTABLECER BASE DE DATOS
                            </button>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="control-label">Pasar precios de contado a Credito</label>
                        </div>
                        <div class="col-md-8" id="">
                            <label></label>
                            <button onclick="Utilities.precioscontadoacredito();" type="button" id=""
                                    class="btn btn-danger">Actualizar
                            </button>
                        </div>

                    </div>

                </div>


                <div class="tab-pane col-md11 " id="app_customer" role="tabpanel">
                    <div class="form-group">

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Mostrar precios de productos:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="APPCUS_MOTRAR_PRECIO_PRODUCTOS"
                                       id="APPCUS_MOTRAR_PRECIO_PRODUCTOS"
                                       value="1" <?php echo ($this->session->userdata('APPCUS_MOTRAR_PRECIO_PRODUCTOS') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Totalizar pedido:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="APPCUS_TOTALIZAR_PEDIDO" id="APPCUS_TOTALIZAR_PEDIDO"
                                       value="1" <?php echo ($this->session->userdata('APPCUS_TOTALIZAR_PEDIDO') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Pedir Direcci&oacute;n:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="APPCUS_PEDIR_DIRECCION" id="APPCUS_PEDIR_DIRECCION"
                                       value="1" <?php echo ($this->session->userdata('APPCUS_PEDIR_DIRECCION') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>
                        </div>


                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Mostrar productos agotados:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" name="APPCUS_MOSTRAR_PROD_AGOTADOS"
                                       id="APPCUS_MOSTRAR_PROD_AGOTADOS"
                                       value="1" <?php echo ($this->session->userdata('APPCUS_MOSTRAR_PROD_AGOTADOS') == TRUE) ? 'checked="checked"' : NULL; ?>>
                            </div>
                        </div>


                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Teléfono del negocio:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="APPCUS_TELEFONO_NEGOCIO" id="APPCUS_TELEFONO_NEGOCIO"
                                       class='form-control'
                                       maxlength="100"
                                       value="<?php echo $this->session->userdata('APPCUS_TELEFONO_NEGOCIO'); ?>">
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Teléfono celular (whatsap):</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="APPCUS_CELULAR_NEGOCIO" id="APPCUS_CELULAR_NEGOCIO"
                                       class='form-control'
                                       maxlength="100"
                                       value="<?php echo $this->session->userdata('APPCUS_CELULAR_NEGOCIO'); ?>">
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Email:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="APPCUS_EMAIL_NEGOCIO" required="true" id="APPCUS_EMAIL_NEGOCIO"
                                       class='form-control'
                                       maxlength="100"
                                       value="<?php echo $this->session->userdata('APPCUS_EMAIL_NEGOCIO'); ?>">
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-3">
                                <label class="control-label">Mensaje:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="APPCUS_MENSAJE" required="true" id="APPCUS_MENSAJE"
                                       class='form-control'
                                       value="<?php echo $this->session->userdata('APPCUS_MENSAJE'); ?>">
                            </div>
                        </div>

                        <?php

                        $APPCUS_CATEGORY_FILTER = array
                        ('CLASIFICACION',
                            'TIPO',
                            'COMPONENTE',
                            'GRUPO',
                            'UBICACION_FISICA',
                            'IMPUESTO',
                        );
                        $variable_sesion = $this->session->userdata('APPCUS_CATEGORY_FILTER');

                        $value_select = NULL;
                        if ($variable_sesion == NULL) {

                        } else {
                            $variable_sesion = json_decode($variable_sesion);
                            $value_select = $variable_sesion[0];
                        }
                        ?>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label class="control-label">Filtro por Categoría:</label>
                            </div>
                            <div class="col-md-8">
                                <select name="APPCUS_CATEGORY_FILTER" id="APPCUS_CATEGORY_FILTER">
                                    <option value="">SELECCIONE</option>
                                    <?php

                                    foreach ($APPCUS_CATEGORY_FILTER as $row) { ?>
                                        <option value="<?= $row ?>"
                                            <?php echo ($value_select == $row) ? 'selected' : ''; ?>>
                                            <?= $row ?>
                                        </option>

                                    <?php } ?>

                                </select>
                                <BR>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Logotipo del Negocio:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="file" accept="image/jpeg, image/png" class="form-control"
                                       name="APPCUS_LOGOTIPO_NEGOCIO"
                                       id="APPCUS_LOGOTIPO_NEGOCIO">
                            </div>
                        </div>

                    </div>
                </div>

                <?php if ($this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN') { ?>
                    <div class="tab-pane col-md11 " id="firebase" role="tabpanel">

                        <p>Configuraciones Globales del Proyecto</p>
                        <div class="row ">
                            <div class="col-md-3"><label class="control-label">Credenciales del proyecto, clave del
                                    servidor:</label></div>
                            <div class="col-md-8">
                                <input type="text" name="FRB_PROJECT_SERVERKEY" id="FRB_PROJECT_SERVERKEY"
                                       class='form-control'
                                       value="<?php echo $this->session->userdata('FRB_PROJECT_SERVERKEY'); ?>">
                            </div>
                        </div>

                        <hr>
                        <p>Aplicaciones web</p>
                        <div class="form-group">

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">apiKey:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_APIKEY" id="FRB_APPWEB_APIKEY"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPWEB_APIKEY'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">authDomain:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_AUTHDOMAIN" id="FRB_AUTHDOMAIN"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_AUTHDOMAIN'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">databaseURL:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_DATABASEURL" id="FRB_DATABASEURL"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_DATABASEURL'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">projectId:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_PROJECTID" id="FRB_APPWEB_PROJECTID"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPWEB_PROJECTID'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">storageBucket:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_STORAGEBUCKET" id="FRB_APPWEB_STORAGEBUCKET"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPWEB_STORAGEBUCKET'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">messagingSenderId:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_MESSAGINGSENDERID"
                                           id="FRB_APPWEB_MESSAGINGSENDERID"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPWEB_MESSAGINGSENDERID'); ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">appId:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPWEB_APPID" id="FRB_APPWEB_APPID"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPWEB_APPID'); ?>">
                                </div>
                            </div>


                            <hr>
                            <p>Aplicaciones Android</p>

                            <div class="row ">
                                <div class="col-md-3"><label class="control-label">Límite de notificaciones por
                                        mes:</label></div>
                                <div class="col-md-8">
                                    <input type="text" name="FRB_APPANDROID_LIMIT_CONT_NOTIF"
                                           id="FRB_APPANDROID_LIMIT_CONT_NOTIF"
                                           class='form-control'
                                           value="<?php echo $this->session->userdata('FRB_APPANDROID_LIMIT_CONT_NOTIF'); ?>">
                                </div>
                            </div>

                        </div>

                    </div>

                <?php } ?>


            </div>
            <br>
            <div class="form-group">
                <button type="button" id="" class="btn btn-primary" onclick="opcionesgenerales.guardar()">Confirmar</button>

            </div>
        </div>

    </div>


</div>


<?= form_close() ?>

<div class="modal fade" id="modalconfirmrestablecer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalconfirmrestablecer').modal('hide')"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Confirmar</h4>
            </div>
            <div class="modal-body">
                <h2 style="color: red">Est&aacute; seguro que quiere restablecer la Base datos? tenga en cuenta que se
                    perder&aacute;n informaciones. Si est&aacute; de acuerdo, presione confirmar</h2>

            </div>

            <div class="modal-footer">

                <div class="text-left col-md-2" id="">
                    <a href="#" class="btn btn-primary"
                       style="text-align: left" onclick="opcionesgenerales.restablecerBD()">Confirmar</a></div>

                <div class="text-right" id="">
                    <button type="button" class="btn btn-default"
                            onclick="$('#modalconfirmrestablecer').modal('hide')">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<script>

    $(document).ready(function () {
        $('select').chosen({'width': '100%'});
    })
    var opcionesgenerales = {
        ajaxgrupo: function () {
            return $.ajax({
                url: '<?= base_url()?>opciones'

            })
        },
        guardar: function () {

            App.formSubmitWithFile($("#formguardar_opciones").attr('action'), this.ajaxgrupo, null, 'formguardar_opciones');
        },
        restablecerBD: function () {

            App.restablecerBD();
        },

        modalconfirmrestablecer: function () {
            $("#modalconfirmrestablecer").modal('show');
        },
    }

    $(function () {

        $("[name='CORRELATIVO_PRODUCTO']").on('click', function () {

            if (this.value == "SI") {

                $("#div_id_correlativo").css("display", "block");

                $("#div_element_8").attr('class', 'col-md-4');
            } else {
                $("#div_element_8").attr('class', 'col-md-8');

                $("#div_id_correlativo").css("display", "none");
            }

        });


    })
</script>
