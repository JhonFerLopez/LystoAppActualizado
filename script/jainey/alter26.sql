ALTER TABLE `venta_anular`
  CHANGE `var_venanular_descripcion` `tipo_anulación` BIGINT(20) NOT NULL;

ALTER TABLE `tipo_devolucion`
  ENGINE=INNODB;

ALTER TABLE `tipo_anulacion`
  ENGINE=INNODB;

ALTER TABLE `venta_anular`
  ADD FOREIGN KEY (`tipo_anulación`) REFERENCES `tipo_anulacion`(`tipo_anulacion_id`);
