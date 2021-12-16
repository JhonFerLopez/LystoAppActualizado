
/*se hace esto ya que se va a usar ahora las condiciones de pago para los precios de las unidades */

ALTER TABLE `unidades_has_precio`
  DROP FOREIGN KEY `fk_precios_has_unidades_has_producto_precios1`;
  ALTER TABLE `unidades_has_precio`
  CHANGE `id_precio` `id_condiciones_pago` BIGINT(20) NOT NULL;
ALTER TABLE `unidades_has_precio`
  ADD FOREIGN KEY (`id_condiciones_pago`) REFERENCES `condiciones_pago`(`id_condiciones`);
  ALTER TABLE `unidades_has_precio`
  ADD COLUMN `utilidad` DECIMAL(18,2) NULL AFTER `precio`;


ALTER TABLE `producto`
  ADD COLUMN `producto_costo` DECIMAL(25,2) NULL AFTER `producto_mensaje`,
  ADD COLUMN `costo_promedio` DECIMAL(25,2) NULL AFTER `producto_costo`,
  ADD COLUMN `producto_descuentos` DECIMAL(18,2) NULL AFTER `costo_promedio`,
  ADD COLUMN `costo_cargue` DECIMAL(25,2) NULL AFTER `producto_descuentos`,
  ADD COLUMN `contenido_interno` DECIMAL(25,2) NULL AFTER `costo_cargue`,
  ADD COLUMN `producto_comision` DECIMAL(25,2) NULL AFTER `contenido_interno`,
  ADD COLUMN `producto_bonificaciones` DECIMAL(25,2) NULL AFTER `producto_comision`,
  ADD COLUMN `porcentaje_descuento` DECIMAL(25,2) NULL AFTER `producto_bonificaciones`,
  ADD COLUMN `precio_abierto` BOOLEAN NULL AFTER `porcentaje_descuento`,
  ADD COLUMN `precio_minimo` DECIMAL(25,2) NULL AFTER `precio_abierto`,
  ADD COLUMN `precio_maximo` DECIMAL(25,2) NULL AFTER `precio_minimo`,
  ADD COLUMN `porcentaje_costo` DECIMAL(25,2) NULL AFTER `precio_maximo`;

