<?php

function tcpdf()
{
	//require_once('../libraries/tcpdf/config/lang/eng.php');
	require_once(APPPATH.'/libraries/Pdf.php');
}

function format_number($number)
{
    return number_format($number, 2, ',', '.');
}