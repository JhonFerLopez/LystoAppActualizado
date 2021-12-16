ALTER TABLE `ajustedetalle`
  DROP COLUMN `fraccion_detalle`,
  DROP COLUMN `old_fraccion`;

ALTER TABLE .`kardex`
  DROP COLUMN `cKardexTipoDocumentoFiscal`,
  DROP COLUMN `cKardexNumeroDocumentoFiscal`,
  DROP COLUMN `cKardexNumeroSerieFiscal`;

ALTER TABLE `detalle_venta_unidad`
  ADD COLUMN `utilidad` FLOAT(20) NULL AFTER `detalle_venta_id`;
ALTER TABLE `detalle_venta_unidad`
  ADD COLUMN `costo` FLOAT NULL AFTER `utilidad`;

ALTER TABLE `kardex`
  CHANGE `nKardexPrecioUnitario` `nKardexPrecioUnitario` DECIMAL(9,2) NULL;
