<?php $ruta = base_url(); ?>

<div class="modal-dialog" style="width: 70%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Ventas en espera</h4>
        </div>

        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered" id="tablaresultadostatus">
                    <thead>
                    <tr>

                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Fecha</th>
                        <th>Total</th>


                    </tr>
                    </thead>
                    <tbody id="selectable">
                    <?php if (count($ventas) > 0){

                        foreach ($ventas as $venta) {
                            ?>
                            <tr id="<?= $venta->venta_id?>">
                                <td><?= $venta->venta_id ?></td>
                                <td><?= $venta->tipo_venta_nombre ?></td>
                                <td><?= $venta->nombres." ".$venta->apellidos ?></td>
                                <td><?= $venta->nombre ?></td>

                                <td><?= date('d-m-Y H:i:s', strtotime($venta->fecha)) ?></td>

                                <td><?= $venta->total ?></td>



                            </tr>
                        <?php }
                    } ?>

                    </tbody>
                </table>

            </div>
        </div>
        <div class="modal-footer">

            <a href="#" id="abrir"
               class="btn  btn-default " data-toggle="tooltip" title="Abrir"
               data-original-title="fa fa-file-pdf-o"><i class="fa fa-folder-open"></i>Continuar venta</a>
            <a href="#"
               class="btn  btn-default" data-dismiss="modal" title="Cancelar"
               data-original-title="fa fa-file-pdf-o"><i class="fa fa-remove"></i>Cancelar</a>

        </div>
    </div>
</div>






<script type="text/javascript">
    $(function () {
        var ruta = '<?php echo $ruta; ?>';
        TablesDatatables.init(0,'tablaresultadostatus');
        $(function() {
            $( "#selectable" ).selectable({
                stop: function() {

                         var id =$("#selectable tr.ui-selected").attr('id');
                        console.log( id );

                }
            });
        });

        $("#abrir").click(function(){
            var id =$("#selectable tr.ui-selected").attr('id');
            $.ajax({
                type: 'POST',
                data:{'idventa':id},
                dataType:'json',
                url: ruta + 'venta/verVentaJson',
                success: function (data) {


                       /// $("#frmVenta").reset();
                   $("#selectproductos").val('').trigger("chosen:updated");
                    $("#idventa").val(data.ventas[0].venta_id);
                    //$("#tipo_documento").val(data.ventas[0].descripcion).trigger("chosen:updated");
                    $("#tipoventa").val(data.ventas[0].venta_tipo).trigger("chosen:updated");
                    $("#id_cliente").val(data.ventas[0].cliente_id).trigger("chosen:updated");
                    //$("#cboModPag").val(data.ventas[0].id_condiciones).trigger("chosen:updated");
                    $("#venta_status").val(data.ventas[0].venta_status).trigger("chosen:updated");
                    $("#fecha").val(data.ventas[0].fechaemision);

                    $("#basegravada").val(data.ventas[0].subTotal);
                    $("#iva").val(data.ventas[0].impuesto);
                    $("#totApagar").val(data.ventas[0].montoTotal);
                    $("#dineroentregado").val(data.ventas[0].pagado);
                    $("#cambio").val(data.ventas[0].cambio);
                    $("#id_vendedor").val(data.ventas[0].id_vendedor);
                    $("#id_vendedor").prop('disabled', true);
                    $("#id_vendedor").trigger("chosen:updated");

                    if(data.ventas[0].desc_global=='1'){
                        $("#descuentoenvalor").val(data.ventas[0].descuento_valor);
                        $("#descuentoenporcentaje").val(data.ventas[0].descuento_porcentaje);

                    }

                    Venta.tablalistaventa.clear()
                        .draw();

                    Venta.lst_producto = new Array();


                    for(var i=0;i<data.ventas.length;i++){
                        var producto_id=data.ventas[i].producto_id;
                        var producto_nombre=data.ventas[i].nombre;
                        var porcentaje_impuesto=parseFloat(data.ventas[i].porcentaje_impuesto);
                        var tipo_impuesto=data.ventas[i].tipo_impuesto;
                        var tipo_otro_impuesto=data.ventas[i].tipo_otro_impuesto;
                        var porcentaje_otro_impuesto=parseFloat(data.ventas[i].porcentaje_otro_impuesto);
                        var precio_abierto=parseFloat(data.ventas[i].precio_abierto);
                        var descuento=parseFloat(data.ventas[i].descuento);
                        var desc_porcentaje=parseFloat(data.ventas[i].desc_porcentaje);
                        var control_inven=parseFloat(data.ventas[i].control_inven);
                        var producto_tipo=parseFloat(data.ventas[i].producto_tipo);
                        var producto_codigo_interno=parseFloat(data.ventas[i].producto_codigo_interno);
                        var fe_impuesto=parseFloat(data.ventas[i].fe_impuesto);
                        var fe_otro_impuesto=parseFloat(data.ventas[i].fe_otro_impuesto);
                        var fe_type_item_identification_id=parseFloat(data.ventas[i].fe_type_item_identification_id);
                        var is_paquete=data.ventas[i].is_paquete;

                        console.log('dsdasdd ', tipo_otro_impuesto);
                        Venta.edicion=1;
                        Venta.addProductoToArray(producto_id, encodeURIComponent(producto_nombre),
                            porcentaje_impuesto, porcentaje_otro_impuesto, tipo_impuesto,
                            tipo_otro_impuesto, is_paquete, control_inven,producto_tipo,producto_codigo_interno, fe_type_item_identification_id,
                            fe_impuesto, fe_otro_impuesto, data.ventas[i].detalle_unidad);
                        Venta.addproductototable(producto_id, producto_nombre, i, porcentaje_impuesto,
                            data.ventas[i].detalle_unidad,precio_abierto,descuento,desc_porcentaje);

                    }

                    $("#venta_status").val('COMPLETADO');
                    Venta.getTipoVenta()
                    $("#ventasabiertas").modal('hide');
                }
            });
        })
    });
