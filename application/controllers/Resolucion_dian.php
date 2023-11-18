<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class resolucion_dian extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('caja/caja_model','c');
        $this->load->model('resolucion/resolucion_model');

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

        $data['tipos'] = $this->resolucion_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/resolucion/tabla', $data, true);
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
            $data['tipo'] = $this->resolucion_model->get_by(array('resolucion_id' => $id));
        }
        $this->load->view('menu/resolucion/form', $data);
    }

    function guardar()
    {
        $id = $this->input->post('resolucion_id');
        $resolucion_numero = $this->input->post('resolucion_numero');
        $resolucion_prefijo = $this->input->post('resolucion_prefijo');
        $resolucion_numero_inicial = $this->input->post('resolucion_numero_inicial');
        $resolucion_numero_final = $this->input->post('resolucion_numero_final');
        $resolucion_fech_aprobacion = $this->input->post('resolucion_fech_aprobacion');
        $resolucion_fech_vencimiento = $this->input->post('resolucion_fech_vencimiento');
        $resolucion_avisar = $this->input->post('resolucion_avisar');

        $datos = array(

            'resolucion_numero' => $resolucion_numero,
            'resolucion_prefijo' => $resolucion_prefijo,
            'resolucion_numero_inicial' => $resolucion_numero_inicial,
            'resolucion_numero_final' => $resolucion_numero_final,
            'resolucion_fech_aprobacion' => date('Y-m-d', strtotime($resolucion_fech_aprobacion)),
            'resolucion_fech_vencimiento' => empty($resolucion_fech_vencimiento) ? NULL : date('Y-m-d', strtotime($resolucion_fech_vencimiento)),
            'resolucion_avisar' => $resolucion_avisar,

        );
        if (empty($id)) {
            $data['resultado'] = $this->resolucion_model->set($datos);

            $datos['resolucion_id'] = $data['resultado'];
        } else {
            $datos['resolucion_id'] = $id;
            $data['resultado'] = $this->resolucion_model->update($datos);
        }
        if ($data['resultado'] != FALSE) {
            $json['success'] = 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }
        if ($data['resultado'] === NOMBRE_EXISTE || $data['resultado'] === CODIGO_EXISTE) {
            $json['error'] = $data['resultado'];
        }
        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('resolucion_id');
        $nombre = strtoupper($this->input->post('resolucion_numero'));

        $grupo = array(
            'resolucion_id' => $id,
            'resolucion_numero' => $nombre,
            'deleted_at' => date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->resolucion_model->softDelete($grupo);

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error'] = 'Ha ocurrido un error al eliminar el tipo de venta';
        }

        echo json_encode($json);
    }


    function pdf()
    {

        $tipos = $this->resolucion_model->get_all();
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        $pdf->SetTitle('RESOLUCION DIAN');
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
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>RESOLUCION DE LA DIAN</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";
        $html .= "<br><b>RESOLUCION DE LA DIAN:</b> " . "<br>";
        $html .= "<table><tr><th>ID</th><th>NUMERO</th></tr>";
        foreach ($tipos as $tipo) {
            $html .= "<tr><td>" . $tipo['resolucion_id'] . "</td><td>" . $tipo['resolucion_numero'] . "</td></tr>";
        }
        $html .= "</table>";
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("resolucionDian.pdf");
        $pdf->Output($nombre_archivo, 'D');
    }

    function excel()
    {
        $marcas = $this->resolucion_model->get_all();
        $columna_pdf[0] = "ID";
        $columna_pdf[1] = "NUMERO";
        $col = 0;
        for ($i = 0; $i < count($columna_pdf); $i++) {
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna_pdf[$i]);
        }
        $row = 2;
        foreach ($marcas as $marca) {
            $col = 0;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $marca['resolucion_id']);
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col + 1, $row, $marca['resolucion_numero']);
            $col++;
            $row++;
        }
        $this->phpexcel->getActiveSheet()->setTitle('Resolucion de la dian');
        $this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="resoluciondian.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}