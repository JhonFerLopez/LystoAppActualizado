<?php

class Migration_update_opcion_1 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
update `opcion` set cOpcionDescripcion='Kardex' where cOpcionNombre='consultamovimientos' and nOpcionClase=3
");
    }

    public function down()
    {

    }
}