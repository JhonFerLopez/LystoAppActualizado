<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ubicacion_fisica extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->model('caja/caja_model','c');
        $this->load->model('ubicacion_fisica/ubicacion_fisica_model');
        //$this->load->library('Pdf');
        //$this->load->library('phpExcel/PHPExcel.php');

        $this->very_sesion();
    }



    function index(){
        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['marcas']=$this->ubicacion_fisica_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/ubicacion_fisica/tabla',$data, true);
        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        }else{
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {

        $data = array();
        if ($id != FALSE) {
            $data['ubicacion_fisica'] = $this->ubicacion_fisica_model->get_by(array('ubicacion_id'=> $id));
        }


        $this->load->view('menu/ubicacion_fisica/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');
        $datos = array(
            'ubicacion_nombre' => strtoupper($nombre)
        );
        if (empty($id)) {
            $data['resultado'] = $this->ubicacion_fisica_model->set($datos);
        } else {
            $datos['ubicacion_id'] = $id;
            $data['resultado'] = $this->ubicacion_fisica_model->update($datos);
        }

        if ($data['resultado'] != FALSE) {

          //  $this->session->set_flashdata('success', 'Solicitud Procesada con exito');
            $json['success']= 'Solicitud Procesada con exito';

        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al procesar la solicitud');
            $json['error']= 'Ha ocurrido un error al procesar la solicitud';
        }

        if($data['resultado']===NOMBRE_EXISTE){
          //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error']= NOMBRE_EXISTE;
        }

        if(!isset($json['error'])){

            $this->db->cache_delete( 'ubicacion_fisica' ,'index');
        }
        echo json_encode($json);
    }

    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $grupo = array(
            'ubicacion_id' => $id,
            'ubicacion_nombre' => $nombre,
            'deleted_at' =>date('Y-m-d h:i:s')

        );

        $data['resultado'] = $this->ubicacion_fisica_model->update($grupo, 'eliminar');

        if ($data['resultado'] != FALSE) {

            //$this->session->set_flashdata('success', 'Se ha Eliminado exitosamente');
            $json['success']= 'Se ha eliminado exitosamente';


        } else {

            //$this->session->set_flashdata('error', 'Ha ocurrido un error al eliminar la marca');
            $json['error']= 'Ha ocurrido un error al eliminar la ubicacoin';
        }

        if(!isset($json['error'])){

            $this->db->cache_delete( 'ubicacion_fisica' ,'index');
        }

        echo json_encode($json);
    }


    function pdf()
    {

        $marcas= $this->ubicacion_fisica_model->get_all();

        //var_dump($miembro);
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('P');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('GRUPOS');
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

        $textoheader ="";
        $pdf->writeHTMLCell(
            $w = 0, $h = 0, $x = '60', $y = '',
            $textoheader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'C', $autopadding = true);

//fijar efecto de sombra en el texto
//        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        $pdf->SetFontSize(12);

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE UBICACIONES</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);



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


        $html .= "<br><b>Marcas:</b> " . "<br>";



        $html .= "<table><tr><th>ID</th><th>Nombre</th></tr>";
        foreach ($marcas as $marca) {
            $html .= "<tr><td>" . $marca['ubicacion_id'] . "</td><td>" . $marca['ubicacion_nombre'] . "</td></tr>";

        }
        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este mÃ©todo tiene varias opciones, consulte la documentaciÃ³n para mÃ¡s informaciÃ³n.
        $nombre_archivo = utf8_decode("ReporteUbicaciones.pdf");
        $pdf->Output($nombre_archivo, 'D');


    }

    function excel(){



        $marcas = $this->ubicacion_fisica_model->get_all();

        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("Reporte de Ubicaciones")
            ->setSubject("Reporte de Ubicaciones")
            ->setDescription("Reporte de Ubicaciones")
            ->setKeywords("Reporte de Ubicaciones")
            ->setCategory("Reporte de Ubicaciones");



        $columna_pdf[0]="ID";
        $columna_pdf[1]="NOMBRE";

        $col = 0;
        for($i=0;$i<count($columna_pdf);$i++) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($i, 1, $columna_pdf[$i]);

        }

        $row = 2;
        foreach ($marcas as $marca) {
            $col = 0;

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $marca['ubicacion_id']);

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col+1, $row, $marca['ubicacion_nombre']);
            $col++;
            $row++;
        }

        // Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('Reporte Ubicaciones');


        // configuramos el documento para que la hoja
        // de trabajo nÃºmero 0 sera la primera en mostrarse
        // al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


        // redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteUbicaciones.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    /*solo retorna los tipos en json*/
    function getUbicacionJson()
    {
        $json=$this->ubicacion_fisica_model->get_all();
        echo json_encode($json);
    }


}