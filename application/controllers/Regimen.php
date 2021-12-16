<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class regimen extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('caja/caja_model','c');
        $this->load->model('regimen/regimen_model');

        $this->load->library('Pdf');
        $this->load->library('phpExcel/PHPExcel.php');

        $this->very_sesion();
    }



    function index()
    {
        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['regimenes'] = $this->regimen_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/regimen/tabla', $data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['regimen'] = $this->regimen_model->get_by(array('regimen_id' => $id));

        }

        $this->load->view('menu/regimen/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('regimen_id');
        $nombre = $this->input->post('regimen_nombre');
        $compra_retienen = $this->input->post('compra_retienen');
        $compra_retienen_iva = $this->input->post('compra_retienen_iva');
        $venta_retienen = $this->input->post('venta_retienen');
        $venta_retienen_iva = $this->input->post('venta_retienen_iva');
        $genera_iva = $this->input->post('genera_iva');
        $autoretenedor = $this->input->post('autoretenedor');
        $gran_contribuyente = $this->input->post('gran_contribuyente');

        $datos = array(

            'regimen_nombre' => strtoupper($nombre),
            'compra_retienen' => !empty($compra_retienen)?1:0,
            'compra_retienen_iva' => !empty($compra_retienen_iva)?1:0,
            'venta_retienen' => !empty($venta_retienen)?1:0,
            'venta_retienen_iva' => !empty($venta_retienen_iva)?1:0,
            'genera_iva' => !empty($genera_iva)?1:0,
            'autoretenedor' => !empty($autoretenedor)?1:0,
            'gran_contribuyente' => !empty($gran_contribuyente)?1:0,

        );
        if (empty($id)) {
            $data['resultado'] = $this->regimen_model->set($datos);

            $datos['regimen_id'] = $data['resultado'];
        } else {
            $datos['regimen_id'] = $id;
            $data['resultado'] = $this->regimen_model->update($datos);
        }


        if ($data['resultado'] != FALSE) {

            //  $this->session->set_flashdata('success', 'Solicitud Procesada con exito');
            $json['success'] = 'Solicitud Procesada con exito';

        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al procesar la solicitud');
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        if ($data['resultado'] === NOMBRE_EXISTE || $data['resultado'] === CODIGO_EXISTE) {
            //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = $data['resultado'];
        }
        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('regimen_id');
        $nombre = strtoupper($this->input->post('regimen_nombre'));

        $grupo = array(
            'regimen_id' => $id,
            'regimen_nombre' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->regimen_model->softDelete($grupo);

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error'] = 'Ha ocurrido un error al eliminar el régimen';
        }

        echo json_encode($json);
    }


    function pdf()
    {

        $tipos = $this->regimen_model->get_all();
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        $pdf->SetTitle('REGIMEN');
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
            $w = 0, $h = 0, $x = '60', $y = '',
            $textoheader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'C', $autopadding = true);
        $pdf->SetFontSize(12);
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE REGIMEN CONTRIBUTIVO</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";
        $html .= "<br><b>Régimen contributivo:</b> " . "<br>";
        $html .= "<table><tr><th>ID</th><th>Nombre</th></tr>";
        foreach ($tipos as $tipo) {
            $html .= "<tr><td>" . $tipo['regimen_id'] . "</td><td>" . $tipo['regimen_nombre'] . "</td></tr>";
        }
        $html .= "</table>";
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("reporteRegimenContributivo.pdf");
        $pdf->Output($nombre_archivo, 'D');
    }

    function excel()
    {
        $marcas = $this->regimen_model->get_all();
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
                ->setCellValueByColumnAndRow($col, $row, $marca['regimen_id']);
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col + 1, $row, $marca['regimen_nombre']);
            $col++;
            $row++;
        }
        $this->phpexcel->getActiveSheet()->setTitle('Reporte Régimen contributivo');
        $this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporteRegimenContributivo.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}