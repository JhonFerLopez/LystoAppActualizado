<?php

class Migration_Add_column_last_update_to_ingreso extends CI_Migration
{
    public function up()
    {
		$query = "ALTER TABLE `ingreso`   
  ADD COLUMN `last_update`  DATETIME NULL";  //ultima fecha de modificacion
		$this->db->query($query);
    }

    public function down()
    {

    }
}
