<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Actualizar Fecha a Ventas</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Actualizar Fecha a Ventas</li>
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
                    <span id="successspan"><?php echo isset($success) ? $success : '' ?>  </span></div>

            </div>
        </div>

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">

                <div class="col-md-1">
                    Desde ID
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="id_desde" value="" required="true"
                           class="form-control fecha">
                </div>

                <div class="col-md-1">
                    Hasta ID
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_desde" id="id_hasta" value="" required="true"
                           class="form-control fecha">
                </div>
                <div class="col-md-1">
                    Fecha a Actualizar
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="fecha_actualiza" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos input-datepicker">
                </div>
            </div>

            <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-info" onclick="accionupdatefechaventa()">
                    <i class="fa fa-bar-search"></i>Confirmar
                </button>
            </div>
            </div>

            <div class="divider"><br></div>

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

    function accionupdatefechaventa(){


        Utilities.showPreloader();
            setTimeout( function (){
                if($('#id_desde').val()=='' || $('#id_hasta').val()=='' ||
                    $('#fecha_actualiza').val()==''){
                    Utilities.alertModal('Debe ingresar todos los datos', 'warning');
                }


                $.ajax({
                    url: "<?= base_url()?>soporte/accionupdatefechaventa",
                    type: "POST",
                    async: false,
                    data: {
                        'id_desde': $('#id_desde').val(),
                        'id_hasta':$('#id_hasta').val(),
                        'fecha_actualiza': $('#fecha_actualiza').val()
                    },
                    dataType: 'JSON',
                    success: function (data) {

                        if(data.success){
                            Utilities.alertModal('Fechas actualizadas con éxito', 'success');
                        }
                        Utilities.hiddePreloader();
                    }
                })
            },2500)




    }

    $(function () {
        Utilities.alertModal('Por seguridad, le recomendamos generar una copia de seguridad, antes de realizar cualquier cambio', 'warning');
    });


</script>
