<?php

class Migration_Add_ubicacion_fisica extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'ubicacion_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'ubicacion_nombre' => array(
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
       

        $this->dbforge->add_key('ubicacion_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ubicacion_fisica', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('ubicacion_fisica');
    }
}