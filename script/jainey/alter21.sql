ALTER TABLE `usuario`
  DROP COLUMN `caja`,
  DROP INDEX `caja`,
  DROP FOREIGN KEY `usuario_ibfk_3`;

ALTER TABLE caja
  DROP COLUMN `local`,
  DROP INDEX `local`,
  DROP FOREIGN KEY `caja_ibfk_1`;


ALTER TABLE `historial_pagos_clientes`
ADD COLUMN `observaciones_adicionales` TEXT NULL AFTER `historial_caja_id`;
