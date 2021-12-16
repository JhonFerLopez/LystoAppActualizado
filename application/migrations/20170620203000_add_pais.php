<?php

class Migration_Add_pais extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_pais' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_pais' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),

            )
        );

        $this->dbforge->add_key('id_pais', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('pais', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('pais');
    }
}