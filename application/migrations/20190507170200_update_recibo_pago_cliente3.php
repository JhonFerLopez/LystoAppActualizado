<?php

class Migration_Update_recibo_pago_cliente3 extends CI_Migration
{

    public function up()
    {

        $query1 = "ALTER TABLE `recibo_pago_proveedor`   
  CHANGE `fecha` `fecha` DATETIME NULL;
";
        $this->db->query($query1);



    }

    public function down()
    {

    }
}