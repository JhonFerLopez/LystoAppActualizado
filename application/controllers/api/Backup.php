<?php

// Api Rest
require(APPPATH . '/libraries/REST_Controller.php');

class backup extends REST_Controller
{
    protected $uid = null;

    protected $methods = array(
        'index_get' => array('level' => 0),
        'ver_get' => array('level' => 0, 'limit' => 10),
        'create_get' => array('level' => 1),
        'update_get' => array('level' => 1, 'limit' => 5),
    );

    function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->model('banco/banco_model', 'banco');
        $this->load->library('form_validation');

        $this->load->model('api/api_model', 'api');

        $this->load->model('opciones/opciones_model');
        $this->load->model('usuario/usuario_model');
        $this->load->model('pais/pais_model');
        $this->load->model('local/local_model');
        $this->load->helper('download');
        $this->load->helper('file');

        $this->very_auth();
    }

    function very_auth()
    {
        // Request Header
        $reqHeader = $this->input->request_headers();

        // Key
        $key = null;
        if (isset($reqHeader['X-api-key'])) {
            $key = $reqHeader['X-api-key'];
        } else if ($key_get = $this->get('x-api-key')) {
            $key = $key_get;
        } else if ($key_post = $this->post('x-api-key')) {
            $key = $key_post;
        } else {
            $key = null;
        }

        // Auth ID
        $auth_id = $this->api->getAuth($key);

        if (!empty($auth_id)) {
            $this->uid = $auth_id;
        } else {
            $this->uid = null;
        }
    }

    //este es el metodo que va a invocar el cronjob
    function backupdrive_get()
    {
        $this->load->library('curl');
        //generp el backup
        $json = $this->generatebackupDrive();
        if (isset($json['file'])) {

            // Simple call to remote URL
            $credenciales= $this->curl->simple_get('http://prosode.com/sid_central/api/backup/getTokend');
            $json['drive'] = $this->obtenerCodigo($json['file'],$credenciales);

        }
        echo json_encode($json);

    }

    function obtenerCodigo($nombrebackup,$credenciales)
    {

        //obtengo el token y configuro
        $client = $this->getClient($credenciales);
        $service = new Google_Service_Drive($client);
        //hago la operacion
        $guardar = $this->insertFile($service, $nombrebackup);
        if($guardar==true){
            //si se genero bien, borro el backup de hace dos dias
            $fecha = date('d-m-Y');
            $fecha = date('d-m-Y', strtotime($fecha." - 2day"));
            $fecha = date('d-m', strtotime($fecha));
            if(is_file('.' .RUTA_BACKUP .'sid_' . $fecha . '.zip')){
                unlink('.' .RUTA_BACKUP .'sid_' . $fecha . '.zip');
            }

        }
        return $guardar;

    }

    function getClient($credenciales)
    {

        //la url a la que va a redieccionar cuando haya que refrescar el token
        $redirect_uri = base_url() . 'opciones/obtenerCodigo';

        $client = new Google_Client();
        //$client->setApplicationName("Prueba Drive");

        //obtengo los credenciales que ya guarde en la bd
        $credenciales = json_decode($credenciales, true);
        $credenciales = (array)$credenciales;
        $credenciales = $credenciales['config_value'];
        //obtengo los datos de la aplicacion prosode
        $cliente_secret = $this->opciones_model->getByKey("CLIENTE_SECRET_DRIVE");
        $cliente_secret = json_decode($cliente_secret['config_value'], true);
        $cliente_secret = (array)$cliente_secret;

        $client->setAuthConfig($cliente_secret);
        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setRedirectUri($redirect_uri);
        $client->setIncludeGrantedScopes(true);
        $client->setSubject('prosodesas@gmail.com');
        // $client->setIncludeGrantedScopes(true);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $api_key = "AIzaSyAmS8W0sxmMVrQneG2mZMpyvTcJ1b0OFNY";
        $client->setDeveloperKey($api_key);

        $accessToken = $credenciales;
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {


            // save refresh token to some variable
            $refreshTokenSaved = $client->getRefreshToken();

            // update access token
            $client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);

            // pass access token to some variable
            $accessTokenUpdated = $client->getAccessToken();

            // append refresh token
            $accessTokenUpdated['refresh_token'] = $refreshTokenSaved;

            //Set the new acces token
            $accessToken = $refreshTokenSaved;
            $client->setAccessToken($accessToken);

            //actualizo los credenciales en la BD del token
            $configuraciones = array(
                'config_value' => json_encode($accessTokenUpdated)
            );
            $where = array(
                'config_key' => "CREDENCIALES_DRIVE",
            );
            $this->opciones_model->update($where, $configuraciones);

        }
        return $client;
    }

    function generatebackupDrive()
    {

        //esta me genera el backup pero me retorna un arreglo sin el echo json
        $this->load->helper('file');
        $json = array();
        // $ruta = "C:\\xampp\\htdocs\\sid";

        $ruta = '.' . RUTA_BACKUP;

        if (!is_dir('./uploads/')) {
            mkdir('./uploads/', 0755);
        }
        if (!is_dir($ruta)) {
            mkdir($ruta, 0755);

            $htaccess = '<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>';

            $fichero = $ruta . '.htaccess';
            file_put_contents($fichero, $htaccess, FILE_APPEND | LOCK_EX);
        }

        $prefs = array(
            'tables' => array(),  // Array of tables to backup.
            'ignore' => array(),           // List of tables to omit from the backup
            'format' => 'zip',             // gzip, zip, txt
            'add_drop' => FALSE,              // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE,              // Whether to add INSERT data to backup file
            'newline' => "\n",               // Newline character used in backup file

        );
        $backup = $this->dbutil->backup($prefs);
        $fecha = date('d-m');
        $this->load->helper('file');
        write_file($ruta . 'sid_' . $fecha . '.zip', $backup);

        $nombre_backup = 'sid_' . $fecha . '.zip';
        if ($ruta != "") {
            if (file_exists($ruta . 'sid_' . $fecha . '.zip')) {
                $json['success'] = "El backup ha sido generado con éxito en la ruta especificada en configuraciones";
                $json['file'] = $nombre_backup;

                // $this->obtenerCodigo();
            } else {
                $json['error'] = "ha ocurrido un error al descargar el backup";
            }
        } else {
            $json['error'] = "Debe configurar una ruta en configuraciones generales, para poder generar el backup";
        }

        return $json;
    }


    function insertFile($service, $nombrebackup)
    {


        try {

            //aqui me lista la aplicacion
            //https://console.developers.google.com/apis/credentials?project=plexiform-armor-204312

            //https://developers.google.com/drive/v3/web/folder
            //https://developers.google.com/drive/v3/web/resumable-upload
            //https://questionfocus.com/google-drive-php-api-simple-file-upload.html
            //https://developers.google.com/drive/v3/web/folder
            //https://developers.google.com/api-client-library/php/auth/api-keys
            //https://developers.google.com/drive/api/v3/about-auth

            //busco el id de la carpeta backup
            $id_folder = $this->opciones_model->getByKey("ID_BACKUP_DRIVE");

            if ($id_folder['config_value'] == null || $id_folder['config_value'] == "") {

                $folderId = $this->crearCapetaDrive($service);
            } else {
                $folderId = $id_folder['config_value'];
            }

            //hago la validacion para ver si toca borrar los archivos o no
            $this->validarBorrarBackup($service, $folderId);

            /*$files = array();
            $dir = dir(RUTA_BACKUP);//ruta donde se crean los backup
            while ($file = $dir->read()) {
                if ($file != '.' && $file != '..') {
                    $files[] = $file;
                }
            }
            $dir->close();*/

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file = new Google_Service_Drive_DriveFile(
                array('parents' => array($folderId)) //el id de la carpeta,
            // se obtiene ingresando a la url d ela capetael ultimo parametro
            );

            $file_path = '.' .RUTA_BACKUP . $nombrebackup;
            $mime_type = finfo_file($finfo, $file_path);
            $file->setName($nombrebackup);
            //$file->setDescription('This is a '.$mime_type.' document');
            $file->setMimeType($mime_type);
            $service->files->create(
                $file,
                array(
                    'data' => file_get_contents($file_path)
                )
            );

            finfo_close($finfo);

            return true;
        } catch (Exception $e) {
            return "error: " . $e->getMessage();
        }
    }


    function validarBorrarBackup($service, $folderId)
    {

        // busco los backup dentro de la carpeta de este cliente
        $optParams = array(
            'pageSize' => 150,
            'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
            'q' => "'" . $folderId . "' in parents"
        );
        $results = $service->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            //print "No files found.\n";
        } else {
            //print "Files:\n";

            $fechaborrar= date("Y-m-d", strtotime(date('Y-m-d') . ' -2 day'));

            $nombredosdiasantes = "sid_" . date("d-m", strtotime($fechaborrar)).".zip";


            foreach ($results->getFiles() as $file) {
                //si el nombre del archivo es igual al afecha del mes pasado, lo borro
                if ($file->getName() == $nombredosdiasantes) {
                    $service->files->delete($file->getId());
                }
                //printf("%s (%s)\n", $file->getName(), $file->getId());
            }
        }

    }


    function crearCapetaDrive($service)
    {

        $ParentfolderId = "128bPOJwXKw9LZflER19mEmPbHKiS4xZE"; //aqui va el id de la carpeta principal llamada backup o como se llame


        $nombreEmpresa = $this->opciones_model->getByKey("EMPRESA_NOMBRE");
        $DriveFolderName = $nombreEmpresa['config_value']; //el nombre de la carpeta sera el nombre de la empresa

        $folder_mime = "application/vnd.google-apps.folder"; //el tipo para la carpeta
        $folder = new Google_Service_Drive_DriveFile(array
        ('parents' => array($ParentfolderId),//le digo que se va a guardar dentro de la carpeta principal, o backup
            'name' => $DriveFolderName));
        //$folder->setTitle($folder_name);
        $folder->setDescription($nombreEmpresa);
        $folder->setMimeType($folder_mime);

        // $parent = new Google_Service_Drive_ParentReference();
        $folder->setParents(array($ParentfolderId));

        $newFolder = $service->files->create($folder);

        //actualizo el id de la carpeta retornada de google drive para no tener que crearla nuevamente
        $configuraciones = array(
            'config_value' => $newFolder['id']
        );
        $where = array(
            'config_key' => "ID_BACKUP_DRIVE",
        );
        $this->opciones_model->update($where, $configuraciones);

        return $newFolder['id'];


    }


}