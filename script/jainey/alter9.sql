ALTER TABLE `unidades`
  ADD COLUMN `orden` NUMERIC NULL AFTER `abreviatura`;
ALTER TABLE `venta`
  DROP COLUMN `tipo_doc_fiscal`,
  DROP INDEX `venta_tipodocumento_idx`,
  DROP FOREIGN KEY `venta_ibfk_1`;

DROP table tipo_doc_fiscal;
DROP TABLE documento_detalle;