/*se borra la columna codigo de barra de la tabla columnas ya que no se esta uilizando como antes, y se le coloca el orden 2 a el codigo interno
para que aparesca antes del nombre*/
DELETE FROM columnas WHERE nombre_columna="producto_codigo_barra";
UPDATE columnas SET orden=2 WHERE nombre_columna="producto_codigo_interno";
/*se coloca varchar porque anteriormente estaba en int*/
alter table `producto`
   change `producto_codigo_interno` `producto_codigo_interno` varchar(100) NOT NULL;
UPDATE columnas SET nombre_join="producto_codigo_interno" WHERE nombre_columna="producto_codigo_interno";