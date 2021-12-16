<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class opciones extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->model('opciones/opciones_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('pais/pais_model');
        $this->load->model('regimen/regimen_model');
        $this->load->model('local/local_model');
        $this->load->helper('download');
        $this->load->helper('file');
        $this->very_sesion();
    }


    function index()
    {
        $data = array();

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }
        $data['configuraciones'] = $this->opciones_model->get_opciones();
        $data['correlativo'] = $this->opciones_model->getByKey("CORRELATIVO_PRODUCTO");
        $data['paises'] = $this->pais_model->get_all();
        $data['regimenes'] = $this->regimen_model->get_all();
        $data['bodegas'] = $this->local_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/opciones/opciones', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }


    function save()
    {

        ini_set('memory_limit', '1024M');
        $this->load->library('upload');
        $data = array();
        $configuraciones[] = array(
            'config_key' => EMPRESA_NOMBRE,
            'config_value' => $this->input->post('EMPRESA_NOMBRE')
        );

        $configuraciones[] = array(
            'config_key' => EMPRESA_DIRECCION,
            'config_value' => $this->input->post('EMPRESA_DIRECCION')
        );

        $configuraciones[] = array(
            'config_key' => EMPRESA_TELEFONO,
            'config_value' => $this->input->post('EMPRESA_TELEFONO')
        );


        $configuraciones[] = array(
            'config_key' => 'IMPRESORA',
            'config_value' => $this->input->post('IMPRESORA')
        );
        $configuraciones[] = array(
            'config_key' => 'USUARIO_IMPRESORA',
            'config_value' => $this->input->post('USUARIO_IMPRESORA')
        );
        $configuraciones[] = array(
            'config_key' => 'PASSWORD_IMPRESORA',
            'config_value' => $this->input->post('PASSWORD_IMPRESORA')
        );
        $configuraciones[] = array(
            'config_key' => 'WORKGROUP_IMPRESORA',
            'config_value' => $this->input->post('WORKGROUP_IMPRESORA')
        );
        $configuraciones[] = array(
            'config_key' => 'ABRIR_CAJA_REGISTRADORA',
            'config_value' => $this->input->post('ABRIR_CAJA_REGISTRADORA')
        );

        $configuraciones[] = array(
            'config_key' => MONEDA_OPCION,
            'config_value' => $this->input->post('MONEDA')
        );
        $configuraciones[] = array(
            'config_key' => REFRESCAR_PEDIDOS_OPCION,
            'config_value' => $this->input->post('REFRESCAR_PEDIDOS')
        );
        $configuraciones[] = array(
            'config_key' => 'TIPO_EMPRESA',
            'config_value' => $this->input->post('TIPO_EMPRESA')
        );

        $configuraciones[] = array(
            'config_key' => EMPRESA_PAIS,
            'config_value' => $this->input->post('EMPRESA_PAIS')
        );

        $configuraciones[] = array(
            'config_key' => MENSAJE_FACTURA,
            'config_value' => $this->input->post('MENSAJE_FACTURA')
        );
        /*esta opcion es para calcular el precio en unidades y precios*/
        $configuraciones[] = array(
            'config_key' => CALCULO_PRECIO_VENTA,
            'config_value' => $this->input->post('CALCULO_PRECIO_VENTA')
        );

        $configuraciones[] = array(
            'config_key' => 'VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS',
            'config_value' => $this->input->post('VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS')
        );
        $configuraciones[] = array(
            'config_key' => REGIMEN_CONTRIBUTIVO,
            'config_value' => $this->input->post('REGIMEN_CONTRIBUTIVO')
        );

        $configuraciones[] = array(
            'config_key' => REPRESENTANTE_LEGAL,
            'config_value' => $this->input->post('REPRESENTANTE_LEGAL')
        );

        $configuraciones[] = array(
            'config_key' => NIT,
            'config_value' => $this->input->post('NIT')
        );

        $configuraciones[] = array(
            'config_key' => CODIGO_COOPIDROGAS,
            'config_value' => $this->input->post('CODIGO_COOPIDROGAS')
        );
        $configuraciones[] = array(
            'config_key' => 'CLAVE_MAESTRA',
            'config_value' => $this->input->post('CLAVE_MAESTRA')
        );

        $configuraciones[] = array(
            'config_key' => MOSTRAR_SIN_STOCK,
            'config_value' => $this->input->post('MOSTRAR_SIN_STOCK')
        );
        $configuraciones[] = array(
            'config_key' => 'INVENTARIO_UBICACION_REQUERIDO',
            'config_value' => $this->input->post('INVENTARIO_UBICACION_REQUERIDO')
        );

        $configuraciones[] = array(
            'config_key' => 'MOSTRAR_PROSODE',
            'config_value' => $this->input->post('MOSTRAR_PROSODE')
        );
        $configuraciones[] = array(
            'config_key' => 'CLAVE_MAESTRA_ANULAR_CARTERA',
            'config_value' => $this->input->post('CLAVE_MAESTRA_ANULAR_CARTERA')
        );

        $configuraciones[] = array(
            'config_key' => 'BODEGA_PRINCIPAL',
            'config_value' => $this->input->post('BODEGA_PRINCIPAL')
        );

        /*para guardar el correlativo del codigo del producto interno*/
        $configuraciones[] = array(
            'config_key' => 'CALCULO_UTILIDAD',
            'config_value' => $this->input->post('CALCULO_UTILIDAD') == "NO" ? "NO" : $this->input->post('CALCULO_UTILIDAD')
        );


        $configuraciones[] = array(
            'config_key' => 'PANTALLA_COMPLETA',
            'config_value' => $this->input->post('PANTALLA_COMPLETA')
        );

        $configuraciones[] = array(
            'config_key' => 'TIPO_IMPRESION',
            'config_value' => $this->input->post('TIPO_IMPRESION')
        );

        $configuraciones[] = array(
            'config_key' => 'SISTEMA_OPERATIVO',
            'config_value' => $this->input->post('SISTEMA_OPERATIVO')
        );

        $configuraciones[] = array(
            'config_key' => 'VENDEDOR_EN_FACTURA',
            'config_value' => $this->input->post('VENDEDOR_EN_FACTURA')
        );

        $configuraciones[] = array(
            'config_key' => 'PEDIR_VALOR_CIERRE_CAJA',
            'config_value' => $this->input->post('PEDIR_VALOR_CIERRE_CAJA')
        );
        $configuraciones[] = array(
            'config_key' => 'MODIFICAR_FECHA_GASTOS',
            'config_value' => $this->input->post('MODIFICAR_FECHA_GASTOS')
        );

        if ($this->input->post('CORRELATIVO_PRODUCTO') == "SI") {

            $configuraciones[] = array(
                'config_key' => 'CORRELATIVO_PRODUCTO',
                'config_value' => $this->input->post('CORRELATIVO_NUMERO')
            );

        } else {
            $configuraciones[] = array(
                'config_key' => 'CORRELATIVO_PRODUCTO',
                'config_value' => $this->input->post('CORRELATIVO_PRODUCTO')
            );

        }

        /**********************************************
         ***********    PESTAÑA APP CUSTOMER    ***********
         **********************************************/
        $configuraciones[] = array(
            'config_key' => 'APPCUS_MOTRAR_PRECIO_PRODUCTOS',
            'config_value' => $this->input->post('APPCUS_MOTRAR_PRECIO_PRODUCTOS')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_TOTALIZAR_PEDIDO',
            'config_value' => $this->input->post('APPCUS_TOTALIZAR_PEDIDO')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_MOSTRAR_PROD_AGOTADOS',
            'config_value' => $this->input->post('APPCUS_MOSTRAR_PROD_AGOTADOS')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_TELEFONO_NEGOCIO',
            'config_value' => $this->input->post('APPCUS_TELEFONO_NEGOCIO')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_CELULAR_NEGOCIO',
            'config_value' => $this->input->post('APPCUS_CELULAR_NEGOCIO')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_EMAIL_NEGOCIO',
            'config_value' => $this->input->post('APPCUS_EMAIL_NEGOCIO')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_MENSAJE',
            'config_value' => $this->input->post('APPCUS_MENSAJE')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_PEDIR_DIRECCION',
            'config_value' => $this->input->post('APPCUS_PEDIR_DIRECCION')
        );

        $configuraciones[] = array(
            'config_key' => 'APPCUS_CATEGORY_FILTER',
            'config_value' => '["' . $this->input->post('APPCUS_CATEGORY_FILTER') . '"]'
        );


        if (!empty($_FILES) and $_FILES['APPCUS_LOGOTIPO_NEGOCIO']['size'] != '0') {
            $files = $_FILES;
            if ($files['APPCUS_LOGOTIPO_NEGOCIO']['name'] != "") {
                $files['APPCUS_LOGOTIPO_NEGOCIO']['name'] = str_replace(' ', '_', $files['APPCUS_LOGOTIPO_NEGOCIO']['name']);
                if ($this->session->userdata('APPCUS_LOGOTIPO_NEGOCIO') &&
                    is_file("./" . $this->session->userdata('APPCUS_LOGOTIPO_NEGOCIO'))) {
                    unlink("./" . $this->session->userdata('APPCUS_LOGOTIPO_NEGOCIO'));
                }

                $config_upload = $this->upload->initialize($this->set_upload_options($files['APPCUS_LOGOTIPO_NEGOCIO']['name']));
                if ($this->upload->do_upload('APPCUS_LOGOTIPO_NEGOCIO')) {
                    $configuraciones[] = array(
                        'config_key' => 'APPCUS_LOGOTIPO_NEGOCIO',
                        'config_value' => "uploads/logotipo_negocio/" . $files['APPCUS_LOGOTIPO_NEGOCIO']['name']
                    );
                }
            }
        }
        /**********************************************
         ***********  FIN DE PESTAÑA APP CUSTOMER    ***********
         **********************************************/


        if ($this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN') {

            /**********************************************
             ***********    PESTAÑA FIREBASE    ***********
             **********************************************/

            $configuraciones[] = array(
                'config_key' => 'FRB_PROJECT_SERVERKEY',
                'config_value' => $this->input->post('FRB_PROJECT_SERVERKEY')
            );

            $configuraciones[] = array(
                'config_key' => 'FRB_APPWEB_APIKEY',
                'config_value' => $this->input->post('FRB_APPWEB_APIKEY')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_AUTHDOMAIN',
                'config_value' => $this->input->post('FRB_AUTHDOMAIN')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_DATABASEURL',
                'config_value' => $this->input->post('FRB_DATABASEURL')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_APPWEB_PROJECTID',
                'config_value' => $this->input->post('FRB_APPWEB_PROJECTID')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_APPWEB_STORAGEBUCKET',
                'config_value' => $this->input->post('FRB_APPWEB_STORAGEBUCKET')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_APPWEB_MESSAGINGSENDERID',
                'config_value' => $this->input->post('FRB_APPWEB_MESSAGINGSENDERID')
            );
            $configuraciones[] = array(
                'config_key' => 'FRB_APPWEB_APPID',
                'config_value' => $this->input->post('FRB_APPWEB_APPID')
            );



            /**
             * CANTIDAD DE NOFITICACIONES QUE SE PUEDEN ENVIAR POR MES
             */
            $configuraciones[] = array(
                'config_key' => 'FRB_APPANDROID_LIMIT_CONT_NOTIF',
                'config_value' => $this->input->post('FRB_APPANDROID_LIMIT_CONT_NOTIF')
            );


            /**********************************************
             ***********  FIN DE PESTAÑA FIREBASE    ***********
             **********************************************/
        }


        /*esto es para actualizar el catalogo de coopidrogas*/
        if (!empty($_FILES) and $_FILES['CATALOGO']['size'] != '0') {

            $filas = file($_FILES['CATALOGO']['tmp_name']);

            /*los campos de la tabla*/
            $filas_columnas = array(
                'producto_codigo_interno',
                'producto_codigo_barra',
                'producto_nombre',
                'presentacion',
                'costo_corriente',
                'costo_real',
                'iva',
                'nombre_laboratorio',
                'codigo_laboratorio',
                'bonificacion'
            );

            $array_insert = array();
            foreach ($filas as $v) {
                $con = 0;
                $arreglo = array();
                $datos = explode(",", $v);
                foreach ($datos as $dato) {

                    $arreglo[$filas_columnas[$con]] = $dato;
                    $con++;
                }

                $array_insert[] = $arreglo;
            }

            /*elimino todos los datos en la tabla para actualizar*/
            if ($this->opciones_model->delete_all_catalogo()) {
                $this->opciones_model->insert_bach_catalogo($array_insert);
            }

        }

        $password = $this->input->post('DATABASE_PASWORD');

        if (!empty($password)) {
            $configuraciones[] = array(
                'config_key' => DATABASE_PASWORD,
                'config_value' => $password
            );
        }

        $result = $this->opciones_model->guardar_configuracion($configuraciones);

        $this->opciones_model->loadConfigsInSession();

        if ($result) {
            $json['success'] = 'Las configuraciones se han guardado exitosamente';
        } else {
            $json['error'] = 'Ha ocurido un error al guardar las configuraciones';
        }


        echo json_encode($json);


    }

    function downloadbackup($forzardescarga = true)
    {
        //$forzardescarga para decirle que lo descargue o no
        //ya que desde restablecer BD

        $ruta = RUTA_BACKUP;
        $prefs = array(
            'tables' => array(),  // Array of tables to backup.
            'ignore' => array(),           // List of tables to omit from the backup
            'format' => 'zip',             // gzip, zip, txt
            'add_drop' => TRUE,              // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE,              // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );

        $backup = $this->dbutil->backup($prefs);
        $fecha = date('Y-m-d_H_i_s');
        $this->load->helper('file');
        write_file($ruta . 'sid_' . $fecha . '.zip', $backup);
        if ($forzardescarga == true) {
            force_download('sid_' . $fecha . '.zip', $backup);
            unlink($ruta . 'sid_' . $fecha . '.zip');
        }

    }

    function generatebackup()
    {
        $this->load->helper('file');
        $json = array();
        // $ruta = "C:\\xampp\\htdocs\\sid";

        try {

            $ruta = '.' . RUTA_BACKUP;

            if (!is_dir('./uploads/')) {
                mkdir('./uploads/', 0755);
            }
            if (!is_dir($ruta)) {
                mkdir($ruta, 0755);

                $htaccess = '<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>';

                $fichero = $ruta . '.htaccess';
                file_put_contents($fichero, $htaccess, FILE_APPEND | LOCK_EX);
            }

            $prefs = array(
                'tables' => array(),  // Array of tables to backup.
                'ignore' => array(),           // List of tables to omit from the backup
                'format' => 'zip',             // gzip, zip, txt
                'add_drop' => FALSE,              // Whether to add DROP TABLE statements to backup file
                'add_insert' => TRUE,              // Whether to add INSERT data to backup file
                'newline' => "\n",               // Newline character used in backup file

            );
            $backup = $this->dbutil->backup($prefs);
            $fecha = date('Y-m-d_H_i_s');
            $this->load->helper('file');
            write_file($ruta . 'sid_' . $fecha . '.zip', $backup);

            $nombre_backup = 'sid_' . $fecha . '.zip';
            if ($ruta != "") {
                if (file_exists($ruta . 'sid_' . $fecha . '.zip')) {
                    $json['success'] = "El backup ha sido generado con éxito en la ruta especificada en configuraciones";
                    $json['file'] = $nombre_backup;
                    // $this->obtenerCodigo();
                } else {
                    $json['error'] = "ha ocurrido un error al descargar el backup";
                }
            } else {
                $json['error'] = "Debe configurar una ruta en configuraciones generales, para poder generar el backup";
            }

        } catch (Exception $e) {

            $json['error'] = $e->getMessage();
        }

        echo json_encode($json);
    }


    function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

    function printParents($service, $fileId)
    {
        try {
            $parents = $service->parents->listParents($fileId);

            foreach ($parents->getItems() as $parent) {
                print 'File Id: ' . $parent->getId();
            }
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }

    function restablecerBD()
    {

        $this->downloadbackup(false);
        $restablecer = $this->opciones_model->restablecerBD();

        echo json_encode($restablecer);

    }

    private function holandamascotas()
    {

        $this->load->model('producto/producto_model');
        $this->load->model('impuesto/impuestos_model');
        $this->load->model('proveedor/proveedor_model');
        $this->load->model('grupos/grupos_model');
        $this->load->model('unidades/unidades_model');
        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        $filas = file($_FILES['HOLANDAMASCOTAS']['tmp_name']);

        unset($filas[0]);
        $this->db->trans_begin();
        foreach ($filas as $v) {
            $dato = explode(";", $v);


            if (trim($dato[0]) != "" && trim($dato[1]) != "") {

                //busco si existe el producto
                $where = array(
                    'producto_codigo_interno' => trim($dato[0])
                );
                $producto = $this->producto_model->getAnyCondition($where);

                if (sizeof($producto) < 1) {

                    $iva_id = null;
                    if (trim($dato[8]) != "" && trim($dato[8]) > 0) {
                        $iva = trim($dato[8]);
                        $buscar = $this->impuestos_model->get_by('porcentaje_impuesto', $iva);
                        if (sizeof($buscar) < 1) {
                            $datos = array(
                                'nombre_impuesto' => "Impuesto " . trim($dato[8]) . " %",
                                'estatus_impuesto' => 1,
                                'porcentaje_impuesto' => trim($dato[8]),
                                'tipo_calculo' => 'PORCENTAJE'
                            );
                            $iva_id = $this->impuestos_model->crear_impuesto($datos);

                            if ($iva_id == false) {
                                $iva_id = null;
                            }
                        } else {
                            $iva_id = $buscar['id_impuesto'];
                        }
                    }

                    $proveedor_id = null;
                    $ubicacion_fisica = null;
                    if (trim($dato[5]) != "") {
                        $where = array(
                            'proveedor_nombre' => trim($dato[5])
                        );
                        $buscar = $this->proveedor_model->getOnlyProveedor($where);

                        if (sizeof($buscar) < 1) {
                            $datos = array(
                                'proveedor_nombre' => trim($dato[5]),
                                'proveedor_identificacion' => trim($dato[4]),
                                'proveedor_celular' => 0,
                                'proveedor_direccion' => ""
                            );
                            $proveedor_id = $this->proveedor_model->insertar($datos);

                            if ($proveedor_id == FALSE) {
                                $proveedor_id = null;
                            }
                        } else {
                            $proveedor_id = $buscar->id_proveedor;
                        }

                        $ub = $this->ubicacion_fisica_model->get_by(array('ubicacion_nombre' => trim($dato[5])));

                        if (sizeof($ub) < 1) {
                            $ubicacion = array(
                                'ubicacion_nombre' => trim($dato[5]),
                                'deleted_at' => null
                            );
                            $ubicacion_fisica = $this->ubicacion_fisica_model->insert($ubicacion);
                        } else {
                            $ubicacion_fisica = $ub['ubicacion_id'];
                        }


                    }

                    $grupo_id = null;
                    if (trim($dato[7]) != "") {

                        $buscar = $this->grupos_model->get_by('nombre_grupo', trim($dato[7]));

                        if (sizeof($buscar) < 1) {
                            $datos = array(
                                'nombre_grupo' => trim($dato[7]),
                                'estatus_grupo' => 1,
                                'codigo' => trim($dato[6])
                            );
                            $grupo_id = $this->grupos_model->set_grupos($datos);

                            if ($grupo_id == FALSE) {
                                $grupo_id = null;
                            }
                        } else {
                            $grupo_id = $buscar['id_grupo'];
                        }
                    }

                    $comision = null;
                    if (trim($dato[12]) == "1") {
                        $comision = 10.00;
                    } elseif (trim($dato[12]) == "0,5") {
                        $comision = 5.00;
                    } elseif (trim($dato[12]) == "2,5") {
                        $comision = 2.5;
                    }


                    $costounitario = str_replace(',', '.', $dato[9]);
                    $producto = array(
                        'producto_codigo_interno' => trim($dato[0]),
                        'producto_nombre' => trim(utf8_encode($dato[1])),
                        'costo_unitario' => $costounitario,
                        'producto_impuesto' => $iva_id,
                        'producto_proveedor' => $proveedor_id,
                        'produto_grupo' => $grupo_id,
                        'producto_comision' => $comision,
                        'producto_presentacion' => trim($dato[2]),
                        'producto_estatus' => 1,
                        'producto_activo' => 1,
                        'producto_ubicacion_fisica' => $ubicacion_fisica,
                        'control_inven' => 1,
                    );

                    $producto_id = $this->producto_model->solo_insertar($producto);

                    if ($producto_id != false) {

                        $guardar = array(
                            'id_unidad' => 1,
                            'producto_id' => $producto_id,
                            'unidades' => 1,
                            'costo' => null
                        );
                        $this->unidades_model->insert_unidades_producto($guardar);

                        $precio = str_replace(',', '.', $dato[11]);
                        $guardar = array(
                            'id_condiciones_pago' => 1,
                            'id_producto' => $producto_id,
                            'id_unidad' => 1,
                            'precio' => $precio,
                        );
                        $this->unidades_has_precio_model->insert($guardar);

                        $this->ingreso_model->setInventario($producto_id, 1, $dato[13],
                            ENTRADA_MIGRACION,
                            $costounitario, null, null,
                            null,
                            ENTRADA, "SUMA", $stockviejo = array(), $stocknuevo = array(),
                            null, $this->session->userdata('id_local'), 1,
                            str_replace(',', ".", $dato[8]) * 100,
                            $costounitario);

                        $producto_before = array();
                        $where = array('producto_id' => $producto_id);
                        $producto['contenido_interno'] = $this->unidades_model->solo_unidades_xprod($where);

                        $where = array('id_producto' => $producto_id);
                        $producto['precios'] = $this->unidades_has_precio_model->get_all_where($where);

                        $producto_after= ProductoElo::where('producto_id',$producto_id)->with(ProductoElo::allTablesRelations())->first();
                        $log = array(
                            'usuario' => $this->session->userdata('nUsuCodigo'),
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'fecha' => date('Y-m-d H:i:s'),
                            'tabla' => 'PRODUCTO',
                            'tipo' => empty($this->input->post('id')) ? LOG_INSERT : LOG_UPDATE,
                            'data_before' => null,
                            'data_after' => json_encode($producto_after),
                        );
                        $this->systemLogsModel->insert($log);

                    }
                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Ha ocurido un error al guardar los Departamentos, revise por favor que
             los datos sean ingresados en el orden correspondiente');

        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Los Departamentos han sido agregados correctamente');
        }


    }


    /*pasa los precios de contado a credito, para todos los productos en todas sus unidades*/
    function precioscontadoacredito()
    {

        $this->load->model('unidades_has_precio/unidades_has_precio_model');
        $this->db->trans_start();
        $where = array(
            'id_condiciones_pago' => 1
        );
        $precios = $this->unidades_has_precio_model->get_all_where($where);

        foreach ($precios as $row) {

            $where = array(
                'id_condiciones_pago' => 2, //credito
                'id_unidad' => $row['id_unidad'],
                'id_producto' => $row['id_producto'],
            );
            $tieneacredito = $this->unidades_has_precio_model->get_all_where($where);

            if (count($tieneacredito) > 0) {
                $guardar = array(
                    'precio' => $row['precio'],
                );
                $this->unidades_has_precio_model->solo_update($where, $guardar);
            } else {
                $guardar = array(
                    'id_condiciones_pago' => 2,
                    'id_producto' => $row['id_producto'],
                    'id_unidad' => $row['id_unidad'],
                    'precio' => $row['precio'],
                );
                $this->unidades_has_precio_model->insert($guardar);
            }

        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
            $json['error'] = 'Ha ocurido un error al guardar las configuraciones';
        else
            $json['success'] = 'Las configuraciones se han guardado exitosamente';;


        echo json_encode($json);
    }


    function set_upload_options($namefile)
    {
        // upload an image options
        $this->load->helper('path');
        $dir = './uploads/logotipo_negocio/';

        if (!is_dir('./uploads/')) {
            mkdir('./uploads/', 0755);
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }
        $config = array();
        $config['upload_path'] = $dir;
        //$config ['file_path'] = './prueba/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '1000';
        $config['overwrite'] = TRUE;
        $config['file_name'] = $namefile;

        return $config;
    }

}
