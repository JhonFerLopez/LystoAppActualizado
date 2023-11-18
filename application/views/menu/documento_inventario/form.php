<form name="formagregar" action="<?= base_url() ?>documento_inventario/guardar" method="post" id="formagregar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Nuevo documento inventario</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group">
						<div class="col-md-3">
							Nombre
						</div>
						<div class="col-md-9">
							<input type="text" name="documento_nombre" id="documento_nombre" required="true" class="form-control" 
							value="<?php if (isset($documento['documento_nombre'])) echo $documento['documento_nombre']; ?>">
						</div>
						<input type="hidden" name="documento_id" id="documento_id" required="true" value="<?php if (isset($documento['documento_id'])) echo $documento['documento_id']; ?>">
						<div class="col-md-3">
							Entrada o salida
						</div>
						<div class="col-md-9">
							Entrada
							<input type="radio" name="documento_tipo" id="documento_tipo" 
								required="true" value="ENTRADA"
								<?php if (isset($documento['documento_id']) && 
									$documento['documento_tipo'] == 'ENTRADA') 
									echo 'checked'; ?>>
							Salida
							<input type="radio" name="documento_tipo" id="documento_tipo" 
								required="true" value="SALIDA" 
								<?php if ((isset($documento['documento_id']) && 
									$documento['documento_tipo'] == 'SALIDA')) 
									echo 'checked'; ?>>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="guardar" onclick="DocumentoInventario.save()">
					Confirmar
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</form>

<script>
	$(function() {
		DocumentoInventario.init();
	});
</script>