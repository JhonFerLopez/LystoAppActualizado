<?php
/**
 * Created by PhpStorm.
 * User: Jhainey
 * Date: 31/05/2015
 * Time: 15:29
 */

/**
 * Class main_controller
 *  * @property ajusteinventario_model $ajusteinventario_model
 *  * @property ajustedetalle_model $ajustedetalle_model
 *  * @property inventario_model $inventario_model
 *  * @property producto_model $producto_model
 *  * @property paquete_has_prod_model $paquete_has_prod_model
 *  * @property unidades_model $unidades_model
 *  * @property local_model $local_model
 *  * @property clientes_grupos_model $clientes_grupos_model
 *  * @property unidades_has_precio_model $unidades_has_precio_model
 *  * @property marcas_model $marcas_model
 *  * @property clasificacion_model $clasificacion_model
 *  * @property componentes_model $componentes_model
 *  * @property ubicacion_fisica_model $ubicacion_fisica_model
 *  * @property afiliado_model $afiliado_model
 *  * @property StatusCajaModel $StatusCajaModel
 *  * @property tipo_proveedor_model $tipo_proveedor_model
 *  * @property tipo_anulacion_model $tipo_anulacion_model
 *  * @property tipo_devolucion_model $tipo_devolucion_model
 *  * @property tipo_venta_model $tipo_venta_model
 *  * @property resolucion_model $resolucion_model
 *  * @property regimen_model $regimen_model
 *  * @property afiliado_descuentos $afiliado_descuentos
 *  * @property tipo_producto_model $tipo_producto
 *  * @property lineas_model $lineas_model
 *  * @property familias_model $familias_model
 *  * @property grupos_model $grupos_model
 *  * @property proveedor_model $proveedor_model
 *  * @property impuestos_model $impuestos_model
 *  * @property columnas_model $columnas_model
 *  * @property estado_model $estado_model
 *  * @property ciudad_model $ciudad_model
 *  * @property cliente_model $cliente_model
 *  * @property condiciones_pago_model $condiciones_pago_model
 *  * @property venta_model $venta_model
 *  * @property cajas_model $cajas_model
 *  * @property gastos_model $gastos_model
 *  * @property usuarios_grupos_model $usuarios_grupos_model
 *  * @property usuario_model $usuario_model
 *  * @property opciones_model $opciones_model
 *  * @property ingreso_model $ingreso_model
 *  * @property tipos_gasto_model $tipos_gasto_model
 *  * @property metodos_pago_model $metodos_pago_model
 *  * @property ComprobanteDiarioVentas $ComprobanteDiarioVentas
 *  * @property banco_model $banco_model
 *  * @property recibo_pago_proveedor_model $recibo_pago_proveedor_model
 *  * @property pais_model $pais_model
 *  * @property camiones_model $camiones_model
 *  * @property zona_model $zona_model
 *  * @property consolidado_model $consolidado_model
 *  * @property venta_estatus_model $venta_estatus_model
 *  * @property historial_pagos_clientes_model $historial_pagos_clientes_model
 *  * @property bonificaciones_model $bonificaciones_model
 *  * @property descuentos_model $descuentos_model
 *  * @property liquidacion_cobranza_model $liquidacion_cobranza_model
 *  * @property kardex_model $kardex_model
 *  * @property drogueria_relacionada_model $drogueria_relacionada_model
 *  * @property documento_inventario_model $documento_inventario_model
 *  * @property detalle_ingreso_model $detalle_ingreso_model
 *  * @property traslado_model $traslado_model
 *  * @property systemLogsModel $systemLogsModel
 *  * @property control_ambiental_model $control_ambiental_model
 */
class MY_Controller extends CI_Controller
{
    function __construct()
    {

        parent::__construct();
        $this->load->model('usuariosgrupos/usuarios_grupos_model');
        $this->load->model('control_ambiental/control_ambiental_model');
        /*con esto valido la sesion*/
        $this->load->model('login/login_model');
        $this->load->library('session');
        $this->login_model->very_session();
        /* if (!$this->session->userdata('nUsuCodigo')) {
              redirect(base_url() . 'inicio');
          }*/

        ini_set('memory_limit', '10000M');
        ini_set('MAX_EXECUTION_TIME', -1);
        //header("Content-type:application/json");
    }

