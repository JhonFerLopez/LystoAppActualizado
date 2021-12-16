<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Devoluciones</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Ventas</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
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

               <!-- <div class="col-md-1">
                    Estatus
                </div>
                <div class="col-md-3">
                    <select id="estatus" class="form-control campos" name="estatus">
                        <option value="">SELECCIONE</option>
                        <option value="COMPLETADO">COMPLETADO</option>
                        <option value="EN ESPERA">EN ESPERA</option>
                        <option value="ANULADO">ANULADO</option>
                        <option value="DEVUELTO">DEVUELTO</option>
                    </select>
                </div>-->
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

            <div class="row" >
                <div class="col-md-1">Leyenda</div>
                <?php
                $colores_formapago=array();

                $colores_formapago[0] = "#D7DF01";
                $colores_formapago[1] = "#0101DF";
                $colores_formapago[2] = "#FF0000";
                $colores_formapago[3] = "#00FF00";
                $colores_formapago[4] = "#0B614B";
                $colores_formapago[5] = "#58D3F7";

                $cont=0;
                foreach ($formas_de_pago as $forma_pago) { ?>

                    <div class="col-md-2" style="color: white; background-color: <?= $colores_formapago[$cont] ?>">
                        <?= $forma_pago['nombre_metodo']  ?></div>

                    <?php  $cont++;
                }
                ?>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-body" id="tabla">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {


        Venta.get_devoluciones();

        $(".campos").on("change", function () {

            Venta.get_devoluciones();

        });


    });


</script>
