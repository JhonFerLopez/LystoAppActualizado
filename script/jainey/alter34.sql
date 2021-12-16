ALTER TABLE `venta`
  DROP COLUMN `confirmacion_caja`,
  DROP COLUMN `confirmacion_banco`,
  DROP COLUMN `confirmacion_fecha`,
  DROP COLUMN `confirmacion_usuario`,
  DROP INDEX `confirmacion_caja`,
  DROP INDEX `confirmacion_banco`,
  DROP INDEX `confirmacion_usuario`,
  DROP FOREIGN KEY `venta_ibfk_3`,
  DROP FOREIGN KEY `venta_ibfk_4`,
  DROP FOREIGN KEY `venta_ibfk_5`;
