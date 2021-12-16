<?php

/***Esta clase me permite sobrescribir el manejo de exceptiones de codeiniger**/
class MY_Exceptions extends CI_Exceptions
{
    function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {

        log_message('debug', print_r($message, TRUE));
        throw new Exception(is_array($message) ? $message[1] : $message, $status_code);
    }
}