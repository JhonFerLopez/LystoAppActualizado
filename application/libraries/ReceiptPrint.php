<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// IMPORTANT - Replace the following line with your path to the escpos-php autoload script
require_once __DIR__ . '/escpos-php-development/autoload.php';
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector2;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class ReceiptPrint
{
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

    private $CI;
    private $connector;
    private $printer;
    // TODO: printer settings
    // Make this configurable by printer (32 or 48 probably)
    private $printer_width = 32;

    function __construct()
    {
        $this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->...

    }

    function connectEthernet($ip_address, $port)
    {
        $this->connector = new NetworkPrintConnector($ip_address, $port);


        $this->printer = new Printer($this->connector);

        return $this->printer;
    }
    function connectFile($ip_address, $port)
    {
        $this->connector = new FilePrintConnector('\\\\1p_printserv8e7\lp1');


        $this->printer = new Printer($this->connector);

        return $this->printer;
    }

    function connectUsb($address,$username, $password, $workgroup, $platform=2)
    {

        if(empty($platform)){
            $platform =2;
        }

        if ($platform == self::PLATFORM_WIN) {
            //$this->connector = new WindowsPrintConnector2("generic5");
            $this->connector = new WindowsPrintConnector($address, $username, $password, $workgroup);
            // $this->connector  = null;;
        }else if($platform == self::PLATFORM_LINUX){

            //dev/usb/lp0
            $this->connector = new FilePrintConnector($address);
        }else{
            $this->connector = new WindowsPrintConnector($address, $username, $password, $workgroup);
        }

        $this->printer = new Printer($this->connector);

        return $this->printer;
    }

    function connectLPT($address,$username, $password, $workgroup)
    {
        $this->connector = new WindowsPrintConnector($address, $username, $password, $workgroup);
        $this->printer = new Printer($this->connector);
        return $this->printer;
    }

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

    /**
     * Install the printer using USB printing support, and the "Generic / Text Only" driver,
     * then share it (you can use a firewall so that it can only be seen locally).
     *
     * Use a WindowsPrintConnector with the share name to print.
     *
     * Troubleshooting: Fire up a command prompt, and ensure that (if your printer is shared as
     * "Receipt Printer), the following commands work:
     *
     *  echo "Hello World" > testfile
     *  copy testfile "\\%COMPUTERNAME%\Receipt Printer"
     *  del testfile
     */

    function usbWindowsPrint($impresora)
    {
        // Enter the share name for your USB printer here
        $connector = null;
        $connector = new WindowsPrintConnector($impresora);
        /* Print a "Hello world" receipt" */
        $printer = new Printer($connector);
        $printer->text("Hello World!\n");
        $printer->cut();

        /* Close printer */
        $printer->close();
    }

    private function check_connection()
    {
        if (!$this->connector OR !$this->printer OR !is_a($this->printer, 'Mike42\Escpos\Printer')) {
            throw new Exception("Tried to create receipt without being connected to a printer.");
        }
    }

    public function close_after_exception()
    {
        if (isset($this->printer) && is_a($this->printer, 'Mike42\Escpos\Printer')) {
            $this->printer->close();
        }
        $this->connector = null;
        $this->printer = null;
        $this->emc_printer = null;
    }

    // Calls printer->text and adds new line
    private function add_line($text = "", $should_wordwrap = true)
    {
        $text = $should_wordwrap ? wordwrap($text, $this->printer_width) : $text;
        $this->printer->text($text . "\n");
    }

    public function print_test_receipt($text = "")
    {
        $this->check_connection();
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->add_line("TESTING");
        $this->add_line("Receipt Print");
        $this->printer->selectPrintMode();
        $this->add_line(); // blank line
        $this->add_line($text);
        $this->add_line(); // blank line
        $this->add_line(date('Y-m-d H:i:s'));
        $this->printer->cut(Printer::CUT_PARTIAL);
        $this->printer->close();
    }

}