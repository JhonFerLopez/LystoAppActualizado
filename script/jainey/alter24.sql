CREATE TABLE documentos_inventarios(
  `documento_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `documento_nombre` VARCHAR(250),
  `documento_tipo` VARCHAR(20),
  PRIMARY KEY (`documento_id`)
) ENGINE=INNODB;

ALTER TABLE `documentos_inventarios`
  ADD COLUMN `deleted_at` DATETIME NULL AFTER `documento_tipo`;

ALTER TABLE`pagos_ingreso`
DROP COLUMN `pagoingreso_fecha`;
