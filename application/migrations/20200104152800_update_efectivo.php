<?php

class Migration_Update_efectivo extends CI_Migration
{
    public function up()
    {

        $this->db->query("

UPDATE metodos_pago SET fe_method_id =10 WHERE nombre_metodo='EFECTIVO';


");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM metodos_pago");
    }
}