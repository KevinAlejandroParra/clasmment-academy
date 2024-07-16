-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2024 a las 23:28:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `classment_academy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_cursos` int(12) NOT NULL,
  `descripcion` varchar(999) NOT NULL,
  `precio_curso` decimal(6,0) NOT NULL,
  `profesor_curos` varchar(12) NOT NULL,
  `escuela_id_escuela` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas`
--

CREATE TABLE `escuelas` (
  `id_escuela` int(12) NOT NULL,
  `nombre_institucion` varchar(30) NOT NULL,
  `informacion` varchar(999) NOT NULL,
  `contacto` varchar(999) NOT NULL,
  `ubicacion` varchar(999) NOT NULL,
  `id_cursos` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion_pagos`
--

CREATE TABLE `facturacion_pagos` (
  `id_factura` int(12) NOT NULL,
  `descripcion` varchar(999) NOT NULL,
  `medio` varchar(30) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `no_usuario` int(12) NOT NULL,
  `precio_total` decimal(6,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id_matricula` int(12) NOT NULL,
  `precio_total` decimal(6,0) NOT NULL,
  `fecha_matricula` datetime DEFAULT NULL,
  `id_escuela` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `codigo_pago` char(8) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`codigo_pago`, `descripcion`) VALUES
('15469', 'pago en efectivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(12) NOT NULL,
  `identificador` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `identificador`) VALUES
(1, 'administrador'),
(2, 'administrador_escuela'),
(3, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `no_user` int(12) NOT NULL,
  `nombre_completo_user` tinytext NOT NULL,
  `email` varchar(30) NOT NULL,
  `numero_telefono` int(10) NOT NULL,
  `genero` tinytext DEFAULT NULL,
  `direccion_residencia` varchar(30) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `id_rol` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_cursos`);

--
-- Indices de la tabla `escuelas`
--
ALTER TABLE `escuelas`
  ADD PRIMARY KEY (`id_escuela`);

--
-- Indices de la tabla `facturacion_pagos`
--
ALTER TABLE `facturacion_pagos`
  ADD PRIMARY KEY (`id_factura`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id_matricula`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`codigo_pago`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`no_user`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;


INSERT INTO `cursos` (`id_cursos`, `descripcion`, `precio_curso`, `profesor_curos`, `escuela_id_escuela`) VALUES
(1, 'Asesoria nutricional', 50000, 'JuanPerez', 1),
(2, 'Taekwondo', 60000, 'MariaLopez', 2),
(3, 'Master Class', 70000, 'Alejandro Cifuentes', 3)

INSERT INTO `escuelas` (`id_escuela`, `nombre_institucion`, `informacion`, `contacto`, `ubicacion`, `id_cursos`) VALUES
(1, 'GO FIT', 'Asesoria nutricional y entrenadores certificados a tu disposición.', 'contacto@gofit.com', 'Calle bosa centro', NULL),
(2, 'Taekwondo free', 'Cursos de artes marciales para todas las edades.', 'info@escuelatk.com', 'Avenida Siempre Viva 456', NULL),
(3, 'Centro de Marketing', 'Cursos de marketing y negocios.', 'admin@centrodemarketing.com', 'Boulevard 789', NULL);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


