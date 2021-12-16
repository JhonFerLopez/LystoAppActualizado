alter table `producto`
   add column `producto_codigo_interno` int(50) NOT NULL after `producto_tipo`,
   add column `producto_sustituto` varchar(100) NULL after `producto_codigo_interno`,
   add column `producto_nombre_corto` varchar(100) NULL after `producto_sustituto`,
   add column `producto_nivel_rotacion` varchar(20) NULL after `producto_nombre_corto`,
   add column `producto_mensaje` text NULL after `producto_nivel_rotacion`;

   INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_clasificacion','producto_clasificacion','Clasificacion','producto',0,0,24);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_tipo','producto_tipo','Tipo Producto','producto',0,0,25);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_codigo_interno','producto_clasificacion','Codigo del Producto','producto',0,0,26);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_nombre_corto','producto_nombre_corto','Nombre corto','producto',0,0,27);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_sustituto','producto_sustituto','Sustituto','producto',0,0,28);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_nivel_rotacion','producto_nivel_rotacion','Nivel de Rotacion','producto',0,0,29);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_mensaje','producto_mensaje','Mensaje','producto',0,0,30);


/*esta es la tabla que contendra los codigos de barra asignados al producto*/
CREATE TABLE `producto_codigo_barra`(
   `producto_id` BIGINT NOT NULL ,
   `codigo_barra` VARCHAR(100) NOT NULL
 );

 ALTER TABLE `producto_codigo_barra` ADD CONSTRAINT `FK_producto_codigo_barra` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`);