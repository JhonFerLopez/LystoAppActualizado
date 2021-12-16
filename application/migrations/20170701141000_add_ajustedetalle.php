<?php

class Migration_Add_ajustedetalle extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_ajustedetalle' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_ajusteinventario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cantidad_detalle' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'old_cantidad' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),
                'id_inventario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),

                'costo' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                ),

            )
        );


        $this->dbforge->add_key('id_ajustedetalle', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_ajusteinventario) REFERENCES ajusteinventario(id_ajusteinventario)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_inventario) REFERENCES inventario(id_inventario)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ajustedetalle', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('ajustedetalle');
    }
}