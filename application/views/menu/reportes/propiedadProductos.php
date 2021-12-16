<?php $ruta = base_url(); ?>

<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Reporte por Propiedades del Producto</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>">SID</a></li>
            <li class="active">Reportes</li>
        </ol>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">


    <div class="col-md-12">

        <div class="white-box">
            <!-- Progress Bars Wizard Title -->

            <div class="row">

                <div class="col-md-1">
                    Categor&iacute;a
                </div>
                <div class="col-md-3">
                    <select id="categoria" class="form-control campos" name="categoria">
                        <option value="" selected>SELECCIONE</option>
                        <option value="CLASIFICACION">CLASIFICACIÓN</option>
                        <option value="TIPO">TIPO</option>
                        <option value="COMPONENTE">COMPONENTE</option>
                        <option value="GRUPO">GRUPO</option>
                        <option value="UBICACION_FISICA">UBICACIÓN FÍSICA</option>
                        <option value="IMPUESTO">IMPUESTO</option>
                    </select>
                </div>
                <div class="col-md-1">
                    Sub Categor&iacute;a
                </div>
                <div class="col-md-3">
                    <select id="subcategoria" class="form-control campos" name="subcategoria">

                    </select>
                </div>

            </div>

            <div class="divider"><br></div>
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

    var subcategoria = "";
    var subcatSelected = '';

    function definirCategoria() {
        if (subcategoria != "") {
            subcategoria.select2("destroy");
            subcategoria.html("<option value=''>Seleccione<option>");
        }
        subcategoria = $("#subcategoria").select2(
            {
                //dropdownParent: $("#modal_enviar"),
                allowClear: true,
                language: "es",
                width: "100%",
                placeholder: 'Buscar Sub Categoria',
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work

                language: {
                    inputTooShort: function () {
                        return 'Ingrese un texto para buscar';
                    },
                    noResults: function () {
                        return "Sin resultados";
                    }
                }
            });


        subcategoria.on('select2:select', function (e) {
            /*Entra aqui, cuando se seleccione un cliente*/
            var data = e.params.data;
            subcatSelected = data;
            console.log('data', data)
            /*genero un mensaje  con los datos anteriores*/
            traerProductos(data.id);

        });
    }

    function traerProductos() {

        Utilities.showPreloader();

        if ($("#categoria").val() == "") {
            return false
        }

        if ($("#subcategoria").val() == "") {
            return false
        }

        $.ajax({
            url: baseurl + 'reportes/productos_x_propiedad',
            data: {
                'categoria': $("#categoria").val(),
                'subcategoria': subcatSelected.id,
                'subcategoria_name': subcatSelected.text
            },
            type: 'POST',
            success: function (data) {
                Utilities.hiddePreloader();
                if (data.length > 0) {
                    $("#tabla").html(data);
                }
                $("#tablaresult").dataTable();
                Utilities.hiddePreloader();
            },
            error: function () {
                Utilities.hiddePreloader();
                Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
            }
        })


    }

    function imprimirPropProd() {

        var TIPO_IMPRESION = $("#TIPO_IMPRESION").val();
        var IMPRESORA = $("#IMPRESORA").val();

        var TICKERA_URL = $("#TICKERA_URL").val();
        var is_nube = TIPO_IMPRESION == 'NUBE' ? 1 : 0;

        var username ='<?= $this->session->userdata('username')?> ';
        var EMPRESA_NOMBRE ='<?= $this->session->userdata('EMPRESA_NOMBRE')?> ';
        var id_local ='<?= $this->session->userdata('id_local')?> ';
        if (is_nube) {

            $.ajax({
                url: baseurl + 'api/Venta/get_data_printPropProd',
                type: 'POST',
                data: {  'categoria': $("#categoria").val(),
                    'subcategoria': subcatSelected.id,
                    'subcategoria_name': subcatSelected.text ,id_local:id_local},
                success: function (data) {
                    var urltickera = TICKERA_URL;

                    var url = urltickera + '/printPropProd/';


                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            productos:data.productos,
                            subcategoria:data.subcategoria,
                            categoria:data.categoria,
                            condiciones_pago:data.condiciones_pago,
                            unidades:data.unidades,
                            impresora: IMPRESORA,
                            username: username,
                            'subcategoria_name': subcatSelected.text

                        },
                        success: function (data) {
                            Utilities.alertModal('El reporte se ha enviado a la impresora', 'success');

                        }, error: function () {
                            Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                        }
                    });


                }, error: function () {

                }
            });


        } else {
            var url = baseurl + 'reportes/printPropProd/';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'categoria': $("#categoria").val(),
                    'subcategoria': subcatSelected.id,
                    'subcategoria_name': subcatSelected.text
                },
                success: function (data) {
                    Utilities.alertModal('El reporte se ha enviado a la impresora', 'success');

                }, error: function () {
                    Utilities.alertModal('no se ha podido imprimir, contacte con soporte');
                }
            });
        }
    }

    $(function () {
        var url = "";

        definirCategoria();

        $("#categoria").on("change", function () {

            if ($(this).val() == "") {
                return false;
            }

            Utilities.showPreloader();
            url = baseurl;
            if ($(this).val() == "GRUPO") {
                url += 'grupo/getGruposJson'
            }

            if ($(this).val() == "CLASIFICACION") {
                url += 'clasificacion/getClasificacionJson'
            }
            if ($(this).val() == "TIPO") {
                url += 'tipo_producto/getTiposJson'
            }

            if ($(this).val() == "COMPONENTE") {
                url += 'componentes/getComponentesJson'
            }

            if ($(this).val() == "UBICACION_FISICA") {
                url += 'ubicacion_fisica/getUbicacionJson'
            }

            if ($(this).val() == "IMPUESTO") {
                url += 'impuesto/getImpuestosJson'
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    var newOption = "";
                    definirCategoria();
                    for (var i = 0; i < data.length; i++) {
                        newOption = new Option(data[i].text, data[i].id, false, false);
                        subcategoria.append(newOption);
                    }
                    subcategoria.trigger('change');
                    Utilities.hiddePreloader();
                },
                error: function () {
                    Utilities.hiddePreloader();
                    Utilities.alertModal('Ocurrio un error por favor intente nuevamente');
                }
            })

        });


    });


</script>
