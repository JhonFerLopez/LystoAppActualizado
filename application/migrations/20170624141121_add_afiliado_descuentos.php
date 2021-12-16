<?php

class Migration_Add_afiliado_descuentos extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'tipo_prod_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'unidad_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true
                ),
                'afiliado_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true
                ),

                'porcentaje' => array(
                    'type' => 'FLOAT',
                    'null' => false,
                ),


            )
        );

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (tipo_prod_id) REFERENCES tipo_producto(tipo_prod_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (unidad_id) REFERENCES unidades(id_unidad)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (afiliado_id) REFERENCES afiliado(afiliado_id)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('afiliado_descuentos', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('afiliado_descuentos');
    }
}