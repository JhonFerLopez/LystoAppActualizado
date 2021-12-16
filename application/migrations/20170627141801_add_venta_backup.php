<?php

class Migration_Add_venta_backup extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'venta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true
                ),
                'venta_tipo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'id_cliente' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'id_vendedor' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'cajero_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'caja_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'local_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'fecha' => array(
                    'type' => 'DATETIME',
                    'null' => true,

                ),
                'venta_status' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '45',
                    'null' => true,
                ),
                'subtotal' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'total_impuesto' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'total' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'pagado' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'descuento_valor' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'descuento_porcentaje' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),
                'cambio' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '18,2',
                    'null' => true,
                ),


            )
        );
        $this->dbforge->add_key('venta_id', TRUE);


        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id_vendedor) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (cajero_id) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (caja_id) REFERENCES caja(caja_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (local_id) REFERENCES local(int_local_id)');




        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('venta_backup', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('venta_backup');
    }
}