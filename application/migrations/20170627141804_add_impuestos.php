<?php

class Migration_Add_impuestos extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_impuesto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'nombre_impuesto' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,

                ),

                'porcentaje_impuesto' => array(
                    'type' => 'FLOAT',
                    'null' => true,

                ),


                'estatus_impuesto' => array(
                    'type' => 'BOOL',
                    'default' => 1,
                    'null' => false,
                ),

            )
        );
        $this->dbforge->add_key('id_impuesto', TRUE);




        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('impuestos', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('impuestos');
    }
}