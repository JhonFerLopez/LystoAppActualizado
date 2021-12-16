<?php

class Migration_Add_familia extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_familia' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_familia' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'estatus_familia' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('id_familia', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('familia', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('familia');
    }
}