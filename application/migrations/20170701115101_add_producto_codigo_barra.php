<?php

class Migration_Add_producto_codigo_barra extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'producto_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'codigo_barra' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,

                ),

            )
        );



        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id) REFERENCES producto(producto_id)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('producto_codigo_barra', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('producto_codigo_barra');
    }
}