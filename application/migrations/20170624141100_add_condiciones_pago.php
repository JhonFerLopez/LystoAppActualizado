<?php

class Migration_Add_condiciones_pago extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_condiciones' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_condiciones' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => false,
                ),
                'dias' => array(
                    'type' => 'INT',
                    'constraint' => '11',
                    'null' => false,
                ),
                'status_condiciones' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),

            )
        );

        $this->dbforge->add_key('id_condiciones', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('condiciones_pago', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('condiciones_pago');
    }
}