CREATE TABLE `detalle_ingreso_unidad`(
  `unidad_id` BIGINT,
  `cantidad` FLOAT,
  `costo` FLOAT,
  `detalle_ingreso_id` BIGINT,
  `costo_total` FLOAT,
  FOREIGN KEY (`unidad_id`) REFERENCES `unidades`(`id_unidad`),
  FOREIGN KEY (`detalle_ingreso_id`) REFERENCES `detalleingreso`(`id_detalle_ingreso`)
);

ALTER TABLE `detalleingreso`
  DROP COLUMN `cantidad`,
  DROP COLUMN `precio`;

ALTER TABLE `detalleingreso`
  DROP COLUMN `unidad_medida`,
  DROP INDEX `fk_detalle_ingreso3_idx`,
  DROP FOREIGN KEY `fk_detalle_ingreso3`;
