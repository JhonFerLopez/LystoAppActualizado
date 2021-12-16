<?php

class Migration_Add_componentes extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'componente_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'componente_nombre' => array(
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
       

        $this->dbforge->add_key('componente_id', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('componentes', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('componentes');
    }
}