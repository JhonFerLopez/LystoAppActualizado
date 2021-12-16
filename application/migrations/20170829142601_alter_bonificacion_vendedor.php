<?php

class Migration_Alter_bonificacion_vendedor extends CI_Migration
{
    public function up()
    {

        $query='ALTER TABLE `bonificacion_vendedor`   
  CHANGE `id_bonif` `id_comision` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;



';
        $this->db->query($query);

        $query='RENAME TABLE `bonificacion_vendedor` TO `comision_vendedor`;';
        $this->db->query($query);
    }

    public function down()
    {

    }
}