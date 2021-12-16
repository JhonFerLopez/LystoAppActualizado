<?php

class Migration_Insert_opcion7 extends CI_Migration
{
    public function up()
    {

        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_puntos_venta', 'Puntos de venta') ;
");

        $las_id = $this->db->insert_id();


        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( $las_id , 'rep_informe_ventas_fecha', 'Informe de Ventas por fecha') ;
");


        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_informes_estadisticos', 'Informes estadísticos') ;
");

        $las_id = $this->db->insert_id();


        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( $las_id , 'rep_cont_marginal', 'Contribución Marginal') ;
");



        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_vendedor', 'Reportes por vendedor') ;
");

        $las_id = $this->db->insert_id();



        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( $las_id , 'rep_part_ventas_vendedor', 'Participacion ventas por vendedor') ;
");

        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( $las_id , 'rep_comparar_vendedores', 'Comparar vendedores') ;
");



        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( $las_id , 'rep_vendedores_tipo_prod', 'Por tipo producto') ;
");





        $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_valor_inventario', 'Valor del inventario') ;
");
       $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_cuadre_caja', 'Cierre de caja') ;
");

       $this->db->query("
INSERT INTO `opcion` (`nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
( 7 , 'rep_comp_diario_venta', 'Comprobante diario de venta') ;
");


    }

    public function down()
    {

    }
}