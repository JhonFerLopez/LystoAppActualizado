<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class NuSoap_lib{
    function __construct(){
        require_once(str_replace("\\","/",APPPATH).'libraries/NuSOAP/src/nusoap.php');
        //Por si estamos ejecutando este script en un servidor Windows
    }
}
?>