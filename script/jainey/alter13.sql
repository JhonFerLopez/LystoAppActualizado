DROP TABLE kardex_fiscal;

ALTER TABLE `detalle_venta`
  DROP COLUMN `precio_sugerido`;

ALTER TABLE `detalle_venta`
  DROP COLUMN `detalle_importe`,
  DROP COLUMN `detalle_costo_promedio`,
  DROP COLUMN `detalle_utilidad`;
