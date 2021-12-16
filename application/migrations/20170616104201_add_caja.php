<?php

class Migration_Add_caja extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'caja_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'alias' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),

                'status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('caja_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('caja', false, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('caja');
    }
}