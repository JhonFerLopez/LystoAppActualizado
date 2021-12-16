ALTER TABLE `venta`
  ADD COLUMN `cambio` DECIMAL(18,2) DEFAULT 0.00  NULL AFTER `pagado`;

ALTER TABLE
`documento_venta`
  ADD COLUMN `id_venta` BIGINT(20) NULL AFTER `documento_Numero`;

ALTER TABLE venta
  DROP COLUMN `numero_documento`;

ALTER TABLE `venta`
  CHANGE `subtotal` `subtotal` DECIMAL(18,2) DEFAULT 0.00  NULL,
  CHANGE `total_impuesto` `total_impuesto` DECIMAL(18,2) DEFAULT 0.00  NULL,
  CHANGE `total` `total` DECIMAL(18,2) DEFAULT 0.00  NULL,
  ADD COLUMN `descuento_valor` DECIMAL(18,2) DEFAULT 0.00  NULL AFTER `pagado`,
  ADD COLUMN `descuento_porcentaje` DECIMAL(18,2) DEFAULT 0.00  NULL AFTER `descuento_valor`;

