<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mike42\Escpos\Printer;

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class Configuraciones extends REST_Controller
{

    protected $uid = null;

    function __construct()
    {
        parent::__construct();

        $this->load->model('api/api_model', 'api');
        $this->very_auth();
    }

    /**
     * Metodo que trae las configuraciones de app customers
     */
    function getConfigsAppCustomer_get()
    {

        try {
            $array = array();
            $configuraciones = ConfiguracionesElo::whereIn('config_key',
                array(
                    'APPCUS_MOTRAR_PRECIO_PRODUCTOS',
                    'APPCUS_TOTALIZAR_PEDIDO',
                    'APPCUS_TELEFONO_NEGOCIO',
                    'APPCUS_CELULAR_NEGOCIO',
                    'APPCUS_EMAIL_NEGOCIO',
                    'APPCUS_LOGOTIPO_NEGOCIO',
                    'EMPRESA_NOMBRE',
                    'APPCUS_MENSAJE',
                    'APPCUS_PEDIR_DIRECCION',
                    'APPCUS_CATEGORY_FILTER',
                    'APPCUS_MOSTRAR_PROD_AGOTADOS'
                )
            )->get();
            $array['configuraciones'] = $configuraciones; // esto dbe venir por post

            $this->response($array, 200);

        } catch (Exception $e) {

            $this->response([
                'isSuccess' => false,
                'message' => $e->getMessage(),
                'error' => 'Ha ocurrido un error, por favor comunicarse con soporte'
            ], 400);

        }

    }


    /**
     * metodo que retorna la categoria por la cual se filtrará los productos,
     * dependiendo del valor guardado en la tabla configuraciones->APPCUS_CATEGORY_FILTER
     */
    public function getCategoryByFilter_post()
    {


        try {

            $categorias = $this->input->post('APPCUS_CATEGORY_FILTER');

            //$varirable=json_decode('[ [ ["tipo2"],["TIPO"] ], "CLASIFICACION"]');
            //$varirable=json_decode('{"TIPO":{"FERNANDO":1},"CLASIFICACION":1}');

            if ($categorias == NULL) {
                $this->response([
                    'isSuccess' => true,
                    'message' => 'Debe ingresar una categoría válida',
                    'error' => true
                ], 200);
            }

            $categorias = json_decode($categorias);
            $filtros = array();


            foreach ($categorias as $row) {

                if ($row == 'CLASIFICACION') {
                    $filtros['CLASIFICACION'] =
                        ClasificacionElo::whereNull('deleted_at')->get();
                }

                if ($row == 'TIPO') {
                    $filtros['TIPO'] =
                        TipoProductoElo::whereNull('deleted_at')->get();
                }

                if ($row == 'COMPONENTE') {
                    $filtros['COMPONENTE'] =
                        ComponentesElo::where('deleted_at', null)->get();
                }

                if ($row == 'GRUPO') {
                    $filtros['GRUPO'] =
                        GrupoElo::where('estatus_grupo', 1)->get();
                }

                if ($row == 'UBICACION_FISICA') {
                    $filtros['UBICACION_FISICA'] =
                        UbicacionFisicaElo::where('deleted_at', null)->get();
                }

                if ($row == 'IMPUESTO') {
                    $filtros['IMPUESTO'] =
                        ImpuestoElo::where('estatus_impuesto', 1)->get();
                }
            }


            $this->response($filtros, 200);
        } catch (Exception $e) {
            $this->response([
                'isSuccess' => false,
                'message' => $e->getMessage(),
                'error' => true,
                'status' => 400,
            ], 400);
        }

    }

    function very_auth()
    {

        $reqHeader = $this->input->request_headers();
      
        //var_dump($reqHeader);
        $key = very_key($reqHeader, $this->get(), $this->post(), $this->options());
       
        $auth_id = $this->api->getAuth($key);

       
        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->response([
                'isSuccess' => false,
                'message' => 'No tiene permiso para obtener los datos',
                'error' => true,
                'status' => 400,
            ], 400);
        }
    }


}