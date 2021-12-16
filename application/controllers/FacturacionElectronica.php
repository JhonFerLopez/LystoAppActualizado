<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class facturacionElectronica extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('venta/venta_model');

        $this->load->model('cajas/StatusCajaModel');
        $this->load->model('impuesto/Impuestos_model');
        $this->load->model('notaCreditoModel');
        $this->load->model('notaDebitoModel');
        $this->very_sesion();
    }


    function index()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['listing'] = $this->get_listing();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/index', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function software()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['listing'] = $this->get_listing();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/software', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function certificado()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['listing'] = $this->get_listing();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/certificado', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function resolucion()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['listing'] = $this->get_listing();
        $data['resoluciones'] = $this->get_resolutions();



        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/resolucion', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }
    function logo()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }




        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/logo', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function fact_externa()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }
        $data['listing'] = $this->get_listing();

        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/fact_externa', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    private function get_listing()
    {


        $data = [
            'Country' => $this->Impuestos_model->get_fe_countries(),
            'Language' => $this->Impuestos_model->get_fe_languages(),
            'TypeRegime' => $this->Impuestos_model->get_fe_typeregime(),
            'TypeDocument' => $this->Impuestos_model->get_fe_typedocument(),
            'TypeCurrency' => $this->Impuestos_model->get_fe_typecurrency(),
            'Municipality' => $this->Impuestos_model->get_fe_municipalities(),
            'TypeLiability' => $this->Impuestos_model->get_fe_typeliabilities(),
            'TypeOperation' => $this->Impuestos_model->get_fe_typeoperation(),
            'TypeEnvironment' => $this->Impuestos_model->get_fe_typeenviroment(),
            'TypeOrganization' => $this->Impuestos_model->get_fe_typeorganizations(),
            'TypeDocumentIdentification' => $this->Impuestos_model->get_fe_typedocumentidentifications(),
            'TaxDetail' => $this->Impuestos_model->get_fe_taxdetails(),
        ];
        return $data;
    }


    private function get_resolutions($FACT_E_RESOLUCION_resolucion_id = false)
    {

        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
        $url = "https://apidian.sidroguerias.com/api/ubl2.1/config/multiple-resolutions";

        // append the header putting the secret key and hash
        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        $request_headers = array();
        $request_headers[] = 'Authorization: Bearer ' . $api_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            $resoluciones = array();

            // Show me the result
            $transaction = json_decode($data, TRUE);
            if ($FACT_E_RESOLUCION_resolucion_id === false) {
                $resoluciones = $transaction;
            } else {


                foreach ($transaction as $res) {


                    if ($res['id'] == $FACT_E_RESOLUCION_resolucion_id) {

                        array_push($resoluciones, $res);
                    }
                }
            }


            curl_close($ch);

            return $resoluciones;
        }
    }


    public function registrarEmpresa()
    {


        $fields = $this->input->post(null, TRUE);

        $secretKey = $fields['FACT_E_env_token'];


        $nit = $fields['FACT_E_NIT'];
        $dv = $fields['FACT_E_DV'];


        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        $test = $this->input->post('FACT_E_habilitacionn');
        $post_params = array(
            'type_document_identification_id' => $fields['FACT_E_type_document_identification_id'],
            'type_organization_id' => $fields['FACT_E_type_organization_id'],
            'type_regime_id' => $fields['FACT_E_type_regime_id'],
            'type_liability_id' => $fields['FACT_E_type_liability_id'],
            'type_environment_id' => ($test != false) ? 2 : 1,

            'business_name' => $fields['FACT_E_business_name'],
            'merchant_registration' => $fields['FACT_E_merchant_registration'],
            'municipality_id' => $fields['FACT_E_municipality_id'],
            'address' => $fields['FACT_E_address'],
            'phone' => $fields['FACT_E_phone'],
            'email' => $fields['FACT_E_email'],

            'tax_detail_id' => $fields['FACT_E_TaxDetail'],
            'mailer' => 'smtp',
        );

        $url = API_ENDPOINT . "/api/ubl2.1/config/" . $nit . "/" . $dv;
        $FACT_E_API_DESTINO = $fields['FACT_E_API_DESTINO'];
        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
        $url = $base_url . "/api/ubl2.1/config/" . $nit . "/" . $dv;

        //   echo $url;
        // append the header putting the secret key and hash

        $request_headers = array();

        $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $request_headers[] = 'Accept: application/json';
        $ch = curl_init();
        // echo $api_token;


        //FACTURALATAM usa un mismo metodo para actualizar y crear
        if ($FACT_E_API_DESTINO == 'FACTURALATAM') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            if (empty($api_token)) {
                $request_headers[] = 'Authorization: Bearer ' . $secretKey;
                curl_setopt($ch, CURLOPT_POST, 1);
            } // lo mando por POST
            else {
                $request_headers[] = 'Authorization: Bearer ' . $api_token;
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            }
        }



        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);


        if (curl_errno($ch)) {
            //print "Error: " . curl_error($ch);
            echo json_encode(array('error' => curl_error($ch)));
        } else {
            // Show me the result

            $transaction = json_decode($data, TRUE);

            curl_close($ch);



            echo json_encode($transaction);
        }
    }

    public function registrarSoftare()
    {


        $fields = $this->input->post(null, TRUE);
        // var_dump($fields);


        $api_token = $this->session->userdata('FACT_E_API_TOKEN');

        // echo $api_token;
        if (!empty($api_token)) {

            $post_params = array(
                'id' => $fields['FACT_E_SOFTWARE_ID'],
                'pin' => $fields['FACT_E_SOFTWARE_PIN'],
                'url' => $fields['FACT_E_SOFTWARE_URL'],

            );
            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
            $url =  $base_url . "/api/ubl2.1/config/software";

            $request_headers = array();

            $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $request_headers[] = 'Accept: application/json';
            $ch = curl_init();
            // echo $api_token;


            $request_headers[] = 'Authorization: Bearer ' . $api_token;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");


            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);

            // var_dump($data);
            if (curl_errno($ch)) {
                //print "Error: " . curl_error($ch);
                echo json_encode(array('error' => curl_error($ch)));
            } else {
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);


                // var_dump($transaction);
                echo json_encode($transaction);
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }

    public function registrarResolucion()
    {


        $fields = $this->input->post(null, TRUE);
        $FACT_E_resolucion_actualizar =  $fields['FACT_E_resolucion_actualizar'];
        $FACT_E_RESOLUCION_resolution_id =  $fields['FACT_E_RESOLUCION_resolution_id'];
        // var_dump($fields);

        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {

            $post_params = array(
                'resolution' => $fields['FACT_E_RESOLUCION_resolution'],
                'prefix' => $fields['FACT_E_RESOLUCION_prefix'],
                'technical_key' => $fields['FACT_E_RESOLUCION_technical_key'],
                'resolution_date' => date('Y-m-d', strtotime($fields['FACT_E_RESOLUCION_resolution_date'])),
                'date_from' => date('Y-m-d', strtotime($fields['FACT_E_RESOLUCION_date_from'])),
                'date_to' => date('Y-m-d', strtotime($fields['FACT_E_RESOLUCION_date_to'])),
                'from' => $fields['FACT_E_RESOLUCION_from'],
                'to' => $fields['FACT_E_RESOLUCION_to'],
                'type_document_id' => $fields['FACT_E_RESOLUCION_type_document_id'],

            );

            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

            $url =  $base_url  . "/api/ubl2.1/config/multiple-resolutions";
            $method = "POST";
            if ($FACT_E_resolucion_actualizar != false) {
                $url =  $base_url  . "/api/ubl2.1/config/resolution";
                $method = "PUT";
            }
            if (!empty($FACT_E_RESOLUCION_resolution_id)) {
                $url =  $base_url  . "/api/ubl2.1/config/multiple-resolutions/" . $FACT_E_RESOLUCION_resolution_id;
                $method = "PUT";
            }


            $request_headers = array();

            $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $request_headers[] = 'Accept: application/json';
            $ch = curl_init();
            // echo $api_token;


            $request_headers[] = 'Authorization: Bearer ' . $api_token;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);


            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);

            // var_dump($data);
            if (curl_errno($ch)) {
                //print "Error: " . curl_error($ch);
                echo json_encode(array('error' => curl_error($ch)));
            } else {
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);


                // var_dump($transaction);
                echo json_encode($transaction);
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }
    public function deleteResolucion()
    {


        $fields = $this->input->post(null, TRUE);
        $id =  $fields['id'];
        // var_dump($fields);

        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {

            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

            $url =  $base_url  . "/api/ubl2.1/config/multiple-resolutions/" . $id;
            $method = 'DELETE';


            $request_headers = array();

            $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $request_headers[] = 'Accept: application/json';
            $ch = curl_init();
            // echo $api_token;


            $request_headers[] = 'Authorization: Bearer ' . $api_token;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);

            // var_dump($data);
            if (curl_errno($ch)) {
                //print "Error: " . curl_error($ch);
                echo json_encode(array('error' => curl_error($ch)));
            } else {
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);


                // var_dump($transaction);
                echo json_encode($transaction);
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }


    public function registrarCertificado()
    {


        $fields = $this->input->post(null, TRUE);
        // var_dump($fields);

        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {


            $this->load->library('upload');
            if (!empty($_FILES) and $_FILES['FACT_E_CERTIFICADO']['size'] != '0') {

                $files = $_FILES;


                if ($files['FACT_E_CERTIFICADO']['name'] != "") {

                    //  var_dump($files['FACT_E_CERTIFICADO']);
                    $this->load->library('encryption');
                    $this->encryption->initialize(
                        array(
                            'cipher' => 'aes-256',
                            'mode' => 'cbc',
                            'key' => '<a 32-character random string>'
                        )
                    );
                    $this->upload->initialize($this->set_upload_options($files['FACT_E_CERTIFICADO']['name']));
                    if ($this->upload->do_upload('FACT_E_CERTIFICADO')) {


                        //$password =  $this->encryption->encrypt($fields['FACT_E_CERTIFICADO_CLAVE']);
                        $base_64_cert = base64_encode(file_get_contents($files['FACT_E_CERTIFICADO']['tmp_name']));
                        $post_params = array(
                            'certificate' => $base_64_cert,
                            'password' => $fields['FACT_E_CERTIFICADO_CLAVE'],

                        );

                        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

                        $url =  $base_url . "/api/ubl2.1/config/certificate";

                        $request_headers = array();

                        $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
                        $request_headers[] = 'Accept: application/json';
                        $ch = curl_init();
                        // echo $api_token;


                        $request_headers[] = 'Authorization: Bearer ' . $api_token;
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");


                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));

                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $data = curl_exec($ch);

                        // var_dump($data);
                        if (curl_errno($ch)) {
                            //print "Error: " . curl_error($ch);
                            echo json_encode(array('error' => curl_error($ch)));
                        } else {
                            // Show me the result

                            $transaction = json_decode($data, TRUE);

                            curl_close($ch);


                            // var_dump($transaction);
                            echo json_encode($transaction);
                        }
                    } else {
                        echo json_encode(array('error' => $this->upload->display_errors()));
                    }
                } else {
                    echo json_encode(array('error' => 'Nombre del archivo vacio'));
                }
            } else {
                echo json_encode(array('error' => 'Debe adjuntar el certificado'));
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }

    public function registrarLogo()
    {


        $fields = $this->input->post(null, TRUE);
        // var_dump($fields);

        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {


            $this->load->library('upload');
            if (!empty($_FILES) and $_FILES['FACT_E_LOGO']['size'] != '0') {

                $files = $_FILES;


                if ($files['FACT_E_LOGO']['name'] != "") {

                    //  var_dump($files['FACT_E_CERTIFICADO']);
                    $this->load->library('encryption');
                    $this->encryption->initialize(
                        array(
                            'cipher' => 'aes-256',
                            'mode' => 'cbc',
                            'key' => '<a 32-character random string>'
                        )
                    );
                    $this->upload->initialize($this->set_upload_options($files['FACT_E_LOGO']['name']));
                    if ($this->upload->do_upload('FACT_E_LOGO')) {


                        //$password =  $this->encryption->encrypt($fields['FACT_E_CERTIFICADO_CLAVE']);
                        $base_64_cert = base64_encode(file_get_contents($files['FACT_E_LOGO']['tmp_name']));
                        $post_params = array(
                            'logo' => $base_64_cert,


                        );

                        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;


                        $url = $base_url . "/api/ubl2.1/config/logo";

                        $request_headers = array();

                        $request_headers[] = 'Content-Type: application/x-www-form-urlencoded';
                        $request_headers[] = 'Accept: application/json';
                        $ch = curl_init();
                        // echo $api_token;


                        $request_headers[] = 'Authorization: Bearer ' . $api_token;
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");


                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));

                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $data = curl_exec($ch);

                        // var_dump($data);
                        if (curl_errno($ch)) {
                            //print "Error: " . curl_error($ch);
                            echo json_encode(array('error' => curl_error($ch)));
                        } else {
                            // Show me the result

                            $transaction = json_decode($data, TRUE);

                            curl_close($ch);


                            // var_dump($transaction);
                            echo json_encode($transaction);
                        }
                    } else {
                        echo json_encode(array('error' => $this->upload->display_errors()));
                    }
                } else {
                    echo json_encode(array('error' => 'Nombre del archivo vacio'));
                }
            } else {
                echo json_encode(array('error' => 'Debe adjuntar el certificado'));
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }

    function registrarFactExterna()
    {
        $fields = $this->input->post(null, TRUE);
        // var_dump($fields);
        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {
            $this->load->library('upload');
            if (!empty($_FILES) and $_FILES['FACT_EXTERNA']['size'] != '0') {
                $files = $_FILES;
                if ($files['FACT_EXTERNA']['name'] != "") {
                    $this->upload->initialize($this->set_upload_options($files['FACT_EXTERNA']['name']));
                    if ($this->upload->do_upload('FACT_EXTERNA')) {
                        $base_64_cert = base64_encode(file_get_contents($files['FACT_EXTERNA']['tmp_name']));

                        var_dump($base_64_cert);
                    } else {
                        echo json_encode(array('error' => $this->upload->display_errors()));
                    }
                } else {
                    echo json_encode(array('error' => 'Nombre del archivo vacio'));
                }
            } else {
                echo json_encode(array('error' => 'Debe adjuntar el archivo xml'));
            }
        } else {
            echo json_encode(array('error' => 'Debe registrar el software'));
        }
    }

    function set_upload_options($contador)
    {
        // upload an image options
        $this->load->helper('path');
        $dir = './uploads/';

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
        $config['file_name'] = $contador;

        return $config;
    }


    public function saveOptions()
    {

        $data = array();
        $configuraciones = array();
        if ($this->input->post('FACT_E_NIT')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_NIT',
                'config_value' => $this->input->post('FACT_E_NIT')
            );
        }
        if ($this->input->post('FACT_E_DV')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_DV',
                'config_value' => $this->input->post('FACT_E_DV')
            );
        }

        if ($this->input->post('FACT_E_env_token')) {
            $test = $this->input->post('FACT_E_habilitacionn');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_habilitacionn',
                'config_value' => ($test != false) ? true : false
            );
        }
        if ($this->input->post('FACT_E_env_token')) {
            $test = $this->input->post('FACT_E_syncrono');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_syncrono',
                'config_value' => ($test != false) ? true : false
            );
        }
        if ($this->input->post('FACT_E_type_document_identification_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_type_document_identification_id',
                'config_value' => $this->input->post('FACT_E_type_document_identification_id')
            );
        }
        if ($this->input->post('FACT_E_type_organization_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_type_organization_id',
                'config_value' => $this->input->post('FACT_E_type_organization_id')
            );
        }
        if ($this->input->post('FACT_E_type_regime_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_type_regime_id',
                'config_value' => $this->input->post('FACT_E_type_regime_id')
            );
        }

        if ($this->input->post('FACT_E_business_name')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_business_name',
                'config_value' => $this->input->post('FACT_E_business_name')
            );
        }
        if ($this->input->post('FACT_E_merchant_registration')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_merchant_registration',
                'config_value' => $this->input->post('FACT_E_merchant_registration')
            );
        }
        if ($this->input->post('FACT_E_municipality_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_municipality_id',
                'config_value' => $this->input->post('FACT_E_municipality_id')
            );
        }
        if ($this->input->post('FACT_E_type_liability_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_type_liability_id',
                'config_value' => $this->input->post('FACT_E_type_liability_id')
            );
        }
        if ($this->input->post('FACT_E_address')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_address',
                'config_value' => $this->input->post('FACT_E_address')
            );
        }
        if ($this->input->post('FACT_E_phone')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_phone',
                'config_value' => $this->input->post('FACT_E_phone')
            );
        }
        if ($this->input->post('FACT_E_email')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_email',
                'config_value' => $this->input->post('FACT_E_email')
            );
        }
        if ($this->input->post('FACT_E_test_set_id')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_test_set_id',
                'config_value' => $this->input->post('FACT_E_test_set_id')
            );
        }

        if ($this->input->post('FACT_E_API_TOKEN')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_API_TOKEN',
                'config_value' => $this->input->post('FACT_E_API_TOKEN')
            );
        }

        if ($this->input->post('FACT_E_SOFTWARE_ID')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_SOFTWARE_ID',
                'config_value' => $this->input->post('FACT_E_SOFTWARE_ID')
            );
        }
        if ($this->input->post('FACT_E_SOFTWARE_PIN')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_SOFTWARE_PIN',
                'config_value' => $this->input->post('FACT_E_SOFTWARE_PIN')
            );
        }
        if ($this->input->post('FACT_E_SOFTWARE_URL')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_SOFTWARE_URL',
                'config_value' => $this->input->post('FACT_E_SOFTWARE_URL')
            );
        }
        if ($this->input->post('FACT_E_CERTIFICADO_CLAVE')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_CERTIFICADO_CLAVE',
                'config_value' => $this->input->post('FACT_E_CERTIFICADO_CLAVE')
            );
        }
        if ($this->input->post('FACT_E_resolucion_start_in')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_resolucion_start_in',
                'config_value' => $this->input->post('FACT_E_resolucion_start_in')
            );
        }

        if ($this->input->post('FACT_E_CONT_FACTURADOR_resolucion_start_in')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_CONT_FACTURADOR_resolucion_start_in',
                'config_value' => $this->input->post('FACT_E_CONT_FACTURADOR_resolucion_start_in')
            );
        }


        if ($this->input->post('FACT_E_CONT_DIAN_resolucion_start_in')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_CONT_DIAN_resolucion_start_in',
                'config_value' => $this->input->post('FACT_E_CONT_DIAN_resolucion_start_in')
            );
        }
        if ($this->input->post('FACT_E_resolucion_start_credit_note_in')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_resolucion_start_credit_note_in',
                'config_value' => $this->input->post('FACT_E_resolucion_start_credit_note_in')
            );
        }
        if ($this->input->post('FACT_E_resolucion_start_debit_note_in')) {
            $configuraciones[] = array(
                'config_key' => 'FACT_E_resolucion_start_debit_note_in',
                'config_value' => $this->input->post('FACT_E_resolucion_start_debit_note_in')
            );
        }
        if ($this->input->post('FACT_E_merchant_registration')) {
            $FACT_E_ALLOW = $this->input->post('FACT_E_ALLOW');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_ALLOW',
                'config_value' => !empty($FACT_E_ALLOW) ? 1 : 0
            );
        }
        if ($this->input->post('FACT_E_TaxDetail')) {
            $FACT_E_habilitacionn = $this->input->post('FACT_E_TaxDetail');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_TaxDetail',
                'config_value' => !empty($FACT_E_habilitacionn) ? 1 : 0
            );
        }

        if ($this->input->post('FACT_E_RESOLUCION_resolucion_FE')) {
            $FACT_E_RESOLUCION_resolucion_FE = $this->input->post('FACT_E_RESOLUCION_resolucion_FE');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_RESOLUCION_resolucion_FE',
                'config_value' => $FACT_E_RESOLUCION_resolucion_FE
            );
        }
        if ($this->input->post('FACT_E_RESOLUCION_resolucion_NC')) {
            $FACT_E_RESOLUCION_resolucion_NC = $this->input->post('FACT_E_RESOLUCION_resolucion_NC');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_RESOLUCION_resolucion_NC',
                'config_value' => $FACT_E_RESOLUCION_resolucion_NC
            );
        }

        if ($this->input->post('FACT_E_RESOLUCION_resolucion_ND')) {
            $FACT_E_RESOLUCION_resolucion_ND = $this->input->post('FACT_E_RESOLUCION_resolucion_ND');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_RESOLUCION_resolucion_ND',
                'config_value' =>  $FACT_E_RESOLUCION_resolucion_ND
            );
        }

        if ($this->input->post('FACT_E_RESOLUCION_resolucion_FCF')) {
            $FACT_E_RESOLUCION_resolucion_FCF = $this->input->post('FACT_E_RESOLUCION_resolucion_FCF');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_RESOLUCION_resolucion_FCF',
                'config_value' => $FACT_E_RESOLUCION_resolucion_FCF
            );
        }


        if ($this->input->post('FACT_E_RESOLUCION_resolucion_FCD')) {
            $FACT_E_RESOLUCION_resolucion_FCD = $this->input->post('FACT_E_RESOLUCION_resolucion_FCD');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_RESOLUCION_resolucion_FCD',
                'config_value' => $FACT_E_RESOLUCION_resolucion_FCD
            );
        }
        if ($this->input->post('FACT_E_API_DESTINO')) {
            $FACT_E_API_DESTINO = $this->input->post('FACT_E_API_DESTINO');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_API_DESTINO',
                'config_value' => $FACT_E_API_DESTINO
            );
        }



        //LOGO
        if ($this->input->post('b64')) {
            $FACT_E_LOGO = $this->input->post('b64');
            $configuraciones[] = array(
                'config_key' => 'FACT_E_LOGO',
                'config_value' => !empty($FACT_E_LOGO) ? 1 : 0
            );
        }


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


        echo json_encode($json);
    }


    /**
     *Metodo que realiza la factura electronica
     *Tambien factura contingencia
     *  */
    public function facturaElectronica()
    {

        try {
            $id_vendedor = $this->input->post('id_vendedor');

            //TODO VER QUE NO CONSUMA EL OTRO RESOLUCION
            $cajero = $this->session->userdata('cajero_id');
            $caja = $this->session->userdata('cajapertura');

            /**en este contecto test y sync siempre son lo mismo, pero deja asi por si a futuro se quiere enviar en produccion con sync */
            $test =   $this->session->userdata('FACT_E_habilitacionn');
            $sync =   $this->session->userdata('FACT_E_syncrono');
            $FACT_E_RESOLUCION_resolucion_id =   $this->session->userdata('FACT_E_RESOLUCION_resolucion_FE');
            $sync =  $sync == '1' ? true : false;


            $typeDocument = $this->input->post('feTypeDocument');
            $fe_referencia = $this->input->post('fe_referencia');
            $fe_referencia_date = $this->input->post('fe_referencia_date');
            $feContingenciaCheck = $this->input->post('feContingenciaCheck');
            $type_document = 1; //FACTURA DE VENTA NACIONAL, puede ser que quede parametrizable o aqui siempres es uno

            /**para factura contingencia facturador(03) */
            $reference = array();
            $isContingencia = false;
            /**Es factura de contingencia */
            if (!empty($feContingenciaCheck)) {
                $type_document = $typeDocument;
                $isContingencia = true;

                if ($type_document == '3') {
                    $reference = array(
                        'number' => $fe_referencia,
                        'issue_date' => date('Y-m-d', strtotime($fe_referencia_date)),
                        'type_document_id' => 1 //venta TODO validar si es asi esiempre
                    );
                    $FACT_E_RESOLUCION_resolucion_id =   $this->session->userdata('FACT_E_RESOLUCION_resolucion_FCF');
                } else {
                    $FACT_E_RESOLUCION_resolucion_id =   $this->session->userdata('FACT_E_RESOLUCION_resolucion_FCD');
                }
            }


            $tax_exclusive_amount = 0;
            //echo $caja;
            $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
            if (!empty($statuscaja['cierre'])) {
                $dataresult['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
                echo json_encode(array('errors' => [$dataresult['result']]));
            } else {
                if (($caja == '' or $cajero == '')) {
                    $dataresult['result'] = "Debe aperturar una caja para poder continuar";
                    echo json_encode(array('errors' => [$dataresult['result']]));
                } else {
                    $result = array();
                    $fe_unid_id = 70; // el id de la unidad de medida UNIDAD
                    $FACT_E_type_regime_id = $this->session->userdata('FACT_E_type_regime_id');

                    $impuestos = array();
                    $otros_impuestos = array();


                    $id = $this->input->post('id');
                    $nota = $this->input->post('nota');
                    if (!empty($id)) {
                        // esto por ahora no lo estamos usando
                        $venta = $this->venta_model->obtener_venta($id);
                    } else {


                        /*********BUSCO LA ULTIMA RESOLUCION DE LA DIAN*******/

                        $resoluciones = $this->get_resolutions($FACT_E_RESOLUCION_resolucion_id); //call function
                        $FACT_E_resolucion_start_in = $this->session->userdata('FACT_E_resolucion_start_in'); //donde iniciar la resolucion de facturacion electronica #1
                        if ($isContingencia) {
                            if ($type_document == '3') {
                                $FACT_E_resolucion_start_in = $this->session->userdata('FACT_E_CONT_FACTURADOR_resolucion_start_in'); //donde iniciar la resolucion de facturacion electronica de contingencia facturador #3
                            }
                            if ($type_document == '4') {
                                $FACT_E_resolucion_start_in = $this->session->userdata('FACT_E_CONT_DIAN_resolucion_start_in'); //donde iniciar la resolucion de facturacion electronica de contingencia DIAN #4

                            }
                        }

                        $resolucion_api = $resoluciones[0]; //POR AHORA VOY A PONER POR DEFECTO LA PRIMERA,
                        // PERO EN TEORIA DEBERIA PODER IDENTIFICAR POR LA FECHA O ALGO CUAL ES AL RESOCLUCICON ACTUAL
                        $resolucion = $this->venta_model->generarnumeroFacturaElectronica($resolucion_api, $FACT_E_resolucion_start_in);

                        //armo la venta con lo que viene de javascript


                        $venta = array();
                        $lst_producto = json_decode($this->input->post('lst_producto'));

                        $cliente_post = (object) json_decode($this->input->post('cliente'));
                        $fe_payment_form_id = $this->input->post('fe_payment_form_id');
                        $dias = $this->input->post('diascondicionpagoinput');
                        $forma_pagos = $this->input->post('forma_pago');
                        $forma_pagos_fe_id = $this->input->post('forma_pago_fe_id_');
                        $date = date('Y-m-d');
                        if (intval($dias) > 0) {

                            $date = date('Y-m-d', strtotime($date . ' + ' . $dias . ' days'));
                        }
                        ///TODO implementar metodos
                        $payments_forms = array();
                        if (sizeof($forma_pagos) < 1) {
                            $payment_form = array(
                                'payment_form_id' => $fe_payment_form_id,
                                'payment_method_id' => 1,
                                'payment_due_date' => $date,
                                'duration_measure' => 1,

                            );
                            array_push($payments_forms, $payment_form);
                        } else {

                            $forma_pagos_i = 0;
                            foreach ($forma_pagos as $formapago) {
                                $payment_form = array(
                                    'payment_form_id' =>  $forma_pagos_fe_id[$forma_pagos_i],
                                    'payment_method_id' => 1,
                                    'payment_due_date' => $date,
                                    'duration_measure' => 1,

                                );


                                array_push($payments_forms, $payment_form);
                                $forma_pagos_i++;
                            }
                        }

                        //var_dump($cliente_post);
                        foreach ($lst_producto as $producto_ls) {


                            //aqui asingo los elementos que vienen de post para que quede como si lo hubiera consultado de venta_model->obtenerventa

                            $venta_item = array(
                                'numero' => $resolucion['numero'], //NUMERACION CONSECUTIVA
                                'documento_cliente' => trim($cliente_post->identificacion),
                                'cliente' => $cliente_post->nombres,
                                'apellidos' => $cliente_post->apellidos,
                                'telefonoC1' => $cliente_post->telefono,
                                'email_cliente' => $cliente_post->email,
                                'direccion_cliente' => $cliente_post->direccion,
                                'merchant_registration' => $cliente_post->merchant_registration,
                                'fe_type_liability' => $cliente_post->fe_type_liability,
                                'fe_regime' => $cliente_post->fe_regime,
                                //'id_pais' => $cliente_post->id_pais,// todo , enviar en caso de facturar a clientes internacionales
                                'fe_municipality' => $cliente_post->fe_municipality,
                                'type_document_identification_id' => $cliente_post->type_document_identification_id,
                                'type_organization_id' => $cliente_post->type_organization_id,
                                'tax_detail_id' => $cliente_post->tax_detail_id,

                                'montoTotal' => $this->input->post('totApagar'),
                                'descuentoenvalorhidden' => $this->input->post('descuentoenvalorhidden'),
                                'subTotal' => $this->input->post('subtotal'),
                                'totaldescuento' => $producto_ls->descuento,
                                'porcentaje_impuesto' => $producto_ls->porcentaje_impuesto,
                                'otro_impuesto_detalle_venta' => $producto_ls->otro_impuesto,
                                'nombre' => $producto_ls->nombre,
                                'producto_codigo_interno' => $producto_ls->producto_codigo_interno,
                                'total_detalle_venta' => $producto_ls->total,
                                'fe_type_item_identification_id' => $producto_ls->fe_type_item_identification_id,
                                'fe_impuesto' => $producto_ls->fe_impuesto,
                                'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,

                            );
                            $unidades_lst = array();
                            foreach ($producto_ls->unidades as $unidades) {

                                $unidad = array(
                                    'precio' => $unidades->precio,
                                    'precio_sin_iva' => $unidades->precio_sin_iva,
                                    'cantidad' => $unidades->cantidad,
                                    'fe_unidad' => $unidades->fe_unidad,
                                );
                                array_push($unidades_lst, $unidad);
                            }
                            $venta_item['detalle_unidad'] = $unidades_lst;


                            if ($producto_ls->porcentaje_impuesto > 0) {
                                $porcentaje_impuesto = $producto_ls->porcentaje_impuesto;
                                $current_amount = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['amount'] : 0;
                                $current_impuesto = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['impuesto'] : 0;

                                $impuestos[$porcentaje_impuesto] = array(
                                    'porcentaje_impuesto' => $porcentaje_impuesto,
                                    'impuesto' => $producto_ls->impuesto + $current_impuesto,
                                    'fe_impuesto' => $producto_ls->fe_impuesto,
                                    'amount' => ($producto_ls->subtotal - $producto_ls->descuento) + $current_amount,
                                );
                            }

                            if ($producto_ls->otro_impuesto > 0) {

                                $impuesto_unitario = $producto_ls->otro_impuesto / $producto_ls->unidades[0]->cantidad;
                                $current_amount = isset($otros_impuestos[$impuesto_unitario])  ? $otros_impuestos[$impuesto_unitario]['amount'] : 0;
                                $otros_impuestos[$impuesto_unitario] = array(

                                    //asumimos que por ahora el impuesto a la bolsa es el unico impuesto fijo, por eso lo hacemos asi
                                    'impuesto' => $producto_ls->otro_impuesto,
                                    'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,

                                    'amount' => ($producto_ls->subtotal - $producto_ls->descuento) + $current_amount,
                                    'per_unit_amount' => $impuesto_unitario
                                );
                            }


                            array_push($venta, $venta_item);
                        }
                        // var_dump($venta);


                    }


                    if (isset($venta[0])) {
                        //var_dump($venta[0]);

                        $venta[0] = (object) $venta[0];
                        $cliente = array(
                            'identification_number' => $venta[0]->documento_cliente,
                            'name' => $venta[0]->cliente . " " . $venta[0]->apellidos,
                            'phone' => $venta[0]->telefonoC1,
                            'address' => $venta[0]->direccion_cliente,
                            'email' => $venta[0]->email_cliente,
                            'merchant_registration' => $venta[0]->merchant_registration,
                            'type_liability_id' => $venta[0]->fe_type_liability,
                            'type_regime_id' => $venta[0]->fe_regime,
                            'municipality_id' => $venta[0]->fe_municipality,
                            'type_document_identification_id' => $venta[0]->type_document_identification_id,
                            'type_organization_id' => $venta[0]->type_organization_id,
                            'tax_detail_id' => $venta[0]->tax_detail_id,
                            //'country_id' => $venta[0]->id_pais, //TODO HACERLO PARAMETRIZABLE


                        );

                        $allowance_charges = array();


                        $invoice_lines = array();

                        $tax_totals = array();

                        if (!empty($id)) {

                            $impuestos = $this->venta_model->get_total_impuestos_grouped($id);
                            // LOS OTROS IMPUESTOS, POR AHORA SOLO EL IMPIUESTO A LA BOLSA
                            $otros_impuestos = $this->venta_model->get_total_otros_impuestos_grouped($id);
                        } else {
                        }

                        foreach ($impuestos as $impuesto) {
                            if (!is_object($impuesto)) {
                                $impuesto = (object) $impuesto;
                            }

                            $porcentaje_impuesto = $impuesto->porcentaje_impuesto;
                            $taxable_amount = $impuesto->amount;
                            $tax_exclusive_amount += $taxable_amount;
                            $tax = array(
                                'tax_id' => $impuesto->fe_impuesto,
                                'percent' => $porcentaje_impuesto,
                                'tax_amount' => round($impuesto->impuesto),
                                'taxable_amount' => round($taxable_amount), //Base Imponible sobre la que se calcula el valor del tributo En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas

                            );

                            array_push($tax_totals, $tax);
                        }


                        foreach ($otros_impuestos as $impuesto) {

                            if (!is_object($impuesto)) {
                                $impuesto = (object) $impuesto;
                            }

                            $taxable_amount = $impuesto->amount;
                            // $tax_exclusive_amount += $taxable_amount;
                            $tax = array(
                                'tax_id' => $impuesto->fe_otro_impuesto,
                                'tax_amount' => round($impuesto->impuesto),
                                'taxable_amount' => 0, // en el ejmplo el muchahco puso cero////Base Imponible sobre la que se calcula el valor del tributo  En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas


                                'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                                'per_unit_amount' => $impuesto->per_unit_amount,
                                'base_unit_measure' => 1,  //tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                            );


                            array_push($tax_totals, $tax);
                        }

                        $tax_line_tax_exclusive_amount = 0;
                        $line_extension_amount_tot = 0;
                        $tax_inclusive_amount = 0;
                        foreach ($venta as $item) {


                            $item = (object) $item;

                            $porcentaje_impuesto = $item->porcentaje_impuesto;
                            $otro_impuesto_fijo = $item->otro_impuesto_detalle_venta;

                            $descuento_producto = $item->totaldescuento;


                            $total_producto = $item->total_detalle_venta;

                            foreach ($item->detalle_unidad as $item_unidad) {

                                $item_unidad = (object) $item_unidad;

                                if ($item_unidad->cantidad > 0) {
                                    $descuento_unidad = 0;

                                    $tax_totals_line = array();

                                    $allowance_charges_line = array();

                                    $total_line = $item_unidad->precio * $item_unidad->cantidad;
                                    $total_line_sin_iva = $item_unidad->precio_sin_iva * $item_unidad->cantidad;
                                    $descuento_unidad_prorateo = 0;
                                    if ($descuento_producto > 0) {
                                        //los descuentos se manejan por linea, la lilnea es producto/unidad


                                        $porecentaje_descuento_unidad = $total_line / $total_producto;
                                        $descuento_unidad = $descuento_producto * $porecentaje_descuento_unidad;
                                        $porcentahe_prorateo = ($total_line_sin_iva * 100) / $total_line;
                                        $descuento_unidad_prorateo = $descuento_unidad * $porcentahe_prorateo / 100;
                                        $discount_line = array(
                                            'charge_indicator' => false,
                                            'allowance_charge_reason' => 'Discount',
                                            'amount' => $descuento_unidad,
                                            'base_amount' => $total_line,
                                        );
                                        array_push($allowance_charges_line, $discount_line);
                                    }

                                    $tax_line = array();
                                    //estamos asumiendo que siempre el unico impuesto que se maneja en droguerias es
                                    // el impuesto a la bolda y que siempre viene por unidad. esto hay que analizarlo si toca manejar otro tipo de negocios
                                    if ($otro_impuesto_fijo > 0) {


                                        $taxable_amount = $total_line;
                                        $tax_line_tax_exclusive_amount += $taxable_amount;
                                        $tax_line = array(
                                            'tax_id' => $item->fe_otro_impuesto,
                                            'tax_amount' => $otro_impuesto_fijo, //
                                            'taxable_amount' => 0, // en el ejemplo el muchacho puso cero
                                            'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                                            'per_unit_amount' => $otro_impuesto_fijo / $item_unidad->cantidad,
                                            'base_unit_measure' => 1, // tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                                        );
                                        array_push($tax_totals_line, $tax_line);
                                        //  var_dump($tax_line);
                                    }

                                    //impuestos de porcentaje como iva
                                    if ($porcentaje_impuesto > 0) {

                                        $taxable_amount = $total_line_sin_iva - $descuento_unidad_prorateo;
                                        $tax_amount = ($taxable_amount * $porcentaje_impuesto) / 100;
                                        $tax_line_tax_exclusive_amount += $taxable_amount;

                                        $tax_line = array(
                                            'tax_id' => $item->fe_impuesto,
                                            'tax_amount' => round($tax_amount),
                                            'taxable_amount' => round($taxable_amount),
                                            'percent' => $porcentaje_impuesto,
                                        );
                                        array_push($tax_totals_line, $tax_line);
                                    }


                                    $free_of_charge_indicator = false;
                                    $item_unidad = (object) $item_unidad;
                                    $line_extension_amount = $item_unidad->precio_sin_iva * $item_unidad->cantidad;
                                    $line = array(
                                        'unit_measure_id' => $item_unidad->fe_unidad,
                                        'invoiced_quantity' => $item_unidad->cantidad,
                                        //Valor total de la línea. Resultado: Unidad de Medida x Precio Unidad, libre de tributos, sumando cargos y restando descuentos que apliquen para la línea.
                                        'line_extension_amount' => round($line_extension_amount - $descuento_unidad_prorateo),
                                        // SE DEBE PONER EN TRUE SI SE TRATA DE UNA MUESTRA O REGALO COMERCIAL
                                        'free_of_charge_indicator' => $free_of_charge_indicator,             'description' => $item->nombre,
                                        'code' => $item->producto_codigo_interno,
                                        'type_item_identification_id' => $item->fe_type_item_identification_id,
                                        'price_amount' => round($item_unidad->precio),
                                        'base_quantity' => $item_unidad->cantidad,
                                    );


                                    $line_extension_amount_tot = $line_extension_amount_tot + round($line_extension_amount - $descuento_unidad_prorateo);
                                    $informartaxses = sizeof($tax_totals_line) > 0 ? true : false;
                                    $informarcharges = sizeof($allowance_charges_line) > 0 ? true : false;

                                    if ($informartaxses && $FACT_E_type_regime_id == '1') {
                                        $line['tax_totals'] = $tax_totals_line;
                                    }
                                    if ($informarcharges) {
                                        // var_dump($allowance_charges_line);
                                        $line['allowance_charges'] = $allowance_charges_line;
                                    }
                                    if ($free_of_charge_indicator) {
                                        $line['reference_price_id'] = 1;
                                    }
                                    array_push($invoice_lines, $line);
                                }
                            }
                        }



                        $legal_monetary_totals = array(
                            'line_extension_amount' => round($line_extension_amount_tot),
                            //Valor bruto antes de tributos: Total valor bruto, suma de los valores brutos de las líneas de la factura.
                            'tax_exclusive_amount' => sizeof($tax_totals) > 0 ? round($tax_exclusive_amount) : 0, // se pone en cero si no hay impuestos //Total Base Imponible (Valor Bruto+CargosDescuentos): Base imponible para el cálculo de los tributos
                            'tax_inclusive_amount' => round($venta[0]->montoTotal), //Total de Valor bruto con tributos Los tributos retenidos son retirados en el cálculo de PayableAmount
                            'allowance_total_amount' => 0, //Descuentos: Suma de todos los descuentos aplicados al total de la factura, lo pongo en cero porque todos van como descuento de lineea y no global
                            'charge_total_amount' => 0, //ChargeTotal Amount // AUN NO HAY CARGOS
                            'payable_amount' => round($venta[0]->montoTotal) //Valor Pagable de Factura: Valor total deítems (incluyendo cargos y descuentos anivel de ítems)+valors tributos + valors cargos – valor descuentos – valor anticipos
                        );
                        //   var_dump($legal_monetary_totals);

                        $data = array(
                            'sync' => $sync,
                            'send' => true, //para enviar por email
                            'number' => $venta[0]->numero,
                            'type_document_id' => $type_document,
                            'customer' => $cliente,
                            'legal_monetary_totals' => $legal_monetary_totals,
                            'invoice_lines' => $invoice_lines,
                            'resolution_id' =>  $FACT_E_RESOLUCION_resolucion_id



                            //'payment_form' => $payments_forms,


                        );

                        if (!empty($nota)) {
                            $data['notes'] = array(array('text' => $nota));
                        }
                        $informartaxses = sizeof($tax_totals) > 0 ? true : false;
                        if ($informartaxses && $FACT_E_type_regime_id == '1') {

                            //ar_dump($tax_totals);
                            $data['tax_totals'] = $tax_totals;
                        }

                        if (sizeof($reference) > 0) {
                            $data['additional_document_reference'] = $reference;
                        }

                        //  var_dump($allowance_charges);
                        $informarcharges = sizeof($allowance_charges) > 0 ? true : false;

                        if ($informarcharges) {


                            //  var_dump($invoice_lines);
                            $data['allowance_charges'] = $allowance_charges;
                        }
                        //var_dump($data);

                        //            var_dump($data);
                        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
                        $FACT_E_test_set_id = $this->session->userdata('FACT_E_test_set_id');

                        //var_dump($data);

                        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
                        if (!empty($api_token)) {
                            //var_dump($data);

                            $testurl = ($test != false) ? $FACT_E_test_set_id : '';
                            $url = $base_url . "/api/ubl2.1/invoice";
                            if ($test != false) {
                                $url = $base_url . "/api/ubl2.1/invoice/" . $testurl;
                            }
                            if ($isContingencia) {
                                $url = $base_url . "/api/ubl2.1/contingency-billing";
                                if ($test != false) {
                                    $url = $base_url . "/api/ubl2.1/contingency-billing/" . $testurl;
                                }
                            }


                            $request_headers = array();


                            $request_headers[] = 'Content-Type: application/json';
                            $request_headers[] = 'Accept: application/json';
                            $request_headers[] = 'Authorization: Bearer ' . $api_token;
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
                                $transaction['number'] = $resolucion['numero'];
                                $transaction['resolution_id'] = $resolucion['id_resolucion'];
                                $transaction['type_document_id'] = $type_document;
                                $transaction['fe_prefijo'] = $resolucion_api['prefix'];
                                $result = $transaction;
                            }
                        } else {
                            $result = array('error' => 'Debe registrrar el software');
                        }
                    }
                    echo json_encode($result);
                }
            }
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage(), 'line'=>$e->getLine());
            echo json_encode($result);
        }
    }

    public
    function statusZip()
    {
        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {

            $data = array();
            $trackid = $this->input->post('trackid');
            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
            $url =  $base_url . "/api/ubl2.1/status/zip/" . $trackid;


            $request_headers = array();


            $request_headers[] = 'Content-Type: application/json';
            $request_headers[] = 'Accept: application/json';
            $request_headers[] = 'Authorization: Bearer ' . $api_token;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
            $result = array('error' => 'Debe registrar el software');
        }
        echo json_encode($result);
    }
    public
    function consultarRangos()
    {
        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        $FACT_E_NIT = $this->session->userdata('FACT_E_NIT');
        $FACT_E_SOFTWARE_ID = $this->session->userdata('FACT_E_SOFTWARE_ID');
        if (!empty($api_token)) {

            $data = array();
            $trackid = $this->input->post('trackid');
            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
            $url =  $base_url . "/api/ubl2.1/numbering/range/" . $FACT_E_NIT . "/" . $FACT_E_NIT . "/" . $FACT_E_SOFTWARE_ID;


            $request_headers = array();


            $request_headers[] = 'Content-Type: application/json';
            $request_headers[] = 'Accept: application/json';
            $request_headers[] = 'Authorization: Bearer ' . $api_token;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
            $result = array('error' => 'Debe registrar el software');
        }
        echo json_encode($result);
    }


    public
    function statusDocument()
    {
        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
        if (!empty($api_token)) {

            $trackid = $this->input->post('trackid'); //UUID

            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

            $url =   $base_url . "/api/ubl2.1/status/document/" . $trackid;


            $request_headers = array();


            $request_headers[] = 'Content-Type: application/json';
            $request_headers[] = 'Accept: application/json';
            $request_headers[] = 'Authorization: Bearer ' . $api_token;
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
        echo json_encode($result);
    }




    public
    function notaCredito()
    {
        try {
            $fe_unid_id = 70; // el id de la unidad de medida UNIDAD

            $cajero = $this->session->userdata('cajero_id');
            $caja = $this->session->userdata('cajapertura');
            $FACT_E_RESOLUCION_resolucion_id =   $this->session->userdata('FACT_E_RESOLUCION_resolucion_NC');
            $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
            $sync =   $this->session->userdata('FACT_E_syncrono');
            $sync = $sync == '1' ? true : false;
            if (!empty($statuscaja['cierre'])) {
                $dataresult['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
                echo json_encode(array('errors' => [$dataresult['result']]));
            } else {
                if (($caja == '' or $cajero == '')) {
                    $dataresult['result'] = "Debe aperturar una caja para poder continuar";
                    echo json_encode(array('errors' => [$dataresult['result']]));
                } else {
                    $id = $this->input->post('idventa');
                    $cliente = json_decode($this->input->post('cliente'));

                    $test = $this->session->userdata('FACT_E_habilitacionn');
                    $type_document = 5;
                    $resoluciones = $this->get_resolutions($FACT_E_RESOLUCION_resolucion_id); //call function
                    $FACT_E_resolucion_start_in = $this->session->userdata('FACT_E_resolucion_start_credit_note_in'); //call function
                    $resolucion_api = $resoluciones[0]; //POR AHORA VOY A PONER POR DEFECTO LA PRIMERA,

                    $resolucion = $this->venta_model->generarnumeroNotaCredito($resolucion_api, $FACT_E_resolucion_start_in);

                    $ventas = $this->venta_model->obtener_venta($id);

                    $ventas0 = (object) $ventas[0];

                    $line_extension_amount_tot = 0; //Valor bruto antes de tributos: Total valor bruto, suma de los valores brutos de las líneas de la factura.
                    $tax_exclusive_amount = 0; // se pone en cero si no hay impuestos //Total Base Imponible (Valor Bruto+CargosDescuentos): Base imponible para el cálculo de los tributos
                    $tax_inclusive_amount = 0; //Total de Valor bruto con tributos Los tributos retenidos son retirados en el cálculo de PayableAmount
                    $allowance_total_amount = 0; //Descuentos: Suma de todos los descuentos aplicados al total de la factura
                    $charge_total_amount = 0; //ChargeTotal Amount // AUN NO HAY CARGOS
                    $payable_amount = 0; //Valor Pagable de Factura: Valor total deítems (incluyendo cargos y descuentos anivel de ítems)+valors tributos + valors cargos – valor descuentos – valor anticipos


                    $data = array(
                        "billing_reference" => array(
                            "number" => $ventas0->fe_numero,
                            "uuid" => $ventas0->uuid,
                            "issue_date" => $ventas0->fe_issue_date,
                        ),
                        'sync' =>  $sync,
                        'send' => true, //para enviar por email
                        "number" => $resolucion['numero'],
                        "type_document_id" => $type_document, // 5 es nota credito
                        "customer" => array(
                            "identification_number" => !empty($ventas0->documento_cliente) ? $ventas0->documento_cliente : $cliente->identificacion,
                            "name" => !empty($ventas0->cliente) ? $ventas0->cliente . " " . $ventas0->apellidos : $cliente->nombres . " " . $cliente->apellidos,
                            "phone" => !empty($ventas0->telefonoC1) ? $ventas0->telefonoC1 : $cliente->telefono,
                            "address" => !empty($ventas0->clienteDireccion) ? $ventas0->clienteDireccion : $cliente->direccion,
                            "email" =>  !empty($ventas0->email_cliente) ? $ventas0->email_cliente : $cliente->email,
                            "merchant_registration" => !empty($ventas0->merchant_registration) ? $ventas0->merchant_registration : $cliente->merchant_registration,
                            'type_liability_id' => !empty($ventas0->fe_type_liability) ? $ventas0->fe_type_liability : $cliente->fe_type_liability,
                            'type_regime_id' => !empty($ventas0->fe_regime) ? $ventas0->fe_regime : $cliente->fe_regime,
                            'municipality_id' => !empty($ventas0->fe_municipality) ? $ventas0->fe_municipality : $cliente->fe_municipality,
                            'type_document_identification_id' => !empty($ventas0->type_document_identification_id) ? $ventas0->type_document_identification_id : $cliente->type_document_identification_id,
                            'type_organization_id' => !empty($ventas0->type_organization_id) ? $ventas0->type_organization_id : $cliente->type_organization_id,
                            'tax_detail_id' => !empty($ventas0->tax_detail_id) ? $ventas0->tax_detail_id : $cliente->tax_detail_id,
                        ),
                        'resolution_id' =>  $FACT_E_RESOLUCION_resolucion_id


                    );
                    $credit_note_lines = array();


                    $conteo = 0;
                    $devolucion = $this->input->post('devolver');
                    $impuestos = array();
                    $otros_impuestos = array();
                    $ventadev = array();
                    if ($devolucion === 'true') {

                        ////LO DE LA NOTA CREDITO
                        $lst_producto = json_decode($this->input->post('lst_producto'));

                        foreach ($lst_producto as $producto_ls) {


                            //aqui asingo los elementos que vienen de post para que quede como si lo hubiera consultado de venta_model->obtenerventa

                            $venta_item = array(
                                'numero' => $resolucion['numero'], //NUMERACION CONSECUTIVA
                                'montoTotal' => $this->input->post('totApagar'),
                                'subTotal' => $this->input->post('subtotal'),
                                'totaldescuento' => $producto_ls->descuento,
                                'impuesto' => $producto_ls->impuesto,
                                'porcentaje_impuesto' => $producto_ls->porcentaje_impuesto,
                                'otro_impuesto_detalle_venta' => $producto_ls->otro_impuesto_devolver,
                                'nombre' => $producto_ls->nombre,
                                'producto_codigo_interno' => $producto_ls->producto_codigo_interno,
                                'total_detalle_venta' => $producto_ls->total,
                                'fe_type_item_identification_id' => $producto_ls->fe_type_item_identification_id,
                                'fe_impuesto' => $producto_ls->fe_impuesto,
                                'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,

                            );
                            $unidades_lst = array();
                            $isDev = false;
                            $subtotal_devolver = 0;
                            $total_devolver = 0;
                            $impuesto_devolver = 0;
                            $porcentaje_impuesto = $producto_ls->porcentaje_impuesto;
                            $total_producto = $producto_ls->total;
                            $descuento_producto = $producto_ls->descuento;
                            foreach ($producto_ls->unidades as $unidades) {
                                $descuento_unidad_prorateo = 0;
                                if ($unidades->cantidad_dev > 0) {

                                    $item_dev_sin_iva = floatval($unidades->precio_sin_iva) * floatval($unidades->cantidad_dev);
                                    $item_dev = floatval($unidades->precio) * floatval($unidades->cantidad_dev);

                                    $isDev = true;
                                    $total_devolver = $total_devolver + floatval($unidades->precio);
                                    $subtotal_devolver =  $item_dev_sin_iva;
                                }
                                if ($descuento_producto > 0) {
                                    //los descuentos se manejan por linea, la lilnea es producto/unidad

                                    $porecentaje_descuento_unidad =  $item_dev / $total_producto;
                                    $descuento_unidad = $descuento_producto * $porecentaje_descuento_unidad;

                                    $porcentahe_prorateo = ($item_dev_sin_iva * 100) / $item_dev;
                                    $descuento_unidad_prorateo = $descuento_unidad * $porcentahe_prorateo / 100;

                                    $subtotal_devolver = $item_dev_sin_iva - $descuento_unidad_prorateo;
                                }
                                if (isset($unidades->precio)) {
                                    $unidad = array(
                                        'precio' => $unidades->precio,
                                        'precio_sin_iva' => $unidades->precio_sin_iva,
                                        'cantidad' => $unidades->cantidad_dev,
                                        // 'cantidad_dev' => $unidades->cantidad_dev,
                                        'fe_unidad' => $unidades->fe_unidad,
                                    );
                                    array_push($unidades_lst, $unidad);
                                }
                            }
                            $venta_item['detalle_unidad'] = $unidades_lst;


                            if ($producto_ls->porcentaje_impuesto > 0 && $isDev) {


                                $impuesto_devolver = ($subtotal_devolver * $porcentaje_impuesto) / 100;

                                $porcentaje_impuesto = $producto_ls->porcentaje_impuesto;
                                $current_amount = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['amount'] : 0;
                                $current_impuesto = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['impuesto'] : 0;

                                $impuestos[$porcentaje_impuesto] = array(
                                    'porcentaje_impuesto' => $porcentaje_impuesto,
                                    'impuesto' => $impuesto_devolver + $current_impuesto,
                                    'fe_impuesto' => $producto_ls->fe_impuesto,
                                    'amount' => ($subtotal_devolver) + $current_amount,
                                );
                            }
                            if ($producto_ls->otro_impuesto_devolver > 0) {

                                $impuesto_unitario = $producto_ls->otro_impuesto_devolver / $producto_ls->unidades[0]->cantidad_dev;
                                $current_amount = isset($otros_impuestos[$impuesto_unitario]) ? $otros_impuestos[$impuesto_unitario]['amount'] : 0;
                                $otros_impuestos[$impuesto_unitario] = array(

                                    //asumimos que por ahora el impuesto a la bolsa es el unico impuesto fijo, por eso lo hacemos asi
                                    'impuesto' => $producto_ls->otro_impuesto_devolver,
                                    'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,
                                    'amount' => ($subtotal_devolver) + $current_amount,
                                    'per_unit_amount' => $impuesto_unitario
                                );
                            }

                            array_push($ventadev, $venta_item);
                        }
                        $ventas = $ventadev;
                    }


                    $fe_unid_id = 70; // el id de la unidad de medida UNIDAD

                    $FACT_E_type_regime_id = $this->session->userdata('FACT_E_type_regime_id');
                    //ES ANULACION


                    $tax_totals = array();

                    /**entra aqui solo si es anulacion */
                    if ($devolucion !== 'true') {

                        $impuestos = $this->venta_model->get_total_impuestos_grouped($id);
                        // LOS OTROS IMPUESTOS, POR AHORA SOLO EL IMPIUESTO A LA BOLSA
                        $otros_impuestos = $this->venta_model->get_total_otros_impuestos_grouped($id);
                    } else {
                    }


                    foreach ($impuestos as $impuesto) {
                        if (!is_object($impuesto)) {
                            $impuesto = (object) $impuesto;
                        }

                        $porcentaje_impuesto = $impuesto->porcentaje_impuesto;
                        $taxable_amount = $impuesto->amount;
                        $tax_exclusive_amount += $taxable_amount;
                        $tax = array(
                            'tax_id' => $impuesto->fe_impuesto,
                            'percent' => $porcentaje_impuesto,
                            'tax_amount' => round($impuesto->impuesto),
                            'taxable_amount' => round($taxable_amount), //Base Imponible sobre la que se calcula el valor del tributo En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas

                        );

                        array_push($tax_totals, $tax);
                    }



                    foreach ($otros_impuestos as $impuesto) {

                        if (!is_object($impuesto)) {
                            $impuesto = (object) $impuesto;
                        }

                        $taxable_amount = $impuesto->amount;
                        // $tax_exclusive_amount += $taxable_amount;
                        $tax = array(
                            'tax_id' => $impuesto->fe_otro_impuesto,
                            'tax_amount' => round($impuesto->impuesto),
                            'taxable_amount' => 0, // en el ejmplo el muchahco puso cero////Base Imponible sobre la que se calcula el valor del tributo  En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas

                            'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                            'per_unit_amount' => $impuesto->per_unit_amount,
                            'base_unit_measure' => 1,  //tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                        );



                        array_push($tax_totals, $tax);
                    }


                    foreach ($ventas as $item) {
                        $item = (object) $item;

                        $porcentaje_impuesto = $item->porcentaje_impuesto;
                        $otro_impuesto_fijo = $item->otro_impuesto_detalle_venta;

                        $descuento_producto = $item->totaldescuento;

                        $total_producto = $item->total_detalle_venta;
                        //  var_dump($item->detalle_unidad);
                        if (sizeof($item->detalle_unidad) > 0) {
                            foreach ($item->detalle_unidad as $item_unidad) {
                                $item_unidad = (object) $item_unidad;


                                if ($item_unidad->cantidad > 0) {


                                    $tax_totals_line = array();

                                    $allowance_charges_line = array();

                                    $total_line = $item_unidad->precio * $item_unidad->cantidad;
                                    $total_line_sin_iva = $item_unidad->precio_sin_iva * $item_unidad->cantidad;
                                    $descuento_unidad  = 0;
                                    if ($descuento_producto > 0) {
                                        //los descuentos se manejan por linea, la lilnea es producto/unidad


                                        $porecentaje_descuento_unidad = $total_line / $total_producto;
                                        $descuento_unidad = $descuento_producto * $porecentaje_descuento_unidad;

                                        $discount_line = array(
                                            'charge_indicator' => false,
                                            'allowance_charge_reason' => 'Discount',
                                            'amount' => $descuento_unidad,
                                            'base_amount' => $total_line,
                                        );
                                        array_push($allowance_charges_line, $discount_line);
                                    }


                                    //estamos asumiendo que siempre el unico impuesto que se maneja en droguerias es
                                    // el impuesto a la bolda y que siempre viene por unidad. esto hay que analizarlo si toca manejar otro tipo de negocios
                                    if ($otro_impuesto_fijo > 0) {


                                        $tax = array(
                                            'tax_id' => $item->fe_otro_impuesto,
                                            'tax_amount' => $otro_impuesto_fijo, //
                                            'taxable_amount' => 0,
                                            'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                                            'per_unit_amount' => $otro_impuesto_fijo / $item_unidad->cantidad,
                                            'base_unit_measure' => 1, // tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                                        );

                                        array_push($tax_totals_line, $tax);
                                    }


                                    $descuento_unidad_prorateo = 0;
                                    $porcentahe_prorateo = ($total_line_sin_iva * 100) / $total_line;
                                    $descuento_unidad_prorateo = $descuento_unidad * $porcentahe_prorateo / 100;
                                    //impuestos de porcentaje como iva
                                    if ($porcentaje_impuesto > 0) {

                                        $taxable_amount = $total_line_sin_iva - $descuento_unidad_prorateo;
                                        $tax_amount = ($taxable_amount * $porcentaje_impuesto) / 100;
                                        //$tax_line_tax_exclusive_amount += $taxable_amount;

                                        $tax = array(
                                            'tax_id' => $item->fe_impuesto,
                                            'tax_amount' => round($tax_amount),
                                            'taxable_amount' => round($taxable_amount),
                                            'percent' => $porcentaje_impuesto,
                                        );

                                        array_push($tax_totals_line, $tax);
                                    }


                                    $free_of_charge_indicator = false;
                                    $item_unidad = (object) $item_unidad;

                                    $informartaxses = sizeof($tax_totals_line) > 0 ? true : false;
                                    $informarcharges = sizeof($allowance_charges_line) > 0 ? true : false;

                                    if ($informartaxses && $FACT_E_type_regime_id == 1) {
                                        $line['tax_totals'] = $tax_totals_line;
                                    }
                                    if ($informarcharges) {
                                        $line['allowance_charges'] = $allowance_charges_line;
                                    }
                                    if ($free_of_charge_indicator) {
                                        $line['reference_price_id'] = 1;
                                    }
                                    $line_extension_amount = $item_unidad->precio_sin_iva * $item_unidad->cantidad;
                                    // var_dump($venta);
                                    $linea = array(
                                        "unit_measure_id" => $item_unidad->fe_unidad,
                                        "invoiced_quantity" => $item_unidad->cantidad,
                                        "line_extension_amount" => round($line_extension_amount),
                                        "free_of_charge_indicator" => $free_of_charge_indicator,
                                        //  "allowance_charges" => $allowance_charges_line,
                                        //  "tax_totals" => $tax_totals_line,
                                        "description" => $item->nombre,
                                        "code" => $item->producto_codigo_interno,
                                        "type_item_identification_id" => $item->fe_type_item_identification_id,
                                        'price_amount' => round($item_unidad->precio),
                                        "base_quantity" => $item_unidad->cantidad
                                    );




                                    $line_extension_amount_tot = $line_extension_amount_tot + round($line_extension_amount - $descuento_unidad_prorateo);
                                    $allowance_total_amount = +0; ///TODO
                                    $payable_amount += $total_line - $descuento_unidad;
                                    $tax_inclusive_amount += $total_line - $descuento_unidad;
                                    if ($otro_impuesto_fijo > 0) {
                                        $tax_inclusive_amount += ($otro_impuesto_fijo);
                                        $payable_amount += ($otro_impuesto_fijo);
                                    }



                                    $informartaxses = sizeof($tax_totals_line) > 0 ? true : false;
                                    $informarcharges = sizeof($allowance_charges_line) > 0 ? true : false;

                                    if ($informartaxses && $FACT_E_type_regime_id == '1') {
                                        $linea['tax_totals'] = $tax_totals_line;
                                    }
                                    if ($informarcharges) {
                                        // var_dump($allowance_charges_line);
                                        $linea['allowance_charges'] = $allowance_charges_line;
                                    }
                                    if ($free_of_charge_indicator) {
                                        $linea['reference_price_id'] = 1;
                                    }
                                    array_push($credit_note_lines, $linea);
                                }
                            }
                        }
                        $conteo++;
                    }


                    $ventas[0] = (object) $ventas[0];
                    $data["legal_monetary_totals"] = array(

                        'line_extension_amount' => round($line_extension_amount_tot), //Valor bruto antes de tributos: Total valor bruto, suma de los valores brutos de las líneas de la factura.
                        'tax_exclusive_amount' => sizeof($tax_totals) > 0 ? round($tax_exclusive_amount) : 0, // se pone en cero si no hay impuestos //Total Base Imponible (Valor Bruto+CargosDescuentos): Base imponible para el cálculo de los tributos
                        'tax_inclusive_amount' => round($tax_inclusive_amount), //Total de Valor bruto con tributos Los tributos retenidos son retirados en el cálculo de PayableAmount
                        'allowance_total_amount' => $allowance_total_amount, //Descuentos: Suma de todos los descuentos aplicados al total de la factura
                        'charge_total_amount' => 0, //ChargeTotal Amount // AUN NO HAY CARGOS
                        'payable_amount' => round($payable_amount) //Valor Pagable de Factura: Valor total deítems (incluyendo cargos y descuentos anivel de ítems)+valors tributos + valors cargos – valor descuentos – valor anticipos

                    );


                    $informartaxses = sizeof($tax_totals) > 0 ? true : false;
                    if ($informartaxses && $FACT_E_type_regime_id == '1') {

                        // var_dump($tax_totals);
                        $data['tax_totals'] = $tax_totals;
                    }


                    if (sizeof($credit_note_lines) > 0) {

                        $data['credit_note_lines'] = $credit_note_lines;

                        //   var_dump( $data);
                        $FACT_E_test_set_id = $this->session->userdata('FACT_E_test_set_id');
                        $testurl = ($test != false) ? "/" . $FACT_E_test_set_id : '';
                        $api_token = $this->session->userdata('FACT_E_API_TOKEN');

                        if (!empty($api_token)) {


                            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;


                            $url =  $base_url  . "/api/ubl2.1/credit-note" . $testurl;


                            $request_headers = array();


                            $request_headers[] = 'Content-Type: application/json';
                            $request_headers[] = 'Accept: application/json';
                            $request_headers[] = 'Authorization: Bearer ' . $api_token;
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
                                $transaction['number'] = $resolucion['numero'];
                                $transaction['resolution_id'] = $resolucion['id_resolucion'];
                                $transaction['fe_prefijo'] = $resolucion_api['prefix'];
                                curl_close($ch);

                                $result = $transaction;
                                //$this->postProcesNotaCredito();
                            }
                        } else {
                            $result = array('error' => 'Debe registrrar el software');
                        }
                    } else {
                        $result = array('errors' => ['No hay productos que registrar']);
                    }


                    echo json_encode($result);
                }
            }
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage());
            echo json_encode($result);
        }
    }





    public function notaDebito()
    {

        try {

            $fe_unid_id = 70; // el id de la unidad de medida UNIDAD
            $sync =   $this->session->userdata('FACT_E_syncrono');
            $sync = $sync == '1' ? true : false;
            $cajero = $this->session->userdata('cajero_id');
            $FACT_E_RESOLUCION_resolucion_id =   $this->session->userdata('FACT_E_RESOLUCION_resolucion_ND');
            $caja = $this->session->userdata('cajapertura');
            $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));
            if (!empty($statuscaja['cierre'])) {
                $dataresult['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
                echo json_encode(array('errors' => [$dataresult['result']]));
            } else {

                if (($caja == '' or $cajero == '')) {
                    $dataresult['result'] = "Debe aperturar una caja para poder continuar";
                    echo json_encode(array('errors' => [$dataresult['result']]));
                } else {
                    $id = $this->input->post('idventa');
                    $test = $this->session->userdata('FACT_E_habilitacionn');
                    $type_document = 6; //nota debito
                    $cliente = json_decode($this->input->post('cliente'));
                    $resoluciones = $this->get_resolutions($FACT_E_RESOLUCION_resolucion_id); //call function
                    $FACT_E_resolucion_start_in = $this->session->userdata('FACT_E_resolucion_start_debit_note_in'); //call function
                    $resolucion_api = $resoluciones[0]; //POR AHORA VOY A PONER POR DEFECTO LA PRIMERA,

                    $resolucion = $this->venta_model->generarnumeroNotaDebito($resolucion_api, $FACT_E_resolucion_start_in);


                    $ventas = $this->venta_model->obtener_venta($id);

                    $ventas0 = (object) $ventas[0];


                    $line_extension_amount_tot = 0; //Valor bruto antes de tributos: Total valor bruto, suma de los valores brutos de las líneas de la factura.
                    $tax_exclusive_amount = 0; // se pone en cero si no hay impuestos //Total Base Imponible (Valor Bruto+CargosDescuentos): Base imponible para el cálculo de los tributos
                    $tax_inclusive_amount = 0; //Total de Valor bruto con tributos Los tributos retenidos son retirados en el cálculo de PayableAmount
                    $allowance_total_amount = 0; //Descuentos: Suma de todos los descuentos aplicados al total de la factura
                    $charge_total_amount = 0; //ChargeTotal Amount // AUN NO HAY CARGOS
                    $payable_amount = 0; //Valor Pagable de Factura: Valor total deítems (incluyendo cargos y descuentos anivel de ítems)+valors tributos + valors cargos – valor descuentos – valor anticipos


                    $data = array(
                        "billing_reference" => array(
                            "number" => $ventas0->fe_numero,
                            "uuid" => $ventas0->uuid,
                            "issue_date" => $ventas0->fe_issue_date,
                        ),
                        'sync' => $sync,
                        'send' => true, //para enviar por email
                        "number" => $resolucion['numero'],
                        "type_document_id" => $type_document, // 5 es nota credito
                        "customer" => array(
                            "identification_number" => $ventas0->documento_cliente,
                            "name" => $ventas0->cliente . " " . $ventas0->apellidos,
                            "phone" => !empty($ventas0->telefonoC1) ? $ventas0->telefonoC1 : $ventas0->celular,
                            "address" => $ventas0->clienteDireccion,
                            "email" => $ventas0->email_cliente,
                            "merchant_registration" => $ventas0->merchant_registration,
                            'type_liability_id' => !empty($ventas0->fe_type_liability) ? $ventas0->fe_type_liability : $cliente->fe_type_liability,
                            'type_regime_id' => !empty($ventas0->fe_regime) ? $ventas0->fe_regime : $cliente->fe_regime,
                            'municipality_id' => !empty($ventas0->fe_municipality) ? $ventas0->fe_municipality : $cliente->fe_municipality,
                            'type_document_identification_id' => !empty($ventas0->type_document_identification_id) ? $ventas0->type_document_identification_id : $cliente->type_document_identification_id,
                            'type_organization_id' => !empty($ventas0->type_organization_id) ? $ventas0->type_organization_id : $cliente->type_organization_id,
                            'tax_detail_id' => !empty($ventas0->tax_detail_id) ? $ventas0->tax_detail_id : $cliente->tax_detail_id,
                      
                        ),
                        'resolution_id' =>  $FACT_E_RESOLUCION_resolucion_id


                    );
                    $credit_note_lines = array();


                    $conteo = 0;

                    $impuestos = array();
                    $otros_impuestos = array();
                    $ventadev = array();


                    ////LO DE LA NOTA CREDITO
                    $lst_producto = json_decode($this->input->post('lst_producto'));

                    // var_dump($lst_producto);
                    foreach ($lst_producto as $producto_ls) {


                        //aqui asingo los elementos que vienen de post para que quede como si lo hubiera consultado de venta_model->obtenerventa

                        $venta_item = array(
                            'numero' => $resolucion['numero'], //NUMERACION CONSECUTIVA
                            'montoTotal' => $this->input->post('totApagar'),
                            'subTotal' => $this->input->post('subtotal'),
                            'totaldescuento' => $producto_ls->descuento,
                            'impuesto' => $producto_ls->impuesto,
                            'porcentaje_impuesto' => $producto_ls->porcentaje_impuesto,
                            'otro_impuesto_detalle_venta' => $producto_ls->otro_impuesto_devolver,
                            'nombre' => $producto_ls->nombre,
                            'producto_codigo_interno' => $producto_ls->producto_codigo_interno,
                            'total_detalle_venta' => $producto_ls->total,
                            'fe_type_item_identification_id' => $producto_ls->fe_type_item_identification_id,
                            'fe_impuesto' => $producto_ls->fe_impuesto,
                            'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,

                        );
                        $unidades_lst = array();
                        $isDev = false;
                        $subtotal_devolver = 0;
                        $total_devolver = 0;
                        $impuesto_devolver = 0;
                        $porcentaje_impuesto = $producto_ls->porcentaje_impuesto;
                        $total_producto = $producto_ls->total;
                        $descuento_producto = $producto_ls->descuento;
                        foreach ($producto_ls->unidades as $unidades) {
                            $descuento_unidad_prorateo = 0;
                            if ($unidades->cantidad_dev > 0) {

                                $item_dev_sin_iva = floatval($unidades->precio_sin_iva) * floatval($unidades->cantidad_dev);
                                $item_dev = floatval($unidades->precio) * floatval($unidades->cantidad_dev);

                                $isDev = true;
                                $total_devolver = $total_devolver + floatval($unidades->precio);
                                $subtotal_devolver =  $item_dev_sin_iva;
                            }
                            if ($descuento_producto > 0) {
                                //los descuentos se manejan por linea, la lilnea es producto/unidad

                                $porecentaje_descuento_unidad =  $item_dev / $total_producto;
                                $descuento_unidad = $descuento_producto * $porecentaje_descuento_unidad;

                                $porcentahe_prorateo = ($item_dev_sin_iva * 100) / $item_dev;
                                $descuento_unidad_prorateo = $descuento_unidad * $porcentahe_prorateo / 100;

                                $subtotal_devolver = $item_dev_sin_iva - $descuento_unidad_prorateo;
                            }



                            $unidad = array(
                                'precio' => $unidades->precio,
                                'precio_sin_iva' => $unidades->precio_sin_iva,
                                'cantidad' => $unidades->cantidad_dev,
                                'fe_unidad' => $unidades->fe_unidad,
                            );
                            array_push($unidades_lst, $unidad);
                        }
                        $venta_item['detalle_unidad'] = $unidades_lst;

                        if ($producto_ls->porcentaje_impuesto > 0 &&  $isDev) {

                            $impuesto_devolver = ($subtotal_devolver * $porcentaje_impuesto) / 100;

                            $porcentaje_impuesto = $producto_ls->porcentaje_impuesto;
                            $current_amount = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['amount'] : 0;
                            $current_impuesto = isset($impuestos[$porcentaje_impuesto]) ? $impuestos[$porcentaje_impuesto]['impuesto'] : 0;

                            $impuestos[$porcentaje_impuesto] = array(
                                'porcentaje_impuesto' => $porcentaje_impuesto,
                                'impuesto' => $impuesto_devolver + $current_impuesto,
                                'fe_impuesto' => $producto_ls->fe_impuesto,
                                'amount' => ($subtotal_devolver) + $current_amount,
                            );
                        }


                        if ($producto_ls->otro_impuesto_devolver > 0) {

                            $impuesto_unitario = $producto_ls->otro_impuesto_devolver / $producto_ls->unidades[0]->cantidad_dev;
                            $current_amount = isset($otros_impuestos[$impuesto_unitario]) ? $otros_impuestos[$impuesto_unitario]['amount'] : 0;
                            $otros_impuestos[$impuesto_unitario] = array(

                                //asumimos que por ahora el impuesto a la bolsa es el unico impuesto fijo, por eso lo hacemos asi
                                'impuesto' => $impuesto_unitario * $unidades->cantidad_dev,
                                'fe_otro_impuesto' => $producto_ls->fe_otro_impuesto,
                                'amount' => ($subtotal_devolver) + $current_amount,
                                'per_unit_amount' => $impuesto_unitario
                            );
                        }

                        array_push($ventadev, $venta_item);
                    }

                    $ventas = $ventadev;

                    $FACT_E_type_regime_id = $this->session->userdata('FACT_E_type_regime_id');

                    $tax_totals = array();



                    foreach ($impuestos as $impuesto) {
                        if (!is_object($impuesto)) {
                            $impuesto = (object) $impuesto;
                        }

                        $porcentaje_impuesto = $impuesto->porcentaje_impuesto;
                        $taxable_amount = $impuesto->amount;
                        $tax_exclusive_amount += $taxable_amount;
                        $tax = array(
                            'tax_id' => $impuesto->fe_impuesto,
                            'percent' => $porcentaje_impuesto,
                            'tax_amount' => round($impuesto->impuesto),
                            'taxable_amount' => round($taxable_amount), //Base Imponible sobre la que se calcula el valor del tributo En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas

                        );

                        array_push($tax_totals, $tax);
                    }



                    foreach ($otros_impuestos as $impuesto) {

                        if (!is_object($impuesto)) {
                            $impuesto = (object) $impuesto;
                        }

                        $taxable_amount = $impuesto->amount;

                        $tax = array(
                            'tax_id' => $impuesto->fe_otro_impuesto,
                            'tax_amount' => round($impuesto->impuesto),
                            'taxable_amount' => 0, // en el ejmplo el muchahco puso cero////Base Imponible sobre la que se calcula el valor del tributo  En el caso de que el tributo es una porcentaje del valortributable: informar la base imponible en valormonetarioEn el caso de que el tributo es un valor fijo por unidadtributada: informar el número de unidades tributadas

                            'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                            'per_unit_amount' => $impuesto->per_unit_amount,
                            'base_unit_measure' => 1,  //tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                        );


                        array_push($tax_totals, $tax);
                    }



                    foreach ($ventas as $item) {
                        $item = (object) $item;

                        $porcentaje_impuesto = $item->porcentaje_impuesto;
                        $otro_impuesto_fijo = $item->otro_impuesto_detalle_venta;

                        $descuento_producto = $item->totaldescuento;

                        $total_producto = $item->total_detalle_venta;

                        foreach ($item->detalle_unidad as $item_unidad) {
                            $item_unidad = (object) $item_unidad;


                            if ($item_unidad->cantidad > 0) {


                                $tax_totals_line = array();

                                $allowance_charges_line = array();

                                $total_line = $item_unidad->precio * $item_unidad->cantidad;
                                $total_line_sin_iva = $item_unidad->precio_sin_iva * $item_unidad->cantidad;
                                $descuento_unidad  = 0;
                                if ($descuento_producto > 0) {
                                    //los descuentos se manejan por linea, la lilnea es producto/unidad


                                    $porecentaje_descuento_unidad = $total_line / $total_producto;
                                    $descuento_unidad = $descuento_producto * $porecentaje_descuento_unidad;

                                    $discount_line = array(
                                        'charge_indicator' => false,
                                        'allowance_charge_reason' => 'Discount',
                                        'amount' => $descuento_unidad,
                                        'base_amount' => $total_line,
                                    );
                                    array_push($allowance_charges_line, $discount_line);
                                }


                                //estamos asumiendo que siempre el unico impuesto que se maneja en droguerias es
                                // el impuesto a la bolda y que siempre viene por unidad. esto hay que analizarlo si toca manejar otro tipo de negocios
                                if ($otro_impuesto_fijo > 0) {


                                    $tax = array(
                                        'tax_id' => $item->fe_otro_impuesto,
                                        'tax_amount' => $otro_impuesto_fijo, //
                                        'taxable_amount' => 0,
                                        'unit_measure_id' => $fe_unid_id, //LA UNIDAD PARA EL CASO DE LAS BOLSAS
                                        'per_unit_amount' => $otro_impuesto_fijo / $item_unidad->cantidad,
                                        'base_unit_measure' => 1, // tengo que decir cada cuantas unidades cobro este impuesto, en el caso de las bosas siempre es uno
                                    );
                                    array_push($tax_totals_line, $tax);
                                }

                                $descuento_unidad_prorateo = 0;
                                $porcentahe_prorateo = ($total_line_sin_iva * 100) / $total_line;
                                $descuento_unidad_prorateo = $descuento_unidad * $porcentahe_prorateo / 100;

                                //impuestos de porcentaje como iva
                                if ($porcentaje_impuesto > 0) {


                                    $taxable_amount = $total_line_sin_iva - $descuento_unidad_prorateo;
                                    $tax_amount = ($taxable_amount * $porcentaje_impuesto) / 100;


                                    $tax = array(
                                        'tax_id' => $item->fe_impuesto,
                                        'tax_amount' => round($tax_amount),
                                        'taxable_amount' => round($taxable_amount),
                                        'percent' => $porcentaje_impuesto,
                                    );

                                    array_push($tax_totals_line, $tax);
                                }



                                $free_of_charge_indicator = false;
                                $item_unidad = (object) $item_unidad;

                                $informartaxses = sizeof($tax_totals_line) > 0 ? true : false;
                                $informarcharges = sizeof($allowance_charges_line) > 0 ? true : false;

                                if ($informartaxses && $FACT_E_type_regime_id == 1) {
                                    $line['tax_totals'] = $tax_totals_line;
                                }
                                if ($informarcharges) {
                                    $line['allowance_charges'] = $allowance_charges_line;
                                }
                                if ($free_of_charge_indicator) {
                                    $line['reference_price_id'] = 1;
                                }
                                $line_extension_amount = $item_unidad->precio_sin_iva * $item_unidad->cantidad;

                                $linea = array(
                                    "unit_measure_id" => $item_unidad->fe_unidad,
                                    "invoiced_quantity" => $item_unidad->cantidad,
                                    "line_extension_amount" => round($line_extension_amount - $descuento_unidad_prorateo),
                                    "free_of_charge_indicator" => $free_of_charge_indicator,
                                    "description" => $item->nombre,
                                    "code" => $item->producto_codigo_interno,
                                    "type_item_identification_id" => $item->fe_type_item_identification_id,
                                    'price_amount' => round($item_unidad->precio),
                                    "base_quantity" => $item_unidad->cantidad
                                );
                                $line_extension_amount_tot = $line_extension_amount_tot + round($line_extension_amount - $descuento_unidad_prorateo);
                                $allowance_total_amount = +0; ///TODO
                                $payable_amount += $total_line - $descuento_unidad;
                                $tax_inclusive_amount += $total_line - $descuento_unidad;
                                if ($otro_impuesto_fijo > 0) {
                                    $tax_inclusive_amount += ($otro_impuesto_fijo);
                                    $payable_amount += ($otro_impuesto_fijo);
                                }


                                $informartaxses = sizeof($tax_totals_line) > 0 ? true : false;
                                $informarcharges = sizeof($allowance_charges_line) > 0 ? true : false;

                                if ($informartaxses && $FACT_E_type_regime_id == '1') {
                                    $linea['tax_totals'] = $tax_totals_line;
                                }
                                if ($informarcharges) {

                                    $linea['allowance_charges'] = $allowance_charges_line;
                                }
                                if ($free_of_charge_indicator) {
                                    $linea['reference_price_id'] = 1;
                                }
                                array_push($credit_note_lines, $linea);
                            }
                        }
                        $conteo++;
                    }



                    $ventas[0] = (object) $ventas[0];
                    $data["requested_monetary_totals"] = array(

                        'line_extension_amount' => round($line_extension_amount_tot), //Valor bruto antes de tributos: Total valor bruto, suma de los valores brutos de las líneas de la factura.
                        'tax_exclusive_amount' => sizeof($tax_totals) > 0 ? round($tax_exclusive_amount) : 0, // se pone en cero si no hay impuestos //Total Base Imponible (Valor Bruto+CargosDescuentos): Base imponible para el cálculo de los tributos
                        'tax_inclusive_amount' => round($tax_inclusive_amount), //Total de Valor bruto con tributos Los tributos retenidos son retirados en el cálculo de PayableAmount
                        'allowance_total_amount' => $allowance_total_amount, //Descuentos: Suma de todos los descuentos aplicados al total de la factura
                        'charge_total_amount' => 0, //ChargeTotal Amount // AUN NO HAY CARGOS
                        'payable_amount' => round($payable_amount) //Valor Pagable de Factura: Valor total deítems (incluyendo cargos y descuentos anivel de ítems)+valors tributos + valors cargos – valor descuentos – valor anticipos

                    );



                    $informartaxses = sizeof($tax_totals) > 0 ? true : false;
                    if ($informartaxses && $FACT_E_type_regime_id == '1') {


                        $data['tax_totals'] = $tax_totals;
                    }


                    if (sizeof($credit_note_lines) > 0) {

                        $data['debit_note_lines'] = $credit_note_lines;
                        $FACT_E_test_set_id = $this->session->userdata('FACT_E_test_set_id');
                        $testurl = ($test != false) ? "/" . $FACT_E_test_set_id : '';
                        $api_token = $this->session->userdata('FACT_E_API_TOKEN');
                        if (!empty($api_token)) {


                            // var_dump($data);

                            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;
                            $url = $base_url  . "/api/ubl2.1/debit-note" . $testurl;


                            $request_headers = array();


                            $request_headers[] = 'Content-Type: application/json';
                            $request_headers[] = 'Accept: application/json';
                            $request_headers[] = 'Authorization: Bearer ' . $api_token;
                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
                                $transaction['number'] = $resolucion['numero'];
                                $transaction['resolution_id'] = $resolucion['id_resolucion'];
                                $transaction['fe_prefijo'] = $resolucion_api['prefix'];
                                curl_close($ch);

                                $result = $transaction;
                                //$this->postProcesNotaCredito();
                            }
                        } else {
                            $result = array('error' => 'Debe registrrar el software');
                        }
                    } else {
                        $result = array('errors' => ['No hay productos que registrar']);
                    }
                    echo json_encode($result);
                }
            }
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage());
            echo json_encode($result);
        }
    }


    public
    function sendmail()
    {

        $jsonReturn = array('error' => 'Debe ingresar el uuid');

        $uuid = $this->input->post('uuid');
        $email = $this->input->post('email');
        $FACT_E_API_TOKEN  = $this->session->userdata('FACT_E_API_TOKEN');


        if (!empty($uuid)) {

            $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
            $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

            if ($FACT_E_API_DESTINO == 'SOENAC') {
                $url = $base_url . "/api/ubl2.1/send-mail/" . $uuid;
            } else {
                $url = $base_url . "/api/ubl2.1/send-email/" . $uuid;
            }



            $request_headers = array();


            $request_headers[] = 'Content-Type: application/json';
            $request_headers[] = 'Accept: application/json';
            $request_headers[] = 'Authorization: Bearer ' . $FACT_E_API_TOKEN;
            $ch = curl_init();

            $data = array('to' => array($email));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
            $result = array('error' => 'Debe registrar el software');
        }

        if ($jsonReturn) {
            echo json_encode($result);
        } else {
            return $result;
        }
    }


    /**enviar conjunto ZIP 
     * 
     * para envios Asyncronos masivos
     */
    public function envioZip()
    {

        try {

            $test = $this->session->userdata('FACT_E_habilitacionn');
            $cajero = $this->session->userdata('cajero_id');
            $caja = $this->session->userdata('cajapertura');
            $statuscaja = $this->StatusCajaModel->getBy(array('id' => $caja));

            if (!empty($statuscaja['cierre'])) {
                $dataresult['result'] = "La caja en la que está intentando realizar la operación ya ha sido cerrada por otro usuario. Por favor cierre sessión e ingrese nuevamente para trabajar en otra caja";
                echo json_encode(array('errors' => [$dataresult['result']]));
            } else {


                $zipName = $this->input->post('zipName');
                $zipBase64Bytes = $this->input->post('zipBase64Bytes');
                $data = array('fileName' => $zipName, 'contentFile' => $zipBase64Bytes);

                if (sizeof($data) > 0) {


                    $FACT_E_test_set_id = $this->session->userdata('FACT_E_test_set_id');

                    $api_token = $this->session->userdata('FACT_E_API_TOKEN');
                    if (!empty($api_token)) {

                        // var_dump($data);

                        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
                        $base_url = $FACT_E_API_DESTINO  == 'SOENAC' ? API_ENDPOINT : API_ENDPOINT_LATAM;

                        if ($test != false) {
                            /**modo hablitacion */
                            $url =  $base_url . "/api/ubl2.1/send-test-set-async/" . $FACT_E_test_set_id;
                        } else {

                            /**modo produccoin */
                            $url =  $base_url . "/api/ubl2.1/api/ubl2.1/send-bill-async/";
                        }


                        $request_headers = array();


                        $request_headers[] = 'Content-Type: application/json';
                        $request_headers[] = 'Accept: application/json';
                        $request_headers[] = 'Authorization: Bearer ' . $api_token;
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
                } else {
                    $result = array('errors' => ['No hay productos que registrar']);
                }
                echo json_encode($result);
            }
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage());
            echo json_encode($result);
        }
    }


    function postProcesNotaCredito()
    {


        try {
            $uuid = $this->input->post('uuid');
            $number = $this->input->post('number');
            $prefijo = $this->input->post('prefijo');
            $status = $this->input->post('status');
            $issued_date = $this->input->post('issued_date');
            $resolution_id = $this->input->post('resolution_id');
            $user_id = $this->session->userdata('nUsuCodigo');
            $caja_id = $this->session->userdata('cajapertura');
            $venta_id = $this->input->post('venta_id');
            $type = $this->input->post('type');
            $zipkey = $this->input->post('zipkey');
            $reponseDian = $this->input->post('reponseDian');
            $notacredito = array(
                'uuid' => $uuid,
                'number' => $number,
                'resolution_id' => $resolution_id,
                'issued_date' => $issued_date,
                'user_id' => $user_id,
                'caja_id' => $caja_id,
                'venta_id' => $venta_id,
                'zipkey' => $zipkey,
                'reponseDian' => json_encode($reponseDian),
                'type' => $type,
                'prefijo' => $prefijo,
                'status' => $status
            );

            $this->notaCreditoModel->insert($notacredito);
            echo json_encode(array('success' => true));
        } catch (Exception $e) {
            $result = array('success' => false, 'error' => $e->getMessage());
            echo json_encode($result);
        }
    }

    function postProcesNotaDebito()
    {

        try {

            $uuid = $this->input->post('uuid');
            $number = $this->input->post('number');
            $prefijo = $this->input->post('prefijo');
            $status = $this->input->post('status');

            $issued_date = $this->input->post('issued_date');
            $resolution_id = $this->input->post('resolution_id');
            $user_id = $this->session->userdata('nUsuCodigo');
            $caja_id = $this->session->userdata('cajapertura');
            $venta_id = $this->input->post('venta_id');

            $zipkey = $this->input->post('zipkey');
            $reponseDian = $this->input->post('reponseDian');
            $notacredito = array(
                'uuid' => $uuid,
                'number' => $number,
                'resolution_id' => $resolution_id,
                'issued_date' => $issued_date,
                'user_id' => $user_id,
                'caja_id' => $caja_id,
                'venta_id' => $venta_id,
                'zipkey' => $zipkey,
                'reponseDian' => json_encode($reponseDian),
                'prefijo' => $prefijo,
                'status' => $status

            );

            $this->notaDebitoModel->insert($notacredito);
            echo json_encode(array('success' => true));
        } catch (Exception $e) {
            $result = array('success' => false, 'error' => $e->getMessage());
            echo json_encode($result);
        }
    }

    function consulta()
    {

        $data = array('document_types' => array(
            ['1', 'Factura electrónica'],
            ['4', 'Factura contingencia DIAN'],
            ['3', 'Factura contingencia facturador'],
            ['5', 'Nota crédito electrónica'],
            ['6', 'Nota débito electrónica'],

        ));

        $dataCuerpo['cuerpo'] = $this->load->view('menu/facturacion_electronica/consulta', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }
}
