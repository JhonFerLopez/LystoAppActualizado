<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');



class Request
{
    function __construct()
    {
        require_once 'Requests/library/Requests.php';
        Requests::register_autoloader();
    }
}

?>