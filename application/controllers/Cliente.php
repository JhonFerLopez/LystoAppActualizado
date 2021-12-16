<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cliente extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('cliente/cliente_model');
        $this->load->model('clientesgrupos/clientes_grupos_model');
        $this->load->model('pais/pais_model');
        $this->load->model('estado/estado_model');
        $this->load->model('ciudad/ciudad_model');
        $this->load->model('zona/zona_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('afiliado/afiliado_model');
        $this->load->model('impuesto/Impuestos_model');

        $this->very_sesion();
    }

    /** carga cuando listas los clientes*/
    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }

        $data['clientes'] = $this->cliente_model->get_all();
        $data['vendedores'] = $this->usuario_model->get_all_vendedores();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/cliente/cliente', $data, true);


        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        $fe_liabilities = [];
        $fe_regimes = [];
        $fe_municipalities = [];
        $types_document = [];
        $types_organization = [];
        $taxes = [];
        if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
            $fe_liabilities = $this->Impuestos_model->get_fe_typeliabilities();
            $fe_regimes = $this->Impuestos_model->get_fe_typeregime();
            $fe_municipalities = $this->Impuestos_model->get_fe_municipalities();
            $types_document = $this->Impuestos_model->get_fe_typedocumentidentifications();
            $types_organization = $this->Impuestos_model->get_fe_typeorganizations();
            $taxes = $this->Impuestos_model->get_fe_taxdetails();
        }
        $data['grupos'] = $this->clientes_grupos_model->get_all();
        $data['paises'] = $this->pais_model->get_all();
        $data['estados'] = $this->estado_model->get_all();
        $data['ciudades'] = $this->ciudad_model->get_all();
        $data['afiliados'] = $this->afiliado_model->get_all();
        $data['liabilities'] =$fe_liabilities;
        $data['municipalities'] =$fe_municipalities;
        $data['types_document'] =$types_document;
        $data['types_organization'] =$types_organization;
        $data['taxes'] =$taxes;
        $data['regimes'] =$fe_regimes;
        if ($id != FALSE) {
            $data['cliente'] = $this->cliente_model->get_by('id_cliente', $id);
        }
        $data['vendedores'] = $this->usuario_model->get_all_vendedores();
        $data['zonas'] = $this->zona_model->get_all();
        $this->load->view('menu/cliente/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');

        $vendedor_id = $this->input->post('vendedor');
        $zona = $this->input->post('zona');
        $valida_fact_maximo = $this->input->post('valida_fact_maximo');
        $valida_venta_credito = $this->input->post('valida_venta_credito');
        $permitir_deuda_vencida = $this->input->post('permitir_deuda_vencida');
        $fe_type_liability = $this->input->post('fe_type_liability');
        $afiliado = $this->input->post('afiliado');
        $cliente = array(
            'ciudad_id' => $this->input->post('ciudad_id'),
            'codigo_interno' => $this->input->post('codigo_interno'),
            'grupo_id' => $this->input->post('grupo_id'),
            'sexo' => $this->input->post('sexo'),
            'direccion' => $this->input->post('direccion'),
            'email' => $this->input->post('email'),
            'nombres' => strtoupper($this->input->post('nombres')),
            'apellidos' => strtoupper($this->input->post('apellidos')),
            'identificacion' => $this->input->post('identificacion'),
            'afiliado' => empty($afiliado) ? null : $afiliado,
            'telefono' => $this->input->post('telefono'),
            'celular' => $this->input->post('celular'),
            'fecha_nacimiento' => date('Y-m-d', strtotime($this->input->post('fecha_nacimiento'))),
            'facturacion_maximo' => $this->input->post('facturacion_maximo'),
            'valida_fact_maximo' => !empty($valida_fact_maximo) ? 1 : 0,
            'valida_venta_credito' => !empty($valida_venta_credito) ? 1 : 0,
            'permitir_deuda_vencida' => !empty($permitir_deuda_vencida) ? 1 : 0,
            'dias_credito' => $this->input->post('dias_credito'),
            'latitud' => $this->input->post('latitud'),
            'longitud' => $this->input->post('longitud'),
            'merchant_registration' => $this->input->post('merchant_registration'),
            'fe_type_liability' => $this->input->post('fe_type_liability'),
            'type_document_identification_id' => $this->input->post('type_document_identification_id'),
            'fe_regime' => $this->input->post('fe_regime'),
            'fe_municipality' => $this->input->post('fe_municipality'),
            'type_organization_id' => $this->input->post('type_organization_id'),
            'tax_detail_id' => $this->input->post('tax_detail_id'),
            'id_zona' => !empty($zona) ? $zona : null,
            'cliente_status' => 1,

        );

        if (empty($id)) {
            $cliente['create_at'] = date('Y-m-d H:i:s');
            $resultado = $this->cliente_model->insertar($cliente);
            $id = $resultado;
        } else {
            $cliente['id_cliente'] = $id;
            $resultado = $this->cliente_model->update($cliente);
        }

        if ($resultado === CEDULA_EXISTE || $resultado === CODIGO_EXISTE) {

            $json['error'] = $resultado;
        } else {
            if ($resultado == TRUE) {

                $json['success'] = 'Solicitud Procesada con exito';
                $json['id'] = $id;
            } else {
                $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
            }
        }
        echo json_encode($json);
    }


    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $cliente = array(
            'id_cliente' => $id,
            'nombres' => $nombre . time(),
            'cliente_status' => 0

        );

        $data['resultado'] = $this->cliente_model->softeDelete($cliente);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';
        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el Cliente';
        }

        echo json_encode($json);
    }
}
