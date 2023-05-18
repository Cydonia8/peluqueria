-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2023 a las 12:17:39
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `peluqueria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `cliente` bigint(20) NOT NULL,
  `trabajador` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `servicio` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`cliente`, `trabajador`, `fecha`, `hora`, `servicio`) VALUES
(1, 3, '2023-04-29', '09:00:00', 1),
(1, 3, '2023-05-11', '11:30:00', 1),
(1, 6, '2023-05-17', '11:00:00', 1),
(4, 3, '2023-04-28', '18:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descanso`
--

CREATE TABLE `descanso` (
  `dia` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `descanso`
--

INSERT INTO `descanso` (`dia`) VALUES
(0),
(6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id` bigint(20) NOT NULL,
  `m_apertura` time DEFAULT NULL,
  `m_cierre` time DEFAULT NULL,
  `t_apertura` time DEFAULT NULL,
  `t_cierre` time DEFAULT NULL,
  `dia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id`, `m_apertura`, `m_cierre`, `t_apertura`, `t_cierre`, `dia`) VALUES
(1, '09:00:00', '14:00:00', '17:00:00', '20:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(75) NOT NULL,
  `correo` varchar(75) NOT NULL,
  `telefono` char(9) NOT NULL,
  `tipo` bigint(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `f_inicio` date DEFAULT NULL,
  `f_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `correo`, `telefono`, `tipo`, `pass`, `activo`, `f_inicio`, `f_fin`) VALUES
(1, 'admin', 'admin@admin.com', '', 3, 'admin', 1, NULL, NULL),
(3, 'trabajador', 'traba@gmail.com', '612123123', 2, 'traba', 1, NULL, NULL),
(4, 'Pepe', 'pepe@gmail.com', '123123123', 1, 'pepe', 1, NULL, NULL),
(5, 'juan', 'juan@gmail.com', '111111111', 1, 'juan', 1, NULL, NULL),
(6, 'maria', 'maria@gmail.com', '645378912', 2, 'maria', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `realiza`
--

CREATE TABLE `realiza` (
  `empleado` bigint(20) NOT NULL,
  `servicio` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `realiza`
--

INSERT INTO `realiza` (`empleado`, `servicio`) VALUES
(3, 1),
(6, 1),
(6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `duracion` time NOT NULL,
  `precio` float(5,2) DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `duracion`, `precio`, `activo`) VALUES
(1, 'Corte', '00:30:00', 10.00, 1),
(2, 'tinte', '00:45:00', 10.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id`, `nombre`) VALUES
(1, 'Cliente'),
(2, 'Trabajador'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabaja`
--

CREATE TABLE `trabaja` (
  `empleado` bigint(20) NOT NULL,
  `m_inicio` time DEFAULT NULL,
  `m_fin` time DEFAULT NULL,
  `t_inicio` time DEFAULT NULL,
  `t_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabaja`
--

INSERT INTO `trabaja` (`empleado`, `m_inicio`, `m_fin`, `t_inicio`, `t_fin`) VALUES
(3, '10:00:00', '13:00:00', NULL, NULL),
(6, NULL, NULL, '17:30:00', '19:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`cliente`,`trabajador`,`fecha`,`hora`),
  ADD KEY `fk_cit_servicio` (`servicio`),
  ADD KEY `fk_cit_trabajador` (`trabajador`);

--
-- Indices de la tabla `descanso`
--
ALTER TABLE `descanso`
  ADD PRIMARY KEY (`dia`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_per_tipo` (`tipo`);

--
-- Indices de la tabla `realiza`
--
ALTER TABLE `realiza`
  ADD PRIMARY KEY (`empleado`,`servicio`),
  ADD KEY `ce_rea_pers` (`empleado`),
  ADD KEY `ce_rea_serv` (`servicio`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trabaja`
--
ALTER TABLE `trabaja`
  ADD PRIMARY KEY (`empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_cit_cliente` FOREIGN KEY (`cliente`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_cit_servicio` FOREIGN KEY (`servicio`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `fk_cit_trabajador` FOREIGN KEY (`trabajador`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `fk_per_tipo` FOREIGN KEY (`tipo`) REFERENCES `tipos` (`id`);

--
-- Filtros para la tabla `realiza`
--
ALTER TABLE `realiza`
  ADD CONSTRAINT `ce_rea_pers` FOREIGN KEY (`empleado`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `ce_rea_serv` FOREIGN KEY (`servicio`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `trabaja`
--
ALTER TABLE `trabaja`
  ADD CONSTRAINT `fk_tra_per` FOREIGN KEY (`empleado`) REFERENCES `personas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
