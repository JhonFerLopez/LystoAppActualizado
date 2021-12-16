CREATE TABLE `status_caja`(
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME,
  `cajero` BIGINT,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cajero`) REFERENCES `usuario`(`nUsuCodigo`)
) ENGINE=INNODB;

ALTER TABLE `status_caja`
  ADD COLUMN `operacion` ENUM('APERTURA','CIERRE') NULL AFTER `cajero`;
