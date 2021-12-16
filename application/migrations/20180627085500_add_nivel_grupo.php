<?php

class Migration_Add_nivel_grupo extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(

                'nivel_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_nivel' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '250',
                    'null' => false,
                ),
                'nivel' => array(
                    'type' => 'INT',
                    'constraint' => '10',
                    'null' => true,
                ),
                'estatus' => array(
                    'type' => 'BOOL',
                    'null' => true,
                    'default' => 1,
                ),

            )
        );

        $this->dbforge->add_key('nivel_id', TRUE);

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('niveles_grupos', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('niveles_grupos');
    }
}