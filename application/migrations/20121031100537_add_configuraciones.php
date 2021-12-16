<?php

class Migration_Add_configuraciones extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'config_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'config_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'config_value' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('config_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('configuraciones', false, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('configuraciones');
    }
}