<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Domicilios</h4></div>



    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Control de Domicilios</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->



</div>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <a href="#" class="btn btn-primary"
           style="text-align: left" onclick="Venta.viewMapaDomiciliarios()" target="_blank">Ver Mapa de Domiciliarios</a>
    </div>
</div>
<div class="row">



    <div class="col-md-12">


        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">

                <div class="col-md-1">
                    Estatus
                </div>
                <div class="col-md-3">
                    <select id="estatus" class="form-control campos" name="estatus">
                        <option value="">SELECCIONE</option>
                        <option value="EN ESPERA" selected>EN ESPERA</option>
                        <option value="ASIGNADO">ASIGNADO</option>
                        <option value="ENTREGADO">ENTREGADO</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select>
                </div>
                <div class="col-md-1">
                    Desde
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_desde" id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker ">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>



            </div>

            <div class="divider"><br></div>

            <input type="hidden" name="listar" id="listar" value="ventas">

            <div class="row">
                <div class="table-responsive" id="open_table_domicilios">
                    <table class="table table-striped dataTable table-bordered" id="tabladomicilios">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Factura</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <!--	<th>Tipo de Documento</th>-->
                            <th>Estatus</th>
                            <th>Direcci&oacute;n</th>
                            <th>Tiempo Promedio de Entrega</th>
                            <th>Domiciliario</th>
                            <th>Total</th>
                            <th>Acciones</th>

                        </tr>
                        </thead>
                        <tbody id="tbodyTableDomicilios">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAsignarDomicilio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalAsignarDomicilio').modal('hide')"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Domicilio</h4>
            </div>
            <div class="modal-body">

                <div class="row" >
                    <div class="col-md-12">
                <div class="form-group">

                    <div class="col-md-3">
                        Asignar a un Domiciliario:
                    </div>
                    <div class="col-md-5">
                        <select name=""
                                id="selectdomiciliarios" placeholder="Seleccione"
                                class='cho form-control'>

                        </select>

                    </div>
                </div>
                </div>
                </div>

            </div>

            <div class="modal-footer">

                <div class="text-left col-md-3" id="">
                    <a href="#" class="btn btn-success"
                       style="text-align: left" onclick="Venta.asocDomicUsuario()">Asociar al domicilio</a></div>

                <div class="text-right" id="">
                    <button type="button" class="btn btn-default"
                            onclick="$('#modalAsignarDomicilio').modal('hide')">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>


</div>


<div class="modal fade" id="modalDetalleDom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div class="modal-dialog" style="width: 100%; height: 100%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalDetalleDom').modal('hide')"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Historial del Domicilio</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-4" >
                            <h4 class="modal-title" id="lblusuarioasigna"></h4>
                        </div>

                    </div>
                </div>
                <br>
                <div class="row">
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="table-responsive" id="open_table_historial">
                        <table class="table table-striped dataTable table-bordered" id="tablahistorial">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Estatus</th>
                                <th>Comentario</th>
                                <th>Punto de creaci&oacute;n</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyTableDomicilios">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer">


                <div class="text-right" id="">
                    <button type="button" class="btn btn-default"
                            onclick="$('#modalDetalleDom').modal('hide')">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>


</div>

<script type="text/javascript">
    $(function () {

        Venta.initDomicilios(<?php echo json_encode( $this->session->userdata()); ?>,'<?php echo DOMICILIO_ASIGNADO ?>');
        $(".campos").on("change", function () {
            Venta.get_Domicilios();
        });

    });


</script>
