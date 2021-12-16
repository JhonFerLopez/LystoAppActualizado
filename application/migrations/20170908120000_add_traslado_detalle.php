<?php

class Migration_Add_traslado_detalle extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'trasladodetalle_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'traslado_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'id_producto' => array(
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
                    'null' => true,
                ),
                'local_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'tipo_operacion' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,

                )

            )
        );


        $this->dbforge->add_key('trasladodetalle_id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (traslado_id) REFERENCES traslado(id_traslado)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (local_id) REFERENCES local(int_local_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('traslado_detalle', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('traslado_detalle');
    }
}