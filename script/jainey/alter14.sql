CREATE TABLE `producto_has_componente`(
  `producto_id` BIGINT(20),
  `componente_it` BIGINT(20)
);
ALTER TABLE `producto`
  DROP COLUMN `producto_componente`,
  DROP INDEX `producto_componente`,
  DROP FOREIGN KEY `producto_ibfk_5`;

ALTER TABLE `producto_has_componente`
  CHANGE `componente_it` `componente_id` BIGINT(20) NULL;


