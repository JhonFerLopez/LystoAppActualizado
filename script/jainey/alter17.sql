ALTER TABLE  paquete_has_prod
  ADD COLUMN `unidad_id` BIGINT(20) NOT NULL AFTER `prod_id`,
  ADD COLUMN `cantidad` FLOAT(20) NULL AFTER `unidad_id`,
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`paquete_id`, `prod_id`, `unidad_id`);
ALTER TABLE paquete_has_prod
  ADD FOREIGN KEY (`unidad_id`) REFERENCES `unidades`(`id_unidad`);
DROP TABLE precios;