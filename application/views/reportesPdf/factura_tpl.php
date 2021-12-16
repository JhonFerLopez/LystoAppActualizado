<?php
tcpdf();
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetTitle($invoice_title);
$pdf->setPrintHeader(false);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('helvetica', '', 9);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFont('helvetica', 'BI', 20);
$pdf->setPrintFooter(true);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


$pdf->AddPage();
# Logo
//$pdf->Image(base_url() . 'uploads/admin/img/' . $logo, 15, 5, 50);

# Invoice Status
$pdf->SetXY(0, 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(255);
$pdf->SetLineWidth(0.75);
$pdf->StartTransform();
$pdf->Rotate(-35, 100, 225);
/*if ($status == $this->lang->line('accepted')) {
    $pdf->SetFillColor(151, 223, 74);
    $pdf->SetDrawColor(110, 192, 70);
} elseif ($status == $this->lang->line('declined')) {
    $pdf->SetFillColor(200);
    $pdf->SetDrawColor(140);
} elseif ($status == $this->lang->line('pending')) {
    $pdf->SetFillColor(131, 182, 218);
    $pdf->SetDrawColor(91, 136, 182);
} elseif ($status == $this->lang->line('collections')) {
    $pdf->SetFillColor(3, 3, 2);
    $pdf->SetDrawColor(127);
} elseif ($status == $this->lang->line('draft')) {
    $pdf->SetFillColor(200);
    $pdf->SetDrawColor(140);

} elseif ($status == $this->lang->line('received')) {
    $pdf->SetFillColor(151, 223, 74);
    $pdf->SetDrawColor(110, 192, 70);
} else {
    $pdf->SetFillColor(223, 85, 74);
    $pdf->SetDrawColor(171, 49, 43);
}*/
//$pdf->Cell(100, 18, get_status($status), 'TB', 0, 'C', '1');
$pdf->StopTransform();
$pdf->SetTextColor(0);

# Company Details
$pdf->SetXY(15, 15);
$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(180, 4, trim($user_company), 0, 1, 'R');
if ($user_address) {
    $pdf->SetFont('helvetica', '', 9);
    if ($user_address) {
        $pdf->Cell(180, 2, trim($user_address), 0, 1, 'R');
    }
    if (isset($rep_legal)) {
        $pdf->Cell(180, 2, trim($rep_legal), 0, 1, 'R');
    }
    if (isset($nit)) {
        $pdf->Cell(180, 2, 'NIT '.trim($nit), 0, 1, 'R');
    }if (isset($telf_empresa)) {
        $pdf->Cell(180, 2, 'Tel '.trim($telf_empresa), 0, 1, 'R');
    }

    if (isset($regimen)) {
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(180, 2, trim($regimen), 0, 1, 'R');
    }
}
$pdf->Ln(2);

# Header Bar
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(239);
$pdf->Cell(0, 2, $page_title, 0, 1, 'L', '1');
$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(0, 2, 'Factura de venta Nº ' . ': ' . $invoice_numb, 0, 1, 'L', '1');

$pdf->SetXY(105, 46);
$pdf->Cell(0, 2, 'Vendedor' . ': ' . $vendedor, 0, 1, 'L', '1');

$pdf->SetXY(15, 50);
$pdf->Cell(0, 2, 'Fecha' . ': ' . $date, 0, 1, 'L', '1');
$pdf->SetXY(55, 50);
$pdf->Cell(0, 2, 'Hora' . ': ' . $hora, 0, 1, 'L', '1');
$pdf->SetXY(105, 50);
$pdf->Cell(0, 2, 'Cajero' . ': ' . $cajero_id, 0, 1, 'L', '1');

//$pdf->Cell(0, 6, $this->lang->line('due_date') . ': ' . $due_date, 0, 1, 'L', '1');
$pdf->Ln(3);

$startpage = $pdf->GetPage();

# Clients Details
$addressypos = $pdf->GetY();

$pdf->SetFont('helvetica', 'B', 10);
if ($client == true) {
    $pdf->Cell(0, 4, 'CLIENTE', 0, 1);
    $pdf->SetFont('helvetica', '', 9);

    if (isset($client_name)) {
        $pdf->Cell(0, 4, $client_name, 0, 1, 'L');
    }
    if ($client_cedula) {
        $pdf->Cell(0, 4, $client_cedula, 0, 1, 'L');
    }
    $pdf->Cell(0, 4, $address1, 0, 1, 'L');
    if (isset($cel_client)) {
        $pdf->Cell(0, 4, 'Celular: '.$cel_client, 0, 1, 'L');
    }  if (isset($fijo_client)) {
        $pdf->Cell(0, 4, 'Fijo: '.$fijo_client, 0, 1, 'L');
    }

}
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 8);

# Invoice Items
$tblhtml = '<table  width="100%" bgcolor="#ccc" cellspacing="1" cellpadding="2" border="0" >
    
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;text-align:center; ">
        <th width="15%" align="left">CODIGO</th>
        <th width="40%" align="left">PRODUCTO</th>
        <th width="5%" align="right">CNT</th>
        <th width="13%" align="right">PRECIO</th>
        <th width="13%" align="right">SUBTOTAL</th>
        <th width="13%" align="right">UM</th>
    </tr>';
