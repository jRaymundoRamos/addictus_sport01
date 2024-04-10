-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-01-2024 a las 02:04:53
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_tiendacarrito`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` bigint(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `portada` varchar(100) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `ruta` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `nombre`, `descripcion`, `portada`, `datecreated`, `ruta`, `status`) VALUES
(1, 'Ropa artesanal', 'Ropa tejida a mano, por artesanos mexicanos', 'img_03f9cc9d021e0113346d289b8a116e88.jpg', '2020-10-23 03:14:08', 'ropa-artesanal', 1),
(2, 'Alebrijes', 'Animales increíbles, realizados a mano', 'img_0ecd88968056a3e87b6998a532c30a4d.jpg', '2020-10-23 03:17:26', 'alebrijes', 1),
(3, 'Juguetes', 'Juguetes artesanales de alta calidad', 'img_a15ac40fd8d2882b5b1caad38dc7df28.jpg', '2020-10-23 03:17:42', 'juguetes', 1),
(4, 'Vajillas', 'Artículos de la más alta calidad', 'img_3908af8f46dc42258f60d73b54aaadde.jpg', '2020-10-28 03:45:12', 'vajillas', 1),
(5, 'Artículos tejidos', 'Artesanías hecha a mano, con materiales hechos en México', 'img_a1a2b6980c4a726d1ae1a8b4dd69f6c0.jpg', '2020-10-30 03:05:09', 'articulos-tejidos', 1),
(6, 'Accesorios', 'Variedad de elementos artesanales hechos a mano', 'img_71da7c5d3741ffc250b4ab9cabf23ae5.jpg', '2020-11-14 00:21:15', 'accesorios', 1),
(7, 'Categoria ejemplo', 'Descripción categoría ejemplo', 'portada_categoria.png', '2020-12-05 22:38:27', 'categoria-ejemplo', 0),
(8, 'Caterogía 20', 'Descripción', 'portada_categoria.png', '2020-12-05 23:00:16', 'caterogia-20', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` bigint(20) NOT NULL,
  `pedidoid` bigint(20) NOT NULL,
  `productoid` bigint(20) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `id` bigint(20) NOT NULL,
  `personaid` bigint(20) NOT NULL,
  `productoid` bigint(20) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `transaccionid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `id` bigint(20) NOT NULL,
  `productoid` bigint(20) NOT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `imagen`
--

INSERT INTO `imagen` (`id`, `productoid`, `img`) VALUES
(3, 3, 'pro_e702903506bd14ecc0e5645cc8a308d2.jpg'),
(4, 3, 'pro_c3abd10d62fa7b01e8dfd61e18118913.jpg'),
(19, 2, 'pro_25bff00db4ed6a2e028cb28195cfa649.jpg'),
(20, 2, 'pro_75f4d282b2735d59287c551e6c2a094e.jpg'),
(37, 13, 'pro_0408aa8573df733abbef3939e40432ce.jpg'),
(38, 12, 'pro_7954f2e609798d5aac386b393969af41.jpg'),
(39, 12, 'pro_f8775196f5a00cb79e634c588f961ff9.jpg'),
(43, 9, 'pro_208abf70ec65c48e46d0f8478468e3ee.jpg'),
(46, 9, 'pro_f7a5e44decc8f6530e6a4e3c3797ba7d.jpg'),
(47, 8, 'pro_c1d4707046ca4d12c1103d5f725148bf.jpg'),
(48, 8, 'pro_024e27353233cd178af402932200e5fb.jpg'),
(49, 11, 'pro_6b3771eeefe9343f6060350a5cb7f457.jpg'),
(50, 10, 'pro_86a6b73676da8b9796208278e31a39ce.jpg'),
(51, 7, 'pro_7acb5771b8c41680a980933b3e94e208.jpg'),
(53, 6, 'pro_ff7b5da1104ab6b0072aa24042f4c250.jpg'),
(54, 5, 'pro_bf1a016bff48d3f73b789b395fc86711.jpg'),
(55, 4, 'pro_39a6d839da5e410e5d75271c1b70cd08.jpg'),
(56, 4, 'pro_5e247180c1c5eb0fb78b67e97bd886e3.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `idmodulo` bigint(20) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`idmodulo`, `titulo`, `descripcion`, `status`) VALUES
(1, 'Dashboard', 'Dashboard', 1),
(2, 'Usuarios', 'Usuarios del sistema', 1),
(3, 'Clientes', 'Clientes de tienda', 1),
(4, 'Productos', 'Todos los productos', 1),
(5, 'Pedidos', 'Pedidos', 1),
(6, 'Caterogías', 'Caterogías Productos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idpedido` bigint(20) NOT NULL,
  `personaid` bigint(20) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `monto` decimal(11,2) NOT NULL,
  `tipopagoid` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `idpermiso` bigint(20) NOT NULL,
  `rolid` bigint(20) NOT NULL,
  `moduloid` bigint(20) NOT NULL,
  `r` int(11) NOT NULL DEFAULT 0,
  `w` int(11) NOT NULL DEFAULT 0,
  `u` int(11) NOT NULL DEFAULT 0,
  `d` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idpermiso`, `rolid`, `moduloid`, `r`, `w`, `u`, `d`) VALUES
(602, 1, 1, 1, 0, 0, 0),
(603, 1, 2, 1, 1, 1, 1),
(604, 1, 3, 1, 1, 1, 1),
(605, 1, 4, 1, 1, 1, 1),
(606, 1, 5, 1, 1, 1, 1),
(607, 1, 6, 1, 1, 1, 1),
(608, 3, 1, 1, 0, 0, 0),
(609, 3, 2, 1, 0, 0, 0),
(610, 3, 3, 0, 0, 0, 0),
(611, 3, 4, 1, 1, 1, 0),
(612, 3, 5, 0, 0, 0, 0),
(613, 3, 6, 1, 0, 0, 0),
(614, 7, 1, 1, 0, 0, 0),
(615, 7, 2, 0, 0, 0, 0),
(616, 7, 3, 0, 0, 0, 0),
(617, 7, 4, 0, 0, 0, 0),
(618, 7, 5, 0, 0, 0, 0),
(619, 7, 6, 0, 0, 0, 0),
(620, 4, 1, 1, 0, 0, 0),
(621, 4, 2, 1, 0, 0, 0),
(622, 4, 3, 1, 0, 0, 0),
(623, 4, 4, 1, 0, 0, 0),
(624, 4, 5, 0, 0, 0, 0),
(625, 4, 6, 0, 0, 0, 0),
(626, 2, 1, 1, 0, 0, 0),
(627, 2, 2, 1, 0, 1, 1),
(628, 2, 3, 1, 0, 1, 1),
(629, 2, 4, 1, 0, 0, 1),
(630, 2, 5, 0, 0, 0, 0),
(631, 2, 6, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `idpersona` bigint(20) NOT NULL,
  `identificacion` varchar(30) DEFAULT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `password` varchar(75) NOT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `nombrefiscal` varchar(80) DEFAULT NULL,
  `direccionfiscal` varchar(100) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `rolid` bigint(20) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`idpersona`, `identificacion`, `nombres`, `apellidos`, `telefono`, `email_user`, `password`, `nit`, `nombrefiscal`, `direccionfiscal`, `token`, `rolid`, `datecreated`, `status`) VALUES
(5, '4654654', 'Alphonso', 'Davis', 12121221, 'alphonso_veloz@bayern.com', 'be63ad947e82808780278e044bcd0267a6ac6b3cd1abdb107cc10b445a182eb0', '', '', '', '', 2, '2023-08-22 00:35:04', 1),
(6, '8465484', 'Alex', 'Ferguson', 222222222, 'alex_ferguson@manchesterunited.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '', '', '', '', 3, '2023-08-22 00:48:50', 1),
(7, '54684987', 'Francisco', 'Palencia', 6654456545, 'francisco@info.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '', '', '', '', 2, '2023-08-22 01:55:57', 1),
(8, '54646849844', 'Carlos', 'Vela', 654687454, 'axel@info.com', '993fdea29acd1f7c6a6423c038601b175bb282382fc85b306a85f134fff4a63e', '', '', '', '', 3, '2023-09-07 01:30:52', 1),
(22, '13546813', 'Raymundo', 'Ramos', 5566229305, 'raymundo@info.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', NULL, NULL, NULL, NULL, 1, '2024-01-13 15:08:50', 1),
(23, '1231234758', 'Victor', 'Luna', 123546874, 'victor@info.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '5468713', 'RFC', 'cuatro vientos ixtapaluca', NULL, 7, '2024-01-13 16:28:04', 1),
(24, '445643513', 'La Chilindrina', 'Gomez', 646813, 'lachilindrina@gmail.com', 'ec5be6aa281d96b2080625c33e30bc6e982d73f48ae718f5f86a09d2005781b5', NULL, NULL, NULL, NULL, 2, '2024-01-13 16:50:22', 1),
(25, '311468749', 'Juan Pablo', 'El Papa', 468138546, 'elpapa_juanpablo2@religioso.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', NULL, NULL, NULL, NULL, 7, '2024-01-13 16:51:19', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL,
  `categoriaid` bigint(20) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `ruta` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idproducto`, `categoriaid`, `codigo`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `datecreated`, `ruta`, `status`) VALUES
(1, 4, '45684545', 'Producto 1', '<p>Descripci&oacute;n producto 1</p>', 200.00, 10, '', '2020-11-15 00:57:57', 'producto-1', 0),
(2, 3, '465456465', 'Producto 1', '<p>Descripci&oacute;n producto</p> <ul> <li>Uno</li> <li>Dos</li> </ul>', 110.00, 10, '', '2020-11-15 03:13:35', 'producto-1', 0),
(3, 1, '4654654', 'Producto Uno', '<p>Descripci&oacute;n producto uno</p> <table style=\"border-collapse: collapse; width: 100%;\" border=\"1\"> <tbody> <tr> <td style=\"width: 48.0244%;\">N&uacute;mero</td> <td style=\"width: 48.022%;\">Descripc&iacute;&oacute;n</td> </tr> <tr> <td style=\"width: 48.0244%;\">Uno</td> <td style=\"width: 48.022%;\">Peque&ntilde;o</td> </tr> <tr> <td style=\"width: 48.0244%;\">Dos</td> <td style=\"width: 48.022%;\">Mediano</td> </tr> <tr> <td style=\"width: 48.0244%;\">Tres</td> <td style=\"width: 48.022%;\">Grande</td> </tr> </tbody> </table>', 200.00, 50, '', '2020-11-15 03:19:15', 'producto-uno', 0),
(4, 2, '45654654', 'Vajillas de barro', '<p>Artesania elaborada completamente a mano, vajilla de barro con terminaciones hechas a mano</p>', 780.00, 10, '', '2020-11-23 02:59:44', 'vajillas-de-barro', 1),
(5, 4, '6546546545', 'Vajillas de porcelana', '<p>Vajillas elaboradas y pintadas a mano, todas nuestras vajillas son productos de calidad elaborados en zacatecas</p>', 1000.00, 10, '', '2020-11-23 03:22:35', 'vajillas-de-porcelana', 1),
(6, 1, '646546547877', 'Sarapes tejidos', '<p>Sarapes tejidos a mano, elaborados con materiales 100% mexicanos</p>', 350.00, 10, '', '2020-11-23 03:38:55', 'sarapes-tejidos', 1),
(7, 1, '65465454', 'Camisas tejidas a mano', '<p>Camisas tejidas a mano, elaboradas en baja california para una mayor c&oacute;modidad en los climas c&aacute;lidos</p>', 100.00, 10, '', '2020-11-23 03:39:59', 'camisas-tejidas-a-mano', 1),
(8, 3, '6546545', 'Muñecas mexicanas', '<p>Mu&ntilde;ecas tejidas elaboradas por manos mexicanas desde el estado de oaxaca</p>', 85.00, 10, '', '2020-11-23 03:43:29', 'munecas-mexicanas', 1),
(9, 4, '546455456', 'Jarrones de cerámica', '<p>Todos nuestros jarrones son elaborados desde cero, cuidadosamente&nbsp;</p> <p>Elaborados desde micho&aacute;can para toda la r&eacute;publica</p>', 180.00, 50, '', '2020-12-01 12:52:33', 'jarrones-de-ceramica', 1),
(10, 5, '654546544', 'Jarrones tejidos de palma', '<p>Todos los jarrones son tejidos con palmas y elaborados por artesanos de tijuana&nbsp;</p> <p>Cada jarr&oacute;n tarda de 2 a 3 d&iacute;as elaborarse.</p>', 145.00, 9, '', '2020-12-02 03:52:09', 'jarrones-tejidos-de-palma', 1),
(11, 3, '4657897897', 'Valeros de madera', '<p>Todos los valeros son tallados y pintados a mano, desarrollados con madre del estado de guanajuato</p>', 100.00, 50, '', '2020-12-06 02:30:02', 'valeros-de-madera', 1),
(12, 2, '4894647878', 'Alebrijes mágicos', '<p>Son alebrijes personalizables los cuales van a ser a gusto y a placer del cliente&nbsp;</p> <p>Cada alebrije es desarrollado con productos mexicanos</p> <p><em>Tardan aproximadamente de 3 a 4 semanas en realizarse&nbsp;</em></p>', 110.00, 10, '', '2020-12-11 02:23:22', 'alebrijes-magicos', 1),
(13, 5, '4654654564', 'Jarrones tejidos', '<p>Los jarrones tejidos a mano, son creados por manos tlaxcaltecas<br /><strong>Nuestros productos son hechos 100% a mano</strong></p>', 125.00, 5, '', '2023-12-18 00:44:28', 'jarrones-tejidos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` bigint(20) NOT NULL,
  `nombrerol` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `nombrerol`, `descripcion`, `status`) VALUES
(1, 'Administrador', 'Acceso a todo el sistema', 1),
(2, 'Supervisores', 'Supervisor de tienda', 1),
(3, 'Vendedores', 'Acceso a módulo ventas', 1),
(4, 'Servicio al cliente', 'Servicio al cliente sistema', 1),
(7, 'Cliente', 'Clientes tienda', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopago`
--

CREATE TABLE `tipopago` (
  `idtipopago` bigint(20) NOT NULL,
  `tipopago` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Volcado de datos para la tabla `tipopago`
--

INSERT INTO `tipopago` (`idtipopago`, `tipopago`, `status`) VALUES
(1, 'PayPal', 1),
(2, 'Efectivo', 1),
(3, 'Tarjeta', 1),
(4, 'Cheque', 1),
(5, 'Despósito Bancario', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidoid` (`pedidoid`),
  ADD KEY `productoid` (`productoid`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productoid` (`productoid`),
  ADD KEY `personaid` (`personaid`);

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productoid` (`productoid`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`idmodulo`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `personaid` (`personaid`),
  ADD KEY `tipopagoid` (`tipopagoid`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`idpermiso`),
  ADD KEY `rolid` (`rolid`),
  ADD KEY `moduloid` (`moduloid`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`idpersona`),
  ADD KEY `rolid` (`rolid`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD KEY `categoriaid` (`categoriaid`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `tipopago`
--
ALTER TABLE `tipopago`
  ADD PRIMARY KEY (`idtipopago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `imagen`
--
ALTER TABLE `imagen`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `idmodulo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idpedido` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `idpermiso` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=632;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `idpersona` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipopago`
--
ALTER TABLE `tipopago`
  MODIFY `idtipopago` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedidoid`) REFERENCES `pedido` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`productoid`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_1` FOREIGN KEY (`productoid`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `imagen_ibfk_1` FOREIGN KEY (`productoid`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`personaid`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`tipopagoid`) REFERENCES `tipopago` (`idtipopago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`moduloid`) REFERENCES `modulo` (`idmodulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoriaid`) REFERENCES `categoria` (`idcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
