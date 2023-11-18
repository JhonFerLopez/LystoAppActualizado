<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class FacturacionElectronica extends REST_Controller
{
	protected $uid = null;
	protected $FACT_E_API_TOKEN ='';

	function __construct()
	{
		parent::__construct();
		$this->load->model('api/api_model', 'api');
		$this->load->model('cajas/StatusCajaModel');
		$this->load->model('cajas/cajas_model');
		$this->load->model('usuario/usuario_model');
		$this->load->model('impuesto/impuestos_model');
		$this->load->model('metodosdepago/metodos_pago_model');
		$this->load->model('venta/venta_model');
		$this->load->model('tipo_venta/tipo_venta_model');
		$this->load->model('domicilios/domicilios_model');
		$this->load->model('tipo_anulacion/tipo_anulacion_model');
		$this->load->model('tipo_devolucion/tipo_devolucion_model');
		$this->load->model('opciones/opciones_model');
		$this->load->model('cliente/cliente_model');
		$this->load->model('unidades_has_precio/unidades_has_precio_model');
		$this->load->model('producto_componente/producto_componente_model');
		$this->load->model('clasificacion/clasificacion_model');
		$this->load->model('producto/producto_model', 'pd');
		$this->load->model('tipo_producto/tipo_producto_model');
		$this->load->model('ubicacion_fisica/ubicacion_fisica_model');
		//$this->very_auth();
	}

	function very_auth()
	{
		// Request Header
		$reqHeader = $this->input->request_headers();

		// Key
		$key = null;
		if (isset($reqHeader['X-api-key'])) {
			$key = $reqHeader['X-api-key'];
		} else if ($key_get = $this->get('x-api-key')) {
			$key = $key_get;
		} else if ($key_post = $this->post('x-api-key')) {
			$key = $key_post;
		} else {
			$key = null;
		}

		// Auth ID
		$auth_id = $this->api->getAuth($key);

		// ID ?
		if (!empty($auth_id)) {
			$this->uid = $auth_id;
		} else {
			$this->uid = null;
		}
	}

	function index_get()
	{
	}


	function index_post()
	{
	}

	// All
	public function documents_get()
	{

		$data = $this->input->get('data');
		$document_type = isset($data['document_type']) ? $data['document_type'] : $this->input->get('document_type');

		$array = array('data' => array(), 'recordsTotal' => 0, 'recordsTotal' => 0);
		if ($document_type == '1' || $document_type == '3' || $document_type == '4') {
			$array = $this->facturas();
		}
		if ($document_type == '5') {
			$array = $this->notasCredito();
		}
		if ($document_type == '6') {
			$array = $this->notasDebito();
		}


		$this->response($array, 200);
	}

	private function facturas()
	{
		$data = $this->input->get('data');

		$document_type = isset($data['document_type']) ? $data['document_type'] : $this->input->get('document_type');
		$fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
		$fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
		$status = isset($data['status']) ? $data['tipo_document'] : $this->input->get('status');
		$this->FACT_E_API_TOKEN =  isset($data['FACT_E_API_TOKEN']) ? $data['FACT_E_API_TOKEN'] : $this->input->get('FACT_E_API_TOKEN');

		$where = array('uuid IS NOT NULL' => NULL);
		$tipo_doc = '';
		switch ($document_type) {
			case '1':
				$tipo_doc = 'FACTURA ELECTRONICA DE VENTA NACIONAL';
				break;
			case '3':
				$tipo_doc = 'FACTURA ELECTRONICA DE CONTINGENCIA FACTURADOR';
				break;
			case '4':
				$tipo_doc = 'FACTURA ELECTRONICA DE CONTINGENCIA DIAN';
				break;
		}
		$where['fe_type_document'] = $document_type;
		if ($fechadesde != "") {
			$where['date(fe_issue_date) >= '] = date('Y-m-d', strtotime($fechadesde));
		}
		if ($fechahasta != "") {
			$where['date(fe_issue_date) <='] = date('Y-m-d', strtotime($fechahasta));
		}
		$where_custom = false;

		$nombre_or = false;
		$where_or = false;
		$nombre_in = false;
		$where_in = false;
		$group = false;
		$select = 'vb.*, cliente.nombres , cliente.apellidos, cliente.email ';

		$from = "venta vb";
		$join = array('cliente');
		$campos_join = array('cliente.id_cliente=vb.id_cliente');
		$tipo_join = array();

		$ordenar = $this->input->get('order');
		$order = false;
		$order_dir = 'desc';
		if (!empty($ordenar)) {
			$order_dir = $ordenar[0]['dir'];
			if ($ordenar[0]['column'] == 0) {
				$order = 'venta_id';
			}
			if ($ordenar[0]['column'] == 1) {
				$order = 'fe_type_document';
			}
		}

		$start = 0;
		$limit = false;
		$draw = $this->input->get('draw');
		if (!empty($draw)) {

			$start = $this->input->get('start');
			$limit = $this->input->get('length');
			if ($limit == '-1') {
				$limit = false;
			}
		}


		$ventas = $this->venta_model->traer_by_mejorado(
			$select,
			$from,
			$join,
			$campos_join,
			$tipo_join,
			$where,
			$nombre_in,
			$where_in,
			$nombre_or,
			$where_or,
			$group,
			$order,
			"RESULT",
			$limit,
			$start,
			$order_dir,
			false,
			$where_custom
		);

		$total_resultados = isset($ventas[0]) ? (isset($ventas[0]->total_afectados)) ? : 0 : 0;

		$new_array_datatable = $this->processResult($tipo_doc, $ventas);

		$array['data'] = $new_array_datatable;
		$array['draw'] = $draw; //esto debe venir por post
		$array['recordsTotal'] = $total_resultados;
		$array['recordsFiltered'] = $total_resultados; // esto dbe venir por post
		//print("<pre>".print_r($array,true)."</pre>");
		return $array;
	}

	private function processResult($tipo_doc, $ventas){
		$new_array_datatable = array();
		foreach ($ventas as $venta) {
			$estadoReception = 'EN ESPERA';
			$issueDate = isset($venta->fecha) ? $venta->fecha : $venta->fe_issue_date;
			$datetime1 = new DateTime($issueDate);
			$datetime2 = new DateTime(date('Y-m-d'));
			$interval = $datetime1->diff($datetime2);
			$days = $interval->format('%R%a');
			if (intval($days) > 2) {
				$estadoReception = 'APROBACIÓN TÁCITA';
			}
			if (!empty($venta->uuid) && $venta->uuid != 'null') {
				$logs = $this->logs($venta->uuid,  $this->FACT_E_API_TOKEN );
				if(isset($logs[0]['acknowledgment_received'])){
					if ($logs[0]['acknowledgment_received'] == '1') {
						$estadoReception = 'APROBADO POR EL CLIENTE';
					}
					if ($logs[0]['acknowledgment_received'] == '0') {
						$estadoReception = 'RECHAZADO POR EL CLIENTE';
					}
				}
			}
			if(isset($venta->fe_prefijo)){
				$numero = $venta->fe_prefijo . "-" . $venta->fe_numero;
			}else{
				$numero = $venta->prefijo . "-" . $venta->number;
			}
			$elememt = array(
				$numero,
				$tipo_doc,
				$venta->nombres." ".$venta->apellidos,
				$issueDate,
				isset($venta->fe_status)?$venta->fe_status:'ENVIADO',
				$estadoReception,
				'<button class="btn btn-info" type="button" onclick=\'sendMail("'.$venta->uuid.'", "'.$venta->email.'")\'><i class="fa fa-envelope"></i></button>'
			);
			array_push($new_array_datatable, $elememt);
		}
		return $new_array_datatable;
	}

	private function notasCredito()
	{
		$data = $this->input->get('data');
		$fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
		$fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
		$status = isset($data['status']) ? $data['tipo_document'] : $this->input->get('status');

		$where = array();


		if ($fechadesde != "") {
			$where['date(issued_date) >= '] = date('Y-m-d', strtotime($fechadesde));
		}
		if ($fechahasta != "") {
			$where['date(issued_date) <='] = date('Y-m-d', strtotime($fechahasta));
		}



		$where_custom = false;

		$nombre_or = false;
		$where_or = false;
		$nombre_in = false;
		$where_in = false;
		$group = false;
		$select = 'vb.*, cliente.nombres, cliente.apellidos, cliente.email  ';

		$from = "credit_note vb";
		$join = array('venta', 'cliente');
		$campos_join = array('venta.venta_id=vb.venta_id', 'cliente.id_cliente=venta.id_cliente');
		$tipo_join = array();

		$ordenar = $this->input->get('order');
		$order = false;
		$order_dir = 'desc';
		if (!empty($ordenar)) {
			$order_dir = $ordenar[0]['dir'];
			if ($ordenar[0]['column'] == 0) {
				$order = 'venta_id';
			}
		}

		$start = 0;
		$limit = false;
		$draw = $this->input->get('draw');
		if (!empty($draw)) {

			$start = $this->input->get('start');
			$limit = $this->input->get('length');
			if ($limit == '-1') {
				$limit = false;
			}
		}


		$ventas = $this->venta_model->traer_by_mejorado(
			$select,
			$from,
			$join,
			$campos_join,
			$tipo_join,
			$where,
			$nombre_in,
			$where_in,
			$nombre_or,
			$where_or,
			$group,
			false,
			"RESULT",
			$limit,
			$start,
			false,
			false,
			$where_custom
		);

		$total_resultados = isset($ventas[0]) ? $ventas[0]->total_afectados : 0;

		$new_array_datatable = 	 $this->processResult('NOTA CRÉDITO ELECTRONICA', $ventas);

		$array['data'] = $new_array_datatable;
		$array['draw'] = $draw; //esto debe venir por post
		$array['recordsTotal'] = $total_resultados;
		$array['recordsFiltered'] = $total_resultados; // esto dbe venir por post

		return $array;
	}

	private function notasDebito()
	{
		$data = $this->input->get('data');
		$fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : $this->input->get('fecha_desde');
		$fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : $this->input->get('fecha_hasta');
		$status = isset($data['status']) ? $data['tipo_document'] : $this->input->get('status');

		$where = array();


		if ($fechadesde != "") {
			$where['date(issued_date) >= '] = date('Y-m-d', strtotime($fechadesde));
		}
		if ($fechahasta != "") {
			$where['date(issued_date) <='] = date('Y-m-d', strtotime($fechahasta));
		}



		$where_custom = false;

		$nombre_or = false;
		$where_or = false;
		$nombre_in = false;
		$where_in = false;
		$group = false;
		$select = 'vb.*, cliente.nombres, cliente.apellidos, cliente.email  ';

		$from = "debit_note vb";
		$join = array('venta', 'cliente');
		$campos_join = array('venta.venta_id=vb.venta_id', 'cliente.id_cliente=venta.id_cliente');
		$tipo_join = array();

		$ordenar = $this->input->get('order');
		$order = false;
		$order_dir = 'desc';
		if (!empty($ordenar)) {
			$order_dir = $ordenar[0]['dir'];
			if ($ordenar[0]['column'] == 0) {
				$order = 'venta_id';
			}
		}

		$start = 0;
		$limit = false;
		$draw = $this->input->get('draw');
		if (!empty($draw)) {

			$start = $this->input->get('start');
			$limit = $this->input->get('length');
			if ($limit == '-1') {
				$limit = false;
			}
		}


		$ventas = $this->venta_model->traer_by(
			$select,
			$from,
			$join,
			$campos_join,
			$tipo_join,
			$where,
			$nombre_in,
			$where_in,
			$nombre_or,
			$where_or,
			$group,
			false,
			"RESULT",
			$limit,
			$start,
			false,
			false,
			$where_custom
		);

		$total_resultados = isset($ventas[0]) ? $ventas[0]->total_afectados : 0;

		$new_array_datatable = 	 $this->processResult('NOTA DÉBITO ELECTRONICA', $ventas);

		$array['data'] = $new_array_datatable;
		$array['draw'] = $draw; //esto debe venir por post
		$array['recordsTotal'] = $total_resultados;
		$array['recordsFiltered'] = $total_resultados; // esto dbe venir por post

		return $array;
	}

	public
	function logs($trackid = '', $FACT_E_API_TOKEN = '')
	{

		$jsonReturn = false;



		if ($trackid == '') {
			$trackid = $this->input->post('trackid'); //UUID
			$FACT_E_API_TOKEN = $this->input->post('FACT_E_API_TOKEN');
			$jsonReturn = true;
		}
		if (!empty($trackid)) {
			$url = API_ENDPOINT . "/api/ubl2.1/logs/" . $trackid;


			$request_headers = array();


			$request_headers[] = 'Content-Type: application/json';
			$request_headers[] = 'Accept: application/json';
			$request_headers[] = 'Authorization: Bearer ' . $FACT_E_API_TOKEN;
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data_result = curl_exec($ch);
			$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if (curl_errno($ch) or curl_error($ch)) {

				$result = array('error' => curl_error($ch));
			} else {

				$transaction = json_decode($data_result, TRUE);

				curl_close($ch);

				$result = $transaction;
			}
		} else {
			$result = array('error' => 'Debe registrrar el software');
		}

		if ($jsonReturn) {
			echo json_encode($result);
		} else {
			return $result;
		}
	}
}
