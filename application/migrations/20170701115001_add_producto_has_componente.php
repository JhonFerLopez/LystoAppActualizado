<?php

class Migration_Add_producto_has_componente extends CI_Migration
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
                'componente_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

            )
        );



        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id) REFERENCES producto(producto_id)');

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (componente_id) REFERENCES componentes(componente_id)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('producto_has_componente', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('producto_has_componente');
    }
}