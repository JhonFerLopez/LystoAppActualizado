UPDATE columnas SET nombre_join="clasificacion_nombre" WHERE nombre_columna="producto_clasificacion";
UPDATE columnas SET nombre_join="tipo_prod_nombre" WHERE nombre_columna="producto_tipo";

INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_componente','componente_nombre','Componente o Droga','producto',0,0,31);
INSERT INTO columnas (nombre_columna,nombre_join,nombre_mostrar,tabla,mostrar,activo,orden)
VALUES ('producto_ubicacion_fisica','ubicacion_nombre','Ubicación Física','producto',0,0,32);