CREATE TABLE `recibo_pago_cliente`(
  `recibo_id` BIGINT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`recibo_id`)
);
ALTER TABLE `recibo_pago_cliente`
  ENGINE=INNODB;

ALTER TABLE `historial_pagos_clientes`
  ADD COLUMN `recibo_id` BIGINT(20) NULL AFTER `observaciones_adicionales`,
  ADD FOREIGN KEY (`recibo_id`) REFERENCES `recibo_pago_cliente`(`recibo_id`);

CREATE TABLE `recibo_pago_proveedor`(
  `recibo_id` BIGINT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`recibo_id`)
);
ALTER TABLE `recibo_pago_proveedor`
  ENGINE=INNODB;

ALTER TABLE `pagos_ingreso`
  ADD COLUMN `recibo_id` BIGINT NULL AFTER `pagoingreso_restante`,
  ADD FOREIGN KEY (`recibo_id`) REFERENCES `recibo_pago_proveedor`(`recibo_id`);

ALTER TABLE `ingreso`
  DROP COLUMN `tipo_ingreso`,
  ADD COLUMN `condicion_pago` BIGINT(20) NULL AFTER `usuario_costos`,
  ADD FOREIGN KEY (`condicion_pago`) REFERENCES `condiciones_pago`(`id_condiciones`);

ALTER TABLE `ingreso`
  DROP COLUMN `pago`;

ALTER TABLE `afiliado_descuentos`
  ENGINE=INNODB;

ALTER TABLE `afiliado_descuentos`
  ADD FOREIGN KEY (`tipo_prod_id`) REFERENCES `tipo_producto`(`tipo_prod_id`),
  ADD FOREIGN KEY (`unidad_id`) REFERENCES `unidades`(`id_unidad`),
  ADD FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado`(`afiliado_id`);

ALTER TABLE `tipo_proveedor`
  ENGINE=INNODB;

ALTER TABLE `proveedor`
  ADD FOREIGN KEY (`proveedor_tipo`) REFERENCES `tipo_proveedor`(`tipo_proveedor_id`);
