<?php

class Migration_Update_registros_compra_obsequios extends CI_Migration
{
    public function up()
    {

        $query='UPDATE kardex SET ckardexTipo="INGRESO",ckardexReferencia = CONCAT(ckardexReferencia, " - INGRESO DE MERCANCIA OBSEQUIO")
WHERE ckardexTipo="INGRESO DE MERCANCIA OBSEQUIO"' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="INGRESO",ckardexReferencia = CONCAT(ckardexReferencia, " - INGRESO DE MERCANCIA OBSEQUIO") 
WHERE ckardexTipo="INGRESO POR OBSEQUIO"' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="SALIDA",ckardexReferencia = CONCAT(ckardexReferencia, " - SALIDA POR OBSEQUIO") 
WHERE ckardexTipo="SALIDA POR OBSEQUIO";' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="INGRESO",ckardexReferencia = CONCAT(ckardexReferencia, " - INGRESO DE MERCANCIA PREPACK") 
WHERE ckardexTipo="INGRESO DE MERCANCIA PREPACK";' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="INGRESO",ckardexReferencia = CONCAT(ckardexReferencia, " - INGRESO DE MERCANCIA PREPACK") 
WHERE ckardexTipo="INGRESO POR PREPACK";' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="SALIDA",ckardexReferencia = CONCAT(ckardexReferencia, " - SALIDA POR PREPACK") 
WHERE ckardexTipo="SALIDA POR PREPACK";' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="SALIDA",ckardexReferencia = CONCAT(ckardexReferencia, " - SALIDA POR OBSEQUIO") 
WHERE ckardexTipo="SALIDA POR OBSEQUIO A TERCEROS";' ;
        $this->db->query($query);

        $query='UPDATE kardex SET ckardexTipo="ENTRADA",ckardexReferencia = CONCAT(ckardexReferencia, " - INGRESO DE MERCANCIA OBSEQUIO") 
WHERE ckardexTipo="ENTRADA POR OBSEQUIO A TERCEROS";' ;
        $this->db->query($query);


    }

    public function down()
    {

    }
}