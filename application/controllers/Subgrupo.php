<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class subgrupo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('caja/caja_model','c');
        $this->load->model('subgrupos/subgrupos_model');
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
        $data['lstMovimiento'] = array();
        $data['subgrupos'] = $this->subgrupos_model->get_subgrupos();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/subgrupo/subgrupos', $data, true);

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
            $data['subgrupo'] = $this->subgrupos_model->get_by('id_subgrupo', $id);
        }


        $this->load->view('menu/subgrupo/form', $data);
    }


    function guardar()
    {

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $grupo = array(
            'nombre_subgrupo' => $nombre
        );
        if (empty($id)) {
            $data['resultado'] = $this->subgrupos_model->set_subgrupos();
        } else {
            $grupo['id_subgrupo'] = $id;
            $data['resultado'] = $this->subgrupos_model->update_subgrupos($grupo);
        }

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Solicitud Procesada con exito';

        } else {

            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        if ($data['resultado'] === NOMBRE_EXISTE) {
            //$this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = NOMBRE_EXISTE;
        }
        echo json_encode($json);
    }


    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'id_subgrupo' => $id,
            'nombre_subgrupo' => $nombre . time(),
            'estatus_subgrupo' => 0

        );

        $data['resultado'] = $this->subgrupos_model->update_subgrupos($grupo, 'eliminar');

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha Eliminado exitosamente';


        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el subgrupo';
        }

        echo json_encode($json);
    }

    function pdf()
    {

        $subgrupos = $this->subgrupos_model->get_subgrupos();

        //var_dump($miembro);
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('SUBGRUPOS');
        // $pdf->SetSubject('FICHA DE MIEMBROS');
        $pdf->SetPrintHeader(false);
//echo K_PATH_IMAGES;
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "AL.â€¢.G.â€¢.D.â€¢.G.â€¢.A.â€¢.D.â€¢.U.â€¢.<br>Gran Logia de la RepÃºblica de Venezuela", "Gran Logia de la <br> de Venezuela", array(0, 64, 255), array(0, 64, 128));


        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        //  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        //  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//relaciÃ³n utilizada para ajustar la conversiÃ³n de los pÃ­xeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);

// Establecer el tipo de letra

//Si tienes que imprimir carÃ¡cteres ASCII estÃ¡ndar, puede utilizar las fuentes bÃ¡sicas como
// Helvetica para reducir el tamaÃ±o del archivo.
        $pdf->SetFont('helvetica', '', 14, '', true);

// AÃ±adir una pÃ¡gina
// Este mÃ©todo tiene varias opciones, consulta la documentaciÃ³n para mÃ¡s informaciÃ³n.
        $pdf->AddPage();

        $pdf->SetFontSize(8);

        $textoheader = "";
        $pdf->writeHTMLCell(
            $w = 0, $h = 0, $x = '60', $y = '',
            $textoheader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'C', $autopadding = true);

//fijar efecto de sombra en el texto
//        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        $pdf->SetFontSize(12);

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE SUBGRUPOS</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);


        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";
        /*
                $html .= "<b>Apellidos y Nombres:</b>  " . $miembro['nombre'] . " " . $miembro['apellido'] . "<br>";
                $html .= "<b>Nacido el: </b> " . date('Y-m-d', strtotime($miembro['fecha_nac'])) . "     Edad:" . $this->utils->calcular_edad(date('Y-m-d', strtotime($miembro['fecha_nac']))) . "<br>";
                $html .= "<b>ProfesiÃ³n: </b> " . $profesion . "<br>";
                $html .= "<b>Grado Actual: </b> " . $miembro['grad_nombre'] . "<br><br>";
                $html .= "<b>Grados Alcanzados:</b> " . "<br>";
        */


        $html .= "<br><b>Subgrupos:</b> " . "<br>";


        $html .= "<table><tr><th>ID</th><th>Nombre</th></tr>";
        foreach ($subgrupos as $grupo) {
            $html .= "<tr><td>" . $grupo['id_subgrupo'] . "</td><td>" . $grupo['nombre_subgrupo'] . "</td></tr>";

        }
        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este mÃ©todo tiene varias opciones, consulte la documentaciÃ³n para mÃ¡s informaciÃ³n.
        $nombre_archivo = utf8_decode("ListaSubGrupos.pdf");
        $pdf->Output($nombre_archivo, 'D');


    }


    function excel()
    {


        $subgrupos = $this->subgrupos_model->get_subgrupos();

        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("Reporte de SubGrupos")
            ->setSubject("Reporte de SubGrupos")
            ->setDescription("Reporte de SubGrupos")
            ->setKeywords("Reporte de SubGrupos")
            ->setCategory("Reporte de SubGrupos");


        $columna_pdf[0] = "ID";
        $columna_pdf[1] = "NOMBRE";


        $col = 0;
        for ($i = 0; $i < count($columna_pdf); $i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna_pdf[$i]);

        }

        $row = 2;
        foreach ($subgrupos as $grupo) {
            $col = 0;

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $grupo['id_subgrupo']);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col + 1, $row, $grupo['nombre_subgrupo']);

            $row++;
        }

// Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('Reporte SubGrupos');


// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteSubGrupo.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }
}