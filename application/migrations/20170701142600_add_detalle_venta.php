<?php

class Migration_Add_detalle_venta extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_detalle' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'id_producto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),


                'detalle_importe' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('id_detalle', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalle_venta', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalle_venta');
    }
}