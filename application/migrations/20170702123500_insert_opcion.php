<?php

class Migration_Insert_opcion extends CI_Migration
{
    public function up()
    {

        $this->db->query("


INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(1, NULL, 'parametrizacion', 'Parametrización'),
(2, NULL, 'ventas', 'Ventas'),
(3, NULL, 'inventario', 'Inventario'),
(4, NULL, 'compras', 'Compras'),
(5, NULL, 'cartera', 'Cartera'),
(6, NULL, 'cuentasporpagar', 'Cuentas por pagar'),
(7, NULL, 'reportes', 'Reportes'),
(8, NULL, 'utilidad', 'Utilidad'),
(9, 1, 'parametrosproductos', 'Parametros productos'),
(10, 1, 'parametrosclientes', 'Parametros clientes'),
(11, 1, 'parametrosproveedores', 'Parametros proveedores'),
(12, 1, 'parametrosfacturacion', 'Parametros facturación'),
(13, 1, 'parametrosinventario', 'Parametros inventario'),
(14, 1, 'parametrosinstalacion', 'Parametros instalación'),
(15, 2, 'generarventa', 'Generar venta'),
(16, 2, 'historialventas', 'Historial de ventas'),
(17, 2, 'anularventa', 'Anular venta'),
(18, 2, 'devolucionventa', 'Devolver venta'),
(19, 3, 'stockbodegas', 'Stock bodegas'),
(20, 3, 'stockdroguerias', 'Stock droguerias'),
(21, 3, 'registrarfisicos', 'Registrar fisicos'),
(22, 3, 'movimientosdiarios', 'Movimientos diarios'),
(23, 3, 'consultamovimientos', 'Consultar movimientos'),
(24, 4, 'registraringreo', 'Registrar compra'),
(25, 4, 'consultaringresos', 'Cosultar compras'),
(26, 5, 'movimientoscartera', 'Movimintos de cartera'),
(27, 5, 'generarreciboscliente', 'Generar recibos cliente'),
(28, 6, 'movimientoproveedor', 'Movimientos proveedor'),
(29, 6, 'generarcomprobanteproveedor', 'Generar comprobantes'),
(30, 8, 'seguridad', 'Seguridad'),
(31, 8, 'drogueriasrelacionadas', 'Droguerias relacionadas'),
(32, 8, 'condicionespago', 'Condiciones pago'),
(33, 8, 'localizacion', 'Localizacion'),
(34, 9, 'productos', 'Productos'),
(35, 9, 'clasificacion', 'Clasificacion'),
(36, 9, 'tipo_producto', 'Tipo'),
(37, 9, 'componentes', 'Componentes'),
(38, 9, 'gruposproductos', 'Grupos'),
(39, 9, 'ubicacion_fisica', 'Ubicacion fisica'),
(40, 9, 'impuestos', 'Impuestos'),
(41, 9, 'unidadesmedida', 'Unidades de medida'),
(42, 10, 'clientes', 'Clientes'),
(43, 10, 'gruposcliente', 'Tipos de cliente'),
(44, 11, 'proveedor', 'Proveedores'),
(45, 11, 'tipoproveedor', 'Tipos de proveedor'),
(46, 11, 'regimencontributivo', 'Regimen contributivo'),
(47, 12, 'afiliado', 'Empresas afiliadas'),
(48, 12, 'metodospago', 'Formas de pago'),
(49, 12, 'tiposanulacion', 'Tipos de anulación'),
(50, 12, 'tiposdevolucion', 'Tipos de devolucion'),
(51, 12, 'tiposventa', 'Tipos de venta'),
(52, 12, 'reosluciondian', 'Resolucion de la DIAN'),
(53, 13, 'tipomovimiento', 'Tipos de moviminto'),
(54, 13, 'bodegas', 'Bodegas'),
(55, 14, 'opcionesgenerales', 'Opciones generales'),
(56, 14, 'bancos', 'Bancos'),
(57, 21, 'registrarfisicotodos', 'Registra todos los productos'),
(58, 21, 'registrafisicoporproducto', 'Registra por producto'),
(59, 30, 'usuarios', 'Usuarios'),
(60, 30, 'roles', 'Roles'),
(61, 33, 'pais', 'Paises'),
(62, 33, 'departamentos', 'Departamentos'),
(63, 33, 'ciudad', 'Ciudades'),
(64, 33, 'barrios', 'Barrios'),
(65, 21, 'registrafisicoporgrupo', 'Registrar por grupo'),
(66, 14, 'cajas', 'Cajas');
");
    }

    public function down()
    {
        $this->db->query(" DELETE FROM opcion");
    }
}