ALTER TABLE `detalleingreso`
  CHARSET=utf8, COLLATE=utf8_spanish2_ci;

ALTER TABLE `detalle_ingreso_unidad`
  DROP FOREIGN KEY `detalle_ingreso_unidad_ibfk_2`;

  ALTER TABLE `detalle_ingreso_unidad`
  ADD CONSTRAINT `detalleIngreso` FOREIGN KEY (`detalle_ingreso_id`) REFERENCES `detalleingreso`(`id_detalle_ingreso`);