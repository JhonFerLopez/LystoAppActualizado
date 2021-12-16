<?php

class Migration_Add_producto_has_grupo extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(

                'grupo_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'producto_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

            )
        );

        //$this->dbforge->add_key('id_unidad', TRUE);
        //$this->dbforge->add_key('id_producto', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (grupo_id) REFERENCES grupos(id_grupo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id) REFERENCES producto(producto_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('producto_has_grupo', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('unidades_has_producto');
    }
}