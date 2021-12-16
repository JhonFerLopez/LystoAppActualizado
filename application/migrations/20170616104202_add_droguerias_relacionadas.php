<?php

class Migration_Add_droguerias_relacionadas extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'drogueria_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'drogueria_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'drogueria_domain' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),

                'deleted_at' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
            )
        );

        $this->dbforge->add_key('drogueria_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('droguerias_relacionadas',false,$attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('droguerias_relacionadas');
    }
}