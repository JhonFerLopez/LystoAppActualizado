<?php

class Migration_Add_paquete_has_prod extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'paquete_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'prod_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'unidad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => false,

                ),

            )
        );


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (paquete_id) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (prod_id) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('paquete_has_prod', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('paquete_has_prod');
    }
}