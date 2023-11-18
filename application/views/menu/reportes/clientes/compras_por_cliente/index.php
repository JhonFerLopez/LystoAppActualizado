
<style>
    caption {
        display: none
    }
</style>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Compras por cliente</h4></div>
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
            <!-- Progress Bars Wizard Title -->


            <div class="row">
                <input type="hidden" name="listar" id="listar" value="ventas">

                <div class="col-md-1">
                    Desde
                </div>
                <div class="col-md-3">
                    <input type="text" name="fecha_desde" id="fecha_desde"
                           value="<?= date('d-m-Y'); ?>"
                           required="true"
                           class="form-control fecha campos">
                </div>
                <div class="col-md-1">
                    Hasta
                </div>


                <div class="col-md-3">
                    <input type="text" name="fecha_hasta" id="fecha_hasta" value="<?= date('d-m-Y'); ?>" required="true"
                           class="form-control fecha campos">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1">
                    Clientes
                </div>
                <div class="col-md-3">
                    <select id="select_cliente" class="form-control select2">
                        <option value="TODOS" selected>TODOS</option>
                        <?php
                        if (count($clientes) > 0) {
                            foreach ($clientes as $row) { ?>
                                <option value="<?= $row->id_cliente ?>"><?= $row->nombres . ' ' . $row->apellidos ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>


                <div class="col-md-1">
                    Producto
                </div>
                <div class="col-md-3">
                    <select id="productoselect" class="js-data-example-ajax">
                        <option value="TODOS" selected>TODOS</option>
                    </select>
                </div>

            </div>

            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="">
                        <table class="table table-striped dataTable table-bordered" id="tabla">

                            <thead id="theadtabla">
                            <tr>
                                <th>Venta ID</th>
                                <th>Producto ID</th>
                                <th>Código interno</th>
                                <th>Producto</th>
                                <?php foreach ($unidades as $unidades) {
                                    echo "<th>" . $unidades->nombre_unidad . "</th>";
                                } ?>
                            </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    var cliente_selected='';
    var producto_selected='';
    $(function () {

        $(".fecha").datepicker({format: 'dd-mm-yyyy'});

        $(".campos").on("change", function () {
            get_datos();
        });

        var languageseelct2 = {
            inputTooShort: function () {
                return 'Ingrese para buscar';
            },
            noResults: function () {
                return "Sin resultados";
            },
            searching: function () {
                return "Buscando...";
            },
            errorLoading: function () {
                return 'El resultado aún no se ha cargado.';
            },
            loadingMore: function () {
                return 'Cargar mas resultados...';
            },
        }

        $("#select_cliente").select2(
            {
                width: "100%",
                multiple: false,
                allowClear: true,
                placeholder: 'Buscar clientes',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                language: languageseelct2
            }).on('select2:selecting', function (e) {
            //cada vez que selecciono una opcion
            cliente_selected=e.params.args.data.id
            get_datos();
        }).on("select2:unselecting", function (e) {
            //se ejecuta antes de desmarcar la opcion
            cliente_selected=''
        }).on("select2:unselect", function (e) {
            cliente_selected=''
            //despues de que ya haya sido desmaracado una opcion
            get_datos();
        }).trigger('change');


        $("#productoselect").select2(
            {
                width: "100%",
                multiple: false,
                allowClear: true,
                placeholder: 'Buscar Producto',
                ajax: {
                    url: baseurl + ProductoService.urlApi + '/productToSelect2',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            type: 'public'
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.productos,
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                language: languageseelct2
            }).on('select2:selecting', function (e) {
            //cada vez que selecciono una opcion
            //e.params.args.data
            producto_selected=e.params.args.data.id
            get_datos();
        }).on("select2:unselecting", function (e) {
            producto_selected=''
            //cada vez que desmarco una opcion
        }).on("select2:unselect", function (e) {
            producto_selected=''
            //despues de que ya haya sido desmaracado una opcion
            get_datos();
        }).trigger('change');




        get_datos();

    });

    function get_datos() {


        var table = $('#tabla').DataTable();
        table.destroy();

        Utilities.showPreloader();

        TablesDatatablesLazzy.init(baseurl + VentaService.urlApi + '/data_clientes_compras_por_cliente', 0, 'tabla', {
            desde: $('#fecha_desde').val(),
            hasta: $('#fecha_hasta').val(),
            id_cliente: cliente_selected,
            producto_id: producto_selected,
        }, false, false, false, false, false, false);
        Utilities.hiddePreloader();


    }

</script>



