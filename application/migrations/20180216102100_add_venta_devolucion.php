<?php

class Migration_Add_venta_devolucion extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id_devolucion' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'id_venta' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'id_usuario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'apertura_caja_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true
                ),

                'fecha_devolucion' => array(
                    'type' => 'DATETIME'
                ),



            )
        );


        $this->dbforge->add_key('id_devolucion', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_usuario) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (apertura_caja_id) REFERENCES status_caja(id)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_devolucion', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_devolucion');
    }
}