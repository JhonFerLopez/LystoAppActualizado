<?php

class Migration_Update_compras_anuladas_kardex extends CI_Migration
{
    public function up()
    {

        $query='UPDATE kardex SET ckardexTipo="SALIDA"
WHERE ckardexReferencia="COMPRA ANULADA"' ;
        $this->db->query($query);


    }

    public function down()
    {

    }
}