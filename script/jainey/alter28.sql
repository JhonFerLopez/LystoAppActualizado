DELETE FROM opcion_grupo;
DELETE FROM opcion;
ALTER TABLE opcion AUTO_INCREMENT = 1;



insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (1,NULL,'parametrizacion','Parametrización');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (2,NULL,'ventas','Ventas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (3,NULL,'inventario','Inventario');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (4,NULL,'compras','Compras');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (5,NULL,'cartera','Cartera');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (6,NULL,'cuentasporpagar','Cuentas por pagar');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (7,NULL,'reportes','Reportes');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (8,NULL,'utilidad','Utilidad');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (9,1,'parametrosproductos','Parametros productos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (10,1,'parametrosclientes','Parametros clientes');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (11,1,'parametrosproveedores','Parametros proveedores');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (12,1,'parametrosfacturacion','Parametros facturación');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (13,1,'parametrosinventario','Parametros inventario');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (14,1,'parametrosinstalacion','Parametros instalación');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (15,2,'generarventa','Generar venta');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (16,2,'historialventas','Historial de ventas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (17,2,'anularventa','Anular venta');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (18,2,'devolucionventa','Devolver venta');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (19,3,'stockbodegas','Stock bodegas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (20,3,'stockdroguerias','Stock droguerias');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (21,3,'registrarfisicos','Registrar fisicos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (22,3,'movimientosdiarios','Movimientos diarios');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (23,3,'consultamovimientos','Consultar movimientos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (24,4,'registraringreo','Registrar compra');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (25,4,'consultaringresos','Cosultar compras');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (26,5,'movimientoscartera','Movimintos de cartera');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (27,5,'generarreciboscliente','Generar recibos cliente');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (28,6,'movimientoproveedor','Movimientos proveedor');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (29,6,'generarcomprobanteproveedor','Generar comprobantes');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (30,8,'seguridad','Seguridad');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (31,8,'drogueriasrelacionadas','Droguerias relacionadas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (32,8,'condicionespago','Condiciones pago');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (33,8,'localizacion','Localizacion');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (34,9,'productos','Productos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (35,9,'clasificacion','Clasificacion');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (36,9,'tipo_producto','Tipo');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (37,9,'componentes','Componentes');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (38,9,'gruposproductos','Grupos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (39,9,'ubicacion_fisica','Ubicacion fisica');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (40,9,'impuestos','Impuestos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (41,9,'unidadesmedida','Unidades de medida');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (42,10,'clientes','Clientes');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (43,10,'gruposcliente','Tipos de cliente');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (44,11,'proveedor','Proveedores');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (45,11,'tipoproveedor','Tipos de proveedor');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (46,11,'regimencontributivo','Regimen contributivo');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (47,12,'afiliado','Empresas afiliadas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (48,12,'metodospago','Formas de pago');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (49,12,'tiposanulacion','Tipos de anulación');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (50,12,'tiposdevolucion','Tipos de devolucion');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (51,12,'tiposventa','Tipos de venta');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (52,12,'reosluciondian','Resolucion de la DIAN');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (53,13,'tipomovimiento','Tipos de moviminto');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (54,13,'bodegas','Bodegas');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (55,14,'opcionesgenerales','Opciones generales');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (56,14,'bancos','Bancos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (57,21,'registrarfisicotodos','Registra todos los productos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (58,21,'registrafisicoporproducto','Registra por producto');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (59,30,'usuarios','Usuarios');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (60,30,'roles','Roles');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (61,33,'pais','Paises');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (62,33,'departamentos','Departamentos');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (63,33,'ciudad','Ciudades');
insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionNombre`,`cOpcionDescripcion`) values (64,33,'barrios','Barrios');

INSERT INTO opcion_grupo VALUES(1,8, 1);
INSERT INTO opcion_grupo VALUES(1,30, 1);
INSERT INTO opcion_grupo VALUES(1,60, 1);

ALTER TABLE  `ajustedetalle`
  ADD COLUMN `costo` FLOAT NULL AFTER `id_inventario`;
