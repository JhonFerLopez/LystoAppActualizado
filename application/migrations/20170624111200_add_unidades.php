<?php

class Migration_Add_unidades extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_unidad' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_unidad' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'abreviatura' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '45',
                    'null' => true,
                ),
                'orden' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,0',
                    'null' => true,
                ),
                'estatus_unidad' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('id_unidad', TRUE);


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('unidades', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('unidades');
    }
}