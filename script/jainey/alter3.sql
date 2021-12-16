ALTER TABLE cliente
  ADD COLUMN `digito_verificacion` INT NULL AFTER `id_zona`;

ALTER TABLE `cliente`
  DROP COLUMN `codigo_postal`,
  DROP COLUMN `descuento`,
  DROP COLUMN `direccion2`,
  DROP COLUMN `exento_impuesto`,
  DROP COLUMN `limite_credito`,
  DROP COLUMN `pagina_web`,
  DROP COLUMN `nota`,
  CHANGE `representante` `apellios` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  CHANGE `razon_social` `nombres` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  CHANGE `telefono1` `telefono` VARCHAR(45) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  CHANGE `telefono2` `celular` VARCHAR(45) CHARSET latin1 COLLATE latin1_swedish_ci NULL,
  ADD COLUMN `barrio` VARCHAR(255) NULL AFTER `digito_verificacion`,
  ADD COLUMN `sexo` CHAR(1) NULL AFTER `barrio`,
  ADD COLUMN `fecha_nacimiento` DATE NULL AFTER `sexo`,
  ADD COLUMN `facturacion_maximo` FLOAT NULL AFTER `fecha_nacimiento`,
  ADD COLUMN `valida_fact_maximo` BOOLEAN DEFAULT 0  NULL AFTER `facturacion_maximo`,
  ADD COLUMN `valida_venta_credito` BOOLEAN DEFAULT 0  NULL AFTER `valida_fact_maximo`,
  ADD COLUMN `dias_credito` INT NULL AFTER `valida_venta_credito`;

ALTER TABLE `cliente`
  ADD COLUMN `codigo_interno` VARCHAR(255) NULL AFTER `dias_credito`;

  ALTER TABLE `cliente`
  CHANGE `apellios` `apellidos` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE `cliente`
  ADD COLUMN `afiliado` BIGINT NULL AFTER `codigo_interno`;


ALTER TABLE `cliente`
  CHANGE `id_zona` `id_zona` INT(11) NULL;
