ALTER TABLE `producto`
  DROP COLUMN `producto_subgrupo`,
  DROP INDEX `producto_subgrupo`,
  DROP FOREIGN KEY `producto_ibfk_1`;
ALTER TABLE `producto`
  DROP COLUMN `producto_marca`,
  DROP INDEX `producto_fk_1_idx`,
  DROP FOREIGN KEY `producto_fk_1`;
ALTER TABLE `producto`
  DROP COLUMN `producto_linea`,
  DROP COLUMN `producto_familia`,
  DROP COLUMN `producto_subfamilia`,
  DROP INDEX `R_19`,
  DROP INDEX `producto_fk_3_idx`,
  DROP INDEX `producto_subfamilia`,
  DROP FOREIGN KEY `producto_fk_2`,
  DROP FOREIGN KEY `producto_fk_3`,
  DROP FOREIGN KEY `producto_ibfk_2`;
