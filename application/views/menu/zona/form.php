<form name="formagregar" action="<?= base_url() ?>zona/guardar" method="post" id="formagregar">

    <input type="hidden" name="id" id="" required="true"
           value="<?php if (isset($zona->zona_id)) echo $zona->zona_id; ?>">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nuevo Barrio</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Pa&iacute;s</label>
                        </div>
                        <div class="col-md-9">
                            <select name="id_pais" id="id_pais" required="true" class="form-control"
                                    onchange="region.actualizarestados();">
                                <option value="">Seleccione</option>
                                <?php foreach ($paises as $pais): ?>
                                    <option
                                        value="<?php echo $pais['id_pais'] ?>" <?php if (isset($zona->id_pais) and $pais['id_pais'] == $zona->id_pais) echo 'selected' ?>><?= $pais['nombre_pais'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Ciudad</label>
                        </div>
                        <div class="col-md-9">

                            <select name="estado_id" ID="estado_id" required="true" class="form-control"
                                    onchange="region.actualizardistritos();">
                                <option value="">Seleccione</option>
                                <?php if (isset($zona->zona_id)): ?>
                                    <?php foreach ($estados as $estado): ?>
                                        <option
                                            value="<?php echo $estado['estados_id'] ?>" <?php if (isset($zona->estados_id) and $estado['estados_id'] == $zona->estados_id) echo 'selected' ?>><?= $estado['estados_nombre'] ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Distrito</label>
                        </div>
                        <div class="col-md-9">

                            <select name="ciudad_id" ID="ciudad_id" required="true" class="form-control">
                                <option value="">Seleccione</option>
                                <?php if (isset($zona->zona_id)) { ?>
                                    <?php foreach ($ciudades as $ciudad): ?>
                                        <option
                                            value="<?php echo $ciudad['ciudad_id'] ?>" <?php if (isset($zona->ciudad_id) and $ciudad['ciudad_id'] == $zona->ciudad_id) echo 'selected' ?>><?= $ciudad['ciudad_nombre'] ?></option>
                                    <?php endforeach ?>
                                <?php } ?>
                            </select>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Barrio</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="zona_nombre" id="zona_nombre" required="true"
                                   class="form-control"
                                   value="<?php if (isset($zona->zona_nombre)) echo $zona->zona_nombre; ?>">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <!--<div class="form-group">
                        <div class="col-md-3">
                            <label>Urbanizaciones</label>
                        </div>
                        <div class="col-md-9">
                            <input type="textarea" name="urb" id="urb" required="true"
                                   class="form-control"
                                   value="<?php if (isset($zona->urb)) echo $zona->urb; ?>">
                        </div>

                    </div>-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" class="btn btn-primary" onclick="grupo.guardar()">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
        <!-- /.modal-content -->
</form>



