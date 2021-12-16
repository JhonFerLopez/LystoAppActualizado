CREATE TABLE `catalogo`(
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `producto_codigo_interno` VARCHAR(50),
  `producto_codigo_barra` VARCHAR(255),
  `producto_nombre` VARCHAR(100),
  `presentacion` VARCHAR(50),
  `costo_corriente` DECIMAL(18,2),
  `costo_real` DECIMAL(18,2),
  `iva` DECIMAL(10,2),
  `nombre_laboratorio` VARCHAR(100),
  `codigo_laboratorio` VARCHAR(25),
  `bonificacion` DECIMAL(18,2),
  PRIMARY KEY (`id`)
);

INSERT INTO configuraciones (config_key,config_value) VALUES('CORRELATIVO_PRODUCTO','NO');