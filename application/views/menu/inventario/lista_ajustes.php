<br>
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<table class=" table-striped dataTable table-bordered" id="tablaresultado">
    <thead>
    <tr>

        <th>N&uacute;mero</th>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Tipo</th>
        <th>Documento</th>
        <th class="desktop">Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($ajustes) > 0) {
        foreach ($ajustes as $ajuste) {
            ?>
            <tr>
                <td class="center"><?= $ajuste->id_ajusteinventario ?></td>
                <td class="center"><?= date('d-m-Y H:i:s', strtotime($ajuste->fecha)) ?></td>
                <td class="center"><?= isset($ajuste->username)?$ajuste->username:'' ?></td>
                <td class="center"><?= $ajuste->documento_tipo ?></td>
                <td class="center"><?= $ajuste->documento_nombre ?></td>
                <td class="center">
                    <div class="btn-group">

                        <a class="btn btn-default btn-default btn-default" data-toggle="tooltip"
                           title="Ver Detalle" data-original-title="Ver Detalle"
                           href="#" onclick="AjusteInventario.ver('<?= $ajuste->id_ajusteinventario ?>');">
                            <i class="fa fa-list"></i>
                        </a>
                        <a class="btn btn-default btn-default btn-default" data-toggle="tooltip"
                           title="Ver Detalle" data-original-title="Ver Detalle"
                           href="#" onclick="AjusteInventario.print('<?= $ajuste->id_ajusteinventario ?>');">
                            <i class="fa fa-print"></i>
                        </a>

                    </div>
                </td>
            </tr>
        <?php }
    } ?>

    </tbody>
</table>
