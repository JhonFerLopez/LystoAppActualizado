<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class metodosdepago extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('metodosdepago/metodos_pago_model');
        $this->load->library('Pdf');
        $this->load->library('phpExcel/PHPExcel.php');


        $this->load->model('impuesto/impuestos_model');
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

        $data['metodos'] = $this->metodos_pago_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/metodosdepago/metodosdepago', $data, true);

        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();


        $fe_impuestos = []; 
        if (!empty($this->session->userdata('FACT_E_ALLOW') and $this->session->userdata('FACT_E_ALLOW') === '1')) {
            $fe_impuestos = $this->impuestos_model->get_fepayment_methods();
        }
        $data['fe_methods'] = $fe_impuestos;
        if ($id != FALSE) {
            $data['metodospago'] = $this->metodos_pago_model->get_by('id_metodo', $id);
        }

        $this->load->view('menu/metodosdepago/form', $data);
    }

    function guardar()
    {
        $id = $this->input->post('id');
        $suma_total_ingreso = $this->input->post('suma_total_ingreso');
        $incluye_cuadre_caja = $this->input->post('incluye_cuadre_caja');
        $centros_bancos = $this->input->post('centros_bancos');
        $metodospago = array(
            'nombre_metodo' => strtoupper($this->input->post('nombre_metodo')),
            'fe_method_id' => strtoupper($this->input->post('fe_method_id')),
            'centros_bancos' => !empty($centros_bancos) ? 1 : 0,
            'suma_total_ingreso' => !empty($suma_total_ingreso) ? 1 : 0,
            'incluye_cuadre_caja' => !empty($incluye_cuadre_caja) ? 1 : 0,
        );
        if (empty($id)) {
            $resultado = $this->metodos_pago_model->insertar($metodospago);
        } else {
            $metodospago['id_metodo'] = $id;
            $resultado = $this->metodos_pago_model->update($metodospago);
        }
        if ($resultado == TRUE) {
            $json['success'] = 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        if ($resultado === NOMBRE_EXISTE) {
            $json['error'] = NOMBRE_EXISTE;
        }
        echo json_encode($json);
    }
    function buscarmetodo()
    {
        if ($this->input->is_ajax_request()) {


            $metodo = json_decode($this->input->post('metodo', true));

            $tipo_metodo = $this->metodos_pago_model->get_by('id_metodo', $metodo);

            if ($tipo_metodo['tipo_metodo'] == "BANCO") {

                $json['bancos'] = $this->banco_model->get_all();
            } else {
                $json = false;
            }
            echo json_encode($json);
        }
    }


    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = strtoupper($this->input->post('nombre'));

        $metodospago = array(
            'id_metodo' => $id,
            'nombre_metodo' => $nombre . time(),
            'deleted_at' => date('Y-m-d')


        );

        $data['resultado'] = $this->metodos_pago_model->update($metodospago);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';
        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el M&eacute;todos';
        }

        echo json_encode($json);
    }

    function pdf()
    {

        $tipos = $this->metodos_pago_model->get_all();
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        $pdf->SetTitle('METODOS DE PAGO');
        $pdf->SetPrintHeader(false);
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 14, '', true);
        $pdf->AddPage();
        $pdf->SetFontSize(8);
        $textoheader = "";
        $pdf->writeHTMLCell(
            $w = 0,
            $h = 0,
            $x = '60',
            $y = '',
            $textoheader,
            $border = 0,
            $ln = 1,
            $fill = 0,
            $reseth = true,
            $align = 'C',
            $autopadding = true
        );
        $pdf->SetFontSize(12);
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE METODOSS DE PAGO</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";
        $html .= "<br><b>METODOS DE PAGO:</b> " . "<br>";
        $html .= "<table><tr><th>ID</th><th>Nombre</th></tr>";
        foreach ($tipos as $tipo) {
            $html .= "<tr><td>" . $tipo['id_metodo'] . "</td><td>" . $tipo['nombre_metodo'] . "</td></tr>";
        }
        $html .= "</table>";
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("metodosdepago.pdf");
        $pdf->Output($nombre_archivo, 'D');
    }

    function excel()
    {
        $marcas = $this->metodos_pago_model->get_all();
        $columna_pdf[0] = "ID";
        $columna_pdf[1] = "NOMBRE";
        $col = 0;
        for ($i = 0; $i < count($columna_pdf); $i++) {
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna_pdf[$i]);
        }
        $row = 2;
        foreach ($marcas as $marca) {
            $col = 0;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $marca['id_metodo']);
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col + 1, $row, $marca['nombre_metodo']);
            $col++;
            $row++;
        }
        $this->phpexcel->getActiveSheet()->setTitle('Reporte métodos de pago');
        $this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="metodosdeago.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
