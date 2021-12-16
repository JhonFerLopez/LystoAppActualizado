<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">

<ul class="breadcrumb breadcrumb-top">
    <li>Gastos</li>
    <li><a href="">Agregar y editar Gastos</a></li>
</ul>

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success alert-dismissable" id="success" style="display:<?php echo isset($success) ? 'block' : 'none' ?>">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
            <h4><i class="icon fa fa-check"></i> Operaci&oacute;n realizada</h4>
            <span id="successspan"><?php echo isset($success) ? $success : '' ?>
        </div>
        </span>
    </div>
</div>
<?php
echo validation_errors('<div class="alert alert-danger alert-dismissable"">', "</div>");
?>
<div class="row">







    <div class="col-md-6">
        Desde (NO es la fecha de registro en el sistema sino la fecha que aparece en la factura del gasto)
        <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true" class="form-control fecha campos input-datepicker ">
    </div>

    <div class="col-md-6">
        Hasta (NO es la fecha de registro en el sistema sino la fecha que aparece en la factura del gasto)
        <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true" class="form-control fecha campos input-datepicker">
    </div>
</div>
<div class="divider"><br></div>
<div class="row">
    <div class="col-md-6">
        Tipo de gasto
        <select name="tipo" id="tipo" class="select2 campos">
            <option value="">Seleccione</option>
            <?php foreach ($tipos as $tipo) {
            ?>
                <option value="<?= $tipo['id_tipos_gasto'] ?>"><?= $tipo['nombre_tipos_gasto'] ?></option>
            <?php
            } ?>
        </select>
    </div>

</div>
<div class="row">
    <div class="divider"><br></div>
    <div class="col-md-12">
        <div class="white-box">
            <!-- Progress Bars Wizard Title -->


            <a class="btn btn-primary" onclick="Gasto.agregar()">
                <i class="fa fa-plus "> Nueva</i>
            </a>
            <br>

            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="tabla">
                    <thead>
                        <tr>

                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Descripci&oacute;n</th>
                            <th>Tipo de Gasto</th>
                            <th>Local</th>
                            <th>Total</th>

                            <th class="desktop">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>

                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>

                            <th class="desktop"></th>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modales for Messages -->
<div class="modal hide" id="mOK">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload();">
        </button>
        <h3>Notificaci&oacute;n</h3>
    </div>
    <div class="modal-body">
        <p>Registro Exitosa</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary" data-dismiss="modal" onclick="javascript:window.location.reload();">Close</a>
    </div>
</div>






<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>

<div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>gastos/eliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar Gasto</h4>
                </div>
                <div class="modal-body">
                    <p>Est&aacute; seguro que desea eliminar el Gasto seleccionado?</p>
                    <input type="hidden" name="id" id="id_borrar">
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmar" class="btn btn-primary" onclick="Gasto.eliminar()">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>

</div>
<!-- /.modal-dialog -->
</div>

<script>
    $(function() {
        $(".campos").on("change", function() {

            Gasto.search();

        });
        $(".select2").select2();
        Gasto.search();
    });
</script>