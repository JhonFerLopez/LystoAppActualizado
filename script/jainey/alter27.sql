
ALTER TABLE `ajusteinventario`
  CHANGE `descripcion` `tipo_ajuste` BIGINT(20) NULL;

ALTER TABLE `kardex`
  DROP COLUMN `cKardexOperacion`;
