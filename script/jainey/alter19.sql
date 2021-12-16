DROP TABLE venta_backup;
DROP TABLE detalle_venta_backup;

CREATE TABLE `venta_backup` (
  `venta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cliente` bigint(20) DEFAULT NULL,
  `id_vendedor` bigint(20) DEFAULT NULL COMMENT 'Este campo hace referencia al vendedor que se selecciona al momnento de hacer la venta',
  `fecha` datetime DEFAULT NULL,
  `venta_status` varchar(45) DEFAULT NULL,
  `local_id` bigint(20) DEFAULT NULL,
  `subtotal` decimal(18,2) DEFAULT '0.00',
  `total_impuesto` decimal(18,2) DEFAULT '0.00',
  `total` decimal(18,2) DEFAULT '0.00',
  `pagado` decimal(18,2) DEFAULT '0.00',
  `descuento_valor` decimal(18,2) DEFAULT '0.00',
  `descuento_porcentaje` decimal(18,2) DEFAULT '0.00',
  `cambio` decimal(18,2) DEFAULT '0.00',
  `confirmacion_caja` bigint(20) DEFAULT NULL,
  `confirmacion_banco` bigint(20) DEFAULT NULL,
  `confirmacion_fecha` datetime DEFAULT NULL,
  `confirmacion_usuario` bigint(20) DEFAULT NULL,
  `venta_tipo` bigint(20) DEFAULT NULL COMMENT 'Este campo viene de la tabla tipo_venta',
  `usuario_id` bigint(20) DEFAULT NULL COMMENT 'Este campo hace referencia l usuario que abre la venta.',
  `cajero_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`venta_id`),
  KEY `ventafklocal_idx` (`local_id`),
  KEY `ventafkpersonal_idx` (`id_vendedor`),
  KEY `ventaclientefk_idx` (`id_cliente`),
  KEY `confirmacion_caja` (`confirmacion_caja`),
  KEY `confirmacion_banco` (`confirmacion_banco`),
  KEY `confirmacion_usuario` (`confirmacion_usuario`),
  KEY `usuario_id` (`usuario_id`),
  KEY `venta_tipo` (`venta_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=latin1

CREATE TABLE `detalle_venta_backup` (
  `id_detalle` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_venta` bigint(20) DEFAULT NULL,
  `id_producto` bigint(20) DEFAULT NULL,
  `bono` tinyint(1) DEFAULT '0',
  `detalle_importe` float DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `R_9` (`id_venta`),
  KEY `transaccion_ibfk_4_idx` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=460 DEFAULT CHARSET=latin1

CREATE TABLE `detalle_venta_unidad_backup` (
  `unidad_id` bigint(20) DEFAULT NULL,
  `cantidad` decimal(10,0) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `detalle_venta_id` bigint(20) DEFAULT NULL,
  `utilidad` float DEFAULT NULL,
  `costo` float DEFAULT NULL,
  `impuesto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1


ALTER TABLE afiliado
  ADD COLUMN `lista_precios` BIGINT(20) NULL AFTER `deleted_at`,
  ADD FOREIGN KEY (`lista_precios`) REFERENCES `condiciones_pago`(`id_condiciones`),
  ENGINE=INNODB;
