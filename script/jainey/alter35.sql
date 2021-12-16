ALTER TABLE `caja`
  DROP COLUMN `activo`,
  DROP COLUMN `cuenta_contable`,
  DROP COLUMN `responsable`,
  ADD COLUMN `alias` VARCHAR(255) NULL AFTER `status`,
  DROP INDEX `responsable`,
  DROP FOREIGN KEY `caja_ibfk_2`;

ALTER TABLE `status_caja`
  ADD COLUMN `caja_id` BIGINT(20) NULL AFTER `operacion`,
  ADD FOREIGN KEY (`caja_id`) REFERENCES `caja`(`caja_id`);

ALTER TABLE `venta`
  ADD COLUMN `caja_id` BIGINT(20) NULL AFTER `cajero_id`,
  ADD FOREIGN KEY (`caja_id`) REFERENCES `caja`(`caja_id`);

DROP TABLE venta_backup;

CREATE TABLE `venta_backup` (
  `venta_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `id_cliente` BIGINT(20) DEFAULT NULL,
  `id_vendedor` BIGINT(20) DEFAULT NULL COMMENT 'Este campo hace referencia al vendedor que se selecciona al momnento de hacer la venta',
  `fecha` DATETIME DEFAULT NULL,
  `venta_status` VARCHAR(45) DEFAULT NULL,
  `local_id` BIGINT(20) DEFAULT NULL,
  `subtotal` DECIMAL(18,2) DEFAULT '0.00',
  `total_impuesto` DECIMAL(18,2) DEFAULT '0.00',
  `total` DECIMAL(18,2) DEFAULT '0.00',
  `pagado` DECIMAL(18,2) DEFAULT '0.00',
  `descuento_valor` DECIMAL(18,2) DEFAULT '0.00',
  `descuento_porcentaje` DECIMAL(18,2) DEFAULT '0.00',
  `cambio` DECIMAL(18,2) DEFAULT '0.00',
  `venta_tipo` BIGINT(20) DEFAULT NULL COMMENT 'Este campo viene de la tabla tipo_venta',
  `usuario_id` BIGINT(20) DEFAULT NULL COMMENT 'Este campo hace referencia l usuario que abre la venta.',
  `cajero_id` BIGINT(20) DEFAULT NULL,
  `caja_id` BIGINT(20) DEFAULT NULL,
  PRIMARY KEY (`venta_id`),
  KEY `ventafklocal_idx` (`local_id`),
  KEY `ventafkpersonal_idx` (`id_vendedor`),
  KEY `ventaclientefk_idx` (`id_cliente`),
  KEY `caja_id` (`caja_id`),
  CONSTRAINT `ventabk_ibfk_1` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  CONSTRAINT `ventabkclientefk` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ventabkfklocal` FOREIGN KEY (`local_id`) REFERENCES `local` (`int_local_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ventabkfkpersonal` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`nUsuCodigo`)
) ENGINE=INNODB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;


insert into `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) values('66','14','cajas','Cajas');

ALTER TABLE `caja`
  DROP COLUMN `caja_saldo`;

ALTER TABLE `status_caja`
  ADD COLUMN `monto` FLOAT NULL AFTER `caja_id`;
