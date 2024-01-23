-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-01-2024 a las 14:55:03
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarResultadosProcedure` (`dni_param` VARCHAR(9), `codigocurso_param` INT, `admitido_param` INT)   BEGIN
    DECLARE puntos_param INT;

    -- Obtener puntos del solicitante
    SELECT puntos INTO puntos_param
    FROM solicitantes
    WHERE dni = dni_param;

    -- Insertar o actualizar en la tabla resultados
    INSERT INTO resultados (dni, solicitudes_admitido, puntos)
    VALUES (dni_param, admitido_param, puntos_param)
    ON DUPLICATE KEY UPDATE solicitudes_admitido = admitido_param;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(9) DEFAULT NULL,
  `contrasena` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `dni`, `nombre`, `contrasena`) VALUES
(1, '42214665M', 'Cristina', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `abierto` tinyint(1) DEFAULT NULL,
  `numeroplazas` int(2) DEFAULT NULL,
  `plazoinscripcion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`codigo`, `nombre`, `abierto`, `numeroplazas`, `plazoinscripcion`) VALUES
(1, 'PHP Avanzado', 1, 11, '2024-01-16'),
(2, 'PHP Básico', 1, 10, '2024-01-22'),
(3, 'PHP E-commerce', 1, 10, '2024-01-21'),
(36, 'Symfony Avanzado', 1, 6, '2024-01-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados`
--

CREATE TABLE `resultados` (
  `dni` varchar(9) NOT NULL,
  `solicitudes_admitido` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resultados`
--

INSERT INTO `resultados` (`dni`, `solicitudes_admitido`, `puntos`) VALUES
('09076123R', 1, 13),
('09076123R', 1, 13),
('234567890', 1, 9),
('123456789', 1, 10),
('77359169A', 1, 14),
('012345678', 1, 10),
('987654321', 1, 4),
('77359169A', 1, 14),
('09076123R', 1, 13),
('123456789', 1, 10),
('012345678', 1, 10),
('234567890', 1, 9),
('987654321', 1, 4);

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
  `coordinadortic` tinyint(1) DEFAULT NULL,
  `grupotic` tinyint(1) DEFAULT NULL,
  `nombregrupo` varchar(5) DEFAULT NULL,
  `pbilin` tinyint(1) DEFAULT NULL,
  `cargo` tinyint(1) DEFAULT NULL,
  `nombrecargo` varchar(20) DEFAULT NULL,
  `situacion` enum('activo','inactivo') DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL,
  `especialidad` varchar(50) DEFAULT NULL,
  `puntos` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitantes`
--

INSERT INTO `solicitantes` (`dni`, `apellidos`, `nombre`, `contrasena`, `telefono`, `correo`, `codigocentro`, `coordinadortic`, `grupotic`, `nombregrupo`, `pbilin`, `cargo`, `nombrecargo`, `situacion`, `fechaAlta`, `especialidad`, `puntos`) VALUES
('012345678', 'Romero', 'Sara', 'pass789', '333444555', 'sara.romero@email.com', 'CC012345', 1, 0, NULL, 1, 1, 'Director', 'activo', '2024-01-14', 'Database Developer', 10),
('09076123R', 'Sánchez Romero', 'Manuel', '1234', '666899078', 'falso2@gmail.com', 'F1234567', 1, 1, 'bbbb', 1, 0, NULL, 'activo', '2024-01-13', 'muchas', 13),
('123456789', 'Lopez', 'Maria', 'pass123', '987654321', 'maria.lopez@email.com', 'CC123456', 1, 0, NULL, 1, 1, 'Director', 'activo', '2024-01-14', 'IT Specialist', 10),
('234567890', 'Rodriguez', 'Pedro', 'pass789', '555666777', 'pedro.rodriguez@email.com', 'CC345678', 0, 1, 'GRP02', 1, 1, 'Secretario', 'activo', '2024-01-14', 'Network Engineer', 9),
('345678901', 'Martinez', 'Laura', 'passabc', '111222333', 'laura.martinez@email.com', 'CC456789', 1, 1, 'aaaa', 1, 1, 'Director', 'activo', '2005-01-03', 'Database Administrator', 8),
('456789012', 'Sanchez', 'Ana', 'pass456', '999000111', 'ana.sanchez@email.com', 'CC678901', 0, 1, 'GRP03', 1, 0, NULL, 'activo', '2024-01-14', 'Security Specialist', 9),
('567890123', 'Lopez', 'Isabel', 'pass123', '222333444', 'isabel.lopez@email.com', 'CC890123', 1, 0, NULL, 1, 1, 'Secretario', 'activo', '2024-01-14', 'IT Director', 10),
('678901234', 'Perez', 'Miguel', 'passabc', '666777888', 'miguel.perez@email.com', 'CC901234', 0, 1, 'GRP04', 0, 1, 'Jefe de Departamento', 'activo', '2024-01-14', 'Software Architect', 4),
('77359169A', 'Hidalgo Cobo', 'Cristina', '1234', '670644812', 'chc0089@gmail.com', 'F3708638', 1, 1, 'aaa', 1, 1, 'Director', 'activo', '2009-01-06', 'aaaa', 14),
('789012345', 'Garcia', 'Carlos', 'passxyz', '444555666', 'carlos.garcia@email.com', 'CC567890', 1, 0, NULL, 0, 1, 'Executive', 'inactivo', '2024-01-14', 'Project Manager', 4),
('890123456', 'Fernandez', 'David', 'passxyz', '777888999', 'david.fernandez@email.com', 'CC789012', 1, 0, NULL, 0, 1, 'Jefe de Estudios', 'activo', '2024-01-14', 'System Administrator', 5),
('92045507J', 'Martínez Fernández', 'Jesús', '1234', '6677453219', 'jesus@gmail.com', 'F4567091', 1, 1, 'bbb', 1, 1, 'Director', 'activo', '2005-01-11', 'varias', 14),
('987654321', 'Gomez', 'Juan', 'pass456', '123456789', 'juan.gomez@email.com', 'CC789012', 0, 1, 'GRP01', 0, 1, 'Jefe de Estudios', 'activo', '2024-01-14', 'Software Engineer', 4);

--
-- Disparadores `solicitantes`
--
DELIMITER $$
CREATE TRIGGER `before_insert_calcularPuntos` BEFORE INSERT ON `solicitantes` FOR EACH ROW BEGIN
    DECLARE puntos INT;

    -- Lógica para calcular puntos
    SELECT
        SUM(CASE WHEN NEW.coordinadortic THEN 4 ELSE 0 END +
            CASE WHEN NEW.grupotic THEN 3 ELSE 0 END +
            CASE WHEN NEW.pbilin THEN 3 ELSE 0 END +
            CASE WHEN NEW.nombrecargo IN ('Director', 'Jefe de Estudios') THEN 2
                 WHEN NEW.nombrecargo = 'Secretario' THEN 2
                 WHEN NEW.nombrecargo = 'Jefe de Departamento' THEN 1
                 ELSE 0 END +
            CASE WHEN DATEDIFF(CURDATE(), NEW.fechaAlta) > 15 THEN 1 ELSE 0 END +
            CASE WHEN NEW.situacion = 'activo' THEN 1 ELSE 0 END)
    INTO puntos;

    -- Asignar los puntos al nuevo solicitante
    SET NEW.puntos = puntos;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `codigocurso` int(6) NOT NULL,
  `fechasolicitud` date DEFAULT NULL,
  `admitido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `dni`, `codigocurso`, `fechasolicitud`, `admitido`) VALUES
(30, '09076123R', 1, '2024-01-14', 1),
(31, '09076123R', 2, '2024-01-10', 1),
(32, '77359169A', 1, '2024-01-14', 1),
(33, '77359169A', 2, '2024-01-14', 0),
(34, '09076123R', 36, '2024-01-14', 1),
(35, '77359169A', 36, '2024-01-11', 1),
(36, '234567890', 36, '2024-01-14', 1),
(37, '92045507J', 36, '2024-01-14', 0),
(38, '012345678', 36, '2024-01-14', 1),
(39, '123456789', 36, '2024-01-13', 1),
(40, '456789012', 36, '2024-01-12', 0),
(41, '987654321', 36, '2024-01-14', 1);

--
-- Disparadores `solicitudes`
--
DELIMITER $$
CREATE TRIGGER `after_solicitudes_insert` AFTER INSERT ON `solicitudes` FOR EACH ROW BEGIN
    IF NEW.admitido = 1 THEN
        CALL ActualizarResultadosProcedure(NEW.dni, NEW.codigocurso, NEW.admitido);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_solicitudes_update` AFTER UPDATE ON `solicitudes` FOR EACH ROW BEGIN
    IF NEW.admitido = 1 THEN
      CALL ActualizarResultadosProcedure(NEW.dni, NEW.codigocurso, NEW.admitido);
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `resultados`
--
ALTER TABLE `resultados`
  ADD KEY `dni` (`dni`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigocurso` (`codigocurso`),
  ADD KEY `fk_solicitudes_solicitantes` (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `resultados`
--
ALTER TABLE `resultados`
  ADD CONSTRAINT `resultados_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `solicitantes` (`dni`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `fk_solicitudes_solicitantes` FOREIGN KEY (`dni`) REFERENCES `solicitantes` (`dni`),
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `solicitantes` (`dni`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`codigocurso`) REFERENCES `cursos` (`codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
