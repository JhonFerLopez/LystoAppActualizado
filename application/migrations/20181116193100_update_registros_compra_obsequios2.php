<?php

class Migration_Update_registros_compra_obsequios2 extends CI_Migration
{
    public function up()
    {


        $query='UPDATE kardex SET ckardexTipo="ENTRADA"
WHERE ckardexTipo="INGRESO";' ;
        $this->db->query($query);
    }

    public function down()
    {

    }
}