CREATE TABLE `droguerias_relacionadas` (
  `drogueria_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `drogueria_nombre` text,
  `drogueria_domain` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`drogueria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `droguerias_relacionadas`
ADD COLUMN `drogueria_deleted` BOOLEAN DEFAULT 0  NULL AFTER `drogueria_domain`;
ALTER TABLE  `droguerias_relacionadas`
CHANGE `drogueria_deleted` `deleted_at` TINYINT(1) DEFAULT 0  NULL;
ALTER TABLE `droguerias_relacionadas`
CHANGE `deleted_at` `deleted_at` DATETIME NULL;


ALTER TABLE `recibo_pago_proveedor`
ADD COLUMN `usuario` BIGINT(20) NULL AFTER `recibo_id`,
ADD COLUMN `metodo_pago` BIGINT(20) NULL AFTER `usuario`,
ADD FOREIGN KEY (`usuario`) REFERENCES `usuario`(`nUsuCodigo`),
ADD FOREIGN KEY (`metodo_pago`) REFERENCES`metodos_pago`(`id_metodo`);

ALTER TABLE `recibo_pago_proveedor`
ADD COLUMN `observaciones_adicionales` TEXT NULL AFTER `metodo_pago`;

ALTER TABLE `historial_pagos_clientes`
DROP COLUMN `historial_tipopago`,
DROP COLUMN `historial_usuario`,
DROP COLUMN `historial_estatus`,
DROP COLUMN `historial_banco_id`,
DROP COLUMN `historial_caja_id`,
DROP COLUMN `observaciones_adicionales`,
DROP INDEX `historialcrono_tipopago`,
DROP INDEX `historial_usuario`,
DROP INDEX `historial_banco_id`,
DROP INDEX `historial_caja_id`,
DROP FOREIGN KEY `historial_pagos_clientes_ibfk_2`,
DROP FOREIGN KEY `historial_pagos_clientes_ibfk_3`,
DROP FOREIGN KEY `historial_pagos_clientes_ibfk_4`;

ALTER TABLE `recibo_pago_cliente`
ADD COLUMN `usuario` BIGINT(20) NULL AFTER `recibo_id`,
ADD COLUMN `banco` BIGINT(20) DEFAULT NULL  NULL AFTER `usuario`,
ADD COLUMN `observaciones_adicionales` TEXT NULL AFTER `banco`,
ADD COLUMN `metodo` BIGINT(20) NULL AFTER `observaciones_adicionales`,
ADD FOREIGN KEY (`usuario`) REFERENCES `usuario`(`nUsuCodigo`),
ADD FOREIGN KEY (`banco`) REFERENCES `banco`(`banco_id`),
ADD FOREIGN KEY (`metodo`) REFERENCES `metodos_pago`(`id_metodo`);

ALTER TABLE `recibo_pago_cliente`
ADD COLUMN `fecha_consignacion` DATE NULL AFTER `metodo`,
ADD COLUMN `numero_documento` VARCHAR(100) NULL AFTER `fecha_consignacion`;


ALTER TABLE `recibo_pago_proveedor`
ADD COLUMN `fecha_consignacion` DATE NULL AFTER `observaciones_adicionales`,
ADD COLUMN `numero_documento` VARCHAR(100) NULL AFTER `fecha_consignacion`,
ADD COLUMN `banco` BIGINT(20) NULL AFTER `numero_documento`;

ALTER TABLE `historial_pagos_clientes`
DROP COLUMN `historial_fecha`;

ALTER TABLE `recibo_pago_cliente`
ADD COLUMN `fecha` DATETIME NULL AFTER `numero_documento`;

ALTER TABLE `recibo_pago_proveedor`
ADD COLUMN `fecha` DATETIME NULL AFTER `banco`;

ALTER TABLE `pagos_ingreso`
DROP COLUMN `pagoingreso_fecha`;
