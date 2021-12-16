<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Logs de errores</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Logs de errores</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>


<!--row -->
<div class="row">


    <div class="col-md-12">
        <div class="white-box">

            <div class="row">


                <div class="col-md-12">

                    <div class="col-md-1">
                        Desde
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fecha_desde" onchange="get_logs()" id="fecha_desde"
                               value="<?= date('d-m-Y'); ?>" required="true"
                               class="form-control fecha campos input-datepicker ">
                    </div>
                    <div class="col-md-1">
                        Hasta
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="fecha_hasta" onchange="get_logs()" id="fecha_hasta"
                               value="<?= date('d-m-Y'); ?>" required="true"
                               class="form-control fecha campos input-datepicker">
                    </div>


                </div>
            </div>

            <table class="table table-striped dataTable table-bordered" id="tabla">
                <thead>
                <tr>
                    <th>FECHA</th>
                </tr>
                </thead>
                <tbody>



                </tbody>
            </table>






        </div>
    </div>
</div>

<div class="modal fade" id="modalshowerror" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">


    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalshowerror').modal('hide')"
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Confirmar</h4>
            </div>
            <div class="modal-body" id="modalbodyError">

            </div>

            <div class="modal-footer">

                <div class="text-right" id="">
                    <button type="button" class="btn btn-default"
                            onclick="$('#modalshowerror').modal('hide')">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>


</div>


<script>

    function get_logs() {


        var fercha_desde = $("#fecha_desde").val();
        var fercha_hasta = $("#fecha_hasta").val();


        TablesDatatablesLazzy.init('<?php echo $ruta ?>api/SystemLogs/datatableerror', 0, 'tabla', {
            fecha_desde: fercha_desde,
            fecha_hasta: fercha_hasta,
        }, 'Logs de Errores');


    }

    function showerrorlog(fechaformateada){

        $.ajax({
            url: '<?php echo $ruta ?>SystemLogs/showFileError',
            type: 'post',
            dataType: 'json',
            data: {'fecha': fechaformateada},
            success: function (data) {
                $("#modalbodyError").html('');
                var datos=data.data;
                var html="";
                if(datos.length>0){

                    for(var i=0; i<datos.length;i++){
                        html+= datos[i]+"<br>";

                    }
                }
                $("#modalbodyError").html(html);
                $("#modalshowerror").modal('show');
            },
            error: function (error) {
                Utilities.hiddePreloader();
                Utilities.alertModal('<h4>Ha ocurrido un error!</h4>', 'warning', true);
            }

        })


    }
    $(function () {



        get_logs();


    });
</script>