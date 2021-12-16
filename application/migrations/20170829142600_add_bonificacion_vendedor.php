<?php

class Migration_Add_bonificacion_vendedor extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_bonif' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_vendedor' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'id_detalle_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),


                'porcentaje' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'comision' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),


            )
        );


        $this->dbforge->add_key('id_bonif', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_detalle_venta) REFERENCES detalle_venta(id_detalle)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_vendedor) REFERENCES usuario(nUsuCodigo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('bonificacion_vendedor', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('bonificacion_vendedor');
    }
}