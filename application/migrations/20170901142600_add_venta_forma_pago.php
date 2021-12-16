<?php

class Migration_Add_venta_forma_pago extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_forma_pago' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'id_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),


                'monto' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),



            )
        );


        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_forma_pago) REFERENCES metodos_pago(id_metodo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta(venta_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_forma_pago', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_forma_pago');
    }
}