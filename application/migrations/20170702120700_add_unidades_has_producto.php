<?php

class Migration_Add_unidades_has_producto extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(

                'id_unidad' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'producto_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,


                ),
                'unidades' => array(
                    'type' => 'FLOAT',
                    'null' => true,

                ),
                'stock_minimo' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,

                ),
                'stock_maximo' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,

                ),

            )
        );


        //$this->dbforge->add_key('id_unidad', TRUE);
        //$this->dbforge->add_key('id_producto', TRUE);


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_unidad) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (producto_id) REFERENCES producto(producto_id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('unidades_has_producto', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('unidades_has_producto');
    }
}