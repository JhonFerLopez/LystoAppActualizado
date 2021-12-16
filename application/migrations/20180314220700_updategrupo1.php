<?php

class Migration_updategrupo1 extends CI_Migration
{
    public function up()
    {

        $query = 'UPDATE grupos SET  estatus_grupo=1 WHERE estatus_grupo=NULL;';
        $this->db->query($query);

    }

    public function down()
    {

    }
}