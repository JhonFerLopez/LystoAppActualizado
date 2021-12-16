<?php

class Migration_Add_detalle_venta_unidad extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'unidad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'detalle_venta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),


                'precio' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'utilidad' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'costo' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'impuesto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (detalle_venta_id) REFERENCES detalle_venta(id_detalle)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalle_venta_unidad', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalle_venta_unidad');
    }
}