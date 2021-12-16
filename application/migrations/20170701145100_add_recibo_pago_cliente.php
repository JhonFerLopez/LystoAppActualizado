<?php

class Migration_Add_recibo_pago_cliente extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'recibo_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'auto_increment' => true

                ),
                'usuario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'banco' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),
                'metodo' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,

                ),

                'observaciones_adicionales' => array(
                    'type' => 'TEXT',
                    'null' => true,

                ),
                'numero_documento' => array(
                    'type' => 'VARCHAR',
                    'null' => true,
                    'constraint' => 100,

                ),
                'fecha_consignacion' => array(
                    'type' => 'DATE',
                    'null' => true,

                ),
                'fecha' => array(
                    'type' => 'DATE',
                    'null' => true,
                ),


            )
        );

        $this->dbforge->add_key('recibo_id', TRUE);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (usuario) REFERENCES usuario(nUsuCodigo)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (banco) REFERENCES banco(banco_id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (metodo) REFERENCES metodos_pago(id_metodo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('recibo_pago_cliente', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('recibo_pago_cliente');
    }
}