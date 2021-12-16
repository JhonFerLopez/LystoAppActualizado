<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class importar extends MY_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('unidades/unidades_model');
        $this->load->model('ingreso/ingreso_model');
        $this->very_sesion();
    }

    function index()
    {

        $data = array();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/importar/form', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }

    }

    function productosdosCodigoBarra(){
        //solo guarda los codigos de barra para los productos con dos unidades de medida
        //aqui se carga el mismo archivo de productos, solo que se va a tomar el 2 y 3 campo que son los que tienen los
        //codigos de barra

        $this->load->model('producto/producto_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('producto_barra/producto_barra_model');
        $this->load->model('grupos/grupos_model');

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        try {
            if (!empty($_FILES) and $_FILES['codigobarrados']['size'] != '0') {
                $this->db->trans_begin();
                $filas = file($_FILES['codigobarrados']['tmp_name']);
                unset($filas[0]);
                $this->db->query("DELETE FROM producto_codigo_barra");

                foreach ($filas as $v) {

                    $datos = explode(";", $v);

                    if ($datos[0] != "" && $datos[1] != "") {

                        $buscarCodigo = $this->producto_model->get_by('producto_codigo_interno', $datos[0]);

                        $arreglo = array();

                        if (count($buscarCodigo) > 0) {
                            $producto_id = $buscarCodigo['producto_id'];

                            if ($datos[2] != "" && $datos[2] >0) {
                                //EL CODIGO DE BARRA
                                $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => $datos[2]));
                                //si no encontro el coigo de barra
                                if (count($buscarcodigobarra) < 1) {
                                    $this->producto_barra_model->insert(
                                        array('producto_id' => $producto_id,
                                            'codigo_barra' => $datos[2])
                                    );
                                }
                            }
                            if ($datos[3] != "" && $datos[3]>0) {
                                //EL CODIGO DE BARRA TAMBIEN
                                $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => $datos[3]));
                                //si no encontro el coigo de barra
                                if (count($buscarcodigobarra) < 1) {
                                    $this->producto_barra_model->insert(
                                        array('producto_id' => $producto_id,
                                            'codigo_barra' => $datos[3])
                                    );
                                }
                            }

                        }


                    }

                }

                $this->db->query("DELETE FROM producto_codigo_barra WHERE codigo_barra LIKE '%+%' ");

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $json['error'] = 'Ha ocurido un error al guardar los productos';
                } else {
                    $this->db->trans_commit();
                    $json['success'] = 'Los codigos de barra han sido agregados correctamente';
                }

            } else {

                $json['error'] = 'Debe seleccionar un archivo';

            }

        } catch
        (Exception $e) {
            $json['error'] = 'Ocurrio un error al importar: '.$e->getMessage();
            log_message("error", "Ocurrio un error al importar:  " . $e->getMessage());
        }

        echo json_encode($json);

    }

    function productosdos()
    {

        $this->load->model('producto/producto_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('producto_barra/producto_barra_model');
        $this->load->model('grupos/grupos_model');

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        try {
            if (!empty($_FILES) and $_FILES['userfile']['size'] != '0') {


                $this->db->trans_begin();

                $filas = file($_FILES['userfile']['tmp_name']);

                unset($filas[0]);
                //la fila 0 es la que tiene los nombres de los campos

                //busco las dos unidades que se insertan en el archivo
                $caja = $this->unidades_model->get_by('orden', 1);
                $unidad = $this->unidades_model->get_by('orden', 3);

                //las condiciones de pago
                $contado = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CONTADO');
                $credito = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CREDITO');

                foreach ($filas as $v) {


                    $datos = explode(";", $v);

                    if ($datos[0] != "" && $datos[1] != "") {

                        $buscarCodigo = $this->producto_model->get_by('producto_codigo_interno', $datos[0]);

                        $arreglo = array();
                        $arreglo = array(
                            'producto_activo' => 1,
                            'producto_estatus' => 1,
                            'producto_codigo_interno' => $datos[0],
                            'producto_nombre' => $datos[1],
                            'costo_unitario' => $datos[18] != "" ? $datos[18] : null,
                            'costo_promedio' => $datos[19] != "" ? $datos[19] : null
                        );


                        if ($datos[5] == "N") {
                            $arreglo['precio_abierto'] = 0;
                        }
                        if ($datos[5] == "S") {
                            $arreglo['precio_abierto'] = 1;
                        }

                        //control inventario
                        $arreglo['control_inven'] = 1;
                        if ($datos[28] == "N") {
                            $arreglo['control_inven'] = 0;
                        }
                        if ($datos[28] == "S") {
                            $arreglo['control_inven'] = 1;
                        }

                        $arreglo['control_inven_diario'] = 0;
                        //control inventario diario
                        if ($datos[29] == "N") {
                            $arreglo['control_inven_diario'] = 0;
                        }
                        if ($datos[29] == "S") {
                            $arreglo['control_inven_diario'] = 1;
                        }

                        if ($datos[12] != "" and str_replace(',', ".", $datos[12]) > 0.0) {
                            //$datos[12] es el iva
                            $iva = $this->impuestos_model->get_by('porcentaje_impuesto', str_replace(',', ".", $datos[12]) * 100);

                            if (sizeof($iva) < 1) {
                                $impuesto = array(
                                    'nombre_impuesto' => "IVA " . str_replace(',', ".", $datos[12]) * 100,
                                    'porcentaje_impuesto' => str_replace(',', ".", $datos[12]) * 100,
                                    'estatus_impuesto' => 1
                                );
                                $id_iva = $this->impuestos_model->crear_impuesto($impuesto);
                            } else {
                                $id_iva = $iva['id_impuesto'];
                            }

                            $arreglo['producto_impuesto'] = $id_iva;
                        }

                        if ($datos[40] !== "" and $datos[40] != false) {
                            //$datos[40] es la ubicacion fisica
                            $ub = $this->ubicacion_fisica_model->get_by(array('ubicacion_nombre' => $datos[40]));

                            if (sizeof($ub) < 1) {
                                $ubicacion = array(
                                    'ubicacion_nombre' => $datos[40],
                                    'deleted_at' => null
                                );
                                $id_ubi = $this->ubicacion_fisica_model->insert($ubicacion);
                            } else {
                                $id_ubi = $ub['ubicacion_id'];
                            }

                            $arreglo['producto_ubicacion_fisica'] = $id_ubi;
                        }

                        if ($datos[43] != "" and str_replace(',', ".", $datos[43]) > 0.0) {
                            //$datos[43] es % de comision
                            $arreglo['producto_comision'] = str_replace(',', ".", $datos[43]) * 100;
                        }


                        if (isset($datos[65]) && $datos[65] != "") {
                            //$datos[65] es la Clasificacion
                            $clas = $this->clasificacion_model->get_by(array('clasificacion_nombre' => $datos[65]));

                            if (sizeof($clas) < 1) {
                                $clasificacion = array(
                                    'clasificacion_nombre' => $datos[65],
                                    'deleted_at' => NULL
                                );
                                $id_clasi = $this->clasificacion_model->insert($clasificacion);
                            } else {
                                $id_clasi = $clas['clasificacion_id'];
                            }

                            $arreglo['producto_clasificacion'] = $id_clasi;
                        }

                        $id_compo = "";
                        if (isset($datos[66]) && $datos[66] != "") {
                            //$datos[66] es el Componente o Principio activo
                            $comp = $this->componentes_model->get_by(array('componente_nombre' => $datos[66]));

                            if (sizeof($comp) < 1) {
                                $componente = array(
                                    'componente_nombre' => $datos[66],
                                    'deleted_at' => NULL
                                );
                                $id_compo = $this->componentes_model->insert($componente);
                            } else {
                                $id_compo = $comp['componente_id'];
                            }
                        }


                        if (isset($datos[78]) && $datos[78] != "" and str_replace(',', ".", $datos[78]) > 0.0) {
                            //$datos[78] es la bonificacion de producto
                            $arreglo['producto_bonificaciones'] = str_replace(',', ".", $datos[78]);
                        }


                        if ($datos[21] != "") {
                            //$datos[20]  //GRUPO 1
                            $grupo = $this->grupos_model->get_by('codigo', $datos[21]);
                            if (sizeof($grupo) > 0) {
                                $arreglo['produto_grupo'] = $grupo['id_grupo'];

                            }
                        }

                        if ($buscarCodigo!=NULL && count($buscarCodigo) > 0) {
                            //le actualizo el nombre
                            $this->producto_model->solo_update(
                                array('producto_codigo_interno' => $datos[0]),
                                $arreglo
                            );
                            $producto_id = $buscarCodigo['producto_id'];

                        } else {

                            $producto_id = $this->producto_model->solo_insertar($arreglo);

                            $unidades = array();
                            $precios = array();

                            /********************************************************/
                            $unidades = array(
                                'id_unidad' => $caja['id_unidad'],
                                'producto_id' => $producto_id,
                                'unidades' => $datos[13] + 0 < 1 ? 1 : $datos[13] + 0
                            );
                            $this->unidades_model->insert_unidades_producto($unidades);

                            if ($datos[13] + 0 > 1) {
                                $unidades['id_unidad'] = $unidad['id_unidad'];
                                $unidades['unidades'] = 1;

                                $this->unidades_model->insert_unidades_producto($unidades);
                            }
                            /********************************************************/

                            $precios = array(
                                'id_condiciones_pago' => $contado['id_condiciones'],
                                'id_unidad' => $caja['id_unidad'],
                                'id_producto' => $producto_id,
                                'precio' => $datos[6]  // es el costo de la caja a contado
                            );
                            $this->unidades_has_precio_model->insert($precios);

                            if ($datos[8] != "" and $datos[8] > 0) {
                                //el costo de la caja a credito
                                $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                                $precios['precio'] = $datos[8];
                            }


                            $precios = array(
                                'id_condiciones_pago' => $contado['id_condiciones'],
                                'id_unidad' => $unidad['id_unidad'],
                                'id_producto' => $producto_id,
                                'precio' => $datos[7]
                            );
                            //$datos[7] es el valor del costo a contado de la fraccion, es decir, de la unidad
                            if ($datos[7] != "" and $datos[7] > 0) {
                                $this->unidades_has_precio_model->insert($precios);
                            }
                            if ($datos[9] != "" and $datos[9] > 0) {
                                //el costo de la unidad a credito
                                $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                                $precios['precio'] = $datos[9];
                                $this->unidades_has_precio_model->insert($precios);
                            }


                            if ($datos[2] != "") {
                                //EL CODIGO DE BARRA
                                $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => $datos[2]));
                                //si no encontro el coigo de barra
                                if ($buscarcodigobarra!=NULL && count($buscarcodigobarra) < 1) {
                                    $this->producto_barra_model->insert(
                                        array('producto_id' => $producto_id,
                                            'codigo_barra' => $datos[2])
                                    );
                                }
                            }
                            if ($datos[3] != "") {
                                //EL CODIGO DE BARRA TAMBIEN
                                $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => $datos[3]));
                                //si no encontro el coigo de barra
                                if ($buscarcodigobarra!=NULL && count($buscarcodigobarra) < 1) {
                                    $this->producto_barra_model->insert(
                                        array('producto_id' => $producto_id,
                                            'codigo_barra' => $datos[3])
                                    );
                                }
                            }


                            $operacion = "SUMA";

                            //inventario de la caja
                            if ($datos[32] != "") {

                                $this->ingreso_model->setInventario($producto_id, $caja['id_unidad'], $datos[32],
                                    ENTRADA_MIGRACION,
                                    $datos[18] != "" ? $datos[18] : 0, null, null, null,
                                    ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                    null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                    str_replace(',', ".", $datos[12]) * 100,
                                    $datos[18] != "" ? $datos[18] : null);
                            }


                            //PENDIENTE GUARDAR UNIDAD
                            //inventario actual de la unidad: invenfracc
                            if ($datos[33] != "" && $datos[33] > 0) {

                                $where = array(
                                    'producto_id' => $producto_id
                                );
                                $unidades_producto = $this->unidades_model->solo_unidades_xprod($where);
                                if ($unidades_producto!=NULL &&  count($unidades_producto) > 0 && $datos[18] != "") {
                                    foreach ($unidades_producto as $rowunidad) {

                                        if ($rowunidad['id_unidad'] == $unidad['id_unidad']) {
                                            $costoestaunidad = $this->producto_model->calcularCostoUnidad($unidad['id_unidad'], $datos[18], $unidades_producto);

                                            $this->ingreso_model->setInventario($producto_id, $unidad['id_unidad'], $datos[33],
                                                ENTRADA_MIGRACION,
                                                $costoestaunidad, null, null, null,
                                                ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                                null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                                str_replace(',', ".", $datos[12]) * 100,
                                                $datos[18] != "" ? $datos[18] : null);
                                        }
                                    }
                                }

                            }


                            if (isset($datos[66]) && $datos[66] != "") {

                                //lo guardo en la tabla producto_has_componente
                                $this->producto_componente_model->insert(
                                    array(
                                        'producto_id' => $producto_id,
                                        'componente_id' => $id_compo
                                    )
                                );
                            }


                        }//final condicion si el producto existe

                        if ($datos[20] != "") {
                            //$datos[20]  //GRUPO 1
                            $grupo = $this->grupos_model->get_by('codigo', $datos[20]);
                            if (sizeof($grupo) > 0) {
                                $datosgrupo = array(
                                    'producto_id' => $producto_id,
                                    'grupo_id' => $grupo['id_grupo']
                                );
                                $buscarprodgrup = $this->grupos_model->get_productohasgrupo($datosgrupo);

                                if ($buscarprodgrup!=NULL && count($buscarprodgrup) < 1) {
                                    $this->grupos_model->insertproductohasgrupo($datosgrupo);
                                }

                            }
                        }

                        if ($datos[21] != "") {
                            //$datos[21]  //GRUPO 2
                            $grupo = $this->grupos_model->get_by('codigo', $datos[21]);
                            if (sizeof($grupo) > 0) {
                                $datosgrupo = array(
                                    'producto_id' => $producto_id,
                                    'grupo_id' => $grupo['id_grupo']
                                );
                                $buscarprodgrup = $this->grupos_model->get_productohasgrupo($datosgrupo);

                                if ($buscarprodgrup!=NULL && count($buscarprodgrup) < 1) {
                                    $this->grupos_model->insertproductohasgrupo($datosgrupo);
                                }

                            }
                        }


                        //inserto los codigos de barra
                        if ($datos[2] != "") {
                            //EL CODIGO DE BARRA
                            $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => $datos[3]));
                            //si no encontro el coigo de barra
                            if ($buscarcodigobarra!=NULL && count($buscarcodigobarra) < 1) {
                                $this->producto_barra_model->insert(
                                    array('producto_id' => $producto_id,
                                        'codigo_barra' => $datos[2])
                                );
                            }
                        }

                    }

                }

                $this->db->query("DELETE FROM producto_codigo_barra WHERE codigo_barra LIKE '%+%' ");

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $json['error'] = 'Ha ocurido un error al guardar los productos';
                } else {
                    $this->db->trans_commit();
                    $json['success'] = 'Los productos han sido agregados correctamente';
                }

            } else {

                $json['error'] = 'Debe seleccionar un archivo';

            }


        } catch
        (Exception $e) {
            $json['error'] = 'Ocurrio un error al importar: '.$e->getMessage();
            log_message("error", "Ocurrio un error al importar:  " . $e->getMessage());
        }

        echo json_encode($json);
    }


    function productostres()
    {

        //segun el archivo, debe eliminarse la columa BM que es sustituto
        $this->load->model('producto/producto_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('producto_barra/producto_barra_model');


        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);
        $codigos_barra = array();

        $id_productos = array();

        if (isset($_FILES) && $_FILES["archivo"]['error'] == 0) {

            $con = 0;
            $this->db->trans_begin();

            $filas = fopen($_FILES['archivo']['tmp_name'], "r");

            unset($filas[0]);
            //la fila 0 es la que tiene los nombres de los campos

            //busco las dos unidades que se insertan en el archivo
            $caja = $this->unidades_model->get_by('orden', 1);
            $blister = $this->unidades_model->get_by('orden', 2);
            $unidad = $this->unidades_model->get_by('orden', 3);

            //las condiciones de pago
            $contado = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CONTADO');
            $credito = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CREDITO');

            while (($datos = fgetcsv($filas, ",")) == true) {


                if ($con > 0) {
                    //$datos = explode(";", $datos[0]);

                    if ($datos[0] != "") {

                        $buscarCodigo = $this->producto_model->get_by('producto_codigo_interno', trim($datos[1]));

                        $arreglo = array();
                        $arreglo = array(
                            'producto_activo' => 1,
                            'producto_estatus' => 1,
                            'producto_codigo_interno' => trim($datos[1]),
                            'producto_nombre' => $datos[2],
                            'costo_unitario' => $datos[9] != "" ? $datos[9] : null,
                            'costo_promedio' => $datos[10] != "" ? $datos[10] : null,
                            'producto_descuentos' => $datos[11] != "" ? $datos[11] : null,
                            'costo_cargue' => $datos[12] != "" ? $datos[12] : null
                        );

                        if ($datos[70] == "N") {
                            $arreglo['precio_abierto'] = 0;
                        }
                        if ($datos[70] == "S") {
                            $arreglo['precio_abierto'] = 1;
                        }

                        //control inventario
                        $arreglo['control_inven'] = 1;
                        if ($datos[71] == "N") {
                            $arreglo['control_inven'] = 0;
                        }
                        if ($datos[71] == "S") {
                            $arreglo['control_inven'] = 1;
                        }

                        //control inventario diario
                        if ($datos[72] == "N") {
                            $arreglo['control_inven_diario'] = 0;
                        }
                        if ($datos[72] == "S") {
                            $arreglo['control_inven_diario'] = 1;
                        }




                        if ($datos[4] != "" and str_replace(',', ".", $datos[4]) > 0.0) {
                            //$datos[12] es el iva
                            $iva = $this->impuestos_model->get_by('porcentaje_impuesto',
                                str_replace(',', ".", $datos[4]) * 100);

                            if (sizeof($iva) < 1) {
                                $impuesto = array(
                                    'nombre_impuesto' => "IVA " . str_replace(',', ".", $datos[4]) * 100,
                                    'porcentaje_impuesto' => str_replace(',', ".", $datos[4]) * 100,
                                    'estatus_impuesto' => 1
                                );
                                $id_iva = $this->impuestos_model->crear_impuesto($impuesto);
                            } else {
                                $id_iva = $iva['id_impuesto'];
                            }

                            $arreglo['producto_impuesto'] = $id_iva;
                        }


                        if ($datos[46] != "" and str_replace(',', ".", $datos[46]) > 0.0000) {
                            //$datos[43] es % de comision
                            $arreglo['producto_comision'] = str_replace(',', ".", $datos[46]) * 100;
                        }

                        if ($datos[67] != "" and str_replace(',', ".", $datos[67]) > 0.0000) {
                            //$datos[78] es la bonificacion de producto
                            $arreglo['producto_bonificaciones'] = str_replace(',', ".", $datos[67]);
                        }

                        if (sizeof($buscarCodigo) < 1) {
                            $producto_id = $this->producto_model->solo_insertar($arreglo);
                            $unidades = array();
                            $precios = array();

                            /************************CONTENIDO INTERNO********************************/
                            $unidades = array(
                                'producto_id' => $producto_id
                            );
                            if ($datos[5] != "" && $datos[5] != null && $datos[5] > 0) {
                                $unidades['id_unidad'] = $caja['id_unidad'];
                                $unidades['unidades'] = $datos[5];
                                $unidades['stock_minimo'] = $datos[30] != "" ? $datos[30] : null;
                                $unidades['stock_maximo'] = $datos[31] != "" ? $datos[31] : null;
                                $this->unidades_model->insert_unidades_producto($unidades);
                            }

                            if ($datos[6] != "" && $datos[6] != null && $datos[6] > 0) {
                                $unidades['id_unidad'] = $blister['id_unidad'];
                                $unidades['unidades'] = $datos[6];
                                $unidades['stock_minimo'] = $datos[32] != "" ? $datos[32] : null;
                                $unidades['stock_maximo'] = $datos[33] != "" ? $datos[33] : null;
                                $this->unidades_model->insert_unidades_producto($unidades);
                            }

                            if ($datos[7] != "" && $datos[7] != null && $datos[7] > 0) {
                                $unidades['id_unidad'] = $unidad['id_unidad'];
                                $unidades['unidades'] = $datos[7];
                                $unidades['stock_minimo'] = $datos[34] != "" ? $datos[34] : null;
                                $unidades['stock_maximo'] = $datos[35] != "" ? $datos[35] : null;
                                $this->unidades_model->insert_unidades_producto($unidades);
                            }

                            /***********************UNIDADES HAS PRECIO******************************/

                            $precios = array(
                                'id_producto' => $producto_id
                            );
                            //empezamos con condicion de pago a CONTADO
                            if ($datos[13] != "" && $datos[13] > 0.00) {
                                $precios['precio'] = $datos[13];
                                $precios['id_unidad'] = $caja['id_unidad'];
                                $precios['id_condiciones_pago'] = $contado['id_condiciones'];
                                $precios['utilidad'] = $datos[49] != "" && $datos[49] > 0.0000 ? $datos[49] : null;
                                /* $unidades['precio_minimo']=$datos[34]!=""?$datos[34]:null;
                                 $unidades['precio_maximo']=$datos[35]!=""?$datos[35]:null;*/

                                $this->unidades_has_precio_model->insert($precios);
                            }

                            if ($datos[14] != "" && $datos[14] > 0.00) {
                                $precios['precio'] = $datos[14];
                                $precios['id_unidad'] = $blister['id_unidad'];
                                $precios['id_condiciones_pago'] = $contado['id_condiciones'];
                                $precios['utilidad'] = $datos[50] != "" && $datos[50] > 0.0000 ? $datos[50] : null;
                                $this->unidades_has_precio_model->insert($precios);
                            }

                            if ($datos[15] != "" && $datos[15] > 0.00) {
                                $precios['precio'] = $datos[15];
                                $precios['id_unidad'] = $unidad['id_unidad'];
                                $precios['id_condiciones_pago'] = $contado['id_condiciones'];
                                $precios['utilidad'] = $datos[51] != "" && $datos[51] > 0.0000 ? $datos[51] : null;
                                $this->unidades_has_precio_model->insert($precios);
                            }

                            //empezamos con credito
                            if ($datos[16] != "" && $datos[16] > 0.00) {
                                $precios['precio'] = $datos[16];
                                $precios['id_unidad'] = $caja['id_unidad'];
                                $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                                $precios['utilidad'] = $datos[51] != "" && $datos[51] > 0.0000 ? $datos[52] : null;
                                $this->unidades_has_precio_model->insert($precios);
                            }

                            if ($datos[17] != "" && $datos[17] > 0.00) {
                                $precios['precio'] = $datos[17];
                                $precios['id_unidad'] = $blister['id_unidad'];
                                $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                                $precios['utilidad'] = $datos[51] != "" && $datos[51] > 0.0000 ? $datos[53] : null;
                                $this->unidades_has_precio_model->insert($precios);
                            }

                            if ($datos[18] != "" && $datos[18] > 0.00) {
                                $precios['precio'] = $datos[18];
                                $precios['id_unidad'] = $unidad['id_unidad'];
                                $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                                $precios['utilidad'] = $datos[51] != "" && $datos[51] > 0.0000 ? $datos[54] : null;
                                $this->unidades_has_precio_model->insert($precios);
                            }


                            /**************INVENTARIO****************/
                            $where = array(
                                'producto_id' => $producto_id
                            );
                            $unidades_producto = $this->unidades_model->solo_unidades_xprod($where);
                            $operacion = "SUMA";

                            if ($datos[36] != "" && $datos[36] > 0) {

                                $costoestaunidad = $this->producto_model->calcularCostoUnidad($caja['id_unidad'],
                                    $arreglo['costo_unitario'], $unidades_producto);

                                $this->ingreso_model->setInventario($producto_id, $caja['id_unidad'], $datos[36],
                                    ENTRADA_MIGRACION,
                                    $costoestaunidad, null, null,
                                    null,
                                    ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                    null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                    str_replace(',', ".", $datos[4]) * 100,
                                    $arreglo['costo_unitario'] != "" ? $arreglo['costo_unitario'] : null);
                            }

                            if ($datos[37] != "" && $datos[37] > 0) {
                                $costoestaunidad = $this->producto_model->calcularCostoUnidad($blister['id_unidad'],
                                    $arreglo['costo_unitario'], $unidades_producto);

                                $this->ingreso_model->setInventario($producto_id, $blister['id_unidad'], $datos[37],
                                    ENTRADA_MIGRACION,
                                    $costoestaunidad, null, null,
                                    null,
                                    ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                    null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                    str_replace(',', ".", $datos[4]) * 100,
                                    $arreglo['costo_unitario'] != "" ? $arreglo['costo_unitario'] : null);
                            }

                            if ($datos[38] != "" && $datos[38] > 0) {

                                $costoestaunidad = $this->producto_model->calcularCostoUnidad($blister['id_unidad'],
                                    $arreglo['costo_unitario'], $unidades_producto);

                                $this->ingreso_model->setInventario($producto_id, $unidad['id_unidad'], $datos[38],
                                    ENTRADA_MIGRACION,
                                    $costoestaunidad, null, null,
                                    null,
                                    ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                    null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                    str_replace(',', ".", $datos[4]) * 100,
                                    $arreglo['costo_unitario'] != "" ? $arreglo['costo_unitario'] : null);
                            }
                        } else {
                            $producto_id = $buscarCodigo['producto_id'];
                            $where=array('producto.producto_id'=>$producto_id);
                            $producto_id = $this->producto_model->solo_update($where,$arreglo);
                        }

                        $id_productos[$con]['id_producto_excel'] = $datos[0];
                        $id_productos[$con]['id_producto'] = $producto_id;
                    }
                }

                $con++;

            }

            $this->db->query("DELETE FROM producto_codigo_barra WHERE codigo_barra LIKE '%+%' ");

            //si encontro el archivo de los codigos de barra y tiene contenido
            if (isset($_FILES) && $_FILES["codigos_barra"]['error'] == 0) {
                $codigos_barra = file($_FILES['codigos_barra']['tmp_name']);

                foreach ($id_productos as $row) {

                    foreach ($codigos_barra as $codigos) {
                        $datosCO = explode(",", $codigos);

                        if ($datosCO[1] != "") {
                            if ($row['id_producto_excel'] == $datosCO[0]) {

                                $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' =>
                                    trim($datosCO[1])));
                                //si no encontro el coigo de barra
                                if (count($buscarcodigobarra) < 1) {
                                    $this->producto_barra_model->insert(
                                        array('producto_id' => $row['id_producto'],
                                            'codigo_barra' => trim($datosCO[1]))
                                    );
                                }

                            }
                        }

                    }

                }
            }

            $this->db->query("DELETE FROM producto_codigo_barra WHERE codigo_barra LIKE '%+%' ");

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $json['error'] = 'Ha ocurido un error al guardar los productos';
            } else {
                $this->db->trans_commit();
                $json['success'] = 'Los productos han sido agregados correctamente';
            }

        } else {

            $json['error'] = 'Debe seleccionar un archivo';

        }

        echo json_encode($json);
    }

    function importar_codigos_barra($productos, $codigos_barra)
    {

        //guarda los codigos de barra
        //recibe un arreglo con los id del producto tanto el que viene del excel, como el que ha sido creado en la db
        $con = 0;
        //recorro el archivo de los codigos de barra
        while (($datos = fgetcsv($codigos_barra, ",")) == true) {

            //recorro el arreglo de id de productos
            foreach ($productos as $row) {
                if ($con > 0) {
                    //los separo en un arreglo, ya que viene en un string separado por ;
                    $data = explode(";", $datos[0]);

                    //pregunto si el data[0]== al id del que esta en el excel, para guardarlo y ponerle el id del que se guardo
                    //en la db
                    if ($data[0] == $row['id_producto_excel']) {

                        $buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' =>
                            trim($data[1])));
                        //si no encontro el coigo de barra
                        if (count($buscarcodigobarra) < 1) {
                            $this->producto_barra_model->insert(
                                array('producto_id' => $row['id_producto'],
                                    'codigo_barra' => trim($data[1]))
                            );
                        }

                    }

                }
                $con++;
            }

        }
    }


    function soloCodigoBarra()
    {


        $this->load->model('producto/producto_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('producto_barra/producto_barra_model');


        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        if (isset($_FILES) && ($_FILES["archivo"]['error'] == 0 && $_FILES["codigos_barra"]['error'] == 0)) {

            $codigos_barra = file($_FILES['codigos_barra']['tmp_name']);
            $filas = file($_FILES['archivo']['tmp_name']);
            $con = 0;
            $this->db->trans_begin();


            $con_consiguio = 0;
            foreach ($filas as $datos) {


                if ($con > 0) {
                    $datosexcel = explode(";", $datos);
                    // var_dump($datos);

                    $producto_id = $datosexcel[0];
                    $producto_codigo = $datosexcel[1];
                    $producto_nombre = $datosexcel[2];

                    if ($producto_id !== "") {
                        $buscarCodigo = $this->producto_model->get_by('producto_codigo_interno', $producto_codigo);

                        if (count($buscarCodigo) > 0) {

                            foreach ($codigos_barra as $row) {

                                $datosCO = explode(";", $row);

                                $producto_id_codigobarra = $datosCO[0];
                                $codigobarraExcel = $datosCO[1];

                                if ($producto_id_codigobarra != "" && $producto_id_codigobarra == $producto_id &&
                                    $producto_nombre == $buscarCodigo['producto_nombre']
                                ) {

                                    $buscarcodigobarra =
                                        $this->producto_barra_model->get_codigo_barra(array('codigo_barra' => trim($codigobarraExcel)));
                                    //si no encontro el coigo de barra
                                    if (count($buscarcodigobarra) < 1) {
                                        $this->producto_barra_model->insert(
                                            array('producto_id' => $buscarCodigo['producto_id'],
                                                'codigo_barra' => trim($codigobarraExcel))
                                        );
                                        $con_consiguio++;
                                    }

                                }

                            }

                        }
                    }
                }

                $con++;

            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $json['error'] = 'Ha ocurido un error al guardar los productos';
            } else {
                $this->db->trans_commit();
                $json['success'] = 'Los Codigos de barra han sido agregados correctamente';
            }

        } else {

            $json['error'] = 'Debe seleccionar ambos archivos';

        }

        echo json_encode($json);

    }


    function grupos()
    {


        $this->load->model('grupos/grupos_model');

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        if (!empty($_FILES) and $_FILES['userfilegrupo']['size'] != '0') {


            $this->db->trans_begin();

            $filas = file($_FILES['userfilegrupo']['tmp_name']);

            unset($filas[0]);
            //la fila 0 es la que tiene los nombres de los campos

            foreach ($filas as $v) {

                $datos = explode(";", $v);

                if ($datos[0] != "") {
                    $grupo = $this->grupos_model->get_by('codigo', $datos[0]);

                    if (sizeof($grupo) < 1) {

                        $guardar = array(
                            'nombre_grupo' => $datos[1],
                            'estatus_grupo' => 1,
                            'codigo' => $datos[0],
                        );
                        $this->db->insert('grupos', $guardar);
                    }
                }

            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $json['error'] = 'Ha ocurido un error al guardar los grupos';
            } else {
                $this->db->trans_commit();
                $json['success'] = 'Los grupos han sido agregados correctamente';
            }

        } else {

            $json['error'] = 'Debe seleccionar un archivo';

        }


        echo json_encode($json);
    }



    function clientes()
    {
        $this->load->model('zona/zona_model');
        $this->load->model('cliente/cliente_model');

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        try {
            if (!empty($_FILES) and $_FILES['clientes']['size'] != '0') {


                $this->db->trans_begin();

                $filas = file($_FILES['clientes']['tmp_name']);

                unset($filas[0]);

                foreach ($filas as $v) {
                    $datos = explode(";", $v);

                    if ($datos[0] != "") {
                        $zona = array(
                            'ciudad_id' => 11,
                            'zona_nombre' => strtoupper($datos[4]),
                            'status' => 1
                        );
                        $buscarzona=$this->zona_model->get_by('zona_nombre',strtoupper($datos[4]));

                        if (sizeof($buscarzona)<1) {
                            $zona_id = $this->zona_model->insertar($zona);

                        } else {
                            $zona_id = $buscarzona['zona_id'];
                        }


                        $cliente = array(
                            'ciudad_id' => 11,
                            'codigo_interno' => $datos[0],
                            'grupo_id' => 2,
                            'sexo' => null,
                            'direccion' => $datos[3],
                            'email' => $datos[6],
                            'nombres' => strtoupper($datos[1]),
                            'apellidos' => null,
                            'identificacion' => $datos[2],
                            'afiliado' => null,
                            'telefono' => $datos[4],
                            'celular' => $datos[5],
                            'fecha_nacimiento' => null,
                            'facturacion_maximo' => null,
                            'valida_fact_maximo' =>0, //bloquear al cliente cuando supere el monto máximo
                            'valida_venta_credito' => 0, //valida si se le vende a credito o no
                            'dias_credito' => null,
                            'latitud' => null,
                            'longitud' => null,
                            'id_zona' => $zona_id,
                            'cliente_status' => 1,
                        );

                        $resultado = $this->cliente_model->soloinsertar($cliente);

                    }

                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $json['error'] = 'Ha ocurido un error al guardar los clientes';
                } else {
                    $this->db->trans_commit();
                    $json['success'] = 'Los clientes han sido agregados correctamente';
                }

            } else {

                $json['error'] = 'Debe seleccionar un archivo';

            }

        } catch
        (Exception $e) {
            $json['error'] = 'Ocurrio un error al importar: '.$e->getMessage();
            log_message("error", "Ocurrio un error al importar:  " . $e->getMessage());
        }

        echo json_encode($json);
    }

    function clienteCustom()
    {
        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        try {
            if (!empty($_FILES) and $_FILES['clientes']['size'] != '0') {

                ModelTransaction::beginTransaction();

                $filas = file($_FILES['clientes']['tmp_name']);

                unset($filas[0]);
                $ciudadmayusc=  CiudadElo::where(array('ciudad_nombre'=>'Cali'))->first();
                if($ciudadmayusc){
                    $ciudadmayusc->ciudad_nombre='CALI';
                    $ciudadmayusc->save();
                }

                $ciudadm=  CiudadElo::where(array('ciudad_nombre'=>'cali'))->first();
                if($ciudadm){
                    $ciudadm->ciudad_nombre='CALI';
                    $ciudadm->save();
                }

                foreach ($filas as $v) {
                    $datos = explode(";", $v);


                    if(is_array($datos)==false || count($datos)===1){
                        $datos = explode(",", $v);
                    }


                    if ($datos[0] != "") {

                        $ciudad=  CiudadElo::firstOrNew(array('ciudad_nombre'=>strtoupper($datos[4]),'estado_id'=>5));
                        $ciudad->save();


                        $zona = array(
                            'ciudad_id' => $ciudad->ciudad_id,
                            'zona_nombre' => strtoupper($datos[6]),
                            'status' => 1
                        );

                        $buscarzona = ZonaElo::firstOrNew($zona);
                        $buscarzona->save();


                        $cliente = array(
                            'ciudad_id' => $ciudad->ciudad_id,
                            'codigo_interno' => trim($datos[0]),
                            'grupo_id' => 2,
                            'sexo' => $datos[9]!=''?$datos[9]:null,
                            'direccion' => $datos[3],
                            'email' => trim($datos[8]),
                            'nombres' => strtoupper($datos[1]),
                            'apellidos' => strtoupper($datos[2]),
                            'identificacion' => $datos[5],
                            'afiliado' => null,
                            'celular' => $datos[7],
                            'fecha_nacimiento' => null,
                            'facturacion_maximo' => null,
                            'valida_fact_maximo' =>0, //bloquear al cliente cuando supere el monto máximo
                            'valida_venta_credito' => 0, //valida si se le vende a credito o no
                            'dias_credito' => null,
                            'latitud' => null,
                            'longitud' => null,
                            'id_zona' => $buscarzona->zona_id,
                            'cliente_status' => 1,
                        );

                        ClienteElo::create($cliente);

                    }

                }

                ModelTransaction::commit();
                $json['success'] = 'Los clientes han sido agregados correctamente';
            } else {

                $json['error'] = 'Debe seleccionar un archivo';

            }

        } catch
        (Exception $e) {
            $json['error'] = 'Ocurrio un error al importar: '.$e->getMessage();
            log_message('error', 'Error al importar clientes: ' . $e->getMessage());
            ModelTransaction::rollback();
        }

        echo json_encode($json);
    }

    function acomodarKardex(){
        $this->load->model('kardex/kardex_model');
        $this->load->model('inventario/inventario_model');
        $where=array(
            'ckardexReferencia'=>'REGISTRO DE FISICOS',
            'cKardexProducto!='=>1198
        );
        $order="dkardexFecha asc";
        $todoskardex=$this->kardex_model->getKardex($where,$order);
        foreach($todoskardex as $row){

            $return_inventario = $this->inventario_model->sumar_inventario($row['cKardexProducto'],
                1, 1, $row['nKardexCantidad']);
            var_dump($return_inventario);
        }


    }

    //esta funcion se pede modificar cuantas veces se quiera, es un custom de importar productos
    function colombo_Suiza()
    {

        $this->load->model('producto/producto_model');
        $this->load->model('condicionespago/condiciones_pago_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('inventario/inventario_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $this->load->model('clasificacion/clasificacion_model');
        $this->load->model('componentes/componentes_model');
        $this->load->model('producto_componente/producto_componente_model');
        $this->load->model('producto_barra/producto_barra_model');
        $this->load->model('grupos/grupos_model');

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);

        try {
            if (!empty($_FILES) and $_FILES['archivo']['size'] != '0') {


                $this->db->trans_begin();

                $filas = file($_FILES['archivo']['tmp_name']);

                unset($filas[0]);
                //la fila 0 es la que tiene los nombres de los campos

                //busco las dos unidades que se insertan en el archivo
                $caja = $this->unidades_model->get_by('orden', 1);
                $unidad = $this->unidades_model->get_by('orden', 3);

                //las condiciones de pago
                $contado = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CONTADO');
                $credito = $this->condiciones_pago_model->get_by('nombre_condiciones', 'CREDITO');

                foreach ($filas as $v) {


                    $datos = explode(",", $v);


                    if ($datos[0] != "" && $datos[1] != "") {

                        $buscarCodigo = $this->producto_model->get_by('producto_codigo_interno', $datos[0]);

                        $arreglo = array();
                        $arreglo = array(
                            'producto_activo' => 1,
                            'producto_estatus' => 1,
                            'producto_codigo_interno' =>  trim($datos[0]),
                            'producto_nombre' => trim($datos[1]),
                            'costo_unitario' =>  trim($datos[4]) != "" ?  trim($datos[4]) : null,
                            'costo_promedio' =>  trim($datos[4]) != "" ?  trim($datos[4]) : null
                        );

                        $arreglo['precio_abierto'] = 1;

                        //control inventario
                        $arreglo['control_inven'] = 1;

                        //control inventario diario
                        $arreglo['control_inven_diario'] = 1;


                        /*if ($datos[4] != "" and str_replace(',', ".", $datos[4]) > 0.0) {
                            //$datos[12] es el iva
                            $iva = $this->impuestos_model->get_by('porcentaje_impuesto', str_replace(',', ".",  trim($datos[4])));

                            if (sizeof($iva) < 1) {
                                $impuesto = array(
                                    'nombre_impuesto' => "IVA " . str_replace(',', ".",  trim($datos[4])),
                                    'porcentaje_impuesto' => str_replace(',', ".",  trim($datos[4])),
                                    'estatus_impuesto' => 1
                                );
                                $id_iva = $this->impuestos_model->crear_impuesto($impuesto);
                            } else {
                                $id_iva = $iva['id_impuesto'];
                            }

                            $arreglo['producto_impuesto'] = $id_iva;
                        }*/

                        if ($datos[6] != "") {

                            $comp = $this->grupos_model->get_by('nombre_grupo', trim($datos[6]));

                            if (sizeof($comp) < 1) {
                                $componente = array(
                                    'nombre_grupo' =>  trim($datos[6]),
                                    'estatus_grupo' => 1
                                );
                                $id_compo = $this->grupos_model->insert($componente);
                            } else {
                                $id_compo = $comp['id_grupo'];
                            }
                            $arreglo['produto_grupo']=$id_compo;
                        }

                        if (count($buscarCodigo) > 0) {
                            $this->producto_model->solo_update(
                                array('producto_codigo_interno' =>  trim($datos[0])),
                                $arreglo
                            );
                            $producto_id = $buscarCodigo['producto_id'];

                        } else {


                            $producto_id = $this->producto_model->solo_insertar($arreglo);

                            $unidades = array();
                            $precios = array();

                            /********************************************************/
                            $unidades = array(
                                'id_unidad' => $caja['id_unidad'],
                                'producto_id' => $producto_id,
                                'unidades' => 1
                            );
                            $this->unidades_model->insert_unidades_producto($unidades);

                            /********************************************************/

                            $precios = array(
                                'id_condiciones_pago' => $contado['id_condiciones'],
                                'id_unidad' => $caja['id_unidad'],
                                'id_producto' => $producto_id,
                                'precio' =>  trim($datos[5])  // es el costo de la caja a contado
                            );
                            $this->unidades_has_precio_model->insert($precios);


                            //el costo de la caja a credito
                            $precios['id_condiciones_pago'] = $credito['id_condiciones'];
                            $precios['precio'] =  trim($datos[5]);

                            $this->unidades_has_precio_model->insert($precios);
                            $operacion = "SUMA";

                            //inventario de la caja
                            if ($datos[3] != "") {

                                $this->ingreso_model->setInventario($producto_id, $caja['id_unidad'],  trim($datos[3]),
                                    ENTRADA_MIGRACION,
                                    trim($datos[4]) != "" ?  trim($datos[4]) : 0, null, null, null,
                                    ENTRADA, $operacion, $stockviejo = array(), $stocknuevo = array(),
                                    null, $this->session->userdata('id_local'), $arreglo['control_inven'],
                                    NULL,
                                    trim($datos[4]) != "" ?  trim($datos[4]) : null);
                            }



                        }//final condicion si el producto existe

						$buscarcodigobarra = $this->producto_barra_model->get_codigo_barra(array('codigo_barra' =>
							trim($datos[2])));
						//si no encontro el coigo de barra
						if (count($buscarcodigobarra) < 1) {
							$this->producto_barra_model->insert(
								array('producto_id' => $producto_id,
									'codigo_barra' => trim($datos[2]))
							);
						}

                    }

                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $json['error'] = 'Ha ocurido un error al guardar los productos';
                } else {
                    $this->db->trans_commit();
                    $json['success'] = 'Los productos han sido agregados correctamente';
                }

            } else {

                $json['error'] = 'Debe seleccionar un archivo';

            }

        } catch
        (Exception $e) {
            $json['error'] = 'Ocurrio un error al importar: '.$e->getMessage();
            log_message("error", "Ocurrio un error al importar:  " . $e->getMessage());
        }

        echo json_encode($json);
    }



}
