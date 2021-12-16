<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;

class gastos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('gastos/gastos_model');
        $this->load->model('tiposdegasto/tipos_gasto_model');
        $this->load->model('local/local_model');
        $this->load->model('producto/producto_model');
        $this->load->library('ReceiptPrint');
        $this->very_sesion();
    }


    /** carga cuando listas los proveedores*/
    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data['error'] = $this->session->flashdata('error');
        }

        $data['gastoss'] = []; // $this->gastos_model->get_all();
        $data['tipos'] = $this->tipos_gasto_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/gastos/gastos', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }
    function all()
    {

        try {
            $todoslosproductos = array();

            $search = $this->input->get('search');
            $buscar = $search['value'];
            $where_custom = false;
            if (!empty($buscar) && $buscar != '') {
                $where_custom = "(id_gastos LIKE '%" . $buscar . "%'
          or fecha LIKE '%" . $buscar . "%'
          or descripcion LIKE '%" . $buscar . "%')";
            }

            $nombre_or = false;
            $where_or = false;
            $nombre_in = false;
            $where_in = false;
            $group = 'gastos.id_gastos';
            $select = 'gastos.*, tipos_gasto.nombre_tipos_gasto, local.local_nombre';
            $from = "gastos";
            $join = array('tipos_gasto', 'local');
            $campos_join = array('tipos_gasto.id_tipos_gasto=gastos.tipo_gasto', 'local.int_local_id=gastos.local_id');
            $tipo_join = array('left');
            $ordenar = $this->input->get('order');
            $order = 'id_gastos';
            $order_dir = 'desc';
            if (!empty($ordenar)) {
                $order_dir = $order . ',' . $ordenar[0]['dir'];
                /*if ($ordenar[0]['column'] == 0) {
                $order = 'dkardexFecha';
            }*/
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

            $where = false;
            $search = $this->input->get('search');
            $buscar = $search['value'];
            $where_custom = false;

            $data = $this->input->get('data');
            $fechadesde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
            $fechahasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';
            $tipo = isset($data['tipo']) ? $data['tipo'] : '';
            $where = array();
            $where['date(fecha) >='] = date('Y-m-d', strtotime($fechadesde));
            $where['date(fecha) <='] = date('Y-m-d', strtotime($fechahasta));
            if (!empty($tipo)) {
                $where['tipo_gasto'] = $tipo;
            }

            $todoslosproductos = $this->producto_model->traer_by(
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
                "RESULT_ARRAY",
                $limit,
                $start,
                $order_dir,
                false,
                $where_custom
            );


            //echo $this->db->last_query();
            $total = $todoslosproductos[0]['total_afectados'];


            //AHORA SI EMPIEZO CON EL KARDEX

            $datajson = array();

            $countttt = 0;
            foreach ($todoslosproductos as $prod) {



                $json = array();
                $json[] = $prod['id_gastos'];
                $json[] = $prod['fecha'];
                $json[] = $prod['descripcion'];
                $json[] = $prod['nombre_tipos_gasto'];
                $json[] = $prod['local_nombre'];
                $json[] = $prod['total'];
                $json[] = '   <a class="btn btn-default" title="editar"
                onclick="Gasto.confirmarEdit('. $prod['id_gastos'].',
                '
                . $this->session->userdata('CLAVE_MAESTRA') .',
)"><i
                         class="fa fa-edit"></i>  </a> <a class="btn btn-default" data-toggle="tooltip"
                title="Eliminar" data-original-title="fa fa-comment-o"
                onclick="Gasto.borrar(' . $prod['id_gastos'] . ');"><i class="fa fa-trash-o"></i>
                </a> <a class="btn btn-default" data-toggle="tooltip"
                title="Imprimit" data-original-title="fa fa-comment-o"
                onclick="Gasto.print(' . $prod['id_gastos'] . ');"><i class="fa fa-print"></i>
                </a>';

                $json['campos_sumar'] = array();
                $json['campos_sumar'][] = 2;
                $json['campos_sumar'][] = 3;
                $json['campos_sumar'][] = 4;
                $json['campos_sumar'][] = 5;

                $datajson[] = $json;
            }


            $array['data'] = $datajson;
            $array['draw'] = $draw; //esto debe venir por post
            $array['recordsTotal'] = $total;
            $array['recordsFiltered'] = $total; // esto dbe venir por post

            if ($array) {
                echo json_encode($array);
            } else {
                echo json_encode(array());
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
            log_message("error", "Error:  " . $exception->getMessage());
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        $data['gastos'] = array();
        $data['tiposdegasto'] = $this->tipos_gasto_model->get_all();
        $data['local'] = $this->local_model->get_all();
        if ($id != FALSE) {
            $data['gastos'] = $this->gastos_model->get_by('id_gastos', $id);
        }
        $this->load->view('menu/gastos/form', $data);
    }

    function guardar()
    {
        $cajero = $this->session->userdata('cajero_id');
        $caja = $this->session->userdata('cajapertura');
        if ($caja == '' or $cajero == '') {
            $dataresult['error'] = "Debe aperturar una caja para poder continuar";
            echo json_encode($dataresult);
        } else {
            $id = $this->input->post('id');

            $gastos = array(
                'fecha' => date('Y-m-d', strtotime($this->input->post('fecha'))),
                'descripcion' => $this->input->post('descripcion'),
                'total' => $this->input->post('total'),
                'tipo_gasto' => $this->input->post('tipo_gasto'),
                'local_id' => $this->input->post('local_id'),
                'user_id' => $cajero,
                'caja_id' => $caja,
                'created_at' => date("Y-m-d H:i:s"),
            );

            if (empty($id)) {
                $resultado = $this->gastos_model->insertar($gastos);
                $id = $resultado;
            } else {
                $gastos['id_gastos'] = $id;
                $resultado = $this->gastos_model->update($gastos);
            }

            if ($resultado !== FALSE) {
                $json['success'] = 'Solicitud Procesada con exito';
                $json['id'] = $id;
            } else {
                $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
            }
            echo json_encode($json);
        }
    }

    function eliminar()
    {
        $id = $this->input->post('id');

        $gastos = array(
            'id_gastos' => $id,
            'status_gastos' => 0

        );

        $data['resultado'] = $this->gastos_model->update($gastos);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';
        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el Gasto';
        }

        echo json_encode($json);
    }

    function get_data_for_cloud_print()
    {

        $idventa = $this->input->post('id');


        $json = $this->gastos_model->get_by('id_gastos', $idventa);
        echo json_encode($json);
    }
    function print()
    {

        try {


            $idventa = $this->input->post('id');


            $result['ventas'] = array();
            if ($idventa != FALSE) {
                $ventas = $this->gastos_model->get_by('id_gastos', $idventa);

                if (isset($ventas)) {
                    $printer = $this->receiptprint->connectUsb(
                        $this->session->userdata('IMPRESORA'),
                        $this->session->userdata('USUARIO_IMPRESORA'),
                        $this->session->userdata('PASSWORD_IMPRESORA'),
                        $this->session->userdata('WORKGROUP_IMPRESORA'),
                        $this->session->userdata('SISTEMA_OPERATIVO')
                    );


                    $printer->initialize();

                    $printer->feed(1);

                    /* $printer->text(isset($ventas['REPRESENTANTE_LEGAL']) ? strtoupper($ventas['REPRESENTANTE_LEGAL']) . " \n" : '');
                        $printer->text(isset($ventas['RazonSocialEmpresa']) ? strtoupper($ventas['RazonSocialEmpresa']) . " \n" : '');
                        $printer->text('NIT ' . str_pad(isset($ventas['NIT']) ? strtoupper($ventas['NIT']) : '', 10));
                        $printer->text('REGIMEN' . isset($ventas['REGIMEN_CONTRIBUTIVO']) ? strtoupper($ventas['REGIMEN_CONTRIBUTIVO']) . " \n" : '');
                        $printer->text(isset($ventas['DireccionEmpresa']) ? $ventas['DireccionEmpresa'] . " \n" : '');
                        $printer->text('Telf:' . isset($ventas['TelefonoEmpresa']) ? $ventas['TelefonoEmpresa'] . " \n" : '');
                        */

                    $printer->feed(1);
                    $printer->text("----------------------------------------\n");


                    $printer->text("COMPROBANTE DE GASTO Nº:");
                    $printer->text($ventas['id_gastos']);

                    $printer->feed(1);

                    $printer->text(str_pad("Fecha", 12));
                    $printer->text(str_pad("Hora", 10));
                    $printer->text(str_pad("Cajero", 10));

                    $printer->feed(1);
                    $printer->text(str_pad(date('d/m/Y', strtotime($ventas['created_at'])), 12));
                    $printer->text(str_pad(date('h:i A', strtotime($ventas['created_at'])), 10));
                    $printer->text(str_pad(isset($ventas['user_id']) ? $ventas['user_id'] : '', 10));


                    $printer->text("----------------------------------------\n");



                    $printer->text($this->session->userdata('MENSAJE_FACTURA'));

                    if ($this->session->userdata('MOSTRAR_PROSODE') == true) {
                        $printer->feed(3);

                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->setTextSize(2, 2);
                        /**
                         * lo siguiente se comenta el 25/04/2020, y se pone lo de Bienes exentos,
                         * ya que hay muchos clientes que lo piden a soliticud de gionvani
                         */
                        /*
                                $printer->text('SID - Un producto de PROSODE SAS');
                                $printer->feed(1);
                                $printer->text('www.prosode.com');
                                */
                        $printer->text('Bienes Exentos');
                        $printer->feed(1);
                        $printer->text('Decreto 417 del 17 de marzo de 2020');
                    }


                    $printer->feed(5);


                    $printer->cut(Printer::CUT_FULL, 10); //corta el papel
                    $printer->pulse(); // abre la caja registradora
                    /* Close printer */
                    $printer->close();
                }
            }


            echo json_encode(array('result' => "success"));
        } catch (Exception $e) {
            log_message("error", "Error: Could not print. Message " . $e->getMessage());
            echo json_encode(array('result' => "Couldn't print to this printer: " . $e->getMessage() . "\n"));
            $this->receiptprint->close_after_exception();
        }
    }
}
