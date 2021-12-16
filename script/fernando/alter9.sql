ALTER TABLE `producto`
  CHANGE `producto_codigo_interno` `producto_codigo_interno` VARCHAR(100) CHARSET latin1 COLLATE latin1_swedish_ci DEFAULT '0'   NOT NULL  AFTER `producto_id`,
  CHANGE `producto_nombre` `producto_nombre` VARCHAR(100) CHARSET latin1 COLLATE latin1_swedish_ci NULL  AFTER `producto_codigo_interno`,
  CHANGE `producto_codigo_barra` `producto_codigo_barra` VARCHAR(255) CHARSET latin1 COLLATE latin1_swedish_ci NULL  AFTER `producto_nombre`,
  CHANGE `producto_descripcion` `producto_descripcion` VARCHAR(500) CHARSET latin1 COLLATE latin1_swedish_ci NULL  AFTER `producto_codigo_barra`,
  CHANGE `contenido_interno` `contenido_interno` DECIMAL(25,2) NULL  AFTER `producto_descripcion`,
  CHANGE `producto_marca` `producto_marca` BIGINT(20) NULL  AFTER `contenido_interno`,
  CHANGE `producto_sustituto` `producto_sustituto` VARCHAR(100) CHARSET latin1 COLLATE latin1_swedish_ci NULL  AFTER `producto_tipo`,
  CHANGE `producto_comision` `producto_comision` DECIMAL(25,2) NULL  AFTER `costo_cargue`,
  CHANGE `pedir_fecha_venc` `pedir_fecha_venc` DATE NULL;