    function saveSesionControlAmb(){

        //horas de notificacion de control ambiental
        $this->session->set_userdata('control_ambiental_hrsnot', '');
        $horasnot = $this->control_ambiental_model->gethrsnot();
        if (count($horasnot) > 0) {
            $this->session->set_userdata('control_ambiental_hrsnot', json_encode($horasnot));

        }
        $control_amb_actual=array();
        $notif_control_hoy=array();
        $this->session->set_userdata('control_amb_actual', '');
        $this->session->set_userdata('notif_control_hoy','');
        //busco el control ambioental del mes actual
        $where=array(
            'periodo >='=>date('Y-m-01'),
            'periodo <='=>date('Y-m-31'),
        );
        $control = $this->control_ambiental_model->get_only_control($where);

        if (sizeof((array)$control) > 0) {

            $control['notif_control_hoy']=array();
            $where=array(
                'control_id'=>$control['control_ambiental_id'],
                'dia'=>date('d'),
            );

            $control['diahoy']=date('d');

            $notif_control_hoy = $this->control_ambiental_model->getAll_control_amb_notific($where);
            if(count($notif_control_hoy)>0){
                $control['notif_control_hoy']=$notif_control_hoy;
            }
            $control_amb_actual[0]=$control;
            $control_amb_actual=json_encode($control_amb_actual);
            $this->session->set_userdata('control_amb_actual', $control_amb_actual);
        }


    }

    function very_sesion()
    {


        $ver = $this->login_model->very_session();
        if ($ver == false) {
            redirect(base_url() . 'inicio');

        } else {


            if (!$this->input->is_ajax_request()) {
                

                //TODO, poner estas url con permisos
                if (uri_string() != 'opciones/generatebackup' && uri_string() != 'migrate/pull'  && uri_string() != 'migrate'
                && uri_string()!='opciones/downloadbackup' && uri_string()!='reportesExcel/excelInformeVentasPorFecha'
                    && uri_string()!='reportesExcel/geProductosParaPedidoSugerido'
                    && uri_string()!='reportesPdf/pdfInformeVentasPorFecha'   && uri_string()!='Venta/ventas_por_fecha'

                    && uri_string()!='Venta/sendElectronicInvoice'
                    && strpos(uri_string(),'Venta/facturaPdf') != false

                ) {

                    if ($this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), uri_string())
                        or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                    ) {

                    } else {
                        if (uri_string() != 'principal') {
                            redirect(base_url() . 'principal');
                        }

                    }
                }
            }
        }


    }

	function mesespanol($mes)
	{

		$mescortoanterior = "";
		if ($mes == "January") {
			$mes = "Enero";
			$mescortoanterior = "Ene";
		}
		if ($mes == "February") {
			$mes = "Febrero";
			$mescortoanterior = "Feb";
		}
		if ($mes == "March") {
			$mes = "Marzo";
			$mescortoanterior = "Mar";
		}
		if ($mes == "April") {
			$mes = "Abril";
			$mescortoanterior = "Abr";
		}
		if ($mes == "May") {
			$mes = "Mayo";
			$mescortoanterior = "May";
		}
		if ($mes == "June") {
			$mes = "Junio";
			$mescortoanterior = "Jun";
		}
		if ($mes == "July") {
			$mes = "Julio";
			$mescortoanterior = "Jul";
		}
		if ($mes == "August") {
			$mes = "Agosto";
			$mescortoanterior = "Ago";
		}
		if ($mes == "September") {
			$mes = "Septiembre";
			$mescortoanterior = "Sep";
		}
		if ($mes == "October") {
			$mes = "Octubre";
			$mescortoanterior = "Oct";
		}
		if ($mes == "November") {
			$mes = "Noviembre";
			$mescortoanterior = "Nov";
		}
		if ($mes == "December") {
			$mes = "Diciembre";
			$mescortoanterior = "Dic";
		}

		return array($mes, $mescortoanterior);

	}
}
