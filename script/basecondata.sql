-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-08-2017 a las 16:54:57
-- Versión del servidor: 5.7.11
-- Versión de PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sid`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `access`
--

CREATE TABLE `access` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(40) NOT NULL,
  `all_access` tinyint(1) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afiliado`
--

CREATE TABLE `afiliado` (
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_codigo` bigint(20) UNSIGNED NOT NULL,
  `afiliado_nombre` varchar(255) NOT NULL,
  `afiliado_monto_cartera` float NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `lista_precios` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afiliado_descuentos`
--

CREATE TABLE `afiliado_descuentos` (
  `tipo_prod_id` bigint(20) UNSIGNED NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `porcentaje` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustedetalle`
--

CREATE TABLE `ajustedetalle` (
  `id_ajustedetalle` bigint(20) UNSIGNED NOT NULL,
  `id_ajusteinventario` bigint(20) UNSIGNED NOT NULL,
  `cantidad_detalle` float DEFAULT NULL,
  `old_cantidad` float DEFAULT NULL,
  `id_inventario` bigint(20) UNSIGNED NOT NULL,
  `costo` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ajustedetalle`
--

INSERT INTO `ajustedetalle` (`id_ajustedetalle`, `id_ajusteinventario`, `cantidad_detalle`, `old_cantidad`, `id_inventario`, `costo`) VALUES
(1, 1, 12, 0, 10, NULL),
(2, 1, 23, 0, 11, NULL),
(3, 1, 123, 0, 12, NULL),
(4, 1, 12, 12, 10, NULL),
(5, 1, 22, 12, 10, NULL),
(6, 1, 123, 12, 10, NULL),
(7, 1, 12, 0, 13, NULL),
(8, 1, 123, 0, 14, NULL),
(9, 1, 112, 123, 10, NULL),
(10, 1, 12, 112, 10, NULL),
(11, 1, 23, 112, 10, NULL),
(12, 1, 123, 0, 15, NULL),
(13, 1, 12, 0, 16, NULL),
(14, 1, 22, 23, 10, NULL),
(15, 1, 123, 22, 10, NULL),
(16, 1, 12, 22, 10, NULL),
(17, 1, 123, 0, 17, NULL),
(18, 1, 112, 0, 18, NULL),
(19, 1, 12, 12, 10, NULL),
(20, 1, 23, 12, 10, NULL),
(21, 1, 123, 12, 10, NULL),
(22, 1, 12, 0, 19, NULL),
(23, 1, 22, 0, 20, NULL),
(24, 1, 123, 123, 10, NULL),
(25, 1, 12, 123, 10, NULL),
(26, 1, 123, 123, 10, NULL),
(27, 1, 112, 0, 21, NULL),
(28, 2, 23, -16, 7, NULL),
(29, 2, 23, 23, 7, NULL),
(30, 2, 23, 23, 7, NULL),
(31, 3, 23, 0, 22, NULL),
(32, 3, 23, 123, 10, NULL),
(33, 3, 23, 23, 10, NULL),
(34, 4, 1, 23, 7, 12),
(35, 4, 12, 37, 7, 12),
(36, 4, 12, 43, 7, 12),
(37, 5, 212, 29, 7, NULL),
(38, 5, 1212, 212, 7, NULL),
(39, 5, 121, 212, 7, NULL),
(40, 5, 21, 212, 7, NULL),
(41, 5, 1212, 21, 7, NULL),
(42, 5, 121, 21, 7, NULL),
(43, 5, 1212, 21, 7, NULL),
(44, 5, 12, 1212, 7, NULL),
(45, 5, 121, 1212, 7, NULL),
(46, 5, 212, 1212, 7, NULL),
(47, 5, 1212, 212, 7, NULL),
(48, 5, 121, 212, 7, NULL),
(49, 5, 21, 212, 7, NULL),
(50, 5, 1212, 21, 7, NULL),
(51, 5, 121, 21, 7, NULL),
(52, 5, 1212, 21, 7, NULL),
(53, 5, 12, 1212, 7, NULL),
(54, 5, 121, 1212, 7, NULL),
(55, 5, 212, 1212, 7, NULL),
(56, 5, 1212, 212, 7, NULL),
(57, 5, 121, 212, 7, NULL),
(58, 5, 21, 212, 7, NULL),
(59, 5, 1212, 21, 7, NULL),
(60, 5, 121, 21, 7, NULL),
(61, 5, 1212, 21, 7, NULL),
(62, 5, 12, 1212, 7, NULL),
(63, 5, 121, 1212, 7, NULL),
(64, 6, 23, 1174, 7, 23),
(65, 6, 23, 1197, 7, 23),
(66, 6, 123, 1208, 7, 123),
(67, 7, 100, 0, 30, NULL),
(68, 7, 100, 0, 31, NULL),
(69, 8, 100, 0, 32, NULL),
(70, 9, 100, 0, 37, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajusteinventario`
--

CREATE TABLE `ajusteinventario` (
  `id_ajusteinventario` bigint(20) UNSIGNED NOT NULL,
  `tipo_ajuste` bigint(20) UNSIGNED DEFAULT NULL,
  `local_id` bigint(20) UNSIGNED NOT NULL,
  `usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ajusteinventario`
--

INSERT INTO `ajusteinventario` (`id_ajusteinventario`, `tipo_ajuste`, `local_id`, `usuario`, `fecha`) VALUES
(1, NULL, 2, 1, '2017-06-29 23:11:03'),
(2, NULL, 1, 1, '2017-07-06 23:12:54'),
(3, NULL, 2, 1, '2017-07-07 23:13:59'),
(4, 1, 1, 1, '2007-01-23 23:17:32'),
(5, NULL, 1, 1, '2017-07-20 16:03:47'),
(6, 1, 1, 1, '2017-07-20 20:04:54'),
(7, NULL, 1, 1, '2017-08-17 22:36:23'),
(8, NULL, 1, 1, '2017-08-17 22:36:39'),
(9, NULL, 1, 1, '2017-08-18 16:44:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `android_gcm_users`
--

CREATE TABLE `android_gcm_users` (
  `usuario` varchar(255) NOT NULL,
  `codigo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `banco_id` bigint(20) UNSIGNED NOT NULL,
  `banco_nombre` varchar(255) DEFAULT NULL,
  `banco_numero_cuenta` varchar(255) DEFAULT NULL,
  `banco_saldo` float DEFAULT NULL,
  `banco_cuenta_contable` varchar(255) DEFAULT NULL,
  `banco_titular` varchar(255) DEFAULT NULL,
  `banco_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`caja_id`, `alias`, `status`) VALUES
(1, 'CAJA1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `producto_codigo_interno` varchar(50) DEFAULT NULL,
  `producto_codigo_barra` varchar(255) DEFAULT NULL,
  `producto_nombre` varchar(100) DEFAULT NULL,
  `presentacion` varchar(50) DEFAULT NULL,
  `costo_corriente` decimal(18,2) DEFAULT NULL,
  `costo_real` decimal(18,2) DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `nombre_laboratorio` varchar(100) DEFAULT NULL,
  `codigo_laboratorio` varchar(25) DEFAULT NULL,
  `bonificacion` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `ciudad_id` bigint(20) UNSIGNED NOT NULL,
  `ciudad_nombre` varchar(255) DEFAULT NULL,
  `estado_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`ciudad_id`, `ciudad_nombre`, `estado_id`) VALUES
(11, 'Cali', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `clasificacion_id` bigint(20) UNSIGNED NOT NULL,
  `clasificacion_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clasificacion`
--

INSERT INTO `clasificacion` (`clasificacion_id`, `clasificacion_nombre`, `deleted_at`) VALUES
(1, 'ANALGESICO', NULL),
(2, 'VACUNAS', NULL),
(3, 'ANTISEPTICO', NULL),
(4, 'ANTIBIOTICO', NULL),
(5, 'ANTINFLAMATORIO', NULL),
(6, 'ANTIHISTAMINICO', NULL),
(7, 'ANESTESICO', NULL),
(8, 'ANTIDEPRESIVO', NULL),
(9, 'DIURETICO', NULL),
(10, 'LAXANTE', NULL),
(11, 'BRONCODILATADOR', NULL),
(12, 'ANTIPIRETICO', NULL),
(13, 'ANTIFUNGICO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` bigint(20) UNSIGNED NOT NULL,
  `ciudad_id` bigint(20) UNSIGNED DEFAULT NULL,
  `direccion` text,
  `email` varchar(255) DEFAULT NULL,
  `grupo_id` bigint(20) UNSIGNED NOT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `identificacion` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `celular` varchar(45) DEFAULT NULL,
  `longitud` varchar(255) DEFAULT NULL,
  `latitud` varchar(255) DEFAULT NULL,
  `cliente_status` tinyint(1) DEFAULT NULL,
  `id_zona` bigint(20) UNSIGNED DEFAULT NULL,
  `digito_verificacion` int(11) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `fnac` date DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `facturacion_maximo` float DEFAULT NULL,
  `valida_fact_maximo` tinyint(1) DEFAULT NULL,
  `valida_venta_credito` tinyint(1) DEFAULT NULL,
  `dias_credito` int(11) DEFAULT NULL,
  `codigo_interno` varchar(255) DEFAULT NULL,
  `afiliado` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `ciudad_id`, `direccion`, `email`, `grupo_id`, `apellidos`, `nombres`, `identificacion`, `telefono`, `celular`, `longitud`, `latitud`, `cliente_status`, `id_zona`, `digito_verificacion`, `sexo`, `fnac`, `fecha_nacimiento`, `facturacion_maximo`, `valida_fact_maximo`, `valida_venta_credito`, `dias_credito`, `codigo_interno`, `afiliado`) VALUES
(2, 11, 'Cl. 9c #31a-2 a 31a-60, Cali, Valle del Cauca, Colombia', 'milevisj@gmail.com', 1, 'Perez', 'Jhainey', '123123', '3055927754', '3055927754', '-76.53479060000001', '3.427502', 1, NULL, NULL, 'M', NULL, '2017-07-25', 0, 0, 0, 0, '3131', NULL),
(3, 11, 'Cra. 29a 1 #10a-2 a 10a-218, Cali, Valle del Cauca, Colombia', 'alejandranhr@gmail.com', 1, 'HErnandez', 'Alejandra ', '3213123', '213123123', '213123', '-76.53147039999999', '3.4279805', 1, 1, NULL, 'M', NULL, '2017-07-05', 123213, 1, 0, 0, '4234234', NULL),
(4, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'fernandoga22@gmai.com', 2, 'Perez', 'Fernando', '12312334234', '3055927754', '3055927754', '-76.53474340000002', '3.4274391', 1, 1, NULL, 'M', NULL, '2017-07-19', 0, 0, 0, 0, '423423434', NULL),
(5, 11, 'Cra. 29a 1 #10a-2 a 10a-218, Cali, Valle del Cauca, Colombia', 'rey@gmail.comfe', 2, 'Perez', 'Ferney', '26698779789879', '34534543', '234234234', '-76.53147039999999', '3.4279805', 1, 1, NULL, 'M', NULL, '2017-07-19', 0, 0, 0, 0, '124090', NULL),
(6, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'orma@gmail.com', 2, 'Romero', 'Ana', '53345435345', '5345345435', '543534', '-76.53472720000002', '3.4274389999999997', 1, NULL, NULL, NULL, NULL, '2017-07-11', 0, 0, 0, 0, '5647890890', NULL),
(7, 11, 'Cl. 9c #31a-2 a 31a-60, Cali, Valle del Cauca, Colombia', 'adasdasdasd@asdas.com', 1, 'Ramire', 'Sonia', '5345345345', '24234234', '234234234', '-76.53481049999999', '3.4274009', 1, 1, NULL, NULL, NULL, '2017-07-19', 0, 0, 0, 0, '534534534', NULL),
(8, 11, 'Dg. 23 #10-20 a 10-240, Cali, Valle del Cauca, Colombia', 'daniel@gail.com', 2, 'Hernandez', 'Daniel1501778053', '35435345345', '123123', '34113123', '-76.53147039999999', '3.4289026', 0, 1, NULL, 'M', NULL, '2017-07-11', 0, 0, 0, 0, '545345345', NULL),
(9, 11, '', 'william@gmail.com', 1, 'Hernandez', 'Wiliam1501778012', '234234234', '234234', '434234234', '0', '0', 0, 1, NULL, NULL, NULL, '2017-07-18', 0, 0, 0, 0, '234234234', NULL),
(10, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'daniel@gmail.com', 2, 'Pereira', 'Daniel1501778100', '43534535', '3423434', '334234', '-76.53475220000001', '3.4274381999999997', 0, 1, NULL, 'M', NULL, '2017-07-20', 0, 0, 0, 0, '424234234', NULL),
(11, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'oscar@gmail.com', 2, 'Moralez', 'Oscar1501777938', '2342342343434', '4234234', '5345345', '-76.53475220000001', '3.4274381999999997', 0, 1, NULL, 'M', NULL, '2017-07-13', 0, 0, 0, 0, '4234234234234', NULL),
(12, 11, 'Dg. 23 #10-20 a 10-240, Cali, Valle del Cauca, Colombia', 'keyla@gmail.com', 2, 'BArrera', 'Keila', '234243', '3123123', '12321312', '-76.53147039999999', '3.4289026', 1, 1, NULL, 'M', NULL, '2017-07-12', 0, 0, 0, 0, '4244', NULL),
(13, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'robert@gmail.com', 2, 'VEliz', 'Robert', '978989', '', '234234', '-76.53473789999998', '3.4274373', 1, 1, NULL, 'M', NULL, '1970-01-01', 0, 0, 0, 0, '242342342424', NULL),
(14, 11, 'Cl. 9c #31A-15, Cali, Valle del Cauca, Colombia', 'camre@gmail.com', 2, 'Carrera', 'Carmen1501777854', '2342366897870', '', '', '-76.53473559999998', '3.4274388', 0, 1, NULL, 'F', NULL, '2017-07-19', 0, 0, 0, 0, '4234234868678', NULL),
(15, 11, 'Cl. 23 #20-2 a 20-44, Cali, Valle del Cauca, Colombia', 'dasdasdsad@sdsa.com', 1, 'ssdfsdfsdf', 'dsfsdfsdf', '4234234', '', '', '-76.52008169999999', '3.4370149', 1, 1, NULL, NULL, NULL, '1970-01-01', 0, 0, 0, 0, '43423423', NULL),
(16, 11, 'Cl. 23 #20-2 a 20-44, Cali, Valle del Cauca, Colombia', 'sdfsdf@sdas.com', 2, 'fsdfsdf', 'sdfsdfsd', '24234234', '', '', '-76.5200734', '3.4370009', 1, 1, NULL, NULL, NULL, '1970-01-01', 0, 0, 0, 0, '3434234', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `columnas`
--

CREATE TABLE `columnas` (
  `id_columna` bigint(20) UNSIGNED NOT NULL,
  `nombre_columna` varchar(255) NOT NULL,
  `nombre_join` varchar(255) NOT NULL,
  `nombre_mostrar` varchar(255) NOT NULL,
  `tabla` varchar(255) NOT NULL,
  `mostrar` tinyint(1) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `orden` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `columnas`
--

INSERT INTO `columnas` (`id_columna`, `nombre_columna`, `nombre_join`, `nombre_mostrar`, `tabla`, `mostrar`, `activo`, `orden`) VALUES
(35, 'producto_id', 'producto_id', 'ID', 'producto', 1, 0, 1),
(37, 'producto_nombre', 'producto_nombre', 'Nombre', 'producto', 1, 0, 3),
(40, 'produto_grupo', 'nombre_grupo', 'Grupo', 'producto', 0, 1, 6),
(53, 'producto_activo', 'producto_activo', 'Activo', 'producto', 0, 1, 5),
(59, 'producto_clasificacion', 'clasificacion_nombre', 'Clasificacion', 'producto', 0, 1, 7),
(60, 'producto_tipo', 'tipo_prod_nombre', 'Tipo Producto', 'producto', 0, 1, 8),
(61, 'producto_codigo_interno', 'producto_codigo_interno', 'Codigo del Producto', 'producto', 1, 1, 2),
(63, 'producto_sustituto', 'producto_sustituto', 'Sustituto', 'producto', 0, 1, 11),
(65, 'producto_mensaje', 'producto_mensaje', 'Mensaje', 'producto', 0, 1, 13),
(66, 'producto_componente', 'componente_nombre', 'Componente o Droga', 'producto', 1, 1, 9),
(67, 'producto_ubicacion_fisica', 'ubicacion_nombre', 'Ubicación Física', 'producto', 0, 1, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes`
--

CREATE TABLE `componentes` (
  `componente_id` bigint(20) UNSIGNED NOT NULL,
  `componente_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`componente_id`, `componente_nombre`, `deleted_at`) VALUES
(1, 'ACETAMINOFEN', NULL),
(2, 'IBUPROFENO', NULL),
(3, 'AZITROMICINA', NULL),
(4, 'AMPICILINA', NULL),
(5, 'ATORVASTATINA', NULL),
(6, 'ACICLOVIR', NULL),
(7, 'ACIDO FUSIDICO', NULL),
(8, 'ACIDO VALPROICO', NULL),
(9, 'ACIDO ACETIL SALICILICO', NULL),
(10, 'ALIZAPRIDA', NULL),
(11, 'AMITRIPTILINA', NULL),
(12, 'ALENDRONATO', NULL),
(13, 'AMLODIPINO', NULL),
(14, 'AMIKACINA', NULL),
(15, 'ALBENDAZOL', NULL),
(16, 'AMOXICILINA', NULL),
(17, 'ACIDO FOLICO', NULL),
(18, 'SALBUTAMOL', NULL),
(19, 'NAPROXENO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condiciones_pago`
--

CREATE TABLE `condiciones_pago` (
  `id_condiciones` bigint(20) UNSIGNED NOT NULL,
  `nombre_condiciones` varchar(255) NOT NULL,
  `dias` int(11) NOT NULL,
  `status_condiciones` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `condiciones_pago`
--

INSERT INTO `condiciones_pago` (`id_condiciones`, `nombre_condiciones`, `dias`, `status_condiciones`) VALUES
(4, 'CONTADO', 0, 1),
(5, 'CREDITO', 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `config_id` bigint(20) UNSIGNED NOT NULL,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`config_id`, `config_key`, `config_value`) VALUES
(1, 'EMPRESA_NOMBRE', 'Droguería primicias'),
(2, 'EMPRESA_DIRECCION', 'Cali'),
(3, 'EMPRESA_TELEFONO', '302554645656'),
(4, 'MONTO_BOLETAS_VENTA', NULL),
(5, 'VENTA_SIN_STOCK', NULL),
(6, 'MONEDA', 'PESOS'),
(7, 'REFRESCAR_PEDIDOS', NULL),
(8, 'DATABASE_IP', NULL),
(9, 'DATABASE_NAME', NULL),
(10, 'DATABASE_USERNAME', NULL),
(11, 'EMPRESA_PAIS', '3'),
(12, 'MENSAJE_FACTURA', 'GRACIAS POR SU COMPRA'),
(13, 'CALCULO_PRECIO_VENTA', 'MATEMATICO'),
(14, 'CORRELATIVO_PRODUCTO', 'NO'),
(15, 'REGIMEN_CONTRIBUTIVO', ''),
(16, 'REPRESENTANTE_LEGAL', ''),
(17, 'NIT', ''),
(18, 'CODIGO_COOPIDROGAS', ''),
(19, 'MOSTRAR_SIN_STOCK', '1'),
(20, 'CALCULO_UTILIDAD', 'COSTO_UNITARIO'),
(21, 'BODEGA_PRINCIPAL', '1'),
(22, 'IMPRESORA', 'smb://Principal-PC/Generic');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE `credito` (
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `var_credito_estado` varchar(255) NOT NULL,
  `dec_credito_montodeuda` decimal(18,2) DEFAULT NULL,
  `dec_credito_montodebito` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id_venta`, `var_credito_estado`, `dec_credito_montodeuda`, `dec_credito_montodebito`) VALUES
(72, 'DEBE', '90888.00', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleingreso`
--

CREATE TABLE `detalleingreso` (
  `id_detalle_ingreso` bigint(20) UNSIGNED NOT NULL,
  `id_ingreso` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `total_detalle` float DEFAULT NULL,
  `porcentaje_descuento` float DEFAULT NULL,
  `impuesto_porcentaje` float DEFAULT NULL,
  `total_impuesto` float DEFAULT NULL,
  `porcentaje_bonificacion` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalleingreso`
--

INSERT INTO `detalleingreso` (`id_detalle_ingreso`, `id_ingreso`, `id_producto`, `status`, `total_detalle`, `porcentaje_descuento`, `impuesto_porcentaje`, `total_impuesto`, `porcentaje_bonificacion`) VALUES
(1, 1, 238, NULL, 12, 0, 0, 0, 0),
(2, 2, 238, NULL, 3, 0, 0, 0, 0),
(3, 3, 238, NULL, 34, 0, 0, 0, 0),
(4, 4, 238, NULL, 1, 0, 0, 0, 0),
(5, 5, 238, NULL, 1, 0, 0, 0, 0),
(6, 6, 238, NULL, 1, 0, 0, 0, 0),
(8, 8, 238, NULL, 1, 0, 0, 0, 0),
(9, 9, 238, NULL, 4, 0, 0, 0, 0),
(10, 10, 238, NULL, 9, 0, 0, 0, 0),
(11, 11, 238, NULL, 3, 0, 0, 0, 0),
(12, 12, 238, NULL, 2, 0, 0, 0, 0),
(14, 14, 238, NULL, 2, 0, 0, 0, 0),
(15, 15, 238, NULL, 2, 0, 0, 0, 0),
(16, 16, 238, NULL, 2, 0, 0, 0, 0),
(17, 17, 238, NULL, 34, 0, 0, 0, 0),
(18, 18, 238, NULL, 46, 0, 0, 0, 0),
(19, 19, 238, NULL, 46, 0, 0, 0, 0),
(20, 20, 238, NULL, 0, 0, 0, 0, 0),
(21, 21, 238, NULL, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleingreso_especial`
--

CREATE TABLE `detalleingreso_especial` (
  `id_detalle_especial` bigint(20) UNSIGNED NOT NULL,
  `detalle_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id_especial` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` float DEFAULT '0',
  `costo_uni` float DEFAULT '0',
  `costo_total` float DEFAULT '0',
  `tipo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso_unidad`
--

CREATE TABLE `detalle_ingreso_unidad` (
  `detalle_ingreso_unidad_id` bigint(20) NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `detalle_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `costo` float DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `impuesto` float DEFAULT NULL,
  `costo_total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_ingreso_unidad`
--

INSERT INTO `detalle_ingreso_unidad` (`detalle_ingreso_unidad_id`, `unidad_id`, `detalle_ingreso_id`, `costo`, `cantidad`, `impuesto`, `costo_total`) VALUES
(1, 1, 1, 0, 12, 0, 0),
(2, 2, 1, 1, 12, 0, 12),
(3, 1, 2, 0.0882353, 34, 0, 3),
(4, 1, 3, 11.3333, 3, 0, 34),
(5, 1, 4, 1, 1, 0, 1),
(6, 1, 5, 1, 1, 0, 1),
(7, 1, 6, 1, 1, 0, 1),
(8, 1, 8, 1, 1, 0, 1),
(9, 1, 9, 1, 1, 0, 1),
(10, 2, 9, 1.5, 2, 0, 3),
(11, 1, 10, 0.5, 4, 0, 2),
(12, 2, 10, 1, 3, 0, 3),
(13, 3, 10, 1.33333, 3, 0, 4),
(14, 1, 11, 1.5, 2, 0, 3),
(15, 1, 12, 1, 2, 0, 2),
(16, 1, 14, 2, 1, 0, 2),
(17, 1, 15, 1, 2, 0, 2),
(18, 1, 16, 1, 2, 0, 2),
(19, 2, 17, 4.25, 8, 0, 34),
(20, 1, 18, 12, 1, 0, 12),
(21, 2, 18, 4.25, 8, 0, 34),
(22, 1, 19, 12, 1, 0, 12),
(23, 2, 19, 4.25, 8, 0, 34),
(24, 1, 20, 0, 1, 0, 0),
(25, 1, 21, 0, 5, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle` bigint(20) UNSIGNED NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `impuesto` float DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `porcentaje_impuesto` float DEFAULT NULL,
  `total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle`, `id_venta`, `id_producto`, `impuesto`, `descuento`, `subtotal`, `porcentaje_impuesto`, `total`) VALUES
(3, 7, 238, NULL, NULL, NULL, NULL, NULL),
(4, 8, 238, NULL, NULL, NULL, NULL, NULL),
(5, 9, 238, NULL, NULL, NULL, NULL, NULL),
(6, 10, 238, NULL, NULL, NULL, NULL, NULL),
(7, 11, 238, NULL, NULL, NULL, NULL, NULL),
(8, 12, 238, NULL, NULL, NULL, NULL, NULL),
(9, 13, 238, NULL, NULL, NULL, NULL, NULL),
(10, 14, 238, NULL, NULL, NULL, NULL, NULL),
(11, 15, 238, NULL, NULL, NULL, NULL, NULL),
(12, 16, 238, NULL, NULL, NULL, NULL, NULL),
(14, 18, 238, NULL, NULL, NULL, NULL, NULL),
(15, 19, 238, NULL, NULL, NULL, NULL, NULL),
(16, 20, 238, NULL, NULL, NULL, NULL, NULL),
(18, 22, 238, NULL, NULL, NULL, NULL, NULL),
(19, 23, 238, NULL, NULL, NULL, NULL, NULL),
(20, 24, 238, NULL, NULL, NULL, NULL, NULL),
(21, 25, 191, NULL, NULL, NULL, NULL, NULL),
(22, 26, 191, NULL, NULL, NULL, NULL, NULL),
(23, 27, 191, NULL, NULL, NULL, NULL, NULL),
(24, 28, 191, NULL, NULL, NULL, NULL, NULL),
(25, 29, 191, NULL, NULL, NULL, NULL, NULL),
(26, 30, 191, NULL, NULL, NULL, NULL, NULL),
(27, 31, 191, NULL, NULL, NULL, NULL, NULL),
(28, 32, 191, NULL, NULL, NULL, NULL, NULL),
(29, 33, 191, NULL, NULL, NULL, NULL, NULL),
(30, 34, 238, NULL, NULL, NULL, NULL, NULL),
(31, 35, 238, NULL, NULL, NULL, NULL, NULL),
(32, 36, 238, NULL, NULL, NULL, NULL, NULL),
(33, 37, 238, NULL, NULL, NULL, NULL, NULL),
(34, 38, 238, NULL, NULL, NULL, NULL, NULL),
(35, 39, 238, NULL, NULL, NULL, NULL, NULL),
(36, 40, 238, NULL, NULL, NULL, NULL, NULL),
(37, 41, 238, NULL, NULL, NULL, NULL, NULL),
(38, 42, 238, NULL, NULL, NULL, NULL, NULL),
(39, 43, 238, NULL, NULL, NULL, NULL, NULL),
(40, 44, 238, NULL, NULL, NULL, NULL, NULL),
(41, 45, 238, NULL, NULL, NULL, NULL, NULL),
(42, 46, 238, NULL, NULL, NULL, NULL, NULL),
(43, 47, 238, NULL, NULL, NULL, NULL, NULL),
(44, 48, 238, NULL, NULL, NULL, NULL, NULL),
(45, 49, 238, NULL, NULL, NULL, NULL, NULL),
(46, 50, 238, NULL, NULL, NULL, NULL, NULL),
(47, 51, 191, NULL, NULL, NULL, NULL, NULL),
(48, 52, 238, NULL, NULL, NULL, NULL, NULL),
(49, 53, 238, NULL, NULL, NULL, NULL, NULL),
(50, 54, 238, NULL, NULL, NULL, NULL, NULL),
(51, 55, 238, NULL, NULL, NULL, NULL, NULL),
(52, 56, 238, NULL, NULL, NULL, NULL, NULL),
(53, 57, 238, NULL, NULL, NULL, NULL, NULL),
(54, 58, 238, NULL, NULL, NULL, NULL, NULL),
(55, 59, 238, NULL, NULL, NULL, NULL, NULL),
(63, 60, 238, NULL, NULL, NULL, NULL, NULL),
(64, 61, 238, NULL, NULL, NULL, NULL, NULL),
(67, 17, 238, NULL, NULL, NULL, NULL, NULL),
(69, 21, 238, NULL, NULL, NULL, NULL, NULL),
(71, 64, 238, NULL, NULL, NULL, NULL, NULL),
(72, 65, 238, NULL, NULL, NULL, NULL, NULL),
(73, 66, 238, NULL, NULL, NULL, NULL, NULL),
(74, 67, 238, NULL, NULL, NULL, NULL, NULL),
(75, 68, 238, NULL, NULL, NULL, NULL, NULL),
(76, 69, 238, NULL, NULL, NULL, NULL, NULL),
(77, 70, 238, NULL, NULL, NULL, NULL, NULL),
(78, 71, 238, NULL, NULL, NULL, NULL, NULL),
(79, 72, 238, NULL, NULL, NULL, NULL, NULL),
(80, 73, 235, NULL, NULL, NULL, NULL, NULL),
(81, 73, 238, NULL, NULL, NULL, NULL, NULL),
(82, 74, 213, NULL, NULL, NULL, NULL, NULL),
(83, 75, 213, NULL, NULL, NULL, NULL, NULL),
(84, 76, 213, NULL, NULL, NULL, NULL, NULL),
(85, 77, 213, NULL, NULL, NULL, NULL, NULL),
(86, 78, 213, NULL, NULL, NULL, NULL, NULL),
(87, 79, 213, NULL, NULL, NULL, NULL, NULL),
(88, 80, 239, NULL, NULL, NULL, NULL, NULL),
(89, 80, 240, NULL, NULL, NULL, NULL, NULL),
(90, 80, 176, NULL, NULL, NULL, NULL, NULL),
(91, 81, 176, NULL, NULL, NULL, NULL, NULL),
(92, 81, 240, NULL, NULL, NULL, NULL, NULL),
(93, 81, 239, NULL, NULL, NULL, NULL, NULL),
(94, 82, 176, NULL, NULL, NULL, NULL, NULL),
(95, 82, 239, NULL, NULL, NULL, NULL, NULL),
(96, 82, 240, NULL, NULL, NULL, NULL, NULL),
(97, 83, 240, NULL, NULL, NULL, NULL, NULL),
(98, 83, 241, NULL, NULL, NULL, NULL, NULL),
(99, 85, 240, NULL, NULL, NULL, NULL, NULL),
(100, 85, 241, NULL, NULL, NULL, NULL, NULL),
(101, 86, 176, 0, 0, 500, 0, 500),
(102, 86, 241, 1740.34, 0, 9159.66, 19, 10900),
(103, 87, 176, 0, 0, 500, 0, 500),
(104, 87, 241, 1740.34, 0, 9159.66, 19, 10900),
(105, 88, 176, 0, 70.1754, 500, 0, 500),
(106, 88, 241, 1496.08, 1529.82, 9403.92, 19, 10900),
(107, 89, 241, 3225.21, 1600, 18574.8, 19, 21800),
(108, 90, 241, 1740.34, 0, 9159.66, 19, 10900),
(109, 91, 241, 1740.34, 0, 9159.66, 19, 10900),
(110, 92, 241, 1740.34, 0, 9159.66, 19, 10900),
(111, 93, 241, 1596.64, 900, 9303.36, 19, 10900),
(112, 94, 238, 0.909091, 0, 9.09091, 10, 10),
(113, 95, 191, 3748.48, 0, 108706, 5, 112454),
(114, 96, 191, 3748.48, 0, 108706, 5, 112454),
(115, 97, 191, 3748.48, 0, 108706, 5, 112454),
(116, 98, 191, 3748.48, 0, 108706, 5, 112454),
(117, 99, 191, 3748.48, 0, 108706, 5, 112454),
(118, 100, 191, 3748.48, 0, 108706, 5, 112454),
(119, 101, 176, 0, 0, 1000, 0, 1000),
(123, 102, 176, 0, 0, 0, 0, 0),
(124, 103, 176, 0, 0, 500, 0, 500),
(125, 104, 176, 0, 0, 500, 0, 500),
(126, 105, 176, 0, 0, 500, 0, 500),
(127, 106, 241, 3480.67, 0, 18319.3, 19, 21800),
(128, 107, 241, 1740.34, 0, 9159.66, 19, 10900),
(129, 108, 241, 3480.67, 0, 18319.3, 19, 21800),
(130, 109, 241, 3480.67, 0, 18319.3, 19, 21800),
(131, 110, 241, 1740.34, 0, 9159.66, 19, 10900),
(132, 111, 241, 1740.34, 0, 9159.66, 19, 10900),
(133, 112, 241, 1740.34, 0, 9159.66, 19, 10900);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_backup`
--

CREATE TABLE `detalle_venta_backup` (
  `id_detalle` bigint(20) UNSIGNED NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `impuesto` float DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `porcentaje_impuesto` float DEFAULT NULL,
  `total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta_backup`
--

INSERT INTO `detalle_venta_backup` (`id_detalle`, `id_venta`, `id_producto`, `impuesto`, `descuento`, `subtotal`, `porcentaje_impuesto`, `total`) VALUES
(3, 7, 238, NULL, NULL, NULL, NULL, NULL),
(4, 8, 238, NULL, NULL, NULL, NULL, NULL),
(5, 9, 238, NULL, NULL, NULL, NULL, NULL),
(6, 10, 238, NULL, NULL, NULL, NULL, NULL),
(7, 11, 238, NULL, NULL, NULL, NULL, NULL),
(8, 12, 238, NULL, NULL, NULL, NULL, NULL),
(9, 13, 238, NULL, NULL, NULL, NULL, NULL),
(10, 14, 238, NULL, NULL, NULL, NULL, NULL),
(11, 15, 238, NULL, NULL, NULL, NULL, NULL),
(12, 16, 238, NULL, NULL, NULL, NULL, NULL),
(13, 17, 238, NULL, NULL, NULL, NULL, NULL),
(14, 18, 238, NULL, NULL, NULL, NULL, NULL),
(15, 19, 238, NULL, NULL, NULL, NULL, NULL),
(16, 20, 238, NULL, NULL, NULL, NULL, NULL),
(17, 21, 238, NULL, NULL, NULL, NULL, NULL),
(18, 22, 238, NULL, NULL, NULL, NULL, NULL),
(19, 23, 238, NULL, NULL, NULL, NULL, NULL),
(20, 24, 238, NULL, NULL, NULL, NULL, NULL),
(21, 25, 191, NULL, NULL, NULL, NULL, NULL),
(22, 26, 191, NULL, NULL, NULL, NULL, NULL),
(23, 27, 191, NULL, NULL, NULL, NULL, NULL),
(24, 28, 191, NULL, NULL, NULL, NULL, NULL),
(25, 29, 191, NULL, NULL, NULL, NULL, NULL),
(26, 30, 191, NULL, NULL, NULL, NULL, NULL),
(27, 31, 191, NULL, NULL, NULL, NULL, NULL),
(28, 32, 191, NULL, NULL, NULL, NULL, NULL),
(29, 33, 191, NULL, NULL, NULL, NULL, NULL),
(30, 34, 238, NULL, NULL, NULL, NULL, NULL),
(31, 35, 238, NULL, NULL, NULL, NULL, NULL),
(32, 36, 238, NULL, NULL, NULL, NULL, NULL),
(33, 37, 238, NULL, NULL, NULL, NULL, NULL),
(34, 38, 238, NULL, NULL, NULL, NULL, NULL),
(35, 39, 238, NULL, NULL, NULL, NULL, NULL),
(36, 40, 238, NULL, NULL, NULL, NULL, NULL),
(37, 41, 238, NULL, NULL, NULL, NULL, NULL),
(38, 42, 238, NULL, NULL, NULL, NULL, NULL),
(39, 43, 238, NULL, NULL, NULL, NULL, NULL),
(40, 44, 238, NULL, NULL, NULL, NULL, NULL),
(41, 45, 238, NULL, NULL, NULL, NULL, NULL),
(42, 46, 238, NULL, NULL, NULL, NULL, NULL),
(43, 47, 238, NULL, NULL, NULL, NULL, NULL),
(44, 48, 238, NULL, NULL, NULL, NULL, NULL),
(45, 49, 238, NULL, NULL, NULL, NULL, NULL),
(46, 50, 238, NULL, NULL, NULL, NULL, NULL),
(47, 51, 191, NULL, NULL, NULL, NULL, NULL),
(48, 52, 238, NULL, NULL, NULL, NULL, NULL),
(49, 53, 238, NULL, NULL, NULL, NULL, NULL),
(50, 54, 238, NULL, NULL, NULL, NULL, NULL),
(51, 55, 238, NULL, NULL, NULL, NULL, NULL),
(52, 56, 238, NULL, NULL, NULL, NULL, NULL),
(53, 57, 238, NULL, NULL, NULL, NULL, NULL),
(54, 58, 238, NULL, NULL, NULL, NULL, NULL),
(55, 59, 238, NULL, NULL, NULL, NULL, NULL),
(56, 60, 238, NULL, NULL, NULL, NULL, NULL),
(57, 61, 238, NULL, NULL, NULL, NULL, NULL),
(59, 64, 238, NULL, NULL, NULL, NULL, NULL),
(60, 65, 238, NULL, NULL, NULL, NULL, NULL),
(61, 66, 238, NULL, NULL, NULL, NULL, NULL),
(62, 67, 238, NULL, NULL, NULL, NULL, NULL),
(63, 68, 238, NULL, NULL, NULL, NULL, NULL),
(64, 69, 238, NULL, NULL, NULL, NULL, NULL),
(65, 70, 238, NULL, NULL, NULL, NULL, NULL),
(66, 71, 238, NULL, NULL, NULL, NULL, NULL),
(67, 72, 238, NULL, NULL, NULL, NULL, NULL),
(68, 73, 235, NULL, NULL, NULL, NULL, NULL),
(69, 73, 238, NULL, NULL, NULL, NULL, NULL),
(70, 74, 213, NULL, NULL, NULL, NULL, NULL),
(71, 75, 213, NULL, NULL, NULL, NULL, NULL),
(72, 76, 213, NULL, NULL, NULL, NULL, NULL),
(73, 77, 213, NULL, NULL, NULL, NULL, NULL),
(74, 78, 213, NULL, NULL, NULL, NULL, NULL),
(75, 79, 213, NULL, NULL, NULL, NULL, NULL),
(76, 80, 239, NULL, NULL, NULL, NULL, NULL),
(77, 80, 240, NULL, NULL, NULL, NULL, NULL),
(78, 80, 176, NULL, NULL, NULL, NULL, NULL),
(79, 81, 176, NULL, NULL, NULL, NULL, NULL),
(80, 81, 240, NULL, NULL, NULL, NULL, NULL),
(81, 81, 239, NULL, NULL, NULL, NULL, NULL),
(82, 82, 176, NULL, NULL, NULL, NULL, NULL),
(83, 82, 239, NULL, NULL, NULL, NULL, NULL),
(84, 82, 240, NULL, NULL, NULL, NULL, NULL),
(85, 83, 240, NULL, NULL, NULL, NULL, NULL),
(86, 83, 241, NULL, NULL, NULL, NULL, NULL),
(87, 85, 240, NULL, NULL, NULL, NULL, NULL),
(88, 85, 241, NULL, NULL, NULL, NULL, NULL),
(89, 86, 176, 0, 0, 500, 0, 500),
(90, 86, 241, 1740.34, 0, 9159.66, 19, 10900),
(91, 87, 176, 0, 0, 500, 0, 500),
(92, 87, 241, 1740.34, 0, 9159.66, 19, 10900),
(93, 88, 176, 0, 70.1754, 500, 0, 500),
(94, 88, 241, 1496.08, 1529.82, 9403.92, 19, 10900),
(95, 89, 241, 3225.21, 1600, 18574.8, 19, 21800),
(96, 90, 241, 1740.34, 0, 9159.66, 19, 10900),
(97, 91, 241, 1740.34, 0, 9159.66, 19, 10900),
(98, 92, 241, 1740.34, 0, 9159.66, 19, 10900),
(99, 93, 241, 1596.64, 900, 9303.36, 19, 10900),
(100, 94, 238, 0.909091, 0, 9.09091, 10, 10),
(101, 95, 191, 3748.48, 0, 108706, 5, 112454),
(102, 96, 191, 3748.48, 0, 108706, 5, 112454),
(103, 97, 191, 3748.48, 0, 108706, 5, 112454),
(104, 98, 191, 3748.48, 0, 108706, 5, 112454),
(105, 99, 191, 3748.48, 0, 108706, 5, 112454),
(106, 100, 191, 3748.48, 0, 108706, 5, 112454),
(107, 101, 176, 0, 0, 1000, 0, 1000),
(108, 102, 176, 0, 0, 1000, 0, 1000),
(109, 103, 176, 0, 0, 500, 0, 500),
(110, 104, 176, 0, 0, 500, 0, 500),
(111, 105, 176, 0, 0, 500, 0, 500),
(112, 106, 241, 3480.67, 0, 18319.3, 19, 21800),
(113, 107, 241, 1740.34, 0, 9159.66, 19, 10900),
(114, 108, 241, 3480.67, 0, 18319.3, 19, 21800),
(115, 109, 241, 3480.67, 0, 18319.3, 19, 21800),
(116, 110, 241, 1740.34, 0, 9159.66, 19, 10900),
(117, 111, 241, 1740.34, 0, 9159.66, 19, 10900),
(118, 112, 241, 1740.34, 0, 9159.66, 19, 10900);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_unidad`
--

CREATE TABLE `detalle_venta_unidad` (
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `detalle_venta_id` bigint(20) UNSIGNED NOT NULL,
  `precio` float DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `utilidad` float DEFAULT NULL,
  `costo` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta_unidad`
--

INSERT INTO `detalle_venta_unidad` (`unidad_id`, `detalle_venta_id`, `precio`, `cantidad`, `utilidad`, `costo`) VALUES
(1, 3, 3753, 1, 3753, NULL),
(2, 3, 3753, 1, 3753, NULL),
(1, 4, 3753, 1, 3753, NULL),
(2, 4, 3753, 1, 3753, NULL),
(1, 5, 3753, 1, 3753, NULL),
(2, 5, 3753, 1, 3753, NULL),
(1, 6, 3753, 1, 3753, NULL),
(2, 6, 3753, 1, 3753, NULL),
(1, 7, 3753, 1, 3753, NULL),
(2, 7, 3753, 1, 3753, NULL),
(1, 8, 3753, 1, 3753, NULL),
(2, 8, 3753, 1, 3753, NULL),
(1, 9, 3753, 1, 3753, NULL),
(2, 9, 3753, 1, 3753, NULL),
(1, 10, 3753, 1, 3753, NULL),
(2, 10, 3753, 1, 3753, NULL),
(3, 11, 375, 1, 375, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(3, 14, 375, 1, 375, NULL),
(3, 15, 375, 1, 375, NULL),
(3, 16, 375, 3, 1125, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 21, 112454, 1, 112454, NULL),
(1, 22, 112454, 1, 112454, NULL),
(1, 23, 112454, 1, 112454, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(2, 33, 3753, 4, 15012, NULL),
(2, 34, 3753, 4, 15012, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 43, 3753, 1, 3753, NULL),
(1, 44, 3753, 1, 3753, NULL),
(1, 45, 3753, 1, 3753, NULL),
(3, 46, 375, 6, 2250, NULL),
(2, 48, 3753, 5, 18765, NULL),
(1, 49, 3753, 6, 22518, NULL),
(3, 50, 375, 1, 375, NULL),
(1, 51, 3753, 2, 7506, NULL),
(2, 52, 3753, 2, 7506, NULL),
(1, 53, 3753, 5, 18765, NULL),
(1, 54, 3787, 3, 11361, NULL),
(1, 55, 3753, 1, 3753, NULL),
(2, 55, 3753, 2, 7506, NULL),
(1, 63, 3753, 1, 3753, NULL),
(1, 64, 3753, 1, 3753, NULL),
(1, 67, 3753, 3, 11259, NULL),
(1, 69, 3753, 3, 11259, NULL),
(3, 69, 375, 3, 1125, NULL),
(1, 71, 3753, 2, 7506, NULL),
(1, 72, 3753, 2, 7506, 0),
(1, 73, 3753, 2, 7506, 0),
(1, 74, 3753, 2, 7506, 0),
(1, 75, 3753, 2, 7506, 0),
(1, 76, 3787, 3, 11361, 0),
(1, 77, 3753, 2, 7506, 0),
(1, 78, 3787, 2, 7574, 0),
(1, 79, 3787, 22, 83314, 0),
(2, 79, 3787, 2, 7574, 0),
(1, 80, 95951, 1, 95951, 0),
(1, 81, 10, 1, 10, 0),
(1, 82, 10900, 1, 10900, 0),
(1, 83, 10900, 1, 10900, 0),
(1, 84, 10900, 1, 10900, 0),
(1, 85, 10900, 1, 10900, 0),
(1, 86, 10900, 1, 10900, 0),
(1, 87, 10900, 1, 10900, 0),
(3, 88, 4700, 1, 4700, 0),
(3, 89, 9900, 1, 9900, 0),
(3, 90, 500, 2, 1000, 0),
(3, 91, 500, 2, 1000, 0),
(3, 92, 9900, 1, 9900, 0),
(3, 93, 4700, 1, 4700, 0),
(3, 94, 500, 2, 1000, 0),
(3, 95, 4700, 1, 4700, 0),
(3, 96, 9900, 1, 9900, 0),
(3, 97, 9900, 1, 9900, 0),
(3, 98, 10900, 1, 10900, 0),
(3, 99, 9900, 1, 9900, 0),
(3, 100, 10900, 1, 10900, 0),
(3, 101, 500, 1, 500, NULL),
(3, 102, 10900, 1, 10900, NULL),
(3, 103, 500, 1, 500, NULL),
(3, 104, 10900, 1, 10900, NULL),
(3, 105, 500, 1, 500, NULL),
(3, 106, 10900, 1, 10900, NULL),
(3, 107, 10900, 2, 21800, 0),
(3, 108, 10900, 1, 4900, 6000),
(3, 109, 10900, 1, 4900, 6000),
(3, 110, 10900, 1, 4900, 6000),
(3, 111, 10900, 1, 4900, 6000),
(1, 112, 10, 1, 1.5, 8.5),
(1, 113, 112454, 1, NULL, 8.5),
(1, 114, 112454, 1, NULL, 8.5),
(3, 115, 0, 1, -8.5, 8.5),
(3, 116, 112454, 1, 112446, 8.5),
(1, 117, 112454, 1, -8.5, 8.5),
(1, 118, 112454, 1, 112446, 8.5),
(3, 119, 500, 2, 977, 11.5),
(3, 123, 500, 2, 0, 11.5),
(1, 124, 0, 1, -11.5, 11.5),
(3, 124, 500, 1, 488.5, 11.5),
(3, 125, 500, 1, 488.5, 11.5),
(3, 126, 500, 1, 488.5, 11.5),
(3, 127, 10900, 2, 9800, 6000),
(3, 128, 10900, 1, 4900, 6000),
(3, 129, 10900, 2, 9800, 6000),
(3, 130, 10900, 2, 9800, 6000),
(3, 131, 10900, 1, 4900, 6000),
(3, 132, 10900, 1, 4900, 6000),
(3, 133, 10900, 1, 4900, 6000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_unidad_backup`
--

CREATE TABLE `detalle_venta_unidad_backup` (
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `detalle_venta_id` bigint(20) UNSIGNED NOT NULL,
  `precio` float DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `utilidad` float DEFAULT NULL,
  `costo` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_venta_unidad_backup`
--

INSERT INTO `detalle_venta_unidad_backup` (`unidad_id`, `detalle_venta_id`, `precio`, `cantidad`, `utilidad`, `costo`) VALUES
(1, 3, 3753, 1, 3753, NULL),
(2, 3, 3753, 1, 3753, NULL),
(1, 4, 3753, 1, 3753, NULL),
(2, 4, 3753, 1, 3753, NULL),
(1, 5, 3753, 1, 3753, NULL),
(2, 5, 3753, 1, 3753, NULL),
(1, 6, 3753, 1, 3753, NULL),
(2, 6, 3753, 1, 3753, NULL),
(1, 7, 3753, 1, 3753, NULL),
(2, 7, 3753, 1, 3753, NULL),
(1, 8, 3753, 1, 3753, NULL),
(2, 8, 3753, 1, 3753, NULL),
(1, 9, 3753, 1, 3753, NULL),
(2, 9, 3753, 1, 3753, NULL),
(1, 10, 3753, 1, 3753, NULL),
(2, 10, 3753, 1, 3753, NULL),
(3, 11, 375, 1, 375, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 12, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(1, 13, 3753, 1, 3753, NULL),
(3, 14, 375, 1, 375, NULL),
(3, 15, 375, 1, 375, NULL),
(3, 16, 375, 3, 1125, NULL),
(3, 17, 375, 3, 1125, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 18, 3753, 1, 3753, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 19, 3753, 3, 11259, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 20, 3753, 1, 3753, NULL),
(1, 21, 112454, 1, 112454, NULL),
(1, 22, 112454, 1, 112454, NULL),
(1, 23, 112454, 1, 112454, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 30, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 31, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(1, 32, 3753, 1, 3753, NULL),
(2, 33, 3753, 4, 15012, NULL),
(2, 34, 3753, 4, 15012, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 35, 3753, 3, 11259, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 36, 3753, 2, 7506, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 37, 3753, 23, 86319, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 38, 3753, 2, 7506, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 39, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 40, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 41, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 42, 3753, 1, 3753, NULL),
(1, 43, 3753, 1, 3753, NULL),
(1, 44, 3753, 1, 3753, NULL),
(1, 45, 3753, 1, 3753, NULL),
(3, 46, 375, 6, 2250, NULL),
(2, 48, 3753, 5, 18765, NULL),
(1, 49, 3753, 6, 22518, NULL),
(3, 50, 375, 1, 375, NULL),
(1, 51, 3753, 2, 7506, NULL),
(2, 52, 3753, 2, 7506, NULL),
(1, 53, 3753, 5, 18765, NULL),
(1, 54, 3787, 3, 11361, NULL),
(1, 55, 3753, 1, 3753, NULL),
(2, 55, 3753, 2, 7506, NULL),
(1, 56, 3753, 1, 3753, NULL),
(1, 57, 3753, 1, 3753, NULL),
(1, 59, 3753, 2, 7506, NULL),
(1, 60, 3753, 2, 7506, 0),
(1, 61, 3753, 2, 7506, 0),
(1, 62, 3753, 2, 7506, 0),
(1, 63, 3753, 2, 7506, 0),
(1, 64, 3787, 3, 11361, 0),
(1, 65, 3753, 2, 7506, 0),
(1, 66, 3787, 2, 7574, 0),
(1, 67, 3787, 22, 83314, 0),
(2, 67, 3787, 2, 7574, 0),
(1, 68, 95951, 1, 95951, 0),
(1, 69, 10, 1, 10, 0),
(1, 70, 10900, 1, 10900, 0),
(1, 71, 10900, 1, 10900, 0),
(1, 72, 10900, 1, 10900, 0),
(1, 73, 10900, 1, 10900, 0),
(1, 74, 10900, 1, 10900, 0),
(1, 75, 10900, 1, 10900, 0),
(3, 76, 4700, 1, 4700, 0),
(3, 77, 9900, 1, 9900, 0),
(3, 78, 500, 2, 1000, 0),
(3, 79, 500, 2, 1000, 0),
(3, 80, 9900, 1, 9900, 0),
(3, 81, 4700, 1, 4700, 0),
(3, 82, 500, 2, 1000, 0),
(3, 83, 4700, 1, 4700, 0),
(3, 84, 9900, 1, 9900, 0),
(3, 85, 9900, 1, 9900, 0),
(3, 86, 10900, 1, 10900, 0),
(3, 87, 9900, 1, 9900, 0),
(3, 88, 10900, 1, 10900, 0),
(3, 89, 500, 1, 500, NULL),
(3, 90, 10900, 1, 10900, NULL),
(3, 91, 500, 1, 500, NULL),
(3, 92, 10900, 1, 10900, NULL),
(3, 93, 500, 1, 500, NULL),
(3, 94, 10900, 1, 10900, NULL),
(3, 95, 10900, 2, 21800, 0),
(3, 96, 10900, 1, 4900, 6000),
(3, 97, 10900, 1, 4900, 6000),
(3, 98, 10900, 1, 4900, 6000),
(3, 99, 10900, 1, 4900, 6000),
(1, 100, 10, 1, 1.5, 8.5),
(1, 101, 112454, 1, NULL, 8.5),
(1, 102, 112454, 1, NULL, 8.5),
(3, 103, 0, 1, -8.5, 8.5),
(3, 104, 112454, 1, 112446, 8.5),
(1, 105, 112454, 1, -8.5, 8.5),
(1, 106, 112454, 1, 112446, 8.5),
(3, 107, 500, 2, 977, 11.5),
(3, 108, 500, 2, 977, 11.5),
(1, 109, 0, 1, -11.5, 11.5),
(3, 109, 500, 1, 488.5, 11.5),
(3, 110, 500, 1, 488.5, 11.5),
(3, 111, 500, 1, 488.5, 11.5),
(3, 112, 10900, 2, 9800, 6000),
(3, 113, 10900, 1, 4900, 6000),
(3, 114, 10900, 2, 9800, 6000),
(3, 115, 10900, 2, 9800, 6000),
(3, 116, 10900, 1, 4900, 6000),
(3, 117, 10900, 1, 4900, 6000),
(3, 118, 10900, 1, 4900, 6000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_inventarios`
--

CREATE TABLE `documentos_inventarios` (
  `documento_id` bigint(20) UNSIGNED NOT NULL,
  `documento_nombre` varchar(255) DEFAULT NULL,
  `documento_tipo` varchar(255) NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `documentos_inventarios`
--

INSERT INTO `documentos_inventarios` (`documento_id`, `documento_nombre`, `documento_tipo`, `deleted_at`) VALUES
(1, 'ENTRADA POR AJUSTE DE INVENTARIO', 'ENTRADA', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_venta`
--

CREATE TABLE `documento_venta` (
  `id_tipo_documento` bigint(20) UNSIGNED NOT NULL,
  `nombre_tipo_documento` varchar(255) DEFAULT NULL,
  `documento_Numero` varchar(20) NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `id_resolucion` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `documento_venta`
--

INSERT INTO `documento_venta` (`id_tipo_documento`, `nombre_tipo_documento`, `documento_Numero`, `id_venta`, `id_resolucion`) VALUES
(5, 'FACTURA', '0000000001', 7, NULL),
(6, 'FACTURA', '0000000002', 8, NULL),
(7, 'FACTURA', '0000000003', 9, NULL),
(8, 'FACTURA', '0000000004', 10, NULL),
(9, 'FACTURA', '0000000005', 11, NULL),
(10, 'FACTURA', '0000000006', 12, NULL),
(11, 'FACTURA', '0000000007', 13, NULL),
(12, 'FACTURA', '0000000008', 14, NULL),
(13, 'FACTURA', '0000000009', 15, NULL),
(14, 'FACTURA', '0000000010', 16, NULL),
(15, 'FACTURA', '0000000011', 17, NULL),
(16, 'FACTURA', '0000000012', 18, NULL),
(17, 'FACTURA', '0000000013', 19, NULL),
(18, 'FACTURA', '0000000014', 20, NULL),
(19, 'FACTURA', '0000000015', 21, NULL),
(20, 'FACTURA', '0000000016', 22, NULL),
(21, 'FACTURA', '0000000017', 23, NULL),
(22, 'FACTURA', '0000000018', 24, NULL),
(23, 'FACTURA', '0000000019', 25, NULL),
(24, 'FACTURA', '0000000020', 26, NULL),
(25, 'FACTURA', '0000000021', 27, NULL),
(26, 'FACTURA', '0000000022', 28, NULL),
(27, 'FACTURA', '0000000023', 29, NULL),
(28, 'FACTURA', '0000000024', 30, NULL),
(29, 'FACTURA', '0000000025', 31, NULL),
(30, 'FACTURA', '0000000026', 32, NULL),
(31, 'FACTURA', '0000000027', 33, NULL),
(32, 'FACTURA', '0000000028', 34, NULL),
(33, 'FACTURA', '0000000029', 35, NULL),
(34, 'FACTURA', '0000000030', 36, NULL),
(35, 'FACTURA', '0000000031', 37, NULL),
(36, 'FACTURA', '0000000032', 38, NULL),
(37, 'FACTURA', '0000000033', 39, NULL),
(38, 'FACTURA', '0000000034', 40, NULL),
(39, 'FACTURA', '0000000035', 41, NULL),
(40, 'FACTURA', '0000000036', 42, NULL),
(41, 'FACTURA', '0000000037', 43, NULL),
(42, 'FACTURA', '0000000038', 44, NULL),
(43, 'FACTURA', '0000000039', 45, NULL),
(44, 'FACTURA', '0000000040', 46, NULL),
(45, 'FACTURA', '0000000041', 47, NULL),
(46, 'FACTURA', '0000000042', 48, NULL),
(47, 'FACTURA', '0000000043', 49, NULL),
(48, 'FACTURA', '0000000044', 50, NULL),
(49, 'FACTURA', '0000000045', 51, NULL),
(50, 'FACTURA', '0000000046', 52, NULL),
(51, 'FACTURA', '0000000047', 53, NULL),
(52, 'FACTURA', '0000000048', 54, NULL),
(53, 'FACTURA', '0000000049', 55, NULL),
(54, 'FACTURA', '0000000050', 56, NULL),
(55, 'FACTURA', '0000000051', 57, NULL),
(56, 'FACTURA', '0000000052', 58, NULL),
(57, 'FACTURA', '0000000053', 59, NULL),
(58, 'FACTURA', '0000000054', 60, NULL),
(59, 'FACTURA', '0000000055', 61, NULL),
(62, 'FACTURA', '0000000056', 64, NULL),
(63, 'FACTURA', '0000000057', 65, NULL),
(64, 'FACTURA', '0000000058', 66, NULL),
(65, 'FACTURA', '0000000059', 67, NULL),
(66, 'FACTURA', '0000000060', 68, NULL),
(67, 'FACTURA', '0000000061', 69, NULL),
(68, 'FACTURA', '0000000062', 70, NULL),
(69, 'FACTURA', '0000000063', 71, NULL),
(70, 'FACTURA', '0000000064', 72, NULL),
(71, 'FACTURA', '0000000065', 73, NULL),
(72, 'FACTURA', '0000000066', 74, NULL),
(73, 'FACTURA', '0000000067', 75, NULL),
(74, 'FACTURA', '0000000068', 76, NULL),
(75, 'FACTURA', '0000000069', 77, NULL),
(76, 'FACTURA', '0000000070', 78, NULL),
(77, 'FACTURA', '0000000071', 79, NULL),
(78, 'FACTURA', '0000000072', 80, NULL),
(79, 'FACTURA', '0000000073', 81, NULL),
(80, 'FACTURA', '0000000074', 82, NULL),
(81, 'FACTURA', '0000000075', 83, NULL),
(83, 'FACTURA', '0000000076', 85, NULL),
(84, 'FACTURA', '0000000077', 86, NULL),
(85, 'FACTURA', '0000000078', 87, NULL),
(86, 'FACTURA', '0000000079', 88, NULL),
(87, 'FACTURA', '0000000080', 89, NULL),
(88, 'FACTURA', '0000000081', 90, NULL),
(89, 'FACTURA', '0000000082', 91, NULL),
(90, 'FACTURA', '0000000083', 92, NULL),
(91, 'FACTURA', '0000000084', 93, NULL),
(92, 'FACTURA', '0000000085', 94, NULL),
(93, 'FACTURA', '0000000086', 95, NULL),
(94, 'FACTURA', '0000000087', 96, NULL),
(95, 'FACTURA', '0000000088', 97, NULL),
(96, 'FACTURA', '0000000089', 98, NULL),
(97, 'FACTURA', '0000000090', 99, NULL),
(98, 'FACTURA', '0000000091', 100, NULL),
(99, 'FACTURA', '0000000092', 101, NULL),
(100, 'FACTURA', '0000000093', 102, NULL),
(101, 'FACTURA', '11', 103, 1),
(102, 'FACTURA', '12', 104, 1),
(103, 'FACTURA', '13', 105, 1),
(104, 'FACTURA', '14', 106, 1),
(105, 'FACTURA', '15', 107, 1),
(106, 'FACTURA', '16', 108, 1),
(107, 'FACTURA', '17', 109, 1),
(108, 'FACTURA', '1', 110, 2),
(109, 'FACTURA', '2', 111, 2),
(110, 'FACTURA', '3', 112, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `droguerias_relacionadas`
--

CREATE TABLE `droguerias_relacionadas` (
  `drogueria_id` bigint(20) UNSIGNED NOT NULL,
  `drogueria_nombre` varchar(255) DEFAULT NULL,
  `drogueria_domain` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `droguerias_relacionadas`
--

INSERT INTO `droguerias_relacionadas` (`drogueria_id`, `drogueria_nombre`, `drogueria_domain`, `deleted_at`) VALUES
(1, 'DROGUERIA EN LA NUBE', 'http://sid.prosode.com/', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `estados_id` bigint(20) UNSIGNED NOT NULL,
  `estados_nombre` varchar(255) DEFAULT NULL,
  `pais_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`estados_id`, `estados_nombre`, `pais_id`) VALUES
(5, 'Valle del cauca', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia`
--

CREATE TABLE `familia` (
  `id_familia` bigint(20) UNSIGNED NOT NULL,
  `nombre_familia` varchar(255) DEFAULT NULL,
  `estatus_familia` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` bigint(20) UNSIGNED NOT NULL,
  `nombre_grupo` varchar(255) DEFAULT NULL,
  `estatus_grupo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `estatus_grupo`) VALUES
(1, 'LECHES', 1),
(2, 'PAñALES', 1),
(3, 'DESODORANTES', 1),
(4, 'DESODORANTES EN SOBRE', 1),
(5, 'CHAMPU', 1),
(6, 'CHAMPU SOBRE', 1),
(7, 'CUIDADO DEL BEBE', 1),
(8, 'jabones1478033277', 0),
(9, 'JABONES', 1),
(10, 'PAñAL', 1),
(11, 'CREMA', 1),
(12, 'TOALLAS Y PROTECTORES', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_cliente`
--

CREATE TABLE `grupos_cliente` (
  `id_grupos_cliente` bigint(20) UNSIGNED NOT NULL,
  `nombre_grupos_cliente` varchar(255) DEFAULT NULL,
  `status_grupos_cliente` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupos_cliente`
--

INSERT INTO `grupos_cliente` (`id_grupos_cliente`, `nombre_grupos_cliente`, `status_grupos_cliente`) VALUES
(1, 'EMPRESA', 1),
(2, 'PARTICULAR', 1),
(3, 'CLIENTE MINORISTA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_usuarios`
--

CREATE TABLE `grupos_usuarios` (
  `id_grupos_usuarios` bigint(20) UNSIGNED NOT NULL,
  `nombre_grupos_usuarios` varchar(255) DEFAULT NULL,
  `status_grupos_usuarios` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `grupos_usuarios`
--

INSERT INTO `grupos_usuarios` (`id_grupos_usuarios`, `nombre_grupos_usuarios`, `status_grupos_usuarios`) VALUES
(1, 'Administrador', 1),
(2, 'Vendedor', 1),
(3, 'Cajero', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos_clientes`
--

CREATE TABLE `historial_pagos_clientes` (
  `historial_id` bigint(20) UNSIGNED NOT NULL,
  `credito_id` bigint(20) UNSIGNED NOT NULL,
  `historial_monto` float(20,2) DEFAULT NULL,
  `monto_restante` float(20,2) DEFAULT NULL,
  `recibo_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos`
--

CREATE TABLE `impuestos` (
  `id_impuesto` bigint(20) UNSIGNED NOT NULL,
  `nombre_impuesto` varchar(255) DEFAULT NULL,
  `porcentaje_impuesto` float DEFAULT NULL,
  `estatus_impuesto` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `impuestos`
--

INSERT INTO `impuestos` (`id_impuesto`, `nombre_impuesto`, `porcentaje_impuesto`, `estatus_impuesto`) VALUES
(1, 'IVA 16', 16, 1),
(3, 'IVA 5', 5, 1),
(4, 'IVA 7', 7, 1),
(5, 'IVA 10', 10, 1),
(6, 'IVA 0', 0, 1),
(7, 'IVA 19', 19, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE `ingreso` (
  `id_ingreso` bigint(20) UNSIGNED NOT NULL,
  `condicion_pago` bigint(20) UNSIGNED NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT NULL,
  `int_Proveedor_id` bigint(20) UNSIGNED NOT NULL,
  `nUsuCodigo` bigint(20) UNSIGNED NOT NULL,
  `local_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_documento` varchar(45) DEFAULT NULL,
  `documento_numero` varchar(45) DEFAULT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ingreso_status` varchar(45) DEFAULT NULL,
  `impuesto_ingreso` double DEFAULT NULL,
  `sub_total_ingreso` double DEFAULT NULL,
  `total_ingreso` double DEFAULT NULL,
  `total_bonificado` double DEFAULT NULL,
  `total_descuento` double DEFAULT NULL,
  `tipo_carga` varchar(10) DEFAULT 'MANUAL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`id_ingreso`, `condicion_pago`, `fecha_registro`, `int_Proveedor_id`, `nUsuCodigo`, `local_id`, `tipo_documento`, `documento_numero`, `fecha_emision`, `ingreso_status`, `impuesto_ingreso`, `sub_total_ingreso`, `total_ingreso`, `total_bonificado`, `total_descuento`, `tipo_carga`) VALUES
(1, 5, '2017-07-18 05:06:43', 2, 1, 1, NULL, '432423', '2017-07-18 05:06:43', 'COMPLETADO', 0, 12, 12, 0, 0, 'MANUAL'),
(2, 4, '2017-07-21 04:22:13', 2, 1, 1, NULL, '3123123', '2017-07-21 04:22:13', 'COMPLETADO', 0, 3, 3, 0, 0, 'MANUAL'),
(3, 5, '2017-07-21 04:22:28', 2, 1, 1, NULL, '23423', '2017-07-21 04:22:28', 'COMPLETADO', 0, 34, 34, 0, 0, 'MANUAL'),
(4, 4, '2017-08-07 00:26:31', 2, 1, 1, NULL, '423423', '2017-08-07 00:26:31', 'COMPLETADO', 0, 1, 1, 0, 0, 'MANUAL'),
(5, 4, '2017-08-07 00:28:10', 2, 1, 1, NULL, '423423', '2017-08-07 00:28:10', 'COMPLETADO', 0, 1, 1, 0, 0, 'MANUAL'),
(6, 4, '2017-08-07 00:28:45', 2, 1, 1, NULL, '423423', '2017-08-07 00:28:45', 'COMPLETADO', 0, 1, 1, 0, 0, 'MANUAL'),
(8, 4, '2017-08-07 00:30:38', 2, 1, 1, NULL, '423423', '2017-08-07 00:30:38', 'COMPLETADO', 0, 1, 1, 0, 0, 'MANUAL'),
(9, 4, '2017-08-07 00:41:38', 2, 1, 1, NULL, '424234', '2017-08-07 00:41:38', 'COMPLETADO', 0, 4, 4, 0, 0, 'MANUAL'),
(10, 4, '2017-08-07 00:45:26', 2, 1, 1, NULL, '3123123', '2017-08-07 00:45:26', 'COMPLETADO', 0, 9, 9, 0, 0, 'MANUAL'),
(11, 4, '2017-08-07 00:48:51', 2, 1, 1, NULL, '232323', '2017-08-07 00:48:51', 'COMPLETADO', 0, 3, 3, 0, 0, 'MANUAL'),
(12, 4, '2017-08-07 00:51:05', 2, 1, 1, NULL, '4234234', '2017-08-07 00:51:05', 'COMPLETADO', 0, 2, 2, 0, 0, 'MANUAL'),
(14, 4, '2017-08-07 01:11:24', 2, 1, 1, NULL, '21212', '2017-08-07 01:11:24', 'COMPLETADO', 0, 2, 2, 0, 0, 'MANUAL'),
(15, 4, '2017-08-07 01:11:48', 2, 1, 1, NULL, '2323', '2017-08-07 01:11:48', 'COMPLETADO', 0, 2, 2, 0, 0, 'MANUAL'),
(16, 4, '2017-08-07 01:12:44', 2, 1, 1, NULL, '2323', '2017-08-07 01:12:44', 'COMPLETADO', 0, 2, 2, 0, 0, 'MANUAL'),
(17, 4, '2017-08-09 01:15:43', 2, 1, 1, NULL, '234234', '2017-08-09 01:15:43', 'COMPLETADO', 0, 34, 34, 0, 0, 'MANUAL'),
(18, 4, '2017-08-09 01:16:19', 2, 1, 1, NULL, '234234', '2017-08-09 01:16:19', 'COMPLETADO', 0, 46, 46, 0, 0, 'MANUAL'),
(19, 4, '2017-08-09 01:19:43', 2, 1, 1, NULL, '234234', '2017-08-09 01:19:43', 'COMPLETADO', 0, 46, 46, 0, 0, 'MANUAL'),
(20, 4, '2017-08-10 07:18:29', 2, 1, 1, NULL, '7687', '2017-08-10 07:18:29', 'COMPLETADO', 0, 0, 0, 0, 0, 'MANUAL'),
(21, 4, '2017-08-10 07:18:58', 2, 1, 1, NULL, '687678', '2017-08-10 07:18:58', 'COMPLETADO', 0, 0, 0, 0, 0, 'MANUAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `cantidad` float NOT NULL,
  `id_local` bigint(20) UNSIGNED NOT NULL,
  `id_unidad` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `id_producto`, `cantidad`, `id_local`, `id_unidad`) VALUES
(7, 238, 708, 1, 1),
(8, 238, 0, 1, 2),
(9, 238, 0, 1, 3),
(10, 238, 23, 2, 1),
(11, 238, 23, 2, 1),
(12, 238, 23, 2, 1),
(13, 238, 23, 2, 1),
(14, 238, 23, 2, 1),
(15, 238, 23, 2, 1),
(16, 238, 23, 2, 1),
(17, 238, 23, 2, 1),
(18, 238, 23, 2, 1),
(19, 238, 23, 2, 1),
(20, 238, 23, 2, 1),
(21, 238, 23, 2, 1),
(22, 238, 23, 2, 1),
(23, 191, -3, 1, 1),
(24, 236, -1, 1, 1),
(25, 237, -1, 1, 1),
(26, 235, -1, 1, 1),
(27, 235, 0, 1, 2),
(28, 235, 0, 1, 3),
(29, 213, -6, 1, 1),
(30, 240, 99, 1, 1),
(31, 239, 99, 1, 1),
(32, 176, 97, 1, 1),
(33, 239, 7, 1, 3),
(34, 240, 5, 1, 3),
(35, 176, 0, 1, 2),
(36, 176, 6, 1, 3),
(37, 241, 97, 1, 1),
(38, 241, 9, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `nkardex_id` bigint(20) UNSIGNED NOT NULL,
  `dkardexFecha` datetime DEFAULT NULL,
  `ckardexReferencia` varchar(100) DEFAULT NULL,
  `cKardexProducto` bigint(20) UNSIGNED DEFAULT NULL,
  `nKardexCantidad` decimal(9,2) DEFAULT NULL,
  `nKardexPrecioUnitario` decimal(9,2) DEFAULT NULL,
  `nKardexPrecioTotal` decimal(9,2) DEFAULT NULL,
  `cKardexUsuario` bigint(20) UNSIGNED DEFAULT NULL,
  `cKardexUnidadMedida` bigint(20) UNSIGNED DEFAULT NULL,
  `cKardexAlmacen` bigint(20) UNSIGNED DEFAULT NULL,
  `cKardexTipo` varchar(255) DEFAULT NULL,
  `cKardexIdOperacion` bigint(20) UNSIGNED DEFAULT NULL,
  `cKardexTipoDocumento` varchar(255) DEFAULT NULL,
  `cKardexNumeroDocumento` varchar(255) DEFAULT NULL,
  `stockUManterior` text,
  `stockUMactual` text,
  `cKardexCliente` bigint(20) UNSIGNED DEFAULT NULL,
  `cKardexProveedor` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`nkardex_id`, `dkardexFecha`, `ckardexReferencia`, `cKardexProducto`, `nKardexCantidad`, `nKardexPrecioUnitario`, `nKardexPrecioTotal`, `cKardexUsuario`, `cKardexUnidadMedida`, `cKardexAlmacen`, `cKardexTipo`, `cKardexIdOperacion`, `cKardexTipoDocumento`, `cKardexNumeroDocumento`, `stockUManterior`, `stockUMactual`, `cKardexCliente`, `cKardexProveedor`) VALUES
(1, '2017-07-06 02:59:08', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '-1', 2, NULL),
(2, '2017-07-06 02:59:08', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(3, '2017-07-06 02:59:08', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(4, '2017-07-06 02:59:09', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '-1', '-2', 2, NULL),
(5, '2017-07-06 02:59:09', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(6, '2017-07-06 02:59:09', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(7, '2017-07-06 02:59:09', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '-2', '-2', 2, NULL),
(8, '2017-07-06 02:59:09', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(9, '2017-07-06 02:59:09', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 7, 'FACTURA', '0000000001', '0', '0', 2, NULL),
(10, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '-2', '-3', 2, NULL),
(11, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(12, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(13, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '-3', '-4', 2, NULL),
(14, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(15, '2017-07-06 02:59:56', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(16, '2017-07-06 02:59:56', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '-4', '-4', 2, NULL),
(17, '2017-07-06 02:59:56', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(18, '2017-07-06 02:59:56', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 8, 'FACTURA', '0000000002', '0', '0', 2, NULL),
(19, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '-4', '-5', 2, NULL),
(20, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(21, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(22, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '-5', '-6', 2, NULL),
(23, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(24, '2017-07-06 03:00:54', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(25, '2017-07-06 03:00:54', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '-6', '-6', 2, NULL),
(26, '2017-07-06 03:00:54', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(27, '2017-07-06 03:00:54', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 9, 'FACTURA', '0000000003', '0', '0', 2, NULL),
(28, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '-6', '-7', 2, NULL),
(29, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(30, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(31, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '-7', '-8', 2, NULL),
(32, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(33, '2017-07-06 03:02:28', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(34, '2017-07-06 03:02:28', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '-8', '-8', 2, NULL),
(35, '2017-07-06 03:02:28', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(36, '2017-07-06 03:02:28', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 10, 'FACTURA', '0000000004', '0', '0', 2, NULL),
(37, '2017-07-06 03:04:26', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '-8', '-9', 2, NULL),
(38, '2017-07-06 03:04:27', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(39, '2017-07-06 03:04:27', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(40, '2017-07-06 03:04:27', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '-9', '-10', 2, NULL),
(41, '2017-07-06 03:04:27', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(42, '2017-07-06 03:04:27', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(43, '2017-07-06 03:04:27', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '-10', '-10', 2, NULL),
(44, '2017-07-06 03:04:27', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(45, '2017-07-06 03:04:27', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 11, 'FACTURA', '0000000005', '0', '0', 2, NULL),
(46, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '-10', '-11', 2, NULL),
(47, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(48, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(49, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '-11', '-12', 2, NULL),
(50, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(51, '2017-07-06 03:05:38', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(52, '2017-07-06 03:05:38', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '-12', '-12', 2, NULL),
(53, '2017-07-06 03:05:38', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(54, '2017-07-06 03:05:38', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 12, 'FACTURA', '0000000006', '0', '0', 2, NULL),
(55, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '-12', '-13', 2, NULL),
(56, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(57, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(58, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '-13', '-14', 2, NULL),
(59, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(60, '2017-07-06 03:06:46', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(61, '2017-07-06 03:06:46', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '-14', '-14', 2, NULL),
(62, '2017-07-06 03:06:46', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(63, '2017-07-06 03:06:46', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 13, 'FACTURA', '0000000007', '0', '0', 2, NULL),
(64, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '-14', '-15', 2, NULL),
(65, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(66, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(67, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '-15', '-16', 2, NULL),
(68, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(69, '2017-07-06 03:08:42', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 2, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(70, '2017-07-06 03:08:42', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '-16', '-16', 2, NULL),
(71, '2017-07-06 03:08:42', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(72, '2017-07-06 03:08:42', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 14, 'FACTURA', '0000000008', '0', '0', 2, NULL),
(73, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'ENTRADA', 1, NULL, NULL, '0', '12', NULL, NULL),
(74, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 2, 2, 'ENTRADA', 1, NULL, NULL, '0', '23', NULL, NULL),
(75, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '0', '123', NULL, NULL),
(76, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'SALIDA', 1, NULL, NULL, '12', '12', NULL, NULL),
(77, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '22.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 1, NULL, NULL, '23', '22', NULL, NULL),
(78, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '12', '123', NULL, NULL),
(79, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'ENTRADA', 1, NULL, NULL, '0', '12', NULL, NULL),
(80, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 2, 2, 'ENTRADA', 1, NULL, NULL, '0', '123', NULL, NULL),
(81, '2017-07-17 23:11:03', 'REGISTRO DE FISICOS ', 238, '112.00', NULL, '0.00', 1, 3, 2, 'SALIDA', 1, NULL, NULL, '123', '112', NULL, NULL),
(82, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'SALIDA', 1, NULL, NULL, '12', '12', NULL, NULL),
(83, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 1, NULL, NULL, '112', '23', NULL, NULL),
(84, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '0', '123', NULL, NULL),
(85, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'ENTRADA', 1, NULL, NULL, '0', '12', NULL, NULL),
(86, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '22.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 1, NULL, NULL, '23', '22', NULL, NULL),
(87, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'SALIDA', 1, NULL, NULL, '123', '123', NULL, NULL),
(88, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'SALIDA', 1, NULL, NULL, '22', '12', NULL, NULL),
(89, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 2, 2, 'ENTRADA', 1, NULL, NULL, '0', '123', NULL, NULL),
(90, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '112.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '0', '112', NULL, NULL),
(91, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'SALIDA', 1, NULL, NULL, '12', '12', NULL, NULL),
(92, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 1, NULL, NULL, '123', '23', NULL, NULL),
(93, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '12', '123', NULL, NULL),
(94, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'ENTRADA', 1, NULL, NULL, '0', '12', NULL, NULL),
(95, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '22.00', NULL, '0.00', 1, 2, 2, 'ENTRADA', 1, NULL, NULL, '0', '22', NULL, NULL),
(96, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 3, 2, 'SALIDA', 1, NULL, NULL, '123', '123', NULL, NULL),
(97, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '12.00', NULL, '0.00', 1, 1, 2, 'SALIDA', 1, NULL, NULL, '12', '12', NULL, NULL),
(98, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '123.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 1, NULL, NULL, '123', '123', NULL, NULL),
(99, '2017-07-17 23:11:04', 'REGISTRO DE FISICOS ', 238, '112.00', NULL, '0.00', 1, 3, 2, 'ENTRADA', 1, NULL, NULL, '0', '112', NULL, NULL),
(100, '2017-07-17 23:12:54', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 1, 1, 'ENTRADA', 2, NULL, NULL, '-16', '23', NULL, NULL),
(101, '2017-07-17 23:12:55', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 2, 1, 'ENTRADA', 2, NULL, NULL, '0', '23', NULL, NULL),
(102, '2017-07-17 23:12:55', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 3, 1, 'ENTRADA', 2, NULL, NULL, '0', '23', NULL, NULL),
(103, '2017-07-17 23:13:59', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 1, 2, 'ENTRADA', 3, NULL, NULL, '0', '23', NULL, NULL),
(104, '2017-07-17 23:13:59', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 2, 2, 'SALIDA', 3, NULL, NULL, '123', '23', NULL, NULL),
(105, '2017-07-17 23:13:59', 'REGISTRO DE FISICOS ', 238, '23.00', NULL, '0.00', 1, 3, 2, 'SALIDA', 3, NULL, NULL, '112', '23', NULL, NULL),
(106, '2017-07-17 23:17:32', 'ENTRADA POR AJUSTE DE INVENTARIO', 238, '1.00', '12.00', '12.00', 1, 1, 1, 'ENTRADA', 4, NULL, NULL, '23', '1', NULL, NULL),
(107, '2017-07-17 23:17:32', 'ENTRADA POR AJUSTE DE INVENTARIO', 238, '12.00', '12.00', '144.00', 1, 2, 1, 'ENTRADA', 4, NULL, NULL, '1', '12', NULL, NULL),
(108, '2017-07-17 23:17:32', 'ENTRADA POR AJUSTE DE INVENTARIO', 238, '12.00', '12.00', '144.00', 1, 3, 1, 'ENTRADA', 4, NULL, NULL, '3', '12', NULL, NULL),
(109, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(110, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(111, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(112, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(113, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(114, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(115, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(116, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(117, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(118, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(119, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(120, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(121, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(122, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(123, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(124, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(125, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(126, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(127, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(128, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(129, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(130, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(131, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(132, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(133, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(134, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(135, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(136, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(137, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(138, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(139, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(140, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(141, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(142, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(143, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(144, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(145, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(146, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(147, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(148, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(149, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(150, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(151, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '45', 2, NULL),
(152, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(153, '2017-07-17 23:26:11', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '0', 2, NULL),
(154, '2017-07-17 23:26:11', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '45', '44', 2, NULL),
(155, '2017-07-17 23:26:11', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '1', 2, NULL),
(156, '2017-07-17 23:26:11', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 15, 'FACTURA', '0000000009', '0', '4', 2, NULL),
(157, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '44', '43', 2, NULL),
(158, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(159, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(160, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '43', '42', 2, NULL),
(161, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(162, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(163, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '42', '41', 2, NULL),
(164, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(165, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(166, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '41', '40', 2, NULL),
(167, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(168, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(169, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '40', '39', 2, NULL),
(170, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(171, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(172, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '39', '38', 2, NULL),
(173, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(174, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(175, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '38', '37', 2, NULL),
(176, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(177, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(178, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '37', '36', 2, NULL),
(179, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(180, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(181, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '36', '35', 2, NULL),
(182, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(183, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(184, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '35', '34', 2, NULL),
(185, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(186, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(187, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '34', '33', 2, NULL),
(188, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(189, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(190, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '33', '32', 2, NULL),
(191, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(192, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(193, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '32', '31', 2, NULL),
(194, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(195, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(196, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '31', '30', 2, NULL),
(197, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(198, '2017-07-17 23:28:07', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(199, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '30', '30', 2, NULL),
(200, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(201, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(202, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '30', '30', 2, NULL),
(203, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '1', '1', 2, NULL),
(204, '2017-07-17 23:28:07', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 16, 'FACTURA', '0000000010', '4', '4', 2, NULL),
(205, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '30', '29', 2, NULL),
(206, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(207, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(208, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '29', '28', 2, NULL),
(209, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(210, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(211, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '28', '27', 2, NULL),
(212, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(213, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(214, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '27', '26', 2, NULL),
(215, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(216, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(217, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '26', '25', 2, NULL),
(218, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(219, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(220, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '25', '24', 2, NULL),
(221, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(222, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(223, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '24', '23', 2, NULL),
(224, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(225, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(226, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '23', '22', 2, NULL),
(227, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(228, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(229, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '22', '21', 2, NULL),
(230, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(231, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(232, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '21', '20', 2, NULL),
(233, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(234, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(235, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '20', '19', 2, NULL),
(236, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(237, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(238, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '19', '18', 2, NULL),
(239, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(240, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(241, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '18', '17', 2, NULL),
(242, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(243, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(244, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '17', '16', 2, NULL),
(245, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(246, '2017-07-17 23:31:00', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(247, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '16', '16', 2, NULL),
(248, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(249, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(250, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '16', '16', 2, NULL),
(251, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '1', '1', 2, NULL),
(252, '2017-07-17 23:31:00', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 17, 'FACTURA', '0000000011', '4', '4', 2, NULL),
(253, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(254, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(255, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(256, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(257, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(258, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(259, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(260, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(261, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(262, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(263, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(264, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(265, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(266, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(267, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(268, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(269, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(270, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(271, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(272, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(273, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(274, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(275, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(276, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(277, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(278, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(279, '2017-07-17 23:32:12', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(280, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(281, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(282, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(283, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(284, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(285, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(286, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(287, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(288, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(289, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(290, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(291, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(292, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(293, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(294, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(295, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(296, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(297, '2017-07-17 23:32:13', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '4', 2, NULL),
(298, '2017-07-17 23:32:13', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '16', '16', 2, NULL),
(299, '2017-07-17 23:32:13', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '1', '1', 2, NULL),
(300, '2017-07-17 23:32:13', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 18, 'FACTURA', '0000000012', '4', '3', 2, NULL),
(301, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(302, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(303, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(304, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(305, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(306, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(307, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(308, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(309, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(310, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(311, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(312, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(313, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(314, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(315, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(316, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(317, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(318, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(319, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(320, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '1', '1', NULL, NULL),
(321, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '3', '3', NULL, NULL),
(322, '2017-07-19 00:21:44', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 19, 'FACTURA', '0000000013', '29', '29', NULL, NULL),
(792, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"795"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":794},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(793, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"794"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":793},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(794, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"793"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":792},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(795, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"792"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":791},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(796, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"791"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":790},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL);
INSERT INTO `kardex` (`nkardex_id`, `dkardexFecha`, `ckardexReferencia`, `cKardexProducto`, `nKardexCantidad`, `nKardexPrecioUnitario`, `nKardexPrecioTotal`, `cKardexUsuario`, `cKardexUnidadMedida`, `cKardexAlmacen`, `cKardexTipo`, `cKardexIdOperacion`, `cKardexTipoDocumento`, `cKardexNumeroDocumento`, `stockUManterior`, `stockUMactual`, `cKardexCliente`, `cKardexProveedor`) VALUES
(797, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"790"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":789},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(798, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"789"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":788},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(799, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"788"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":787},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(800, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"787"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":786},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(801, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"786"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":785},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(802, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"785"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":784},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(803, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"784"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":783},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(804, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"783"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":782},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(805, '2017-08-06 20:20:14', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"782"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":781},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(806, '2017-08-06 20:20:14', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"781"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":781},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(807, '2017-08-06 20:20:14', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 43, 'FACTURA', '0000000037', '{"1":{"nombre":"CAJA","cantidad":"781"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":781},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(808, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"781"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":780},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(809, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"780"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":779},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(810, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"779"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":778},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(811, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"778"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":777},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(812, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"777"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":776},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(813, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"776"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":775},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(814, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"775"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":774},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(815, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"774"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":773},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(816, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"773"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":772},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(817, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"772"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":771},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(818, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"771"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":770},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(819, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"770"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":769},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(820, '2017-08-07 18:07:48', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"769"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":768},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(821, '2017-08-07 18:07:49', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"768"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":767},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(822, '2017-08-07 18:07:49', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"767"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":767},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(823, '2017-08-07 18:07:49', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 44, 'FACTURA', '0000000038', '{"1":{"nombre":"CAJA","cantidad":"767"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":767},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(824, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"767"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":766},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(825, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"766"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":765},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(826, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"765"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":764},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(827, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"764"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":763},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(828, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"763"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":762},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(829, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"762"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":761},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(830, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"761"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":760},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(831, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"760"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":759},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(832, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"759"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":758},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(833, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"758"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":757},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(834, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"757"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":756},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(835, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"756"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":755},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(836, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"755"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":754},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(837, '2017-08-07 18:08:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"754"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":753},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(838, '2017-08-07 18:08:55', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"753"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":753},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(839, '2017-08-07 18:08:55', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 45, 'FACTURA', '0000000039', '{"1":{"nombre":"CAJA","cantidad":"753"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":753},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(840, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"753"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":752},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(841, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"752"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":751},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(842, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"751"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":750},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(843, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"750"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":749},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(844, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"749"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":748},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(845, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"748"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":747},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(846, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"747"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":746},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(847, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"746"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":745},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(848, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"745"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":744},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(849, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"744"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":743},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(850, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"743"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":742},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(851, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"742"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(852, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":740},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(853, '2017-08-07 18:14:15', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"740"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":739},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(854, '2017-08-07 18:14:15', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"739"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":739},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(855, '2017-08-07 18:14:15', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 46, 'FACTURA', '0000000040', '{"1":{"nombre":"CAJA","cantidad":"739"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":739},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(856, '2017-08-08 01:16:31', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 47, 'FACTURA', '0000000041', '{"1":{"nombre":"CAJA","cantidad":"739"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":738},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(857, '2017-08-08 01:16:31', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 47, 'FACTURA', '0000000041', '{"1":{"nombre":"CAJA","cantidad":"738"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":738},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(858, '2017-08-08 01:16:31', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 47, 'FACTURA', '0000000041', '{"1":{"nombre":"CAJA","cantidad":"738"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":738},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(859, '2017-08-08 01:16:36', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 48, 'FACTURA', '0000000042', '{"1":{"nombre":"CAJA","cantidad":"738"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(860, '2017-08-08 01:16:36', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 48, 'FACTURA', '0000000042', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(861, '2017-08-08 01:16:36', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 48, 'FACTURA', '0000000042', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(862, '2017-08-08 20:03:55', 'Venta al contado', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 49, 'FACTURA', '0000000043', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(863, '2017-08-08 20:03:55', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 49, 'FACTURA', '0000000043', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(864, '2017-08-08 20:03:55', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 49, 'FACTURA', '0000000043', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(865, '2017-08-08 20:05:30', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 50, 'FACTURA', '0000000044', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(866, '2017-08-08 20:05:30', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 2, 1, 'SALIDA', 50, 'FACTURA', '0000000044', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(867, '2017-08-08 20:05:30', 'Venta al contado', 238, '6.00', '375.00', '2250.00', 3, 3, 1, 'SALIDA', 50, 'FACTURA', '0000000044', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(868, '2017-08-08 20:08:11', 'Venta al contado', 236, '1.00', '0.00', '0.00', 3, 1, 1, 'SALIDA', 51, 'FACTURA', '0000000045', '{"1":{"nombre":"CAJA","cantidad":"-1"}}', '{"1":{"nombre":"CAJA","cantidad":0}}', 6, NULL),
(869, '2017-08-08 20:08:12', 'Venta al contado', 237, '1.00', '0.00', '0.00', 3, 1, 1, 'SALIDA', 51, 'FACTURA', '0000000045', '{"1":{"nombre":"CAJA","cantidad":"-1"}}', '{"1":{"nombre":"CAJA","cantidad":0}}', 6, NULL),
(870, '2017-08-08 20:08:12', 'Venta al contado', 238, '1.00', '0.00', '0.00', 3, 3, 1, 'SALIDA', 51, 'FACTURA', '0000000045', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(871, '2017-08-08 20:13:08', 'Venta al contado', 238, '0.00', '3753.00', '0.00', 3, 1, 1, 'SALIDA', 52, 'FACTURA', '0000000046', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(872, '2017-08-08 20:13:08', 'Venta al contado', 238, '5.00', '3753.00', '18765.00', 3, 2, 1, 'SALIDA', 52, 'FACTURA', '0000000046', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":733},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(873, '2017-08-08 20:13:08', 'Venta al contado', 238, '0.00', '375.00', '0.00', 3, 3, 1, 'SALIDA', 52, 'FACTURA', '0000000046', '{"1":{"nombre":"CAJA","cantidad":"733"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":733},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(874, '2017-08-08 20:15:12', 'Venta al contado', 238, '6.00', '3753.00', '22518.00', 3, 1, 1, 'SALIDA', 53, 'FACTURA', '0000000047', '{"1":{"nombre":"CAJA","cantidad":"733"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":727},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(875, '2017-08-08 20:15:43', 'INGRESO', 238, '0.00', '0.00', '0.00', 1, 1, 1, 'ENTRADA', 17, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"727"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":727},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(876, '2017-08-08 20:15:43', 'INGRESO', 238, '8.00', '4.25', '34.00', 1, 2, 1, 'ENTRADA', 17, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"727"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":731},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(877, '2017-08-08 20:15:43', 'INGRESO', 238, '0.00', '0.00', '0.00', 1, 3, 1, 'ENTRADA', 17, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"731"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":731},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(878, '2017-08-08 20:16:19', 'INGRESO', 238, '1.00', '12.00', '12.00', 1, 1, 1, 'ENTRADA', 18, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"731"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":732},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(879, '2017-08-08 20:16:19', 'INGRESO', 238, '8.00', '4.25', '34.00', 1, 2, 1, 'ENTRADA', 18, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"732"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(880, '2017-08-08 20:16:19', 'INGRESO', 238, '0.00', '0.00', '0.00', 1, 3, 1, 'ENTRADA', 18, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(881, '2017-08-08 20:19:43', 'COMPRA A CONTADO', 238, '1.00', '12.00', '12.00', 1, 1, 1, 'ENTRADA', 19, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(882, '2017-08-08 20:19:43', 'COMPRA A CONTADO', 238, '8.00', '4.25', '34.00', 1, 2, 1, 'ENTRADA', 19, 'FACTURA', '234234', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(883, '2017-08-08 20:30:00', 'Venta al contado', 238, '1.00', '375.00', '375.00', 3, 3, 1, 'SALIDA', 54, 'FACTURA', '0000000048', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(884, '2017-08-10 00:00:53', 'Venta al contado', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 55, 'FACTURA', '0000000049', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":739},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(885, '2017-08-10 00:01:28', 'Venta al contado', 238, '2.00', '3753.00', '7506.00', 3, 2, 1, 'SALIDA', 56, 'FACTURA', '0000000050', '{"1":{"nombre":"CAJA","cantidad":"739"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":738},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(888, '2017-08-10 00:06:58', 'VENTA ANULADA', 238, '2.00', '3753.00', '7506.00', 1, 2, 1, '{"1":{"nombre":"CAJA","cantidad":"738"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', 56, '6', '0000000050', '{"1":{"nombre":"CAJA","cantidad":739},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 0, NULL),
(890, '2017-08-10 00:07:38', 'VENTA ANULADA', 238, '2.00', '3753.00', '7506.00', 1, 1, 1, '{"1":{"nombre":"CAJA","cantidad":"739"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', 55, '6', '0000000049', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 0, NULL),
(891, '2017-08-10 00:14:05', 'Venta al contado', 238, '5.00', '3753.00', '18765.00', 3, 1, 1, 'SALIDA', 57, 'FACTURA', '0000000051', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(892, '2017-08-10 00:14:24', 'VENTA ANULADA', 238, '5.00', '3753.00', '18765.00', 1, 1, 1, 'ENTRADA', 57, 'FACTURA', '0000000051', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(893, '2017-08-10 01:00:45', 'VENTA A CONTADO', 238, '3.00', '3787.00', '11361.00', 3, 1, 1, 'SALIDA', 58, 'FACTURA', '0000000052', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":738},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(894, '2017-08-10 01:04:14', 'VENTA A CONTADO', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 59, 'FACTURA', '0000000053', '{"1":{"nombre":"CAJA","cantidad":"738"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(895, '2017-08-10 01:04:14', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 2, 1, 'SALIDA', 59, 'FACTURA', '0000000053', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":736},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(896, '2017-08-10 02:18:29', 'COMPRA A CONTADO', 238, '1.00', '0.00', '0.00', 1, 1, 1, 'ENTRADA', 20, 'FACTURA', '7687', '{"1":{"nombre":"CAJA","cantidad":"736"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(897, '2017-08-10 02:18:58', 'COMPRA A CONTADO', 238, '5.00', '0.00', '0.00', 1, 1, 1, 'ENTRADA', 21, 'FACTURA', '687678', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":742},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, 2),
(898, '2017-08-11 23:43:08', 'VENTA A CONTADO', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 60, 'FACTURA', '0000000054', '{"1":{"nombre":"CAJA","cantidad":"742"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, NULL),
(899, '2017-08-11 23:47:09', 'VENTA A CONTADO', 238, '1.00', '3753.00', '3753.00', 3, 1, 1, 'SALIDA', 61, 'FACTURA', '0000000055', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":740},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, NULL),
(906, '2017-08-12 17:50:50', 'ENTRADA POR DEVOLUCION DE VENTA', 238, '1.00', '3753.00', '3753.00', 1, 1, 1, 'ENTRADA', 60, 'FACTURA', '0000000054', '{"1":{"nombre":"CAJA","cantidad":"740"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":741},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, NULL),
(907, '2017-08-12 17:51:53', 'ENTRADA POR DEVOLUCION DE VENTA', 238, '1.00', '3753.00', '3753.00', 1, 1, 1, 'ENTRADA', 61, 'FACTURA', '0000000055', '{"1":{"nombre":"CAJA","cantidad":"741"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":742},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', NULL, NULL),
(909, '2017-08-12 22:52:50', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 64, 'FACTURA', '0000000056', '{"1":{"nombre":"CAJA","cantidad":"750"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":748},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(910, '2017-08-12 23:01:10', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 65, 'FACTURA', '0000000057', '{"1":{"nombre":"CAJA","cantidad":"748"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":746},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(911, '2017-08-12 23:02:23', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 66, 'FACTURA', '0000000058', '{"1":{"nombre":"CAJA","cantidad":"746"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":744},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(912, '2017-08-12 23:06:52', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 67, 'FACTURA', '0000000059', '{"1":{"nombre":"CAJA","cantidad":"744"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":742},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(913, '2017-08-12 23:55:38', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 68, 'FACTURA', '0000000060', '{"1":{"nombre":"CAJA","cantidad":"742"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":740},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(914, '2017-08-13 00:01:04', 'VENTA A CONTADO', 238, '3.00', '3787.00', '11361.00', 3, 1, 1, 'SALIDA', 69, 'FACTURA', '0000000061', '{"1":{"nombre":"CAJA","cantidad":"740"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":737},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(915, '2017-08-13 00:02:04', 'VENTA A CONTADO', 238, '2.00', '3753.00', '7506.00', 3, 1, 1, 'SALIDA', 70, 'FACTURA', '0000000062', '{"1":{"nombre":"CAJA","cantidad":"737"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":735},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(916, '2017-08-13 00:06:58', 'VENTA A CONTADO', 238, '2.00', '3787.00', '7574.00', 3, 1, 1, 'SALIDA', 71, 'FACTURA', '0000000063', '{"1":{"nombre":"CAJA","cantidad":"735"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":733},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(917, '2017-08-13 00:11:58', 'VENTA A CONTADO', 238, '22.00', '3787.00', '83314.00', 3, 1, 1, 'SALIDA', 72, 'FACTURA', '0000000064', '{"1":{"nombre":"CAJA","cantidad":"733"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":711},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(918, '2017-08-13 00:11:58', 'VENTA A CONTADO', 238, '2.00', '3787.00', '7574.00', 3, 2, 1, 'SALIDA', 72, 'FACTURA', '0000000064', '{"1":{"nombre":"CAJA","cantidad":"711"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":710},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(919, '2017-08-15 23:34:31', 'VENTA A CONTADO', 235, '1.00', '95951.01', '95951.01', 3, 1, 1, 'SALIDA', 73, 'FACTURA', '0000000065', '{"1":{"nombre":"CAJA","cantidad":0},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":0}}', '{"1":{"nombre":"CAJA","cantidad":"-1"},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(920, '2017-08-15 23:34:31', 'VENTA A CONTADO', 238, '1.00', '10.00', '10.00', 3, 1, 1, 'SALIDA', 73, 'FACTURA', '0000000065', '{"1":{"nombre":"CAJA","cantidad":"710"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":709},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(921, '2017-08-16 16:26:53', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 74, 'FACTURA', '0000000066', '{"1":{"nombre":"CAJA","cantidad":0}}', '{"1":{"nombre":"CAJA","cantidad":"-1"}}', 6, NULL),
(922, '2017-08-16 16:28:39', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 75, 'FACTURA', '0000000067', '{"1":{"nombre":"CAJA","cantidad":"-1"}}', '{"1":{"nombre":"CAJA","cantidad":"-2"}}', 6, NULL),
(923, '2017-08-16 16:29:48', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 76, 'FACTURA', '0000000068', '{"1":{"nombre":"CAJA","cantidad":"-2"}}', '{"1":{"nombre":"CAJA","cantidad":"-3"}}', 6, NULL),
(924, '2017-08-16 16:32:02', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 77, 'FACTURA', '0000000069', '{"1":{"nombre":"CAJA","cantidad":"-3"}}', '{"1":{"nombre":"CAJA","cantidad":"-4"}}', 6, NULL),
(925, '2017-08-16 16:34:33', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 78, 'FACTURA', '0000000070', '{"1":{"nombre":"CAJA","cantidad":"-4"}}', '{"1":{"nombre":"CAJA","cantidad":"-5"}}', 6, NULL),
(926, '2017-08-16 16:55:46', 'VENTA A CONTADO', 213, '1.00', '10900.00', '10900.00', 3, 1, 1, 'SALIDA', 79, 'FACTURA', '0000000071', '{"1":{"nombre":"CAJA","cantidad":"-5"}}', '{"1":{"nombre":"CAJA","cantidad":"-6"}}', 6, NULL),
(927, '2017-08-17 22:36:23', 'REGISTRO DE FISICOS ', 240, '100.00', NULL, '0.00', 1, 1, 1, 'EMITIDO', 7, NULL, NULL, 'ENTRADA', '0', NULL, 100),
(928, '2017-08-17 22:36:23', 'REGISTRO DE FISICOS ', 239, '100.00', NULL, '0.00', 1, 1, 1, 'EMITIDO', 7, NULL, NULL, 'ENTRADA', '0', NULL, 100),
(929, '2017-08-17 22:36:39', 'REGISTRO DE FISICOS ', 176, '100.00', NULL, '0.00', 1, 1, 1, 'EMITIDO', 8, NULL, NULL, 'ENTRADA', '0', NULL, 100),
(930, '2017-08-18 16:06:44', 'VENTA A CONTADO', 239, '1.00', '4700.00', '4700.00', 3, 3, 1, 'SALIDA', 80, 'FACTURA', '0000000072', '{"1":{"nombre":"CAJA","cantidad":"100"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(931, '2017-08-18 16:06:44', 'VENTA A CONTADO', 240, '1.00', '9900.00', '9900.00', 3, 3, 1, 'SALIDA', 80, 'FACTURA', '0000000072', '{"1":{"nombre":"CAJA","cantidad":"100"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(932, '2017-08-18 16:06:44', 'VENTA A CONTADO', 176, '2.00', '500.00', '1000.00', 3, 3, 1, 'SALIDA', 80, 'FACTURA', '0000000072', '{"1":{"nombre":"CAJA","cantidad":"100"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":8}}', 6, NULL),
(933, '2017-08-18 16:44:04', 'REGISTRO DE FISICOS ', 241, '100.00', NULL, '0.00', 1, 1, 1, 'EMITIDO', 9, NULL, NULL, 'ENTRADA', '0', NULL, 100),
(934, '2017-08-19 23:00:15', 'VENTA A CONTADO', 176, '2.00', '500.00', '1000.00', 3, 3, 1, 'SALIDA', 81, 'FACTURA', '0000000073', '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"8"}}', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":6}}', 6, NULL),
(935, '2017-08-19 23:00:16', 'VENTA A CONTADO', 240, '1.00', '9900.00', '9900.00', 3, 3, 1, 'SALIDA', 81, 'FACTURA', '0000000073', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":8}}', 6, NULL),
(936, '2017-08-19 23:00:16', 'VENTA A CONTADO', 239, '1.00', '4700.00', '4700.00', 3, 3, 1, 'SALIDA', 81, 'FACTURA', '0000000073', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":8}}', 6, NULL),
(937, '2017-08-19 23:05:19', 'VENTA A CONTADO', 176, '2.00', '500.00', '1000.00', 3, 3, 1, 'SALIDA', 82, 'FACTURA', '0000000074', '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"6"}}', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":4}}', 6, NULL),
(938, '2017-08-19 23:05:19', 'VENTA A CONTADO', 239, '1.00', '4700.00', '4700.00', 3, 3, 1, 'SALIDA', 82, 'FACTURA', '0000000074', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"8"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":7}}', 6, NULL),
(939, '2017-08-19 23:05:19', 'VENTA A CONTADO', 240, '1.00', '9900.00', '9900.00', 3, 3, 1, 'SALIDA', 82, 'FACTURA', '0000000074', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"8"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":7}}', 6, NULL),
(940, '2017-08-19 23:24:24', 'VENTA A CONTADO', 240, '1.00', '9900.00', '9900.00', 3, 3, 1, 'SALIDA', 83, 'FACTURA', '0000000075', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"7"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":6}}', 6, NULL),
(941, '2017-08-19 23:24:24', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 83, 'FACTURA', '0000000075', '{"1":{"nombre":"CAJA","cantidad":"100"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(942, '2017-08-19 23:34:47', 'VENTA A CONTADO', 240, '1.00', '9900.00', '9900.00', 3, 3, 1, 'SALIDA', 85, 'FACTURA', '0000000076', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"6"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":5}}', 6, NULL),
(943, '2017-08-19 23:34:48', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 85, 'FACTURA', '0000000076', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":8}}', 6, NULL),
(944, '2017-08-21 21:54:30', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"4"}}', 86, 'FACTURA', '0000000077', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":3}}', NULL, 6, NULL),
(945, '2017-08-21 21:54:30', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"8"}}', 86, 'FACTURA', '0000000077', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":7}}', NULL, 6, NULL);
INSERT INTO `kardex` (`nkardex_id`, `dkardexFecha`, `ckardexReferencia`, `cKardexProducto`, `nKardexCantidad`, `nKardexPrecioUnitario`, `nKardexPrecioTotal`, `cKardexUsuario`, `cKardexUnidadMedida`, `cKardexAlmacen`, `cKardexTipo`, `cKardexIdOperacion`, `cKardexTipoDocumento`, `cKardexNumeroDocumento`, `stockUManterior`, `stockUMactual`, `cKardexCliente`, `cKardexProveedor`) VALUES
(946, '2017-08-21 21:55:17', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, 'SALIDA', 87, 'FACTURA', '0000000078', '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"3"}}', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":2}}', 6, NULL),
(947, '2017-08-21 21:55:17', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 87, 'FACTURA', '0000000078', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"7"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":6}}', 6, NULL),
(948, '2017-08-21 21:57:22', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, 'SALIDA', 88, 'FACTURA', '0000000079', '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"2"}}', '{"1":{"nombre":"CAJA","cantidad":99},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(949, '2017-08-21 21:57:22', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 88, 'FACTURA', '0000000079', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"6"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":5}}', 6, NULL),
(950, '2017-08-21 21:59:23', 'VENTA A CONTADO', 241, '2.00', '10900.00', '21800.00', 3, 3, 1, 'SALIDA', 89, 'FACTURA', '0000000080', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"5"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":3}}', 6, NULL),
(951, '2017-08-21 22:01:15', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 90, 'FACTURA', '0000000081', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"3"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":2}}', 6, NULL),
(952, '2017-08-21 22:01:46', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 91, 'FACTURA', '0000000082', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"2"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(953, '2017-08-21 22:04:24', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 92, 'FACTURA', '0000000083', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":99},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(954, '2017-08-21 22:06:54', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 93, 'FACTURA', '0000000084', '{"1":{"nombre":"CAJA","cantidad":"99"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(955, '2017-08-21 22:25:23', 'VENTA A CONTADO', 238, '1.00', '10.00', '10.00', 3, 1, 1, 'SALIDA', 94, 'FACTURA', '0000000085', '{"1":{"nombre":"CAJA","cantidad":"709"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(956, '2017-08-22 22:49:38', 'VENTA A CONTADO', 238, '1.00', '0.00', '0.00', 3, 3, 1, 'SALIDA', 95, 'FACTURA', '0000000086', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":1},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(957, '2017-08-22 22:49:45', 'VENTA A CONTADO', 238, '1.00', '0.00', '0.00', 3, 3, 1, 'SALIDA', 96, 'FACTURA', '0000000087', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"1"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":4}}', 6, NULL),
(958, '2017-08-22 22:53:54', 'VENTA A CONTADO', 238, '1.00', '0.00', '0.00', 3, 3, 1, 'SALIDA', 97, 'FACTURA', '0000000088', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"4"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":3}}', 6, NULL),
(959, '2017-08-22 23:00:29', 'VENTA A CONTADO', 238, '1.00', '112454.35', '112454.35', 3, 3, 1, 'SALIDA', 98, 'FACTURA', '0000000089', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"3"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":2}}', 6, NULL),
(960, '2017-08-22 23:44:41', 'VENTA A CONTADO', 238, '1.00', NULL, '0.00', 3, 3, 1, 'SALIDA', 99, 'FACTURA', '0000000090', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"2"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(961, '2017-08-22 23:45:55', 'VENTA A CONTADO', 238, '1.00', '112454.35', '112454.35', 3, 3, 1, 'SALIDA', 100, 'FACTURA', '0000000091', '{"1":{"nombre":"CAJA","cantidad":"708"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":708},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(962, '2017-08-22 23:57:57', 'VENTA A CONTADO', 176, '2.00', '500.00', '1000.00', 3, 3, 1, 'SALIDA', 101, 'FACTURA', '0000000092', '{"1":{"nombre":"CAJA","cantidad":"99"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":98},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(963, '2017-08-23 00:00:31', 'VENTA A CONTADO', 176, '2.00', '500.00', '1000.00', 3, 3, 1, 'SALIDA', 102, 'FACTURA', '0000000093', '{"1":{"nombre":"CAJA","cantidad":"98"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":98},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":7}}', 6, NULL),
(964, '2017-08-23 00:04:26', 'ENTRADA POR DEVOLUCION DE VENTA', 176, '2.00', '500.00', '1000.00', 1, 3, 1, 'ENTRADA', 102, 'FACTURA', '0000000093', '{"1":{"nombre":"CAJA","cantidad":"98"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"7"}}', '{"1":{"nombre":"CAJA","cantidad":98},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(965, '2017-08-25 20:47:26', 'VENTA A CONTADO', 176, '1.00', '0.00', '0.00', 3, 1, 1, 'SALIDA', 103, 'FACTURA', '11', '{"1":{"nombre":"CAJA","cantidad":"98"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":97},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL),
(966, '2017-08-25 20:47:26', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, 'SALIDA', 103, 'FACTURA', '11', '{"1":{"nombre":"CAJA","cantidad":"97"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":97},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":8}}', 6, NULL),
(967, '2017-08-25 20:47:46', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, 'SALIDA', 104, 'FACTURA', '12', '{"1":{"nombre":"CAJA","cantidad":"97"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"8"}}', '{"1":{"nombre":"CAJA","cantidad":97},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":7}}', 6, NULL),
(968, '2017-08-25 20:48:00', 'VENTA A CONTADO', 176, '1.00', '500.00', '500.00', 3, 3, 1, 'SALIDA', 105, 'FACTURA', '13', '{"1":{"nombre":"CAJA","cantidad":"97"},"2":{"nombre":"BLISTER","cantidad":"0"},"3":{"nombre":"UNIDAD","cantidad":"7"}}', '{"1":{"nombre":"CAJA","cantidad":97},"2":{"nombre":"BLISTER","cantidad":0},"3":{"nombre":"UNIDAD","cantidad":6}}', 6, NULL),
(969, '2017-08-25 20:49:19', 'VENTA A CONTADO', 241, '2.00', '10900.00', '21800.00', 3, 3, 1, 'SALIDA', 106, 'FACTURA', '14', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"9"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":7}}', 6, NULL),
(970, '2017-08-25 20:49:29', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 107, 'FACTURA', '15', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"7"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":6}}', 6, NULL),
(971, '2017-08-25 20:50:59', 'VENTA A CONTADO', 241, '2.00', '10900.00', '21800.00', 3, 3, 1, 'SALIDA', 108, 'FACTURA', '16', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"6"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":4}}', 6, NULL),
(972, '2017-08-25 20:51:13', 'VENTA A CONTADO', 241, '2.00', '10900.00', '21800.00', 3, 3, 1, 'SALIDA', 109, 'FACTURA', '17', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"4"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":2}}', 6, NULL),
(973, '2017-08-26 15:58:32', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 110, 'FACTURA', '1', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"2"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":1}}', 6, NULL),
(974, '2017-08-26 15:59:57', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 111, 'FACTURA', '2', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"1"}}', '{"1":{"nombre":"CAJA","cantidad":98},"3":{"nombre":"UNIDAD","cantidad":0}}', 6, NULL),
(975, '2017-08-26 16:01:18', 'VENTA A CONTADO', 241, '1.00', '10900.00', '10900.00', 3, 3, 1, 'SALIDA', 112, 'FACTURA', '3', '{"1":{"nombre":"CAJA","cantidad":"98"},"3":{"nombre":"UNIDAD","cantidad":"0"}}', '{"1":{"nombre":"CAJA","cantidad":97},"3":{"nombre":"UNIDAD","cantidad":9}}', 6, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `keys`
--

CREATE TABLE `keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(40) DEFAULT NULL,
  `level` int(2) DEFAULT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`) VALUES
(1, 1, 'VVpW1BaB6iLdP3juUjrxParxbWVur4kateiRYzEe', 0, 0, 0, 0),
(2, 2, '0q9atEvzwNdz7SzGrDemcK2QXuwuqHYkGiOpeVYQ', 0, 0, 0, 0),
(3, 3, 'D3y6QSpUawBiUd9UtYPjXhOUppscY1DoIy61JdlL', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas`
--

CREATE TABLE `lineas` (
  `id_linea` bigint(20) UNSIGNED NOT NULL,
  `nombre_linea` varchar(255) DEFAULT NULL,
  `estatus_linea` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local`
--

CREATE TABLE `local` (
  `int_local_id` bigint(20) UNSIGNED NOT NULL,
  `local_nombre` varchar(255) DEFAULT NULL,
  `local_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `local`
--

INSERT INTO `local` (`int_local_id`, `local_nombre`, `local_status`) VALUES
(1, 'Drogueria Principal', 1),
(2, 'Bodega', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` tinyint(1) NOT NULL,
  `response_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id_metodo` bigint(20) UNSIGNED NOT NULL,
  `nombre_metodo` varchar(255) DEFAULT NULL,
  `incluye_cuadre_caja` tinyint(1) DEFAULT NULL,
  `suma_total_ingreso` tinyint(1) DEFAULT NULL,
  `centros_bancos` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id_metodo`, `nombre_metodo`, `incluye_cuadre_caja`, `suma_total_ingreso`, `centros_bancos`, `deleted_at`) VALUES
(1, 'EFECTIVO', 1, 1, 0, NULL),
(2, 'TARJETA DEBITO', 1, 0, 0, NULL),
(3, 'TARJETA DE CREDITO', 1, 0, 1, NULL),
(4, 'DEPOSITO BANCARIO', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(20170825180512);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcion`
--

CREATE TABLE `opcion` (
  `nOpcion` bigint(20) UNSIGNED NOT NULL,
  `nOpcionClase` bigint(20) UNSIGNED NOT NULL,
  `cOpcionNombre` varchar(255) DEFAULT NULL,
  `cOpcionDescripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `opcion`
--

INSERT INTO `opcion` (`nOpcion`, `nOpcionClase`, `cOpcionNombre`, `cOpcionDescripcion`) VALUES
(1, 0, 'parametrizacion', 'Parametrización'),
(2, 0, 'ventas', 'Ventas'),
(3, 0, 'inventario', 'Inventario'),
(4, 0, 'compras', 'Compras'),
(5, 0, 'cartera', 'Cartera'),
(6, 0, 'cuentasporpagar', 'Cuentas por pagar'),
(7, 0, 'reportes', 'Reportes'),
(8, 0, 'utilidad', 'Utilidad'),
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcion_grupo`
--

CREATE TABLE `opcion_grupo` (
  `grupo` bigint(20) UNSIGNED NOT NULL,
  `Opcion` bigint(20) UNSIGNED NOT NULL,
  `var_opcion_usuario_estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `opcion_grupo`
--

INSERT INTO `opcion_grupo` (`grupo`, `Opcion`, `var_opcion_usuario_estado`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1),
(1, 5, 1),
(1, 6, 1),
(1, 7, 1),
(1, 8, 1),
(1, 9, 1),
(1, 10, 1),
(1, 11, 1),
(1, 12, 1),
(1, 13, 1),
(1, 14, 1),
(1, 15, 1),
(1, 16, 1),
(1, 17, 1),
(1, 18, 1),
(1, 19, 1),
(1, 20, 1),
(1, 21, 1),
(1, 22, 1),
(1, 23, 1),
(1, 24, 1),
(1, 25, 1),
(1, 26, 1),
(1, 27, 1),
(1, 28, 1),
(1, 29, 1),
(1, 30, 1),
(1, 31, 1),
(1, 32, 1),
(1, 33, 1),
(1, 34, 1),
(1, 35, 1),
(1, 36, 1),
(1, 37, 1),
(1, 38, 1),
(1, 39, 1),
(1, 40, 1),
(1, 41, 1),
(1, 42, 1),
(1, 43, 1),
(1, 44, 1),
(1, 45, 1),
(1, 46, 1),
(1, 47, 1),
(1, 48, 1),
(1, 49, 1),
(1, 50, 1),
(1, 51, 1),
(1, 52, 1),
(1, 53, 1),
(1, 54, 1),
(1, 55, 1),
(1, 56, 1),
(1, 57, 1),
(1, 58, 1),
(1, 59, 1),
(1, 60, 1),
(1, 61, 1),
(1, 62, 1),
(1, 63, 1),
(1, 64, 1),
(1, 65, 1),
(1, 66, 1),
(3, 2, 1),
(3, 15, 1),
(3, 16, 1),
(3, 17, 1),
(3, 18, 1),
(3, 3, 1),
(3, 19, 1),
(3, 20, 1),
(3, 21, 1),
(3, 57, 1),
(3, 58, 1),
(3, 22, 1),
(3, 23, 1),
(3, 4, 1),
(3, 24, 1),
(3, 25, 1),
(3, 5, 1),
(3, 26, 1),
(3, 27, 1),
(3, 6, 1),
(3, 28, 1),
(3, 29, 1),
(2, 2, 1),
(2, 15, 1),
(2, 16, 1),
(2, 17, 1),
(2, 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_ingreso`
--

CREATE TABLE `pagos_ingreso` (
  `pagoingreso_id` bigint(20) UNSIGNED NOT NULL,
  `pagoingreso_ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `pagoingreso_monto` float(20,2) DEFAULT NULL,
  `pagoingreso_restante` float(20,2) DEFAULT NULL,
  `recibo_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pagos_ingreso`
--

INSERT INTO `pagos_ingreso` (`pagoingreso_id`, `pagoingreso_ingreso_id`, `pagoingreso_monto`, `pagoingreso_restante`, `recibo_id`) VALUES
(1, 1, 1.00, -1.00, 1),
(2, 1, 1.00, -2.00, 2),
(3, 1, 2.00, -3.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `id_pais` bigint(20) UNSIGNED NOT NULL,
  `nombre_pais` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`id_pais`, `nombre_pais`) VALUES
(3, 'Colombia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete_has_prod`
--

CREATE TABLE `paquete_has_prod` (
  `paquete_id` bigint(20) UNSIGNED NOT NULL,
  `prod_id` bigint(20) UNSIGNED NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paquete_has_prod`
--

INSERT INTO `paquete_has_prod` (`paquete_id`, `prod_id`, `unidad_id`, `cantidad`) VALUES
(237, 234, 1, 34),
(237, 234, 2, 34),
(237, 234, 3, 34),
(237, 235, 1, 34),
(237, 235, 2, 43),
(237, 235, 3, 34),
(191, 238, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `producto_codigo_interno` varchar(100) NOT NULL,
  `producto_nombre` varchar(255) NOT NULL,
  `produto_grupo` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_tipo` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_clasificacion` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_sustituto` varchar(250) DEFAULT NULL,
  `producto_proveedor` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_impuesto` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_ubicacion_fisica` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_estatus` tinyint(1) DEFAULT NULL,
  `producto_activo` tinyint(1) DEFAULT NULL,
  `costo_unitario` float DEFAULT NULL,
  `producto_mensaje` text,
  `costo_promedio` decimal(25,2) DEFAULT NULL,
  `producto_descuentos` decimal(25,2) DEFAULT NULL,
  `costo_cargue` decimal(25,2) DEFAULT NULL,
  `producto_comision` decimal(25,2) DEFAULT NULL,
  `producto_bonificaciones` decimal(25,2) DEFAULT NULL,
  `porcentaje_descuento` decimal(25,2) DEFAULT NULL,
  `precio_minimo` decimal(25,2) DEFAULT NULL,
  `precio_maximo` decimal(25,2) DEFAULT NULL,
  `precio_abierto` tinyint(1) DEFAULT NULL,
  `control_inven` tinyint(1) DEFAULT NULL,
  `control_inven_diario` tinyint(1) DEFAULT NULL,
  `is_paquete` tinyint(1) DEFAULT NULL,
  `porcentaje_costo` decimal(25,2) DEFAULT NULL,
  `is_prepack` tinyint(1) DEFAULT '0',
  `is_obsequio` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `producto_codigo_interno`, `producto_nombre`, `produto_grupo`, `producto_tipo`, `producto_clasificacion`, `producto_sustituto`, `producto_proveedor`, `producto_impuesto`, `producto_ubicacion_fisica`, `producto_estatus`, `producto_activo`, `costo_unitario`, `producto_mensaje`, `costo_promedio`, `producto_descuentos`, `costo_cargue`, `producto_comision`, `producto_bonificaciones`, `porcentaje_descuento`, `precio_minimo`, `precio_maximo`, `precio_abierto`, `control_inven`, `control_inven_diario`, `is_paquete`, `porcentaje_costo`, `is_prepack`, `is_obsequio`) VALUES
(176, '100001910', 'DOLEX 500 MG CAJA X 100 TABLETAS', 11, NULL, 1, 'ACETAMINOFEN', NULL, NULL, 3, 1, 1, 11.5, NULL, '6.11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(178, '200032290', 'NOSOTRAS BUENAS NOCHES X 10 OFERTA', NULL, 4, 9, NULL, NULL, NULL, 3, 1, 1, 42580, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(179, '100005434', 'MESIGYNA INSTAYECT 1 ML AMPOLLA', 9, 2, 5, NULL, NULL, 6, 3, 1, 1, 20000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, NULL, 0, 0),
(180, '200032918', 'ALPINA BABY FOR.INFA.PLUS 3 900 GR', 1, 2, NULL, NULL, NULL, NULL, 3, 1, 1, 0.5, NULL, '2.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 0, 0),
(181, '100022601', 'ENSURE LIQUIDO VAINILLA BOTELLA 237 ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 6808, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(182, '100012983', 'GLUCERNA SR VAINILLA 400 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 43434, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(183, '200032916', 'ALPINA BABY FOR.INFANTIL 2 900 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 37095, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(184, '100005035', 'APRONAX 550 MG 20 TABLETAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0.0487805, NULL, '6.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(185, '100020009', 'APRONAX LIQUID GEL 275 MG 8 CAPSULAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 10045, NULL, NULL, NULL, NULL, '10.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(186, '100013347', 'ASTHALIN HFA 100MCG AER.BUC.200 DOSIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 4803, NULL, NULL, NULL, NULL, '10.00', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(187, '100001673', 'BETAMETASONA 0.1% CREMA 40 GR GF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 8547, NULL, NULL, NULL, NULL, '10.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(191, '1212', 'PAQUETE BOMBA', NULL, NULL, NULL, NULL, NULL, 3, NULL, 1, 1, 8.5, NULL, '3.00', NULL, NULL, '12.00', NULL, NULL, '1.00', '1.00', NULL, 0, 0, 1, NULL, 0, 0),
(192, '100019092', 'OMEPRAZOL 20 MG 840 CAPSULAS', 11, 4, 1, NULL, NULL, 6, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 0, 0),
(193, '100000483', 'BAYCUTEN N CREMA 35 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 30681, NULL, NULL, NULL, NULL, NULL, '5.44', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(194, '200016599', 'EQUIPO MACRO GOTEO PRECISION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 772, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(195, '200024403', 'AGUA OXIGENADA JGB 120 ML PG.6 LL.7', 5, 3, 5, NULL, NULL, NULL, 3, 1, 1, 14434, NULL, NULL, NULL, NULL, NULL, '12.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(196, '100003892', 'ENALAPRIL 20 MG 30 TABLETAS AG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 3370, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(197, '100003947', 'OKEY 30 TABLETAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 36066, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(198, '200033885', 'PREP.TODAY SURTIDO PG42 LL48 3X4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 389366, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(199, '100005906', 'MERTHIOLATE INCOLORO TECNOQUIMICAS 90 ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 9866, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(200, '200034888', 'MENTICOL SPRAY SPORT 260ML', NULL, NULL, NULL, NULL, NULL, 6, NULL, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(201, '100000483', 'BAYCUTEN N CREMA 35 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 30681, NULL, NULL, NULL, NULL, NULL, '5.44', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(202, '200016599', 'EQUIPO MACRO GOTEO PRECISION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 772, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(203, '200024403', 'AGUA OXIGENADA JGB 120 ML PG.6 LL.7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 14434, NULL, NULL, NULL, NULL, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(204, '100003892', 'ENALAPRIL 20 MG 30 TABLETAS AG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 3370, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(205, '100003947', 'OKEY 30 TABLETAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 36066, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(206, '200033885', 'PREP.TODAY SURTIDO PG42 LL48 3X4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 389366, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(207, '100005906', 'MERTHIOLATE INCOLORO TECNOQUIMICAS 90 ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 9866, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(208, '200034888', 'MENTICOL SPRAY SPORT 260ML', NULL, NULL, NULL, NULL, NULL, 6, NULL, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(209, '100022771', 'ASPIRINA ULTRA 500 MG 100 TABLETAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 40993, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(210, '100021562', 'TESTOVIRON DEPOT 250 MG 1 AMP 1ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 22039, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(211, '100000580', 'ENJUAGUE CLARAX 180 ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 13135, NULL, NULL, NULL, NULL, NULL, '4.99', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(212, '100000937', 'FURACIN POMADA 40 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 26587, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(213, '200006123', 'DUCHA INTIMA ETERNA', NULL, NULL, NULL, NULL, NULL, 7, NULL, 1, 1, 3098, NULL, NULL, NULL, NULL, NULL, '4.75', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(214, '100001524', 'FITOSTIMOLINE CREMA 32 GR (T)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 72224, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(215, '100015582', 'NIFEDIPINO RETARD 30 MG 20 CAPSULAS EX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 12704, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(216, '100004451', 'PROCTO GLYVENOL CREMA 30 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 31185, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(217, '100018518', 'GENTOOFTAL 0.3% GOTAS 10ML ICOM (PD)(RF)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 3767, NULL, NULL, NULL, NULL, NULL, '65.76', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(218, '300000519', 'DERMACRON', NULL, NULL, NULL, NULL, NULL, 6, NULL, 1, 1, 19903, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(219, '100018400', 'MIELTERTOS PASTILLAS MASTICABLES 12 SBS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 11878, NULL, NULL, NULL, NULL, NULL, '7.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(220, '200031955', 'JAB.INTIMO INTIBON MUJER CALENDULA 200ML', NULL, NULL, NULL, NULL, NULL, 6, NULL, 1, 1, 8378, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(221, '100019540', 'ESPIRONOLACTONA 25 MG 20 TABLETAS LP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2454, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(222, '100004584', 'FELDENE 5% GEL 30 GR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 25649, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(223, '100004861', 'INDOMETACINA 25 MG 20 CAPSULAS PC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 5017, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(224, '100017689', 'METRONIDAZOL 500 MG 200 OVULOS PC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 44568, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(225, '200016463', 'MAQ.PRESTOBARBA GILLETTE 3 10 UDS', NULL, NULL, NULL, NULL, NULL, 6, NULL, 1, 1, 28274, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(226, '200030935', 'TOA.NOSOTRAS INVIS.RAPIGEL 10 UDS+2 TOA', NULL, NULL, NULL, NULL, NULL, 3, NULL, 1, 1, 2849, NULL, NULL, NULL, NULL, NULL, '10.01', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(227, '200031765', 'TOA.NOSOTRAS MATERNIDAD 10 UN+ 3 TOA B.N', NULL, NULL, NULL, NULL, NULL, 3, NULL, 1, 1, 10712, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(228, '100005173', 'ROXICAINA SPRAY 83 ML', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 33076, NULL, NULL, NULL, NULL, NULL, '8.55', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(229, '100019170', 'DURAFEX 250 MG 36 CAPSULAS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 40693, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(230, '100020800', 'DURAFEX ESPALDA 36 CAPSULAS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 52901, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(231, '100013018', 'HIDRAPLUS 30 CEREZA BOLSA 100 ML 5 UDS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 7876, NULL, NULL, NULL, NULL, NULL, '14.29', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(232, '100013016', 'HIDRAPLUS 30 COCO BOLSA 100 ML 5 UDS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 7876, NULL, NULL, NULL, NULL, NULL, '14.29', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(233, '100019456', 'LEVOTIROXINA 25 MG 50 TBS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 12156, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0),
(234, '100014464', 'LEVOTIROXINA 50 MG 50 TABLETAS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 13084, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(235, '100005923', 'NORAVER MENTA / FORTE 96 PASTILLAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 95001, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(236, '100014460', 'PAROXETINA 20 MG 10 TABLETAS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 1, NULL, 0, 0),
(237, '100005973', 'SERTRALINA 50 MG 10 TABLETAS MK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 108085, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, 0, 0, 1, NULL, 0, 0),
(238, '200003032', 'TALCO YODORA 60 GR', 5, NULL, NULL, NULL, NULL, 5, NULL, 1, 1, 8.5, 'ESTE PRODUTO NO DE PUED VEDER', '4.39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, 0, 0),
(239, '1000016673', 'ALERCET D 10 CAPSULAS', 12, 1, 1, NULL, NULL, NULL, 1, 1, 1, 2000, 'ESTE ES EL MENSAJE DE ALERTA DEL PRODUCTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(240, '300001583', 'BITROZIL TAB X 3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0),
(241, '200030384', 'CHA. JJ BABY', NULL, NULL, NULL, NULL, NULL, 7, NULL, 1, 1, 6000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_codigo_barra`
--

CREATE TABLE `producto_codigo_barra` (
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `codigo_barra` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_has_componente`
--

CREATE TABLE `producto_has_componente` (
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `componente_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `producto_has_componente`
--

INSERT INTO `producto_has_componente` (`producto_id`, `componente_id`) VALUES
(238, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` bigint(20) UNSIGNED NOT NULL,
  `proveedor_nombre` varchar(255) NOT NULL,
  `proveedor_identificacion` varchar(255) NOT NULL,
  `proveedor_celular` varchar(255) NOT NULL,
  `proveedor_direccion` text NOT NULL,
  `proveedor_email` varchar(255) DEFAULT NULL,
  `proveedor_telefono1` varchar(255) DEFAULT NULL,
  `proveedor_telefono2` varchar(255) DEFAULT NULL,
  `proveedor_tipo` bigint(20) UNSIGNED DEFAULT NULL,
  `proveedor_regimen` bigint(20) UNSIGNED DEFAULT NULL,
  `proveedor_ciudad` bigint(20) UNSIGNED DEFAULT NULL,
  `proveedor_digito_verificacion` varchar(2) DEFAULT NULL,
  `longitud` varchar(255) DEFAULT NULL,
  `latitud` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `proveedor_nombre`, `proveedor_identificacion`, `proveedor_celular`, `proveedor_direccion`, `proveedor_email`, `proveedor_telefono1`, `proveedor_telefono2`, `proveedor_tipo`, `proveedor_regimen`, `proveedor_ciudad`, `proveedor_digito_verificacion`, `longitud`, `latitud`, `deleted_at`) VALUES
(1, 'COOPIDROGAS', '12313', '121212', 'VALLE', 'COOPODROGAS@GMAIL.COM', '301212', '', 1, NULL, 11, '1', '0', '0', NULL),
(2, 'DISTRIBUIDORA NEGOCIEMOS', '800123584', '54322345', '', '', '213456', '', 1, 2, 11, '4', '0', '0', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibo_pago_cliente`
--

CREATE TABLE `recibo_pago_cliente` (
  `recibo_id` bigint(20) UNSIGNED NOT NULL,
  `usuario` bigint(20) UNSIGNED NOT NULL,
  `banco` bigint(20) UNSIGNED DEFAULT NULL,
  `metodo` bigint(20) UNSIGNED DEFAULT NULL,
  `observaciones_adicionales` text,
  `numero_documento` varchar(100) DEFAULT NULL,
  `fecha_consignacion` date DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `recibo_pago_cliente`
--

INSERT INTO `recibo_pago_cliente` (`recibo_id`, `usuario`, `banco`, `metodo`, `observaciones_adicionales`, `numero_documento`, `fecha_consignacion`, `fecha`) VALUES
(1, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(2, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(3, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(4, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(5, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(6, 1, NULL, 1, '', NULL, NULL, '2017-07-17'),
(7, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(8, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(9, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(10, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(11, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(12, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(13, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(14, 1, NULL, 1, '1212', NULL, NULL, '2017-07-20'),
(15, 1, NULL, 1, 'dasdasd', NULL, NULL, '2017-07-20'),
(16, 1, NULL, 1, 'eqwe', NULL, NULL, '2017-07-20'),
(17, 1, NULL, 1, 'qweqwe', NULL, NULL, '2017-07-20'),
(18, 1, NULL, 1, 'qqwe', NULL, NULL, '2017-07-20'),
(19, 1, NULL, 1, 'sas', NULL, NULL, '2017-07-20'),
(20, 1, NULL, 1, 'as', NULL, NULL, '2017-07-20'),
(21, 1, NULL, 1, 'as', NULL, NULL, '2017-07-20'),
(22, 1, NULL, 1, 'as', NULL, NULL, '2017-07-20'),
(23, 1, NULL, 1, '', NULL, NULL, '2017-07-20'),
(24, 1, NULL, 1, '', NULL, NULL, '2017-07-20'),
(25, 1, NULL, 2, '', NULL, NULL, '2017-07-20'),
(26, 1, NULL, 1, 'er', NULL, NULL, '2017-07-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibo_pago_proveedor`
--

CREATE TABLE `recibo_pago_proveedor` (
  `recibo_id` bigint(20) UNSIGNED NOT NULL,
  `usuario` bigint(20) UNSIGNED NOT NULL,
  `banco` bigint(20) UNSIGNED DEFAULT NULL,
  `metodo_pago` bigint(20) UNSIGNED DEFAULT NULL,
  `observaciones_adicionales` text,
  `numero_documento` varchar(100) DEFAULT NULL,
  `fecha_consignacion` date DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `recibo_pago_proveedor`
--

INSERT INTO `recibo_pago_proveedor` (`recibo_id`, `usuario`, `banco`, `metodo_pago`, `observaciones_adicionales`, `numero_documento`, `fecha_consignacion`, `fecha`) VALUES
(1, 1, NULL, 1, '', NULL, NULL, '2017-07-18'),
(2, 1, NULL, 1, '', NULL, NULL, '2017-07-20'),
(3, 1, NULL, 1, '', NULL, NULL, '2017-07-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regimen`
--

CREATE TABLE `regimen` (
  `regimen_id` bigint(20) UNSIGNED NOT NULL,
  `regimen_nombre` varchar(255) DEFAULT NULL,
  `compra_retienen` tinyint(1) DEFAULT NULL,
  `compra_retienen_iva` tinyint(1) DEFAULT NULL,
  `venta_retienen` tinyint(1) DEFAULT NULL,
  `venta_retienen_iva` tinyint(1) DEFAULT NULL,
  `genera_iva` tinyint(1) DEFAULT NULL,
  `autoretenedor` tinyint(1) DEFAULT NULL,
  `gran_contribuyente` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `regimen`
--

INSERT INTO `regimen` (`regimen_id`, `regimen_nombre`, `compra_retienen`, `compra_retienen_iva`, `venta_retienen`, `venta_retienen_iva`, `genera_iva`, `autoretenedor`, `gran_contribuyente`, `deleted_at`) VALUES
(1, 'REGIMEN SIMPLIFICADO', 0, 0, 0, 0, 0, 0, 0, NULL),
(2, 'RéGIMEN COMÚN PERSONA JURIDICA', 0, 0, 0, 0, 0, 0, 0, NULL),
(3, 'PERSONA NATURAL NO CONTRIBUYENTE', 0, 0, 0, 0, 0, 0, 0, NULL),
(4, 'GRAN CONTRIBUYENTE', 0, 0, 0, 0, 0, 0, 0, NULL),
(5, 'GRAN CONTRIBUYENTE AUTORETENEDOR', 0, 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resolucion_dian`
--

CREATE TABLE `resolucion_dian` (
  `resolucion_id` bigint(20) UNSIGNED NOT NULL,
  `resolucion_numero` int(11) UNSIGNED NOT NULL,
  `resolucion_prefijo` varchar(255) DEFAULT NULL,
  `resolucion_numero_inicial` int(11) DEFAULT NULL,
  `resolucion_numero_final` int(11) DEFAULT NULL,
  `resolucion_fech_aprobacion` date DEFAULT NULL,
  `resolucion_avisar` bigint(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `resolucion_dian`
--

INSERT INTO `resolucion_dian` (`resolucion_id`, `resolucion_numero`, `resolucion_prefijo`, `resolucion_numero_inicial`, `resolucion_numero_final`, `resolucion_fech_aprobacion`, `resolucion_avisar`, `deleted_at`) VALUES
(1, 12121212, 'SID', 11, 17, '2017-07-05', 232, '2017-08-25 09:02:46'),
(2, 12121212, 'BH', 1, 100000, '2017-08-28', 12, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status_caja`
--

CREATE TABLE `status_caja` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cajero` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `apertura` datetime DEFAULT NULL,
  `cierre` datetime DEFAULT NULL,
  `base` float DEFAULT NULL,
  `monto_cierre` float DEFAULT NULL,
  `observacion_cierre` text,
  `observacion_apertura` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `status_caja`
--

INSERT INTO `status_caja` (`id`, `cajero`, `caja_id`, `apertura`, `cierre`, `base`, `monto_cierre`, `observacion_cierre`, `observacion_apertura`) VALUES
(1, 2, 1, '1717-07-06 02:45:44', '1717-08-05 21:53:33', NULL, 0, '', NULL),
(13, 1, 1, '1717-08-04 16:40:17', '1717-08-05 20:28:10', NULL, 0, '', NULL),
(14, 2, 1, '1717-08-05 20:57:34', '1717-08-05 21:24:19', NULL, 0, 'HFN', NULL),
(15, 2, 1, '1717-08-05 21:29:48', NULL, 0, NULL, NULL, ''),
(16, 1, 1, '1717-08-05 21:51:31', '1717-08-05 21:56:08', NULL, 2, 'dadasd', NULL),
(17, 1, 1, '1717-08-05 22:01:24', '1717-08-06 00:55:07', NULL, 0, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_anulacion`
--

CREATE TABLE `tipo_anulacion` (
  `tipo_anulacion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_anulacion_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_anulacion`
--

INSERT INTO `tipo_anulacion` (`tipo_anulacion_id`, `tipo_anulacion_nombre`, `deleted_at`) VALUES
(1, 'ANULACION NORMAL', NULL),
(2, 'NO EXISTE LA DIRECCIóN ', NULL),
(3, 'EL CLIENTE NO QUISO LLEVAR EL PRODUCTO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_devolucion`
--

CREATE TABLE `tipo_devolucion` (
  `tipo_devolucion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_devolucion_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_devolucion`
--

INSERT INTO `tipo_devolucion` (`tipo_devolucion_id`, `tipo_devolucion_nombre`, `deleted_at`) VALUES
(1, 'EL PRODUCTO ESTABA MALO', NULL),
(2, 'DESPACHO EQUIVOCADO', NULL),
(3, 'EL CLIENTE NO QUIERE EL PRODUCTO', NULL),
(4, 'CLIENTE SE EQUIVOCO DE PRODCUTO', NULL),
(5, 'ERROR DEL VENDEDOR', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `tipo_prod_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_prod_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`tipo_prod_id`, `tipo_prod_nombre`, `deleted_at`) VALUES
(1, 'COMERCIAL', NULL),
(2, 'GENÉRICO', NULL),
(3, 'CONTROLADO', NULL),
(4, 'NATURAL', NULL),
(5, 'POPULARES', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedor`
--

CREATE TABLE `tipo_proveedor` (
  `tipo_proveedor_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_proveedor_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_proveedor`
--

INSERT INTO `tipo_proveedor` (`tipo_proveedor_id`, `tipo_proveedor_nombre`, `deleted_at`) VALUES
(1, 'MAYORISTAS DE MEDICAMENTOS', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_venta`
--

CREATE TABLE `tipo_venta` (
  `tipo_venta_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_venta_nombre` varchar(255) DEFAULT NULL,
  `solicita_cod_vendedor` tinyint(1) DEFAULT NULL,
  `genera_datos_cartera` tinyint(1) DEFAULT NULL,
  `admite_datos_cliente` tinyint(1) DEFAULT NULL,
  `datos_adic_clientes` tinyint(1) DEFAULT NULL,
  `genera_control_domicilios` tinyint(1) DEFAULT NULL,
  `maneja_formas_pago` tinyint(1) DEFAULT NULL,
  `liquida_iva` tinyint(1) DEFAULT NULL,
  `maneja_descuentos` tinyint(1) DEFAULT NULL,
  `opciones_call_center` tinyint(1) DEFAULT NULL,
  `aproximar_precio` int(11) DEFAULT NULL,
  `numero_copias` int(11) DEFAULT NULL,
  `documento_generar` varchar(255) DEFAULT NULL,
  `condicion_pago` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_venta`
--

INSERT INTO `tipo_venta` (`tipo_venta_id`, `tipo_venta_nombre`, `solicita_cod_vendedor`, `genera_datos_cartera`, `admite_datos_cliente`, `datos_adic_clientes`, `genera_control_domicilios`, `maneja_formas_pago`, `liquida_iva`, `maneja_descuentos`, `opciones_call_center`, `aproximar_precio`, `numero_copias`, `documento_generar`, `condicion_pago`, `deleted_at`) VALUES
(1, 'MOSTRADOR PERSONALIZADA', 1, 0, 1, 1, 0, 0, 1, 1, 0, 50, 1, 'FACTURA', 4, NULL),
(2, 'MOSTRADOR', 1, 0, 0, 0, 0, 1, 1, 0, 0, 50, 1, 'FACTURA', 4, NULL),
(3, 'VENTA A CREDITO', 1, 0, 1, 0, 0, 0, 1, 0, 0, 50, 2, 'FACTURA', 5, NULL),
(4, 'DOMICILIOS', 1, 0, 1, 0, 0, 0, 1, 1, 0, 50, 1, 'FACTURA', 4, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_fisica`
--

CREATE TABLE `ubicacion_fisica` (
  `ubicacion_id` bigint(20) UNSIGNED NOT NULL,
  `ubicacion_nombre` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ubicacion_fisica`
--

INSERT INTO `ubicacion_fisica` (`ubicacion_id`, `ubicacion_nombre`, `deleted_at`) VALUES
(1, 'ESTANTE 1', NULL),
(2, 'ESTANTE 2', NULL),
(3, 'ESTANTE 3', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `id_unidad` bigint(20) UNSIGNED NOT NULL,
  `nombre_unidad` varchar(255) DEFAULT NULL,
  `abreviatura` varchar(45) DEFAULT NULL,
  `orden` decimal(10,0) DEFAULT NULL,
  `estatus_unidad` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidades`
--

INSERT INTO `unidades` (`id_unidad`, `nombre_unidad`, `abreviatura`, `orden`, `estatus_unidad`) VALUES
(1, 'CAJA', 'CJA', '1', 1),
(2, 'BLISTER', 'BLIS', '2', 1),
(3, 'UNIDAD', 'UN', '3', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_has_precio`
--

CREATE TABLE `unidades_has_precio` (
  `id_condiciones_pago` bigint(20) UNSIGNED NOT NULL,
  `id_unidad` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `precio` double DEFAULT NULL,
  `utilidad` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidades_has_precio`
--

INSERT INTO `unidades_has_precio` (`id_condiciones_pago`, `id_unidad`, `id_producto`, `precio`, `utilidad`) VALUES
(4, 1, 218, 0, '0.00'),
(5, 1, 218, 0, '0.00'),
(4, 1, 237, 0, '0.00'),
(5, 1, 237, 0, '0.00'),
(4, 1, 236, 0, '0.00'),
(5, 1, 236, 0, '0.00'),
(4, 1, 234, 0, '0.00'),
(5, 1, 234, 0, '0.00'),
(4, 2, 234, 0, '0.00'),
(5, 2, 234, 0, '0.00'),
(4, 3, 234, 0, '0.00'),
(5, 3, 234, 0, '0.00'),
(4, 1, 235, 95951.01, '1.00'),
(5, 1, 235, 95951.01, '1.00'),
(4, 2, 235, 24225.26, '2.00'),
(5, 2, 235, 24225.26, '2.00'),
(4, 3, 235, 24462.76, '3.00'),
(5, 3, 235, 24462.76, '3.00'),
(4, 1, 191, 112454.35, '1.00'),
(5, 1, 191, 0, '0.00'),
(4, 1, 238, 10, '12.00'),
(5, 1, 238, 10.09, '13.00'),
(4, 2, 238, 5, '12.00'),
(5, 2, 238, 5.04, '13.00'),
(4, 3, 238, 1, '12.00'),
(5, 3, 238, 1.01, '13.00'),
(4, 1, 213, 10900, '228.82'),
(5, 1, 213, 0, '0.00'),
(4, 1, 176, 0, '0.00'),
(5, 1, 176, 0, '0.00'),
(4, 2, 176, 0, '0.00'),
(5, 2, 176, 0, '0.00'),
(4, 3, 176, 500, '43378.26'),
(5, 3, 176, 0, '0.00'),
(4, 1, 239, 0, '0.00'),
(5, 1, 239, 0, '0.00'),
(4, 3, 239, 4700, '2250.00'),
(5, 3, 239, 0, '0.00'),
(4, 1, 240, 0, '0.00'),
(5, 1, 240, 0, '0.00'),
(4, 3, 240, 9900, '1880.00'),
(5, 3, 240, 0, '0.00'),
(4, 1, 241, 0, '0.00'),
(5, 1, 241, 0, '0.00'),
(4, 3, 241, 10900, '1597.82'),
(5, 3, 241, 12900, '1909.35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_has_producto`
--

CREATE TABLE `unidades_has_producto` (
  `id_unidad` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `unidades` float DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT NULL,
  `stock_maximo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidades_has_producto`
--

INSERT INTO `unidades_has_producto` (`id_unidad`, `producto_id`, `unidades`, `stock_minimo`, `stock_maximo`) VALUES
(1, 218, 1, 0, 0),
(1, 237, 1, 0, 0),
(1, 236, 1, NULL, NULL),
(1, 234, 3, 4, 0),
(2, 234, 3, 4, 0),
(3, 234, 1, 4, 0),
(1, 235, 4, 0, 0),
(2, 235, 4, 0, 0),
(3, 235, 1, 0, 0),
(1, 191, 1, 0, 0),
(1, 238, 10, 0, 0),
(2, 238, 2, 0, 0),
(3, 238, 5, 0, 0),
(1, 213, 1, 0, 0),
(1, 176, 10, 0, 0),
(2, 176, 1, 0, 0),
(3, 176, 10, 0, 0),
(1, 239, 10, 0, 0),
(3, 239, 1, 0, 0),
(1, 240, 10, 0, 0),
(3, 240, 1, 0, 0),
(1, 241, 10, 0, 0),
(3, 241, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nUsuCodigo` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(18) NOT NULL,
  `var_usuario_clave` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `identificacion` int(45) NOT NULL,
  `grupo` bigint(20) UNSIGNED DEFAULT NULL,
  `sueldo` int(11) DEFAULT NULL,
  `genero` varchar(9) DEFAULT NULL,
  `longitud` varchar(255) DEFAULT NULL,
  `latitud` varchar(255) DEFAULT NULL,
  `obser` varchar(255) DEFAULT NULL,
  `smovil` tinyint(1) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT '0',
  `fnac` date DEFAULT NULL,
  `fent` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nUsuCodigo`, `username`, `var_usuario_clave`, `nombre`, `identificacion`, `grupo`, `sueldo`, `genero`, `longitud`, `latitud`, `obser`, `smovil`, `admin`, `activo`, `deleted`, `fnac`, `fent`) VALUES
(1, 'ADMINISTRADOR', '25d55ad283aa400af464c76d713c07ad', 'Usuario', 46598773, 1, 0, 'masculino', '', '', NULL, 1, 1, 1, 0, '2016-02-16', '2016-02-16'),
(2, 'CAJERO', '25d55ad283aa400af464c76d713c07ad', 'CAJERO 1', 46598773, 3, 0, 'masculino', '', '', NULL, 1, 1, 1, 0, '2016-02-16', '2016-02-16'),
(3, 'VENDEDOR', '25d55ad283aa400af464c76d713c07ad', 'VENDEDOR', 423423, 2, NULL, 'masculino', '-76.5347931', '3.4275504', '234', 1, 0, 1, 0, '2017-07-18', '2017-07-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `venta_tipo` bigint(20) UNSIGNED NOT NULL,
  `id_cliente` bigint(20) UNSIGNED DEFAULT NULL,
  `id_vendedor` bigint(20) UNSIGNED DEFAULT NULL,
  `cajero_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `local_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `venta_status` varchar(45) DEFAULT NULL,
  `subtotal` decimal(18,2) DEFAULT NULL,
  `gravado` decimal(18,2) DEFAULT NULL,
  `excluido` decimal(18,2) DEFAULT NULL,
  `total_impuesto` decimal(18,2) DEFAULT NULL,
  `total` decimal(18,2) DEFAULT NULL,
  `pagado` decimal(18,2) DEFAULT NULL,
  `descuento_valor` decimal(18,2) DEFAULT NULL,
  `descuento_porcentaje` decimal(18,2) DEFAULT NULL,
  `cambio` decimal(18,2) DEFAULT NULL,
  `devuelta` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`venta_id`, `venta_tipo`, `id_cliente`, `id_vendedor`, `cajero_id`, `caja_id`, `local_id`, `fecha`, `venta_status`, `subtotal`, `gravado`, `excluido`, `total_impuesto`, `total`, `pagado`, `descuento_valor`, `descuento_porcentaje`, `cambio`, `devuelta`) VALUES
(7, 1, 2, 3, 2, 1, 1, '2017-07-06 02:59:08', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(8, 1, 2, 3, 2, 1, 1, '2017-07-06 02:59:56', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(9, 1, 2, 3, 2, 1, 1, '2017-07-06 03:00:54', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(10, 1, 2, 3, 2, 1, 1, '2017-07-06 03:02:28', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(11, 1, 2, 3, 2, 1, 1, '2017-07-06 03:04:26', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(12, 1, 2, 3, 2, 1, 1, '2017-07-06 03:05:38', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(13, 1, 2, 3, 2, 1, 1, '2017-07-06 03:06:46', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(14, 1, 2, 3, 2, 1, 1, '2017-07-06 03:08:42', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(15, 1, 2, 3, 1, 1, 1, '2017-07-17 23:26:11', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '12121.00', '0.00', '0.00', '11746.00', 0),
(16, 1, 2, 3, 1, 1, 1, '2017-07-17 23:28:07', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '43424234234.00', '0.00', '0.00', '43424181692.00', 0),
(17, 1, 2, 3, 1, 1, 1, '2017-07-17 23:31:00', 'COMPLETADO', '11259.00', NULL, NULL, '0.00', '11259.00', '0.00', '0.00', '0.00', '0.00', 0),
(18, 1, 2, 3, 1, 1, 1, '2017-07-17 23:32:12', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '49000.00', '0.00', '0.00', '48625.00', 0),
(19, 2, NULL, 3, 1, 1, 1, '2017-07-19 00:21:44', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '4234234.00', NULL, NULL, '4233859.00', 0),
(20, 1, NULL, 3, 1, 1, 1, '2017-07-19 00:25:39', 'COMPLETADO', '1125.00', NULL, NULL, '0.00', '1125.00', '121212.00', NULL, NULL, '120087.00', 0),
(21, 1, 4, 3, 1, 1, 1, '2017-07-19 00:25:46', 'COMPLETADO', '12384.00', NULL, NULL, '0.00', '12384.00', '121212.00', NULL, NULL, '120087.00', 0),
(22, 1, 6, 3, 1, 1, 1, '2017-07-20 19:03:26', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '12121212.00', '0.00', '0.00', '1159579.00', 0),
(23, 1, 6, 3, 1, 1, 1, '2017-07-20 19:34:37', 'COMPLETADO', '157626.00', NULL, NULL, '0.00', '157626.00', '23232323.00', '0.00', '0.00', '2165606.00', 0),
(24, 1, 6, 3, 1, 1, 1, '2017-07-20 23:21:48', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '1212121212.00', '0.00', '0.00', '121159579.00', 0),
(25, 1, 6, 3, 1, 1, 1, '2017-08-05 21:53:09', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1500000.00', '0.00', '0.00', '37545.60', 0),
(26, 1, 6, 3, 1, 1, 1, '2017-08-06 15:56:40', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(27, 1, 6, 3, 1, 1, 1, '2017-08-06 16:00:21', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(28, 1, 6, 3, 1, 1, 1, '2017-08-06 16:04:59', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(29, 1, 6, 3, 1, 1, 1, '2017-08-06 16:05:33', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '213123123.00', '0.00', '0.00', '21199857.60', 0),
(30, 1, 6, 3, 1, 1, 1, '2017-08-06 16:12:19', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(31, 1, 6, 3, 1, 1, 1, '2017-08-06 16:13:49', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(32, 1, 6, 3, 1, 1, 1, '2017-08-06 16:14:05', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(33, 1, 6, 3, 1, 1, 1, '2017-08-06 16:14:21', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(34, 1, 6, 3, 1, 1, 1, '2017-08-06 16:50:06', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23434343.00', '0.00', '0.00', '2290892.00', 0),
(35, 1, 6, 3, 1, 1, 1, '2017-08-06 16:59:16', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '3213123123123.00', '0.00', '0.00', '321312259770.00', 0),
(36, 1, 6, 3, 1, 1, 1, '2017-08-06 17:00:11', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '12121212.00', '0.00', '0.00', '1159579.00', 0),
(37, 4, 6, 3, 1, 1, 1, '2017-08-06 19:51:28', 'COMPLETADO', '15012.00', NULL, NULL, '0.00', '15012.00', '5345345435.00', '0.00', '0.00', '534519531.00', 0),
(38, 4, 6, 3, 1, 1, 1, '2017-08-06 19:52:10', 'COMPLETADO', '15012.00', NULL, NULL, '0.00', '15012.00', '5345345435.00', '0.00', '0.00', '534519531.00', 0),
(39, 1, 6, 3, 1, 1, 1, '2017-08-06 20:06:41', 'COMPLETADO', '157626.00', NULL, NULL, '0.00', '157626.00', '234234234234.00', '0.00', '0.00', '23423265797.00', 0),
(40, 1, 6, 3, 1, 1, 1, '2017-08-06 20:07:51', 'COMPLETADO', '105084.00', NULL, NULL, '0.00', '105084.00', '2313123123123.00', '0.00', '0.00', '231312207228.00', 0),
(41, 1, 6, 3, 1, 1, 1, '2017-08-06 20:08:30', 'COMPLETADO', '1208466.00', NULL, NULL, '0.00', '1208466.00', '23213123123123.00', '0.00', '0.00', '2321311103846.00', 0),
(42, 1, 6, 3, 1, 1, 1, '2017-08-06 20:13:58', 'COMPLETADO', '105084.00', NULL, NULL, '0.00', '105084.00', '232323.00', '0.00', '0.00', '0.00', 0),
(43, 1, 6, 3, 1, 1, 1, '2017-08-06 20:20:14', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '121212121.00', '0.00', '0.00', '12068670.00', 0),
(44, 1, 6, 3, 1, 1, 1, '2017-08-07 18:07:48', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '1212121212.00', '0.00', '0.00', '121159579.00', 0),
(45, 1, 6, 3, 1, 1, 1, '2017-08-07 18:08:55', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23123123123.00', '0.00', '0.00', '2312259770.00', 0),
(46, 1, 6, 3, 1, 1, 1, '2017-08-07 18:14:15', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23123123123.00', '0.00', '0.00', '2312259770.00', 0),
(47, 1, 6, 3, 1, 1, 1, '2017-08-08 01:16:31', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '121212121.00', '0.00', '0.00', '12117459.00', 0),
(48, 1, 6, 3, 1, 1, 1, '2017-08-08 01:16:36', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '121212121.00', '0.00', '0.00', '12117459.00', 0),
(49, 1, 6, 3, 1, 1, 1, '2017-08-08 20:03:54', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '2232323.00', '0.00', '0.00', '219479.00', 0),
(50, 1, 6, 3, 1, 1, 1, '2017-08-08 20:05:30', 'COMPLETADO', '2250.00', NULL, NULL, '0.00', '2250.00', '3434324234312321.00', '0.00', '0.00', '343432423428982.00', 0),
(51, 1, 6, 3, 1, 1, 1, '2017-08-08 20:08:11', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(52, 1, 6, 3, 1, 1, 1, '2017-08-08 20:13:08', 'COMPLETADO', '18765.00', NULL, NULL, '0.00', '18765.00', '4234234234.00', '0.00', '0.00', '423404658.00', 0),
(53, 1, 6, 3, 1, 1, 1, '2017-08-08 20:15:12', 'COMPLETADO', '22518.00', NULL, NULL, '0.00', '22518.00', '345435345.00', '0.00', '0.00', '34521016.00', 0),
(54, 1, 6, 3, 1, 1, 1, '2017-08-08 20:30:00', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '1212.00', '0.00', '0.00', '0.00', 0),
(55, 1, 6, 3, 1, 1, 1, '2017-08-10 00:00:53', 'ANULADO', '7506.00', NULL, NULL, '0.00', '7506.00', '21212121.00', '0.00', '0.00', '2113706.00', 0),
(56, 1, 6, 3, 1, 1, 1, '2017-08-10 00:01:28', 'ANULADO', '7506.00', NULL, NULL, '0.00', '7506.00', '234423424.00', '0.00', '0.00', '23434836.00', 0),
(57, 1, 6, 3, 1, 1, 1, '2017-08-10 00:14:05', 'ANULADO', '18765.00', NULL, NULL, '0.00', '18765.00', '23232323.00', '0.00', '0.00', '2304467.00', 0),
(58, 3, 6, 3, 1, 1, 1, '2017-08-10 01:00:45', 'COMPLETADO', '11361.00', NULL, NULL, '0.00', '11361.00', '34343434.00', '0.00', '0.00', '3422982.00', 0),
(59, 2, 6, 3, 1, 1, 1, '2017-08-10 01:04:14', 'COMPLETADO', '11259.00', NULL, NULL, '0.00', '11259.00', '3232323.00', '0.00', '0.00', '311973.00', 0),
(60, 1, NULL, 3, 1, 1, 1, '2017-08-11 23:43:08', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '65765765.00', NULL, NULL, '6572823.00', 0),
(61, 1, NULL, 3, 1, 1, 1, '2017-08-11 23:47:09', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '65675765.00', NULL, NULL, '6563823.00', 0),
(64, 1, 6, 3, 1, 1, 1, '2017-08-12 22:52:50', 'EN ESPERA', '7506.00', NULL, NULL, '0.00', '7506.00', '232323.00', '0.00', '0.00', '15726.00', 0),
(65, 1, 6, 3, 1, 1, 1, '2017-08-12 23:01:10', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '4234234.00', '0.00', '0.00', '415917.00', 0),
(66, 1, 6, 3, 1, 1, 1, '2017-08-12 23:02:23', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '121212.00', '0.00', '0.00', '4615.00', 0),
(67, 1, 6, 3, 1, 1, 1, '2017-08-12 23:06:52', 'EN ESPERA', '7506.00', NULL, NULL, '0.00', '7506.00', '23232323.00', '0.00', '0.00', '2315726.00', 0),
(68, 3, 6, 3, 1, 1, 1, '2017-08-12 23:55:38', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '23232323.00', '0.00', '0.00', '2315726.00', 0),
(69, 3, 6, 3, 1, 1, 1, '2017-08-13 00:01:04', 'COMPLETADO', '11361.00', NULL, NULL, '0.00', '11361.00', '23423424.00', '0.00', '0.00', '2330981.00', 0),
(70, 3, 6, 3, 1, 1, 1, '2017-08-13 00:02:03', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '12313123.00', '0.00', '0.00', '1223806.00', 0),
(71, 3, 6, 3, 1, 1, 1, '2017-08-13 00:06:58', 'COMPLETADO', '7574.00', NULL, NULL, '0.00', '7574.00', '23232323.00', '0.00', '0.00', '2315658.00', 0),
(72, 3, 6, 3, 1, 1, 1, '2017-08-13 00:11:58', 'COMPLETADO', '90888.00', NULL, NULL, '0.00', '90888.00', '0.00', NULL, NULL, '0.00', 0),
(73, 1, 6, 3, 1, 1, 1, '2017-08-15 23:34:31', 'COMPLETADO', '95960.01', NULL, NULL, '1.00', '95961.01', '234324234.00', '0.00', '0.00', '23336461.99', 0),
(74, 1, 6, 3, 1, 1, 1, '2017-08-16 16:26:53', 'COMPLETADO', '8829.00', NULL, NULL, '2071.00', '10900.00', '234234234.00', '0.00', '0.00', '23412523.00', 0),
(75, 1, 6, 3, 1, 1, 1, '2017-08-16 16:28:38', 'COMPLETADO', '8829.00', NULL, NULL, '2071.00', '10900.00', '121212121212.00', '0.00', '0.00', '12121201221.00', 0),
(76, 1, 6, 3, 1, 1, 1, '2017-08-16 16:29:48', 'COMPLETADO', '8829.00', '8829.00', '0.00', '2071.00', '10900.00', '121212121212.00', '0.00', '0.00', '12121201221.00', 0),
(77, 1, 6, 3, 1, 1, 1, '2017-08-16 16:32:01', 'COMPLETADO', '8829.00', '8829.00', '0.00', '2071.00', '10900.00', '2121212.00', '0.00', '0.00', '201221.00', 0),
(78, 1, 6, 3, 1, 1, 1, '2017-08-16 16:34:33', 'COMPLETADO', '10900.00', '8829.00', '0.00', '2071.00', '10900.00', '1212121212.00', '0.00', '0.00', '121201221.00', 0),
(79, 1, 6, 3, 1, 1, 1, '2017-08-16 16:55:46', 'COMPLETADO', '10900.00', '6480.00', '0.00', '1520.00', '8000.00', '42343434.00', '0.00', '0.00', '4226343.00', 0),
(80, 1, 6, 3, 1, 1, 1, '2017-08-18 16:06:44', 'COMPLETADO', '15600.00', '0.00', '14000.00', '0.00', '14000.00', '4234234234.00', '0.00', '0.00', '4234220234.00', 0),
(81, 1, 6, 3, 1, 1, 1, '2017-08-19 23:00:14', 'COMPLETADO', '15.60', '0.00', '14.00', '0.00', '14.00', '15000.00', '1600.00', '0.00', '14986.00', 0),
(82, 1, 6, 3, 1, 1, 1, '2017-08-19 23:05:19', 'COMPLETADO', '15600.00', '0.00', '14000.00', '0.00', '14.00', '15000.00', '1600.00', '0.00', '14.99', 0),
(83, 1, 6, 3, 1, 1, 1, '2017-08-19 23:24:24', 'COMPLETADO', '19059.66', '9159.66', '9900.00', '1740.34', '20800.00', '3123123123.00', '0.00', '0.00', '3.12', 0),
(85, 1, 6, 3, 1, 1, 1, '2017-08-19 23:34:47', 'COMPLETADO', '19210.27', '8367.00', '9043.27', '1589.73', '19000.00', '20000.00', '1800.00', '0.00', '1.00', 0),
(86, 1, 6, 3, 1, 1, 1, '2017-08-21 21:54:30', 'COMPLETADO', '9659.66', '9159.66', '500.00', '1740.34', '11400.00', '12000.00', '0.00', '0.00', '600.00', 0),
(87, 1, 6, 3, 1, 1, 1, '2017-08-21 21:55:17', 'COMPLETADO', '9659.66', '9159.66', '500.00', '1740.34', '11400.00', '12000.00', '0.00', '0.00', '600.00', 0),
(88, 1, 6, 3, 1, 1, 1, '2017-08-21 21:57:22', 'COMPLETADO', '9903.92', '7874.10', '429.82', '1496.08', '9800.00', '10000.00', '1600.00', '0.00', '200.00', 0),
(89, 1, 6, 3, 1, 1, 1, '2017-08-21 21:59:23', 'COMPLETADO', '18574.79', '16974.79', '0.00', '3225.21', '20200.00', '30000.00', '1600.00', '0.00', '9.80', 0),
(90, 1, 6, 3, 1, 1, 1, '2017-08-21 22:01:15', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '20900.00', '0.00', '0.00', '10.00', 0),
(91, 1, 6, 3, 1, 1, 1, '2017-08-21 22:01:46', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '343434.00', '0.00', '0.00', '332.53', 0),
(92, 1, 6, 3, 1, 1, 1, '2017-08-21 22:04:24', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '20000.00', '0.00', '0.00', '9.10', 0),
(93, 1, 6, 3, 1, 1, 1, '2017-08-21 22:06:54', 'COMPLETADO', '9303.36', '8403.36', '0.00', '1596.64', '10000.00', '11000.00', '900.00', '0.00', '100.00', 0),
(94, 1, 6, 3, 1, 1, 1, '2017-08-21 22:25:23', 'COMPLETADO', '9.09', '9.09', '0.00', '0.91', '10.00', '121.00', '0.00', '0.00', '111.00', 0),
(95, 1, 6, 3, 1, 1, 1, '2017-08-22 22:49:38', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(96, 1, 6, 3, 1, 1, 1, '2017-08-22 22:49:45', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(97, 1, 6, 3, 1, 1, 1, '2017-08-22 22:53:54', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(98, 1, 6, 3, 1, 1, 1, '2017-08-22 23:00:29', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '323232323.00', '0.00', '0.00', '323.12', 0),
(99, 1, 6, 3, 1, 1, 1, '2017-08-22 23:44:41', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '1212121212.00', '0.00', '0.00', '1.21', 0),
(100, 1, 6, 3, 1, 1, 1, '2017-08-22 23:45:55', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '1212121212.00', '0.00', '0.00', '1.21', 0),
(101, 1, 6, 3, 1, 1, 1, '2017-08-22 23:57:57', 'COMPLETADO', '1000.00', '0.00', '1000.00', '0.00', '1000.00', '234234.00', '0.00', '0.00', '233.23', 0),
(102, 1, 6, 3, 1, 1, 1, '2017-08-23 00:00:31', 'COMPLETADO', '0.00', '0.00', '0.00', '0.00', '0.00', '234234.00', '0.00', '0.00', '233.23', 1),
(103, 1, 6, 3, 1, 1, 1, '2017-08-25 20:47:25', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(104, 1, 6, 3, 1, 1, 1, '2017-08-25 20:47:46', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(105, 1, 6, 3, 1, 1, 1, '2017-08-25 20:48:00', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(106, 1, 6, 3, 1, 1, 1, '2017-08-25 20:49:19', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '23232323.00', '0.00', '0.00', '23.21', 0),
(107, 1, 6, 3, 1, 1, 1, '2017-08-25 20:49:29', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '33434.00', '0.00', '0.00', '22.53', 0),
(108, 1, 6, 3, 1, 1, 1, '2017-08-25 20:50:59', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '23232.00', '0.00', '0.00', '1.43', 0),
(109, 1, 6, 3, 1, 1, 1, '2017-08-25 20:51:13', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '232323.00', '0.00', '0.00', '210.52', 0),
(110, 1, 6, 3, 1, 1, 1, '2017-08-26 15:58:32', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '121212.00', '0.00', '0.00', '110.31', 0),
(111, 1, 6, 3, 1, 1, 1, '2017-08-26 15:59:57', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '12121212.00', '0.00', '0.00', '12.11', 0),
(112, 1, 6, 3, 1, 1, 1, '2017-08-26 16:01:18', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '123213213.00', '0.00', '0.00', '123.20', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_anular`
--

CREATE TABLE `venta_anular` (
  `nVenAnularCodigo` bigint(20) UNSIGNED NOT NULL,
  `id_venta` bigint(20) UNSIGNED NOT NULL,
  `tipo_anulación` bigint(20) UNSIGNED NOT NULL,
  `nUsuCodigo` bigint(20) UNSIGNED NOT NULL,
  `dat_fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_anular`
--

INSERT INTO `venta_anular` (`nVenAnularCodigo`, `id_venta`, `tipo_anulacion`, `nUsuCodigo`, `dat_fecha_registro`) VALUES
(3, 56, 1, 1, '2017-08-10 00:06:58'),
(5, 55, 1, 1, '2017-08-10 00:07:38'),
(6, 57, 3, 1, '2017-08-10 00:14:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_backup`
--

CREATE TABLE `venta_backup` (
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `venta_tipo` bigint(20) UNSIGNED NOT NULL,
  `id_cliente` bigint(20) UNSIGNED DEFAULT NULL,
  `id_vendedor` bigint(20) UNSIGNED DEFAULT NULL,
  `cajero_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `local_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `venta_status` varchar(45) DEFAULT NULL,
  `subtotal` decimal(18,2) DEFAULT NULL,
  `gravado` decimal(18,2) DEFAULT NULL,
  `excluido` decimal(18,2) DEFAULT NULL,
  `total_impuesto` decimal(18,2) DEFAULT NULL,
  `total` decimal(18,2) DEFAULT NULL,
  `pagado` decimal(18,2) DEFAULT NULL,
  `descuento_valor` decimal(18,2) DEFAULT NULL,
  `descuento_porcentaje` decimal(18,2) DEFAULT NULL,
  `cambio` decimal(18,2) DEFAULT NULL,
  `devuelta` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_backup`
--

INSERT INTO `venta_backup` (`venta_id`, `venta_tipo`, `id_cliente`, `id_vendedor`, `cajero_id`, `caja_id`, `local_id`, `fecha`, `venta_status`, `subtotal`, `gravado`, `excluido`, `total_impuesto`, `total`, `pagado`, `descuento_valor`, `descuento_porcentaje`, `cambio`, `devuelta`) VALUES
(7, 1, 2, 3, 2, 1, 1, '2017-07-06 02:59:08', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(8, 1, 2, 3, 2, 1, 1, '2017-07-06 02:59:56', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(9, 1, 2, 3, 2, 1, 1, '2017-07-06 03:00:54', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(10, 1, 2, 3, 2, 1, 1, '2017-07-06 03:02:28', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(11, 1, 2, 3, 2, 1, 1, '2017-07-06 03:04:26', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(12, 1, 2, 3, 2, 1, 1, '2017-07-06 03:05:38', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(13, 1, 2, 3, 2, 1, 1, '2017-07-06 03:06:46', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(14, 1, 2, 3, 2, 1, 1, '2017-07-06 03:08:42', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '657656765.00', '0.00', '0.00', '657649259.00', 0),
(15, 1, 2, 3, 1, 1, 1, '2017-07-17 23:26:11', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '12121.00', '0.00', '0.00', '11746.00', 0),
(16, 1, 2, 3, 1, 1, 1, '2017-07-17 23:28:07', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '43424234234.00', '0.00', '0.00', '43424181692.00', 0),
(17, 1, 2, 3, 1, 1, 1, '2017-07-17 23:31:00', 'EN ESPERA', '52542.00', NULL, NULL, '0.00', '52542.00', '0.00', '0.00', '0.00', '0.00', 0),
(18, 1, 2, 3, 1, 1, 1, '2017-07-17 23:32:12', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '49000.00', '0.00', '0.00', '48625.00', 0),
(19, 2, NULL, 3, 1, 1, 1, '2017-07-19 00:21:44', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '4234234.00', NULL, NULL, '4233859.00', 0),
(20, 1, NULL, 3, 1, 1, 1, '2017-07-19 00:25:39', 'COMPLETADO', '1125.00', NULL, NULL, '0.00', '1125.00', '121212.00', NULL, NULL, '120087.00', 0),
(21, 1, NULL, 3, 1, 1, 1, '2017-07-19 00:25:46', 'EN ESPERA', '1125.00', NULL, NULL, '0.00', '1125.00', '121212.00', NULL, NULL, '120087.00', 0),
(22, 1, 6, 3, 1, 1, 1, '2017-07-20 19:03:26', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '12121212.00', '0.00', '0.00', '1159579.00', 0),
(23, 1, 6, 3, 1, 1, 1, '2017-07-20 19:34:37', 'COMPLETADO', '157626.00', NULL, NULL, '0.00', '157626.00', '23232323.00', '0.00', '0.00', '2165606.00', 0),
(24, 1, 6, 3, 1, 1, 1, '2017-07-20 23:21:48', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '1212121212.00', '0.00', '0.00', '121159579.00', 0),
(25, 1, 6, 3, 1, 1, 1, '2017-08-05 21:53:09', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1500000.00', '0.00', '0.00', '37545.60', 0),
(26, 1, 6, 3, 1, 1, 1, '2017-08-06 15:56:40', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(27, 1, 6, 3, 1, 1, 1, '2017-08-06 16:00:21', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(28, 1, 6, 3, 1, 1, 1, '2017-08-06 16:04:59', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(29, 1, 6, 3, 1, 1, 1, '2017-08-06 16:05:33', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '213123123.00', '0.00', '0.00', '21199857.60', 0),
(30, 1, 6, 3, 1, 1, 1, '2017-08-06 16:12:19', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(31, 1, 6, 3, 1, 1, 1, '2017-08-06 16:13:49', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(32, 1, 6, 3, 1, 1, 1, '2017-08-06 16:14:05', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(33, 1, 6, 3, 1, 1, 1, '2017-08-06 16:14:21', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '12121212121.00', '0.00', '0.00', '1212008757.60', 0),
(34, 1, 6, 3, 1, 1, 1, '2017-08-06 16:50:06', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23434343.00', '0.00', '0.00', '2290892.00', 0),
(35, 1, 6, 3, 1, 1, 1, '2017-08-06 16:59:16', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '3213123123123.00', '0.00', '0.00', '321312259770.00', 0),
(36, 1, 6, 3, 1, 1, 1, '2017-08-06 17:00:11', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '12121212.00', '0.00', '0.00', '1159579.00', 0),
(37, 4, 6, 3, 1, 1, 1, '2017-08-06 19:51:28', 'COMPLETADO', '15012.00', NULL, NULL, '0.00', '15012.00', '5345345435.00', '0.00', '0.00', '534519531.00', 0),
(38, 4, 6, 3, 1, 1, 1, '2017-08-06 19:52:10', 'COMPLETADO', '15012.00', NULL, NULL, '0.00', '15012.00', '5345345435.00', '0.00', '0.00', '534519531.00', 0),
(39, 1, 6, 3, 1, 1, 1, '2017-08-06 20:06:41', 'COMPLETADO', '157626.00', NULL, NULL, '0.00', '157626.00', '234234234234.00', '0.00', '0.00', '23423265797.00', 0),
(40, 1, 6, 3, 1, 1, 1, '2017-08-06 20:07:51', 'COMPLETADO', '105084.00', NULL, NULL, '0.00', '105084.00', '2313123123123.00', '0.00', '0.00', '231312207228.00', 0),
(41, 1, 6, 3, 1, 1, 1, '2017-08-06 20:08:30', 'COMPLETADO', '1208466.00', NULL, NULL, '0.00', '1208466.00', '23213123123123.00', '0.00', '0.00', '2321311103846.00', 0),
(42, 1, 6, 3, 1, 1, 1, '2017-08-06 20:13:58', 'COMPLETADO', '105084.00', NULL, NULL, '0.00', '105084.00', '232323.00', '0.00', '0.00', '0.00', 0),
(43, 1, 6, 3, 1, 1, 1, '2017-08-06 20:20:14', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '121212121.00', '0.00', '0.00', '12068670.00', 0),
(44, 1, 6, 3, 1, 1, 1, '2017-08-07 18:07:48', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '1212121212.00', '0.00', '0.00', '121159579.00', 0),
(45, 1, 6, 3, 1, 1, 1, '2017-08-07 18:08:55', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23123123123.00', '0.00', '0.00', '2312259770.00', 0),
(46, 1, 6, 3, 1, 1, 1, '2017-08-07 18:14:15', 'COMPLETADO', '52542.00', NULL, NULL, '0.00', '52542.00', '23123123123.00', '0.00', '0.00', '2312259770.00', 0),
(47, 1, 6, 3, 1, 1, 1, '2017-08-08 01:16:31', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '121212121.00', '0.00', '0.00', '12117459.00', 0),
(48, 1, 6, 3, 1, 1, 1, '2017-08-08 01:16:36', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '121212121.00', '0.00', '0.00', '12117459.00', 0),
(49, 1, 6, 3, 1, 1, 1, '2017-08-08 20:03:54', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '2232323.00', '0.00', '0.00', '219479.00', 0),
(50, 1, 6, 3, 1, 1, 1, '2017-08-08 20:05:30', 'COMPLETADO', '2250.00', NULL, NULL, '0.00', '2250.00', '3434324234312321.00', '0.00', '0.00', '343432423428982.00', 0),
(51, 1, 6, 3, 1, 1, 1, '2017-08-08 20:08:11', 'COMPLETADO', '106831.60', NULL, NULL, '5622.80', '112454.40', '1212121212.00', '0.00', '0.00', '121099666.60', 0),
(52, 1, 6, 3, 1, 1, 1, '2017-08-08 20:13:08', 'COMPLETADO', '18765.00', NULL, NULL, '0.00', '18765.00', '4234234234.00', '0.00', '0.00', '423404658.00', 0),
(53, 1, 6, 3, 1, 1, 1, '2017-08-08 20:15:12', 'COMPLETADO', '22518.00', NULL, NULL, '0.00', '22518.00', '345435345.00', '0.00', '0.00', '34521016.00', 0),
(54, 1, 6, 3, 1, 1, 1, '2017-08-08 20:30:00', 'COMPLETADO', '375.00', NULL, NULL, '0.00', '375.00', '1212.00', '0.00', '0.00', '0.00', 0),
(55, 1, 6, 3, 1, 1, 1, '2017-08-10 00:00:53', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '21212121.00', '0.00', '0.00', '2113706.00', 0),
(56, 1, 6, 3, 1, 1, 1, '2017-08-10 00:01:28', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '234423424.00', '0.00', '0.00', '23434836.00', 0),
(57, 1, 6, 3, 1, 1, 1, '2017-08-10 00:14:05', 'COMPLETADO', '18765.00', NULL, NULL, '0.00', '18765.00', '23232323.00', '0.00', '0.00', '2304467.00', 0),
(58, 3, 6, 3, 1, 1, 1, '2017-08-10 01:00:45', 'COMPLETADO', '11361.00', NULL, NULL, '0.00', '11361.00', '34343434.00', '0.00', '0.00', '3422982.00', 0),
(59, 2, 6, 3, 1, 1, 1, '2017-08-10 01:04:14', 'COMPLETADO', '11259.00', NULL, NULL, '0.00', '11259.00', '3232323.00', '0.00', '0.00', '311973.00', 0),
(60, 1, NULL, 3, 1, 1, 1, '2017-08-11 23:43:08', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '65765765.00', NULL, NULL, '6572823.00', 0),
(61, 1, NULL, 3, 1, 1, 1, '2017-08-11 23:47:09', 'COMPLETADO', '3753.00', NULL, NULL, '0.00', '3753.00', '65675765.00', NULL, NULL, '6563823.00', 0),
(64, 1, 6, 3, 1, 1, 1, '2017-08-12 22:52:50', 'EN ESPERA', '7506.00', NULL, NULL, '0.00', '7506.00', '232323.00', '0.00', '0.00', '15726.00', 0),
(65, 1, 6, 3, 1, 1, 1, '2017-08-12 23:01:10', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '4234234.00', '0.00', '0.00', '415917.00', 0),
(66, 1, 6, 3, 1, 1, 1, '2017-08-12 23:02:23', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '121212.00', '0.00', '0.00', '4615.00', 0),
(67, 1, 6, 3, 1, 1, 1, '2017-08-12 23:06:52', 'EN ESPERA', '7506.00', NULL, NULL, '0.00', '7506.00', '23232323.00', '0.00', '0.00', '2315726.00', 0),
(68, 3, 6, 3, 1, 1, 1, '2017-08-12 23:55:38', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '23232323.00', '0.00', '0.00', '2315726.00', 0),
(69, 3, 6, 3, 1, 1, 1, '2017-08-13 00:01:04', 'COMPLETADO', '11361.00', NULL, NULL, '0.00', '11361.00', '23423424.00', '0.00', '0.00', '2330981.00', 0),
(70, 3, 6, 3, 1, 1, 1, '2017-08-13 00:02:03', 'COMPLETADO', '7506.00', NULL, NULL, '0.00', '7506.00', '12313123.00', '0.00', '0.00', '1223806.00', 0),
(71, 3, 6, 3, 1, 1, 1, '2017-08-13 00:06:58', 'COMPLETADO', '7574.00', NULL, NULL, '0.00', '7574.00', '23232323.00', '0.00', '0.00', '2315658.00', 0),
(72, 3, 6, 3, 1, 1, 1, '2017-08-13 00:11:58', 'COMPLETADO', '90888.00', NULL, NULL, '0.00', '90888.00', '0.00', NULL, NULL, '0.00', 0),
(73, 1, 6, 3, 1, 1, 1, '2017-08-15 23:34:31', 'COMPLETADO', '95960.01', NULL, NULL, '1.00', '95961.01', '234324234.00', '0.00', '0.00', '23336461.99', 0),
(74, 1, 6, 3, 1, 1, 1, '2017-08-16 16:26:53', 'COMPLETADO', '8829.00', NULL, NULL, '2071.00', '10900.00', '234234234.00', '0.00', '0.00', '23412523.00', 0),
(75, 1, 6, 3, 1, 1, 1, '2017-08-16 16:28:38', 'COMPLETADO', '8829.00', NULL, NULL, '2071.00', '10900.00', '121212121212.00', '0.00', '0.00', '12121201221.00', 0),
(76, 1, 6, 3, 1, 1, 1, '2017-08-16 16:29:48', 'COMPLETADO', '8829.00', '8829.00', '0.00', '2071.00', '10900.00', '121212121212.00', '0.00', '0.00', '12121201221.00', 0),
(77, 1, 6, 3, 1, 1, 1, '2017-08-16 16:32:01', 'COMPLETADO', '8829.00', '8829.00', '0.00', '2071.00', '10900.00', '2121212.00', '0.00', '0.00', '201221.00', 0),
(78, 1, 6, 3, 1, 1, 1, '2017-08-16 16:34:33', 'COMPLETADO', '10900.00', '8829.00', '0.00', '2071.00', '10900.00', '1212121212.00', '0.00', '0.00', '121201221.00', 0),
(79, 1, 6, 3, 1, 1, 1, '2017-08-16 16:55:46', 'COMPLETADO', '10900.00', '6480.00', '0.00', '1520.00', '8000.00', '42343434.00', '0.00', '0.00', '4226343.00', 0),
(80, 1, 6, 3, 1, 1, 1, '2017-08-18 16:06:44', 'COMPLETADO', '15600.00', '0.00', '14000.00', '0.00', '14000.00', '4234234234.00', '0.00', '0.00', '4234220234.00', 0),
(81, 1, 6, 3, 1, 1, 1, '2017-08-19 23:00:14', 'COMPLETADO', '15.60', '0.00', '14.00', '0.00', '14.00', '15000.00', '1600.00', '0.00', '14986.00', 0),
(82, 1, 6, 3, 1, 1, 1, '2017-08-19 23:05:19', 'COMPLETADO', '15600.00', '0.00', '14000.00', '0.00', '14.00', '15000.00', '1600.00', '0.00', '14.99', 0),
(83, 1, 6, 3, 1, 1, 1, '2017-08-19 23:24:24', 'COMPLETADO', '19059.66', '9159.66', '9900.00', '1740.34', '20800.00', '3123123123.00', '0.00', '0.00', '3.12', 0),
(85, 1, 6, 3, 1, 1, 1, '2017-08-19 23:34:47', 'COMPLETADO', '19210.27', '8367.00', '9043.27', '1589.73', '19000.00', '20000.00', '1800.00', '0.00', '1.00', 0),
(86, 1, 6, 3, 1, 1, 1, '2017-08-21 21:54:30', 'COMPLETADO', '9659.66', '9159.66', '500.00', '1740.34', '11400.00', '12000.00', '0.00', '0.00', '600.00', 0),
(87, 1, 6, 3, 1, 1, 1, '2017-08-21 21:55:17', 'COMPLETADO', '9659.66', '9159.66', '500.00', '1740.34', '11400.00', '12000.00', '0.00', '0.00', '600.00', 0),
(88, 1, 6, 3, 1, 1, 1, '2017-08-21 21:57:22', 'COMPLETADO', '9903.92', '7874.10', '429.82', '1496.08', '9800.00', '10000.00', '1600.00', '0.00', '200.00', 0),
(89, 1, 6, 3, 1, 1, 1, '2017-08-21 21:59:23', 'COMPLETADO', '18574.79', '16974.79', '0.00', '3225.21', '20200.00', '30000.00', '1600.00', '0.00', '9.80', 0),
(90, 1, 6, 3, 1, 1, 1, '2017-08-21 22:01:15', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '20900.00', '0.00', '0.00', '10.00', 0),
(91, 1, 6, 3, 1, 1, 1, '2017-08-21 22:01:46', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '343434.00', '0.00', '0.00', '332.53', 0),
(92, 1, 6, 3, 1, 1, 1, '2017-08-21 22:04:24', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '20000.00', '0.00', '0.00', '9.10', 0),
(93, 1, 6, 3, 1, 1, 1, '2017-08-21 22:06:54', 'COMPLETADO', '9303.36', '8403.36', '0.00', '1596.64', '10000.00', '11000.00', '900.00', '0.00', '100.00', 0),
(94, 1, 6, 3, 1, 1, 1, '2017-08-21 22:25:23', 'COMPLETADO', '9.09', '9.09', '0.00', '0.91', '10.00', '121.00', '0.00', '0.00', '111.00', 0),
(95, 1, 6, 3, 1, 1, 1, '2017-08-22 22:49:38', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(96, 1, 6, 3, 1, 1, 1, '2017-08-22 22:49:45', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(97, 1, 6, 3, 1, 1, 1, '2017-08-22 22:53:54', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '3123123123.00', '0.00', '0.00', '3.12', 0),
(98, 1, 6, 3, 1, 1, 1, '2017-08-22 23:00:29', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '323232323.00', '0.00', '0.00', '323.12', 0),
(99, 1, 6, 3, 1, 1, 1, '2017-08-22 23:44:41', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '1212121212.00', '0.00', '0.00', '1.21', 0),
(100, 1, 6, 3, 1, 1, 1, '2017-08-22 23:45:55', 'COMPLETADO', '108705.87', '74969.57', '0.00', '3748.48', '112454.35', '1212121212.00', '0.00', '0.00', '1.21', 0),
(101, 1, 6, 3, 1, 1, 1, '2017-08-22 23:57:57', 'COMPLETADO', '1000.00', '0.00', '1000.00', '0.00', '1000.00', '234234.00', '0.00', '0.00', '233.23', 0),
(102, 1, 6, 3, 1, 1, 1, '2017-08-23 00:00:31', 'COMPLETADO', '1000.00', '0.00', '1000.00', '0.00', '1000.00', '234234.00', '0.00', '0.00', '233.23', 0),
(103, 1, 6, 3, 1, 1, 1, '2017-08-25 20:47:25', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(104, 1, 6, 3, 1, 1, 1, '2017-08-25 20:47:46', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(105, 1, 6, 3, 1, 1, 1, '2017-08-25 20:48:00', 'COMPLETADO', '500.00', '0.00', '500.00', '0.00', '500.00', '1212.00', '0.00', '0.00', '712.00', 0),
(106, 1, 6, 3, 1, 1, 1, '2017-08-25 20:49:19', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '23232323.00', '0.00', '0.00', '23.21', 0),
(107, 1, 6, 3, 1, 1, 1, '2017-08-25 20:49:29', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '33434.00', '0.00', '0.00', '22.53', 0),
(108, 1, 6, 3, 1, 1, 1, '2017-08-25 20:50:59', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '23232.00', '0.00', '0.00', '1.43', 0),
(109, 1, 6, 3, 1, 1, 1, '2017-08-25 20:51:13', 'COMPLETADO', '18319.33', '18319.33', '0.00', '3480.67', '21800.00', '232323.00', '0.00', '0.00', '210.52', 0),
(110, 1, 6, 3, 1, 1, 1, '2017-08-26 15:58:32', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '121212.00', '0.00', '0.00', '110.31', 0),
(111, 1, 6, 3, 1, 1, 1, '2017-08-26 15:59:57', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '12121212.00', '0.00', '0.00', '12.11', 0),
(112, 1, 6, 3, 1, 1, 1, '2017-08-26 16:01:18', 'COMPLETADO', '9159.66', '9159.66', '0.00', '1740.34', '10900.00', '123213213.00', '0.00', '0.00', '123.20', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_estatus`
--

CREATE TABLE `venta_estatus` (
  `estatus_id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `vendedor_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL,
  `estatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta_estatus`
--

INSERT INTO `venta_estatus` (`estatus_id`, `venta_id`, `vendedor_id`, `fecha`, `estatus`) VALUES
(1, 7, 1, '2017-07-06 02:07:09', 'COMPLETADO'),
(2, 8, 1, '2017-07-06 02:07:56', 'COMPLETADO'),
(3, 9, 1, '2017-07-06 03:07:54', 'COMPLETADO'),
(4, 10, 1, '2017-07-06 03:07:28', 'COMPLETADO'),
(5, 11, 1, '2017-07-06 03:07:27', 'COMPLETADO'),
(6, 12, 1, '2017-07-06 03:07:38', 'COMPLETADO'),
(7, 13, 1, '2017-07-06 03:07:46', 'COMPLETADO'),
(8, 14, 1, '2017-07-06 03:07:43', 'COMPLETADO'),
(9, 15, 1, '2017-07-17 11:07:11', 'COMPLETADO'),
(10, 16, 1, '2017-07-17 11:07:07', 'COMPLETADO'),
(11, 17, 1, '2017-07-17 11:07:00', 'EN ESPERA'),
(12, 18, 1, '2017-07-17 11:07:13', 'COMPLETADO'),
(13, 19, 1, '2017-07-19 12:07:44', 'COMPLETADO'),
(14, 20, 1, '2017-07-19 12:07:40', 'COMPLETADO'),
(15, 21, 1, '2017-07-19 12:07:47', 'EN ESPERA'),
(16, 22, 1, '2017-07-20 07:07:27', 'COMPLETADO'),
(17, 23, 1, '2017-07-20 07:07:38', 'COMPLETADO'),
(18, 24, 1, '2017-07-20 11:07:49', 'COMPLETADO'),
(19, 25, 1, '2017-08-05 09:08:10', 'COMPLETADO'),
(20, 26, 1, '2017-08-06 03:08:40', 'COMPLETADO'),
(21, 27, 1, '2017-08-06 04:08:21', 'COMPLETADO'),
(22, 28, 1, '2017-08-06 04:08:59', 'COMPLETADO'),
(23, 29, 1, '2017-08-06 04:08:33', 'COMPLETADO'),
(24, 30, 1, '2017-08-06 04:08:19', 'COMPLETADO'),
(25, 31, 1, '2017-08-06 04:08:49', 'COMPLETADO'),
(26, 32, 1, '2017-08-06 04:08:05', 'COMPLETADO'),
(27, 33, 1, '2017-08-06 04:08:21', 'COMPLETADO'),
(28, 34, 1, '2017-08-06 04:08:07', 'COMPLETADO'),
(29, 35, 1, '2017-08-06 04:08:16', 'COMPLETADO'),
(30, 36, 1, '2017-08-06 05:08:12', 'COMPLETADO'),
(31, 37, 1, '2017-08-06 07:08:28', 'COMPLETADO'),
(32, 38, 1, '2017-08-06 07:08:10', 'COMPLETADO'),
(33, 39, 1, '2017-08-06 08:08:42', 'COMPLETADO'),
(34, 40, 1, '2017-08-06 08:08:52', 'COMPLETADO'),
(35, 41, 1, '2017-08-06 08:08:30', 'COMPLETADO'),
(36, 42, 1, '2017-08-06 08:08:58', 'COMPLETADO'),
(37, 43, 1, '2017-08-06 08:08:14', 'COMPLETADO'),
(38, 44, 1, '2017-08-07 06:08:49', 'COMPLETADO'),
(39, 45, 1, '2017-08-07 06:08:55', 'COMPLETADO'),
(40, 46, 1, '2017-08-07 06:08:15', 'COMPLETADO'),
(41, 47, 1, '2017-08-08 01:08:31', 'COMPLETADO'),
(42, 48, 1, '2017-08-08 01:08:36', 'COMPLETADO'),
(43, 49, 1, '2017-08-08 08:08:55', 'COMPLETADO'),
(44, 50, 1, '2017-08-08 08:08:30', 'COMPLETADO'),
(45, 51, 1, '2017-08-08 08:08:12', 'COMPLETADO'),
(46, 52, 1, '2017-08-08 08:08:08', 'COMPLETADO'),
(47, 53, 1, '2017-08-08 08:08:12', 'COMPLETADO'),
(48, 54, 1, '2017-08-08 08:08:00', 'COMPLETADO'),
(49, 55, 1, '2017-08-10 12:08:53', 'COMPLETADO'),
(50, 56, 1, '2017-08-10 12:08:28', 'COMPLETADO'),
(55, 56, 1, '2017-08-10 12:08:58', 'ANULADO'),
(56, 56, 1, '2017-08-10 12:08:58', 'ANULADO'),
(59, 55, 1, '2017-08-10 12:08:38', 'ANULADO'),
(60, 55, 1, '2017-08-10 12:08:38', 'ANULADO'),
(61, 57, 1, '2017-08-10 12:08:05', 'COMPLETADO'),
(62, 57, 1, '2017-08-10 12:08:24', 'ANULADO'),
(63, 57, 1, '2017-08-10 12:08:24', 'ANULADO'),
(64, 58, 1, '2017-08-10 01:08:45', 'COMPLETADO'),
(65, 59, 1, '2017-08-10 01:08:14', 'COMPLETADO'),
(66, 60, 1, '2017-08-11 11:08:09', 'COMPLETADO'),
(67, 61, 1, '2017-08-11 11:08:09', 'COMPLETADO'),
(68, 60, 1, '2017-08-12 05:08:50', 'COMPLETADO'),
(69, 61, 1, '2017-08-12 05:08:53', 'COMPLETADO'),
(70, 17, 1, '2017-08-12 10:08:49', 'EN ESPERA'),
(71, 17, 1, '2017-08-12 10:08:34', 'EN ESPERA'),
(72, 17, 1, '2017-08-12 10:08:27', 'COMPLETADO'),
(73, 21, 1, '2017-08-12 10:08:08', 'EN ESPERA'),
(74, 21, 1, '2017-08-12 10:08:20', 'COMPLETADO'),
(75, 64, 1, '2017-08-12 10:08:50', 'EN ESPERA'),
(76, 65, 1, '2017-08-12 11:08:10', 'COMPLETADO'),
(77, 66, 1, '2017-08-12 11:08:23', 'COMPLETADO'),
(78, 67, 1, '2017-08-12 11:08:52', 'EN ESPERA'),
(79, 68, 1, '2017-08-12 11:08:38', 'COMPLETADO'),
(80, 69, 1, '2017-08-13 12:08:04', 'COMPLETADO'),
(81, 70, 1, '2017-08-13 12:08:04', 'COMPLETADO'),
(82, 71, 1, '2017-08-13 12:08:58', 'COMPLETADO'),
(83, 72, 1, '2017-08-13 12:08:58', 'COMPLETADO'),
(84, 73, 1, '2017-08-15 11:08:31', 'COMPLETADO'),
(85, 74, 1, '2017-08-16 04:08:53', 'COMPLETADO'),
(86, 75, 1, '2017-08-16 04:08:39', 'COMPLETADO'),
(87, 76, 1, '2017-08-16 04:08:48', 'COMPLETADO'),
(88, 77, 1, '2017-08-16 04:08:02', 'COMPLETADO'),
(89, 78, 1, '2017-08-16 04:08:33', 'COMPLETADO'),
(90, 79, 1, '2017-08-16 04:08:46', 'COMPLETADO'),
(91, 80, 1, '2017-08-18 04:08:44', 'COMPLETADO'),
(92, 81, 1, '2017-08-19 11:08:16', 'COMPLETADO'),
(93, 82, 1, '2017-08-19 11:08:19', 'COMPLETADO'),
(94, 83, 1, '2017-08-19 11:08:24', 'COMPLETADO'),
(95, 85, 1, '2017-08-19 11:08:48', 'COMPLETADO'),
(96, 86, 1, '2017-08-21 09:08:30', 'COMPLETADO'),
(97, 87, 1, '2017-08-21 09:08:17', 'COMPLETADO'),
(98, 88, 1, '2017-08-21 09:08:22', 'COMPLETADO'),
(99, 89, 1, '2017-08-21 09:08:23', 'COMPLETADO'),
(100, 90, 1, '2017-08-21 10:08:15', 'COMPLETADO'),
(101, 91, 1, '2017-08-21 10:08:46', 'COMPLETADO'),
(102, 92, 1, '2017-08-21 10:08:24', 'COMPLETADO'),
(103, 93, 1, '2017-08-21 10:08:54', 'COMPLETADO'),
(104, 94, 1, '2017-08-21 10:08:23', 'COMPLETADO'),
(105, 95, 1, '2017-08-22 10:08:38', 'COMPLETADO'),
(106, 96, 1, '2017-08-22 10:08:45', 'COMPLETADO'),
(107, 97, 1, '2017-08-22 10:08:54', 'COMPLETADO'),
(108, 98, 1, '2017-08-22 11:08:29', 'COMPLETADO'),
(109, 99, 1, '2017-08-22 11:08:41', 'COMPLETADO'),
(110, 100, 1, '2017-08-22 11:08:55', 'COMPLETADO'),
(111, 101, 1, '2017-08-22 11:08:57', 'COMPLETADO'),
(112, 102, 1, '2017-08-23 12:08:31', 'COMPLETADO'),
(113, 102, 1, '2017-08-23 12:08:26', 'COMPLETADO'),
(114, 103, 1, '2017-08-25 08:08:26', 'COMPLETADO'),
(115, 104, 1, '2017-08-25 08:08:46', 'COMPLETADO'),
(116, 105, 1, '2017-08-25 08:08:00', 'COMPLETADO'),
(117, 106, 1, '2017-08-25 08:08:19', 'COMPLETADO'),
(118, 107, 1, '2017-08-25 08:08:29', 'COMPLETADO'),
(119, 108, 1, '2017-08-25 08:08:59', 'COMPLETADO'),
(120, 109, 1, '2017-08-25 08:08:13', 'COMPLETADO'),
(121, 110, 1, '2017-08-26 03:08:32', 'COMPLETADO'),
(122, 111, 1, '2017-08-26 03:08:57', 'COMPLETADO'),
(123, 112, 1, '2017-08-26 04:08:18', 'COMPLETADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

CREATE TABLE `zonas` (
  `zona_id` bigint(20) UNSIGNED NOT NULL,
  `zona_nombre` varchar(255) DEFAULT NULL,
  `ciudad_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`zona_id`, `zona_nombre`, `ciudad_id`, `status`) VALUES
(1, 'ULPIANO LLOREIDA', 11, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `afiliado`
--
ALTER TABLE `afiliado`
  ADD PRIMARY KEY (`afiliado_id`);

--
-- Indices de la tabla `afiliado_descuentos`
--
ALTER TABLE `afiliado_descuentos`
  ADD KEY `tipo_prod_id` (`tipo_prod_id`),
  ADD KEY `unidad_id` (`unidad_id`),
  ADD KEY `afiliado_id` (`afiliado_id`);

--
-- Indices de la tabla `ajustedetalle`
--
ALTER TABLE `ajustedetalle`
  ADD PRIMARY KEY (`id_ajustedetalle`),
  ADD KEY `id_ajusteinventario` (`id_ajusteinventario`),
  ADD KEY `id_inventario` (`id_inventario`);

--
-- Indices de la tabla `ajusteinventario`
--
ALTER TABLE `ajusteinventario`
  ADD PRIMARY KEY (`id_ajusteinventario`),
  ADD KEY `tipo_ajuste` (`tipo_ajuste`),
  ADD KEY `local_id` (`local_id`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`banco_id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`caja_id`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`ciudad_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD PRIMARY KEY (`clasificacion_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `ciudad_id` (`ciudad_id`),
  ADD KEY `id_zona` (`id_zona`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `afiliado` (`afiliado`);

--
-- Indices de la tabla `columnas`
--
ALTER TABLE `columnas`
  ADD PRIMARY KEY (`id_columna`);

--
-- Indices de la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD PRIMARY KEY (`componente_id`);

--
-- Indices de la tabla `condiciones_pago`
--
ALTER TABLE `condiciones_pago`
  ADD PRIMARY KEY (`id_condiciones`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`config_id`);

--
-- Indices de la tabla `credito`
--
ALTER TABLE `credito`
  ADD PRIMARY KEY (`id_venta`);

--
-- Indices de la tabla `detalleingreso`
--
ALTER TABLE `detalleingreso`
  ADD PRIMARY KEY (`id_detalle_ingreso`),
  ADD KEY `id_ingreso` (`id_ingreso`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalleingreso_especial`
--
ALTER TABLE `detalleingreso_especial`
  ADD PRIMARY KEY (`id_detalle_especial`),
  ADD KEY `detalle_ingreso_id` (`detalle_ingreso_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `producto_id_especial` (`producto_id_especial`),
  ADD KEY `unidad_id` (`unidad_id`);

--
-- Indices de la tabla `detalle_ingreso_unidad`
--
ALTER TABLE `detalle_ingreso_unidad`
  ADD PRIMARY KEY (`detalle_ingreso_unidad_id`),
  ADD UNIQUE KEY `detalle_ingreso_unidad_id` (`detalle_ingreso_unidad_id`),
  ADD KEY `unidad_id` (`unidad_id`),
  ADD KEY `detalle_ingreso_id` (`detalle_ingreso_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_venta_backup`
--
ALTER TABLE `detalle_venta_backup`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_venta_unidad`
--
ALTER TABLE `detalle_venta_unidad`
  ADD KEY `unidad_id` (`unidad_id`),
  ADD KEY `detalle_venta_id` (`detalle_venta_id`);

--
-- Indices de la tabla `detalle_venta_unidad_backup`
--
ALTER TABLE `detalle_venta_unidad_backup`
  ADD KEY `unidad_id` (`unidad_id`),
  ADD KEY `detalle_venta_id` (`detalle_venta_id`);

--
-- Indices de la tabla `documentos_inventarios`
--
ALTER TABLE `documentos_inventarios`
  ADD PRIMARY KEY (`documento_id`);

--
-- Indices de la tabla `documento_venta`
--
ALTER TABLE `documento_venta`
  ADD PRIMARY KEY (`id_tipo_documento`),
  ADD KEY `documento_venta_ibfk_1` (`id_venta`);

--
-- Indices de la tabla `droguerias_relacionadas`
--
ALTER TABLE `droguerias_relacionadas`
  ADD PRIMARY KEY (`drogueria_id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`estados_id`),
  ADD KEY `pais_id` (`pais_id`);

--
-- Indices de la tabla `familia`
--
ALTER TABLE `familia`
  ADD PRIMARY KEY (`id_familia`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `grupos_cliente`
--
ALTER TABLE `grupos_cliente`
  ADD PRIMARY KEY (`id_grupos_cliente`);

--
-- Indices de la tabla `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD PRIMARY KEY (`id_grupos_usuarios`);

--
-- Indices de la tabla `historial_pagos_clientes`
--
ALTER TABLE `historial_pagos_clientes`
  ADD PRIMARY KEY (`historial_id`),
  ADD KEY `credito_id` (`credito_id`),
  ADD KEY `recibo_id` (`recibo_id`);

--
-- Indices de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  ADD PRIMARY KEY (`id_impuesto`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `condicion_pago` (`condicion_pago`),
  ADD KEY `int_Proveedor_id` (`int_Proveedor_id`),
  ADD KEY `nUsuCodigo` (`nUsuCodigo`),
  ADD KEY `local_id` (`local_id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_local` (`id_local`),
  ADD KEY `id_unidad` (`id_unidad`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`nkardex_id`);

--
-- Indices de la tabla `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lineas`
--
ALTER TABLE `lineas`
  ADD PRIMARY KEY (`id_linea`);

--
-- Indices de la tabla `local`
--
ALTER TABLE `local`
  ADD PRIMARY KEY (`int_local_id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indices de la tabla `opcion`
--
ALTER TABLE `opcion`
  ADD PRIMARY KEY (`nOpcion`);

--
-- Indices de la tabla `opcion_grupo`
--
ALTER TABLE `opcion_grupo`
  ADD KEY `grupo` (`grupo`),
  ADD KEY `Opcion` (`Opcion`);

--
-- Indices de la tabla `pagos_ingreso`
--
ALTER TABLE `pagos_ingreso`
  ADD PRIMARY KEY (`pagoingreso_id`),
  ADD KEY `pagoingreso_ingreso_id` (`pagoingreso_ingreso_id`),
  ADD KEY `recibo_id` (`recibo_id`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id_pais`);

--
-- Indices de la tabla `paquete_has_prod`
--
ALTER TABLE `paquete_has_prod`
  ADD KEY `paquete_id` (`paquete_id`),
  ADD KEY `prod_id` (`prod_id`),
  ADD KEY `unidad_id` (`unidad_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `produto_grupo` (`produto_grupo`),
  ADD KEY `producto_tipo` (`producto_tipo`),
  ADD KEY `producto_clasificacion` (`producto_clasificacion`),
  ADD KEY `producto_proveedor` (`producto_proveedor`),
  ADD KEY `producto_impuesto` (`producto_impuesto`),
  ADD KEY `producto_ubicacion_fisica` (`producto_ubicacion_fisica`);

--
-- Indices de la tabla `producto_codigo_barra`
--
ALTER TABLE `producto_codigo_barra`
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `producto_has_componente`
--
ALTER TABLE `producto_has_componente`
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `componente_id` (`componente_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `proveedor_tipo` (`proveedor_tipo`),
  ADD KEY `proveedor_regimen` (`proveedor_regimen`),
  ADD KEY `proveedor_ciudad` (`proveedor_ciudad`);

--
-- Indices de la tabla `recibo_pago_cliente`
--
ALTER TABLE `recibo_pago_cliente`
  ADD PRIMARY KEY (`recibo_id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `banco` (`banco`),
  ADD KEY `metodo` (`metodo`);

--
-- Indices de la tabla `recibo_pago_proveedor`
--
ALTER TABLE `recibo_pago_proveedor`
  ADD PRIMARY KEY (`recibo_id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `banco` (`banco`),
  ADD KEY `metodo_pago` (`metodo_pago`);

--
-- Indices de la tabla `regimen`
--
ALTER TABLE `regimen`
  ADD PRIMARY KEY (`regimen_id`);

--
-- Indices de la tabla `resolucion_dian`
--
ALTER TABLE `resolucion_dian`
  ADD PRIMARY KEY (`resolucion_id`);

--
-- Indices de la tabla `status_caja`
--
ALTER TABLE `status_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cajero` (`cajero`),
  ADD KEY `caja_id` (`caja_id`);

--
-- Indices de la tabla `tipo_anulacion`
--
ALTER TABLE `tipo_anulacion`
  ADD PRIMARY KEY (`tipo_anulacion_id`);

--
-- Indices de la tabla `tipo_devolucion`
--
ALTER TABLE `tipo_devolucion`
  ADD PRIMARY KEY (`tipo_devolucion_id`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`tipo_prod_id`);

--
-- Indices de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  ADD PRIMARY KEY (`tipo_proveedor_id`);

--
-- Indices de la tabla `tipo_venta`
--
ALTER TABLE `tipo_venta`
  ADD PRIMARY KEY (`tipo_venta_id`),
  ADD KEY `condicion_pago` (`condicion_pago`);

--
-- Indices de la tabla `ubicacion_fisica`
--
ALTER TABLE `ubicacion_fisica`
  ADD PRIMARY KEY (`ubicacion_id`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id_unidad`);

--
-- Indices de la tabla `unidades_has_precio`
--
ALTER TABLE `unidades_has_precio`
  ADD KEY `id_condiciones_pago` (`id_condiciones_pago`),
  ADD KEY `id_unidad` (`id_unidad`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `unidades_has_producto`
--
ALTER TABLE `unidades_has_producto`
  ADD KEY `id_unidad` (`id_unidad`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`nUsuCodigo`),
  ADD KEY `grupo` (`grupo`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `cajero_id` (`cajero_id`),
  ADD KEY `caja_id` (`caja_id`),
  ADD KEY `local_id` (`local_id`),
  ADD KEY `venta_tipo` (`venta_tipo`);

--
-- Indices de la tabla `venta_anular`
--
ALTER TABLE `venta_anular`
  ADD PRIMARY KEY (`nVenAnularCodigo`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `tipo_anulación` (`tipo_anulación`),
  ADD KEY `nUsuCodigo` (`nUsuCodigo`);

--
-- Indices de la tabla `venta_backup`
--
ALTER TABLE `venta_backup`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `cajero_id` (`cajero_id`),
  ADD KEY `caja_id` (`caja_id`),
  ADD KEY `local_id` (`local_id`),
  ADD KEY `venta_tipo` (`venta_tipo`);

--
-- Indices de la tabla `venta_estatus`
--
ALTER TABLE `venta_estatus`
  ADD PRIMARY KEY (`estatus_id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `vendedor_id` (`vendedor_id`);

--
-- Indices de la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`zona_id`),
  ADD KEY `ciudad_id` (`ciudad_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `afiliado`
--
ALTER TABLE `afiliado`
  MODIFY `afiliado_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ajustedetalle`
--
ALTER TABLE `ajustedetalle`
  MODIFY `id_ajustedetalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT de la tabla `ajusteinventario`
--
ALTER TABLE `ajusteinventario`
  MODIFY `id_ajusteinventario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `banco_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `caja_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `ciudad_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `clasificacion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `columnas`
--
ALTER TABLE `columnas`
  MODIFY `id_columna` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `componente_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `condiciones_pago`
--
ALTER TABLE `condiciones_pago`
  MODIFY `id_condiciones` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `config_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `detalleingreso`
--
ALTER TABLE `detalleingreso`
  MODIFY `id_detalle_ingreso` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `detalleingreso_especial`
--
ALTER TABLE `detalleingreso_especial`
  MODIFY `id_detalle_especial` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalle_ingreso_unidad`
--
ALTER TABLE `detalle_ingreso_unidad`
  MODIFY `detalle_ingreso_unidad_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT de la tabla `detalle_venta_backup`
--
ALTER TABLE `detalle_venta_backup`
  MODIFY `id_detalle` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT de la tabla `documentos_inventarios`
--
ALTER TABLE `documentos_inventarios`
  MODIFY `documento_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `documento_venta`
--
ALTER TABLE `documento_venta`
  MODIFY `id_tipo_documento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT de la tabla `droguerias_relacionadas`
--
ALTER TABLE `droguerias_relacionadas`
  MODIFY `drogueria_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `estados_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `familia`
--
ALTER TABLE `familia`
  MODIFY `id_familia` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `grupos_cliente`
--
ALTER TABLE `grupos_cliente`
  MODIFY `id_grupos_cliente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  MODIFY `id_grupos_usuarios` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `historial_pagos_clientes`
--
ALTER TABLE `historial_pagos_clientes`
  MODIFY `historial_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  MODIFY `id_impuesto` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `id_ingreso` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
  MODIFY `nkardex_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=976;
--
-- AUTO_INCREMENT de la tabla `keys`
--
ALTER TABLE `keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `lineas`
--
ALTER TABLE `lineas`
  MODIFY `id_linea` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `local`
--
ALTER TABLE `local`
  MODIFY `int_local_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `opcion`
--
ALTER TABLE `opcion`
  MODIFY `nOpcion` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT de la tabla `opcion_grupo`
--
ALTER TABLE `opcion_grupo`
  MODIFY `grupo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `pagos_ingreso`
--
ALTER TABLE `pagos_ingreso`
  MODIFY `pagoingreso_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `id_pais` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;
--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `recibo_pago_cliente`
--
ALTER TABLE `recibo_pago_cliente`
  MODIFY `recibo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `recibo_pago_proveedor`
--
ALTER TABLE `recibo_pago_proveedor`
  MODIFY `recibo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `regimen`
--
ALTER TABLE `regimen`
  MODIFY `regimen_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `resolucion_dian`
--
ALTER TABLE `resolucion_dian`
  MODIFY `resolucion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `status_caja`
--
ALTER TABLE `status_caja`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `tipo_anulacion`
--
ALTER TABLE `tipo_anulacion`
  MODIFY `tipo_anulacion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipo_devolucion`
--
ALTER TABLE `tipo_devolucion`
  MODIFY `tipo_devolucion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `tipo_prod_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  MODIFY `tipo_proveedor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipo_venta`
--
ALTER TABLE `tipo_venta`
  MODIFY `tipo_venta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `ubicacion_fisica`
--
ALTER TABLE `ubicacion_fisica`
  MODIFY `ubicacion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id_unidad` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `nUsuCodigo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `venta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT de la tabla `venta_anular`
--
ALTER TABLE `venta_anular`
  MODIFY `nVenAnularCodigo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `venta_backup`
--
ALTER TABLE `venta_backup`
  MODIFY `venta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT de la tabla `venta_estatus`
--
ALTER TABLE `venta_estatus`
  MODIFY `estatus_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `zona_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `afiliado_descuentos`
--
ALTER TABLE `afiliado_descuentos`
  ADD CONSTRAINT `afiliado_descuentos_ibfk_1` FOREIGN KEY (`tipo_prod_id`) REFERENCES `tipo_producto` (`tipo_prod_id`),
  ADD CONSTRAINT `afiliado_descuentos_ibfk_2` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `afiliado_descuentos_ibfk_3` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`afiliado_id`);

--
-- Filtros para la tabla `ajustedetalle`
--
ALTER TABLE `ajustedetalle`
  ADD CONSTRAINT `ajustedetalle_ibfk_1` FOREIGN KEY (`id_ajusteinventario`) REFERENCES `ajusteinventario` (`id_ajusteinventario`),
  ADD CONSTRAINT `ajustedetalle_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_inventario`);

--
-- Filtros para la tabla `ajusteinventario`
--
ALTER TABLE `ajusteinventario`
  ADD CONSTRAINT `ajusteinventario_ibfk_1` FOREIGN KEY (`tipo_ajuste`) REFERENCES `documentos_inventarios` (`documento_id`),
  ADD CONSTRAINT `ajusteinventario_ibfk_2` FOREIGN KEY (`local_id`) REFERENCES `local` (`int_local_id`),
  ADD CONSTRAINT `ajusteinventario_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nUsuCodigo`);

--
-- Filtros para la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`estados_id`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`ciudad_id`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_zona`) REFERENCES `zonas` (`zona_id`),
  ADD CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`grupo_id`) REFERENCES `grupos_cliente` (`id_grupos_cliente`),
  ADD CONSTRAINT `cliente_ibfk_4` FOREIGN KEY (`afiliado`) REFERENCES `afiliado` (`afiliado_id`);

--
-- Filtros para la tabla `detalleingreso`
--
ALTER TABLE `detalleingreso`
  ADD CONSTRAINT `detalleingreso_ibfk_1` FOREIGN KEY (`id_ingreso`) REFERENCES `ingreso` (`id_ingreso`),
  ADD CONSTRAINT `detalleingreso_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `detalleingreso_especial`
--
ALTER TABLE `detalleingreso_especial`
  ADD CONSTRAINT `detalleingreso_especial_ibfk_1` FOREIGN KEY (`detalle_ingreso_id`) REFERENCES `detalleingreso` (`id_detalle_ingreso`),
  ADD CONSTRAINT `detalleingreso_especial_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `detalleingreso_especial_ibfk_3` FOREIGN KEY (`producto_id_especial`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `detalleingreso_especial_ibfk_4` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`);

--
-- Filtros para la tabla `detalle_ingreso_unidad`
--
ALTER TABLE `detalle_ingreso_unidad`
  ADD CONSTRAINT `detalle_ingreso_unidad_ibfk_1` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `detalle_ingreso_unidad_ibfk_2` FOREIGN KEY (`detalle_ingreso_id`) REFERENCES `detalleingreso` (`id_detalle_ingreso`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`venta_id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `detalle_venta_backup`
--
ALTER TABLE `detalle_venta_backup`
  ADD CONSTRAINT `detalle_venta_backup_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta_backup` (`venta_id`),
  ADD CONSTRAINT `detalle_venta_backup_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `detalle_venta_unidad`
--
ALTER TABLE `detalle_venta_unidad`
  ADD CONSTRAINT `detalle_venta_unidad_ibfk_1` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `detalle_venta_unidad_ibfk_2` FOREIGN KEY (`detalle_venta_id`) REFERENCES `detalle_venta` (`id_detalle`);

--
-- Filtros para la tabla `detalle_venta_unidad_backup`
--
ALTER TABLE `detalle_venta_unidad_backup`
  ADD CONSTRAINT `detalle_venta_unidad_backup_ibfk_1` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `detalle_venta_unidad_backup_ibfk_2` FOREIGN KEY (`detalle_venta_id`) REFERENCES `detalle_venta_backup` (`id_detalle`);

--
-- Filtros para la tabla `documento_venta`
--
ALTER TABLE `documento_venta`
  ADD CONSTRAINT `documento_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`venta_id`);

--
-- Filtros para la tabla `estados`
--
ALTER TABLE `estados`
  ADD CONSTRAINT `estados_ibfk_1` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id_pais`);

--
-- Filtros para la tabla `historial_pagos_clientes`
--
ALTER TABLE `historial_pagos_clientes`
  ADD CONSTRAINT `historial_pagos_clientes_ibfk_1` FOREIGN KEY (`credito_id`) REFERENCES `venta` (`venta_id`),
  ADD CONSTRAINT `historial_pagos_clientes_ibfk_2` FOREIGN KEY (`recibo_id`) REFERENCES `recibo_pago_cliente` (`recibo_id`);

--
-- Filtros para la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD CONSTRAINT `ingreso_ibfk_1` FOREIGN KEY (`condicion_pago`) REFERENCES `condiciones_pago` (`id_condiciones`),
  ADD CONSTRAINT `ingreso_ibfk_2` FOREIGN KEY (`int_Proveedor_id`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `ingreso_ibfk_3` FOREIGN KEY (`nUsuCodigo`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `ingreso_ibfk_4` FOREIGN KEY (`local_id`) REFERENCES `local` (`int_local_id`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_local`) REFERENCES `local` (`int_local_id`),
  ADD CONSTRAINT `inventario_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidades` (`id_unidad`);

--
-- Filtros para la tabla `opcion_grupo`
--
ALTER TABLE `opcion_grupo`
  ADD CONSTRAINT `opcion_grupo_ibfk_1` FOREIGN KEY (`grupo`) REFERENCES `grupos_usuarios` (`id_grupos_usuarios`),
  ADD CONSTRAINT `opcion_grupo_ibfk_2` FOREIGN KEY (`Opcion`) REFERENCES `opcion` (`nOpcion`);

--
-- Filtros para la tabla `pagos_ingreso`
--
ALTER TABLE `pagos_ingreso`
  ADD CONSTRAINT `pagos_ingreso_ibfk_1` FOREIGN KEY (`pagoingreso_ingreso_id`) REFERENCES `ingreso` (`id_ingreso`),
  ADD CONSTRAINT `pagos_ingreso_ibfk_2` FOREIGN KEY (`recibo_id`) REFERENCES `recibo_pago_proveedor` (`recibo_id`);

--
-- Filtros para la tabla `paquete_has_prod`
--
ALTER TABLE `paquete_has_prod`
  ADD CONSTRAINT `paquete_has_prod_ibfk_1` FOREIGN KEY (`paquete_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `paquete_has_prod_ibfk_2` FOREIGN KEY (`prod_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `paquete_has_prod_ibfk_3` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id_unidad`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`produto_grupo`) REFERENCES `grupos` (`id_grupo`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`producto_tipo`) REFERENCES `tipo_producto` (`tipo_prod_id`),
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`producto_clasificacion`) REFERENCES `clasificacion` (`clasificacion_id`),
  ADD CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`producto_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`producto_impuesto`) REFERENCES `impuestos` (`id_impuesto`),
  ADD CONSTRAINT `producto_ibfk_6` FOREIGN KEY (`producto_ubicacion_fisica`) REFERENCES `ubicacion_fisica` (`ubicacion_id`);

--
-- Filtros para la tabla `producto_codigo_barra`
--
ALTER TABLE `producto_codigo_barra`
  ADD CONSTRAINT `producto_codigo_barra_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `producto_has_componente`
--
ALTER TABLE `producto_has_componente`
  ADD CONSTRAINT `producto_has_componente_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `producto_has_componente_ibfk_2` FOREIGN KEY (`componente_id`) REFERENCES `componentes` (`componente_id`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`proveedor_tipo`) REFERENCES `tipo_proveedor` (`tipo_proveedor_id`),
  ADD CONSTRAINT `proveedor_ibfk_2` FOREIGN KEY (`proveedor_regimen`) REFERENCES `regimen` (`regimen_id`),
  ADD CONSTRAINT `proveedor_ibfk_3` FOREIGN KEY (`proveedor_ciudad`) REFERENCES `ciudades` (`ciudad_id`);

--
-- Filtros para la tabla `recibo_pago_cliente`
--
ALTER TABLE `recibo_pago_cliente`
  ADD CONSTRAINT `recibo_pago_cliente_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `recibo_pago_cliente_ibfk_2` FOREIGN KEY (`banco`) REFERENCES `banco` (`banco_id`),
  ADD CONSTRAINT `recibo_pago_cliente_ibfk_3` FOREIGN KEY (`metodo`) REFERENCES `metodos_pago` (`id_metodo`);

--
-- Filtros para la tabla `recibo_pago_proveedor`
--
ALTER TABLE `recibo_pago_proveedor`
  ADD CONSTRAINT `recibo_pago_proveedor_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `recibo_pago_proveedor_ibfk_2` FOREIGN KEY (`banco`) REFERENCES `banco` (`banco_id`),
  ADD CONSTRAINT `recibo_pago_proveedor_ibfk_3` FOREIGN KEY (`metodo_pago`) REFERENCES `metodos_pago` (`id_metodo`);

--
-- Filtros para la tabla `status_caja`
--
ALTER TABLE `status_caja`
  ADD CONSTRAINT `status_caja_ibfk_1` FOREIGN KEY (`cajero`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `status_caja_ibfk_2` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`);

--
-- Filtros para la tabla `tipo_venta`
--
ALTER TABLE `tipo_venta`
  ADD CONSTRAINT `tipo_venta_ibfk_1` FOREIGN KEY (`condicion_pago`) REFERENCES `condiciones_pago` (`id_condiciones`);

--
-- Filtros para la tabla `unidades_has_precio`
--
ALTER TABLE `unidades_has_precio`
  ADD CONSTRAINT `unidades_has_precio_ibfk_1` FOREIGN KEY (`id_condiciones_pago`) REFERENCES `condiciones_pago` (`id_condiciones`),
  ADD CONSTRAINT `unidades_has_precio_ibfk_2` FOREIGN KEY (`id_unidad`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `unidades_has_precio_ibfk_3` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `unidades_has_producto`
--
ALTER TABLE `unidades_has_producto`
  ADD CONSTRAINT `unidades_has_producto_ibfk_1` FOREIGN KEY (`id_unidad`) REFERENCES `unidades` (`id_unidad`),
  ADD CONSTRAINT `unidades_has_producto_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`grupo`) REFERENCES `grupos_usuarios` (`id_grupos_usuarios`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`cajero_id`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `venta_ibfk_4` FOREIGN KEY (`caja_id`) REFERENCES `status_caja` (`id`),
  ADD CONSTRAINT `venta_ibfk_5` FOREIGN KEY (`local_id`) REFERENCES `local` (`int_local_id`),
  ADD CONSTRAINT `venta_ibfk_6` FOREIGN KEY (`venta_tipo`) REFERENCES `tipo_venta` (`tipo_venta_id`);

--
-- Filtros para la tabla `venta_anular`
--
ALTER TABLE `venta_anular`
  ADD CONSTRAINT `venta_anular_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`venta_id`),
  ADD CONSTRAINT `venta_anular_ibfk_2` FOREIGN KEY (`tipo_anulación`) REFERENCES `tipo_anulacion` (`tipo_anulacion_id`),
  ADD CONSTRAINT `venta_anular_ibfk_3` FOREIGN KEY (`nUsuCodigo`) REFERENCES `usuario` (`nUsuCodigo`);

--
-- Filtros para la tabla `venta_backup`
--
ALTER TABLE `venta_backup`
  ADD CONSTRAINT `venta_backup_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venta_backup_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `venta_backup_ibfk_3` FOREIGN KEY (`cajero_id`) REFERENCES `usuario` (`nUsuCodigo`),
  ADD CONSTRAINT `venta_backup_ibfk_4` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  ADD CONSTRAINT `venta_backup_ibfk_5` FOREIGN KEY (`local_id`) REFERENCES `local` (`int_local_id`),
  ADD CONSTRAINT `venta_backup_ibfk_6` FOREIGN KEY (`venta_tipo`) REFERENCES `tipo_venta` (`tipo_venta_id`);

--
-- Filtros para la tabla `venta_estatus`
--
ALTER TABLE `venta_estatus`
  ADD CONSTRAINT `venta_estatus_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`),
  ADD CONSTRAINT `venta_estatus_ibfk_2` FOREIGN KEY (`vendedor_id`) REFERENCES `usuario` (`nUsuCodigo`);

--
-- Filtros para la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD CONSTRAINT `zonas_ibfk_1` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`ciudad_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
