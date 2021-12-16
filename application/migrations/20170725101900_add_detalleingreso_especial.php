<?php

class Migration_Add_detalleingreso_especial extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_detalle_especial' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'detalle_ingreso_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                //este es o el prepack o el obsequio
                'producto_id_especial' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                //este es el producto al cual se esta asociando o descomponiendo
                'producto_id' => array(
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
                    'default'=>'0'
                ),

                'costo_uni' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                    'default'=>'0.0'
                ),

                'costo_total' => array(
                    'type' => 'FLOAT',
                    'null' => true,
                    'default'=>'0.0'
                ),

                'tipo' => array(
                    'type' => 'VARCHAR',
                    'null' => false,
                    'constraint'=>15
                )
            )
        );


        $this->dbforge->add_key('id_detalle_especial', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (detalle_ingreso_id) REFERENCES detalleingreso(id_detalle_ingreso)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id_especial) REFERENCES producto(producto_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('detalleingreso_especial', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('detalleingreso_especial');
    }
}