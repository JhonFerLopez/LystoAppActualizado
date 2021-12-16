<?php

class Migration_Insert_configuraciones4 extends CI_Migration
{
    public function up()
    {


        $this->db->query("
insert  into `configuraciones`(`config_id`,`config_key`,`config_value`) values 
(null,'CREDENCIALES_DRIVE','{\"access_token\":\"ya29.GlzJBUvLrTLydD4XxGVo61ehU9ss2xvqkFdCa_9By8bsT_eAUa3KYWNQcA-vfWpnuhCFjvC4Yt1_3ddl6pOCPlN123sOK-mB1CnUuZEQFrCxxd57NqSEezEJGGm5sQ\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"created\":1527527517,\"refresh_token\":\"1\/J8buNId53ZyYARAyHYYLUwzKAQeHj6CVuaFG5A10FV0\"}');");



$this->db->query("
insert  into `configuraciones`(`config_id`,`config_key`,`config_value`) values 
(null,'CLIENTE_SECRET_DRIVE','{\"installed\":{\"client_id\":\"1021763848643-ta9u7s7b6rlaq4osqg6dhtjbuiik1rlp.apps.googleusercontent.com\",\"project_id\":\"plexiform-armor-204312\",\"auth_uri\":\"https://accounts.google.com/o/oauth2/auth\",\"token_uri\":\"https://accounts.google.com/o/oauth2/token\",\"auth_provider_x509_cert_url\":\"https://www.googleapis.com/oauth2/v1/certs\",\"client_secret\":\"uxdeVZXk966c4utqkg5FRLvG\",\"redirect_uris\":[\"urn:ietf:wg:oauth:2.0:oob\",\"http://localhost\"]}}');");


}

    public function down()
    {

    }
}