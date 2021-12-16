ALTER TABLE `unidades_has_producto`
  DROP COLUMN `orden`;
ALTER TABLE `detalle_ingreso_unidad`
  DROP COLUMN `impuesto_porcentaje`;

  ALTER TABLE `detalleingreso`
  ADD COLUMN `impuesto_porcentaje` FLOAT(20,2) NULL AFTER `porcentaje_descuento`;

  ALTER TABLE `detalleingreso`
  ADD COLUMN `total_impuesto` FLOAT(20,2) NULL AFTER `impuesto_porcentaje`;


