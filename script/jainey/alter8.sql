ALTER TABLE `tipo_venta`
  ADD COLUMN `condicion_pago` BIGINT(20) NULL AFTER `deleted_at`,
  ADD FOREIGN KEY (`condicion_pago`) REFERENCES `condiciones_pago`(`id_condiciones`);

ALTER TABLE `venta`
  DROP COLUMN `condicion_pago`,
  DROP INDEX `ventacondicionpagofk_idx`,
  DROP FOREIGN KEY `venta_ibfk_2`;

ALTER TABLE `detalle_venta`
  DROP COLUMN `unidad_medida`,
  CHANGE `cantidad` `cantidad` LONGTEXT NOT NULL,
  DROP INDEX `transaccion_ibfk_3_idx`,
  DROP FOREIGN KEY `transaccion_ibfk_3`;

CREATE TABLE `detalle_venta_unidad`(
  `unidad_id` BIGINT,
  `cantidad` DECIMAL,
  `precio` DECIMAL
);

ALTER TABLE `detalle_venta_unidad`
  ADD COLUMN `detalle_venta_id` BIGINT(20) NULL AFTER `precio`;


ALTER TABLE `detalle_venta`
  DROP COLUMN `precio`,
  DROP INDEX `transaccion_ibfk_2_idx`;

ALTER TABLE `detalle_venta`
  DROP COLUMN `cantidad`;

ALTER TABLE `detalle_venta_unidad`
  ADD FOREIGN KEY (`detalle_venta_id`) REFERENCES `detalle_venta`(`id_detalle`),
  ADD FOREIGN KEY (`unidad_id`) REFERENCES `unidades`(`id_unidad`);

ALTER TABLE `venta`
  DROP COLUMN `venta_tipo`;

  ALTER TABLE `venta`
  CHANGE `tipo_venta` `venta_tipo` BIGINT(20) NULL  COMMENT 'Este campo viene de la tabla tipo_venta',
  DROP FOREIGN KEY `venta_ibfk_6`;

  ALTER TABLE `venta`
  ADD FOREIGN KEY (`venta_tipo`) REFERENCES `tipo_venta`(`tipo_venta_id`);