//$tax_amount=0;
//$subtotal=0;
//$total = 0;
$count =0;
foreach ($ventas as $venta) {
    if (sizeof($venta['detalle_unidad']) > 0):

        foreach ($venta['detalle_unidad'] as $detalle_unidad) {
            $cantidad = $detalle_unidad['cantidad'];
            $um = isset($detalle_unidad['abreviatura']) ? $detalle_unidad['abreviatura'] : $detalle_unidad['nombre_unidad'];

            $subtotal = ($cantidad * $detalle_unidad['precio']);
            $tblhtml .= '
    <tr bgcolor="#fff">
        <td align="left" style="border-right: 1px solid #ccc;">' . nl2br($venta['producto_codigo_interno']) . '</td>
        <td align="left">' . nl2br($venta['nombre']) . '</td>
        <td align="right">' . nl2br($cantidad) . '</td>
        <td align="right" style="border-right: 1px solid #ccc;">' . nl2br(format_number($detalle_unidad['precio'])) . '</td>
        <td align="right">' . format_number($subtotal) . '</td>
        <td align="right">' . $um . '</td>
    </tr>';
            //$tax_amount=$tax_amount+($item->item_tax_rate*$item->item_quantity);
            //  $subtotal=$subtotal+($item->item_subtotal-($item->item_tax_rate*$item->item_quantity));
            //$total = $total +$item->item_subtotal;
        }
        $count++;
    endif;
}

$totaldescuentostablaveta = $ventas[0]['descuento_valor'] + $ventas[0]['descuento_porcentaje'];

$desct = $totaldescuentostablaveta > 0 ? $totaldescuentostablaveta : $ventas[0]['totaldescuento'];
$mostrardescuento = MONEDA . " " . number_format($desct, 2, ',', '.');



$tblhtml .= '<br break="true">
   <tfoot> <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Descuento: </td>
        <td align="right">' . $mostrardescuento . '</td>
        <td></td>
    </tr></tfoot>';


$subtotal = $ventas[0]['regimen_iva'] == 1 ? $ventas[0]['subTotal'] : $ventas[0]['montoTotal'] + $desct;
$tblhtml .= '
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Subtotal:</td>
        <td align="right">   ' .MONEDA." ". number_format($subtotal) . '</td>
    </tr>';
if ($ventas[0]['regimen_iva'] == 1) {
    $tblhtml .= '
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Excluido</td>
        <td align="right">' .MONEDA." ". format_number($ventas[0]['excluido']) . '</td>
    </tr><tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Gravado</td>
        <td align="right">' .MONEDA." ". format_number($ventas[0]['gravado']) . '</td>
    </tr>
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Total IVA</td>
        <td align="right">' .MONEDA." ". format_number($ventas[0]['impuesto']) . '</td>
    </tr>
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Otros impuestos</td>
        <td align="right">' .MONEDA." ". format_number($ventas[0]['total_otros_impuestos']) . '</td>
    </tr>';
}


$tblhtml .= '
    <tr height="30" bgcolor="#efefef" style="font-weight:bold;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="right">Total Factura </td>
        <td align="right">' .MONEDA." ". format_number($ventas[0]['montoTotal']) . '</td>
    </tr>';

$tblhtml .= '</table>';

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pdf->Ln(5);

# Notes
/*if ($notes) {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->MultiCell(170, 5, $this->lang->line('notes') . ': ' . $notes, 0, 'L', 0);
} else {
    $html = $terms;
    $pdf->writeHTML($html, true, false, false, false, '');
}
*/
# Generation Date
$pdf->SetFont('helvetica', '', 8);
$pdf->Ln(5);
$pdf->Cell(180, 4, 'FECHA DE IMPRESIÓN:' . ' ' . $current_date, '', '', 'C');

$pdf->Ln(5);
$pdf->Cell(180, 4, "AUTORIZACION NUMERACION SEGUN RESOLUCION No " . $ventas[0]['resolucion_numero'] . " del " . $ventas[0]['resolucion_fech_aprobacion'], '', '', 'C');
$pdf->Ln(5);
$pdf->Cell(180, 4, "DEL " . $ventas[0]['resolucion_prefijo'] . "-" . $ventas[0]['resolucion_numero_inicial'] . " AL " . $ventas[0]['resolucion_prefijo'] . "-" . $ventas[0]['resolucion_numero_final'], '', '', 'C');
$pdf->Ln(5);
//$pdf->Cell(180, 4, $mensaje_factura, '', '', 'C');

$pdf->writeHTML($mensaje_factura, true, false, false, false, '');
# Custum Footer
$pdf->SetFont('helvetica', '', 8);
$pdf->Ln(5);
/*$pdf->MultiCell(180, 4, '____________________________________', 0, 'C', 0);*/

$html = '<p style="text-align: LEFT;"><p>CAJA: ' . $cajero_id . ' </p>';
$html .= '<p style="text-align: LEFT;"><p>Recibido:_______________________________ </p>';
$html .= '<p style="text-align: LEFT;"><p>CC: :  _______________________________ </p>';


$nrt = array("\n", "\r", "\t");
$nrthtml = array("(n /)", "(r /)", "(t /)");





$pdf->writeHTML($html, true, false, false, false, '');

$pdf->Output($invoice_numb, 'I');