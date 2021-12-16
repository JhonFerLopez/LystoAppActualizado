<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Control Ambiental</h4></div>
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
                     <div class="row"></div>
            <a class="btn btn-primary" onclick="addcontrol();">
                <i class="fa fa-plus "> Nuevo</i>
            </a>
            <a class="btn btn-info" onclick="modalnotificacion();">
                <i class="fa fa-plus "> Configurar Notificaciones </i>
            </a>
            <br>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="example">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Mes</th>
                        <th>Usuario Cre&oacute;</th>
                        <th class="desktop">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($controles) > 0) {
                        foreach ($controles as $row) {
                            ?>
                            <tr>
                                <td class="center"><?= $row->control_ambiental_id ?></td>
                                <td><?= date("m-Y", strtotime($row->periodo )) ?></td>
                                <td><?= $row->username ?></td>
                                <td class="center">
                                    <div class="btn-group">
                                        <a class="btn btn-default" data-toggle="tooltip"
                                            title="Editar" data-original-title="fa fa-comment-o"
                                            href="#" onclick="editargrupo(<?= $row->control_ambiental_id ?>);">
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-default" data-toggle="tooltip"
                                           title="Actualizar Control Ambiental" data-original-title="fa fa-comment-o"
                                           href="#" onclick="ControlAmbiental.buscarcontrolambiental(<?= $row->control_ambiental_id ?>);">
                                            <i class="fa fa-check-square-o"></i>
                                        </a>

										<button type="button" id="" class="btn btn-success"
												onclick="ControlAmbiental.showgraficaControl(<?= $row->control_ambiental_id ?>)"><i class="fa fa-bar-chart"></i>Ver
											gráfica
										</button>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>



		<div class="modal fade " id="graficaControlAmb" tabindex="-1" role="dialog"
			 aria-labelledby="myModalLabel"
			 aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title">Gráfica de Control Ambiental</h4>
					</div>

					<div class="modal-body">
						<!-- .row -->
						<div class="row">
							<div id="div_show_grafica" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						</div>

					</div>


				</div>
				<!-- /.modal-content -->

			</div>
		</div>

        <script type="text/javascript">

            function borrargrupo(id, nom) {

                $('#borrar').modal('show');
                $("#id_borrar").attr('value', id);
                $("#nom_borrar").attr('value', nom);
            }


            function editargrupo(id) {
                $("#addcontrol").load('<?= $ruta ?>control_ambiental/form/' + id);
                $('#addcontrol').modal('show');
            }

            function addcontrol() {

                $("#addcontrol").load('<?= $ruta ?>control_ambiental/form');
                $('#addcontrol').modal('show');
            }



            function modalnotificacion(id) {
                $("#addcontrol").load('<?= $ruta ?>control_ambiental/modalnotificacion/');
                $('#addcontrol').modal('show');
            }




            function eliminar() {

                App.formSubmitAjax($("#formeliminar").attr('action'), marca.ajaxgrupo, 'borrar', 'formeliminar');

            }
        </script>

        <div class="modal fade" id="agregargrupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

        </div>

        <div class="modal fade" id="borrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <form name="formeliminar" id="formeliminar" method="post" action="<?= $ruta ?>componentes/eliminar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">Eliminar Componente</h4>
                        </div>
                        <div class="modal-body">
                            <p>Est&aacute; seguro que desea eliminar el componente seleccionado?</p>
                            <input type="hidden" name="id" id="id_borrar">
                            <input type="hidden" name="nombre" id="nom_borrar">
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="confirmar" class="btn btn-primary" onclick="eliminar()">
                                Confirmar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>

        </div>
        <!-- /.modal-dialog -->
    </div>

    <script>$(function () {
            TablesDatatables.init();
        });</script>
