<?php

class Migration_Add_venta_estatus extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'estatus_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'venta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'vendedor_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'fecha' => array(
                    'type' => 'DATETIME',
                ),
                'estatus' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'unsigned' => true,
                ),

            )
        );
        $this->dbforge->add_key('estatus_id', TRUE);


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (venta_id) REFERENCES venta(venta_id)');

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (vendedor_id) REFERENCES usuario(nUsuCodigo)');


        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_estatus', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_estatus');
    }
}