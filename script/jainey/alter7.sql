ALTER TABLE `venta`
  ADD COLUMN `tipo_venta` BIGINT(20) NULL  COMMENT 'Este campo viene de la tabla tipo_venta' AFTER `confirmacion_usuario`,
  ADD FOREIGN KEY (`tipo_venta`) REFERENCES `tipo_venta`(`tipo_venta_id`);

ALTER TABLE `venta`
  CHANGE `id_vendedor` `id_vendedor` BIGINT(20) NULL  COMMENT 'Este campo hace referencia al vendedor que se selecciona al momnento de hacer la venta',
  ADD COLUMN `usuario_id` BIGINT(20) NULL  COMMENT 'Este campo hace referencia l usuario que abre la venta.' AFTER `tipo_venta`,
  ADD FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`nUsuCodigo`);
