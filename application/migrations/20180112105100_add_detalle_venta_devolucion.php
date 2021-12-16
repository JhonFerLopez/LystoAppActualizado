<?php

class Migration_Add_detalle_venta_devolucion extends CI_Migration
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
                'fecha_devolucion' => array(
                    'type' => 'DATETIME',
                ),

                'id_producto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'id_unidad' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'id_detalle_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'id_cuadre_caja' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

                'precio' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

                'precio_sin_iva' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'subtotal' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

                'total' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('id_detalle', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta_backup(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_detalle_venta) REFERENCES detalle_venta(id_detalle)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_cuadre_caja) REFERENCES status_caja(id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalle_venta_devolucion', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalle_venta_devolucion');
    }
}