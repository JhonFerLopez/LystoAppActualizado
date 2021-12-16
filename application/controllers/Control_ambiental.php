<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class control_ambiental extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('control_ambiental/control_ambiental_model');
		$this->load->model('opciones/opciones_model');
		$this->very_sesion();
	}


	function index()
	{

		$data['controles'] = $this->control_ambiental_model->get_all();
		$dataCuerpo['cuerpo'] = $this->load->view('menu/control_ambiental/index', $data, true);
		if ($this->input->is_ajax_request()) {
			echo $dataCuerpo['cuerpo'];
		} else {
			$this->load->view('menu/template', $dataCuerpo);
		}
	}

	function form($id = FALSE)
	{
		$data = array();
		$data['control'] = array();
		if ($id != FALSE) {
			$data['control'] = $this->control_ambiental_model->get_only_control(array('control_ambiental_id' => $id));
		}
		$this->load->view('menu/control_ambiental/form', $data);
	}

	function guardar()
	{

		$id = $this->input->post('id');
		$mes = $this->input->post('mes');
		$fecha = "01-" . $mes;
		$fecha = date('Y-m-d', strtotime($fecha));
		$datos = array(
			'periodo' => $fecha,
		);
		if (empty($id)) {
			$datos['usuario_crea'] = $this->session->userdata('nUsuCodigo');
			$data['resultado'] = $this->control_ambiental_model->set_control($datos);
		} else {
			$datos['control_ambiental_id'] = $id;
			$data['resultado'] = $this->control_ambiental_model->update_control($datos);
		}

		if ($data['resultado'] !== FALSE && $data['resultado'] !== TRUE) {
			$json['error'] = $data['resultado'];
		} else if ($data['resultado'] == FALSE) {
			$json['error'] = "Ha ocurrio un error al guardar, por favor comunicarse con soporte";
		} else {
			$json['success'] = "Solicitud Procesada con éxito";
		}

		$this->saveSesionControlAmb();

		echo json_encode($json);
	}

	function eliminar()
	{

	}

	/*solo retorna los componentes en json*/
	function getComponentesJson()
	{
		$json = $this->componentes_model->get_all();
		echo json_encode($json);
	}

	function buscarcontrolambiental($id)
	{

		$data = array();
		$data['detalle'] = array();
		$data['control'] = array();
		if ($id != FALSE) {
			$data['detalle'] = $this->control_ambiental_model->getOnlyDetalle(
				array('control_ambiental_detalle.control_id' => $id)
			);

			$data['control'] = $this->control_ambiental_model->get_only_control(
				array('control_ambiental_id' => $id)
			);
		}
		$this->load->view('menu/control_ambiental/detalle', $data);

	}

	function guardardetalle()
	{
		$id = $this->input->post('control_id');
		if ($id != '') {
			$control = $this->control_ambiental_model->get_only_control(
				array('control_ambiental_id' => $id)
			);

			if (sizeof($control) > 0) {

				$guardar = $this->control_ambiental_model->guardardetalle($control, $this->input->post());


				if ($guardar == TRUE) {
					$json['success'] = "Solicitud Procesada con éxito";
				} else {
					$json['error'] = "Ha ocurrio un error al guardar, por favor comunicarse con soporte";
				}

			} else {
				$json['error'] = "El Control de Cambio no existe";
			}

		} else {
			$json['error'] = "Debe ingresar un Periodo de Control de Cambio";
		}
		$this->saveSesionControlAmb();
		echo json_encode($json);
	}

	function modalnotificacion()
	{

		$data = array();
		$data['horasnot'] = array();
		$data['horasnot'] = $this->control_ambiental_model->gethrsnot();

		$this->load->view('menu/control_ambiental/form_horasnot', $data);

	}

	function guardarhrsNotif()
	{
		$guardar = $this->control_ambiental_model->guardarhrsNotif($this->input->post());
		if ($guardar == TRUE) {
			$json['success'] = "Solicitud Procesada con éxito";
		} else {
			$json['error'] = "Ha ocurrio un error al guardar, por favor comunicarse con soporte";
		}
		$this->saveSesionControlAmb();
		echo json_encode($json);
	}

	//luego de cerrar el toast, guarda que fue cerrada y notificada
	function cerrarnotificacion()
	{

		$hrsnot = $this->control_ambiental_model->gethrsnot();
		$guardarNotif = false;
		if (count($hrsnot) > 0) {
			$amopmpost = substr($this->input->post('alias'), strlen($this->input->post('alias')) - 2,
				strlen($this->input->post('alias')));
			foreach ($hrsnot as $row) {
				$amopmhrsnot = substr($row->alias, strlen($row->alias) - 2,
					strlen($row->alias));

				if ($amopmpost == $amopmhrsnot) {
					$datos = array(
						'control_id' => $this->input->post('control_id'),
						'dia' => $this->input->post('dia'),
						'item' => $row->alias,
						'fecha' => date('Y-m-d H:i:s'),
						'usuario' => $this->session->userdata('nUsuCodigo'),
					);

					$guardarNotif = $this->control_ambiental_model->guardarNotif($datos);
				}

			}
		}


		if ($guardarNotif == TRUE) {
			$json['success'] = "Solicitud Procesada con éxito";
			$this->saveSesionControlAmb();
		} else {
			$json['error'] = "Ha ocurrio un error al guardar, por favor comunicarse con soporte";
		}
		echo json_encode($json);
	}

	function saveRecibeNotControlAmb()
	{
		$configuraciones[] = array(
			'config_key' => 'KEY_RECIBE_NOTIF_CONTROL_AMB',
			'config_value' => $this->input->post('token')
		);

		$KEY = $this->opciones_model->getByKey("KEY_RECIBE_NOTIF_CONTROL_AMB");


		if (!empty($KEY) && ($KEY['config_value'] === NULL || $KEY['config_value'] == '')) {

			$result = $this->opciones_model->guardar_configuracion($configuraciones);
			$configuraciones = $this->opciones_model->get_opciones();

			if ($configuraciones == TRUE) {
				foreach ($configuraciones as $configuracion) {
					$clave = $configuracion['config_key'];
					$data[$clave] = $configuracion['config_value'];
					//if ($configuracion['config_key'] == 'BODEGA_PRINCIPAL') {
					$this->session->set_userdata($clave, $configuracion['config_value']);
					//}
				}
			}
			$this->session->set_userdata($data);
			if ($result) {
				$json['success'] = 'Las configuraciones se han guardado exitosamente';
			} else {
				$json['error'] = 'Ha ocurido un error al guardar las configuraciones';
			}
		} else {
			$json['error'] = 'DEBE REINICIAR';
		}
		echo json_encode($json);
	}

	function restartRecibeNotControlAmb()
	{
		$KEY = $this->opciones_model->getByKey("KEY_RECIBE_NOTIF_CONTROL_AMB");
		$where = array(
			'config_key' => 'KEY_RECIBE_NOTIF_CONTROL_AMB'
		);
		$datos = array(
			'config_value' => null
		);
		$this->opciones_model->update($where, $datos);
		$json['success'] = true;
		$this->session->set_userdata('KEY_RECIBE_NOTIF_CONTROL_AMB', '');
		echo json_encode($json);
	}

	function showGrafica(){
		$control_id=$this->input->post('control_id');
		$data = array();
		$data['detalle'] = array();
		$data['control'] = array();
		if ($control_id != FALSE && $control_id != '' && $control_id != NULL) {
			$detalle = $this->control_ambiental_model->getOnlyDetalle(
				array('control_ambiental_detalle.control_id' => $control_id)
			);

			$data['control'] = $this->control_ambiental_model->get_only_control(
				array('control_ambiental_id' => $control_id)
			);

			$data['anio']=date('Y',strtotime($data['control']['periodo']));
			$data['mes']=date('m',strtotime($data['control']['periodo']));
			$mesactual = strftime("%B", strtotime($data['control']['periodo']));
			$mesespanol = $this->mesespanol($mesactual);
			$mestexto = $mesespanol[0];
			$controles=$this->control_ambiental_model->gethrsnot();

			$data['mestexto']=$mestexto;

			$data['dataretorno']=array();
			$numero = cal_days_in_month(CAL_GREGORIAN,
				date("m", strtotime($data['control']['periodo'])), date("Y", strtotime($data['control']['periodo'])));

			foreach ($controles as $control){
				$niveluno=array();
				$niveluno['name']=$control->nombre;
				$niveluno['data']=array();
				$datagrafica=array(0);
				//$niveluno['data'][0]=0;
				for ($i = 1; $i <= $numero; $i++) {

					foreach ($detalle as $deta){
						if($deta->dia==$i){
							$valor=0;
							$alias=$control->alias;
							if($deta->$alias!=null){
								$valor=$deta->$alias;
							}
							$datagrafica[$i]=$valor+0;
						}
					}
				}
				$niveluno['data']=$datagrafica;
				$data['dataretorno'][]=$niveluno;

			}


			$data['success']=true;
		}else{
			$data['error'] ='El control ambiental no existe';
		}

		echo json_encode($data,JSON_NUMERIC_CHECK);
	}

}
