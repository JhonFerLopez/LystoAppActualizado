<?php

class Migration_Add_venta_anular extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'nVenAnularCodigo' => array(
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
                'tipo_anulación' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'nUsuCodigo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'dat_fecha_registro' => array(
                    'type' => 'TIMESTAMP',

                ),

            )
        );
        $this->dbforge->add_key('nVenAnularCodigo', TRUE);


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_venta) REFERENCES venta(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (tipo_anulación) REFERENCES tipo_anulacion(tipo_anulacion_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (nUsuCodigo) REFERENCES usuario(nUsuCodigo)');




        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_anular', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_anular');
    }
}