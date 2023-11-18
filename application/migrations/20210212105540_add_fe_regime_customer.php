<?php

class Migration_Add_fe_regime_customer extends CI_Migration
{
    public function up()
    {
        $query = "ALTER TABLE `cliente`   
        ADD COLUMN `fe_regime` INT(11) NULL AFTER `fe_type_liability`;
    ;";

        $this->db->query($query);
    }

    public function down()
    {
        $query = "ALTER TABLE `cliente` DROP COLUMN `fe_regime`;";
        $this->db->query($query);
    }
}