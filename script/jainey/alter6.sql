CREATE TABLE `tipo_anulacion`(
  `tipo_anulacion_id` BIGINT NOT NULL AUTO_INCREMENT,
  `tipo_anulacion_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`tipo_anulacion_id`)
);

CREATE TABLE `tipo_devolucion`(
  `tipo_devolucion_id` BIGINT NOT NULL AUTO_INCREMENT,
  `tipo_devolucion_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`tipo_devolucion_id`)
);


CREATE TABLE `tipo_venta`(
  `tipo_venta_id` BIGINT NOT NULL AUTO_INCREMENT,
  `tipo_venta_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`tipo_venta_id`)
);

ALTER TABLE tipo_venta
  ADD COLUMN `solicita_cod_vendedor` BOOLEAN DEFAULT 0  NULL AFTER `tipo_venta_nombre`,
  ADD COLUMN `genera_datos_cartera` BOOLEAN DEFAULT 0  NULL AFTER `solicita_cod_vendedor`,
  ADD COLUMN `admite_datos_cliente` BOOLEAN DEFAULT 0  NULL AFTER `genera_datos_cartera`,
  ADD COLUMN `datos_adic_clientes` BOOLEAN DEFAULT 0  NULL AFTER `admite_datos_cliente`,
  ADD COLUMN `genera_control_domicilios` BOOLEAN DEFAULT 0  NULL AFTER `datos_adic_clientes`,
  ADD COLUMN `maneja_formas_pago` BOOLEAN DEFAULT 0  NULL AFTER `genera_control_domicilios`,
  ADD COLUMN `liquida_iva` BOOLEAN DEFAULT 0  NULL AFTER `maneja_formas_pago`,
  ADD COLUMN `maneja_descuentos` BOOLEAN DEFAULT 0  NULL AFTER `liquida_iva`,
  ADD COLUMN `aproximar_precio` INT NULL AFTER `maneja_descuentos`,
  ADD COLUMN `documento_generar` VARCHAR(255) NULL AFTER `aproximar_precio`,
  ADD COLUMN `numero_copias` INT NULL AFTER `documento_generar`,
  ADD COLUMN `opciones_call_center` BOOLEAN DEFAULT 0  NULL AFTER `numero_copias`;


/*********REOSLUCION DIAN**/
CREATE TABLE `resolucion_dian`(
  `resolucion_id` BIGINT,
  `resolucion_numero` INT,
  `resolucion_prefijo` VARCHAR(255),
  `resolucion_numero_inicial` INT,
  `resolucion_numero_final` BIGINT,
  `resolucion_fech_aprobacion` DATE,
  `resolucion_avisar` BIGINT
);

ALTER TABLE resolucion_dian
  ADD COLUMN `deleted_at` DATETIME NULL AFTER `resolucion_avisar`;

  ALTER TABLE `resolucion_dian`
  CHANGE `resolucion_id` `resolucion_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (`resolucion_id`);

