<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Cuentas por pagar</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">
        <div class="white-box">
            <form id="frmBuscar">


                <div class="row">
                    <div class="col-md-1">
                        <label>Desde</label>
                    </div>
                    <div class="col-md-2">

                        <input type="text" name="fecIni" id="fecIni" value="<?php echo date('d-m-Y') ?>"
                               class='form-control input-datepicker'>
                    </div>
                    <div class="col-md-1">
                        <label>Hasta</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fecFin" id="fecFin" value="<?php echo date('d-m-Y') ?>"
                               class='form-control input-datepicker'>
                    </div>
					<div class="col-md-1">
						<label>Proveedor</label>
					</div>
                    <div class="col-md-2">

                        <select name="proveedor" id="proveedor" class='cho form-control'>
                            <option value="-1">Seleccionar</option>
                            <?php if (count($lstproveedor) > 0): ?>
                                <?php foreach ($lstproveedor as $cl): ?>
                                    <option
                                            value="<?php echo $cl['id_proveedor']; ?>"><?php echo $cl['proveedor_nombre']; ?></option>
                                <?php endforeach; ?>
                            <?php else : ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button id="btnBuscar" class="btn btn-success">Buscar</button>
                </div>
            </form>
        </div>

    </div>


    <div class="col-md-12">
        <div class="white-box">

            <div id="lstTabla" class="table-responsive">
                <div class="table-responsive">
                    <table class='table table-striped dataTable table-bordered no-footer table-condensed'
                           id="lstPagP" name="lstPagP">
                        <thead>
                        <tr>
                            <th title="Tipo Doc">Ingreso ID</th>
                            <th title="Documento"> Documento</th>
                            <th>Proveedor</th>
                            <th title="Fecha Registro">Fecha Reg.</th>
                            <th title="Total">Monto Ingreso <?php echo MONEDA ?></th>
                            <th title="Total">Monto abonado <?php echo MONEDA ?></th>
                            <th title="Total">Monto Deudor <?php echo MONEDA ?></th>
                            <th>D&iacute;as de atraso</th>
                            <th title="Estatus">Estatus</th>
							<th>Acciones</th>

                        </tr>
                        </thead>
                        <tbody>

						</tbody>
						<tfoot>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						</tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="visualizarPago" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">


</div>

<script>


    $(document).ready(function () {

        $('select').chosen();
        $(".input-datepicker").datepicker({format: 'dd-mm-yyyy'});

        $("#btnBuscar").click(function (e) {
            e.preventDefault()
            buscar();
        });
        buscar();
    });


    function buscar() {

        $(document).ready(function () {
            TablesDatatablesLazzy.init('<?php echo base_url()?>cuentasPorPagar/lst_cuentas_porpagar_json', 0, 'lstPagP', {
                fecIni: $("#fecIni").val(),
                fecFin: $("#fecFin").val(),
                proveedor: $("#proveedor").val()
            });

        });
    }


</script>
