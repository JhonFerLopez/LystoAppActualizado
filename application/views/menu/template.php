<!DOCTYPE html>
<html lang="en">
  <?php
    date_default_timezone_set('America/Bogota');

    // Base URL
    $ruta = base_url();    
    $template = array('active_page' => basename($_SERVER['PHP_SELF']));    
    $aperturocaja = null;
    
    if (isset($cajas_abiertas) and count($cajas_abiertas) > 0) {
			foreach ($cajas_abiertas as $cja) {
				if ($cja['cajero'] === $this->session->userdata('nUsuCodigo')) {
					$aperturocaja = $cja['id'];
				}
			}
    }
    
    /* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
    $primary_nav = array(
			// Menu Principal
			array(
				'name' => 'MENU PRINCIPAL',
				'url' => 'header',
				'icon' => 'fa fa-home',
				'slug' => 'home'
			),
			array(
				'name' => 'Dashboard',
				'url' => $ruta . 'principal',
				'icon' => 'fa fa-home',
				'slug' => 'home'
			),	
			// PARAMETRIZACION
			array(
				'name' => 'PARAMETRIZACION',
				'slug' => 'parametrizacion',
				'icon' => 'fa fa-cogs',
				'sub' => array(	
					array(
						'name' => 'Parámetros productos',
						'slug' => 'parametrosproductos',
						// 'icon' => 'fa fa-list',
						'sub' => array(
							array(
								'name' => 'Productos',
								'url' => $ruta . 'producto',
								'icon' => 'gi gi-barcode',
								'slug' => 'productos'
							),
							array(
								'name' => 'Clasificacion',
								'url' => $ruta . 'clasificacion',
								'icon' => 'fa fa-certificate',
								'slug' => 'clasificacion'
							),
							array(
								'name' => 'Tipo',
								'url' => $ruta . 'tipo_producto',
								'icon' => 'fa fa-certificate',
								'slug' => 'tipo_producto'
							),
							array(
								'name' => 'Componentes',
								'url' => $ruta . 'componentes',
								'icon' => 'fa fa-certificate',
								'slug' => 'componentes'
							),
							array(
								'name' => 'Grupos',
								'url' => $ruta . 'grupo',
								'icon' => 'fa fa-cubes',
								'slug' => 'gruposproductos'
							),
							/*
							array(
								'name' => 'Niveles de Grupos',
								'url' => $ruta . 'grupo/niveles',
								'icon' => 'fa fa-cubes',
								'slug' => 'niveles_grupos'
							),*/
							array(
								'name' => 'Ubicación física',
								'url' => $ruta . 'ubicacion_fisica',
								'icon' => 'fa fa-certificate',
								'slug' => 'ubicacion_fisica'
							),
							array(
								'name' => 'Impuestos',
								'url' => $ruta . 'impuesto',
								'icon' => 'fa fa-money',
								'slug' => 'impuestos'
							),
							array(
								'name' => 'Parametrización rápida',
								'url' => $ruta . 'producto/paramrap',
								'icon' => 'fa fa-money',
								'slug' => 'paramrap'
							),

							array(
								'name' => 'Datos del Producto',
								'url' => $ruta . 'venta/columnasmodalproductos',
								'icon' => 'fa fa-ticket',
								'slug' => 'venta_columas_productos'
							),
							/*
							array(
								'name' => 'Unidades de Medida',
								'url' => $ruta . 'unidades',
								'icon' => 'fa fa-list-ol',
								'slug' => 'unidadesmedida'
							),*/
						)
					),
					array(
						'name' => 'Empresas y Clientes',
						//'icon' => 'gi gi-parents',
						'slug' => 'parametrosclientes',
						'sub' => array(
							array(
								'name' => 'Clientes',
								'url' => $ruta . 'cliente',
								'icon' => 'gi gi-parents',
								'slug' => 'clientes'
							),
							array(
								'name' => 'Tipos de cliente',
								'url' => $ruta . 'clientesgrupos',
								'icon' => 'fa fa-group',
								'slug' => 'gruposcliente'
							),
							array(
								'name' => 'Convenios Empresas',
								'url' => $ruta . 'afiliado',
								'icon' => 'gi gi-vcard',
								'slug' => 'afiliado'
							),
						)
					),
					// Proveedores
					array(
						'name' => 'Parametros proveedores',
						'slug' => 'parametrosproveedores',
						'sub' => array(
							array(
								'name' => 'Proveedores',
								'url' => $ruta . 'proveedor',
								'icon' => 'gi gi-vcard',
								'slug' => 'proveedor'
							),
							array(
								'name' => 'Tipos de Proveedor',
								'url' => $ruta . 'tipo_proveedor',
								'icon' => 'gi gi-vcard',
								'slug' => 'tipoproveedor'
							),
							array(
								'name' => 'Régimen Contributivo',
								'url' => $ruta . 'regimen',
								'icon' => 'gi gi-vcard',
								'slug' => 'regimencontributivo'
							),
						)
					),	
					array(
						'name' => 'Parámetros facturación',
						'slug' => 'parametrosfacturacion',
						'sub' => array(
							array(
								'name' => 'Formas de Pago',
								'url' => $ruta . 'metodosdepago',
								'icon' => 'fa fa-money',
								'slug' => 'metodospago'
							),
							array(
								'name' => 'Tipos de anulación',
								'url' => $ruta . 'tipo_anulacion',
								'icon' => 'fa fa-money',
								'slug' => 'tiposanulacion'
							),
							array(
								'name' => 'Tipos de devolución',
								'url' => $ruta . 'tipo_devolucion',
								'icon' => 'fa fa-money',
								'slug' => 'tiposdevolucion'
							),
							array(
								'name' => 'Tipos de venta',
								'url' => $ruta . 'tipo_venta',
								'icon' => 'fa fa-money',
								'slug' => 'tiposventa'
							),
							array(
								'name' => 'Resolucion de la DIAN',
								'url' => $ruta . 'resolucion_dian',
								'icon' => 'fa fa-money',
								'slug' => 'reosluciondian'
							),
							array(
								'name' => 'Condiciones de Pago',
								'url' => $ruta . 'condicionespago',
								'icon' => 'fa fa-ticket',
								'slug' => 'condicionespago'
							),
						)	
					), 
					array(
						'name' => 'Parametros de inventario',
						'slug' => 'parametrosinventario',
						'sub' => array(
							array(
								'name' => 'Tipo de movimiento',
								'url' => $ruta . 'documento_inventario',
								'icon' => 'gi gi-vcard',
								'slug' => 'tipomovimiento'
							),
							array(
								'name' => 'Bodegas',
								'url' => $ruta . 'local',
								'icon' => 'gi gi-shop_window',
								'slug' => 'bodegas'
							),
						)
					),
					array(
						'name' => 'Parametros de instalación',
						'slug' => 'parametrosinstalacion',
						'sub' => array(
							array(
								'name' => 'Parámetros generales',
								'url' => $ruta . 'opciones',
								//'icon' => 'fa fa-cogs',
								'slug' => 'opcionesgenerales'
							),
							array(
								'name' => 'Bancos',
								'url' => $ruta . 'banco',
								'icon' => 'gi gi-kiosk',
								'slug' => 'bancos'
							),
							array(
								'name' => 'Cajas',
								'url' => $ruta . 'cajas',
								'icon' => 'gi gi-nameplate',
								'slug' => 'cajas'
							),
						)
					),
					array(
						'name' => 'Control Ambiental',
						'url' => $ruta . 'control_ambiental/index',
						'icon' => 'fa fa-share',
						'slug' => 'control_ambiental'
					),
				),
			),	
			// Ventas
			array(
				'name' => 'VENTAS',
				'slug' => 'ventas',
				'icon' => 'fa fa-share',
				'sub' => array(
					array(
						'name' => 'Generar Venta',
						'url' => $ruta . 'venta',
						'icon' => 'fa fa-share',
						'slug' => 'generarventa'
					),
					array(
						'name' => 'Historial de Ventas',
						'url' => $ruta . 'venta/consultar',
						'icon' => 'fa fa-history',
						'slug' => 'historialventas'
					),
					array(
						'name' => 'Anular Venta',
						'url' => $ruta . 'venta/cancelar',
						'icon' => 'gi gi-remove_2',
						'slug' => 'anularventa'
					),
					array(
						'name' => 'Devoluci&oacute;n de Ventas',
						'url' => $ruta . 'venta/devolver',
						'icon' => 'fa fa-share',
						'slug' => 'devolucionventa'
					),
					array(
						'name' => 'Nota débito',
						'url' => $ruta . 'venta/notadebito',
						'icon' => 'fa fa-share',
						'slug' => 'anularventa'
					),
					array(
						'name' => 'Historial de Devoluciones',
						'url' => $ruta . 'venta/devoluciones',
						'icon' => 'fa fa-history',
						'slug' => 'devolucionventa'
					),
					array(
						'name' => 'Cajas abiertas',
						'url' => $ruta . 'StatusCaja/cajasabiertas',
						'icon' => 'fa fa-share',
						'slug' => 'cajas_abiertas'
					),
					array(
						'name' => 'Control de Domicilios',
						'url' => $ruta . 'venta/controlDomicilios',
						'icon' => 'fa fa-share',
						'slug' => 'control_domicilios'
					),
					array(
						'name' => 'Gastos',
						'icon' => 'fa fa-calculator',
						'sub' => array(
							array(
								'name' => 'Gastos',
								'url' => $ruta . 'gastos',
								'icon' => 'gi gi-parents',
								'slug' => 'gastos'
							),
							array(
								'name' => 'Tipos de gasto',
								'url' => $ruta . 'tiposdegasto',
								'icon' => 'gi gi-parents',
								'slug' => 'tiposgasto'
							),
						),
						'slug' => 'gastospadre'
					),
					/*
					array(
						'name' => 'Promociones',
						'icon' => 'fa fa-gift',
						'sub' => array(
							array(
								'name' => 'Bonificaciones',
								'url' => $ruta . 'bonificaciones',
								'icon' => 'gi gi-parents',
								'slug' => 'bonificaciones'
							),
							array(
								'name' => 'Descuentos',
								'url' => $ruta . 'descuentos',
								'icon' => 'gi gi-parents',
								'slug' => 'descuentos'
							),
						),
						'slug' => 'promociones'
					)*/
				)
			),	
			// Inventario
			array(
				'name' => 'INVENTARIO',
				'slug' => 'inventario',
				'icon' => 'fa fa-list',
				'sub' => array(
					array(
						'name' => 'Stock Bodegas',
						'url' => $ruta . 'producto/stock/bodegas',
						'icon' => 'fa fa-table',
						'slug' => 'stockbodegas'
					),
					array(
						'name' => 'Stock Droguerias',
						'url' => $ruta . 'producto/stock/droguerias',
						'icon' => 'fa fa-home',
						'slug' => 'stockdroguerias'
					),
					/*
					array(
							'name' => 'Lista de Precios',
							'url' => $ruta . 'producto/listaprecios',
							'icon' => 'fa fa-money',
							'slug' => 'listaprecios'
					),*/
					array(
							'name' => 'Registro Físicos',
							'url' => $ruta . 'inventario/ajuste',
							'icon' => 'fa fa-exchange',
							'slug' => 'registrarfisicos',
							'sub' => array(
								array(
									'name' => 'Registra todos los productos',
									'url' => $ruta . 'inventario/addajuste/todos',
									'icon' => 'fa fa-exchange',
									'slug' => 'registrarfisicotodos'
								),
								array(
									'name' => 'Registra por producto',
									'url' => $ruta . 'inventario/addajuste/byProduct',
									'icon' => 'fa fa-exchange',
									'slug' => 'registrafisicoporproducto'
								),
								array(
									'name' => 'Registra por grupo',
									'url' => $ruta . 'inventario/addajuste/byGroup',
									'icon' => 'fa fa-exchange',
									'slug' => 'registrafisicoporgrupo'
								),
								array(
									'name' => 'Historial',
									'url' => $ruta . 'inventario/ajustehistorial',
									'icon' => 'fa fa-exchange',
									'slug' => 'movimientosdiarios' //ajustehistorial TODO AGREGAR A PERMISOS
								),
							)
						),
						array(
							'name' => 'Movimiento diarios',
							'url' => $ruta . 'inventario/ajuste',
							'icon' => 'fa fa-exchange',
							'slug' => 'movimientosdiarios'
						),
						array(
							'name' => 'Kardex',
							'url' => $ruta . 'inventario/movimiento',
							'icon' => 'fa fa-calculator',
							'slug' => 'consultamovimientos'
						),
						array(
							'name' => 'Traslado',
							'url' => $ruta . 'traslado',
							'icon' => 'fa fa-calculator',
							'slug' => 'traslado'
						),
						/*  
						array(
							'name' => 'Existencia minima',
							'url' => $ruta . 'inventario/existencia_minima',
							'icon' => 'fa fa-minus-square-o',
							'slug' => 'exitenciaminima'
						),
						array(
							'name' => 'Existencias bajas',
							'url' => $ruta . 'inventario/existencia_baja',
							'icon' => 'fa fa-sort-amount-desc',
							'slug' => 'existenciabaja'
						),
						array(
							'name' => 'Existencias altas',
							'url' => $ruta . 'inventario/existencia_alta',
							'icon' => 'fa fa-sort-amount-asc',
							'slug' => 'existenciasalta'
						),*/
				),
			),
			// Ingresos
			array(
				'name' => 'COMPRAS',
				'slug' => 'compras',
				'icon' => 'fa fa-shopping-cart',
				'sub' => array(
					array(
						'name' => 'Registrar Compra',
						'url' => $ruta . 'ingresos',
						'icon' => 'gi gi-cart_in',
						'slug' => 'registraringreo'
					),
					array(
						'name' => 'Consultar compras',
						'url' => $ruta . 'ingresos/consultar',
						'icon' => 'gi gi-history',
						'slug' => 'consultaringresos'
					),
					array(
						'name' => 'Pedido sugerido',
						'url' => $ruta . 'ingresos/pedidosugerido',
						'icon' => 'gi gi-history',
						'slug' => 'consultaringresos'
					),
				),
			),
			// COBRANZAS
			array(
				'name' => 'CARTERA',
				'slug' => 'cartera',
				'icon' => 'fa  fa-money',
				'sub' => array(
					array(
						'name' => 'Movimientos de cartera',
						'url' => $ruta . 'cartera/estadocuenta',
						'icon' => 'gi gi-wallet',
						'slug' => 'movimientoscartera'
					),
					array(
						'name' => 'Generar recibos cliente',
						'url' => $ruta . 'cartera/generarrecibos',
						'icon' => 'gi gi-folder_flag',
						'slug' => 'generarreciboscliente'
					),
					array(
						'name' => 'Clientes deuda',
						'url' => $ruta . 'cartera/clientesdeuda',
						'icon' => 'gi gi-folder_flag',
						'slug' => 'clientesdeuda'
					),
				)
			),
			array(
				'name' => 'CTAS POR PAGAR',
				'url' => $ruta . 'proveedor/estadocuenta',
				'icon' => 'fa  fa-credit-card',
				'slug' => 'cuentasporpagar',
				'sub' => array(
					array(
						'name' => 'Movimiento Proveedor',
						'url' => $ruta . 'cuentasPorPagar/cuentas_por_pagar',
						'icon' => 'fa fa-bar-chart',
						'slug' => 'movimientoproveedor'
					),
					array(
						'name' => 'Generar comprobante',
						'url' => $ruta . 'cuentasPorPagar/generar_comprobante',
						'icon' => 'gi gi-folder_flag ',
						'slug' => 'generarcomprobanteproveedor'
					),
				)
			),
			// Reportes
			array(
				'name' => 'REPORTES',
				'slug' => 'reportes',
				'icon' => 'fa fa-bar-chart',
				'sub' => array(
					array(
						'name' => 'Puntos de Venta',
						'slug' => 'rep_puntos_venta',
						'sub' => array(
							array(
								'name' => 'Informe de Ventas por fecha',
								'url' => $ruta . 'reportes/informeVentasFecha',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_informe_ventas_fecha'
							),
							array(
								'name' => 'Ventas por Hora',
								'url' => $ruta . 'reportes/ventasPorHora',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_ventas_por_hora'
							),
							/*  
							array(
								'name' => 'Informe de ventas  por tipo de venta',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
							array(
								'name' => 'Informe detallado por IVA ',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),*/
						)
					),
					array(
						'name' => 'Compras',
						'slug' => 'compras',
						'sub' => array(
							array(
								'name' => 'Informe detallado de compras',
								'url' => $ruta . 'ingresos/informe_detallado',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'informe_detallado'
							),
							/*  
							array(
								'name' => 'Informe de ventas  por tipo de venta',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
							array(
								'name' => 'Informe detallado por IVA ',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),*/
						)
					),
					array(
						'name' => 'Clientes',
						'slug' => 'clientes',
						'sub' => array(
							array(
								'name' => 'Compras por Cliente',
								'url' => $ruta . 'reportes/clientes_compras_por_cliente',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'compras_por_cliente'
							),
							array(
								'name' => 'Unidades por Cliente',
								'url' => $ruta . 'reportes/unidades_por_cliente',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'unidades_por_cliente'
							),
						)
					),
					array(
						'name' => 'Informes estadísticos',
						'slug' => 'rep_informes_estadisticos',
						'sub' => array(
							/* 
							array(
								'name' => 'Transacciones reales',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),*/
							array(
								'name' => 'Contribución Marginal',
								'url' => $ruta . 'reportes/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_cont_marginal'
							),
							array(
								'name' => 'Promedio de Compras por Cliente',
								'url' => $ruta . 'reportes/rep_prom_comp_client',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_prom_comp_client'
							)
						)
					),
					/* 
					array(
						'name' => 'Informe de Facturación',
						'slug' => 'reportes',
						'sub' => array(
							array(
								'name' => 'Total factura por vendedor',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
							array(
								'name' => 'Facturación General',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
							array(
								'name' => 'Facturas por tipo de venta',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
							array(
								'name' => 'Tipos de pago vs facturas',
								'url' => $ruta . 'venta/reporteUtilidades',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'reportes'
							),
						)
					),*/
					array(
						'name' => 'Reportes por vendedor',
						'slug' => 'rep_vendedor',
						'sub' => array(
							array(
								'name' => 'Participacion ventas por vendedor',
								'url' => $ruta . 'reportes/participacionVentas',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_part_ventas_vendedor'
							),
							array(
								'name' => 'Participacion ventas (Factura/Producto)',
								'url' => $ruta . 'reportes/participacionVentasProducto',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_part_ventas_vendedor'
							),
							array(
								'name' => 'Comparar vendedores',
								'url' => $ruta . 'reportes/comparativasVendedor',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_comparar_vendedores'
							),
							array(
								'name' => 'Por tipo producto',
								'url' => $ruta . 'reportes/comparativasVendedorGrupo',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_vendedores_tipo_prod'
							),
						)
					),
	
					/* 
					array(
						'name' => 'Historial por cliente',
						'url' => $ruta . 'venta/reporteUtilidades',
						'icon' => 'fa fa-bar-chart',
						'slug' => 'reportes'
					),
					array(
						'name' => 'Transacciones por hora',
						'url' => $ruta . 'venta/reporteUtilidades',
						'icon' => 'fa fa-bar-chart',
						'slug' => 'reportes'
					),
					*/	
					array(
						'name' => 'Inventario',
						'slug' => 'rep_valor_inventario',
						'sub' => array(
							array(
								'name' => 'Valor del inventario',
								'url' => $ruta . 'inventario/valorinventario',
								'slug' => 'rep_valor_inventario',
							),
							array(
								'name' => 'Productos no afectados',
								'url' => $ruta . 'inventario/productosnoafectados',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_comparar_vendedores'
							),
							array(
								'name' => 'Reporte de transacciones',
								'url' => $ruta . 'inventario/rep_inv_transacciones',
								'icon' => 'fa fa-bar-chart',
								'slug' => 'rep_inv_transacciones'
							),
						)
					),

					array(
						'name' => 'Cierre de caja',
						'url' => $ruta . 'StatusCaja/cuadrecajerohistory',
						'slug' => 'rep_cuadre_caja'
					),
					array(
						'name' => 'Comprobante diario de venta',
						'url' => $ruta . 'venta/comprobantediarioventas',
						'slug' => 'rep_comp_diario_venta'
					),
					array(
						'name' => 'Productos que comisionan',
						'url' => $ruta . 'reportes/productos_comisionan',
						'slug' => 'productos_comisionan'
					),
					array(
						'name' => 'Productos por Propiedad',
						'url' => $ruta . 'reportes/propiedad_productos',
						'slug' => 'propiedad_productos'
					),
					array(
						'name' => 'Productos mas Vendidos',
						'url' => $ruta . 'reportes/productos_mas_vendidos',
						'slug' => 'productos_mas_vendidos'
					),
					array(
						'name' => 'Productos con menor Rotación',
						'url' => $ruta . 'reportes/repProdSinRotacion',
						'icon' => 'fa fa-bar-chart',
						'slug' => 'rep_prod_sin_rotacion'
					),
				)
			),
			// Opciones
			array(
				'name' => 'UTILIDAD',
				'slug' => 'utilidad',
				'icon' => 'fa fa-cog',
				'sub' => array(
					array(
						'name' => 'Notificaciones',
						'url' => $ruta . 'notificaciones/',
						'icon' => 'fa fa-paper-plane',
						'slug' => 'fact_elect_ext'
					),
					array(
						'name' => 'Facturación electrónica',
						'slug' => 'facturacionelectronica',
						'icon' => 'fa fa-home',
						'sub' => array(
							array(
								'name' => 'Consultar documentos',
								'url' => $ruta . 'facturacionElectronica/consulta',
								'icon' => 'fa fa-list',
								'slug' => 'facturacionelectronica'
							),
							array(
								'name' => 'Empresa',
								'url' => $ruta . 'facturacionElectronica',
								'icon' => 'fa fa-users',
								'slug' => 'facturacionelectronica'
							),
							array(
								'name' => 'Software',
								'url' => $ruta . 'facturacionElectronica/software',
								'icon' => 'fa fa-users',
								'slug' => 'facturacionelectronica'
							),
							array(
								'name' => 'Certificado',
								'url' => $ruta . 'facturacionElectronica/certificado',
								'icon' => 'fa fa-users',
								'slug' => 'facturacionelectronica'
							),
							array(
								'name' => 'Resoluciones',
								'url' => $ruta . 'facturacionElectronica/resolucion',
								'icon' => 'fa fa-users',
								'slug' => 'facturacionelectronica'
							),
							array(
								'name' => 'Logo',
								'url' => $ruta . 'facturacionElectronica/logo',
								'icon' => 'fa fa-image',
								'slug' => 'facturacionelectronica'
							),								
							/* array(
									'name' => 'Facturación Electrónica Externa',
									'url' => $ruta . 'facturacionElectronica/fact_externa',
									'icon' => 'fa fa-list',
									'slug' => 'fact_elect_ext'
							),*/
						),
					),
					array(
						'name' => 'Seguridad',
						'icon' => 'fa fa-users',
						'slug' => 'seguridad',
						'sub' => array(
							array(
								'name' => 'Usuarios',
								'url' => $ruta . 'usuario',
								'icon' => 'fa fa-users',
								'slug' => 'usuarios'
							),
							array(
								'name' => 'Roles',
								'url' => $ruta . 'usuariosgrupos',
								'icon' => 'gi gi-parents',
								'slug' => 'roles'
							),
							array(
								'name' => 'Status del Servidor',
								'url' => $ruta . 'server',
								'icon' => 'gi gi-parents',
								'slug' => 'roles'
							),
							array(
								'name' => 'Logs del sistema',
								'url' => $ruta . 'systemLogs',
								'icon' => 'gi gi-parents',
								'slug' => 'system_logs'
							)
						),
					),
					array(
						'name' => 'Droguerias relacionadas',
						'url' => $ruta . 'drogueria_relacionada',
						'icon' => 'fa fa-home',
						'slug' => 'drogueriasrelacionadas'
					),
					array(
						'name' => 'Localización',
						'icon' => 'fa fa-globe',
						'slug' => 'localizacion',
						'sub' => array(
							array(
								'name' => 'Paises',
								'url' => $ruta . 'pais',
								'icon' => 'fa fa-users',
								'slug' => 'pais'
							),
							array(
								'name' => 'Departamentos',
								'url' => $ruta . 'estados',
								'icon' => 'gi gi-shop_window',
								'slug' => 'departamentos'
							),
							array(
								'name' => 'Ciudades',
								'url' => $ruta . 'ciudad',
								'icon' => 'gi gi-shop_window',
								'slug' => 'ciudad'
							),
							array(
								'name' => 'Barrios',
								'url' => $ruta . 'zona',
								'icon' => 'gi gi-globe',
								'slug' => 'barrios'
							)
						),
					),
					array(
						'name' => 'Importar',
						'url' => $ruta . 'importar',
						'icon' => 'fa fa-ticket',
						'slug' => 'importar'
					),
				),
			),
			array(
				'name' => 'SOPORTE',
				'slug' => 'soporte',
				'icon' => 'fa fa-shield',
				'sub' => array(
					array(
						'name' => 'Licenciamiento',
						'icon' => 'fa fa-users',
						'url' => $ruta . 'licenciamiento',
						'slug' => 'licenciamiento',
					),
					array(
						'name' => 'Error logs',
						'url' => $ruta . 'systemLogs/error',
						'icon' => 'fa fa-home',
						'slug' => 'errorlogs'
					),
					array(
						'name' => 'Modificar Fechas a Ventas',
						'url' => $ruta . 'soporte/updatefechaventa',
						'icon' => 'fa fa-users',
						'slug' => 'modif_fecha_ventas'
					),
					array(
						'name' => 'Venta',
						'icon' => 'fa fa-globe',
						'slug' => 'soporte_venta',
						'sub' => array(
							array(
								'name' => 'Modificar Fechas a Ventas',
								'url' => $ruta . 'soporte/updatefechaventa',
								'icon' => 'fa fa-users',
								'slug' => 'pais'
							)
						),
					),
				)
			),    
    );    
	?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $ruta; ?>recursos/img/favicon.png">
    <title>SID | Sistema integral de droguerias</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $ruta; ?>recursos/css/buttons.dataTables.min.css" id="theme" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/jqueryui/jquery-ui.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="<?php echo $ruta; ?>recursos/css/animate.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/chosen/chosen.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!--alerts CSS -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- SELECT2 CSS -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/select2/css/select2.min.css" rel="stylesheet">
    <!-- datetimepicker -->
    <link href="<?php echo $ruta; ?>recursos/plugins/bower_components/datetimepicker/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $ruta; ?>recursos/css/style.css?v=" <?php echo date('Ymdims') ?> rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/colors/megna.css" id="theme" rel="stylesheet">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="<?php echo $ruta; ?>recursos/css/main.css?v=" <?php echo date('Ymdims') ?>>
    <!-- color CSS -->
    <!--  <link href="<?php echo $ruta; ?>recursos/css/colors/megna.css" id="theme" rel="stylesheet">-->
    <link href="<?php echo $ruta; ?>recursos/css/colors/green-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://maps.google.com/maps/api/js?libraries=placeses,visualization,drawing,geometry,places&key=AIzaSyDmw7b5iWE_3X5tUZqXl1Xmtsnhdy7vPuo"></script>
  </head>
  <body style="padding-right: 0px !important;">
    <!-- Preloader -->
    <div class="preloader">
      <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
      <?php
        $tipo_empresa = $this->session->userdata('TIPO_EMPRESA');
        switch ($tipo_empresa) {
					case 'OTRO':		
						$imglogo = 'POS';
						break;
					default:			
						$imglogo = 'roguerias';
						break;
        }
			?>
      <!-- Top Navigation -->
      <nav class="navbar navbar-default navbar-static-top m-b-0">
        <div class="navbar-header">
          <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
          <div class="top-left-part"><a class="logo" href="#"><b>
            <img src="<?php echo $ruta ?>recursos/img/sid-icon_blanco-01.png" width="40" alt="home" />
            </b><span class="hidden-xs"><strong>SID</strong><?= $imglogo ?></span></a>
          </div>
          <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li><a href="javascript:App.sidebar()" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
            <!-- <li>
              <form role="search" class="app-search hidden-xs">
								<input type="text" placeholder="Search..." class="form-control"> 
								<a href=""><i class="fa fa-search"></i></a>
							</form>
						</li>-->
            <?php if (
              $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'stockbodegas')
              or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
              ) { ?>
            <li>
              <a class="text-white menulink" href="<?= $ruta ?>producto/stock/bodegas" target="_blank"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> Stock(F2)</a>
            </li>
            <?php } ?>
            <?php if (
              $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'generarventa')
              or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
              ) { ?>
            <li class=""><a class="menulink text-white" href="<?= $ruta ?>venta"><i class="fa fa-share"></i> Venta(F3)</a>
            </li>
            <?php } ?>
            <?php if (
              $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'registraringreo')
              or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
              ) { ?>
            <li class=""><a class="menulink text-white" href="<?= $ruta ?>ingresos"><i class="fa fa-shopping-cart"></i> Registrar compra</a>
            </li>
            <?php } ?>
            <li>
              <a class=" text-yellow" href="#"> Ultima actualización: <?= $this->session->userdata('system_version') ?></a>
            </li>
          </ul>
          <ul class="nav navbar-top-links navbar-right pull-right">
            <!--<li class="dropdown"><a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
              href="#"><i class="icon-envelope"></i>
              <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
              </a>
              <ul class="dropdown-menu mailbox animated bounceInDown">
              <li>
              <div class="drop-title">You have 4 new messages</div>
              </li>
              <li>
              <div class="message-center">
              <a href="#">
              <div class="user-img">
								<img src="<?php echo $ruta ?>recursos/plugins/images/users/pawandeep.jpg"
									alt="user" class="img-circle"> <span
									class="profile-status online pull-right"></span></div>
              <div class="mail-contnet">
              <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> 
								<span class="time">9:30 AM</span></div>
              </a>
              <a href="#">
              <div class="user-img"><img
                      src="<?php echo $ruta ?>recursos/plugins/images/users/sonu.jpg"
                      alt="user" class="img-circle"> <span
                      class="profile-status busy pull-right"></span></div>
              <div class="mail-contnet">
              <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span>
              <span class="time">9:10 AM</span></div>
              </a>
              <a href="#">
              <div class="user-img"><img
                      src="<?php echo $ruta ?>recursos/plugins/images/users/arijit.jpg"
                      alt="user" class="img-circle"> <span
                      class="profile-status away pull-right"></span></div>
              <div class="mail-contnet">
              <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span
                      class="time">9:08 AM</span></div>
              </a>
              <a href="#">
              <div class="user-img"><img
                      src="<?php echo $ruta ?>recursos/plugins/images/users/pawandeep.jpg"
                      alt="user" class="img-circle"> <span
                      class="profile-status offline pull-right"></span></div>
              <div class="mail-contnet">
              <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span
                      class="time">9:02 AM</span></div>
              </a>
              </div>
              </li>
              <li>
              <a class="text-center" href="javascript:void(0);"> <strong>See all notifications</strong> <i
              class="fa fa-angle-right"></i> </a>
              </li>
              </ul>
              
              </li>-->
            <!-- /.dropdown -->
            <?php
              $sessioncajaapertura = $this->session->userdata('cajapertura');
              //var_dump( $this->session->userdata());
              
              ?>
            <li class="">
              <a class="waves-effect waves-light" title="Enviar Nueva Notificación" data-toggle="dropdown" href="#" onclick="Venta.loadVentaWindows('/index'); ">
              <i class="fa fa-share"></i>
              </a>
            </li>
            <li class="">
              <a class="waves-effect waves-light" title="Enviar Nueva Notificación" data-toggle="dropdown" href="#" onclick="Notificaciones.modalNewNotificacion(false);">
              <i class="fa fa-paper-plane"></i>
              </a>
            </li>
            <li class="">
              <a class="waves-effect waves-light" id="fullscreen" href="#">
              <i class="fa fa-desktop"></i>
              </a>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#">
                <i class="fa fa-money"></i>
                <?php if ($sessioncajaapertura == '') { ?>
                <div class="notify" id="alertmoney">
                  <span class="heartbit"></span><span class="point"></span>
                </div>
                <?php } ?>
              </a>
              <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                <?php
                  if (count($cajas_abiertas) > 0 && $aperturocaja == null) {
                  ?>
                <?php foreach ($cajas_abiertas as $caja) {
                  ?>
                <li id="licaja_<?= $caja['id'] ?>" class="<?= ($caja['id'] == $sessioncajaapertura) ? 'active' : '' ?> listacajas">
                  <a href="#" <?php if ($caja['id'] != $sessioncajaapertura) { ?> onclick="StatusCaja.selectCaja(<?= $caja['id'] ?>,<?= $caja['caja_id'] ?>);" <?php } ?>>
                    <div>
                      <p><strong><?= $caja['alias'] ?></strong> <span class="pull-right text-muted"> <?= $caja['username'] ?></span>
                      </p>
                      <div class="progress progress-striped active">
                        <!--<div class="progress-bar progress-bar-success" role="progressbar"
                          aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                          style="width: 100%"><span class="sr-only"> </span>
                          </div>-->
                      </div>
                    </div>
                  </a>
                </li>
                <li class="divider"></li>
                <?php }
                  } ?>
                <li id="apertura_caja_li" style="display: <?php echo ($aperturocaja != null ? 'none' : 'block') ?>">
                  <a id="apertura_caja_link" class="text-center" href="#"> <strong>Aperturar caja</strong> <i class="fa fa-angle-right"></i> </a>
                </li>
                <li style="display: <?php echo ($aperturocaja != null ? 'block' : 'none') ?>" ID="cierre_caja_li">
                  <a id="cierre_caja_link" class="text-center" href="#"> <strong>Cerrar caja</strong> <i class="fa fa-angle-right"></i> </a>
                </li>
              </ul>
              <!-- /.dropdown-tasks -->
            </li>
            <!-- /.dropdown -->
            <!-- .Megamenu -->
            <li class="mega-dropdown">
              <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><span class="hidden-xs">Ayuda</span> <i class="icon-options-vertical"></i></a>
              <ul class="dropdown-menu mega-dropdown-menu animated bounceInDown">
                <li class="col-sm-3">
                  <ul>
                    <li class="dropdown-header">Parametrizacion</li>
                    <li><a href="javascript:void(0)">Parametrizar productos</a></li>
                    <li><a href="javascript:void(0)">Parametrizar clientes</a></li>
                    <li><a href="javascript:void(0)">Parametrizar proveedores</a></li>
                  </ul>
                </li>
                <li class="col-sm-3">
                  <ul>
                    <li class="dropdown-header">Ventas</li>
                    <li><a href="javascript:void(0)">Generar ventas</a></li>
                    <li><a href="javascript:void(0)">Anular ventas</a></li>
                  </ul>
                </li>
                <li class="col-sm-12 m-t-40 demo-box">
                  <div class="row">
                    <!-- <div class="col-sm-2"><div class="white-box text-center bg-warning"><a href="../eliteadmin-iconbar/index4.html" target="_blank" class="text-white"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Demo 5</a></div></div>
                      <div class="col-sm-2"><div class="white-box text-center bg-danger"><a href="https://themeforest.net/item/elite-admin-responsive-web-app-kit-/16750820" target="_blank" class="text-white"><i class="linea-icon linea-ecommerce fa-fw" data-icon="d"></i><br>Buy Now</a></div></div>
                         <div class="col-sm-2"><div class="white-box text-center bg-success"><a href="../eliteadmin/index.html" target="_blank" class="text-white"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Demo 2</a></div></div>
                      -->
                  </div>
                </li>
                <li class="col-sm-12 m-t-40 demo-box">
                  <div class="row">
                    <?php if (
                      $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'stockbodegas')
                      or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                      ) { ?>
                    <div class="col-sm-2">
                      <div class="white-box text-center bg-purple"><a class="text-white menulink" href="<?= $ruta ?>producto/stock/bodegas" target="_blank"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Stock(F2)</a>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if (
                      $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'generarventa')
                      or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                      ) { ?>
                    <div class="col-sm-2">
                      <div class="white-box text-center bg-success"><a class="menulink text-white" href="<?= $ruta ?>venta"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Venta(F3)</a>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if (
                      $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), 'cartera')
                      or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                      ) { ?>
                    <div class="col-sm-2">
                      <div class=" white-box text-center bg-info"><a href="<?= $ruta ?>cartera/estadocuenta" class="menulink text-white"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Cartera</a>
                      </div>
                    </div>
                    <?php } ?>
                    <!--
                      <div class="col-sm-2">
                          <div class="white-box text-center bg-info"><a
                                      href="../eliteadmin-ecommerce/index.html"
                                      target="_blank" class="text-white"><i
                                          class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Demo
                                  3</a></div>
                      </div>
                      <div class="col-sm-2">
                          <div class="white-box text-center bg-inverse"><a
                                      href="../eliteadmin-horizontal-navbar/index3.html" target="_blank"
                                      class="text-white"><i class="linea-icon linea-basic fa-fw"
                                                            data-icon="v"></i><br>Demo 4</a></div>
                      </div>
                      <div class="col-sm-2">
                          <div class="white-box text-center bg-warning"><a
                                      href="../eliteadmin-iconbar/index4.html"
                                      target="_blank" class="text-white"><i
                                          class="linea-icon linea-basic fa-fw" data-icon="v"></i><br>Demo
                                  5</a></div>
                      </div>
                      <div class="col-sm-2">
                          <div class="white-box text-center bg-danger"><a
                                      href="https://themeforest.net/item/elite-admin-responsive-web-app-kit-/16750820"
                                      target="_blank" class="text-white"><i
                                          class="linea-icon linea-ecommerce fa-fw"
                                          data-icon="d"></i><br>Buy Now</a></div>
                      </div>-->
                  </div>
                </li>
              </ul>
            </li>
            <!-- /.Megamenu -->
            <li class="right-side-toggle"><a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
            <!-- /.dropdown -->
          </ul>
        </div>
        <!-- /.navbar-header -->
        <!-- /.navbar-top-links -->
        <!-- /.navbar-static-side -->
      </nav>
      <!-- End Top Navigation -->
      <!-- Left navbar-header -->
      <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse slimscrollsidebar">
          <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
              <!-- input-group -->
              <div class="input-group custom-search-form">
                <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                </span>
              </div>
              <!-- /input-group -->
            </li>
            <li class="user-pro">
              <a href="nolink" class="waves-effect"><img src="<?php echo $ruta ?>recursos/plugins/images/users/d1.jpg" alt="user-img" class="img-circle"> <span class="hide-menu"><?= $this->session->userdata('nombre') ?><span class="fa arrow"></span></span>
              </a>
              <ul class="nav nav-second-level">
                <li><a href="#" onclick="miperfil.mostrarmodal();"><i class="ti-user"></i> Mi perfil</a></li>
                <!-- <li><a href="javascript:void(0)"><i class="ti-email"></i> Inbox</a></li>
                  <li><a href="javascript:void(0)"><i class="ti-settings"></i> Account Setting</a></li>-->
                <li><a href="<?= base_url() ?>logout"><i class="fa fa-power-off"></i> Logout</a></li>
              </ul>
            </li>
            <?php if ($primary_nav) { ?>
            <!-- Sidebar Navigation -->
            <?php foreach ($primary_nav as $key => $link) {
              $link_class = '';
              $li_active = '';
              $menu_link = '';
              
              // Get 1st level link's vital info
              $url = (isset($link['url']) && $link['url']) ? $link['url'] : 'nolink';
              $active = (isset($link['url']) && ($template['active_page'] == $link['url'])) ? ' active' : '';
              $icon = (isset($link['icon']) && $link['icon']) ? '<i class="' . $link['icon'] . ' sidebar-nav-icon"></i>' : '';
              $slug = (isset($link['slug']) && $link['slug']) ? $link['slug'] : '';
              
              // Check if the link has a submenu
              if (isset($link['sub']) && $link['sub']) {
                  // Since it has a submenu, we need to check if we have to add the class active
                  // to its parent li element (only if a 2nd or 3rd level link is active)
                  foreach ($link['sub'] as $sub_link) {
                      if (in_array($template['active_page'], $sub_link)) {
                          $li_active = 'active';
                          break;
                      }
              
                      // 3rd level links
                      if (isset($sub_link['sub']) && $sub_link['sub']) {
                          foreach ($sub_link['sub'] as $sub2_link) {
                              if (in_array($template['active_page'], $sub2_link)) {
                                  $li_active = ' active';
                                  break;
                              }
                          }
                      }
                  }
              } else {
                  $li_active = 'menulink';
              }
              // Create the class attribute for our link
              
              ?>
            <?php if ($url == 'header') { // if it is a header and not a link
              //if ($this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $slug)) { 
              ?>
            <li class="nav-small-cap m-t-10">--- <?php echo $link['name']; ?></li>
            <?php // }
              } else { // If it is a link
                  if (
                      $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $slug) or $slug == 'home'
                      or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                  ) {
                  ?>
            <li>
              <a href="<?php echo $url; ?>" class="<?php echo $li_active; ?>  waves-effect">
              <i class="<?= $link['icon'] ?>"></i>
              <span class="hide-menu">
              <?php
                echo $link['name']; ?>
              <?php if (isset($link['sub']) && $link['sub']) { // if the link has a submenu 
                ?>
              <span class="fa arrow"></span>
              <?php } ?>
              </span>
              </a>
              <?php if (isset($link['sub']) && $link['sub']) { // if the link has a submenu
                if (
                    $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $slug)
                    or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                ) { ?>
              <ul class="nav nav-second-level">
                <?php
                  foreach ($link['sub'] as $sub_link) {
                    $link_class = '';
                    $li_active = '';
                    $submenu_link = '';
                
                    // Get 2nd level link's vital info
                    $url = (isset($sub_link['url']) && $sub_link['url']) ? $sub_link['url'] : 'nolink';
                    $active = (isset($sub_link['url']) && ($template['active_page'] == $sub_link['url'])) ? ' active' : '';
                    $slug = $sub_link['slug'];
                    $icon = (isset($sub_link['icon']) && $sub_link['icon']) ? '<i class="' . $sub_link['icon'] . ' sidebar-nav-icon"></i>' : '';
                
                    // Check if the link has a submenu
                    if (isset($sub_link['sub']) && $sub_link['sub']) {
                      // Since it has a submenu, we need to check if we have to add the class active
                      // to its parent li element (only if a 3rd level link is active)
                      foreach ($sub_link['sub'] as $sub2_link) {
                          if (in_array($template['active_page'], $sub2_link)) {
                              $li_active = 'active ';
                              break;
                          }
                      }
              
                      $submenu_link = 'sidebar-nav-submenu';
                    } else {
                      $li_active = 'menulink';
                    }
                    if (
                      $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $slug)
                      or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                    ) { ?>
                <li>
                  <a href="<?php echo $url; ?>" class="<?php echo $li_active; ?>  waves-effect ">
                  <?php if (isset($sub_link['sub']) && $sub_link['sub']) { ?>
                  <span class="fa arrow"></span>
                  <?php }
                    echo $sub_link['name']; ?>
                  </a>
                  <?php if (isset($sub_link['sub']) && $sub_link['sub']) {
                    if (
                        $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $slug)
                        or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                    ) { ?>
                  <ul class="nav nav-third-level collapse">
                    <?php
                      foreach ($sub_link['sub'] as $sub2_link) {
                          // Get 3rd level link's vital info
                          $url = (isset($sub2_link['url']) && $sub2_link['url']) ? $sub2_link['url'] : 'nolink';
                          $active = (isset($sub2_link['url']) && ($template['active_page'] == $sub2_link['url'])) ? ' class="active"' : '';
                          if (
                              $this->usuarios_grupos_model->user_has_perm($this->session->userdata('nUsuCodigo'), $sub2_link['slug'])
                              or $this->session->userdata('nombre_grupos_usuarios') == 'PROSODE_ADMIN'
                          ) { ?>
                    <li>
                      <a class="menulink" href="<?php echo $url; ?>" <?php echo $active ?>><?php echo $sub2_link['name']; ?></a>
                    </li>
                    <?php }
                      } ?>
                  </ul>
                  <?php }
                    } ?>
                </li>
                <?php }
                  } ?>
              </ul>
              <?php }
                } ?>
            </li>
            <?php
              }
              } ?>
            <?php } ?>
            <!-- END Sidebar Navigation -->
            <?php } ?>
          </ul>
        </div>
      </div>
      <!-- Left navbar-header end -->
      <!-- Page Content -->
      <div id="page-wrapper">
        <div class="row" id="page-content">
          <?php echo $cuerpo ?>
          <!-- Charts Header -->
          <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
        <!-- /.row -->
        <!-- .right-sidebar -->
        <div class="right-sidebar">
          <div class="slimscrollright">
            <div class="rpanel-title"> Panel de servicio <span><i class="ti-close right-side-toggle"></i></span>
            </div>
            <div class="r-panel-body">
              <ul>
                <li><b>Layout Options</b></li>
                <li>
                  <div class="checkbox checkbox-info">
                    <input id="checkbox1" type="checkbox" class="fxhdr">
                    <label for="checkbox1"> Fix Header </label>
                  </div>
                </li>
                <li>
                  <div class="checkbox checkbox-warning">
                    <input id="checkbox2" type="checkbox" checked="" class="fxsdr">
                    <label for="checkbox2"> Fix Sidebar </label>
                  </div>
                </li>
                <li>
                  <div class="checkbox checkbox-success">
                    <input id="checkbox4" type="checkbox" class="open-close">
                    <label for="checkbox4"> Toggle Sidebar </label>
                  </div>
                </li>
              </ul>
              <ul>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="#" onclick="Venta.ver_catalogo('COOPIDRGOGAS')" class=" btn btn-primary">
                    C. PRINCIPAL</a>
                  </div>
                </li>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="#" onclick="Venta.ver_catalogo('DROGUERIA')" class="btn btn-primary"> C. DROGUERIA</a>
                  </div>
                </li>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="#" onclick="Utilities.drogueriasRelacionadasModal()" class="btn btn-primary">RELACIONADAS</a>
                  </div>
                </li>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="javascript:App.buscarActualizacionesRepository()" class="btn btn-info" id="options-header-default">Buscar Actualizaciones</a>
                  </div>
                </li>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="javascript:App.buscarActualizaciones()" class="btn btn-info" id="options-header-default">Actualizar base de datos</a>
                    <!--  <a href="javascript:void(0)" class="btn btn-primary"
                      id="options-header-inverse">Dark</a>-->
                  </div>
                </li>
                <li>
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="javascript:App.generarBackup()" class="btn btn-success" id="options-header-default">Generar Backup</a>
                    <!--  <a href="javascript:void(0)" class="btn btn-primary"
                      id="options-header-inverse">Dark</a>-->
                  </div>
                </li>
                <?php if (
                  $this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB') == null ||
                  $this->session->userdata('KEY_RECIBE_NOTIF_CONTROL_AMB') == ''
                  ) { ?>
                <li id="li_saveRecibeNotControlAmb">
                  <div class="btn-group btn-group-justified btn-group-sm">
                    <a href="javascript:App.saveRecibeNotControlAmb()" class="btn btn-success" id="options-header-default">Notificarme Control ambiental</a>
                  </div>
                </li>
                <?php } ?>
              </ul>
              <!-- <ul id="themecolors" class="m-t-20">
                <li><b>With Light sidebar</b></li>
                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                <li><a href="javascript:void(0)" data-theme="gray" class="yellow-theme">3</a></li>
                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme">4</a></li>
                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme working">6</a></li>
                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                <br/>
                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                <li><a href="javascript:void(0)" data-theme="gray-dark" class="yellow-dark-theme">9</a></li>
                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme">12</a></li>
                </ul>
                <ul class="m-t-20 chatonline">
                <li><b>Chat option</b></li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/varun.jpg" alt="user-img"
                                class="img-circle"> <span>Varun Dhavan <small
                                    class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/genu.jpg" alt="user-img"
                                class="img-circle"> <span>Genelia Deshmukh <small
                                    class="text-warning">Away</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/ritesh.jpg"
                                alt="user-img"
                                class="img-circle"> <span>Ritesh Deshmukh <small
                                    class="text-danger">Busy</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/arijit.jpg"
                                alt="user-img"
                                class="img-circle"> <span>Arijit Sinh <small
                                    class="text-muted">Offline</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/govinda.jpg"
                                alt="user-img"
                                class="img-circle"> <span>Govinda Star <small
                                    class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/hritik.jpg"
                                alt="user-img"
                                class="img-circle"> <span>John Abraham<small
                                    class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/john.jpg" alt="user-img"
                                class="img-circle"> <span>Hritik Roshan<small
                                    class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img
                                src="<?php echo $ruta; ?>recursos/plugins/images/users/pawandeep.jpg"
                                alt="user-img"
                                class="img-circle"> <span>Pwandeep rajan <small
                                    class="text-success">online</small></span></a>
                </li>
                </ul>-->
            </div>
          </div>
        </div>
        <!-- /.right-sidebar -->
      </div>
      <!-- /.container-fluid -->
      <footer class="footer text-center"> <?= date('Y') ?> &copy; SID - Un producto de <a href="www.prosode.com">PROSODE
        SAS</a>
      </footer>
    </div>
    <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/ion-rangeslider/js/vendor/jquery-1.12.3.min.js"></script>
    <!-- jQuery -->
    <!--<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/jquery/dist/jquery.min.js"></script>-->
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/jqueryui/jquery-ui.js"></script>
    <script src="<?php echo $ruta; ?>recursos/bootstrap/dist/js/tether.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/compMaps.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/locationpicker.jquery.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/gmaps/gmaps.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/jquery.elevateZoom-3.0.8.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!--Morris JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/raphael/raphael-min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/morrisjs/morris.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo $ruta; ?>recursos/js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo $ruta; ?>recursos/js/custom.js?v=<?= date('YmdHis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/dashboard1.js?v=<?= date('YmdHis') ?>"></script>
    <!--Style Switcher -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/chosen/chosen.jquery.js"></script>
    <!-- Sweet-Alert  -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/blockUI/jquery.blockUI.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/screenfull/dist/screenfull.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/uiDraggable.js"></script>
    <!-- datetimepicker  -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datetimepicker/moment-with-locales.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datetimepicker/bootstrap-datetimepicker.js"></script>
    <!--  VALIDATION.JS -->
    <script src="<?php echo $ruta; ?>recursos/js/Validacion.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/excanvas.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.pie.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.time.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.stack.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.crosshair.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.time.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot/jquery.flot.categories.js"></script>
    <script src="<?php echo $ruta ?>recursos/plugins/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/flot-valuelabels-master/jquery.flot.valuelabels.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/flot.stackpercent-master/jquery.flot.stackpercent.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/flotanimator-master/jquery.flot.animator.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/select2/js/select2.min.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/highcharts/highcharts.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/highcharts/modules/series-label.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/highcharts/modules/exporting.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/highcharts/modules/export-data.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/FacturacionElectronica.js?v=<?= date('Ymdis') ?>"></script>
    <script>
      var messagingSenderId = '<?= $this->session->userdata('FIREBASE_MESSAGINGSENDERID'); ?>'
    </script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="<?php echo $ruta ?>recursos/js/firebase/firebase.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/firebase/firebase-app.js"></script>
    <script src="<?php echo $ruta ?>recursos/js/firebase/firebase-messaging.js"></script>
    <script>
      var tokenControlAmbiental = 0;
      // Your web app's Firebase configuration
      var firebaseConfig = {
          apiKey: '<?= $this->session->userdata('FIREBASE_APIKEY'); ?>',
          authDomain: '<?= $this->session->userdata('FIREBASE_AUTHDOMAIN'); ?>',
          databaseURL: '<?= $this->session->userdata('FIREBASE_DATABASEURL'); ?>',
          projectId: '<?= $this->session->userdata('FIREBASE_PROJECTID'); ?>',
          storageBucket: '<?= $this->session->userdata('FIREBASE_STORAGEBUCKET'); ?>',
          messagingSenderId: '<?= $this->session->userdata('FIREBASE_MESSAGINGSENDERID'); ?>',
          appId: '<?= $this->session->userdata('FIREBASE_APPID'); ?>',
      };
      
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      
      const messaging = firebase.messaging();
      messaging.usePublicVapidKey('<?= $this->session->userdata('FIREBASE_CERTIFIC_PUSH_WEB'); ?>');
      
      messaging.requestPermission().then(function() {
          console.log('notificacion granted')
          messaging.getToken().then(function(currentToken) {
              tokenControlAmbiental = currentToken;
              console.log('currenttoken', currentToken)
          }).catch(function(err) {
              console.log('err', err)
          });
      }).catch(function(err) {
          console.log('unable to get permmission to notify', err)
      });
      messaging.onMessage().then(function(payload) {
          var obj = JSON.parse(payload.data.notification)
          var notification = new Notification(obj.title, {
              body: obj.body,
              icon: obj.icon
          })
      })
    </script>
    <script>
      var baseurl = '<?php echo base_url(); ?>';
      var currentuser = '<?= $this->session->userdata('nUsuCodigo'); ?>';
      var nit = '<?= $this->session->userdata('NIT'); ?>';
      var empresa_nombre = '<?= $this->session->userdata('EMPRESA_NOMBRE'); ?>';
      
      var control_items = []; //almacena los valores de los controles ambiental
      
      var permRole = new Array(); //Va a almacenar, los permisos que tiene el rol del usuario logueado
    </script>
    <script src="<?php echo $ruta ?>recursos/js/app.js?v=<?= date('Ymdis') ?>"></script>
    <!---Services Javascript --->
    <script src="<?php echo $ruta; ?>recursos/js/services/CarteraService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/CuentasPorPagarService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/Cliente.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/Unidades.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/Producto.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/InventarioService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/UtilitiesService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/EmpresaAfiliadaService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/AfiliadoDescuentosService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/DrogueriaRelacionadaService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/RolesService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/FactElectronicaServices.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/AjusteInventarioService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/DocumentoInventarioService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/SecurityService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/VentaService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/StatusCajaService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/TrasladoService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/ServerServices.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/GrupoService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/UsuarioService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/NotificacionService.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/services/Gasto.js?v=<?= date('Ymdis') ?>"></script>
    <!--Controllers Javascript-->
    <!---Utilities-->
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Utilities.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/ControlAmbiental.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Producto.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/EmpresaAfiliada.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Venta.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Gasto.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Cliente.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Ingresos.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Cartera.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/CuentasPorPagar.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/DrogueriaRelacionada.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/AjusteInventario.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/DocumentoInventario.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/VentaAnular.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Roles.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/StatusCaja.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Traslado.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Server.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/controllers/Notificaciones.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/js/messages.js?v=<?= date('Ymdis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables/media/js/dataTables.bootstrap.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/datatables-plugins/api/sum().js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/tablesDatatables.js?v=<?= date('YmdHis') ?>"></script>
    <script src="<?php echo $ruta; ?>recursos/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <input type="hidden" id="base_url" value="<?= base_url() ?>">
    <input type="hidden" id="idlocal" value="<?= $this->session->userdata('id_local'); ?>">
    <input type="hidden" id="MOSTRAR_SIN_STOCK" value="<?= $this->session->userdata('MOSTRAR_SIN_STOCK'); ?>">
    <input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
    <input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
    <input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
    <input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
    <input type="hidden" id="FACT_E_habilitacionn" value="<?= $this->session->userdata('FACT_E_habilitacionn'); ?>">
    <input type="hidden" id="FACT_E_syncrono" value="<?= $this->session->userdata('FACT_E_syncrono'); ?>">
    <input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">
    <input type="hidden" id="VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS" value="<?= $this->session->userdata('VENTAS_MOSTRAR_TODOS_LOS_PRODUCTOS'); ?>">
    <input type="hidden" id="VENDEDOR_EN_FACTURA" value="<?= $this->session->userdata('VENDEDOR_EN_FACTURA'); ?>">
    <input type="hidden" id="ALLOW_FACT_E" value="<?php echo $this->session->userdata('FACT_E_ALLOW'); ?>">
    <script>
      var name_user = '<?= $this->session->userdata('nombre') ?>'
      /* function speak(texto) {
      
      if(texto!='') {
          var SpeechSynthesisUtterance = window.webkitSpeechSynthesisUtterance ||
                  window.mozSpeechSynthesisUtterance ||
                  window.msSpeechSynthesisUtterance ||
                  window.oSpeechSynthesisUtterance ||
                  window.SpeechSynthesisUtterance;
          if ( SpeechSynthesisUtterance !== undefined ) {
              var s = new SpeechSynthesisUtterance();
              s.volume = 0.5;
              s.rate = 1;
              s.pitch = 1;
              s.lang = 'es-MX';
              s.text = 'Hola '+texto+', bienvenido!';
              window.speechSynthesis.speak(s);
          }
      
      }
      }
      speak(name_user);
      */
      /* Initialize app when page loads */
      $(function() {
      
          //esta funcion dice que antes que se haga una peticion ajax valide la sesion
      
          jQuery('#catalogo_template').on('hidden.bs.modal', function(e) {
      
              $("#catalogo_template").html('');
      
          });
          Utilities.init(<?= json_encode($droguerias) ?>, <?= json_encode($unidades_medida) ?>);
          App.init(<?php echo count($cajas_abiertas) ?>, '<?php echo $aperturocaja ?>', '<?php echo $this->session->userdata('caja_id') ?>', '<?= $this->session->userdata('PANTALLA_COMPLETA') ?>');
      });
      
      function cerrar_modal_catalogo() {
          $("#catalogo").modal('hide');
      
      }
    </script>
  </body>
  <div id="apertura_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  </div>
  <!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
  <div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header text-center">
          <h2 class="modal-title"><i class="fa fa-pencil"></i> Mi Perfil</h2>
        </div>
        <!-- END Modal Header -->
        <!-- Modal Body -->
        <div class="modal-body">
          <form action="<?= $ruta ?>/usuario/registrar" method="post" id="modal-user-settings-form" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
            <fieldset>
              <legend>Informaci&oacute;n</legend>
              <div class="form-group">
                <label class="col-md-4 control-label">Username</label>
                <input type="hidden" value="<?= $this->session->userdata('nUsuCodigo') ?>" name="nUsuCodigo">
                <input type="hidden" value="<?= $this->session->userdata('username') ?>" name="username">
                <div class="col-md-8">
                  <p class="form-control-static"><?= $this->session->userdata('username') ?></p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label" for="nombre">Nombre</label>
                <div class="col-md-8">
                  <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $this->session->userdata('nombre') ?>">
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend>Cambio de password</legend>
              <div class="form-group">
                <label class="col-md-4 control-label" for="user-settings-password">Nuevo Password</label>
                <div class="col-md-8">
                  <input type="password" id="user-settings-password" name="var_usuario_clave" class="form-control" placeholder="Ingrese un nuevo password">
                </div>
              </div>
            </fieldset>
            <div class="form-group form-actions">
              <div class="col-xs-12 text-right">
                <button type="button" id="" class="btn btn-primary" onclick="miperfil.guardar()">Confirmar
                </button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </form>
        </div>
        <!-- END Modal Body -->
      </div>
    </div>
  </div>
  <!-- END User Settings -->
  <div id="cierre_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  </div>
  <div class="modal fade" id="addcontrol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  </div>
  <input type="hidden" id="base_url" value="<?= base_url() ?>">
  <div class="modal fade" id="mvisualizarVenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
  <div class="modal" id="visualizar_cada_historial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  </div>
  <div class="modal fade" id=globalModal tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
  <div class="modal fade" id=confirmupdateModal tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          Actualizar base de datos
        </div>
        <div class="modal-body">
          <p>El sistema se actualizará, por favor no cierre ni refresque la ventana</p>
          <h4>¿Desea continuar con la actualizaci&oacute;n?</h4>
          <button class="btn btn-success" onclick="App.confirmUpate()">Aceptar</button>
          <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id=confirmupdateRepositoryModal tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          Actualizar sistema
        </div>
        <div class="modal-body">
          <p>El sistema se actualizará, por favor no cierre ni refresque la ventana</p>
          <h4>¿Desea continuar con la actualizacion?</h4>
          <button class="btn btn-success" onclick="App.confirmUpateRepository()">Aceptar</button>
          <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="barloadermodal" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          Cargando . . .
        </div>
        <div class="modal-body">
          <!-- <h3>Cargando Imagen, por favor espere...</h3>-->
          <div class="progress">
            <div class="progress-bar  progress-bar-striped progress-bar-info active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
              <span class="sr-only">Cargando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="catalogo_template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
  <div id="alertbottom" class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-warning myadmin-alert-bottom alertbottom"><i class="ti-user"></i> Hay cajas abiertas. Debe amarrar sus ventas a una de las cajas abiertas o aperturar una
    caja <a href="#" class="closed">×</a>
  </div>
  <div id="alertbottom2" class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-warning myadmin-alert-bottom alertbottom2"><i class="ti-user"></i>Atención. No hay cajas abiertas. Debes aperturar una caja para vender <a href="#" class="closed">×</a></div>
  <div class="modal bs-example-modal-xl" id="modaldroguerias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
          </button>
          <h4 class="modal-title">Stock Droguerias Relacionadas</h4>
          <h5 id="nombreproduto"></h5>
        </div>
        <div class="modal-body" id="">
          <div class="row">
            <div class="col-md-3">
              <div id="tabacatalogo_filter" class="dataTables_filter">
                <label>
                  <div class="input-group"><input type="search" name="search" id="drogueriasearch" class="form-control" placeholder="Buscar..." aria-controls="tabacatalogo">
                  </div>
                </label>
              </div>
            </div>
            <div class="col-md-2">
              <button type="button" id="buscarcatalogodroguerias" onclick="Utilities.buscarCatalogoDroguerias();" class="btn btn-success">Buscar
              </button>
            </div>
          </div>
          <div class="row">
            <table id="tabacatalogo" class="table datatable table-bordered table-hover table-condensed ">
              <thead>
                <th>Codigo</th>
                <th>Nombre</th>
                <?php
                  foreach ($droguerias as $drogueria) {
                  ?>
                <th>
                  <?= $drogueria['drogueria_nombre'] ?>
                  <table class="table table-condensed ">
                    <tr>
                      <?php
                        foreach ($unidades_medida as $unidad) {
                        ?>
                      <td><?= $unidad['nombre_unidad'] ?></td>
                      <?php } ?>
                    </tr>
                  </table>
                </th>
                <?php
                  } ?>
              </thead>
              <tbody id="tbodydroguerias">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal bs-example-modal-lg" id="modalcatalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close " data-dismiss="modal" aria-hidden="true">&times;
          </button>
          <h4 class="modal-title">Catálogo Droguería</h4>
          <h5 id="nombreproduto"></h5>
        </div>
        <div class="modal-body" id="">
          <div class="row">
            <table id="tabacatalogo" class="table datatable table-bordered table-striped ">
              <thead>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Ubicacion</th>
                <th>Principio activo</th>
              <tbody id="tbodycatalogo">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal bs-example-modal-lg" id="modalNewNotificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close " data-dismiss="modal" aria-hidden="true">&times;
          </button>
          <h4 class="modal-title">Enviar nueva Notificación</h4>
        </div>
        <div class="modal-body" id="">
          <div class="row">
            <form name="formagregar" action="#" method="post">
              <div class="form-group">
                <div class="col-md-2">
                  Aplicación
                </div>
                <div class="col-md-10">
                  <select class="form-control" id="topicnewnotif">
                    <option value="/topics/appcustomer" selected>Aplicación Clientes</option>
                  </select>
                </div>
                <div class="col-md-2">
                  Título
                </div>
                <div class="col-md-10">
                  <input type="text" name="nombre" id="titulonewnotif" class="form-control" value="">
                </div>
                <div class="col-md-2">
                  Mensaje
                </div>
                <div class="col-md-10">
                  <textarea class="form-control" name="textareanewnotif" id="textareanewnotif"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal fade" id="modalconfirmsendnotif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="width: 60%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" onclick="$('#modalconfirmsendnotif').modal('hide')" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">Confirmar</h4>
              </div>
              <div class="modal-body">
                <h2>Est&aacute; seguro que quiere enviar este mensaje a todos los usuarios?
                  Si est&aacute; de acuerdo, presione confirmar
                </h2>
              </div>
              <div class="modal-footer">
                <div class="text-left col-md-2" id="">
                  <a href="#" class="btn btn-primary" style="text-align: left" onclick="Notificaciones.confirmSendMsj()">Confirmar</a>
                </div>
                <div class="text-right" id="">
                  <button type="button" class="btn btn-default" onclick="$('#modalconfirmsendnotif').modal('hide')">
                  Cancelar
                  </button>
                </div>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="Notificaciones.modalconfirmsendnotif()">Confirmar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  <?php
    $permRole = $this->usuarios_grupos_model->get_user_perms($this->session->userdata('nUsuCodigo'));
    
    ?>
  <script>
    function llegandatosAlTemplate(permsR){
        permRole = permsR
    }
    
    $(function() {
    
        llegandatosAlTemplate(<?= json_encode($permRole) ?>)
    
        $("body").on("keydown", function(e) {
    
            if (e.keyCode == 34) {
                return false;
            }
        });
        /*  var data = new FormData();
    
          // If you want to add an extra field for the FormData
          data.append("api_token", "1d00edcf0bafc5b769545bdae29f39a8094161c431f98cc6d333b6553ea6c674");
          $.ajax({
              url:'http://127.0.0.1:8000/api/ubl2.1/invoice/39ea5560-6014-4138-8327-0370a6636edf',
              type:'post',
              dataType:'json',
              data: data,
              processData: false,
              contentType: false,
              cache: false,
              headers: {
                  "Authorization": "Bearer 1d00edcf0bafc5b769545bdae29f39a8094161c431f98cc6d333b6553ea6c674",
                  "Authorization": "1d00edcf0bafc5b769545bdae29f39a8094161c431f98cc6d333b6553ea6c674",
                  "api_token": "1d00edcf0bafc5b769545bdae29f39a8094161c431f98cc6d333b6553ea6c674",
                  "X-CSRF-TOKEN":"",
                  "accept":"application/json",
                  "Content-Type":"application/json",
              },
              //data:{api_token:'1d00edcf0bafc5b769545bdae29f39a8094161c431f98cc6d333b6553ea6c674'},
              success: function (){
                  alert('Thanks for your comment!');
              }
          })*/
        var data = <?= json_encode($licencia) ?>;
        var callmapadomicilio = '<?= $callmapadomicilio ?>';
    
        var remaining = parseInt(data.remaining);
    
        console.log(remaining);
    
        var diasrenovar = 8 + remaining;
        if (remaining <= 5 && remaining < 0) {
    
            Utilities.alertModal('Su licencia ha expirado hace ' + remaining + ' dias, recuerde renovar su licencia antes de ' +
                diasrenovar + ' dias o no podrá seguir usando el sistema', 'warning', false);
        }
    
        if (remaining <= 5 && remaining > 0) {
    
            Utilities.alertModal('Su licencia está a ' + remaining + ' dias de expirar', 'warning', false);
        }
    
        //con esto mando a llamar a la vistadel mapa de los domiciliarios si es el caso
        if (callmapadomicilio == "true") {
            var url = baseurl + "venta/viewMapaDomiciliario";
    
            $.ajax({
                url: url,
                type: 'POST',
                success: function(data) {
                    $("#page-content").html(data);
    
                },
                error: function() {
    
                }
            });
    
        }
    
        /*las dos siguientes setencias, es para que al cerrar dos modales, no se contraiga la pantalla*/
        $(document.body).on('hide.bs.modal,hidden.bs.modal', function() {
            $('body').css('padding-right', '0');
    
        });
        $(document.body).on('shown.bs.modal,show.bs.modal', function() {
            $('body').css('padding-right', '0');
    
        });
    
    
    });
  </script>
  <input type="hidden" id="GLOBAL_ID_LOCAL" value="<?= $this->session->userdata('id_local'); ?>">
  <input type="hidden" id="MOSTRAR_SIN_STOCK" value="<?php echo ($this->session->userdata('MOSTRAR_SIN_STOCK') == true) ? 'true' : 'false'; ?>">
  </body>
</html>