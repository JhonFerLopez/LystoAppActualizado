<?php

class Migration_Add_android_gcm_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
            array(
                'usuario' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                ),
                'codigo' => array(
                    'type' => 'text',
                    'null' => true,
                ),

            )
        );



        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('android_gcm_users', true, $attributes);
    }

    public function down()
    {
        $this->dbforge->drop_table('android_gcm_users');
    }
}