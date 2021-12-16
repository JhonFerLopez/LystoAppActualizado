ALTER TABLE `unidades_has_producto`
  ADD COLUMN `stock_minimo` INT(10) NULL AFTER `metros_cubicos`,
  ADD COLUMN `stock_maximo` INT(10) NULL AFTER `stock_minimo`;

ALTER TABLE `inventario`
  DROP COLUMN `fraccion`,
  ADD COLUMN `id_unidad` BIGINT NULL AFTER `id_local`,
  ADD FOREIGN KEY (`id_unidad`) REFERENCES `unidades`(`id_unidad`);

  ALTER TABLE `producto`
  ADD COLUMN `control_inven` BOOLEAN NULL AFTER `producto_ubicacion_fisica`,
  ADD COLUMN `control_inven_diario` BOOLEAN NULL AFTER `control_inven`,
  ADD COLUMN `pedir_fecha_venc` BOOLEAN NULL AFTER `control_inven_diario`;

