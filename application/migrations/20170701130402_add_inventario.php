<?php

class Migration_Add_inventario extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_inventario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_producto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => false,
                ),

                'id_local' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'id_unidad' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

            )
        );


        $this->dbforge->add_key('id_inventario', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_local) REFERENCES local(int_local_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('inventario', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('inventario');
    }
}