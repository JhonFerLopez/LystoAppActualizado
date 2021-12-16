<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code





define('INGRESO_PENDIENTE', 'PENDIENTE');
define('INGRESO_COMPLETADO', 'COMPLETADO');
define('PAGO_CANCELADO', 'PAGO CANCELADO');
define('INGRESO_ANULADO', 'ANULADO');
define('INGRESO_DEVUELTO', 'DEVUELTO');
define('ANULACION_COMPRA', 'COMPRA ANULADA');
define('INGRESO_ELIMINADO', 'ELIMINADO');

define('INGRESO', 'INGRESO');
define('INGRESO_DEVOLUCION', 'DEVOLUCION DE INGRESO');
define('VENTA_DEVOLUCION', 'DEVOLUCION DE VENTA');
define('NOTA_CREDITO', 'NOTA DE CREDITO');
define('NOTA_DEBITO', 'NOTA DE DEBITO');
define('VENTA_EDICION', 'MODIFICACION DE VENTA');
define('PEDIDO_DEVUOLUCION', 'LIQUIDACION DE PEDIDO DEVUELTO PARCALMENTE');
define('PEDIDO_EDICION', 'MODIFICACION DE PEDIDO');
define('PEDIDO_RECHAZO', 'LIQUIDACION DE PEDIDO RECHAZADO');
define('AJUSTE_INVENTARIO', 'AJUSTE DE INVENTARIO');


define('VENTA', 'VENTA');
define('ENTRADA', 'ENTRADA');
define('SALIDA', 'SALIDA');

define('NOTAVENTA',		'NOTA DE PEDIDO');
define('BOLETAVENTA',		'BOLETA DE VENTA');
define('FACTURA',		'FACTURA');
define('NOTA_ENTREGA', 'NOTA DE ENTREGA');

define('DONACION',		'DONACION');
define('COMPRA',		'COMPRA');

define('COMPRA_A_CONTADO',		'COMPRA A CONTADO');
define('COMPRA_A_CREDITO',		'COMPRA A CREDITO');
define('COMPRA_A_',		'COMPRA A ');
define('INGRESO_OBSEQUIO',		'INGRESO DE MERCANCIA OBSEQUIO');
define('INGRESO_PREPACK',		'INGRESO DE MERCANCIA PREPACK');
define('SALIDA_OBSEQUIO',		'SALIDA POR OBSEQUIO');
define('SALIDA_PREPACK',		'SALIDA POR PREPACK');
define('MODIFICACION_COMPRA',		'MODIFICACION DE COMPRA');

define('ENTRADA_MIGRACION',		'ENTRADA MIGRACION');


define('TRASLADO_MERCANCIA',		'TRASLADO DE MERCANCIA');


define('PESABLE',		'PESABLE');
define('MEDIBLE',		'MEDIBLE');

define('MONEDA',		'$');
/* End of file constants.php */
/* Location: ./application/config/constants.php */


/******CONFIGURACIONES************/

define('EMPRESA_NOMBRE',		'EMPRESA_NOMBRE');
define('EMPRESA_DIRECCION',		'EMPRESA_DIRECCION');
define('EMPRESA_TELEFONO',		'EMPRESA_TELEFONO');
define('MONTO_BOLETAS_VENTA', 'MONTO_BOLETAS_VENTA');

define('DATABASE_IP',		'DATABASE_IP');
define('DATABASE_NAME',		'DATABASE_NAME');
define('DATABASE_USERNAME',		'DATABASE_USERNAME');
define('DATABASE_PASWORD',		'DATABASE_PASWORD');
define('MONEDA_OPCION', 'MONEDA');
define('REFRESCAR_PEDIDOS_OPCION', 'REFRESCAR_PEDIDOS');
define('EMPRESA_PAIS', 'EMPRESA_PAIS');
define('MENSAJE_FACTURA', 'MENSAJE_FACTURA');

define('CALCULO_PRECIO_VENTA', 'CALCULO_PRECIO_VENTA');
define('CORRELATIVO_PRODUCTO', 'CORRELATIVO_PRODUCTO');
define('REGIMEN_CONTRIBUTIVO', 'REGIMEN_CONTRIBUTIVO');
define('REPRESENTANTE_LEGAL', 'REPRESENTANTE_LEGAL');
define('CODIGO_COOPIDROGAS', 'CODIGO_COOPIDROGAS');
define('MOSTRAR_SIN_STOCK', 'MOSTRAR_SIN_STOCK');
define('NIT', 'NIT');
define('RUTA_BACKUP',"/uploads/backups/");
define('RUTA_COTIZACIONES',"/uploads/cotizaciones/");

