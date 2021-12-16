<?php
/**
 * Created by PhpStorm.
 * User: Jhainey
 * Date: 04/04/2015
 * Time: 0:59
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{

    public $footer_align = 'C';      //center align footer by default
    public $footer_line = false;     //set to true to display a line above the footer
    public $footer_text = 'hola';        //text to display in footer
    function __construct()
    {
        parent::__construct();
    }

    public function Header()
    {
        $cur_y = $this->y;
        $this->SetTextColorArray($this->footer_text_color);
        //set style for cell border
        $line_width = (0 / $this->k);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));
        //print document barcode
        $barcode = $this->getBarcode();
        if (!empty($barcode)) {
            $this->Ln($line_width);
            $barcode_width = round(($this->w - $this->original_lMargin - $this->original_rMargin) / 3);
            $style = array(
                'position' => $this->rtl ? 'R' : 'L',
                'align' => $this->rtl ? 'R' : 'L',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'padding' => 0,
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
                'text' => false
            );
            $this->write1DBarcode($barcode, 'C128', '', $cur_y + $line_width, '', (($this->footer_margin / 3) - $line_width), 0.3, $style, '');
        }
        $w_page = isset($this->l['w_page']) ? $this->l['w_page'] . ' ' : '';
        if (empty($this->pagegroups)) {
            $pagenumtxt = 'Pag. ' . $w_page . $this->getAliasNumPage() . 'de' . $this->getAliasNbPages();
        } else {
            $pagenumtxt = 'Pag. ' . $w_page . $this->getPageNumGroupAlias() . ' de ' . $this->getPageGroupAlias();
        }
        $this->SetY($cur_y);
        //Print page number
        if ($this->getRTL()) {
            $this->SetX($this->original_rMargin);
            $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
        } else {
            $this->SetX($this->original_lMargin);
            $this->Cell(0, 0, $this->getAliasRightShift() . $pagenumtxt, 'T', 0, 'R');
        }
    }

    // Page footer
   /* public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }*/


}