<?php

class Migration_Add_domicilios extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'domicilio_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'usuario_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cliente_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'domicilio_estatus' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ),
                'fecha_created' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'fecha_salida' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'fecha_entregado' => array(
                    'type' => 'DATETIME',
                    'null' => true,
                ),
                'usuario_asigna' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                ),
                'lat_domiciliario' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ),
                'lon_domiciliario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 100,
                ),
                'direccion_domiciliario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 100,
                ),

            )
        );
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (domicilio_id) REFERENCES venta(venta_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario_id) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (cliente_id) REFERENCES cliente(id_cliente)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('domicilios', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('domicilios');
    }
}