define('ACTUALIZO_DETALLE_UNIDAD_COMPRAS',"ACTUALIZO_DETALLE_UNIDAD_COMPRAS");


define('NOMBRE_EXISTE',		'El nombre ingresado ya existe');
define('CODIGO_EXISTE',		'El codigo ingresado ya existe');
define('CEDULA_EXISTE',		'La identificacion ingresada ya existe');
define('USERNAME_EXISTE',		'El username ingresado ya existe');
define('CAMION_EXISTE', 'La placa ingresada ya existe');

/// constantes de estatus de las ventas a credito
define('CREDITO_DEBE', 'DEBE');
define('CREDITO_ACUENTA', 'A_CUENTA');
define('CREDITO_NOTACREDITO', 'NOTA_CREDITO');
define('CREDITO_CANCELADO', 'CANCELADA');

define('VENTA_CREDITO', 'VENTA A CREDITO');
define('VENTA_CONTADO', 'VENTA A CONTADO');


//////////////////////////////////////


///////// constantes de tipos de metodos de pago
define('METODO_BANCO', 'BANCO');
define('METODO_CAJA', 'CAJA');
////////////////////////////

define('REFRESCAR_PEDIDOS', '100000');

define('VENTA_ENTREGA', 'ENTREGA');
define('VENTA_CAJA', 'CAJA');

//VENTA
define('COMPLETADO',		'COMPLETADO');
define('ESPERA',		'EN ESPERA');

//ANTES DE PEDDO PERO TAMBIEN VENTAS
define('PEDIDO_ENTREGADO', 'ENTREGADO');
define('PEDIDO_ANULADO', 'ANULADO');
define('PEDIDO_ENVIADO', 'ENVIADO');
define('PEDIDO_RECHAZADO', 'RECHAZADO');
define('PEDIDO_GENERADO', 'GENERADO');
define('PEDIDO_ELIMINADO', 'ELIMINADO');
define('PEDIDO_DEVUELTO', 'DEVUELTO');

define('COSTO_UNITARIO', 'COSTO_UNITARIO');
define('COSTO_PROMEDIO', 'COSTO_PROMEDIO');

//DOMICILIOS ESTATUS
define('DOMICILIO_ASIGNADO', 'ASIGNADO');
define('DOMICILIO_ESPERA', 'EN ESPERA');
define('DOMICILIO_CANCELADO', 'CANCELADO');
define('DOMICILIO_ENTREGADO', 'ENTREGADO');
define('SALIENDO_DOMICILIO', 'SALIENDO'); //CUANDO VA SALIENDO DE LA DROGUERIA
define('LLEGANDO_DOMICILIO', 'LLEGANDO'); //CUANDO RETORNA DE ENTREGAR LOS DOMICILIOS QUE ESTABA ENTREGANDO

define('LOG_INSERT', 'INSERT');
define('LOG_UPDATE', 'UPDATE');
define('LOG_DELETE', 'DELETE');
define('API_ENDPOINT', 'https://apidian.sidroguerias.com');
define('API_ENDPOINT_LATAM', 'https://facturalatam.sidroguerias.com');
define('API_ENV_TOKEN', 'aP2wVgY1tiXGt3e7spikzKwvjQ28Re66OaokxDvFtKTjmm5ADcIS0sXOy9cUgO9KYazejwo8MOaR7uOd');



define('URL_CURL_GCM', 'http://sid.com.ve/api/gcm_push/enviar');

//SESSION
define('USUARIO_SESSION','nUsuCodigo');


//MENSAJES DE ERROR, ALERTAS, ETC
define('GLOBAL_ERROR','Ha ocurrido un error, por favor intente nuevamente');
define('NO_STOCK','Ha ingresado una cantidad superior al stock actual, para el producto ');
define('NUMERACION_ERROR','No se puede facturar, la numeración se agotó');

//RESPONSES STATUS
define('OK', 'success'); //todo eliminar
define('SUCCESS', 'success');
define('FAIL', 'failed'); // todo reemplazar por error
define('ERROR', 'error');



define('ANULACION_MOV_INV', 'ANULACION MOVIMIENTO DE INVENTARIO');

define('REG_FISICO_APP', 'REG_FISICO_APP');
define('REG_FISICO_SID', 'REG_FISICO_SID');

