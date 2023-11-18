
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Reporte de transacciones</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?=base_url() ?>">SID</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">


        <div class="white-box">
            <!-- Progress Bars Wizard Title -->


            <div class="row">
                <input type="hidden" name="listar" id="listar" value="ventas">

                <div class="col-md-1">
                    Fecha
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>"  onchange="get_ventas()"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-2">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>"  onchange="get_ventas()"
                           class="form-control fecha campos input-datepicker">
                </div>

                <div class="col-md-2">
                    <label>Tienda/Bodega</label>
                </div>
                <div class="col-md-2">

                    <select name="local_id" id="local_id" required="true" class="form-control"  onchange="get_ventas()">
                        <option value="">Seleccione</option>
                        <?php foreach ($locales as $local) : ?>
                            <option value="<?php echo $local['int_local_id'] ?>" <?php if (isset($gastos['local_id']) and $gastos['local_id'] == $local['int_local_id']) echo 'selected' ?>><?= $local['local_nombre'] ?></option>
                        <?php endforeach ?>
                    </select>

                </div>

            </div>
            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">

                        <table id="tabla" class="table table-striped dataTable table-bordered">
                            <thead>
                            <tr>
                                <th>MOVIMIENTO</th>
                                <th>TIPO</th>
                                <th>TOTAL</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- /.row -->


<script type="text/javascript">


    $(document).ready(function () {
        get_ventas();
    });
    function get_ventas() {

        var hoy = new Date(Date.now()).toLocaleString();
        var usuario = '<?= $this->session->userdata("username")?>';
        var fecha_desde = $("#fecha_desde").val();
        var fecha_hasta = $("#fecha_hasta").val();
        var local_id = $('#local_id').val();

        TablesDatatablesLazzy.init('<?php echo base_url() ?>api/Inventario/rep_inv_transacciones', 0, 'tabla', {
            fecha_desde: fecha_desde,
            fecha_hasta: fecha_hasta,
            local_id : local_id
        }, 'Reporte de transacciones', false, false, false, false, true, false, '300px');


    }


</script>
