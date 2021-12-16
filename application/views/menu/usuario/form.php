<form name="formagregar" action="<?= base_url() ?>usuario/registrar" id="formagregar" method="post">
    <input type="hidden" id="nUsuCodigo" name="nUsuCodigo"
           value="<?php if (isset($usuario->nUsuCodigo)) echo $usuario->nUsuCodigo ?>">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="block-section">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Usuario:</label>
                            </div>
                            <div class="col-md-4">
                                <div class="controls">
                                    <input type="text"
                                           name="username"
                                           id="username"
                                           maxlength="18"
                                           class='form-control'
                                           autofocus="autofocus"
                                           required
                                           value="<?php if (isset($usuario->username)) echo $usuario->username ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Contrase&ntilde;a:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="password"
                                       name="var_usuario_clave"
                                       id="var_usuario_clave"
                                       maxlength="20"
                                       class='form-control'
                                    <?php if (!isset($usuario->nUsuCodigo)) echo 'required' ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Nombre Completo</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text"
                                       name="nombre"
                                       id="nombre"
                                       maxlength="50"
                                       class="form-control"
                                       required value="<?php if (isset($usuario->username)) echo $usuario->nombre ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Cédula</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text"
                                       name="identificacion"
                                       id="identificacion"
                                       maxlength="20"
                                       class="form-control"
                                       required
                                       value="<?php if (isset($usuario->identificacion)) echo $usuario->identificacion ?>">

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="cboPersonal" class="control-label">ROL</label>

                            </div>

                            <div class="col-md-4">
                                <select name="grupo" id="grupo" class='form-control'>
                                    <option value="">Seleccione</option>
                                    <?php if (count($grupos) > 0): ?>
                                        <?php foreach ($grupos as $grupo): ?>
                                            <option
                                                    value="<?php echo $grupo['id_grupos_usuarios']; ?>" <?php if (isset($usuario->grupo) and $usuario->grupo == $grupo['id_grupos_usuarios']) echo 'selected' ?>><?php echo $grupo['nombre_grupos_usuarios']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!--<div class="col-md-2">
                                <label for="cboPersonal" class="control-label">Bodega</label>

                            </div>

                            <div class="col-md-4">
                                <select name="id_local" id="id_local" class='form-control'>
                                    <option value="">Seleccione</option>
                                    <?php if (count($locales) > 0): ?>
                                        <?php foreach ($locales as $local): ?>
                                            <option
                                                value="<?php echo $local['int_local_id']; ?>"  <?php if (isset($usuario->id_local) and $usuario->id_local == $local['int_local_id']) echo 'selected' ?>><?php echo $local['local_nombre']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>-->
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="cboPersonal" class="control-label">Activo</label>

                            </div>

                            <div class="col-md-4">
                                <input type="checkbox"
                                       name="activo" <?php if ((isset($usuario->activo) and $usuario->activo == true) || !isset($usuario->activo)) echo 'checked ' ?>>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label">Observacion</label>
                            </div>
                            <div class="col-md-4">

                                <input type="text"
                                       name="obser"
                                       id="obser"
                                       class="form-control"
                                       value="<?php if (isset($usuario->obser)) echo $usuario->obser ?>">

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="cboPersonal" class="control-label">Fecha de Nacimiento</label>

                            </div>

                            <div class="col-md-4">
                                <input type="text"
                                       name="fnac"
                                       id="fnac"
                                       class="input-small input-datepicker form-control fecha"
                                       value="<?php if (isset($usuario->fnac)) echo $usuario->fnac ?>">

                            </div>

                            <div class="col-md-2">
                                <label class="control-label">Fecha de Ingreso</label>
                            </div>
                            <div class="col-md-4">

                                <input type="text"
                                       name="fent"
                                       id="fent"
                                       class="input-small input-datepicker form-control fecha"
                                       value="<?php if (isset($usuario->fent)) echo $usuario->fent ?>">

                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label for="cboPersonal" class="control-label">Genero</label>

                            </div>

                            <div class="col-md-4">
                                <select name="genero" id="genero" class='form-control'>
                                    <option value="">Seleccione</option>
                                    <?php
                                    if (isset($usuario->genero) and $usuario->genero == 'masculino') {
                                        ?>
                                        <option value="masculino" selected>Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <?php
                                    } else if (isset($usuario->genero) and $usuario->genero == 'femenino') {
                                        ?>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino" selected>Femenino</option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <?php
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Sueldo</label>
                            </div>
                            <div class="col-md-4">

                                <input type="text"
                                       name="sueldo"
                                       id="sueldo"
                                       maxlength="20"
                                       onkeydown="return soloDecimal(this, event)"
                                       class="form-control"
                                       required value="<?php if (isset($usuario->sueldo)) echo $usuario->sueldo ?>">

                            </div>

                            <div class="col-md-2">
                                <label class="control-label">Celular</label>
                            </div>
                            <div class="col-md-4">

                                <input type="text"
                                       name="celular"
                                       placeholder="Ejemplo: 3125060607"
                                       id="celular"
                                       maxlength="20"

                                       class="form-control"
                                       required value="<?php if (isset($usuario->celular)) echo $usuario->celular ?>">

                            </div>


                            <div class="col-md-2">
                                <label class="control-label">IMEI (Codigo IMEI del celular)</label>
                            </div>
                            <div class="col-md-4">

                                <input type="text"
                                       name="imei"
                                       placeholder=""
                                       id="imei"
                                       maxlength="20"

                                       class="form-control"
                                       required value="<?php if (isset($usuario->imei)) echo $usuario->imei ?>">

                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">


                            <div class="col-md-2">
                                <label for="smovil" class="control-label">Sistema Movil</label>

                            </div>

                            <div class="col-md-4">
                                <input type="checkbox" id="smovil"
                                       name="smovil" <?php if (isset($usuario->smovil) and $usuario->smovil == true) echo 'checked ' ?>>
                            </div>


                            <div class="col-md-2">
                                <label for="admin" class="control-label">Super Admin</label>

                            </div>
                            <?php //var_dump($usuario)?>
                            <div class="col-md-4">

                                <input type="checkbox" id="admin"
                                       name="admin" <?php if (isset($usuario->admin) and $usuario->admin == true) echo 'checked ' ?>>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <input type="text" class="form-control" id="location"/>
                        <label for="" class="control-label">Dirección de domicilio</label>


                        <div id="us2" style="width: 100%; height: 400px;"></div>
                        Lat.: <input type="text" id="latitud" name="latitud" required readonly
                                     value="<?php if (isset($usuario->latitud)) echo $usuario->latitud; else echo "0"; ?>"/>
                        Long.: <input type="text" id="longitud" name="longitud" required readonly
                                      value="<?php if (isset($usuario->longitud)) echo $usuario->longitud; else echo "0"; ?>"/>

                        <script>


                            $('.selectpicker').selectpicker();

                        </script>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="form-actions">
                    <button type="button" id="" class="btn btn-primary" onclick="usuario.guardar()">Confirmar</button>
                    <input type="button" class='btn btn-default' data-dismiss="modal" value="Cancelar">
                </div>
            </div>
        </div>

    </div>
</form>
<script type="text/javascript">

    $(".fecha").datepicker({format: 'dd-mm-yyyy'});
    $("select").chosen({'width': '100%'});

    if ($('#latitud').val() == '0') {
        (setTimeout(function () {

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
                            locationNameInput: $('#location')
                        },
                        enableAutocomplete: true,
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
        })(), 5000);
    }
    else {
        $('#us2').locationpicker({
            location: {latitude: $('#latitud').val(), longitude: $('#longitud').val()},
            radius: 50,
            inputBinding: {
                latitudeInput: $('#latitud'),
                longitudeInput: $('#longitud'),
                locationNameInput: $('#location')
            },
            enableAutocomplete: true,
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                (currentLocation.latitude + ", " + currentLocation.longitude);

            }
        });

    }
</script>