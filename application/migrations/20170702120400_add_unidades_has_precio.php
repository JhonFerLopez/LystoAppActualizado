<?php

class Migration_Add_unidades_has_precio extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_condiciones_pago' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'id_unidad' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'id_producto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'precio' => array(
                    'type' => 'DOUBLE',
                    'null' => true,

                ),
                'utilidad' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,

                ),

            )
        );

       /* $this->dbforge->add_key('id_condiciones_pago', TRUE);
        $this->dbforge->add_key('id_unidad', TRUE);
        $this->dbforge->add_key('id_producto', TRUE);*/


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_condiciones_pago) REFERENCES condiciones_pago(id_condiciones)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('unidades_has_precio', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('unidades_has_precio');
    }
}