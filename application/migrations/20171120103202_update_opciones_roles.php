<?php

class Migration_Update_opciones_roles extends CI_Migration
{

    public function up()
    {


        $query1 = "UPDATE opcion SET nOpcionClase='10', cOpcionDescripcion='Convenios Empresas' WHERE nOpcion= '47'

";
        $this->db->query($query1);

        $query1 = "UPDATE opcion SET cOpcionDescripcion='Empresas y Clientes' WHERE nOpcion= '10'

";
        $this->db->query($query1);

    }

    public function down()
    {

    }
}