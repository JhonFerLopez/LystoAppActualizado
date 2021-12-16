<?php

class Migration_Add_credito extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'var_credito_estado' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,


                ),

                'dec_credito_montodeuda' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '18,2',
                ),
                'dec_credito_montodebito' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '18,2',
                ),


            )
        );

        $this->dbforge->add_key('id_venta', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('credito', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('credito');
    }
}