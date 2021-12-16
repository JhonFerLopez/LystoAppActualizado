<?php

class Migration_Add_detalle_ingreso_unidad extends CI_Migration
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
                'detalle_ingreso_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),



                'costo' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'impuesto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'costo_total' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (detalle_ingreso_id) REFERENCES detalleingreso(id_detalle_ingreso)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalle_ingreso_unidad', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalle_ingreso_unidad');
    }
}