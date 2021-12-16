<?php

class Migration_Updateproducto5 extends CI_Migration
{
    public function up()
    {

        $query = "ALTER TABLE `producto`   
  ADD COLUMN `suma_costo_caja` DECIMAL(25,2) NULL  COMMENT 'este campo es la suma de lo que va costando cada caja al momento de comprar un producto' AFTER `porcentaje_costo`,
  ADD COLUMN `cantidad_caja` INT(20) NULL  COMMENT 'este campo es la cantidad de lo que cuesta 1 caja para cuando se compra un producto, va sumando de 1 en 1, para cuandos e calcule el costo promedio de la caja, se toma el campo suma_costo_caja/cantidad_caja' AFTER `suma_costo_caja`;
";
        $this->db->query($query);

    }

    public function down()
    {

    }
}