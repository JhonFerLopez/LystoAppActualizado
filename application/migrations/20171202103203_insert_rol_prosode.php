<?php

class Migration_Insert_rol_prosode extends CI_Migration
{

    public function up()
    {

        $query1 = "INSERT INTO grupos_usuarios (nombre_grupos_usuarios,status_grupos_usuarios) VALUES ('PROSODE_ADMIN',1);";
        $this->db->query($query1);
        $rol_id = $this->db->insert_id();
        $query1 = "INSERT INTO `usuario` (`username`, `var_usuario_clave`, `nombre`, `identificacion`, `grupo`, `sueldo`, `genero`, `longitud`, `latitud`, `obser`, `smovil`, `admin`, `activo`, `deleted`, `fnac`, `fent`) VALUES('PROSODE','0b8b54ec116cff7c743311d42dd797bc','PROSODE_ADMIN','123123',$rol_id,'3234324','MASCULINO','-76.53147039999999','3.4289026',NULL,'0','0','1','0','2017-11-08','1969-12-31');
";
        $this->db->query($query1);
    }

    public function down()
    {

    }
}