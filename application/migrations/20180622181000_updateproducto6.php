<?php

class Migration_Updateproducto6 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `producto`   
  ADD COLUMN `producto_gruponvldos` BIGINT(20) NULL AFTER `produto_grupo`,
  ADD COLUMN `producto_gruponvltres` BIGINT(20) NULL AFTER `producto_gruponvldos`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}