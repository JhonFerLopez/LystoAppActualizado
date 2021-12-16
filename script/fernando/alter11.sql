ALTER TABLE `detalle_ingreso_unidad`
  ADD COLUMN `impuesto_porcentaje` FLOAT(20) NULL AFTER `impuesto`;

  ALTER TABLE `ingreso`
  ADD COLUMN `total_bonificado` DOUBLE NULL AFTER `condicion_pago`,
  ADD COLUMN `total_descuento` DOUBLE NULL AFTER `total_bonificado`;

  ALTER TABLE `detalleingreso`
  ADD COLUMN `bonificacion` FLOAT(20,2) NULL AFTER `total_detalle`,
  ADD COLUMN `descuento` FLOAT(20,2) NULL AFTER `bonificacion`;

  ALTER TABLE `detalleingreso`
  CHANGE `bonificacion` `porcentaje_bonificacion` FLOAT(20,2) NULL,
  CHANGE `descuento` `porcentaje_descuento` FLOAT(20,2) NULL;






