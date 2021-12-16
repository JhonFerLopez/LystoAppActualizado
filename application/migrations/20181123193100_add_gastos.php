<?php

class Migration_Add_gastos extends CI_Migration
{
    public function up()
    {


        $query='CREATE TABLE `tipos_gasto` (
  `id_tipos_gasto` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre_tipos_gasto` varchar(255) DEFAULT NULL,
  `status_tipos_gasto` tinyint(1) DEFAULT \'1\',
  PRIMARY KEY (`id_tipos_gasto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1
' ;
        $this->db->query($query);

        $query='
CREATE TABLE `gastos` (
  `id_gastos` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `descripcion` text,
  `total` float(20,0) DEFAULT NULL,
  `tipo_gasto` bigint(20) DEFAULT NULL,
  `local_id` bigint(20) DEFAULT NULL,
  `status_gastos` tinyint(1) DEFAULT \'1\',
  PRIMARY KEY (`id_gastos`),
  KEY `tipos_gasto_fk1_idx` (`tipo_gasto`),
  KEY `tipos_gasto_fk2_idx` (`local_id`),
  CONSTRAINT `tipos_gasto_fk1` FOREIGN KEY (`tipo_gasto`) REFERENCES `tipos_gasto` (`id_tipos_gasto`) ON DELETE NO ACTION ON UPDATE NO ACTION
  
) ENGINE=InnoDB DEFAULT CHARSET=latin1


' ;
        $this->db->query($query);
    }

    public function down()
    {

    }
}