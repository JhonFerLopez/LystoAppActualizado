<?php

class Migration_Update_unidades2 extends CI_Migration
{
    public function up()
    {


        $query="UPDATE unidades SET fe_unidad=479 WHERE unidades.nombre_unidad='CAJA';";
        $this->db->query($query);

        $query="UPDATE unidades SET fe_unidad=647 WHERE unidades.nombre_unidad='BLISTER';";
        $this->db->query($query);

        $query="UPDATE unidades SET fe_unidad=70 WHERE unidades.nombre_unidad='UNIDAD';";
        $this->db->query($query);


    }

    public function down()
    {

    }
}