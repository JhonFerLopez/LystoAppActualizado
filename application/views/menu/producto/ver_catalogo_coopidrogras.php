<?php $ruta = base_url(); ?>


<div class="modal-dialog modal-lg" style="width: 90%">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" id="xcerrarmodalcatalogo" data-dismiss="modal" aria-hidden="true"
                    onclick="cerrar_modal_catalogo()">&times;
            </button>
            <h4 class="modal-title">Cat&aacute;logo Proveedor Principal</h4>
        </div>


        <div class="modal-body">

            <div class="table-responsive ">
                <table class='table table-striped dataTable table-bordered table-condensed' id="table_catalogo">
                    <thead>
                    <tr>

                        <th>Producto</th>
                        <th>Nombre</th>
                        <th>Costo Corriente</th>
                        <th>Costo Real</th>
                        <th>Iva</th>
                        <th>Nombre Laboratorio</th>
                        <th>C&oacute;digo de Laboratorio</th>
                        <th>Bonificaci&oacute;n</th>
                        <th>Seleccionar</th>

                    </tr>
                    </thead>
                    <tbody id="tbody_catalogo">

                    </tbody>
                </table>
            </div>
        </div>


        <!-- <div class="modal-footer">
             <div class="text-right">
                 <button class="btn btn-primary" type="button" onclick="seleccionar_catalogo()" id="btnGuardar"><i
                         class="fa"></i> Seleccionar
                 </button>
                 <input type="reset" class='btn btn-default' value="Cancelar" data-dismiss="modal">
             </div>


         </div>-->

    </div>

</div>

<script>

    var vistaACargar = '<?= $vistaACargar  ?>'
    function seleccionar_catalogo(check) {

        /*por favor dejar esta funcion asi, ya que este catalogo es llamado desde producto y compras*/
        if (vistaACargar == "ingreso") {
            Compra.procesar_catalogo(check);
        }
        if (vistaACargar == "producto") {
            Producto.verificar_catalogoProducto(check);
        }


    }


    $(function () {

        /*llamo a los productos en la tabla catalogo*/
        TablesDatatablesLazzy.init('<?php echo base_url()?>producto/get_json_catalogo_coopidrogas', 0, 'table_catalogo', false);


    });


</script>

