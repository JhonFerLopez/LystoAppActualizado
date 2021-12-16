<?php

class Migrate extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ingreso/ingreso_model');
        $this->load->model('opciones/opciones_model');
        $this->load->model('detalle_ingreso_unidad/detalle_ingreso_unidad_model');
    }

    public function index($version = NULL)
    {

        $result = array();
        try {

            if (!is_null($version)) {
                $resultado = $this->migration->version($version);
            } else {
                $resultado = $this->migration->latest();
            }

            if (!$resultado) {
                $result['result'] = 'error';
                $result['message']=$this->migration->error_string();

            } else {


                $result['result'] = 'success';
            }
        } catch
        (Exception $e) {
            log_message("error", "Error: Ha ocurrido un error en la migracion " . $e->getMessage());

            $result['result'] = 'error';
            $result['message'] = $e->getMessage();

        }

        if ($this->input->is_ajax_request()) {

            echo json_encode($result);
        } else {
          if( $result['result'] == 'error') {
              echo "Error ejecutando la migracion, por favor intente de nuevo ".$result['message'];
              echo $this->migration->error_string();
          }else{
              redirect('/');
          }

        }

    }

    public function pull(){

        $result = array();
        try {

                $command = sprintf(
                    "git pull"
                );

                $retval = $this->runCommand($command, $outputStr, $errorStr);
                if ($retval != 0) {
                    $result['result'] = 'error';

                    $result['message'] = 'No se ha podido ejecutar el pull del repositorio git ...'.$retval.": " . trim($errorStr) ;
                }else{

                    $command = sprintf(
                        "composer install"
                    );
                    $retval = $this->runCommand($command, $outputStr, $errorStr);

                    $command = sprintf(
                        "git rm --cached credentials.json"
                    );
                    $retval = $this->runCommand($command, $outputStr, $errorStr);

                    $this->migration->latest();

                    $result['result'] = 'success';

                }


        } Catch
        (Exception $e) {
            $result['result'] = 'error';

            $result['message'] = "Error al ejecutar el pull del repositorio git ...";
        }

        if ($this->input->is_ajax_request()) {

            echo json_encode($result);
        } else {
           var_dump($result);


        }
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



    public function undo_migration($version = NULL)
    {

        $migrations = $this->migration->find_migrations();
        $migration_keys = array();
        foreach ($migrations as $key => $migration) {
            $migration_keys[] = $key;
        }
        if (isset($version) && array_key_exists($version, $migrations) && $this->migration->version($version)) {
            echo 'The migration was reset to the version: ' . $version;
            exit;
        } elseif (isset($version) && !array_key_exists($version, $migrations)) {
            echo 'The migration with version number ' . $version . ' doesn\'t exist.';
        } else {
            $penultimate = (sizeof($migration_keys) == 1) ? 0 : $migration_keys[sizeof($migration_keys) - 2];
            if ($this->migration->version($penultimate)) {
                echo 'The migration has been rolled back successfully.';
                exit;
            } else {
                echo 'Couldn\'t roll back the migration.';
                exit;
            }
        }
    }

    public function reset_migration()
    {

        if ($this->migration->current() !== FALSE) {
            echo 'The migration was reset to the version set in the config file.';
            return TRUE;
        } else {
            echo 'Couldn\'t reset migration.';
            show_error($this->migration->error_string());
            exit;
        }
    }

	function newFile($nombre){

		$my_file = APPPATH."migrations/".date('YmdHis')."_$nombre.php";
		if(file_exists($my_file)==false) {
			$handle = fopen($my_file, "x") or die('Cannot open file:  ' . $my_file);
			$data = '<?php

class Migration_' . ucfirst($nombre) . ' extends CI_Migration
{
    public function up()
    {

    }

    public function down()
    {

    }
}';
			fwrite($handle, $data);
			$a=chmod($my_file, 0776);
			var_dump($a);
		}else{
			die('File exist: ' . $my_file);
		}
	}
}
