<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class proveedor extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->very_sesion();

        $this->load->model('proveedor/proveedor_model');
        $this->load->model('tipo_proveedor/tipo_proveedor_model');
        $this->load->model('regimen/regimen_model');
        $this->load->model('pais/pais_model');
        $this->load->model('estado/estado_model');
        $this->load->model('ciudad/ciudad_model');
        $this->load->library('Pdf');
        $this->load->library('phpExcel/PHPExcel.php');
    }


    /** carga cuando listas los proveedores*/
    function index()
    {

        if ($this->session->flashdata('success') != FALSE) {
            $data ['success'] = $this->session->flashdata('success');
        }
        if ($this->session->flashdata('error') != FALSE) {
            $data ['error'] = $this->session->flashdata('error');
        }

        $data['proveedores'] = $this->proveedor_model->get_all();
        $dataCuerpo['cuerpo'] = $this->load->view('menu/proveedor/proveedor', $data, true);


        if ($this->input->is_ajax_request()) {
            echo $dataCuerpo['cuerpo'];
        } else {
            $this->load->view('menu/template', $dataCuerpo);
        }
    }

    function form($id = FALSE)
    {


        $data = array();
        $data['paises'] = $this->pais_model->get_all();
        $data['estados'] = $this->estado_model->get_all();
        $data['ciudades'] = $this->ciudad_model->get_all();
        $data['regimenes'] = $this->regimen_model->get_all();
        $data['tipos'] = $this->tipo_proveedor_model->get_all();
        if ($id != FALSE) {
            $data['proveedor'] = $this->proveedor_model->get_by('id_proveedor', $id);
        }

        $this->load->view('menu/proveedor/form', $data);
    }

    function guardar()
    {

        $id = $this->input->post('id');


        $proveedor = array(
            'proveedor_nombre' => strtoupper($this->input->post('proveedor_nombre')),
            'proveedor_direccion' => $this->input->post('proveedor_direccion'),
            'proveedor_email' => $this->input->post('proveedor_email'),
            'proveedor_telefono1' => $this->input->post('proveedor_telefono1'),
            'proveedor_telefono2' => $this->input->post('proveedor_telefono2'),
            'proveedor_tipo' => $this->input->post('proveedor_tipo')!=""?$this->input->post('proveedor_tipo'):null,
            'proveedor_regimen' => $this->input->post('proveedor_regimen')!=""?$this->input->post('proveedor_regimen'):null,
            'proveedor_digito_verificacion' => $this->input->post('proveedor_digito_verificacion'),
            'proveedor_identificacion' => $this->input->post('proveedor_identificacion'),
            'proveedor_celular' => $this->input->post('proveedor_celular'),
            'proveedor_ciudad' => $this->input->post('proveedor_ciudad')!=""?$this->input->post('proveedor_ciudad'):null,
            'latitud' => $this->input->post('latitud'),
            'longitud' => $this->input->post('longitud'),
        );

        if (empty($id)) {
            $resultado = $this->proveedor_model->insertar($proveedor);
        } else {
            $proveedor['id_proveedor'] = $id;
            $resultado = $this->proveedor_model->update($proveedor);
        }

        if ($resultado !==FALSE && $resultado!==CEDULA_EXISTE && $resultado!==CODIGO_EXISTE) {

            $json['success'] = 'Solicitud Procesada con exito';
        } else {
            $json['error'] = 'Ha ocurrido un error al procesar la solicitud';
        }

        if ($resultado === CEDULA_EXISTE || $resultado === CODIGO_EXISTE) {
            //  $this->session->set_flashdata('error', NOMBRE_EXISTE);
            $json['error'] = $resultado;
        }
        echo json_encode($json);

    }


    function eliminar()
    {
        $id = $this->input->post('id');
        $nombre = $this->input->post('nombre');

        $proveedor = array(
            'id_proveedor' => $id,
            'proveedor_nombre' => $nombre . time(),
            'deleted_at' => date('Y-m-d')

        );

        $data['resultado'] = $this->proveedor_model->softDelete($proveedor);

        if ($data['resultado'] != FALSE) {

            $json['success'] = 'Se ha eliminado exitosamente';


        } else {

            $json['error'] = 'Ha ocurrido un error al eliminar el Proveedor';
        }

        echo json_encode($json);
    }


    function pdf()
    {

        $clientes = $this->proveedor_model->get_all();

        //var_dump($miembro);
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('L');
        // $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('CLIENTES');
        // $pdf->SetSubject('FICHA DE MIEMBROS');
        $pdf->SetPrintHeader(false);
//echo K_PATH_IMAGES;
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "AL.�?�.G.�?�.D.�?�.G.�?�.A.�?�.D.�?�.U.�?�.<br>Gran Logia de la República de Venezuela", "Gran Logia de la <br> de Venezuela", array(0, 64, 255), array(0, 64, 128));


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

