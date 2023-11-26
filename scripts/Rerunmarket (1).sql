-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-11-2023 a las 21:59:49
-- Versión del servidor: 8.0.34-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Rerunmarket`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Anuncios`
--

CREATE TABLE `Anuncios` (
  `idAnuncio` int NOT NULL,
  `idUsuario` int NOT NULL,
  `titulo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `foto` varchar(300) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Anuncios`
--

INSERT INTO `Anuncios` (`idAnuncio`, `idUsuario`, `titulo`, `descripcion`, `precio`, `foto`, `fecha_creacion`) VALUES
(34, 2, 'otra prueba', '&lt;b&gt;anuncio creado con 3 fotos&lt;/b&gt;', '3330.00', '112238e42cd66d4dcd376917eb14c97e.jpg', '2023-11-25 05:53:29'),
(35, 2, 'rtfdgdf', 'dfgdf', '5555.99', 'e6bcd072df6497587d2019d95bc20ea8.webp', '2023-11-25 07:23:58'),
(36, 2, 'dfgfd', 'dfgfdgd', '55.20', '813e493d9775b2ae52301bb8f9e2dd81.jpg', '2023-11-25 09:24:48'),
(37, 14, 'Xiaomi redmi 12', 'Recien comprado nuevo xiaomi redmi 12.&amp;lt;p&amp;gt;108MP, gran angular, selfie.&amp;lt;/p&amp;gt;', '665.95', '5470c3414af22720ed7049ceb97b0922.jpeg', '2023-11-25 09:35:21'),
(38, 14, 'Audi A8', 'Audi A8 del 2021 a estrenar.&amp;amp;nbsp;&amp;lt;p&amp;gt;Llantas de 18&amp;quot;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;Lunas tintadas&amp;lt;/p&amp;gt;', '38500.00', 'f4466458ab8581845f60f95fd0b7fee2.jpg', '2023-11-25 10:00:19'),
(39, 14, 'Yamaha TZR', 'Yamaha TZR negra brillante&amp;lt;p&amp;gt;Para los entusiastas del motor&amp;lt;/p&amp;gt;', '6875.50', '781cd7e69ce2e66e4f04da839759ca21.jpeg', '2023-11-25 10:03:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Fotos`
--

CREATE TABLE `Fotos` (
  `idFoto` int NOT NULL,
  `idAnuncio` int NOT NULL,
  `nombre_foto` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Fotos`
--

INSERT INTO `Fotos` (`idFoto`, `idAnuncio`, `nombre_foto`) VALUES
(56, 34, '69160e963cffd3d395e2f09ae27d79ee.webp'),
(57, 34, '90acc72f6962fcb5a1ad96ba0f96791d.jpeg'),
(58, 34, 'e209bd61930e561ad9465e8d6e649cd4.jpeg'),
(59, 34, 'cffb4c1b868af6cae8aa460b3398baa0.jpeg'),
(60, 34, '10ab66af9e5ced8e0fa241f403ca2172.jpeg'),
(61, 34, '0f20e472621d0db95a73ce819cc7978c.webp'),
(62, 35, '32fac10d38bdef209eb57d3ba473ff8c.jpg'),
(63, 35, '22b705597f50dcbf841ea02351c9b9e2.jpeg'),
(64, 35, '1569621d5b81518523205789c6adc352.jpeg'),
(65, 36, '813e493d9775b2ae52301bb8f9e2dd81.jpg'),
(66, 35, 'e6bcd072df6497587d2019d95bc20ea8.webp'),
(67, 34, '112238e42cd66d4dcd376917eb14c97e.jpg'),
(68, 37, 'fb5e99b51fa199e01431bf074c1a4e48.webp'),
(69, 37, '4991bfca1ab411bce0d249b118d24858.jpeg'),
(70, 37, '43eedd39d733aec7ecd2077c7d8ce047.jpeg'),
(71, 37, 'b0dcd4aaf84045398fe437eadc36b69b.jpeg'),
(72, 37, '034b8211bdad2a57f5810c3f77acde0e.jpeg'),
(73, 37, 'a3d1a43b85ae46a57ed89b3d20e40114.jpeg'),
(74, 37, '92beeac7246521a323f1d0ef025cfb78.jpeg'),
(75, 37, '6d496be04632633c06c54090ecb35c0c.jpeg'),
(76, 37, '1f9ad9bd67708dbc263ddf7315979a2a.jpeg'),
(77, 37, 'dacb08cec34f0a62641240d524bffa0d.jpeg'),
(78, 37, '33c84cf3fe33b1e2b5e2a329d7c6e83e.jpeg'),
(79, 37, '5470c3414af22720ed7049ceb97b0922.jpeg'),
(80, 38, 'f4466458ab8581845f60f95fd0b7fee2.jpg'),
(81, 39, '781cd7e69ce2e66e4f04da839759ca21.jpeg'),
(82, 39, '03880e5ec73adf8bea1259e0c17af6f2.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id` int NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sid` varchar(300) DEFAULT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` int NOT NULL,
  `poblacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id`, `email`, `password`, `sid`, `nombre`, `telefono`, `poblacion`) VALUES
(2, 'test@test.com', '$2y$10$JL9y/X3DzGYXUxMzGzu6GufOScaisdiNmPrqeXKQthiJaXdyItO4m', '10593d24dd61171f5168563e274c9e49', 'test', 4536345, 'tome'),
(14, 'test4@test.com', '$2y$10$izntqXPAaHphRSdugWoAteyNZ5rhedH7ab.VzkR6X/U7LxEu9rp62', 'ac648797a9a876fbec14dac847fe7e41', 'test4', 4353, ''),
(15, 'test5@test.com', '$2y$10$SUxgSdViH1syeEXlDiKZp.j9A4LPWIATMgQwhkRws0mqJFQmQSn/O', '1a15d8f0c492e498b42e6382d2d48f4f', 'admin', 85734958, 'tomelloso');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Anuncios`
--
ALTER TABLE `Anuncios`
  ADD PRIMARY KEY (`idAnuncio`),
  ADD KEY `Anuncios_ibfk_1` (`idUsuario`);

--
-- Indices de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD PRIMARY KEY (`idFoto`),
  ADD KEY `Fotos_ibfk_1` (`idAnuncio`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Anuncios`
--
ALTER TABLE `Anuncios`
  MODIFY `idAnuncio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  MODIFY `idFoto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Anuncios`
--
ALTER TABLE `Anuncios`
  ADD CONSTRAINT `Anuncios_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD CONSTRAINT `Fotos_ibfk_1` FOREIGN KEY (`idAnuncio`) REFERENCES `Anuncios` (`idAnuncio`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
