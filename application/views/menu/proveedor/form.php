<form name="formagregar" action="<?= base_url() ?>proveedor/guardar" method="post" id="formagregar">

    <input type="hidden" name="id" id=""
           value="<?php if (isset($proveedor['id_proveedor'])) echo $proveedor['id_proveedor']; ?>">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Datos del Proveedor</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Código del proveedor (NIT/Cédula)</label>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="proveedor_identificacion" id="proveedor_identificacion"
                                   required="true"
                                   class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_identificacion'])) echo $proveedor['proveedor_identificacion']; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>Nombre</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_nombre" id="proveedor_nombre" required="true"
                                   class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_nombre'])) echo $proveedor['proveedor_nombre']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Tipo de proveedor</label>
                        </div>
                        <div class="col-md-4">
                            <select name="proveedor_tipo" id="proveedor_tipo" required="true" class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option
                                        value="<?php echo $tipo['tipo_proveedor_id'] ?>" <?php if (isset($proveedor['proveedor_tipo']) and $proveedor['proveedor_tipo'] == $tipo['tipo_proveedor_id']) echo 'selected' ?>><?= $tipo['tipo_proveedor_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Dígito de verificación</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_digito_verificacion" id=""  size="1" maxlength="1"max="1" class="form-control "
                                   value="<?php if (isset($proveedor['proveedor_digito_verificacion'])) echo $proveedor['proveedor_digito_verificacion']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Tel&eacute;fono 1</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_telefono1" id="" class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_telefono1'])) echo $proveedor['proveedor_telefono1']; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>Tel&eacute;fono 2</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_telefono2" id="" class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_telefono2'])) echo $proveedor['proveedor_telefono2']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Celular</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_celular" id="proveedor_celular" class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_celular'])) echo $proveedor['proveedor_celular']; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>Régimen</label>
                        </div>
                        <div class="col-md-4">
                            <select name="proveedor_regimen" id="proveedor_regimen" required="true"
                                    class="form-control">
                                <option value="">Seleccione</option>
                                <?php foreach ($regimenes as $regimen): ?>
                                    <option
                                        value="<?php echo $regimen['regimen_id'] ?>" <?php if (isset($proveedor['proveedor_regimen']) and $proveedor['proveedor_regimen'] == $regimen['regimen_id']) echo 'selected' ?>><?= $regimen['regimen_nombre'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label>Email</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="proveedor_email" id="proveedor_email" class="form-control"
                                   value="<?php if (isset($proveedor['proveedor_email'])) echo $proveedor['proveedor_email']; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>Pais</label>
                        </div>
                        <div class="col-md-4">
                            <select name="pais_id" id="id_pais" required="true" class="form-control"
                                    onchange="region.actualizarestados();">

                                <?php
                                $p = 1;
                                foreach ($paises as $pais) {
                                    $paais['pais' . $p] = $pais['id_pais'];
                                    ?>
                                    <option
                                        value="<?php echo $pais['id_pais'] ?>" <?php if (isset($proveedor['id_pais']) and $proveedor['pais_id'] == $pais['id_pais']) echo 'selected' ?>><?= $pais['nombre_pais'] ?></option>
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
                                <select name="estados_id" id="estado_id" required="true" class="form-control"
                                        onchange="region.actualizardistritos();">

                                    <?php foreach ($estados as $estado) {
                                        $eestado['estado' . $e] = $estado['estados_id'];
                                        ?>
                                        <option
                                            value="<?php echo $estado['estados_id'] ?>" <?php if (isset($proveedor['estados_id']) and $proveedor['estados_id'] == $estado['estados_id']) echo 'selected' ?>><?= $estado['estados_nombre'] ?></option>
                                        <?php $e++;
                                    } ?>

                                </select>
                                <?php
                            } else {
                                ?>
                                <select name="estados_id" id="estado_id" required="true" class="form-control"
                                        onchange="region.actualizardistritos();">
                                    <option value="">Seleccione</option>
                                    <?php if (isset($proveedor['id_cliente'])):
                                        $eestado['estado' . $e] = $estado['estados_id'];
                                        ?>
                                        <?php foreach ($estados as $estado): ?>
                                        <option
                                            value="<?php echo $estado['estados_id'] ?>" <?php if (isset($proveedor['estados_id']) and $proveedor['estados_id'] == $estado['estados_id']) echo 'selected' ?>><?= $estado['estados_nombre'] ?></option>
                                        <?php $e++; endforeach ?>
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
                                <select name="proveedor_ciudad" id="proveedor_ciudad" required="true"
                                        class="form-control"
                                        onchange="region.actualizarzonas();">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($ciudades as $ciudad): ?>
                                        <option
                                            value="<?php echo $ciudad['ciudad_id'] ?>" <?php if (isset($proveedor['proveedor_ciudad']) and $proveedor['proveedor_ciudad'] == $ciudad['ciudad_id']) echo 'selected' ?>><?= $ciudad['ciudad_nombre'] ?></option>
                                    <?php endforeach ?>

                                </select>
                                <?php
                            } else {
                                ?>
                                <select name="ciudad_id" id="ciudad_id" required="true" class="form-control"
                                        onchange="region.actualizarzonas();">
                                    <option value="">Seleccione</option>
                                    <?php if (isset($proveedor['id_cliente'])): ?>
                                        <?php foreach ($ciudades as $ciudad): ?>
                                            <option
                                                value="<?php echo $ciudad['ciudad_id'] ?>" <?php if (isset($proveedor['proveedor_ciudad']) and $proveedor['proveedor_ciudad'] == $ciudad['ciudad_id']) echo 'selected' ?>><?= $ciudad['ciudad_nombre'] ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            <?php } ?>
                        </div>


                    </div>
                </div>


                <div class="row">
                    <div class="col-md-2">
                        <label for="" class="control-label">Direcci&oacute;n</label>
                    </div>
                    <div class="col-md-4">


                        <input type="text" name="proveedor_direccion" id="proveedor_direccion" class="form-control"
                               value="<?php if (isset($proveedor['proveedor_direccion'])) echo $proveedor['proveedor_direccion']; ?>">

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

            </div>
            <!-- /.modal-content -->
        </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $("select").chosen({
            width: "100%",
            search_contains: true
        });

    });



    /*

    if ($('#latitud').val() == '0') {
        //(setTimeout(function () {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (objPosition) {
                    var lon = objPosition.coords.longitude;
                    var lat = objPosition.coords.latitude;

                    $('#longitud').val(lon);
                    $('#latitud').val(lat);
                    $('#us2').locationpicker({
                        location: {latitude: lat, longitude: lon},
                        radius: 50,
                        inputBinding: {
                            latitudeInput: $('#latitud'),
                            longitudeInput: $('#longitud'),
                            locationNameInput: $('#proveedor_direccion')
                        },
                        enableAutocomplete: true,
                        markerInCenter: true,
                        onchanged: function (currentLocation, radius, isMarkerDropped) {
                            (currentLocation.latitude + ", " + currentLocation.longitude);


                        }
                    });
                }, function (objPositionError) {
                    switch (objPositionError.code) {
                        case objPositionError.PERMISSION_DENIED:
                            alert("No se ha permitido el acceso a la posición del usuario.");
                            break;
                        case objPositionError.POSITION_UNAVAILABLE:
                            alert("No se ha podido acceder a la información de su posición.");
                            break;
                        case objPositionError.TIMEOUT:
                            alert("El servicio ha tardado demasiado tiempo en responder.");
                            break;
                        default:
                            alert("Error desconocido.");
                    }
                }, {
                    maximumAge: 75000,
                    timeout: 15000
                });
            }
            else {
                alert("Su navegador no soporta la API de geolocalización.");
            }
        //})(), 10000);
    }
    else {
        $('#us2').locationpicker({
            location: {latitude: $('#latitud').val(), longitude: $('#longitud').val()},
            radius: 50,
            inputBinding: {
                latitudeInput: $('#latitud'),
                longitudeInput: $('#longitud'),
                locationNameInput: $('#proveedor_direccion')
            },
            enableAutocomplete: true,
            markerInCenter: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                (currentLocation.latitude + ", " + currentLocation.longitude);

            }
        });


    }
    */

</script>