//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);

// Establecer el tipo de letra

//Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
// Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('helvetica', '', 14, '', true);

// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
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

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', "<br><br><b><u>LISTA DE PROVEEDORES</u></b><br><br>", $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);


        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #000; font-weight: bold; background-color: #CED6DB; }";
        $html .= "td{color: #222; font-weight: bold; background-color: #fff;}";
        $html .= "table{border:0.2px}";
        $html .= "body{font-size:15px}";
        $html .= "</style>";


        $html .= "<table><tr>";
        $html .= "<th>ID</th><th>Codigo</th><th>Nombres</th><th>Telf 1</th>";
        $html .= "<th>Celular</th>";
        $html .= "<th>Direccion</th>";
        $html .= "</tr>";
        foreach ($clientes as $familia) {
            $html .= "<tr><td>" . $familia['id_proveedor'] . "</td>";
            $html .= "<td>" . $familia['proveedor_identificacion'] . "</td>";
            $html .= "<td>" . $familia['proveedor_nombre'] . "</td>";
            $html .= "<td>" . $familia['proveedor_telefono1'] . "</td>";
            $html .= "<td>" . $familia['proveedor_celular'] . "</td>";
            $html .= "<td>" . $familia['proveedor_direccion'] . "</td>";
            $html .= "</tr>";

        }
        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Proveedores.pdf");
        $pdf->Output($nombre_archivo, 'D');


    }

    function excel()
    {



        $clientes = $this->proveedor_model->get_all();


        $columna_pdf[0] = "ID";
        $columna_pdf[1] = "Código";
        $columna_pdf[2] = "Nombre";
        $columna_pdf[3] = "Telf1";
        $columna_pdf[3] = "Celular";
        $columna_pdf[4] = "Dirección";




        // configuramos las propiedades del documento
        $this->phpexcel->getProperties()
            //->setCreator("Arkos Noem Arenom")
            //->setLastModifiedBy("Arkos Noem Arenom")
            ->setTitle("Stock")
            ->setSubject("Stock")
            ->setDescription("Stock")
            ->setKeywords("Stock")
            ->setCategory("Stock");

        $this->phpexcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('I')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('J')->setAutoSize('true');
        $this->phpexcel->getActiveSheet()->getColumnDimension('K')->setAutoSize('true');
        $c = 0;
        foreach ($columna_pdf as $col) {

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($c, 1, $col);
            $c++;

        }
        $col = 0;
        $row = 2;
        foreach ($clientes as $cliente) {
            $col = 0;

            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['id_proveedor']);
            $col++;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['proveedor_identificacion']);
            $col++;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['proveedor_nombre']);
            $col++;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['proveedor_telefono1']);
            $col++;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['proveedor_celular']);
            $col++;
            $this->phpexcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $cliente['proveedor_direccion']);
            $col++;


            $row++;
        }


// Renombramos la hoja de trabajo
        $this->phpexcel->getActiveSheet()->setTitle('Lista Stock');


// configuramos el documento para que la hoja
// de trabajo nÃºmero 0 sera la primera en mostrarse
// al abrir el documento
        $this->phpexcel->setActiveSheetIndex(0);


// redireccionamos la salida al navegador del cliente (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proveedores.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save('php://output');

    }


}