<?php

class Migration_Add_grupos_usuarios extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_grupos_usuarios' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_grupos_usuarios' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'status_grupos_usuarios' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('id_grupos_usuarios', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('grupos_usuarios', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('grupos_usuarios');
    }
}