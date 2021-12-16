ALTER TABLE `producto`
  DROP COLUMN `venta_sin_stock`;
ALTER TABLE `detalle_venta`
  ADD COLUMN `detalle_importe` FLOAT NULL AFTER `bono`;

  ALTER TABLE `detalle_venta_unidad`
  ADD COLUMN `impuesto` FLOAT NULL AFTER `costo`;

