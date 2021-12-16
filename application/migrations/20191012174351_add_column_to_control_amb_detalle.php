<?php

class Migration_Add_column_to_control_amb_detalle extends CI_Migration
{
    public function up()
    {
		$query = "ALTER TABLE `control_ambiental_detalle`   
  ADD COLUMN `cadena_frio_am` DECIMAL(25,2) NULL,
  ADD COLUMN `cadena_frio_pm`  DECIMAL(18,2) NULL;
";
		$this->db->query($query);
    }

    public function down()
    {

    }
}
