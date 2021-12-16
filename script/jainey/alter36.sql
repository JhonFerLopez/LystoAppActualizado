ALTER TABLE ``venta`
  DROP COLUMN `cajero_id`,
  CHANGE `usuario_id` `cajero_id` BIGINT(20) NULL  COMMENT 'Este campo hace referencia l usuario que abre la venta.';

ALTER TABLE  `producto`
  DROP COLUMN `producto_codigo_barra`;

ALTER TABLE `producto`
  DROP COLUMN `producto_descripcion`;

ALTER TABLE `producto`
  DROP COLUMN `contenido_interno`;

ALTER TABLE `producto`
  DROP COLUMN `producto_largo`,
  DROP COLUMN `producto_ancho`,
  DROP COLUMN `producto_alto`,
  DROP COLUMN `producto_peso`;

ALTER TABLE `producto`
  DROP COLUMN `producto_nota`,
  DROP COLUMN `producto_cualidad`,
  DROP COLUMN `presentacion`;

ALTER TABLE `producto`
  DROP COLUMN `producto_stockminimo`;

  ALTER TABLE `producto`
  DROP COLUMN `producto_titulo_imagen`,
  DROP COLUMN `producto_descripcion_img`;

ALTER TABLE `producto`
  DROP COLUMN `producto_nombre_corto`,
  DROP COLUMN `producto_nivel_rotacion`;

ALTER TABLE `producto`
  DROP COLUMN `pedir_fecha_venc`;

ALTER TABLE `producto`
  DROP COLUMN `producto_componente`;

ALTER TABLE `documento_venta`
  DROP COLUMN `documento_Serie`;


DROP TABLE escala_producto;
DROP TABLE escalas;


ALTER TABLE `ingreso`
  DROP COLUMN `usuario_costos`,
  DROP INDEX `usuario_costos`,
  DROP FOREIGN KEY `ingreso_ibfk_2`;

  DROP TABLE documento_fiscal;
DROP TABLE documento_detalle;

ALTER TABLE `detalle_venta`
  DROP COLUMN `bono`;

  ALTER TABLE `detalle_venta_backup`
  DROP COLUMN `bono`;

DROP TABLE bonificaciones;
DROP TABLE bonificaciones_has_producto;

DROP TABLE cliente_v;
DROP TABLE cliente_direccion;

DROP TABLE consolidado_detalle;
DROP TABLE consolidado_carga;

DROP TABLE descuentos;

DROP TABLE SUBGRUPO;
DROP TABLE SUBFAMILIA;

ALTER TABLE `unidades_has_producto`
  DROP COLUMN `metros_cubicos`;

DROP TABLE familia;

DROP TABLE marcas;
DROP TABLE lineas;

DROP TABLE camiones;