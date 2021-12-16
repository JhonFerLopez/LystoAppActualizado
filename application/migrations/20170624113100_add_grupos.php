<?php

class Migration_Add_grupos extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_grupo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_grupo' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'estatus_grupo' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('id_grupo', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('grupos', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('grupos');
    }
}