<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class principal extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('venta/venta_model');
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('cliente/cliente_model');
        $this->load->model('cajas/cajas_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('unidades/unidades_model');
        $this->load->model('drogueria_relacionada/drogueria_relacionada_model');
        $this->load->library('session');

        $this->load->model('login/login_model');
        $this->load->library('session');
        $this->very_sesion();

    }


    function index()
    {
		$data=array();
        //esto es para ver si estoy llamando a la vista del mapa de los domiciliarios
        $dataCuerpo['callmapadomicilio']="false";
        $version =$this->opciones_model->getDatabaseVersion();

        $data['system_version'] = $version['version'];
		$this->session->set_userdata('system_version', $data['system_version'] );
        $mostrar = $this->opciones_model->getByKey("SHOWMAPADOMICILIARIO");
        if (isset($mostrar['config_value']) && $mostrar['config_value']=="SI") {

            $configuraciones = array(
                'config_key' => "SHOWMAPADOMICILIARIO",
                'config_value' => "NO"
            );
            $where = array(
                'config_key' => "SHOWMAPADOMICILIARIO",
            );

            $this->opciones_model->update($where, $configuraciones);
            $dataCuerpo['callmapadomicilio']="true";
        }

        $configuraciones = $this->opciones_model->get_opciones();
        if ($configuraciones == TRUE) {
            foreach ($configuraciones as $configuracion) {

                $clave = $configuracion['config_key'];
                if($clave=='TIPO_EMPRESA') {

                    $data[$clave] = $configuracion['config_value'];
                }

            }
        }


        $tipo_empresa = isset($data['TIPO_EMPRESA'])?$data['TIPO_EMPRESA']:'';

        switch ($tipo_empresa){
            case 'OTRO':
                $imgbanner=base_url().'recursos/img/banner-otros.png';
                $imglogo=base_url().'recursos/img/sidposlogin.png';
                break;
            default:
                $imgbanner=base_url().'recursos/plugins/images/login-register.jpg';
                $imglogo=base_url().'recursos/img/sid-login-01.png';
                break;
        }

        $data['imgbanner']=$imgbanner;
        $data['ventashoy'] = count($this->venta_model->get_ventas_by(array('DATE(fecha)' => date('Y-m-d'))));
        $data['ventastotalhoy'] = $this->venta_model->get_total_ventas_by_date(date('Y-m-d'));
        $data['comprashoy'] = count($this->ingreso_model->get_ingresos_by(array('DATE(fecha_registro)' => date('Y-m-d'))));

        $data["droguerias"] = $this->drogueria_relacionada_model->get_all();
        $data["unidades_medida"] = $this->unidades_model->get_unidades();

        $data["licencia"] = $this->checkSysExpDat();


		$ultimosclientes=$this->cliente_model->getCountLastClients();
		$data['ultimosclientes'] = $ultimosclientes['cont_clientes'];

		$nro_ventashoy = $this->venta_model->count_ventas('fecha>=DATE(NOW())');
		$data['nro_ventashoy'] = $nro_ventashoy['count'];

		$nro_ventasabiertas = $this->venta_model->count_ventas(array('venta_status'=>ESPERA));
		$data['nro_ventasabiertas'] = $nro_ventasabiertas['count'];




        $dataCuerpo['cuerpo'] = $this->load->view('menu/principal', $data, true);

        $cajas_abiertas = $this->StatusCajaModel->getAlBy(
            array(
                'apertura IS NOT null' => NULL,
                'cierre IS null ' => NULL)
        );
       // echo count($cajas_abiertas);
        $cajaaertudada=null;
        if (count($cajas_abiertas) > 0) {
            foreach ($cajas_abiertas as $caja) {
                if ($caja['cajero'] == $this->session->userdata('nUsuCodigo')) {
                    $cajaaertudada=$caja;
                    $this->session->set_userdata('cajapertura', $caja['id']);
                    $this->session->set_userdata('caja_id', $caja['caja_id']);
                    $this->session->set_userdata('cajero_id', $this->session->userdata('nUsuCodigo'));
                };
            }
        }

        $sessioncajaapertura = $this->session->userdata('cajapertura');

        if($cajaaertudada==null && $sessioncajaapertura==null){
            $this->session->unset_userdata('caja_id');
            $this->session->unset_userdata('cajero_id');
            $this->session->unset_userdata('cajapertura');
        }




        $dataCuerpo['cajas_abiertas'] = $cajas_abiertas;
       // var_dump($dataCuerpo['cajas_abiertas']);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }


    }

    function checkSysExpDat()
    {

        $remaingin = 0;
        $result = 'success';
        $ver = $this->login_model->checkSysExpDat();
        if (sizeof($ver) > 0) {

            /* $today = date('d-m-Y', time());
             $exp = date('d-m-Y', strtotime($this->config->item('sid_system_expiration_date'))); //CONFIG VALUE
             $expDate = date_create($exp);
             $todayDate = date_create($today);
             $diff = date_diff($todayDate, $expDate);

             if ($diff->format("%R%a") > 0) {*/

            $today = date('d-m-Y', time());
            $exp = date('d-m-Y', strtotime($ver['config_value'])); //query result form database
            $expDate = date_create($exp);
            $todayDate = date_create($today);
            $diff = date_diff($todayDate, $expDate);
            $remaingin = $diff->format("%R%a days");
            if ($diff->format("%R%a") > -8) {
                $remaingin = $diff->format("%R%a");
            } else {

                $result = 'error';
            }

            /* } else {
                 $result = 'error';
             }*/
        } else {
            $result = 'error';
        }
        return  array('result' => $result, 'remaining' => $remaingin);
    }


    function getPage()
    {
        if (!$_POST['page']) die("0");
        $html = "";
        $page = $_POST['page'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $page);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $html .= curl_exec($curl);
        curl_close($curl);
        echo $html;

    }

}
