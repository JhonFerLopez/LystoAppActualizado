<?php $ruta = base_url(); ?>
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Comprobante Diario Ventas</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="index.html">SID</a></li>

        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>

<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="EMPRESA_NOMBRE" value="<?= $this->session->userdata('EMPRESA_NOMBRE'); ?>">
<input type="hidden" id="REGIMEN_CONTRIBUTIVO" value="<?= $this->session->userdata('REGIMEN_CONTRIBUTIVO'); ?>">
<input type="hidden" id="EMPRESA_DIRECCION" value="<?= $this->session->userdata('EMPRESA_DIRECCION'); ?>">
<input type="hidden" id="EMPRESA_TELEFONO" value="<?= $this->session->userdata('EMPRESA_TELEFONO'); ?>">
<input type="hidden" id="NIT" value="<?= $this->session->userdata('NIT'); ?>">
<input type="hidden" id="USUARIO_SESSION" value="<?= $this->session->userdata('nUsuCodigo'); ?>">
<input type="hidden" id="REGIMEN_CONTRIBUTIVO" value="<?= $this->session->userdata(REGIMEN_CONTRIBUTIVO); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
<div class="row">


    <div class="col-md-12">

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

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->


            <div class="row">
                <input type="hidden" name="listar" id="listar" value="ventas">

                <div class="col-md-1">
                    Fecha
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_desde"  id="fecha_desde" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos ">
                </div>
                <!--<div class="col-md-1">
                    Hasta
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>
                <div class="col-md-3">
                    <button type="button" onclick="get_ventas()" class="btn btn-success">Generar reporte</button>
                </div>-->
                <BUTTON tabindex="0" type="button" id="imprimir" class="btn btn-primary"><i
                            class="fa fa-print"></i>GENERAR Nuevo Reporte
                </BUTTON>

            </div>
            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="row bg-title">
                        <div class="ccol-lg-12">
                            <h4 class="page-title">Historial de comprobantes de venta impresos</h4></div>

                        <!-- /.col-lg-12 -->
                    </div>

                    <div class="table-responsive left">

                        <table class="table datatable" id="history">
                            <thead>
                            <th>ID</th>
                          <!--  <th>FECHA REPORTE</th>-->
                            <th>FECHA GENERADO</th>
                            <th>USUARIO</th>
                            <th>#</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($historial as $item) {
                                ?>
                                <tr>
                                    <td><?= $item['id_reporte'] ?></td>
                                 <!--   <td><?= $item['fecha_reporte'] ?></td>--->
                                    <td><?= $item['fecha_generado'] ?></td>
                                    <td><?= $item['nombre'] ?></td>
                                    <td>
                                        <div class="btn-group"><a class="btn btn-default" data-toggle="tooltip"
                                                                  title="Visualizar" data-original-title="fa fa-search"
                                                                  href="#"
                                                                  onclick="preview(<?= $item['id_reporte'] ?>);"> <i
                                                        class="fa fa-search"></i>
                                            </a>
                                            <a class="btn btn-default" data-toggle="tooltip"
                                               title="Imprimir" data-original-title="fa fa-print"
                                               href="#"
                                               onclick="Venta.printComprobanteDiario('<?= $item['fecha_reporte'] ?>', <?= $item['id_reporte'] ?>);"> <i
                                                        class="fa fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- /.row -->
<div class="modal fade" id="modalpreview" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeseleccionunidades" data-dismiss="modal"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Reporte comprobante diario ventas</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="preview"></div>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">


    $(document).ready(function () {
        TablesDatatables.init(0, 'history', 'desc');
        $("#imprimir").click(function () {

            Venta.printComprobanteDiario($("#fecha_desde").val(), false);

        })
    })


    function preview(id) {


        Utilities.showPreloader();


        $.ajax({
            'url': '<?php echo $ruta ?>venta/comprobanteDiarioData',
            data: {id: id},
            success: function (data) {
                $("#preview").html(data);
                $("#modalpreview").modal('show');
                Utilities.hiddePreloader();
            }
        })
    }


</script>
