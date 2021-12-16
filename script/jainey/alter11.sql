insert  into `opcion`(`nOpcion`,`nOpcionClase`,`cOpcionDescripcion`,`cOpcionNombre`) values (104,NULL,'parametrizacion','Parametrizacion');
ALTER TABLE `venta`
  CHANGE `id_cliente` `id_cliente` BIGINT(20) NULL;
