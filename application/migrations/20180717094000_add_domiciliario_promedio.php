<?php

class Migration_Add_domiciliario_promedio extends CI_Migration
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
                'domiciliario' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'cliente' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                ),
                'segundos_acumulados' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => true,
                ),
                'segundos_tarda' => array(
                    'type' => 'INT',
                    'constraint' => 25,
                ),
                'cantidad_dom' => array(
                    'type' => 'INT',
                    'constraint' => 25,
                ),
                'promedio_seg' => array(
                    'type' => 'INT',
                    'constraint' => 25,
                ),
                'promedio_string' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => true,
                ),

            )
        );
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (cliente) REFERENCES cliente(id_cliente)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (domiciliario) REFERENCES usuario(nUsuCodigo)');

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('domiciliario_promedio', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('domiciliario_promedio');
    }
}