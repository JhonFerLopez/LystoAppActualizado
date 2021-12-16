<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class server extends MY_Controller
{


    /**
     * @var int $platform
     *  Platform we're running on, for selecting different commands. See PLATFORM_* constants.
     */
    private $platform;
    /**
     * Represents Linux
     */
    const PLATFORM_LINUX = 0;

    /**
     * Represents Mac
     */
    const PLATFORM_MAC = 1;

    /**
     * Represents Windows
     */
    const PLATFORM_WIN = 2;

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
        $this->very_sesion();
    }


    function index()
    {


        $dataCuerpo['cuerpo'] = $this->load->view('menu/server/index', array(), true);

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

    function shutdown()
    {
        $this->platform = $this->getCurrentPlatform();
        $result['result'] = 'success';


        $this -> buffer = array();
        try {
            if ($this->platform == self::PLATFORM_WIN) {
                $result['result'] = 'Todavia no se puede apagar el sistema en windows';
            } elseif ($this->platform == self::PLATFORM_LINUX) {

                $command = sprintf(
                    "sudo /sbin/shutdown -h now"
                );

                $retval = $this->runCommand($command, $outputStr, $errorStr);
                if ($retval != 0) {

                    $result['result'] = 'No se ha podido apagar el servidor...'.$retval.": " . trim($errorStr) ;
                }
            } else {
                $result['result'] = 'Todavia no se puede apagar el sistema en mac';
            }

        } Catch
        (Exception $e) {
            $result['result'] = "No se ha podido apagar el servidor...";
        }

        echo json_encode($result);
    }

    function renovarLicencia()
    {

        $configuraciones[] = array(
            'config_key' => 'SYS_EXP_DAT',
            'config_value' => $this->input->post('date')
        );


        $result['result'] = 'success';


        $this -> buffer = array();
        try {
         $resultado =  $this->opciones_model->guardar_configuracion($configuraciones);

         if(!$resultado){
             $result['result'] = "Ha ocurrido un error";
         }
        } Catch
        (Exception $e) {
            $result['result'] = "Ha ocurrido un error";
        }

        echo json_encode($result);
    }

    protected function runCommand($command, &$outputStr, &$errorStr, $inputStr = null)
    {
        $descriptors = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );
        $process = proc_open($command, $descriptors, $fd);
        //stream_set_blocking($fd[2], 0);
        if (is_resource($process)) {
            /* Write to input */
            if ($inputStr !== null) {
                fwrite($fd[0], $inputStr);
            }
            fclose($fd[0]);
            /* Read stdout */
            $outputStr = stream_get_contents($fd[1]);
            fclose($fd[1]);
            /* Read stderr */
            $errorStr = stream_get_contents($fd[2]);
            fclose($fd[2]);
            /* Finish up */
            $retval = proc_close($process);
            return $retval;
        } else {
            /* Method calling this should notice a non-zero exit and print an error */
            return -1;
        }
    }


    /**
     * @return string Current platform. Separated out for testing purposes.
     */
    protected function getCurrentPlatform()
    {
        if (PHP_OS == "WINNT") {
            return self::PLATFORM_WIN;
        }
        if (PHP_OS == "Darwin") {
            return self::PLATFORM_MAC;
        }
        return self::PLATFORM_LINUX;
    }
}