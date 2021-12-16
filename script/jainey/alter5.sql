/**********REGIMEN CONTRIBUTIVO*******/

CREATE TABLE `regimen`(
  `regmen_id` BIGINT,
  `regimen_nombre` VARCHAR(255),
  `compra_retienen` BOOLEAN DEFAULT FALSE,
  `compra_retienen_iva` BOOLEAN DEFAULT FALSE,
  `venta_retienen` BOOLEAN DEFAULT FALSE,
  `venta_retienen_iva` BOOLEAN DEFAULT FALSE,
  `genera_iva` BOOLEAN DEFAULT FALSE,
  `autoretenedor` BOOLEAN DEFAULT FALSE,
  `gran_contribuyente` BOOLEAN DEFAULT FALSE
);

ALTER TABLE `regimen`
  ADD COLUMN `deleted_at` DATETIME NULL AFTER `gran_contribuyente`;
ALTER TABLE `regimen`
  CHANGE `regmen_id` `regimen_id` BIGINT(20) NULL;
ALTER TABLE `regimen`
  CHANGE `regimen_id` `regimen_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (`regimen_id`);


ALTER TABLE `proveedor`
  DROP COLUMN `proveedor_nrofax`,
  DROP COLUMN `proveedor_paginaweb`,
  CHANGE `proveedor_nombre` `proveedor_nombre` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NOT NULL,
  CHANGE `proveedor_direccion1` `proveedor_direccion` TEXT CHARSET latin1 COLLATE latin1_swedish_ci NOT NULL,
  CHANGE `proveedor_email` `proveedor_email` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  CHANGE `proveedor_telefono1` `proveedor_telefono1` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NOT NULL,
  CHANGE `proveedor_telefono2` `proveedor_telefono2` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NOT NULL,
  CHANGE `proveedor_status` `proveedor_tipo` BIGINT NOT NULL,
  CHANGE `proveedor_observacion` `proveedor_regimen` BIGINT NULL,
  CHANGE `proveedor_direccion2` `proveedor_digito_verificacion` VARCHAR(2) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  ADD COLUMN `proveedor_identificacion` VARCHAR(255) NULL AFTER `proveedor_digito_verificacion`,
  ADD COLUMN `proveedor_celular` VARCHAR(255) NULL AFTER `proveedor_identificacion`,
  ADD COLUMN `proveedor_ciudad` BIGINT NULL AFTER `proveedor_celular`,
  ADD COLUMN `deleted_at` DATETIME NULL AFTER `proveedor_ciudad`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`id_proveedor`, `proveedor_telefono2`);


ALTER TABLE `proveedor`
  ADD COLUMN `longitud` VARCHAR(255) NULL AFTER `proveedor_ciudad`,
  ADD COLUMN `latitud` VARCHAR(255) NULL AFTER `longitud`;


ALTER TABLE `proveedor`
  ADD FOREIGN KEY (`proveedor_ciudad`) REFERENCES `ciudades`(`ciudad_id`);

  ALTER TABLE `proveedor`
  ADD FOREIGN KEY (`proveedor_regimen`) REFERENCES `regimen`(`regimen_id`),
  ADD FOREIGN KEY (`proveedor_tipo`) REFERENCES `tipo_proveedor`(`tipo_proveedor_id`);


/*******METODOS DE PAGO****/
ALTER TABLE `metodos_pago`
  ADD COLUMN `suma_total_ingreso` BOOLEAN DEFAULT 0  NULL AFTER `tipo_metodo`,
  ADD COLUMN `incluye_cuadre_caja` BOOLEAN DEFAULT 0  NULL AFTER `suma_total_ingreso`;
ALTER TABLE `metodos_pago`
  CHANGE `status_metodo` `deleted_at` DATETIME NULL;
