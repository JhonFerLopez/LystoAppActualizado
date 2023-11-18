<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class tipo_anulacion extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('caja/caja_model','c');
        $this->load->model('tipo_anulacion/tipo_anulacion_model');

        //$this->load->library('Pdf');
        //$this->load->library('phpExcel/PHPExcel.php');
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

        $data['tipos'] = $this->tipo_anulacion_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/tipo_anulacion/tabla', $data, true);
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
            $data['tipo'] = $this->tipo_anulacion_model->get_by(array('tipo_anulacion_id' => $id));

        }


        $this->load->view('menu/tipo_anulacion/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('tipo_anulacion_id');
        $nombre = $this->input->post('tipo_anulacion_nombre');

        $datos = array(

            'tipo_anulacion_nombre' => strtoupper($nombre),

        );
        if (empty($id)) {
            $data['resultado'] = $this->tipo_anulacion_model->set($datos);

            $datos['tipo_anulacion_id'] = $data['resultado'];
        } else {
            $datos['tipo_anulacion_id'] = $id;
            $data['resultado'] = $this->tipo_anulacion_model->update($datos);
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
        $id = $this->input->post('tipo_anulacion_id');
        $nombre = strtoupper($this->input->post('tipo_anulacion_nombre'));

        $grupo = array(
            'tipo_anulacion_id' => $id,
            'tipo_anulacion_nombre' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->tipo_anulacion_model->softDelete($grupo);

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error'] = 'Ha ocurrido un error al eliminar el tipo de anulacion';
        }

        echo json_encode($json);
    }


    function pdf()
    {

        $tipos = $this->tipo_anulacion_model->get_all();
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        $pdf->SetTitle('GRUPOS');
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
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE TIPOS DE anulacion</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";
        $html .= "<br><b>Tipos de producto:</b> " . "<br>";
        $html .= "<table><tr><th>ID</th><th>Nombre</th></tr>";
        foreach ($tipos as $tipo) {
            $html .= "<tr><td>" . $tipo['tipo_anulacion_id'] . "</td><td>" . $tipo['tipo_anulacion_nombre'] . "</td></tr>";
        }
        $html .= "</table>";
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("reporteTiposDeanulacion.pdf");
        $pdf->Output($nombre_archivo, 'D');
    }

    function excel()
    {
        $marcas = $this->tipo_anulacion_model->get_all();
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
                ->setCellValueByColumnAndRow($col, $row, $marca['tipo_anulacion_id']);
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col + 1, $row, $marca['tipo_anulacion_nombre']);
            $col++;
            $row++;
        }
        $this->phpexcel->getActiveSheet()->setTitle('Reporte Tipos de anulacion');
        $this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteTiposDeanulacion.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}