<?php

class Migration_Add_lineas extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_linea' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_linea' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                ),
                'estatus_linea' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),


            )
        );
       

        $this->dbforge->add_key('id_linea', TRUE);



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('lineas', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('lineas');
    }
}