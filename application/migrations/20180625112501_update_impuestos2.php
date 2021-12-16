<?php

class Migration_Update_impuestos2 extends CI_Migration
{
    public function up()
    {


        $query="UPDATE impuestos SET tipo_calculo = 'PORCENTAJE';
";
        $this->db->query($query);



    }

    public function down()
    {

    }
}