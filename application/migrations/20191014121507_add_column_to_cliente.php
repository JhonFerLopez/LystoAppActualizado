<?php

class Migration_Add_column_to_cliente extends CI_Migration
{
    public function up()
    {
		$query = "ALTER TABLE `cliente`   
  ADD COLUMN `create_at`  DATETIME NULL";
		$this->db->query($query);

    }

    public function down()
    {

    }
}
