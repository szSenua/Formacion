-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-01-2024 a las 18:47:23
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
-- Base de datos: `cursoscp`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `calcularPuntos` (IN `dni_param` VARCHAR(9))   BEGIN
    DECLARE puntos INT;

    -- Lógica para calcular puntos
    SELECT
        SUM(CASE WHEN coordinadortc THEN 4 ELSE 0 END +
            CASE WHEN grupotc THEN 3 ELSE 0 END +
            CASE WHEN pbilin THEN 3 ELSE 0 END +
            CASE WHEN cargo = 1 THEN 2
                 WHEN cargo = 2 THEN 2
                 WHEN cargo = 3 THEN 2
                 WHEN cargo = 4 THEN 1
                 ELSE 0 END +
            CASE WHEN DATEDIFF(CURDATE(), fechaAlta) > 15 THEN 1 ELSE 0 END +
            CASE WHEN situacion = 'activo' THEN 1 ELSE 0 END)
    INTO puntos
    FROM solicitantes
    WHERE dni = dni_param;

    -- Actualizar puntos en la tabla solicitantes
    UPDATE solicitantes
    SET puntos = puntos
    WHERE dni = dni_param;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(9) DEFAULT NULL,
  `contrasena` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `abierto` tinyint(1) DEFAULT NULL,
  `numeroplazas` int(2) DEFAULT NULL,
  `plazoinscripcion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitantes`
--

CREATE TABLE `solicitantes` (
  `dni` varchar(9) NOT NULL,
  `apellidos` varchar(20) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `contrasena` varchar(9) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `codigocentro` varchar(8) DEFAULT NULL,
  `coordinadortc` tinyint(1) DEFAULT NULL,
  `grupotc` tinyint(1) DEFAULT NULL,
  `nombregrupo` varchar(5) DEFAULT NULL,
  `pbilin` tinyint(1) DEFAULT NULL,
  `cargo` tinyint(1) DEFAULT NULL,
  `nombrecargo` varchar(15) DEFAULT NULL,
  `situacion` enum('activo','inactivo') DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL,
  `especialidad` varchar(50) DEFAULT NULL,
  `puntos` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `solicitantes`
--
DELIMITER $$
CREATE TRIGGER `calcularPuntosAfterInsert` AFTER INSERT ON `solicitantes` FOR EACH ROW BEGIN
    CALL calcularPuntos(NEW.dni);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `dni` varchar(9) NOT NULL,
  `codigocurso` int(6) NOT NULL,
  `fechasolicitud` date DEFAULT NULL,
  `admitido` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `solicitantes`
--
ALTER TABLE `solicitantes`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`dni`,`codigocurso`),
  ADD KEY `codigocurso` (`codigocurso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `solicitantes` (`dni`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`codigocurso`) REFERENCES `cursos` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
