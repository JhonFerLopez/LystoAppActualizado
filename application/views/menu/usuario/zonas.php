                <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Zonas </h4>
                </div>
                <div class="modal-body">
                    <table class='table table-striped table-media dataTable table-bordered'>
                <thead>
                <tr>
                    <th>Zona</th>
                    <th>Distrito</th>
                    <th>Ciudad</th>
                    <th>Pais</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($usuario_has_zona) > 0){ ?>
                    <?php foreach ($usuario_has_zona as $zona): ?>
                        <tr style="">
                            <td><?php echo $zona['zona_nombre']; ?></td>
                            <td><?php echo $zona['ciudad_nombre']; ?></td>
                            <td><?php echo $zona['estados_nombre']; ?></td>
                            <td><?php echo $zona['nombre_pais']; ?></td>

                        </tr>

                    <?php endforeach;
                } ?>
                </tbody>
            </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>