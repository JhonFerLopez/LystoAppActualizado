ALTER TABLE `cliente`
  DROP COLUMN `barrio`;

CREATE TABLE `afiliado`(
  `afiliado_id` BIGINT,
  `afiliado_codigo` BIGINT,
  `afiliado_nombre` VARCHAR(255),
  `afiliado_monto_cartera` FLOAT
);


INSERT INTO opcion VALUES(102, null, 'params_facturacion','Parametros de facturación');
INSERT INTO opcion_grupo VALUES(1,102, 1);

INSERT INTO opcion VALUES(103, 102, 'afiliado','Empresas afiliadas');
INSERT INTO opcion_grupo VALUES(1,103, 1);

ALTER TABLE `afiliado`
  CHANGE `afiliado_id` `afiliado_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (`afiliado_id`);

ALTER TABLE `afiliado`
  ADD COLUMN `deleted_at` DATETIME NULL AFTER `afiliado_monto_cartera`;



CREATE TABLE `afiliado_descuentos`(
  `id` BIGINT,
  `tipo_prod_id` BIGINT,
  `unidad_id` BIGINT,
  `pocentaje` FLOAT
);


ALTER TABLE `afiliado_descuentos`
CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (`id`);


ALTER TABLE `afiliado_descuentos`
DROP COLUMN `id`,
DROP PRIMARY KEY;


ALTER TABLE `afiliado_descuentos`
ADD COLUMN `afiliado_id` BIGINT NULL AFTER `pocentaje`;

ALTER TABLE `afiliado_descuentos`
CHANGE `pocentaje` `porcentaje` FLOAT NULL;

/************tipo proveedor******/
CREATE TABLE `tipo_proveedor`(
  `tipo_proveedor_id` BIGINT NOT NULL AUTO_INCREMENT,
  `tipo_proveedor_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`tipo_proveedor_id`)
);


