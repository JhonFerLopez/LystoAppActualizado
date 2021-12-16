<?php

class Migration_Add_producto extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'producto_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'producto_codigo_interno' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,

                ),
                'producto_nombre' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,

                ),

                'produto_grupo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,

                ),
                'producto_tipo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null'=>true

                ),
                'producto_clasificacion' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,

                ),
                'producto_sustituto' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => true,

                ),
                'producto_proveedor' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,

                ),
                'producto_impuesto' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,

                ),

                'producto_ubicacion_fisica' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,

                ),


                'producto_estatus' => array(
                    'type' => 'BOOL',
                    'deafault' => 1,
                    'null' => true

                ),
                'producto_activo' => array(
                    'type' => 'BOOL',
                    'deafault' => 1,
                    'null' => true

                ),

                'costo_unitario' => array(
                    'type' => 'FLOAT',
                    'null' => true

                ),

                'producto_mensaje' => array(
                    'type' => 'TEXT',
                    'null' => true

                ),
                'costo_promedio' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'producto_descuentos' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'costo_cargue' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'producto_comision' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'producto_bonificaciones' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'porcentaje_descuento' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'precio_minimo' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'precio_maximo' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),
                'precio_abierto' => array(
                    'type' => 'BOOL',
                    'null' => true,

                ),
                'control_inven' => array(
                    'type' => 'BOOL',
                    'null' => true,

                ),
                'control_inven_diario' => array(
                    'type' => 'BOOL',
                    'null' => true,

                ),
                'is_paquete' => array(
                    'type' => 'BOOL',
                    'null' => true,

                ),
                'is_prepack' => array(
                    'type' => 'BOOL',
                    'null' => true,
                    'default'=>0
                ),
                'is_obsequio' => array(
                    'type' => 'BOOL',
                    'null' => true,
                    'default'=>0

                ),
                'porcentaje_costo' => array(
                    'type' => 'DECIMAL',
                    'null' => true,
                    'constraint' => '25,2',

                ),

            )
        );
        $this->dbforge->add_key('producto_id', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (produto_grupo) REFERENCES grupos(id_grupo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_tipo) REFERENCES tipo_producto(tipo_prod_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_clasificacion) REFERENCES clasificacion(clasificacion_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_proveedor) REFERENCES proveedor(id_proveedor)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_impuesto) REFERENCES impuestos(id_impuesto)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_ubicacion_fisica) REFERENCES ubicacion_fisica(ubicacion_id)');



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('producto', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('producto');
    }
}