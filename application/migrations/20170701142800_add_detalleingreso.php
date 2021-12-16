<?php

class Migration_Add_detalleingreso extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_detalle_ingreso' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_ingreso' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'id_producto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'status' => array(
                    'type' => 'BOOL',
                    'null' => true,
                ),

                'total_detalle' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'porcentaje_descuento' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'impuesto_porcentaje' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'total_impuesto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'porcentaje_bonificacion'=>array(
                    'type' => 'FLOAT',
                    'null' => true,
                )


            )
        );


        $this->dbforge->add_key('id_detalle_ingreso', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_ingreso) REFERENCES ingreso(id_ingreso)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalleingreso', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalleingreso');
    }
}