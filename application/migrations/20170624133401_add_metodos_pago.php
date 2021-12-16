<?php

class Migration_Add_metodos_pago extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_metodo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_metodo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),

                'incluye_cuadre_caja' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'suma_total_ingreso' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'centros_bancos' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('id_metodo', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('metodos_pago', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('metodos_pago');
    }
}