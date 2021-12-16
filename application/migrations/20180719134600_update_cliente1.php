<?php

class Migration_update_cliente1 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `cliente`   
  ADD COLUMN `cantidad_domic` INT(25) NULL AFTER `afiliado`,
  ADD COLUMN `segundos_acum` INT(25) NULL AFTER `cantidad_domic`,
  ADD COLUMN `segundos_promedio` INT(25) NULL AFTER `segundos_acum`,
  ADD COLUMN `string_promedio` VARCHAR(200) NULL AFTER `segundos_promedio`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}